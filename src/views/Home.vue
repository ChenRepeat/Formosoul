<template>
  <div id="home-container" ref="containerRef">
    <canvas id="home-canvas-back" ref="canvasBackRef"></canvas>
    
    <div id="home-logo-wrapper" ref="logoWrapperRef">
      <img id="home-logo-img" src="/Home/home-logo.svg" alt="Formosoul Logo" />
      <div id="home-socket-visual" ref="socketVisualRef"></div>
    </div>

    <canvas id="home-canvas-front" ref="canvasFrontRef"></canvas>

    <div id="home-letter-overlay" ref="letterOverlayRef">
      <div id="home-envelope-container" ref="envelopeContainerRef">
        <div class="home-envelope-body"></div>
        <div class="home-envelope-flap"></div>
        <div class="home-wax-seal"></div>
      </div>
    </div>

    <Transition name="home-fade">
      <AdmissionLetter 
        v-if="showLetter" 
        @close="onLetterClose" 
      />
    </Transition>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import * as THREE from 'three';
import gsap from 'gsap';
import AdmissionLetter from '@/components/Home/AdmissionLetter.vue';

// --- Refs ---
const containerRef = ref(null);
const canvasBackRef = ref(null);
const canvasFrontRef = ref(null);
const logoWrapperRef = ref(null);
const socketVisualRef = ref(null);
const letterOverlayRef = ref(null);
const envelopeContainerRef = ref(null);

// --- State ---
const showLetter = ref(false);

// --- Variables ---
let scene, camera, rendererBack, rendererFront;
let animationId;
let isDragging = false;
let draggedSnitchIdx = -1;
let raycaster, mouse, dragPlane, dragOffset;
let clock;

// 記錄點擊起始位置
let clickStartPos = { x: 0, y: 0 };

const snitches = [];
const snitchCount = 10;

// --- Helper ---
const checkIsMobile = () => window.innerWidth < 768;

function getClientPos(event) {
  if (event.changedTouches && event.changedTouches.length > 0) {
    return { x: event.changedTouches[0].clientX, y: event.changedTouches[0].clientY };
  }
  return { x: event.clientX, y: event.clientY };
}

function getHoleScreenPos() {
  if (!logoWrapperRef.value) return { x: 0, y: 0 };
  const logoWidth = logoWrapperRef.value.clientWidth;
  const logoHeight = logoWrapperRef.value.clientHeight;
  const centerX = window.innerWidth / 2;
  const centerY = window.innerHeight / 2;
  const offsetX = (0.72 - 0.5) * logoWidth;
  const offsetY = (0.75 - 0.5) * logoHeight;
  return { x: centerX + offsetX, y: centerY + offsetY };
}

function getHoleWorldPos() {
  const screenPos = getHoleScreenPos();
  const ndcX = (screenPos.x / window.innerWidth) * 2 - 1;
  const ndcY = -(screenPos.y / window.innerHeight) * 2 + 1;
  const vec = new THREE.Vector3(ndcX, ndcY, 0.5);
  vec.unproject(camera);
  const dir = vec.sub(camera.position).normalize();
  const dist = -camera.position.z / dir.z;
  return camera.position.clone().add(dir.multiplyScalar(dist));
}

function getScreenDistanceToHole(worldPos) {
  const vector = worldPos.clone().project(camera);
  const snitchX = ((vector.x + 1) / 2) * window.innerWidth;
  const snitchY = ((-vector.y + 1) / 2) * window.innerHeight;
  const holePos = getHoleScreenPos();
  return Math.sqrt(Math.pow(snitchX - holePos.x, 2) + Math.pow(snitchY - holePos.y, 2));
}

function updateDockedSnitchLock() {
  const hero = snitches[0];
  if (!hero || !hero.isDocked) return;
  const newPos = getHoleWorldPos();
  hero.lockedPosition.copy(newPos);
  hero.group.position.copy(newPos);
}

// ★★★ 修正 1：統一數學邏輯並加入 idx 參數 ★★★
// 取得目前的理論軌道位置 (不包含 Offset)
function getOrbitPosition(s, t, idx) {
  const zNarrowFactor = 0.6; // 用於縮放 z 軸，讓它變成橢圓，避免飛出螢幕

  const currentAngle = t * s.speed + s.phase;
  const rNow = s.radius; 
  
  // 計算橢圓軌道 (縮放 z，與 animate 邏輯一致)
  const xBase = rNow * Math.cos(currentAngle);
  const zBase = rNow * Math.sin(currentAngle)* zNarrowFactor;
  
  // Y 軸波動
  const waveY = Math.sin(currentAngle * s.yFreq + s.phase) * s.yAmp;
  // 使用 idx 確保浮動計算與 animate 迴圈一致
  const floatY = Math.sin(t * 1.5 + idx) * 0.15; 
  const yTotal = waveY + floatY;

  return new THREE.Vector3(xBase, yTotal, zBase);
}

function getIntersects(event) {
  const pos = getClientPos(event);
  mouse.x = (pos.x / window.innerWidth) * 2 - 1;
  mouse.y = -(pos.y / window.innerHeight) * 2 + 1;
  raycaster.setFromCamera(mouse, camera);

  const originalVisibilities = snitches.map(s => s.group.visible);
  snitches.forEach(s => s.group.visible = true); 
  
  const targets = snitches.map((s) => s.group);
  const intersects = raycaster.intersectObjects(targets, true);

  snitches.forEach((s, i) => s.group.visible = originalVisibilities[i]);
  return intersects;
}

// --- Interaction Logic ---

const onLetterClose = () => {
  showLetter.value = false;
  if (letterOverlayRef.value) letterOverlayRef.value.style.display = 'none';
  if (envelopeContainerRef.value) {
    envelopeContainerRef.value.style.opacity = '0';
    envelopeContainerRef.value.classList.remove('home-fade-out');
    envelopeContainerRef.value.classList.remove('home-open');
  }
  const hero = snitches[0];
  if (hero) {
    hero.isDocked = true; 
    hero.isFront = true; 
    if (hero.heroLight) hero.heroLight.intensity = 4.6; 
  }
  if (socketVisualRef.value) socketVisualRef.value.classList.add('home-docked');
};

function onDragStart(event) {
  if (!containerRef.value || !containerRef.value.contains(event.target)) return;
  if (showLetter.value) return;

  const pos = getClientPos(event);
  clickStartPos = { x: pos.x, y: pos.y };

  if (snitches[0] && snitches[0].isDocked) {
      // 允許點擊選單，不 return
  }

  const intersects = getIntersects(event);

  if (intersects.length > 0) {
    let obj = intersects[0].object;
    
    // 檢查 draggable / link
    let draggableFound = false;
    let tempObj = obj;
    while (tempObj && tempObj.parent) {
        if (tempObj.userData.draggable || tempObj.userData.isLink) {
            draggableFound = true;
            break;
        }
        tempObj = tempObj.parent;
    }
    
    // 找到 Group Root
    let rootGroup = intersects[0].object;
    while (rootGroup.parent && rootGroup.parent.type !== 'Scene') {
      rootGroup = rootGroup.parent;
    }

    const idx = snitches.findIndex((s) => s.group === rootGroup);
    
    if (idx !== -1) {
        // Hero 特殊保護
        if (idx === 0) {
            if (snitches[0].isDocked) return;
            if (!draggableFound) return; 
        }

        if (event.cancelable) event.preventDefault();
        
        draggedSnitchIdx = idx;
        isDragging = true;
        containerRef.value.classList.add('home-dragging');
        document.body.style.cursor = 'grabbing';

        if (idx === 0 && socketVisualRef.value) {
           socketVisualRef.value.classList.add('home-active');
        }
        
        gsap.killTweensOf(snitches[idx].returningOffset);

        const p = intersects[0].point;
        dragPlane.setFromNormalAndCoplanarPoint(
          camera.getWorldDirection(new THREE.Vector3()),
          p
        );
        dragOffset.copy(p).sub(snitches[idx].group.position);
    }
  }
}

function onDragMove(event) {
  if (!isDragging || draggedSnitchIdx === -1) return;
  if (event.cancelable) event.preventDefault();

  const pos = getClientPos(event);
  mouse.x = (pos.x / window.innerWidth) * 2 - 1;
  mouse.y = -(pos.y / window.innerHeight) * 2 + 1;
  
  raycaster.setFromCamera(mouse, camera);
  const intersection = new THREE.Vector3();
  raycaster.ray.intersectPlane(dragPlane, intersection);
  
  if (intersection) {
    snitches[draggedSnitchIdx].group.position.copy(
      intersection.sub(dragOffset)
    );
  }
}

function onDragEnd() {
  if (isDragging) {
    document.body.style.cursor = 'default';
    setTimeout(() => {}, 50);

    if (draggedSnitchIdx === 0) {
      const hero = snitches[0];
      const dist = getScreenDistanceToHole(hero.group.position);
      
      // 判定範圍維持 80 (或你設定的 50)
      if (dist < 80) {
        const targetPos = getHoleWorldPos();
        hero.isDocked = true;
        hero.lockedPosition = targetPos.clone();
        hero.group.position.copy(targetPos);
        hero.group.position.z = targetPos.z;
        hero.group.lookAt(0, 0, 0);
        hero.isFront = true;

        if (hero.heroLight) hero.heroLight.intensity = 4.6;
        if (socketVisualRef.value) socketVisualRef.value.classList.add('home-docked');

        letterOverlayRef.value.style.display = 'flex';
        // eslint-disable-next-line no-unused-expressions
        letterOverlayRef.value.offsetHeight; 
        letterOverlayRef.value.style.opacity = '1';

        const tl = gsap.timeline();
        gsap.set(envelopeContainerRef.value, {
          top: '-50%', left: '50%', xPercent: -50, yPercent: -50,
          scale: 0.2, rotationX: 0, rotation: 5, opacity: 1, zIndex: 10,
        });
        tl.to(envelopeContainerRef.value, {
          top: '60%', scale: 0.8, rotationX: 70, rotation: 0,
          duration: 1.5, ease: 'power2.out',
        });
        tl.add(() => envelopeContainerRef.value.classList.add('home-open'), '+=0.1');
        tl.add(() => envelopeContainerRef.value.classList.add('home-fade-out'), '+=0.5');
        tl.add(() => {
           showLetter.value = true;
           letterOverlayRef.value.style.display = 'none';
        }, '+=0.5');

        if (socketVisualRef.value) socketVisualRef.value.classList.remove('home-active');
        isDragging = false;
        draggedSnitchIdx = -1;
        containerRef.value.classList.remove('home-dragging');
        return;
      }
      
      if (socketVisualRef.value) socketVisualRef.value.classList.remove('home-active');
    }

    // --- 平滑回歸邏輯 ---
    const s = snitches[draggedSnitchIdx];
    const t = clock.getElapsedTime();
    
    // ★★★ 修正 2：傳入 draggedSnitchIdx 以確保計算正確 ★★★
    const targetOrbitPos = getOrbitPosition(s, t, draggedSnitchIdx);
    
    s.returningOffset.copy(s.group.position).sub(targetOrbitPos);
    
    gsap.to(s.returningOffset, {
        x: 0, y: 0, z: 0,
        duration: 1.5,
        ease: "power2.out"
    });

    isDragging = false;
    draggedSnitchIdx = -1;
    containerRef.value.classList.remove('home-dragging');
  }
}

function onDocumentClick(event) {
  if (!containerRef.value || !containerRef.value.contains(event.target)) return;
  if (showLetter.value) return; 

  const pos = getClientPos(event);
  const dist = Math.sqrt(
    Math.pow(pos.x - clickStartPos.x, 2) + 
    Math.pow(pos.y - clickStartPos.y, 2)
  );
  if (dist > 5) return;

  const intersects = getIntersects(event);

  for (let i = 0; i < intersects.length; i++) {
    let obj = intersects[i].object;
    while (obj && !obj.userData.isLink && obj.parent) {
      obj = obj.parent;
    }
    if (obj && obj.userData.isLink) {
      document.body.style.cursor = 'wait';
      setTimeout(() => {
        document.body.style.cursor = 'default';
        window.location.href = obj.userData.url;
      }, 100);
      return;
    }
  }
}

function onMouseMoveHover(event) {
  if (!containerRef.value || !containerRef.value.contains(event.target)) {
     document.body.classList.remove('home-hover-link');
     document.body.style.cursor = 'default';
     return;
  }

  if (isDragging || showLetter.value) return;
  
  snitches.forEach((s) => {
    const lbl = s.group.getObjectByName('snitchLabel');
    if (lbl) lbl.visible = false;
  });

  const intersects = getIntersects(event);

  let hovering = false;
  if (intersects.length > 0) {
    let root = intersects[0].object;
    while (root.parent && root.parent.type !== 'Scene') {
      root = root.parent;
    }
    const lbl = root.getObjectByName('snitchLabel');
    if (lbl) lbl.visible = true;

    let obj = intersects[0].object;
    
    while (obj && !obj.userData.isLink && !obj.userData.draggable && obj.parent) {
      obj = obj.parent;
    }
    
    if (obj && (obj.userData.isLink || obj.userData.draggable)) {
        hovering = true;
    }
  }

  if (hovering) {
    document.body.classList.add('home-hover-link');
    document.body.style.cursor = 'pointer'; 
  } else {
    document.body.classList.remove('home-hover-link');
    document.body.style.cursor = 'default';
  }
}

function onWindowResize() {
  const w = window.innerWidth;
  const h = window.innerHeight;
  camera.aspect = w / h;
  camera.updateProjectionMatrix();
  rendererBack.setSize(w, h);
  rendererFront.setSize(w, h);

  const isMob = checkIsMobile();
  snitches.forEach((s) => {
    if (isMob) {
      s.radius = s.isHero ? 3.0 : (2.5 + s.randR); 
    } else {
      s.radius = s.isHero ? 4.0 : (3.5 + s.randR); 
    }
  });

  updateDockedSnitchLock();
}

// ★★★ 修正 3：Animate 函式完全平滑化 (Unified Math) ★★★
function animate() {
  animationId = requestAnimationFrame(animate);
  const t = clock.getElapsedTime();
  const duration = 3.0; 
  const u = Math.min(1, t / duration); // u 從 0 跑到 1
  const easeOutCubic = (x) => 1 - Math.pow(1 - x, 5);

  snitches.forEach((s, idx) => {
    if (s.isHero && s.isDocked) {
      if (s.lockedPosition) s.group.position.copy(s.lockedPosition);
      s.group.lookAt(0, 0, 0);
      if (s.heroLight) s.heroLight.intensity = 4.7;
      s.isFront = true;
      return;
    }

    if (isDragging && draggedSnitchIdx === idx) {
      const dragFlap = Math.sin(t * 37 + s.phase) * 0.84;
      s.wings[0].rotation.z = -0.5 + dragFlap;
      s.wings[1].rotation.z = 0.5 - dragFlap;
      s.wings[0].rotation.x = Math.cos(t * 37) * 0.25;
      s.wings[1].rotation.x = Math.cos(t * 37) * 0.25;
      s.isFront = s.group.position.z > 0;
      return;
    }

    // --- 統一飛行邏輯 (解決卡頓問題) ---
    
    // 1. 計算螺旋角度 (當 u=1 時，extraAngle 平滑變為 0)
    const spiralRotations = 3.0;
    const extraAngle = u < 1 ? -spiralRotations * Math.PI * 2 * (1 - easeOutCubic(u)) : 0;
    
    // 2. 基礎角度
    const currentAngle = t * s.speed + s.phase + extraAngle;
    
    // 3. 半徑展開 (隨著 u 變大，rNow 逐漸變成完整的 s.radius)
    const rNow = s.radius * easeOutCubic(u);
    
    // 4. 位置計算 (使用相同的縮放係數)
    const zNarrowFactor = 0.65; 
    const xBase = rNow * Math.cos(currentAngle)
    const zBase = rNow * Math.sin(currentAngle) * zNarrowFactor;
    
    // 5. Y 軸波動 + 展開
    const waveY = Math.sin(currentAngle * s.yFreq + s.phase) * s.yAmp;
    // 加入 idx 以確保數學運算一致
    const floatY = Math.sin(t * 1.5 + idx) * 0.15; 
    
    // Y 軸高度也隨著開場展開
    const yTotal = (waveY + floatY) * easeOutCubic(u);

    // 6. 最終賦值：數學軌道 + 回歸偏移量
    s.group.position.set(xBase, yTotal, zBase).add(s.returningOffset);
    
    s.group.lookAt(0, 0, 0);

    // 翅膀動畫
    const flap = Math.sin(t * s.flapSpeed + s.phase) * 0.5;
    s.wings[0].rotation.z = -0.5 + flap * 0.6;
    s.wings[1].rotation.z = 0.5 - flap * 0.6;
    s.wings[0].rotation.x = Math.cos(t * s.flapSpeed) * 0.15;
    s.wings[1].rotation.x = Math.cos(t * s.flapSpeed) * 0.15;
    
    s.isFront = s.group.position.z > 0;
  });

  snitches.forEach((s) => s.group.visible = !s.isFront);
  rendererBack.render(scene, camera);
  rendererFront.clear();
  snitches.forEach((s) => s.group.visible = s.isFront);
  rendererFront.render(scene, camera);
}

// --- Initialization ---
onMounted(() => {
  const width = window.innerWidth;
  const height = window.innerHeight;
  const textureLoader = new THREE.TextureLoader();

  scene = new THREE.Scene();
  camera = new THREE.PerspectiveCamera(55, width / height, 0.1, 100);
  camera.position.set(0, 0.8, 7);
  camera.lookAt(0, 0, 0);

  rendererBack = new THREE.WebGLRenderer({
    canvas: canvasBackRef.value, antialias: true, alpha: true,
  });
  rendererBack.setPixelRatio(window.devicePixelRatio);
  rendererBack.setSize(width, height);
  rendererBack.setClearColor(0x000000, 0);

  rendererFront = new THREE.WebGLRenderer({
    canvas: canvasFrontRef.value, antialias: true, alpha: true,
  });
  rendererFront.setPixelRatio(window.devicePixelRatio);
  rendererFront.setSize(width, height);
  rendererFront.setClearColor(0x000000, 0);
  rendererFront.autoClear = false;

  scene.add(new THREE.HemisphereLight(0xffffff, 0x444466, 0.9));
  const dirLight = new THREE.DirectionalLight(0xffffff, 1.1);
  dirLight.position.set(5, 8, 4);
  scene.add(dirLight);

  initSnitches(textureLoader);

  raycaster = new THREE.Raycaster();
  mouse = new THREE.Vector2();
  dragPlane = new THREE.Plane();
  dragOffset = new THREE.Vector3();
  clock = new THREE.Clock();

  window.addEventListener('mousedown', onDragStart);
  window.addEventListener('mousemove', onDragMove);
  window.addEventListener('mouseup', onDragEnd);
  window.addEventListener('click', onDocumentClick);
  window.addEventListener('touchstart', onDragStart, { passive: false });
  window.addEventListener('touchmove', onDragMove, { passive: false });
  window.addEventListener('touchend', onDragEnd);
  window.addEventListener('resize', onWindowResize);
  window.addEventListener('mousemove', onMouseMoveHover);

  animate();
});

onUnmounted(() => {
  cancelAnimationFrame(animationId);
  window.removeEventListener('mousedown', onDragStart);
  window.removeEventListener('mousemove', onDragMove);
  window.removeEventListener('mouseup', onDragEnd);
  window.removeEventListener('click', onDocumentClick);
  window.removeEventListener('touchstart', onDragStart);
  window.removeEventListener('touchmove', onDragMove);
  window.removeEventListener('touchend', onDragEnd);
  window.removeEventListener('resize', onWindowResize);
  window.removeEventListener('mousemove', onMouseMoveHover);
});

function initSnitches(loader) {
  const heroBallMaterial = new THREE.MeshStandardMaterial({
    color: 0xffec7e, metalness: 1, roughness: 0.07, emissive: 0xffe400, emissiveIntensity: 0.62,
  });
  const ballGeometry = new THREE.SphereGeometry(0.12, 32, 32);

  const featherMaterial = new THREE.MeshStandardMaterial({
    color: 0xe8e6dc, metalness: 0.4, roughness: 0.6, side: THREE.DoubleSide,
  });
  const featherGeometry = new THREE.BoxGeometry(0.03, 0.42, 0.005);

  const createWing = (side = 1) => {
    const wingGroup = new THREE.Group();
    const featherCount = 10;
    const spread = THREE.MathUtils.degToRad(35);
    for (let i = 0; i < featherCount; i++) {
      const feather = new THREE.Mesh(featherGeometry, featherMaterial);
      feather.castShadow = true;
      feather.receiveShadow = true;
      feather.raycast = () => {};
      const t = i / (featherCount - 1);
      const angle = (t - 0.5) * spread;
      feather.scale.y = 0.6 + 0.4 * Math.sin(t * Math.PI);
      feather.position.y = (t - 0.4) * 0.35;
      feather.position.x = -(t * 0.1) * side;
      feather.rotation.z = angle * side * 1.2;
      feather.rotation.x = t * 0.2 * side;
      wingGroup.add(feather);
    }
    wingGroup.position.x = 0.05 * side; 
    return wingGroup;
  };

  const createTextLabel = (text) => {
    const cvs = document.createElement('canvas');
    const ctx = cvs.getContext('2d');
    cvs.width = 512;
    cvs.height = 128;
    ctx.font = 'bold 48px Georgia, serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.shadowColor = 'rgba(0,0,0,0.8)';
    ctx.shadowBlur = 5;
    ctx.shadowOffsetX = 2;
    ctx.shadowOffsetY = 2;
    ctx.fillStyle = '#ffffff';
    ctx.fillText(text, 256, 64);
    ctx.strokeStyle = '#ffd700';
    ctx.lineWidth = 2.5;
    ctx.strokeText(text, 256, 64);
    const tex = new THREE.CanvasTexture(cvs);
    const mat = new THREE.SpriteMaterial({ map: tex, transparent: true });
    const sprite = new THREE.Sprite(mat);
    sprite.scale.set(1.5, 0.375, 1);
    return sprite;
  };

  const createSnitch = (isHero, linkData) => {
    const group = new THREE.Group();
    let bodyMesh;

    if (isHero) {
      bodyMesh = new THREE.Mesh(ballGeometry, heroBallMaterial);
      bodyMesh.castShadow = true;
      bodyMesh.receiveShadow = true;
      bodyMesh.raycast = () => {}; 
      group.add(bodyMesh);

      const heroLight = new THREE.PointLight(0xffe400, 2.8, 2.3);
      group.add(heroLight);
      group.scale.set(1.6, 1.6, 1.6);

      const hitGeo = new THREE.SphereGeometry(0.25, 16, 16);
      const hitMat = new THREE.MeshBasicMaterial({ visible: false });
      const hitMesh = new THREE.Mesh(hitGeo, hitMat);
      hitMesh.userData = { isHero: true, draggable: true };
      group.add(hitMesh);

    } else {
      if (linkData && linkData.img) {
        const tex = loader.load(linkData.img);
        tex.colorSpace = THREE.SRGBColorSpace;
        const iconMat = new THREE.MeshBasicMaterial({
          map: tex, transparent: true, side: THREE.DoubleSide, depthWrite: false,
        });
        const iconGeo = new THREE.PlaneGeometry(0.5, 0.5);
        bodyMesh = new THREE.Mesh(iconGeo, iconMat);
        group.add(bodyMesh);

        const hitGeo = new THREE.SphereGeometry(0.35, 16, 16);
        const hitMat = new THREE.MeshBasicMaterial({ visible: false }); 
        const hitMesh = new THREE.Mesh(hitGeo, hitMat);
        hitMesh.userData = { url: linkData.url, isLink: true };
        group.add(hitMesh);

        if (linkData.name) {
          const lbl = createTextLabel(linkData.name);
          lbl.position.set(0, -0.4, 0);
          lbl.visible = false;
          lbl.name = 'snitchLabel';
          group.add(lbl);
        }
      } else {
        bodyMesh = new THREE.Mesh(new THREE.SphereGeometry(0.1), new THREE.MeshBasicMaterial({visible:false}));
        group.add(bodyMesh);
      }
    }

    const lWing = createWing(-1);
    const rWing = createWing(1);
    lWing.rotation.z = Math.PI / 2;
    rWing.rotation.z = -Math.PI / 2;

    const lAlign = new THREE.Group();
    lAlign.add(lWing);
    const rAlign = new THREE.Group();
    rAlign.add(rWing);

    let wingPos = isHero ? { x: 0.1, y: 0, z: 0 } : { x: 0.3, y: -0.1, z: -0.05 };
    lAlign.rotation.x = Math.PI / 2;
    lAlign.position.set(-wingPos.x, wingPos.y, wingPos.z);
    rAlign.rotation.x = Math.PI / 2;
    rAlign.position.set(wingPos.x, wingPos.y, wingPos.z);

    const lPivot = new THREE.Group();
    lPivot.add(lAlign);
    const rPivot = new THREE.Group();
    rPivot.add(rAlign);

    group.add(lPivot);
    group.add(rPivot);

    const heroLight = isHero ? group.children.find(c => c.isPointLight) : null;
    return { group, wings: [lPivot, rPivot], heroLight };
  };

  const isMobile = checkIsMobile();
  let menuIdx = 0;
  
  const menuItemsData = [
    { name: 'Classes', img: '/Home/home-class-book.png', url: '/classes' },
    { name: 'Professors', img: '/Home/home-professor-people.png', url: '/professorsintroduction' },
    { name: 'News', img: '/Home/home-news-owl.png', url: '/news' },
    { name: 'Shop', img: '/Home/home-shopping-money.png', url: '/shop' },
    { name: 'Annual Event', img: '/Home/home-annual-lantern.png', url: '/annualevent' },
    { name: 'About', img: '/Home/home-about-badge.png', url: '/about' },
    { name: 'Survival Guide', img: '/Home/home-survival-compass.png', url: '/survivalguide' },
    { name: 'Policy', img: '/Home/home-policy-scroll.png', url: '/policy' },
    { name: 'Admin', img: '/Home/home-admin-tools.png', url: '/admin' },
  ];

  const angleStep = (Math.PI * 2) / snitchCount;

  for (let i = 0; i < snitchCount; i++) {
    const isHero = i === 0;
    let linkData = null;
    if (!isHero && menuIdx < menuItemsData.length) {
      linkData = menuItemsData[menuIdx];
      menuIdx++;
    }
    const snitch = createSnitch(isHero, linkData);

    const randR = isHero ? 0 : Math.random() * 1.2; 
    let radius = isMobile 
        ? (isHero ? 3.0 : 2.5 + randR)
        : (isHero ? 4.0 : 3.5 + randR);

    const yAmp = isHero ? 0.3 : 0.8 + Math.random() * 1.2;
    const yFreq = 1 + Math.random() * 1.5;
    const speed = 0.2 + Math.random() * 0.15;

    snitches.push({
      group: snitch.group,
      wings: snitch.wings,
      heroLight: snitch.heroLight,
      radius,
      randR,
      yAmp,
      yFreq,
      speed,
      flapSpeed: isHero ? 12 : 10 + Math.random() * 5,
      phase: i * angleStep,
      isFront: false,
      isDocked: false,
      lockedPosition: null,
      isHero,
      returningOffset: new THREE.Vector3(0, 0, 0),
    });
    scene.add(snitch.group);
  }
}
</script>

<style lang="scss" scoped>
:global(body.home-hover-link) {
  cursor: pointer !important;
}

#home-container {
  width: 100%;
  height: 100vh;
  position: relative;
  overflow: hidden;
  touch-action: none;
  background: $color-fsTitle;
}
canvas {
  display: block;
  position: absolute;
  inset: 0;
  transition: z-index 0s;
}
#home-canvas-back {
  z-index: 0;
}
#home-canvas-front {
  z-index: 2;
  pointer-events: none;
}

#home-logo-wrapper {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) scale(0);
  height: auto;
  z-index: 1;
  pointer-events: none;
  width: 811px;
  max-width: 45vw;
  animation: home-expandFromPoint 3s
    cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
  opacity: 0;
}
#home-logo-img {
  display: block;
  width: 100%;
  height: auto;
}
@keyframes home-expandFromPoint {
  0% {
    opacity: 0;
    transform: translate(-50%, -50%) scale(0.05);
    filter: blur(10px) brightness(2);
  }
  // 40% {
  //   opacity: 1;
  // }
  // 60% {
  //  opacity: 1;
  // }
  100% {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
    filter: blur(0px) brightness(1);
  }
}

#home-socket-visual {
  position: absolute;
  left: 73%;
  top: 75%;
  transform: translate(-50%, -50%);
  width: 5%;
  aspect-ratio: 1 / 1;
  border-radius: 50%;
  background: radial-gradient(
    circle,
    rgba(0, 0, 0, 0.6) 0%,
    rgba(0, 0, 0, 0) 70%
  );
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
  transition: all 0.4s ease-out;
  z-index: 5;
  pointer-events: none;
}
@media (max-width: 768px) {
  #home-socket-visual {
    width: 12%;
  }
}
#home-socket-visual.home-active {
  background: radial-gradient(
    circle,
    rgba(255, 255, 255, 1) 10%,
    rgba(255, 230, 100, 0.9) 30%,
    rgba(255, 200, 0, 0.4) 50%,
    rgba(255, 200, 0, 0) 70%
  );
  border-color: rgba(255, 255, 255, 0.9);
  transform: translate(-50%, -50%) scale(1.3);
  box-shadow:
    0 0 20px rgba(255, 255, 255, 0.8),
    0 0 40px rgba(255, 215, 0, 0.6),
    0 0 80px rgba(255, 100, 0, 0.4),
    0 0 120px rgba(255, 255, 255, 0.2);
}
#home-socket-visual::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 140%;
  height: 140%;
  border-radius: 50%;
  background: radial-gradient(
    circle,
    rgba(255, 220, 100, 0.3),
    transparent 70%
  );
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.3s;
  z-index: -1;
}
#home-socket-visual.home-active::after {
  opacity: 1;
  animation: home-glowBreathe 2s ease-in-out infinite alternate;
}
@keyframes home-glowBreathe {
  0% {
    transform: translate(-50%, -50%) scale(1);
    opacity: 0.5;
  }
  100% {
    transform: translate(-50%, -50%) scale(1.3);
    opacity: 0.8;
  }
}
#home-socket-visual.home-docked {
  background: #fff;
  border-color: #fff;
  transform: translate(-50%, -50%) scale(0.8);
  box-shadow:
    0 0 60px rgba(255, 255, 255, 1),
    0 0 120px rgba(255, 215, 0, 0.8);
  transition:
    transform 0.2s,
    background 0.2s,
    box-shadow 0.2s;
}
#home-socket-visual.home-docked::after {
  opacity: 0;
  transition: opacity 0.1s;
}

#home-letter-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.75);
  z-index: 100;
  display: none;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.5s ease-out;
  perspective: 1200px;
}
#home-letter-overlay.home-show {
  display: flex;
}

#home-envelope-container {
  position: absolute;
  width: 340px;
  height: 240px;
  cursor: pointer;
  z-index: 10;
  opacity: 0;
  transform-style: preserve-3d;
}
.home-envelope-body {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 100%;
  background: #d4b896;
  border-radius: 5px;
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6);
  z-index: 10;
  overflow: hidden;
}
.home-envelope-body::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 120px 170px 0 170px;
  border-color:
    transparent #e0ccb3 #e0ccb3 transparent;
  z-index: 11;
  pointer-events: none;
}
.home-envelope-body::before {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 0 170px 130px 170px;
  border-color:
    transparent transparent #c9ad8a transparent;
  z-index: 12;
  pointer-events: none;
}
.home-envelope-flap {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 120px;
  background: #bf9e7a;
  clip-path: polygon(0 0, 100% 0, 50% 100%);
  transform-origin: top center;
  transition: transform 0.8s
    cubic-bezier(0.4, 0, 0.2, 1);
  z-index: 15;
}
.home-wax-seal {
  position: absolute;
  top: 100px;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 55px;
  height: 55px;
  background: radial-gradient(
    circle,
    #8b0000 0%,
    #5c0000 100%
  );
  border-radius: 50%;
  border: 2px solid #6b0000;
  z-index: 16;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.4);
  transition:
    transform 0.4s,
    opacity 0.4s;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ffd700;
  font-family: Georgia, serif;
  font-weight: bold;
  font-size: 26px;
  text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
}
.home-wax-seal::after {
  content: 'F';
}
#home-envelope-container.home-open .home-envelope-flap {
  transform: rotateX(180deg);
  z-index: 5;
}
#home-envelope-container.home-open .home-wax-seal {
  opacity: 0;
  transform: translate(-50%, -50%) scale(0.5);
}
#home-envelope-container.home-fade-out {
  opacity: 0 !important;
  pointer-events: none;
  transition: opacity 0.5s ease-out;
}

.home-fade-enter-active,
.home-fade-leave-active {
  transition: opacity 0.5s ease;
}
.home-fade-enter-from,
.home-fade-leave-to {
  opacity: 0;
}
</style>
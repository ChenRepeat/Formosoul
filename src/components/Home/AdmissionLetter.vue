<template>
  <div ref="canvasContainer" class="home-canvas-container"></div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from "vue";
import * as THREE from "three";
import gsap from "gsap";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/autoStore";
import { useI18n } from 'vue-i18n';

// 1. 引入 i18n
const { t, tm, locale } = useI18n();

const emit = defineEmits(['close']);
const router = useRouter();
const authStore = useAuthStore();
const canvasContainer = ref(null);

// 全域變數宣告
let scene, camera, renderer, animationId;
let letterMesh, ashSystem;
let material, particleMat;
let resizeHandler, clickHandler, mouseMoveHandler;

let currentButtonZones = {}; 
function createPaperTexture(isLoggedIn) {
  const cvs = document.createElement("canvas");
  cvs.width = 1400;
  cvs.height = 1050;
  const ctx = cvs.getContext("2d");

  ctx.fillStyle = "#F4E4BC";
  ctx.fillRect(0, 0, cvs.width, cvs.height);
  const gradient = ctx.createRadialGradient(
    cvs.width / 2, cvs.height / 2, 400,
    cvs.width / 2, cvs.height / 2, 800
  );
  gradient.addColorStop(0, "rgba(139, 69, 19, 0)");
  gradient.addColorStop(1, "rgba(139, 69, 19, 0.1)");
  ctx.fillStyle = gradient;
  ctx.fillRect(0, 0, cvs.width, cvs.height);

  for (let i = 0; i < 40000; i++) {
    ctx.fillStyle = Math.random() > 0.5 ? "rgba(139, 69, 19, 0.05)" : "rgba(255,255,255,0.1)";
    ctx.fillRect(Math.random() * cvs.width, Math.random() * cvs.height, 2, 2);
  }

  const paddingX = 80;
  const startX = paddingX;
  const contentWidth = cvs.width - paddingX * 2;
  let currentY = 100;

  function wrapText(context, text, x, y, maxWidth, lineHeight, marginBottom) {
    const words = text.split(" ");
    let line = "";
    for (let n = 0; n < words.length; n++) {
      const testLine = line + words[n] + " ";
      const metrics = context.measureText(testLine);
      if (metrics.width > maxWidth && n > 0) {
        context.fillText(line, x, y);
        line = words[n] + " ";
        y += lineHeight;
      } else {
        line = testLine;
      }
    }
    context.fillText(line, x, y);
    return y + lineHeight + marginBottom;
  }

  const zones = {};
  // Close Button
  zones.close = { x: cvs.width - 80, y: 20, w: 60, h: 60 };

  ctx.textAlign = "right";
  ctx.font = "bold 50px Arial, sans-serif";
  ctx.fillStyle = "#5a3a22";
  ctx.globalAlpha = 0.6;
  ctx.fillText("×", cvs.width - 40, 70);
  ctx.globalAlpha = 1.0;

  ctx.textAlign = "left";

  ctx.font = "56px 'Roboto' , 'Noto Sans TC', sans-serif";
  ctx.fillStyle = "#3e2723";
  ctx.fillText(t("mail.title1"), startX, currentY);
  currentY += 60;

  ctx.font = "56px 'Roboto', 'Noto Sans TC', sans-serif";
  ctx.fillStyle = "#5d4037";
  ctx.fillText(t("mail.title2"), startX, currentY);
  currentY += 35;

  ctx.beginPath();
  ctx.moveTo(startX, currentY);
  ctx.lineTo(startX + contentWidth, currentY);
  ctx.lineWidth = 2;
  ctx.strokeStyle = "rgba(90, 58, 34, 0.3)";
  ctx.stroke();
  currentY += 50;

  // --- Body ---
  ctx.font = "24px 'Roboto', 'Noto Sans TC', sans-serif";
  ctx.fillStyle = "#3e2723";
  const lineHeight = 40;
  const paraMargin = 25;

  const paragraphs = tm('mail.body'); 
  
  if (Array.isArray(paragraphs)) {
    paragraphs.forEach((text) => {
      currentY = wrapText(ctx, text, startX, currentY, contentWidth, lineHeight, paraMargin);
    });
  }

  // --- Footer ---
  const footerY = currentY + 30;
  ctx.font = "italic 24px 'Roboto', 'Noto Sans TC', sans-serif";
  ctx.fillStyle = "#3e2723";
  ctx.textAlign = "left";

  let sigY = footerY;
  ctx.fillText(t("mail.sigh1"), startX, sigY);
  sigY += 35;
  ctx.fillText(t("mail.sigh2"), startX, sigY);
  sigY += 35;
  ctx.fillText(t("mail.sigh3"), startX, sigY);

  // --- Buttons Layout ---
  const btnHeight = 64;
  const btnWidthReg = 240;
  const btnWidthAudit = 280;
  const btnGap = 30;
  const btnY = footerY + 20;
  const rightEdge = cvs.width - paddingX;

  const regBtnX = rightEdge - btnWidthReg;
  let auditBtnX;
  
  if (isLoggedIn) {
      auditBtnX = rightEdge - btnWidthAudit;
  } else {
      auditBtnX = regBtnX - btnGap - btnWidthAudit;
  }

  zones.audit = { x: auditBtnX, y: btnY, w: btnWidthAudit, h: btnHeight };
  const radius = 15;

  // Audit Button
  ctx.strokeStyle = "#5a3a22";
  ctx.lineWidth = 3;
  ctx.beginPath();
  ctx.roundRect(auditBtnX, btnY, btnWidthAudit, btnHeight, radius);
  ctx.stroke();

  ctx.font = "24px 'Roboto', 'Noto Sans TC', sans-serif";
  ctx.fillStyle = "#5a3a22";
  ctx.textAlign = "center";
  ctx.fillText(t("mail.auditBtn"), auditBtnX + btnWidthAudit / 2, btnY + 40);

  // Register Button (if not logged in)
  if (!isLoggedIn) {
      zones.register = { x: regBtnX, y: btnY, w: btnWidthReg, h: btnHeight };

      ctx.beginPath();
      ctx.roundRect(regBtnX, btnY, btnWidthReg, btnHeight, radius);
      ctx.fillStyle = "#FFCC46"; 
      ctx.fill();
      ctx.strokeStyle = "#b4941f"; 
      ctx.lineWidth = 3;
      ctx.stroke();

      ctx.font = "24px 'Roboto', 'Noto Sans TC', sans-serif";
      ctx.fillStyle = "#2c1e14"; 
      ctx.fillText("Entrance Ceremony", regBtnX + btnWidthReg / 2, btnY + 40);

      // 仙女棒 (保持不變)
      ctx.save();
      const sparklerX = regBtnX + btnWidthReg + 35; 
      const sparklerY = btnY + 25; 
      ctx.translate(sparklerX, sparklerY);
      ctx.scale(0.5, 0.5); 
      ctx.rotate(-8 * Math.PI / 180);
      ctx.strokeStyle = '#0a1a33';
      ctx.lineWidth = 5;
      ctx.lineCap = 'round';
      ctx.beginPath(); ctx.moveTo(0, 0); ctx.lineTo(30, 173); ctx.stroke();
      const drawLine = (x1, y1, x2, y2) => {
        ctx.beginPath(); ctx.moveTo(x1, y1); ctx.lineTo(x2, y2); ctx.stroke();
      };
      drawLine(0, -22, 0, -67); drawLine(-16, -16, -44, -44);
      drawLine(-22, 0, -60, 0); drawLine(-18, 16, -40, 36);  
      drawLine(16, -16, 44, -44); drawLine(22, 0, 60, 0); drawLine(17, 14, 40, 34);    
      ctx.restore();
  }

  return { texture: new THREE.CanvasTexture(cvs), zones };
}

function updateSceneTexture() {
  const isLoggedIn = !!authStore.token;
  
  const { texture, zones } = createPaperTexture(isLoggedIn);
  currentButtonZones = zones;
  if (material && material.uniforms.uTexture) {
    const oldTex = material.uniforms.uTexture.value;
    if (oldTex) oldTex.dispose();
    material.uniforms.uTexture.value = texture;
  }
  return texture;
}


watch([locale, () => authStore.token], () => {
  updateSceneTexture();
});

onMounted(async () => {
  try {
    await document.fonts.load('64px "Roboto"');
  } catch (err) {
    console.warn("Font load failed", err);
  }

  const width = window.innerWidth;
  const height = window.innerHeight;

  scene = new THREE.Scene();
  camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 100);
  camera.position.z = 4.8;

  renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
  renderer.setSize(width, height);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

  if (canvasContainer.value) {
    canvasContainer.value.appendChild(renderer.domElement);
  }

  const initialTexture = updateSceneTexture();

  const vertexShader = `
    varying vec2 vUv;
    void main() {
        vUv = uv;
        gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
    }
  `;

  const fragmentShader = `
    uniform float uTime;
    uniform float uProgress; 
    uniform sampler2D uTexture;
    varying vec2 vUv;
    float hash(vec2 p) { return fract(sin(dot(p, vec2(12.9898, 78.233))) * 43758.5453); }
    float noise(vec2 p) {
        vec2 i = floor(p);
        vec2 f = fract(p);
        f = f * f * (3.0 - 2.0 * f);
        return mix(mix(hash(i), hash(i + vec2(1.0, 0.0)), f.x),
                   mix(hash(i + vec2(0.0, 1.0)), hash(i + vec2(1.0, 1.0)), f.x), f.y);
    }
    float fbm(vec2 p) {
        float v = 0.0;
        float a = 0.5;
        for (int i = 0; i < 5; i++) {
            v += a * noise(p);
            p = p * 2.0 + 0.2;
            a *= 0.5;
        }
        return v;
    }
    void main() {
        vec2 uv = vUv;
        vec4 texColor = texture2D(uTexture, uv);
        float fireNoise = fbm(uv * 6.0 + vec2(0.0, uTime * 1.2)); 
        float gradient = uv.y; 
        float threshold = (uProgress * 1.5) - 0.2;
        float burnVal = gradient + fireNoise * 0.3; 
        float diff = burnVal - threshold;
        if (diff < 0.0) {
            discard; 
        } else if (diff < 0.15) {
            float t = diff / 0.15;
            vec3 fireCol = mix(vec3(4.0, 2.0, 0.5), vec3(1.0, 0.4, 0.0), t);
            fireCol = mix(fireCol, vec3(0.2, 0.0, 0.0), smoothstep(0.4, 1.0, t));
            gl_FragColor = vec4(fireCol, 1.0);
        } else if (diff < 0.25) {
            float t = (diff - 0.15) / 0.1;
            vec3 charCol = mix(vec3(0.0), texColor.rgb, t);
            gl_FragColor = vec4(charCol, 1.0);
        } else {
            gl_FragColor = texColor;
        }
    }
  `;

  const uniforms = {
    uTime: { value: 0 },
    uProgress: { value: 0 },
    uTexture: { value: initialTexture }, // 🔥 這裡使用初始產生的貼圖
  };

  material = new THREE.ShaderMaterial({
    vertexShader,
    fragmentShader,
    uniforms,
    side: THREE.DoubleSide,
    transparent: true,
  });

  const geometry = new THREE.PlaneGeometry(4.2, 3.15, 60, 45);
  letterMesh = new THREE.Mesh(geometry, material);
  scene.add(letterMesh);

  // --- 灰燼粒子系統 (保持不變) ---
  // ... 省略粒子系統代碼，不需要修改 ...
  const particleCount = 2000;
  const posArray = new Float32Array(particleCount * 3);
  const randomArray = new Float32Array(particleCount);
  const sizeArray = new Float32Array(particleCount);
  const geoHalfHeight = 3.15 / 2;
  for (let i = 0; i < particleCount; i++) {
    posArray[i * 3] = (Math.random() - 0.5) * 4.2;
    posArray[i * 3 + 1] = -geoHalfHeight - 0.2 + Math.random() * 0.5;
    posArray[i * 3 + 2] = (Math.random() - 0.5) * 0.5;
    randomArray[i] = Math.random();
    sizeArray[i] = Math.random();
  }
  const particlesGeo = new THREE.BufferGeometry();
  particlesGeo.setAttribute("position", new THREE.BufferAttribute(posArray, 3));
  particlesGeo.setAttribute("aRandom", new THREE.BufferAttribute(randomArray, 1));
  particlesGeo.setAttribute("aSize", new THREE.BufferAttribute(sizeArray, 1));
  particleMat = new THREE.ShaderMaterial({
    // ... Vertex/Fragment Shader 保持不變 ...
    vertexShader: `
        uniform float uTime;
        uniform float uBurnProgress; 
        attribute float aRandom;
        attribute float aSize;
        varying float vLife; 
        void main() {
            float originalY = position.y; 
            float burnHeight = (uBurnProgress * 3.15) - 1.575;
            vec3 pos = position;
            vLife = 0.0;
            if (burnHeight > originalY) {
                float flyTime = (burnHeight - originalY) * 2.0; 
                pos.y += flyTime * (0.5 + aRandom * 0.5); 
                pos.x += sin(flyTime * 2.0 + aRandom * 10.0) * 0.1;
                pos.z += cos(flyTime * 1.5 + aRandom) * 0.1;
                vLife = 1.0 - (flyTime * 0.4); 
            } else {
                vLife = 0.0; 
            }
            vec4 mvPosition = modelViewMatrix * vec4(pos, 1.0);
            gl_Position = projectionMatrix * mvPosition;
            gl_PointSize = (6.0 * aSize + 2.0) * vLife * (1.0 / -mvPosition.z);
        }
    `,
    fragmentShader: `
        varying float vLife;
        void main() {
            if (vLife <= 0.0) discard;
            vec2 uv = gl_PointCoord - 0.5;
            float dist = length(uv);
            if (dist > 0.5) discard;
            vec3 color = mix(vec3(1.0, 0.3, 0.0), vec3(1.0, 0.8, 0.4), vLife);
            float alpha = smoothstep(0.5, 0.0, dist) * vLife;
            gl_FragColor = vec4(color, alpha);
        }
    `,
    uniforms: {
      uTime: { value: 0 },
      uBurnProgress: { value: 0 },
    },
    transparent: true,
    depthWrite: false,
    blending: THREE.AdditiveBlending,
  });
  ashSystem = new THREE.Points(particlesGeo, particleMat);
  scene.add(ashSystem);


  // --- 互動邏輯 (Raycaster) ---
  const raycaster = new THREE.Raycaster();
  const mouse = new THREE.Vector2();
  let isBurned = false;

  function checkIntersection(clientX, clientY) {
    if (isBurned) return null;

    const rect = renderer.domElement.getBoundingClientRect();
    const x = clientX - rect.left;
    const y = clientY - rect.top;
    mouse.x = (x / rect.width) * 2 - 1;
    mouse.y = -(y / rect.height) * 2 + 1;

    raycaster.setFromCamera(mouse, camera);
    const intersects = raycaster.intersectObject(letterMesh);

    if (intersects.length > 0) {
      const uv = intersects[0].uv;
      const canvasX = uv.x * 1400;
      const canvasY = (1 - uv.y) * 1050;

      // 🔥 修改：改讀取全域的 currentButtonZones
      for (const [key, zone] of Object.entries(currentButtonZones)) {
        if (
          canvasX >= zone.x &&
          canvasX <= zone.x + zone.w &&
          canvasY >= zone.y &&
          canvasY <= zone.y + zone.h
        ) {
          return key;
        }
      }
    }
    return null;
  }

  function triggerBurn() {
    if (isBurned) return;
    isBurned = true;
    document.body.style.cursor = "default";
    uniforms.uProgress.value = 0;
    particleMat.uniforms.uBurnProgress.value = 0;
    const tl = gsap.timeline({ onComplete: () => { emit('close'); } });
    tl.to(uniforms.uProgress, {
      value: 1.5, duration: 3, ease: "none",
      onUpdate: () => { particleMat.uniforms.uBurnProgress.value = uniforms.uProgress.value; },
    });
    tl.to(letterMesh.position, { y: 0.3, z: -0.5, duration: 2.5, ease: "power1.out", }, "<");
  }

  clickHandler = (e) => {
    const hit = checkIntersection(e.clientX, e.clientY);
    if (hit === 'close') { emit('close'); return; }
    if (hit) {
      triggerBurn();
      if (hit === 'register') {
        setTimeout(() => {
          authStore.setmemberView('coreselection');
          authStore.openLoginModal();
        }, 2500);
      }
    }
  };
  mouseMoveHandler = (e) => {
    if (isBurned) { document.body.style.cursor = "default"; return; }
    const hit = checkIntersection(e.clientX, e.clientY);
    document.body.style.cursor = hit ? "pointer" : "default";
  };
  resizeHandler = () => {
      if(camera && renderer) {
        const w = window.innerWidth;
        const h = window.innerHeight;
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
        renderer.setSize(w, h);
      }
  };

  window.addEventListener("click", clickHandler);
  window.addEventListener("mousemove", mouseMoveHandler);
  window.addEventListener("resize", resizeHandler);

  const clock = new THREE.Clock();
  function animate() {
    animationId = requestAnimationFrame(animate);
    const t = clock.getElapsedTime();
    uniforms.uTime.value = t;
    particleMat.uniforms.uTime.value = t;
    if (uniforms.uProgress.value < 0.9) {
      letterMesh.rotation.z = Math.sin(t * 0.5) * 0.015;
      letterMesh.rotation.x = Math.sin(t * 0.3) * 0.015;
    }
    renderer.render(scene, camera);
  }
  animate();
});

onBeforeUnmount(() => {
  window.removeEventListener("click", clickHandler);
  window.removeEventListener("mousemove", mouseMoveHandler);
  window.removeEventListener("resize", resizeHandler);
  if (animationId) cancelAnimationFrame(animationId);
  if (scene) {
    scene.traverse((object) => {
      if (object.geometry) object.geometry.dispose();
      if (object.material) {
        if (Array.isArray(object.material)) object.material.forEach((m) => m.dispose());
        else object.material.dispose();
      }
    });
  }
  if (material && material.uniforms.uTexture.value) {
      material.uniforms.uTexture.value.dispose();
  }
  if (renderer) renderer.dispose();
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

.home-canvas-container {
  position: relative;
  width: 100vw;
  height: 100vh;
  display: block;
  overflow: hidden;
  margin: 0;
  padding: 0;
  z-index: 1000; 
  background-color: rgba(0, 0, 0, 0.85);
}
</style>
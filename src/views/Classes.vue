<template>
  <div class="book-wrap" ref="wrap">
    <canvas ref="canvas" class="three-canvas"></canvas>

    <!-- 左頁 DOM -->
    <div ref="leftDom" class="page-dom" v-show="!isFlipping && leftComponent">
      <component :is="leftComponent" />
    </div>

    <!-- 右頁 DOM -->
    <div ref="rightDom" class="page-dom" v-show="!isFlipping && rightComponent">
      <component :is="rightComponent" />
    </div>

    <div class="controls">
      <button @click="prevPage" :disabled="isFlipping || !hasPrev">← 上一頁</button>
      <button @click="nextPage" :disabled="isFlipping || !hasNext">下一頁 →</button>
    </div>
  </div>
</template>

<script setup>
import { ref, shallowRef, onMounted, onBeforeUnmount, nextTick } from 'vue'
import * as THREE from 'three'

/* -----------------------------------------------------------
   Vue Pages
----------------------------------------------------------- */
const pages = [
  () => import('../components/ClassPages/pageOne.vue'),
  () => import('../components/ClassPages/pageTwo.vue'),
  () => import('../components/ClassPages/pageThree.vue')
]

const loadedPages = []
let pageIndex = 0
const leftComponent = shallowRef(null)
const rightComponent = shallowRef(null)

async function loadPair(idx) {
  if (pages[idx]) {
    if (!loadedPages[idx]) loadedPages[idx] = (await pages[idx]()).default
    leftComponent.value = loadedPages[idx]
  } else leftComponent.value = null

  const r = idx + 1
  if (pages[r]) {
    if (!loadedPages[r]) loadedPages[r] = (await pages[r]()).default
    rightComponent.value = loadedPages[r]
  } else rightComponent.value = null
}

/* -----------------------------------------------------------
   State
----------------------------------------------------------- */
const isFlipping = ref(false)
const hasPrev = ref(false)
const hasNext = ref(true)

/* -----------------------------------------------------------
   DOM & Three.js Base
----------------------------------------------------------- */
const canvas = ref(null)
const leftDom = ref(null)
const rightDom = ref(null)

let renderer, scene, camera
let leftPageMesh, rightPageMesh
let forwardPivot, backwardPivot
let forwardSegments = []
let backwardSegments = []
let rafIds = new Set()

/* -----------------------------------------------------------
   Book parameters
----------------------------------------------------------- */
const PAGE_W = 6.4
const PAGE_H = 9.0
const SEGMENTS = 801
const GAP = 0.16
const DURATION = 700
const BEND_STRENGTH = 1.15
const ROT_MULT = 1.2
const Z_PUSH = 0.14

/* -----------------------------------------------------------
   Textures
----------------------------------------------------------- */
let paperFrontTexture, paperBackTexture

/* -----------------------------------------------------------
   Shader
----------------------------------------------------------- */
const vert = `
  uniform float uBend;
  uniform float uSegRel;
  uniform float uPageH;
  varying vec2 vUv;

  void main() {
    vUv = uv;

    vec3 pos = position;

    float halfH = uPageH * 0.5;
    float vNorm = (pos.y / halfH + 1.0) * 0.5;
    float local = 1.0 - pow(2.0 * vNorm - 1.0, 2.0);
    float segInfluence = (1.0 - uSegRel);

    float bend = uBend * segInfluence * local;

    pos.z += bend;

    gl_Position = projectionMatrix * modelViewMatrix * vec4(pos, 1.0);
  }
`

const frag = `
  uniform sampler2D uFrontMap;
  uniform sampler2D uBackMap;
  varying vec2 vUv;

  void main() {
    vec4 col;
    if (gl_FrontFacing) {
      col = texture2D(uFrontMap, vUv);
    } else {
      col = texture2D(uBackMap, vec2(1.0 - vUv.x, vUv.y));
    }
    gl_FragColor = col;
  }
`

/* -----------------------------------------------------------
   Init Three.js
----------------------------------------------------------- */
function initThree() {
  renderer = new THREE.WebGLRenderer({ canvas: canvas.value, antialias: true })
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2))
  resizeRenderer()
  scene = new THREE.Scene()
  scene.background = new THREE.Color(0x041426)

  camera = new THREE.PerspectiveCamera(
    45,
    canvas.value.clientWidth / canvas.value.clientHeight,
    0.1,
    100
  )
  camera.position.set(0, 0, 13)

  /* ---- Load textures ---- */
  const loader = new THREE.TextureLoader()

  paperFrontTexture = loader.load(
    'https://png.pngtree.com/background/20210714/original/pngtree-rough-sketch-paper-texture-paper-background-picture-image_1193727.jpg'
  )
  paperBackTexture = loader.load(
    'https://png.pngtree.com/background/20210714/original/pngtree-rough-sketch-paper-texture-paper-background-picture-image_1193727.jpg'
  )

  // Fix texture edges → avoid white seams
  ;[paperFrontTexture, paperBackTexture].forEach((t) => {
    t.wrapS = t.wrapT = THREE.ClampToEdgeWrapping
    t.minFilter = THREE.LinearFilter
    t.magFilter = THREE.LinearFilter
    t.generateMipmaps = false
  })

  // anisotropy
  const maxAniso = renderer.capabilities.getMaxAnisotropy()
  paperFrontTexture.anisotropy = maxAniso
  paperBackTexture.anisotropy = maxAniso

  /* ---- Left page bg ---- */
  leftPageMesh = new THREE.Mesh(
    new THREE.PlaneGeometry(PAGE_W, PAGE_H),
    new THREE.MeshBasicMaterial({ map: paperFrontTexture, side: THREE.DoubleSide })
  )
  leftPageMesh.position.x = -(PAGE_W / 2 + GAP / 2)
  scene.add(leftPageMesh)

  /* ---- Right page bg ---- */
  rightPageMesh = new THREE.Mesh(
    new THREE.PlaneGeometry(PAGE_W, PAGE_H),
    new THREE.MeshBasicMaterial({ map: paperFrontTexture, side: THREE.DoubleSide })
  )
  rightPageMesh.position.x = +(PAGE_W / 2 + GAP / 2)
  scene.add(rightPageMesh)

  /* ---- Create flipping segments ---- */
  createSegments(true)
  createSegments(false)

  setSegmentsVisible(false, true)
  setSegmentsVisible(false, false)

  animate()
  requestAnimationFrame(syncDomToPages)
}

/* -----------------------------------------------------------
   Create Segments (→ 修好縫隙)
----------------------------------------------------------- */
function createSegments(forward = true) {
  const segGroup = new THREE.Object3D()
  const segWidth = PAGE_W / SEGMENTS
  const heightSegs = 20

  // ★ Overlap to remove gaps
  const OVERLAP = 1.015
  const baseOffset = forward
    ? PAGE_W / 2 + GAP / 2
    : -(PAGE_W / 2 + GAP / 2)

  for (let i = 0; i < SEGMENTS; i++) {
    const geo = new THREE.PlaneGeometry(segWidth, PAGE_H, 1, heightSegs)

    // UV fix (keep your logic)
    const uv = geo.attributes.uv
    for (let k = 0; k < uv.count; k++) {
      uv.setX(k, (i + uv.getX(k)) / SEGMENTS)
    }
    uv.needsUpdate = true

    const mat = new THREE.ShaderMaterial({
      uniforms: THREE.UniformsUtils.clone({
        uFrontMap: { value: paperFrontTexture },
        uBackMap: { value: paperBackTexture },
        uBend: { value: 0.0 },
        uSegRel: { value: 0.0 },
        uPageH: { value: PAGE_H }
      }),
      vertexShader: vert,
      fragmentShader: frag,
      side: THREE.DoubleSide,
      polygonOffset: true,
      polygonOffsetFactor: -1,
      polygonOffsetUnits: 0
    })

    const seg = new THREE.Mesh(geo, mat)
    seg.castShadow = true
    seg.receiveShadow = true

    // ★ Fix gap: correct center position & overlap scale
    // ★ 計算每段基準位置
    seg.position.x = baseOffset - PAGE_W / 2 + (i + 0.5) * segWidth

    // ★ 基本重疊（避免內部縫隙）
    seg.scale.x = OVERLAP

    // ★ 針對最外側 segment 加強補強（重要！）
    if (i === 0 || i === SEGMENTS - 1) {
      // 這兩段在最外側，會和背景 page 接觸
      seg.scale.x = OVERLAP * 2 // 加寬 6%
      
      // 小幅往外推 → 把縫隙蓋住
      const PUSH = 0.06
      if (forward) {
        // 翻右頁 → 最外側在 "右側"
        seg.position.x += PUSH
      } else {
        // 翻左頁 → 最外側在 "左側"
        seg.position.x -= PUSH
      }
    }


    segGroup.add(seg)
  }

  const pivot = new THREE.Object3D()
  pivot.position.x = 0
  pivot.position.z = 0.2
  scene.add(pivot)
  pivot.add(segGroup)

  if (forward) {
    forwardPivot = pivot
    forwardSegments = segGroup.children
  } else {
    backwardPivot = pivot
    backwardSegments = segGroup.children
  }
}

function setSegmentsVisible(v, forward = true) {
  const arr = forward ? forwardSegments : backwardSegments
  arr.forEach((s) => (s.visible = v))
}

/* -----------------------------------------------------------
   Flip animation (kept original)
----------------------------------------------------------- */
function easeOutIn(t) {
  return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t
}

function runFlip(forward = true) {
  if (isFlipping.value) return
  if (forward && !rightComponent.value) return
  if (!forward && pageIndex <= 0) return

  isFlipping.value = true

  const pivot = forward ? forwardPivot : backwardPivot
  const segments = forward ? forwardSegments : backwardSegments
  const totalAngle = forward ? Math.PI : -Math.PI
  const start = performance.now()

  setSegmentsVisible(true, forward)

  function tick(now) {
    const t = Math.min(1, (now - start) / DURATION)
    const e = easeOutIn(t)

    pivot.rotation.y = totalAngle * -e

    const count = segments.length
    for (let i = 0; i < count; i++) {
      const seg = segments[i]

      const dist = forward
        ? 1 - i / (count - 1)
        : i / (count - 1)

      const bend = Math.sin(e * Math.PI) * dist * BEND_STRENGTH
      seg.material.uniforms.uBend.value = bend
      seg.material.uniforms.uSegRel.value = dist
      seg.material.uniforms.uPageH.value = PAGE_H

      const curve = Math.pow(dist, 1.35)
      seg.rotation.y = totalAngle * e * (1 - curve * 0.4) * ROT_MULT
      seg.position.z = -dist * Z_PUSH * e
    }

    renderer.render(scene, camera)

    if (t < 1) {
      requestAnimationFrame(tick)
    } else {
      pivot.rotation.y = 0
      segments.forEach((s) => {
        s.material.uniforms.uBend.value = 0
        s.rotation.y = 0
        s.position.z = 0
      })

      setSegmentsVisible(false, forward)

      pageIndex += forward ? 1 : -1
      loadPair(pageIndex)
      hasPrev.value = pageIndex > 0
      hasNext.value = pageIndex + 1 < pages.length

      nextTick(() => requestAnimationFrame(syncDomToPages))
      isFlipping.value = false
    }
  }

  requestAnimationFrame(tick)
}

function nextPage() {
  runFlip(true)
}
function prevPage() {
  runFlip(false)
}

/* -----------------------------------------------------------
   DOM Sync
----------------------------------------------------------- */
function worldToScreen(pos) {
  const v = pos.clone().project(camera)
  const w = renderer.domElement.clientWidth
  const h = renderer.domElement.clientHeight
  return { x: (v.x + 1) * w / 2, y: (-v.y + 1) * h / 2 }
}

function syncDom(dom, mesh) {
  const hw = PAGE_W / 2
  const hh = PAGE_H / 2
  const corners = [
    new THREE.Vector3(-hw, hh, 0),
    new THREE.Vector3(hw, hh, 0),
    new THREE.Vector3(hw, -hh, 0),
    new THREE.Vector3(-hw, -hh, 0)
  ]

  const pts = corners.map((c) => worldToScreen(mesh.localToWorld(c)))
  const xs = pts.map((p) => p.x)
  const ys = pts.map((p) => p.y)

  dom.style.left = `${Math.min(...xs)}px`
  dom.style.top = `${Math.min(...ys)}px`
  dom.style.width = `${Math.max(...xs) - Math.min(...xs)}px`
  dom.style.height = `${Math.max(...ys) - Math.min(...ys)}px`
}

function syncDomToPages() {
  if (leftDom.value) syncDom(leftDom.value, leftPageMesh)
  if (rightDom.value) syncDom(rightDom.value, rightPageMesh)
}

/* -----------------------------------------------------------
   Resize / Animation Loop
----------------------------------------------------------- */
function resizeRenderer() {
  if (!renderer || !canvas.value) return
  const dpr = Math.min(window.devicePixelRatio || 1, 2)
  renderer.setPixelRatio(dpr)
  const w = canvas.value.clientWidth || window.innerWidth
  const h = canvas.value.clientHeight || window.innerHeight
  renderer.setSize(w, h, false)
  if (camera) {
    camera.aspect = w / h
    camera.updateProjectionMatrix()
  }
}

function onResize() {
  resizeRenderer()
  requestAnimationFrame(syncDomToPages)
}

function animate() {
  const id = requestAnimationFrame(animate)
  rafIds.add(id)
  renderer.render(scene, camera)
}

onMounted(async () => {
  initThree()
  await loadPair(pageIndex)
  hasPrev.value = false
  hasNext.value = pageIndex + 1 < pages.length
  nextTick(() => requestAnimationFrame(syncDomToPages))
  window.addEventListener('resize', onResize)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', onResize)
  rafIds.forEach((id) => cancelAnimationFrame(id))
  if (renderer?.forceContextLoss) renderer.forceContextLoss()
})
</script>

<style scoped>
.book-wrap {
  width: 100%;
  height: 100vh;
  overflow: hidden;
  position: relative;
  background: #f6f5f2;
}

.three-canvas {
  width: 100%;
  height: 100%;
  display: block;
}

.page-dom {
  position: absolute;
  background: white;
  border-radius: 6px;
  overflow: hidden;
  z-index: 10;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.controls {
  position: absolute;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 20;
}

.controls button {
  padding: 10px 16px;
  margin: 0 8px;
  border: 1px solid #bbb;
  background: white;
  border-radius: 6px;
  cursor: pointer;
}
</style>

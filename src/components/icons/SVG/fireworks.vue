<template>
  <div class="container">
    <div class="scene">
      <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg" class="svg-canvas">
        <defs>
          <filter id="glow" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur stdDeviation="4" result="coloredBlur" />
            <feMerge>
              <feMergeNode in="coloredBlur" />
              <feMergeNode in="SourceGraphic" />
            </feMerge>
          </filter>
        </defs>

        <g class="particles">
          <circle
            v-for="(p, index) in particles"
            :ref="(el) => { if(el) particleRefs[index] = el }"
            :key="index"
            class="particle"
            r="0"
            :fill="p.color"
          />
        </g>

        <g ref="firecrackerGroup" class="firecracker-group" v-show="!isExploded">
          <path
            ref="fuseRef"
            d="M200,130 Q210,100 200,80"
            fill="none"
            stroke="#ccc"
            stroke-width="3"
            stroke-linecap="round"
          />
          <circle ref="sparkRef" cx="200" cy="80" r="0" fill="orange" filter="url(#glow)" />

          <rect
            x="170"
            y="130"
            width="60"
            height="100"
            rx="5"
            fill="#e53935"
            stroke="#b71c1c"
            stroke-width="2"
          />
          <rect x="175" y="140" width="50" height="5" fill="#ffeb3b" />
          <rect x="175" y="215" width="50" height="5" fill="#ffeb3b" />
          
          <g transform="translate(200, 180)">
             <circle r="18" fill="#ffeb3b" />
             <text text-anchor="middle" dy="5" font-size="16" fill="#d32f2f" font-weight="bold" style="font-family: serif;">福</text>
          </g>
        </g>
        
        <circle ref="flashRef" cx="200" cy="180" r="0" fill="white" opacity="0.8" />
      </svg>
    </div>

    <div class="controls">
      <button @click="handleAction" :disabled="isAnimating">
        {{ isExploded ? '重置 (Reset)' : '點燃 (Ignite)' }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick, onMounted } from 'vue';
import gsap from 'gsap';

// --- 狀態 ---
const isAnimating = ref(false);
const isExploded = ref(false);

// --- Refs ---
const firecrackerGroup = ref(null);
const fuseRef = ref(null);
const sparkRef = ref(null);
const flashRef = ref(null);
const particleRefs = ref([]); // 用來收集所有粒子的 DOM

// --- 粒子設定 ---
const particleCount = 40;
const particles = ref([]);
const colors = ['#FFD700', '#FF4500', '#FF6347', '#FFFFFF', '#e53935'];

// 初始化粒子數據
const initParticles = () => {
  particles.value = Array.from({ length: particleCount }).map(() => ({
    color: colors[Math.floor(Math.random() * colors.length)],
    angle: Math.random() * Math.PI * 2,
    distance: 100 + Math.random() * 150,
    size: 2 + Math.random() * 4
  }));
};

initParticles();

// --- 統一的按鈕處理 ---
const handleAction = () => {
  if (isExploded.value) {
    reset();
  } else {
    triggerExplosion();
  }
};

// --- 動畫主邏輯 ---
const triggerExplosion = () => {
  // 安全檢查：如果 DOM 還沒準備好，就不執行
  if (!fuseRef.value || !firecrackerGroup.value) {
    console.error("DOM 元素未找到，請檢查 Ref");
    return;
  }

  isAnimating.value = true;

  const tl = gsap.timeline({
    onComplete: () => {
      isAnimating.value = false;
      isExploded.value = true; // 動畫結束後才隱藏 (v-show)
    }
  });

  // 1. 設定引信初始狀態
  // 如果 getTotalLength 失敗，給一個預設值 60
  const fuseLength = fuseRef.value.getTotalLength?.() || 60; 
  
  gsap.set(fuseRef.value, { 
    strokeDasharray: fuseLength, 
    strokeDashoffset: 0 
  });
  
  // 2. 開始動畫
  
  // 火花出現
  tl.to(sparkRef.value, { duration: 0.1, r: 4, opacity: 1 });

  // 引信燃燒 (同時火花跟著移動)
  // 這裡我們不依賴 onUpdate 的 this，而是用兩個並行的動畫
  tl.add("burning"); // 設定一個時間標籤，讓下面兩個動畫同時開始
  
  // A. 線條變短
  tl.to(fuseRef.value, {
    duration: 1.5,
    strokeDashoffset: fuseLength,
    ease: "linear"
  }, "burning");

  // B. 火花沿著路徑向下移動 (模擬)
  // 路徑是從 (200, 130) 到 (200, 80)，引信是從上往下燒
  // 為了簡單且穩定，我們直接讓火花從 y:80 移動到 y:130
  tl.fromTo(sparkRef.value, 
    { cx: 200, cy: 80 }, 
    { 
      cy: 130, // 移動到引信底部
      duration: 1.5, 
      ease: "linear",
      // 加一點隨機抖動
      x: "+=2",
      yoyo: true,
      repeat: 15 
    }, 
    "burning"
  );

  // 3. 爆炸前的劇烈震動
  tl.to(firecrackerGroup.value, {
    duration: 0.5,
    x: "+=3",
    rotation: "+=3",
    yoyo: true,
    repeat: 10,
    transformOrigin: "center center"
  });

  // 4. 爆炸瞬間
  tl.add(() => {
    // 閃光
    gsap.fromTo(flashRef.value, 
      { r: 0, opacity: 1 }, 
      { r: 200, opacity: 0, duration: 0.3, ease: "power2.out" }
    );

    // 粒子噴發
    particleRefs.value.forEach((el, i) => {
      if (!el) return;
      const p = particles.value[i];
      const originX = 200;
      const originY = 180;
      const endX = originX + Math.cos(p.angle) * p.distance;
      const endY = originY + Math.sin(p.angle) * p.distance;

      gsap.fromTo(el, 
        { cx: originX, cy: originY, r: p.size, opacity: 1 },
        {
          cx: endX,
          cy: endY,
          r: 0,
          opacity: 0,
          duration: 0.8 + Math.random() * 0.5,
          ease: "power4.out",
          delay: Math.random() * 0.1
        }
      );
    });
  });
};

const reset = async () => {
  isExploded.value = false; // 這會讓 v-show 變為 true
  isAnimating.value = false;
  
  // 等待 DOM 更新
  await nextTick();
  
  // 重置所有 GSAP 屬性
  gsap.set(firecrackerGroup.value, { x: 0, rotation: 0, opacity: 1 });
  gsap.set(fuseRef.value, { strokeDashoffset: 0 });
  gsap.set(sparkRef.value, { r: 0, x: 0, y: 0, cx: 200, cy: 80 }); // 確保火花回到原點
};
onMounted(()=>{
initParticles();

  triggerExplosion()
})
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  background-color: #2c3e50;
  color: white;
}

.scene {
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, #34495e 0%, #2c3e50 80%);
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
  margin-bottom: 20px;
}

.svg-canvas {
  width: 100%;
  height: 100%;
  overflow: visible;
}

button {
  padding: 10px 20px;
  font-size: 1.2rem;
  background-color: #e74c3c;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: opacity 0.2s;
}

button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
<script setup>
  import { ref, reactive, onMounted, computed, nextTick, onUnmounted, defineEmits } from 'vue';
  import { gsap } from 'gsap';
  import BasicButton from '../BasicButton.vue';

// ================ 鍵盤esc關閉 ================ 
const emit = defineEmits(['close-game']);
const handleKey = (e) => { if (e.code === 'Escape') emit('close-game');
};

  // 物件設定
  const config = {
    startRings:10,      // 圈圈數
    ringRadius: 50,     // 圈圈半徑
    targetRadius: 50,   // 物件半徑
    objWidth: 110,      // 物件寬
    objHeight: 140,     // 物件高
    rowGap: 130,        // 行距
    colGap: 150,        // 物件間距
    maxDrag: 240,       // 力量控制變數1
    launchPower: 3.5,   // 力量控制變數2
    rows: 3,            // 控制物品行數
    cols: 8             // 控制物品每行個數 (偶數列 +1 )
  };

  const gameContainer = ref(null);
  const ringRef = ref(null);
  const targetRefs = reactive({});
  const gameStarted = ref(false);
  const isGameOver = ref(false);
  const isFlying = ref(false);
  const isDragging = ref(false);
  const totalRings = ref(config.startRings)
  const ringsLeft = ref(totalRings.value);
  const score = ref(0);

  const containerSize = reactive({ w: 0, h: 0 });
  const dragOffset = reactive({ x: 0, y: 0 });
  let startPos = { x: 0, y: 0 };

  const initialX = computed(() => containerSize.w / 2);
  const initialY = computed(() => containerSize.h * 0.8);

  const targets = ref([]);

  // 生成目標物
  const createGridTargets = () => {
    const newTargets = [];
    const colors = ['#FF5252', '#FFD740', '#64FFDA'];
    const centerX = containerSize.w / 2;
    const startY = containerSize.h * 0.17;

    for (let r = 0; r < config.rows; r++) {
      const isOddRow = r % 2 === 1;
      const itemsInRow = isOddRow ? config.cols + 1 : config.cols;
      const rowWidth = (itemsInRow - 1) * config.colGap;
      const rowStartX = centerX - (rowWidth / 2);

      for (let c = 0; c < itemsInRow; c++) {
        newTargets.push({
          id: `${r}-${c}`,
          x: rowStartX + (c * config.colGap),
          y: startY + (r * config.rowGap),
          row: r,
          color: colors[r % colors.length],
          score: (config.rows - r) * 100,
          hit: false
        });
      }
    }
    targets.value = newTargets.sort((a, b) => a.y - b.y);
  };

  // --- 發射與邊緣判定 ---
  const launch = () => {
    isFlying.value = true;
    const dragMagnitude = Math.sqrt(dragOffset.x**2 + dragOffset.y**2);
    const clampedDrag = Math.min(dragMagnitude, config.maxDrag);
    const ratio = clampedDrag / (dragMagnitude || 1);
    
    const targetX = initialX.value - (dragOffset.x * ratio * config.launchPower);
    const targetY = initialY.value - (dragOffset.y * ratio * config.launchPower);

    // 計算落點座標及縮放比例
    const ringFinalScale = 0.4 + (targetY / containerSize.h) * 0.5;

    const tl = gsap.timeline({
      onComplete: () => {
        checkHit(targetX, targetY, ringFinalScale);
        
        setTimeout(() => {
          if (ringsLeft.value <= 0) {
            isGameOver.value = true;
          } else {
            isFlying.value = false;
            initRingPosition();
          }
        }, 800);
      }
    });

    tl.to(ringRef.value, {
      x: targetX, y: targetY,
      scale: ringFinalScale,
      rotationX: 65, duration: 0.8, ease: "power2.out"
    }, 0);

    tl.to(ringRef.value, {
      yPercent: -500, yoyo: true, repeat: 1, duration: 0.4, ease: "power1.out"
    }, 0);
  };

  const checkHit = (fx, fy, ringScale) => {
    const currentRingRadius = config.ringRadius * ringScale;

    targets.value.forEach(t => {
      const distance = Math.sqrt((fx - t.x)**2 + (fy - t.y)**2);
      
      // 物件縮放比例
      const targetScale = 0.6 + (t.row * 0.15);
      const currentTargetRadius = config.targetRadius * targetScale;

      // 套中判定
      if (distance < (currentRingRadius + currentTargetRadius) && !t.hit) {
        score.value += t.score;
        t.hit = true;
        gsap.to(targetRefs[t.id], { 
          y: "-=20", rotationZ: 10, yoyo: true, repeat: 1, duration: 0.15 
        });
      }
    });
    ringsLeft.value--;
  };

  // --- 輔助函式 ---
  const startGame = () => {
    isGameOver.value = false;
    gameStarted.value = true;
    score.value = 0; 
    ringsLeft.value = totalRings.value;
    isFlying.value = false;
    updateSize();
    createGridTargets();
    nextTick(initRingPosition);
  };

  const updateSize = () => {
    if (gameContainer.value) {
      containerSize.w = gameContainer.value.clientWidth;
      containerSize.h = gameContainer.value.clientHeight;
    }
  };

  const initRingPosition = () => {
    if (ringRef.value) {
      gsap.set(ringRef.value, { 
        x: initialX.value, y: initialY.value, 
        xPercent: -50, yPercent: -50, 
        scale: 1, rotationX: 0 
      });
    }
  };

  const getTargetStyle = (t) => ({
    left: `${t.x}px`, top: `${t.y}px`,
    transform: `translate(-50%, -50%) scale(${0.6 + (t.row * 0.15)})`,
    zIndex: Math.floor(t.y),
    opacity: t.hit ? 0 : 1
  });

  const getTargetBodyStyle = (t) => ({
    backgroundColor: t.color,
    width: `${config.objWidth}px`,
    height: `${config.objHeight}px`,
    borderRadius: '20px',
    boxShadow: `0 8px 20px rgba(0,0,0,0.5)`
  });

  const aimLineStyle = computed(() => {
    const angle = Math.atan2(-dragOffset.y, -dragOffset.x) * 180 / Math.PI;
    const dist = Math.min(Math.sqrt(dragOffset.x**2 + dragOffset.y**2), config.maxDrag);
    return {
      left: `${initialX.value}px`, top: `${initialY.value}px`,
      width: `${dist}px`, transform: `rotate(${angle}deg)`, transformOrigin: '0 50%'
    };
  });

  // --- 事件處理 ---
  const onDragStart = (e) => {
    if (!gameStarted.value || isGameOver.value || isFlying.value || ringsLeft.value <= 0) return;
    isDragging.value = true; 
    startPos = { x: e.clientX, y: e.clientY };
  };

  const onDragging = (e) => {
    if (!isDragging.value) return;
    dragOffset.x = e.clientX - startPos.x;
    dragOffset.y = e.clientY - startPos.y;
    gsap.set(ringRef.value, { 
      x: initialX.value + dragOffset.x * 0.15, 
      y: initialY.value + dragOffset.y * 0.15 
    });
  };

  const onDragEnd = () => {
    if (!isDragging.value) return;
    isDragging.value = false;
    if (Math.sqrt(dragOffset.x**2 + dragOffset.y**2) > 35) {
      launch();
    } else {
      gsap.to(ringRef.value, { x: initialX.value, y: initialY.value, duration: 0.3 });
    }
  };

  onMounted(() => {
    updateSize();
    window.addEventListener('resize', updateSize);
    window.addEventListener('keydown', handleKey);
  });
  onUnmounted(() => {
    window.removeEventListener('resize', updateSize);
    window.addEventListener('keydown', handleKey);
  });
</script>
<template>
  <div ref="gameContainer" class="game-wrapper" 
       @mousedown="onDragStart" @mousemove="onDragging" @mouseup="onDragEnd">
    
    <div v-if="!gameStarted" class="overlay dp-flex">
      <div class="menu-box">
        <h1 class="title">Ring Toss</h1>
        <BasicButton @click="startGame"><p>Start</p></BasicButton>
      </div>
    </div>

    <div v-else-if="isGameOver" class="overlay dp-flex">
      <div class="menu-box result-box">
        <h2 v-if="score < 1500" class="result-title">Game Over</h2>
        <h2 v-if="score >= 1500" class="result-title">Congraduations !!</h2>
        <h6 class="result-title">Score: {{ score }}</h6>
        <BasicButton @click="startGame"><p>Try Again</p></BasicButton>
      </div>
    </div>

    <div v-else class="stage">
      <div v-if="isDragging" class="aim-line" :style="aimLineStyle"></div>

      <div v-for="t in targets" :key="t.id" 
           :ref="el => targetRefs[t.id] = el"
           class="target-object" :style="getTargetStyle(t)">
        <div class="target-body dp-flex" :style="getTargetBodyStyle(t)">
          <p class="score-text">{{ t.score }}</p>
        </div>
      </div>

      <div ref="ringRef" class="ring-circle"></div>
    </div>

    <div class="game-ui">
      <div class="score-tag"><h6>SCORE: {{ score }}</h6></div>
      <div class="ring-stock dp-flex">
        <div v-for="n in totalRings" :key="n" class="ring-mini-icon" :class="{ 'is-spent': n > ringsLeft }"></div>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
  .game-wrapper {   /*  遊戲底圖設定  */
    width: 100%; height: 100%;
    position: relative;
    overflow: hidden;
    perspective: 1200px;
    user-select: none;
    background-image: url('/SurvivalGuide/RingToss/RingTossBack.png');
    background-position: center;
    background-size: cover;
  }
  .stage {          /*  遊戲畫面設定  */
    width: 100%;
    height: 100%;
    transform: rotateX(10deg);
    transform-origin: bottom;
  }

  .ring-circle {    /*  圈圈樣式  */
    position: absolute;
    width: 100px;
    height: 100px;
    border: 10px solid $color-fsGold;
    border-radius: 50%;
    pointer-events: none;
    z-index: 2000;
  }
  .target-object {
    position: absolute;
    transition: opacity 0.4s;
  }
  .target-body {
    align-items: center;
    justify-content: center;
  }
  .score-text { color: $color-fsTitle;}
  .aim-line {
    position: absolute; 
    height: 4px; 
    background: linear-gradient(90deg, $color-fsGold, transparent);
    pointer-events: none; 
    z-index: 500; 
    border-radius: 2px;
  }

  .game-ui {
    position: absolute;
    top: 40px; left: 40px;
    color:  $color-fsWhite;
    pointer-events: none;
  }
  .ring-stock {
    gap: 10px;
    margin-top: 15px;
  }
  .ring-mini-icon { 
    width: 24px;
    height: 24px; 
    border: 3px solid #f1c40f; 
    border-radius: 50%; 
    transition: 0.3s; 
    &.is-spent { opacity: 0.1; transform: scale(0.8); }
  }

  .overlay { 
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.85); 
    justify-content: center;
    align-items: center;
    z-index: 3000;
    backdrop-filter: blur(8px);
  }
  .menu-box {
    text-align: center;
    color:  $color-fsTitle;
  }
  .title {color: $color-fsGold;}
  .result-title { color: #ff5252;}
</style>
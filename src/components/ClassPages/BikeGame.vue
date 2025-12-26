<template>
  <div class="game-wrapper dp-flex"
    @mousedown.stop
    @touchstart.stop
  >
    <div 
      class="game-container" 
      ref="container"
      @touchstart="handleTouchStart"
      @touchmove="handleTouchMove"
    >
      <div class="stage" v-if="containerWidth > 0">
        <div class="ground">
          <div 
            class="road-texture" 
            :style="{ backgroundPositionY: `${roadOffset}px` }"
          ></div>
        </div>

        <div 
          v-for="obs in obstacles" 
          :key="obs.id"
          class="obstacle-box"
          :style="getObsStyle(obs)"
        >
            <img :src="obs.img" alt="" class="obs-inner">
        </div>
        <div 
          class="player-bike" 
          :style="playerStyle"
        >
          <img src="/Classes/MotorGame/MotorPlayer.png" alt="PLAYER" class="bike-inner">
        </div>
      </div>

      <div class="hud dp-flex-col" v-if="gameState === 'playing'">
        <h5>TIME: {{ Math.ceil(timeRemaining) }}s</h5>

        <div class="hpStock">
        <h5 class="dp-flex">HP:<div v-for="n in totalLives" :key="n" class="heart-icon" :class="{ 'is-spent': n > lives}"><font-awesome-icon icon="fa-solid fa-heart" beat /></div></h5>
        </div>
      </div>
      <transition name="fade">
        <div class="overlay dp-flex" v-if="gameState !== 'playing'">
          <div v-if="gameState === 'start'" class="ui-panel">
            <h2>MOTO RUNNER</h2>
            <p>text</p>
            <basic-button @click="startGame" class="btn-white">START</basic-button>
          </div>

          <div v-if="gameState === 'result'" class="ui-panel result">
            <h2 :class="lives > 0 ? 'text-win' : 'text-lose'">
              {{ lives > 0 ? 'Back To School Savety' : `You're Crashed !!` }}
            </h2>
            <div class="final-stats">
              <div class="stat-row"><p>SURVIVAL{{ (30 - timeRemaining).toFixed(1) }}s</p></div>
              <div class="stat-row"><p>LIFE{{ lives }}</p></div>
            </div>
            <basic-button @click="gameState = 'start'" class="btn-white">RETRY</basic-button>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, reactive, computed } from 'vue';
import { gsap } from 'gsap';
import BasicButton from '../BasicButton.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

// --- 障礙物類型定義陣列 ---
const obstacleConfigs = [
  { img: `${import.meta.env.BASE_URL}/Classes/MotorGame/Motor.png`, width: 60, score: 10 },
  { img: `${import.meta.env.BASE_URL}/Classes/MotorGame/Taxi.png`, width: 120, score: 5 },
  { img: `${import.meta.env.BASE_URL}/Classes/MotorGame/Truck.png`, width: 150, score: 15 },

];

// --- 配置常數 ---
const BIKE_WIDTH = 100;
const Z_SPAWN = -3500;
const Z_DESPAWN = 500;

// --- 狀態 ---
const container = ref(null);
const containerWidth = ref(0);
const gameState = ref('start');
const totalLives = ref(3);
const lives = ref(totalLives.value);
const timeRemaining = ref(30);
const playerX = ref(0);
const playerTilt = ref(0);
const roadOffset = ref(0);
const obstacles = reactive([]);

let lastSpawnTime = 0;
let obstacleId = 0;
const keys = { ArrowLeft: false, ArrowRight: false };

// --- 3D 樣式計算 ---
const playerStyle = computed(() => ({
  transform: `translateX(-50%) translateX(${playerX.value}px) rotateX(-90deg) rotateY(${playerTilt.value}deg)`
}));

const getObsStyle = (obs) => ({
  width: `${obs.width}px`,
  transform: `translateX(-50%) translate3d(${obs.x}px, 0, ${obs.z}px) rotateX(-90deg)`,
  opacity: obs.z > -1200 ? 1 : (obs.z + 3500) / 1000,
  filter: obs.hit ? 'grayscale(1) brightness(0.5)' : 'none'
});

// --- 遊戲邏輯循環 ---
const update = (time, deltaTime) => {
  if (gameState.value !== 'playing') return;
  const dt = deltaTime / 1000;

  timeRemaining.value -= dt;
  if (timeRemaining.value <= 0) endGame();
  
  roadOffset.value += 1800 * dt;

  // 玩家移動
  const moveSpeed = 1000 * dt;
  let targetTilt = 0;
  if (keys.ArrowLeft) { playerX.value -= moveSpeed; targetTilt = 25; }
  else if (keys.ArrowRight) { playerX.value += moveSpeed; targetTilt = -25; }
  
  playerTilt.value = gsap.utils.interpolate(playerTilt.value, targetTilt, 0.1);

  // 【邊界鎖定】確保不跑出畫面
  const limit = (containerWidth.value / 2) - (BIKE_WIDTH / 2);
  if (playerX.value < -limit) playerX.value = -limit;
  if (playerX.value > limit) playerX.value = limit;

  // 生成障礙物
  if (time - lastSpawnTime > 0.5) {
    spawnObstacle();
    lastSpawnTime = time;
  }

  // 障礙物移動與碰撞
  const obsSpeed = 1700 * dt;
  for (let i = obstacles.length - 1; i >= 0; i--) {
    const obs = obstacles[i];
    obs.z += obsSpeed;

    if (!obs.hit && obs.z > -60 && obs.z < 60) {
      const collisionMargin = (obs.width + BIKE_WIDTH) / 2 - 15; // 稍微寬鬆一點的判定
      if (Math.abs(obs.x - playerX.value) < collisionMargin) {
        obs.hit = true;
        lives.value--;
        gsap.to(container.value, { y: 15, yoyo: true, repeat: 3, duration: 0.05 });
        if (lives.value <= 0) endGame();
      }
    }
    if (obs.z > Z_DESPAWN) obstacles.splice(i, 1);
  }
};

const spawnObstacle = () => {
  const roadWidth = containerWidth.value * 0.92;
  const laneCenters = [-roadWidth * 0.3, 0, roadWidth * 0.3];
  
  // 隨機生成障礙物
  const config = obstacleConfigs[Math.floor(Math.random() * obstacleConfigs.length)];
  
  obstacles.push({
    id: obstacleId++,
    x: laneCenters[Math.floor(Math.random() * 3)],
    z: Z_SPAWN,
    hit: false,
    img: config.img,
    width: config.width
  });
};

const startGame = () => {
  lives.value = 3;
  timeRemaining.value = 30;
  playerX.value = 0;
  obstacles.length = 0;
  gameState.value = 'playing';
  lastSpawnTime = gsap.ticker.time;
  updateSize();

};

const endGame = () => { gameState.value = 'result'; };

const updateSize = () => {
  if (container.value) {containerWidth.value = container.value.getBoundingClientRect().width;}
 
};

const handleKeyDown = (e) => { keys[e.key] = true; };
const handleKeyUp = (e) => { keys[e.key] = false; };

onMounted(() => {
  window.addEventListener('resize', updateSize);
  window.addEventListener('keydown', handleKeyDown);
  window.addEventListener('keyup', handleKeyUp);
  gsap.ticker.add(update);
});

onUnmounted(() => {
  window.removeEventListener('resize', updateSize);
  window.removeEventListener('keydown', handleKeyDown);
  window.removeEventListener('keyup', handleKeyUp);
  gsap.ticker.remove(update);
});
</script>

<style scoped lang="scss">
.game-wrapper {
  width: 100%;
  height: 100%;
  background: #000;
  justify-content: center;
  align-items: center;
}

.game-container {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
  background-image: url('/Classes/MotorGame/MotorBackGround.png');  
  background-repeat: no-repeat;
  background-size: cover;
  background-position-x: center;
  perspective: 1200px;
  perspective-origin: 50% 45%; 
}

.stage {
  position: absolute;
  width: 100%;
  height: 100%;
  transform-style: preserve-3d;
}

/* 地面與標線：30% / 70% 固定定位 */
.ground {
  position: absolute;
  width: 92%; 
  height: 6000px;
  left: 4%;
  bottom: 0;
  background: #000; 
  transform: rotateX(90deg);
  transform-origin: bottom center;
}

.road-texture {
  width: 100%;
  height: 100%;
  background-image: 
    linear-gradient(to bottom, #fff 50%, transparent 50%),
    linear-gradient(to bottom, #fff 50%, transparent 50%);
  background-size: 8px 160px;
  background-repeat: repeat-y;
  background-position: 30% 0, 70% 0;
  border-left: 6px solid #fff;
  border-right: 6px solid #fff;
  box-sizing: border-box;
}

// 玩家
.player-bike {
  position: absolute;
  bottom: 0px; 
  left: 50%;
  width: 100px;
  height: 100px;
  transform-style: preserve-3d;
  z-index: 20;
}

// 障礙物
.obstacle-box {
  position: absolute;
  bottom: 0;
  left: 50%;     // 固定高度，寬度由 JS 動態決定 
  transform-style: preserve-3d;
}

.obs-inner, .bike-inner {
  width: 100%;
  height: 100%;
  align-items: center;
  justify-content: center;
  font-weight: 900;
  box-sizing: border-box;
  letter-spacing: 2px;
  transform-origin: bottom center;
  transform: rotateX(90deg);
}
.obs-inner{
  img{
    width: 100%;
    height: auto;
    object-fit: cover;
  }
}
.bike-inner{
  img{
    width: auto;
    height: 100%;
    object-fit: contain;
  }
}

/* UI 樣式 */
.hud {
  position: absolute;
  top: 10px;
  width: 50%;
  justify-content: space-around;
  z-index: 100;
}

.hud {
  h5{
    color: #fff;
    padding: 10px;
    text-align: left;
    gap: 8px;
  }
  .hpStock{
    gap: 4px;
  }
  .heart-icon{
    color: $color-fsRed;
    transition: all 0.8s;
  }
}
.is-spent{
  opacity: 0;
  transform: scale(0) translateY(-100%);
}
.overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.9);
  justify-content: center;
  align-items: center;
  z-index: 200;
}

.ui-panel {
  text-align: center;
  color: #fff;
  padding: 50px;
  border: 1px solid #333;
}

</style>
<template>
  <div class="game-container">
    <button @click="startGame" v-if="!gameStarted || gameOver">
      {{ (gameStarted && gameOver) ? '重新開始' : '開始遊戲' }}
    </button>

    <div class="game-window" :style="{ width: '375px', height: '500px' }">
      <canvas ref="gameCanvas" width="375" height="500"></canvas>

      <div class="hud">
        <p>生命: 🧡 x **{{ lives }}**</p>
        <p>時間: ⏳ **{{ formattedTime }}**</p>
      </div>

      <div v-if="gameOver" class="game-over-screen">
        <h3 v-if="lives > 0">🎉 恭喜！挑戰成功 🎉</h3>
        <h3 v-else>💀 遊戲結束 (Game Over) 💀</h3>
        <p v-if="lives === 0">很遺憾，生命耗盡了。</p>
        <p v-else>時間結束，您成功存活！</p>
        <button @click="startGame" class="restart-button">再玩一次</button>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';

// --- 遊戲狀態 ---
const gameCanvas = ref(null);
const ctx = ref(null);
const gameStarted = ref(false);
const gameOver = ref(false);
const lives = ref(3);
const timeRemaining = ref(60); // 秒
let gameLoopInterval = null;
let countdownInterval = null;

// 玩家位置 (0: 左, 1: 中, 2: 右)
const playerPosition = ref(1); 
// 障礙物列表：{ x: 0/1/2, distance: 0-100 }
const obstacles = ref([]); 

// 計算屬性：格式化時間
const formattedTime = computed(() => {
  const mins = Math.floor(timeRemaining.value / 60);
  const secs = timeRemaining.value % 60;
  return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
});

// --- 遊戲核心邏輯 ---

// 繪製遊戲畫面
const drawGame = () => {
  if (!ctx.value) return;
  const c = ctx.value;
  const W = 375;
  const H = 500;

  // 1. 清空畫面 (道路與天空)
  c.fillStyle = '#87CEEB'; // 天空藍
  c.fillRect(0, 0, W, H / 2); // 畫面上半部分為天空

  c.fillStyle = '#666'; // 遠處地面/道路
  c.fillRect(0, H / 2, W, H / 2); 
  
  // 繪製道路 (簡單的透視效果)
  c.fillStyle = '#444'; 
  c.beginPath();
  // 道路消失點（靠近畫面中央上方）
  const vanishingPointY = H * 0.45; 
  c.moveTo(0, vanishingPointY); 
  c.lineTo(W, vanishingPointY);
  c.lineTo(W, H);
  c.lineTo(0, H);
  c.fill();
  
  // 繪製道路中線（可選）
  c.fillStyle = 'yellow';
  c.fillRect(W / 2 - 2, vanishingPointY, 4, H - vanishingPointY);


  // 2. 繪製玩家 (機車)
  const playerX = [W / 6, W / 2, W * 5 / 6][playerPosition.value];
  const playerY = H * 0.8;
  c.fillStyle = 'blue';
  c.fillRect(playerX - 20, playerY - 30, 40, 60); 

  // 3. 繪製障礙物
  obstacles.value.forEach(obs => {
    // distance 從 100 遞減到 0，代表從遠到近
    // 靠近時尺寸變大，最大為 50
    const size = 10 + (100 - obs.distance) * 0.4; 
    
    // Y 座標計算：從消失點 (vanishingPointY) 向下移動到 playerY
    // 當 distance = 100 時，Y 接近 vanishingPointY
    // 當 distance = 0 時，Y 接近 playerY
    const y = vanishingPointY + (100 - obs.distance) / 100 * (playerY - vanishingPointY);
    
    const x = [W / 6, W / 2, W * 5 / 6][obs.x];
    
    // 繪製物件
    c.fillStyle = obs.collided ? 'gray' : 'red'; // 碰撞後變灰
    c.fillRect(x - size / 2, y - size / 2, size, size);

    // 碰撞檢測 (簡化：只判斷距離最近的障礙物)
    // 當障礙物非常接近玩家時進行碰撞檢測
    if (obs.distance < 5 && obs.x === playerPosition.value && !obs.collided) {
      handleCollision(obs);
    }
  });
};

// 更新遊戲狀態 (每幀/每隔一段時間)
const updateGame = () => {
  if (!gameStarted.value || gameOver.value) return;

  // 1. 障礙物移動和生成
  obstacles.value = obstacles.value
    .map(obs => ({ ...obs, distance: obs.distance - 2 })) // 向前移動
    .filter(obs => obs.distance >= 0); // 移除通過的障礙物

  // 隨機生成新的障礙物
  if (Math.random() < 0.015 && obstacles.value.length < 5) { 
    // 確保場景中障礙物數量不會太多
    obstacles.value.push({
      x: Math.floor(Math.random() * 3), // 隨機在左中右 (0, 1, 2)
      distance: 100, // 從最遠處 (前上方) 開始
      collided: false,
    });
  }

  // 2. 檢查遊戲結束條件 (生命檢查)
  if (lives.value <= 0) {
    endGame(false); // 命用完了
  }

  drawGame();
};

// 處理碰撞事件
const handleCollision = (collidedObstacle) => {
  if (collidedObstacle.collided) return; // 避免重複碰撞

  lives.value--;
  collidedObstacle.collided = true; // 標記已碰撞
  // 清除所有已碰撞的障礙物，或只移除當前的
  obstacles.value = obstacles.value.filter(obs => obs !== collidedObstacle); 

  if (lives.value <= 0) {
    endGame(false);
  }
};

// 倒計時
const countdown = () => {
  countdownInterval = setInterval(() => {
    if (timeRemaining.value > 0) {
      timeRemaining.value--;
    } else {
      endGame(true); // 時間結束
    }
  }, 1000);
};

// --- 遊戲流程控制 ---

// 開始遊戲
const startGame = () => {
  if (gameStarted.value && !gameOver.value) return; 

  // 重置狀態
  lives.value = 3;
  timeRemaining.value = 60;
  playerPosition.value = 1;
  obstacles.value = [];
  gameStarted.value = true;
  gameOver.value = false;

  // 啟動遊戲循環
  gameLoopInterval = setInterval(updateGame, 1000 / 60); // 60 FPS
  countdown(); // 啟動時間計數
};

// 結束遊戲
const endGame = (timeUp) => {
  clearInterval(gameLoopInterval);
  clearInterval(countdownInterval);
  gameStarted.value = true;
  gameOver.value = true;

  // 判斷勝利
  if (timeUp && lives.value > 0) {
    console.log("勝利！時間到且生命仍存。");
  } else {
    console.log("遊戲結束！生命耗盡或時間到但生命為零。");
  }
};

// --- 玩家控制 ---

// 左右移動玩家
const movePlayer = (direction) => {
  // -1: 左, 1: 右
  const newPos = playerPosition.value + direction;
  if (newPos >= 0 && newPos <= 2) {
    playerPosition.value = newPos;
  }
};

// 處理鍵盤事件
const handleKeyDown = (event) => {
  if (!gameStarted.value || gameOver.value) return;
  if (event.key === 'ArrowLeft') {
    movePlayer(-1);
  } else if (event.key === 'ArrowRight') {
    movePlayer(1);
  }
};

// 處理手機滑動事件
let touchStartX = 0;
const handleTouchStart = (event) => {
  touchStartX = event.touches[0].clientX;
};

const handleTouchEnd = (event) => {
  if (!gameStarted.value || gameOver.value) return;

  const touchEndX = event.changedTouches[0].clientX;
  const diff = touchEndX - touchStartX;
  const threshold = 30; // 最小滑動距離

  if (diff > threshold) {
    movePlayer(1); // 向右滑動
  } else if (diff < -threshold) {
    movePlayer(-1); // 向左滑動
  }
};

// --- 組件生命週期 ---

onMounted(() => {
  ctx.value = gameCanvas.value.getContext('2d');
  
  // 綁定事件監聽
  window.addEventListener('keydown', handleKeyDown);
  gameCanvas.value.addEventListener('touchstart', handleTouchStart);
  gameCanvas.value.addEventListener('touchend', handleTouchEnd);
  
  // 首次繪製靜態畫面
  drawGame();
});

onUnmounted(() => {
  // 清除計時器和事件監聽
  clearInterval(gameLoopInterval);
  clearInterval(countdownInterval);
  window.removeEventListener('keydown', handleKeyDown);
  if (gameCanvas.value) {
    gameCanvas.value.removeEventListener('touchstart', handleTouchStart);
    gameCanvas.value.removeEventListener('touchend', handleTouchEnd);
  }
});
</script>
<style scoped>
.game-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

.game-window {
  position: relative;
  border: 4px solid black;
  overflow: hidden;
  background-color: #333; /* Canvas 外部背景 */
}

.hud {
  position: absolute;
  top: 10px;
  left: 10px;
  color: white;
  font-weight: bold;
  background-color: rgba(0, 0, 0, 0.5);
  padding: 5px 10px;
  border-radius: 5px;
}

.game-over-screen {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8);
  color: white;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
}

button {
  padding: 10px 20px;
  font-size: 1.2em;
  cursor: pointer;
  z-index: 10;
}
.game-over-screen {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.9); /* 更深的遮罩 */
  color: white;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  z-index: 5; /* 確保在 canvas 上方 */
}

.game-over-screen h3 {
    margin-bottom: 20px;
    font-size: 2em;
}

.restart-button {
    margin-top: 15px;
    padding: 10px 20px;
    font-size: 1.1em;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
}
</style>
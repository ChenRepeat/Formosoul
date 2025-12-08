<template>

  <div class="book-section">
    <div v-if="isAnimating" class="blocking-overlay"></div>

    <div 
      class="book" 
      ref="bookRef"
     :class="{ 
    'intro-center-pos': isIntroPosition,
    'flipping': isFlip  
    }"
    >
      <div class="page cover">
        <div class="page-content">
          <img src="../assets/BookCover.png" alt="" class="book-cover">
          <img src="../assets/logo_white.svg" alt="" class="book-logo">
        </div>
      </div>
      <div class="page">
        <div class="page-content text-page">
        </div>
      </div>
      <div class="page">
        <div class="page-content text-page">
          <ClassPageIndex @flip="goToPage"/>
        </div>
      </div>

      <div class="page">
        <div class="page-content" :class="{'op' :isFlip}">
          <MotorLeft  @flip="goToPage"/>
        </div>
      </div>

      <div class="page">
        <div class="page-content">
          <MotorRight  @flip="goToPage"/>
        </div>
      </div>

      <div class="page">
        <div class="page-content">
          <h3>咒語學：API 召喚術</h3>
          <div class="code-block">
            <span class="keyword">await</span> axios.<span class="func">get</span>(<span class="str">'/magic'</span>);
          </div>
          <p>揮動你的 Axios 魔杖，從遙遠的伺服器召喚數據精靈。</p>
          <hr class="divider">
          <p class="small-text">⚠️ 警告：若魔力不足 (404)，精靈將拒絕回應。</p>
        </div>
      </div>

      <div class="page">
        <div class="page-content">
          <h3>奇獸飼育學</h3>
          <div class="creature-card">
            <div class="creature-icon">🕷️</div>
            <h4>名稱：千年 Bug</h4>
            <p><strong>習性：</strong>總是在 Demo 前一刻出現，喜歡躲在非同步函式中。</p>
            <p><strong>剋星：</strong>Chrome DevTools 與 console.log 法陣。</p>
          </div>
        </div>
      </div>

      <div class="page">
        <div class="page-content">
          <h3>占卜學：未來之路</h3>
          <p>透過水晶球窺探畢業後的命運...</p>
          <ul class="prophecy-list">
            <li>🔮 <strong>全端大法師</strong> (Full Stack)</li>
            <li>🎨 <strong>UI 幻術師</strong> (Designer)</li>
            <li>🛡️ <strong>資安守護者</strong> (Security)</li>
          </ul>
          <p>命運掌握在你的 Commit 紀錄中。</p>
        </div>
      </div>

      <div class="page">
        <div class="page-content">
          <h3>禁忌森林入口</h3>
          <p>前方偵測到強大的魔力波動...</p>
          <div class="game-portal-icon">🌀</div>
          <router-link to="/parallax-test" class="magic-btn">
            進入遊戲測試
          </router-link>
          <p class="small-text">點擊按鈕進行空間跳躍</p>
        </div>
      </div>

      <div class="page">
        <div class="page-content image-page">
           <img src="https://picsum.photos/300/400?grayscale" alt="Old Library" />
        </div>
      </div>

      <div class="page">
        <div class="page-content equipment-page">
           
          
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <h3>冒險者背包</h3>
          <div class="inventory-grid">
            
            <div class="item-slot" title="新手劍" draggable="true" 
                 @dragstart="startDrag($event, '🗡️')" 
                 @mousedown.stop @touchstart.stop>
              🗡️
            </div>

            <div class="item-slot" title="回復藥水" draggable="true" 
                 @dragstart="startDrag($event, '🍷')" 
                 @mousedown.stop @touchstart.stop>
              🍷
            </div>

            <div class="item-slot" title="魔法卷軸" draggable="true" 
                 @dragstart="startDrag($event, '📜')" 
                 @mousedown.stop @touchstart.stop>
              📜
            </div>

            <div class="item-slot" title="世界地圖" draggable="true" 
                 @dragstart="startDrag($event, '🗺️')" 
                 @mousedown.stop @touchstart.stop>
              🗺️
            </div>

            <div class="item-slot empty"></div>
            <div class="item-slot empty"></div>
          </div>
          
          <div class="inventory-info">
             <p>金幣: 💰 1,250 G</p>
             <p>負重: ⚖️ 4/20</p>
          </div>
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <h3>角色狀態</h3>
          <div class="profile-box">
             <div class="avatar">🧙‍♂️</div>
             <h4>Level 5 見習巫師</h4>
          </div>
          
          <div class="stat-bars">
            <div class="stat-row">
              <span>HP</span>
              <div class="bar-container"><div class="bar red" style="width: 80%"></div></div>
            </div>
            <div class="stat-row">
              <span>MP</span>
              <div class="bar-container"><div class="bar blue" style="width: 45%"></div></div>
            </div>
            <div class="stat-row">
              <span>EXP</span>
              <div class="bar-container"><div class="bar green" style="width: 30%"></div></div>
            </div>
          </div>
        </div>
      </div>
      <div class="page">
      <div class="page-content">
        <ClassPageIndex @flip="goToPage"/>
      </div>
      </div>
            <div class="page">
      <div class="page-content">

      </div>
      </div>
      <div class="page cover">
        <div class="page-content">
          <img src="../assets/BookCover.png" alt="" class="book-cover book-end">

          <h3>The End</h3>
          <p>© 2025 Class Project</p>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import { PageFlip } from 'page-flip';
import ClassPageIndex from '@/components/ClassPages/ClassPageIndex.vue';
import MotorLeft from '@/components/ClassPages/MotorLeft.vue';
import MotorRight from '@/components/ClassPages/MotorRight.vue';
import siteLogo from '@/assets/logo_white.svg'; 

const bookRef = ref(null);
const isAnimating = ref(true); // 鎖定互動
const isFlip = ref (false);
const isIntroPosition = ref(true); 
const currentPage = ref(0);
const totalPages = ref(0);
const FLIP_SPEEDS = {
  intro: 400,
  normal: 800,
  coverClose: 1500,
};

let pageFlip = null;

// --- 輔助函式 ---
const wait = (ms) => new Promise(resolve => setTimeout(resolve, ms));

const updatePageNumber = () => {
  if (!pageFlip) return;
  currentPage.value = pageFlip.getCurrentPageIndex();
  totalPages.value = pageFlip.getPageCount();
};

// --- 開場動畫邏輯 ---
const playIntroAnimation = async () => {
  if (!pageFlip) return;

  await wait(800);
  isIntroPosition.value = false;
  await wait(1600);

  // 使用變數控制翻頁速度
  for (let i = 0; i < totalPages.value/2; i++) {
    pageFlip.flipNext();
    await wait(FLIP_SPEEDS.intro);
  }

  await wait(FLIP_SPEEDS.coverClose);
  pageFlip.flip(0);
  await wait(FLIP_SPEEDS.coverClose);

  pageFlip.flip(1);
  await wait(600);
  
  isAnimating.value = false;
  updatePageNumber(); // 動畫結束後更新頁碼
};

const goToPage = async (pageNum) => {
  if (!pageFlip || isAnimating.value) return;
  
  if (pageNum > currentPage.value) {
    isAnimating.value = true;
    const pagesToFlip = pageNum - currentPage.value;

    for (let i = 0; i < pagesToFlip; i++) {
      pageFlip.flipNext();
      await wait(FLIP_SPEEDS.intro);
    }

    updatePageNumber();
    isAnimating.value = false;

  } else if (pageNum < currentPage.value) {

    isAnimating.value = true;
    const pagesToFlip = currentPage.value/2 - pageNum;
    for (let i = 0; i < pagesToFlip; i++) {
      pageFlip.flipPrev();
      await wait(FLIP_SPEEDS.intro);
    }
    updatePageNumber();
    isAnimating.value = false;

  }
};


onMounted(() => {
  pageFlip = new PageFlip(bookRef.value, {
    width: 600,
    height: 800,
    size: 'fixed',
    showCover: true,
    maxShadowOpacity: 0.2,
    flippingTime: FLIP_SPEEDS.normal, // 使用變數
  });

  pageFlip.loadFromHTML(bookRef.value.querySelectorAll('.page'));

  pageFlip.on('flip', () => {
    updatePageNumber();
  });

  pageFlip.on('changeState', (e) => {
    if (e.data === 'read') {
        isFlip.value = false; 
        updatePageNumber(); 
    } else {
        if (e.data === 'flipping') {
            isFlip.value = true;
        }
    }
});
  // 初始化頁碼
  totalPages.value = pageFlip.getPageCount();

  playIntroAnimation();
});

onUnmounted(() => {
  if (pageFlip) pageFlip.destroy();
})

</script>

<style lang="scss" scoped>

.book-section {
  width: 100%; 
  height: 950px;
  display: flex;
  justify-content: center;
  align-items: center;
  // background-color: $color-fsTitle;
  overflow: hidden; 
  user-select: none; 
}
.book {
  // filter: drop-shadow(0 20px 20px rgba(0, 0, 0, 0.5)); 書本陰影
  transition: transform 1.5s cubic-bezier(0.2, 0.8, 0.2, 1);
}
.blocking-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9999;
  cursor: wait; 
  background: rgba(0,0,0,0);
}
.intro-center-pos {
  transform: translateX(-25%) scale(0.9); 
}

.page {
  padding: 20px;
  background-color: #ccc;
  // 實作超出頁面的圖片
  // overflow: hidden;
  transition: none; 
  transform-style: preserve-3d;
  border-radius: 0 16px 16px 0;
  // box-shadow:
  //  6px 1px 20px $color-fsWhite,
  //  6px 1px 20px $color-fsGold300,
  //  12px -3px 20px $color-fsWhite,
  //  12px -3px 6px $color-fsGold300;
  // overflow:;
  opacity: 1;
}

.page::before {
  content: '';
  position: absolute;
  top: 0; left: 0; width: 100%; height: 100%;
  background-image: url('https://www.transparenttextures.com/patterns/paper.png');
  opacity: 0.4;
  pointer-events: none;
}

.book-cover {
  position: absolute;
  transform: scale(1.07);
  box-shadow: unset;
}
.book-end{
  transform: rotate(180deg) scale(1.07);
}
.book-logo{
  position: absolute;
  transform: scale(1.2);

}
.page-content {
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
}

h1, h3, h4 {
  font-family: "Times New Roman", serif;
  margin-bottom: 10px;
  color: #4a3b2a;
}
.cover h1, .cover h3 { color: #e0d5c1; }
.--left{
  border-radius: 16px 0 0 16px;
  border-right: 2px solid $color-fsTitle;
}
/* --- 舊有的樣式 --- */
.decoration { font-size: 2rem; margin: 10px 0; }
.hint { font-size: 0.8rem; opacity: 0.8; }
.magic-list { text-align: left; list-style: none; padding: 0; margin-top: 20px; font-family: 'Courier New', Courier, monospace; }
.magic-list li { margin-bottom: 15px; font-size: 1.1rem; border-bottom: 1px dashed #c2b5a3; padding-bottom: 5px; }
.potion-image-box { font-size: 4rem; margin: 20px 0; filter: drop-shadow(0 0 10px rgba(100, 200, 100, 0.5)); }
img { max-width: 100%; border-radius: 4px; box-shadow: 2px 2px 5px rgba(0,0,0,0.2); }
.code-block { background: #282c34; color: #abb2bf; padding: 15px; border-radius: 5px; font-family: 'Courier New', monospace; margin: 20px 0; box-shadow: inset 0 0 10px #000; text-align: left; width: 90%; }
.keyword { color: #c678dd; } .func { color: #61afef; } .str { color: #98c379; }
.divider { width: 50%; border-top: 1px solid #c2b5a3; margin: 20px 0; }
.small-text { font-size: 0.8rem; color: #888; }
.creature-card { border: 2px dashed #8b4513; padding: 15px; border-radius: 10px; background: rgba(139, 69, 19, 0.05); }
.creature-icon { font-size: 3rem; margin-bottom: 10px; }
.prophecy-list { list-style: none; padding: 0; margin: 20px 0; text-align: left; }
.prophecy-list li { font-size: 1.2rem; margin-bottom: 10px; padding: 5px; border-bottom: 1px solid rgba(0,0,0,0.1); }
.magic-btn { display: inline-block; margin-top: 15px; padding: 12px 24px; background: #8b4513; color: #fff; text-decoration: none; border-radius: 30px; border: 2px solid #e0d5c1; font-family: "Times New Roman", serif; font-weight: bold; letter-spacing: 1px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); transition: all 0.3s ease; cursor: pointer; position: relative; z-index: 10; }
.magic-btn:hover { background: #a0522d; transform: scale(1.1) translateY(-2px); box-shadow: 0 0 15px rgba(255, 215, 0, 0.6); }
.magic-btn:active { transform: scale(0.95); }
.game-portal-icon { font-size: 4rem; margin: 15px 0; animation: spinPortal 3s linear infinite; }
@keyframes spinPortal { 0% { transform: rotate(0deg) scale(1); filter: hue-rotate(0deg); } 50% { transform: rotate(180deg) scale(1.1); filter: hue-rotate(90deg); } 100% { transform: rotate(360deg) scale(1); filter: hue-rotate(0deg); } }

/* --- 🔥 新增頁面樣式 (Inventory & Status) --- */

/* 背包網格 */
.inventory-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
  margin: 20px 0;
}
.item-slot {
  width: 60px;
  height: 60px;
  background: rgba(0,0,0,0.05);
  border: 2px inset #c2b5a3;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 2rem;
  border-radius: 5px;
  cursor: help;
}
.item-slot:hover {
  background: rgba(139, 69, 19, 0.1);
  transform: scale(1.05);
}
.item-slot.empty {
  opacity: 0.3;
}
.inventory-info {
  width: 100%;
  display: flex;
  justify-content: space-around;
  font-family: 'Courier New', monospace;
  font-weight: bold;
  color: #555;
  border-top: 1px dashed #aaa;
  padding-top: 10px;
}

/* 角色狀態 */
.profile-box {
  margin-bottom: 20px;
}
.avatar {
  font-size: 4rem;
  background: #eee;
  width: 100px;
  height: 100px;
  line-height: 100px;
  border-radius: 50%;
  margin: 0 auto 10px;
  border: 4px double #8b4513;
}

.stat-bars {
  width: 100%;
  padding: 0 10px;
}
.stat-row {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
  gap: 10px;
}
.stat-row span {
  width: 40px;
  font-weight: bold;
  font-family: monospace;
}
.bar-container {
  flex-grow: 1;
  height: 15px;
  background: #ddd;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
}
.bar { height: 100%; border-radius: 10px; }
.bar.red { background: linear-gradient(90deg, #ff6b6b, #e74c3c); }
.bar.blue { background: linear-gradient(90deg, #4facfe, #00f2fe); }
.bar.green { background: linear-gradient(90deg, #4cd964, #2ecc71); }

/* --- Page 10 裝備區樣式 --- */
.equipment-page {
  position: relative;
  z-index: 1;
}

/* 裝備格子 (Drop Zone) */
.equipment-slot {
  width: 120px;
  height: 120px;
  margin: 20px auto;
  border: 3px dashed #8b4513;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: all 0.3s;
  box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

/* 拖曳經過時的高亮 (可選，需配合 JS dragenter，這邊先簡單做) */
.equipment-slot:hover {
  border-color: #d35400;
  background: rgba(255, 255, 255, 0.95);
  transform: scale(1.05);
}

/* 裝備上去後的樣式 */
.has-item {
  border-style: solid;
  border-color: #27ae60;
  background: #fff;
}

.equipped-icon {
  font-size: 4rem;
  animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.placeholder-text {
  color: #aaa;
  font-size: 0.8rem;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.equip-status {
  background: rgba(0,0,0,0.7);
  color: #fff;
  padding: 5px 15px;
  border-radius: 20px;
  margin-top: 10px;
}

/* 背景圖稍微淡化，讓裝備欄清楚一點 */
.bg-img {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  width: 80%;
  opacity: 0.3;
  z-index: -1;
  pointer-events: none;
}
:deep(.stf__item.--cursor) {
  z-index: 1000 !important;
}

$high-layer-pages: 4, 5, 8, 12; 
.book:not(.flipping) {
  @each $index in $high-layer-pages {
    :deep(.stf__item:nth-child(#{$index})) {
      z-index: 50 !important;
    }
  }
}

@keyframes popIn {
  from { transform: scale(0); }
  to { transform: scale(1); }
}
</style>
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
        <div class="page-content">
          <ClassPageIndex @flip="goToPage" v-if="innerWidth < 992" />
        </div>
      </div>
      <div class="page" v-if="innerWidth > 992">
        <div class="page-content" >
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
          <BikeRule @flip="goToPage"/>
        </div>
      </div>

      <div class="page">
        <div class="page-content">
          bikeGame
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <EndemicSpeciesLeft @flip="goToPage"/>
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <EndemicSpeciesRight @flip="goToPage"/>

        </div>
      </div>
      <div class="page">
        <div class="page-content">
        </div>
      </div>

      <div class="page">
        <div class="page-content">
          <HistoryOfMagicIndex @flip="goToPage"/>
        </div>
      </div>

      <div class="page">
        <div class="page-content">
           <NewYearLeft @flip="goToPage"/>
        </div>
      </div>

      <div class="page">
        <div class="page-content">
           <NewYearRight @flip="goToPage"/>
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <DuanOoLeft @flip="goToPage"/>
        </div>
      </div>
        <div class="page">
        <div class="page-content">
          <DuanOoRight @flip="goToPage"/>
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <MoonFesLeft @flip="goToPage"/>
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <MoonFesRight  @flip="goToPage"/>
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <CharmLeft @flip="goToPage"/>
        </div>
      </div>
      <div class="page">
        <div class="page-content">
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <PotionLeft  @flip="goToPage"/>
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          Potion game
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <DivinationLeft />
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <DivinationRight />
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
import BikeRule from '@/components/ClassPages/BikeRule.vue';
import HistoryOfMagicIndex from '@/components/ClassPages/HistoryOfMagicIndex.vue';
import EndemicSpeciesLeft from '@/components/ClassPages/EndemicSpeciesLeft.vue';
import EndemicSpeciesRight from '@/components/ClassPages/EndemicSpeciesRight.vue';
import NewYearLeft from '@/components/ClassPages/NewYearLeft.vue';
import NewYearRight from '@/components/ClassPages/NewYearRight.vue';
import MoonFesLeft from '@/components/ClassPages/MoonFesLeft.vue';
import MoonFesRight from '@/components/ClassPages/MoonFesRight.vue';
import DuanOoLeft from '@/components/ClassPages/DuanOoLeft.vue';
import DuanOoRight from '@/components/ClassPages/DuanOoRight.vue';
import PotionLeft from '@/components/ClassPages/PotionLeft.vue';
import CharmLeft from '@/components/ClassPages/CharmLeft.vue';
import DivinationLeft from '@/components/ClassPages/DivinationLeft.vue';
import DivinationRight from '@/components/ClassPages/DivinationRight.vue';

const bookRef = ref(null);
const isAnimating = ref(true); // 鎖定互動
const isFlip = ref (false);
const isIntroPosition = ref(true); 
const currentPage = ref(0);
const totalPages = ref(0);
const innerWidth=ref(window.innerWidth)
let animationTimeoutId = null;
const FLIP_SPEEDS = {
  intro: 400,
  normal: 800,
  coverClose: 1000,
};

let pageFlip = null;
let isIntroPlaying = ref(false);

// --- 輔助函式 ---
const wait = (ms) => new Promise(resolve => {
  animationTimeoutId = setTimeout(resolve, ms);
  setTimeout(resolve, ms)});

const handleKeydown = (event) => {
    if (event.key === 'Escape' && isIntroPlaying.value) {
        clearTimeout(animationTimeoutId); 
        if (pageFlip) {
            goToPage(currentPage); 
            isIntroPlaying.value = false;
            isAnimating.value = false;
            isIntroPosition.value = false;
            updatePageNumber(); 
        }
        console.log('Intro animation interrupted by Esc key.');
    }
};

const updatePageNumber = () => {
  if (!pageFlip) return;
  if(window.innerWidth >= 992){
    currentPage.value = Math.floor(pageFlip.getCurrentPageIndex() / 2);
    totalPages.value = Math.floor(pageFlip.getPageCount()/2);
  }else{
    totalPages.value = pageFlip.getPageCount();
    currentPage.value = pageFlip.getCurrentPageIndex();
  }
};
// --- 開場動畫邏輯 ---
const playIntroAnimation = async () => {
  if (!pageFlip) return;
  await wait(800);
  isIntroPlaying.value = true;
  if (!isIntroPlaying.value) return;
  isIntroPosition.value = false;
  await wait(1600);
  if (!isIntroPlaying.value) return;

  // 使用變數控制翻頁速度

  for (let i = 0; i < totalPages.value; i++) {
    pageFlip.flipNext();
    await wait(FLIP_SPEEDS.intro);
    if (!isIntroPlaying.value) return;
  }

  await wait(FLIP_SPEEDS.coverClose);
  pageFlip.flip(0);
  if (!isIntroPlaying.value) return;
  await wait(FLIP_SPEEDS.coverClose);
  if (!isIntroPlaying.value) return;
  pageFlip.flip(1);
  await wait(600);
  if (!isIntroPlaying.value) return;
  isAnimating.value = false;
  updatePageNumber(); // 動畫結束後更新頁碼
};

const goToPage = async (pageNum) => {
  if (!pageFlip || isAnimating.value || isIntroPlaying.value) return;
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
    minWidth : 375,
    maxWidth : 600,
    minHeight : 500,
    maxHeight : 800,
    size: 'stretch',
    showCover: true,
    maxShadowOpacity: 0.2,
    flippingTime: FLIP_SPEEDS.normal,
    flippingBoundary: 10,
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

  totalPages.value = pageFlip.getPageCount();

  playIntroAnimation();
  window.addEventListener('keyup', handleKeydown);
});

onUnmounted(() => {
  if (pageFlip) pageFlip.destroy();
  window.removeEventListener('keyup', handleKeydown);
  clearTimeout(animationTimeoutId);
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

//  書頁樣式
.page {
  padding: 40px;
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
  // background-image: url('https://www.transparenttextures.com/patterns/paper.png');
  opacity: 0.4;
  pointer-events: none;
}

.book-cover {
  position: absolute;
  transform: scaleX(1.05);
  box-shadow: unset;
}
.book-end{
  transform: rotate(180deg) scaleX(1.05);
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
img { max-width: 100%; border-radius: 4px; box-shadow: 2px 2px 5px rgba(0,0,0,0.2); }



:deep(.stf__item.--cursor) {
  z-index: 1000 !important;
}

$high-layer-pages: 4, 6, 9, 12; 
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
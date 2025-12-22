<template>
  <div class="book-section">
    <div v-if="isAnimating" class="blocking-overlay"><h4>{{$t('classes.escTip')}}</h4></div>

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
          <img src="../assets/LOGO_whiteColor.svg" alt="" class="book-logo">
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <!-- <ClassPageIndex @flip="goToPage" /> -->
        </div>
      </div>
      <div class="page">
        <div class="page-content" >
          <ClassPageIndex @flip="goToPage"/>
        </div>
      </div>

      <div class="page">
        <div class="page-content" :class="{'op': isFlip}">
          <MotorLeft @flip="goToPage"/>
        </div>
      </div>

      <div class="page">
        <div class="page-content">
          <MotorRight @flip="goToPage"/>
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
          <Maho />
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
          <MoonFesRight @flip="goToPage"/>
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <CharmLeft @flip="goToPage" :shared-image="sharedImage"/>
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <CharmRight  @flip="goToPage" @save-image="handleReceiveFromA"/>
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <PotionLeft @flip="goToPage"/>
        </div>
      </div>
      <div class="page">
        <div class="page-content">
          <PotionRight />
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
import { onMounted, onUnmounted, ref, computed, watch } from 'vue';
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
import PotionRight from '@/components/ClassPages/PotionRight.vue';
import CharmLeft from '@/components/ClassPages/CharmLeft.vue';
import DivinationLeft from '@/components/ClassPages/DivinationLeft.vue';
import DivinationRight from '@/components/ClassPages/DivinationRight.vue';
import CharmRight from '@/components/ClassPages/CharmRight.vue';
import Maho from '@/components/ClassPages/Maho.vue';

const bookRef = ref(null);
const isAnimating = ref(true);
const isFlip = ref(false);
const isIntroPosition = ref(true); 
const currentPage = ref(0);
const totalPages = ref(0);
const innerWidth = ref(window.innerWidth);
const isIntroPlaying = ref(false);

let animationTimeoutId = null;
let resizeTimeoutId = null;
let pageFlip = null;

const FLIP_SPEEDS = {
  intro: 400,
  normal: 800,
  coverClose: 1000,
};

const RESIZE_DEBOUNCE_DELAY = 300;

// 判斷是否為雙頁模式
const isDoublePage = computed(() => innerWidth.value >= 992);

// --- 邏輯頁碼與物理索引的轉換 ---
const getLogicalPage = (physicalIndex) => {
  return isDoublePage.value 
    ? Math.floor(physicalIndex / 2) 
    : physicalIndex;
};

const getPhysicalIndex = (logicalPage) => {
  return isDoublePage.value 
    ? logicalPage * 2 
    : logicalPage;
};

// 計算實際頁碼範圍
const pageRange = computed(() => {
  const maxLogicalPage = isDoublePage.value 
  ? Math.floor((totalPages.value - 1) / 2)  // 雙頁模式
  : totalPages.value - 1;                    // 單頁模式
  return {
    min: 0,
    max: maxLogicalPage
  };
});

// --- 輔助函式 ---
const wait = (ms) => new Promise(resolve => {
  animationTimeoutId = setTimeout(resolve, ms);
});

// 清理動畫狀態
const cleanupAnimation = () => {
  if (animationTimeoutId) {
    clearTimeout(animationTimeoutId);
    animationTimeoutId = null;
  }
  isIntroPlaying.value = false;
  isAnimating.value = false;
};

// 清理 resize timeout
const cleanupResize = () => {
  if (resizeTimeoutId) {
    clearTimeout(resizeTimeoutId);
    resizeTimeoutId = null;
  }
};

const handleKeydown = (event) => {
  if (event.key === 'Escape' && isIntroPlaying.value) {
    console.log('Intro animation interrupted by Esc key.');
    cleanupAnimation();
    
    if (pageFlip) {
      const actualPage = pageFlip.getCurrentPageIndex();
      pageFlip.flip(actualPage);
      isIntroPosition.value = false;
      updatePageNumber();
    }
  }
};

const updatePageNumber = () => {
  if (!pageFlip) return;
  
  currentPage.value = getLogicalPage(pageFlip.getCurrentPageIndex());
  totalPages.value = pageFlip.getPageCount();
};

// --- Resize 處理 ---
const handleResize = () => {
  cleanupResize();
  
  resizeTimeoutId = setTimeout(() => {
    const newWidth = window.innerWidth;
    const oldIsDoublePage = isDoublePage.value;
    
    innerWidth.value = newWidth;
    const newIsDoublePage = newWidth >= 992;
    
    // 只在模式切換時需要特殊處理
    if (oldIsDoublePage !== newIsDoublePage && pageFlip) {
      handleModeSwitch(oldIsDoublePage, newIsDoublePage);
    } else if (pageFlip) {
      // 同模式下只需更新頁碼顯示
      updatePageNumber();
    }
  }, RESIZE_DEBOUNCE_DELAY);
};

// 處理單雙頁模式切換
const handleModeSwitch = (wasDoublePage, isNowDoublePage) => {
  if (!pageFlip) return;
  
  const currentPhysicalIndex = pageFlip.getCurrentPageIndex();
  let targetLogicalPage;
  
  if (wasDoublePage && !isNowDoublePage) {
    // 雙頁 → 單頁：保持在左頁
    targetLogicalPage = Math.floor(currentPhysicalIndex / 2) * 2;
  } else {
    // 單頁 → 雙頁：保持當前邏輯頁
    targetLogicalPage = Math.floor(currentPhysicalIndex / 2);
  }
  
  // 重新定位到正確的頁面
  const targetPhysicalIndex = isNowDoublePage 
    ? targetLogicalPage * 2 
    : targetLogicalPage;
  
  pageFlip.flip(targetPhysicalIndex);
  updatePageNumber();
  
  console.log(`Mode switched: ${wasDoublePage ? 'Double' : 'Single'} → ${isNowDoublePage ? 'Double' : 'Single'}, Page: ${targetLogicalPage}`);
};

// --- 開場動畫邏輯 ---
const playIntroAnimation = async () => {
  if (!pageFlip) return;
  
  try {
    await wait(800);
    if (!isIntroPlaying.value) return;
    
    isIntroPosition.value = false;
    await wait(1600);
    if (!isIntroPlaying.value) return;

    // 快速翻頁展示
    const physicalTotalPages = pageFlip.getPageCount();
     const logicalTotalPages = isDoublePage.value 
     ? Math.ceil(physicalTotalPages / 2)  // 雙頁模式
     : physicalTotalPages;                 // 單頁模式
    for (let i = 0; i < logicalTotalPages; i++) {
      if (!isIntroPlaying.value) return;
      pageFlip.flipNext();
      await wait(FLIP_SPEEDS.intro);
    }

    if (!isIntroPlaying.value) return;
    await wait(FLIP_SPEEDS.coverClose);
    
    // 返回封面
    if (!isIntroPlaying.value) return;
    pageFlip.flip(0);
    await wait(FLIP_SPEEDS.coverClose);
    
    // 翻到第一頁
    if (!isIntroPlaying.value) return;
    pageFlip.flip(1);
    await wait(600);
    
    if (!isIntroPlaying.value) return;
    isAnimating.value = false;
    updatePageNumber();
    cleanupAnimation();

  } catch (error) {
    console.error('Intro animation error:', error);
    cleanupAnimation();
  }
};

// --- 改進的 goToPage 邏輯 ---
const goToPage = async (targetLogicalPage) => {
  // 新增: 邏輯頁碼轉物理索引
const targetPhysicalIndex = getPhysicalIndex(targetLogicalPage);
const currentPhysicalIndex = pageFlip.getCurrentPageIndex();

// 使用物理距離計算翻頁次數
const physicalDistance = Math.abs(targetPhysicalIndex - currentPhysicalIndex);
  // 驗證輸入
  if (!pageFlip || isAnimating.value || isIntroPlaying.value) {
    console.warn('Cannot flip: animation in progress or pageFlip not ready');
    return;
  }

  // 邊界檢查
  const { min, max } = pageRange.value;
  if (targetLogicalPage < min || targetLogicalPage > max) {
    console.warn(`Target page ${targetLogicalPage} out of range [${min}, ${max}]`);
    return;
  }

  // 已經在目標頁
  if (targetLogicalPage === currentPage.value) {
    return;
  }

  try {
    isAnimating.value = true;
    const pagesToFlip = Math.abs(targetLogicalPage - currentPage.value);
    const isForward = targetLogicalPage > currentPage.value;

    // 逐頁翻轉動畫
    for (let i = 0; i < pagesToFlip; i++) {
      if (isForward) {
        pageFlip.flipNext();
      } else {
        pageFlip.flipPrev();
      }
      await wait(FLIP_SPEEDS.intro);
    }

    updatePageNumber();
    
    // 驗證是否到達目標頁
    if (currentPage.value !== targetLogicalPage) {
      console.warn(`Failed to reach target page. Current: ${currentPage.value}, Target: ${targetLogicalPage}`);
    }
  } catch (error) {
    console.error('Error in goToPage:', error);
  } finally {
    isAnimating.value = false;
  }
};


const initPageFlip = () => {
  if (!bookRef.value) return;
  
  pageFlip = new PageFlip(bookRef.value, {
    width: 600,
    height: 800,
    minWidth: 375,
    maxWidth: 600,
    minHeight: 500,
    maxHeight: 800,
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
    } else if (e.data === 'flipping') {
      isFlip.value = true;
    }
  });

  totalPages.value = pageFlip.getPageCount();
};

watch(isDoublePage, (newVal, oldVal) => {
  if (newVal !== oldVal) {
    console.log(`Display mode changed to: ${newVal ? 'Double Page' : 'Single Page'}`);
  }
});

onMounted(() => {
  initPageFlip();
  
  isIntroPlaying.value = true;
  playIntroAnimation();
  window.addEventListener('keydown', handleKeydown);
  window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
  cleanupAnimation();
  cleanupResize();
  
  if (pageFlip) {
    pageFlip.destroy();
    pageFlip = null;
  }
  
  window.removeEventListener('keydown', handleKeydown);
  window.removeEventListener('resize', handleResize);
});
const sharedImage = ref('');

const handleReceiveFromA = (url) => {
  sharedImage.value = url;
};
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
  h4{
    position: absolute;
    width: 100%;
    text-align: center;
    bottom: 10%;
    color: white;
    opacity: 0.3;
  }
}

.intro-center-pos {
  transform: translateX(-25%) scale(0.9); 
}

// 書頁樣式
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
  opacity: 1;
}

.page::before {
  content: '';
  position: absolute;
  top: 0; 
  left: 0; 
  width: 100%; 
  height: 100%;
  // background-image: url('https://www.transparenttextures.com/patterns/paper.png');
  opacity: 0.4;
  pointer-events: none;
}

.book-cover {
  position: absolute;
  transform: scaleX(1.05);
  box-shadow: unset;
}

.book-end {
  transform: rotate(180deg) scaleX(1.05);
}
@media (max-width: 749px) {
  .book-end {
    transform: rotate(0deg) scaleX(1.05);
  }
}
.book-logo {
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

.cover h1, .cover h3 { 
  color: #e0d5c1; 
}

.--left {
  border-radius: 16px 0 0 16px;
  border-right: 2px solid $color-fsTitle;
}

img { 
  max-width: 100%; 
  border-radius: 4px; 
  box-shadow: 2px 2px 5px rgba(0,0,0,0.2); 
}

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
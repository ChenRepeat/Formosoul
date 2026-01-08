<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const containerSelector = 'main';

const showBtn = ref(false);
const btnOffsetBottom = ref('1%');

// 加入一個鎖定變數，避免重複呼叫
let ticking = false; 

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

// 將邏輯拆分出來
const updatePosition = () => {
  const scrollY = window.scrollY;
  const innerHeight = window.innerHeight;
  
  // 1. 判斷顯示
  showBtn.value = scrollY > innerHeight * 0.3;

  // 2. 判斷位置 (沈重的計算放在這裡)
  if (containerSelector) {
    const container = document.querySelector(containerSelector);
    if (container) {
      const rect = container.getBoundingClientRect(); // 這裡最耗效能
      const gap = innerHeight - rect.bottom; 
      
      // 使用 px 計算通常比 calc 在動畫中更穩定一點，這裡維持您的邏輯
      if (gap > 0) {
        btnOffsetBottom.value = `calc(1% + ${gap}px)`;
      } else {
        btnOffsetBottom.value = '1%';
      }
    }
  }
  
  // 執行完畢，解鎖，允許下一次請求
  ticking = false;
};

const handleScroll = () => {
  // 如果上一幀還沒執行完，就不要重複請求
  if (!ticking) {
    window.requestAnimationFrame(updatePosition);
    ticking = true;
  }
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true }); // 加入 passive: true 優化
  updatePosition(); // 初始化執行一次
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
  <Transition name="fade">
    <div 
      v-if="showBtn" 
      class="toTop" 
      @click="scrollToTop"
      :style="{ bottom: btnOffsetBottom }"
    >
      <div v-for="index in 4"
        :key="index"
        class="bar"
        :class="`bar${index}`"></div>
    </div>
  </Transition>
</template>

<style lang="scss" scoped>
$color-fsWhite: #ffffff; 

.toTop {
  position: fixed;
  z-index: 9999;
  right: 1%;
  cursor: pointer;
  width: 30px;
  height: 30px;
  // border: 1px solid red;
  border-radius: 50%;
  filter: drop-shadow(0 0 100px $color-fsWhite);
  transition: opacity 0.3s, transform 0.3s, filter 0.3s;

  .bar {
    width: 35%;
    height: 1px;
    background-color: $color-fsWhite;
    position: absolute;
    transform-origin: 50% 100%;
    
    &.bar1, &.bar2 { transform: rotate(50deg); }
    &.bar3, &.bar4 { transform: rotate(-50deg); }
    &.bar1 { top: 35%; right: 23.5%; }
    &.bar2 { top: 60%; right: 23.5%; }
    &.bar3 { top: 35%; left: 23%; }
    &.bar4 { top: 60%; left: 23%; }
  }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
</style>
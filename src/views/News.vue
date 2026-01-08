<script setup>
import { ref, onMounted, onUnmounted, computed, nextTick } from "vue";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";
import Lenis from "lenis";
import NewsCard from "@/components/News/NewsCard.vue";
// 1. 記得引入 onBeforeRouteLeave
import { onBeforeRouteLeave } from 'vue-router'
import Backgroundaction from "@/components/backgroundaction.vue";
import { useNewsData } from "@/stores/news";
import ToTopBottom from "@/components/ToTopBottom.vue";
import { useRoute } from 'vue-router'; 

const route = useRoute();

// 連接 php
const baseUrl = import.meta.env.BASE_URL;
const newsDataStore = useNewsData(); // 吃pinia
// 用computed 從pinia回傳資料到vue檔
const allNewsData = computed(() => {
  return newsDataStore.allNewsData;
})


// 註冊 GSAP Plugin
gsap.registerPlugin(ScrollTrigger);

const mainSection = ref(null);
const ctx = ref(null); // 用來清理 GSAP 動畫
const lenis = ref(null); // Lenis 實例

// 1. 視差漂浮卡片資料 (Parallax Items)
const cards = ref ([])
const randomCard=()=>{
  const cardLoop = (num)=>{
    for(let i = 0; i < num ; i++){
        const rs = Math.ceil(Math.random()*300) - 150;
        const rW = Math.ceil(Math.random()*120) + 280;
        let rX = Math.ceil(Math.random()*20);
        const rY = Math.ceil(Math.random()*6);
        if(i%2 == 0){
          rX += 50;
        }else{
          rX = 30 - rX
        }
        cards.value.push({
          id : i+1,
          src: `${ baseUrl }${ allNewsData.value[i].pic }`,
          style: { width:`${rW}px`,height:`${rW}px`,top: `${rY+ 10 * i}%`, right: `${rX}%`},
          speed: rs,
        })
      }
  }
  if(allNewsData.value.length <10){
    cardLoop(allNewsData.value.length)
  }else{
    cardLoop(10)
  }
}
// 2. 最新消息資料
// 定義 ticker 更新函式 (放在 setup 裡，onMounted 之外)，以便新增與移除時能指涉到同一個函式
const updateLenis = (time) => {
  if (lenis.value) {
    lenis.value.raf(time * 1000); // 毫秒轉換
  }
};

onMounted(async () => {
  // 1. 等待資料抓取完成
  await newsDataStore.get_newsinfo();
  
  // 2. 產生卡片資料
  randomCard();
  
  // 3. ★★★ 修正點：加上 await，確保 Vue 把卡片畫到畫面上 ★★★
  await nextTick();

  // 4. 初始化 Lenis
  lenis.value = new Lenis({
    duration: 1.5,
    smooth: true,
  });

  // 處理重新整理 (Reload)
  if (route.hash) {
    const target = document.querySelector(route.hash);
    if (target) {
      ScrollTrigger.refresh();
      lenis.value.scrollTo(target, { offset: 0, immediate: false });
    }
  } 
  else {
    window.scrollTo(0, 0);
    lenis.value.scrollTo(0, { immediate: true });
  }
  // 5. 連接 ScrollTrigger
  lenis.value.on('scroll', ScrollTrigger.update);

  // 6. 加入 Ticker
  gsap.ticker.add(updateLenis);

  // 7. 關閉延遲平滑
  gsap.ticker.lagSmoothing(0);

  // 8. 設定 GSAP 動畫 Context
  ctx.value = gsap.context(() => {
    ScrollTrigger.create({
      trigger: mainSection.value,
      start: "top top",
      end: "bottom bottom",
      pin: ".news-pin-target",
      pinSpacing: false,
    });

    // 這裡因為有 await nextTick()，所以現在抓得到 DOM 元素了
    const parallaxCards = document.querySelectorAll(".news-parallax-card");
    
    parallaxCards.forEach((el) => {
      // 加上防呆，避免某些元素沒有 speed 屬性
      const speedStr = el.getAttribute("data-speed");
      const speed = speedStr ? parseFloat(speedStr) : 0; 
      
      gsap.to(el, {
        y: speed,
        ease: "none",
        scrollTrigger: {
          trigger: mainSection.value,
          start: "top top",
          end: "bottom bottom",
          scrub: 0,
        },
      });
    });
  }, mainSection.value);

  window.scrollTo({ top: 0, behavior: 'auto' });
});

// 路由離開攔截
onBeforeRouteLeave((to, from, next) => {
  if (lenis.value) {
    lenis.value.destroy(); 
    lenis.value = null;
  }
  next();
});

// 元件卸載清理
onUnmounted(() => {
  if (ctx.value) ctx.value.revert();
  
  // ★★★ 修正點：移除正確的函式 ★★★
  gsap.ticker.remove(updateLenis);
  
  if (lenis.value) {
    lenis.value.destroy();
    lenis.value = null;
  }
});
</script>

<template>
  <div class="news-page-container">

    <section ref="mainSection" class="news-parallax-section">
      <div class="news-sticky-title-wrapper news-pin-target">
        <h6 class="news-main-text">{{$t("nav.newsIntro")}}</h6>
        <div class="scroll-tip" >
          <div class="tip-dot"></div>
        </div>
      </div>

      <div class="news-cards-container">
        <div
          v-for="(card,index) in cards"
          :key="card.id"
          class="news-parallax-card"
          :style="[card.style]"
          :data-speed="card.speed"
        >
          <div class="news-card-inner">
            <img :src="card.src" alt="Magic Item" />
          </div>
        </div>
      </div>
    </section>

    <Backgroundaction></Backgroundaction>
    <section class="news-updates-section" id="updates">
      
      <div class="news-updates-header">
        <h2 class="news-updates-title">{{$t('nav.news')}}</h2>
      </div>

      <div class="news-updates-grid">
        <NewsCard v-for="item in allNewsData" :key="item.id" :data="item" :link="item.id"/>
      </div>
    </section>
    <ToTopBottom />
  </div>
</template>

<style lang="scss" scoped>
.news-page-container {
  width: 100%;
  position: relative;
  // background-color: #0a0a0a;
  color: #ffffff;
  overflow-x: hidden;
}

/* --- 視差區塊樣式 --- */
.news-parallax-section {
  position: relative;
  width: 100%;
  height: 950vh;
  overflow: hidden;
  // background-color: #0a0a0a;
}

.news-sticky-title-wrapper {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 10;
  pointer-events: none;
  .scroll-tip{
    width: 5px;
    height: 35%;
    position: absolute;
    bottom: 10%;
    overflow: hidden;
    .tip-dot{
      position: absolute;
      width: 100%;
      top: -10%;
      height: 100%;
      animation: run 2s infinite ease-in-out;
      background-image: linear-gradient(to top,white 0%,transparent 100%);
      border-radius: 20%;
    }
  }
}
@keyframes run{  
  0% {
  top: 0%;
  transform: scaleY(0);
} 
100% {
  top: 100%;
  transform: scaleY(1);
} }


.news-main-text {
  line-height: 1.1;
  text-align: center;
  color: #fff;
  mix-blend-mode: exclusion;
  position: relative;
  &::after,&::before{
    content: '';
    height: 1px;
    width: 10%;
    background-color: #fff;
    position: absolute;
    top: 50%;
  }
  &::after{
    left: 102%;
  }
  &::before{
    right: 102%;
  }
}
.news-main-text

/* 卡片容器 */
.news-cards-container {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 99;
}

.news-parallax-card {
  position: absolute;
  will-change: transform;
  pointer-events:none;
  z-index: 999;
}

.news-card-inner {
  width: 100%;
  height: 100%;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);
}

.news-card-inner img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: sepia(20%) contrast(110%);
}

/*Updates 區*/
.news-updates-section {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  // background-color: #0a0a0a;
  position: relative;
  z-index: 99;
  margin-bottom: 120px;
}

.news-updates-header {
  margin-bottom: 20px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.3);
  max-width: 1200px;
  
}

.news-updates-title {
  letter-spacing: 2px;
  text-transform: uppercase;
  color: #fff;
  line-height: 1;
  margin-bottom: 60px;
}



/* 網格系統 */
.news-updates-grid {
  width: 100%;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px,1fr));
  gap: 48px;
  max-width: 1176px;
  margin: 0 auto;
  padding: 20px;
  justify-content: center;
}

/* 響應式 */
@media (max-width: 768px) {
  .news-parallax-section {
    height: 450vh;
  }
  .news-parallax-card {
    width: 160px;
    height: 180px;
  }
}


</style>
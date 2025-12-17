<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";
import Lenis from "lenis";
import NewsCard from "@/components/News/NewsCard.vue";
// 1. 記得引入 onBeforeRouteLeave
import { onBeforeRouteLeave } from 'vue-router'

// 註冊 GSAP Plugin
gsap.registerPlugin(ScrollTrigger);

const mainSection = ref(null);
const ctx = ref(null); // 用來清理 GSAP 動畫
const lenis = ref(null); // Lenis 實例

// 1. 視差漂浮卡片資料 (Parallax Items)
const cards = ref([
  {
    id: 1,
    src: "https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=600",
    style: { top: "25%", left: "5%" },
    speed: -100,
  },
  {
    id: 2,
    src: "https://images.unsplash.com/photo-1519074069444-1ba4fff66d16?w=600",
    style: { top: "32%", right: "8%" },
    speed: 50,
  },
  {
    id: 3,
    src: "https://images.unsplash.com/photo-1601314167099-232775b3d6fd?w=600",
    style: { top: "42%", left: "15%" },
    speed: -50,
  },
  {
    id: 4,
    src: "https://images.unsplash.com/photo-1633478062482-790e3b5dd810?w=600",
    style: { top: "48%", right: "25%" },
    speed: 120,
  },
  {
    id: 5,
    src: "https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=600",
    style: { top: "55%", left: "8%" },
    speed: -80,
  },
  {
    id: 6,
    src: "https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600",
    style: { top: "62%", right: "5%" },
    speed: 80,
  },
  {
    id: 7,
    src: "https://images.unsplash.com/photo-1505664194779-8beaceb93744?w=600",
    style: { top: "68%", left: "35%" },
    speed: -150,
  },
  {
    id: 8,
    src: "https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600",
    style: { top: "75%", right: "15%" },
    speed: 40,
  },
  {
    id: 9,
    src: "https://images.unsplash.com/photo-1478760329108-5c3ed9d495a0?w=600",
    style: { top: "82%", left: "10%" },
    speed: 150,
  },
  {
    id: 10,
    src: "https://images.unsplash.com/photo-1633478062482-790e3b5dd810?w=600",
    style: { top: "85%", right: "30%" },
    speed: -60,
  },
]);

// 2. 最新消息資料
const allNewsData = ref([
 {
    id: 1,
    title: "ABia Zhenlan",
    date: '2025.2.5',
    image: "https://cyberisland.teldap.tw/S/JQjyuytdcMmyblySsltwmTSleH",
    content: `The Dajia Matsu Pilgrimage is one of Taiwan’s largest and most iconic religious events, often recognized as one of the “world’s three major religious festivals.” Taking place each lunar March, the nine-day, eight-night journey begins at Dajia Jenn Lann Temple and covers around 300 kilometers across multiple counties. It attracts over a million devotees and visitors every year.

    The departure ceremony is one of the festival’s most electrifying highlights. As the palanquin carrying Matsu is lifted from the temple gate amid drums, firecrackers, and cheering crowds, the atmosphere reaches an emotional peak. Devotees rush to crawl under the palanquin—a ritual believed to cleanse misfortune and bring divine protection. Along the route, traditional performance troupes, lion dances, musicians, and folk arts teams create a rich cultural landscape.

    The pilgrimage emphasizes blessings, peace, and companionship. Thousands walk alongside the deity day and night, journeying through cities, towns, and countryside. Residents provide free food, drinks, and resting areas known as “pilgrim hotels,” reflecting the generosity and communal spirit deeply rooted in Taiwanese culture.

    For international visitors, the Dajia Matsu Pilgrimage offers an extraordinary opportunity to witness Taiwan’s living traditions. The combination of faith, emotion, music, and community support makes it one of the most immersive cultural experiences in Asia.
    `
  },
  {
    id: 2,
    title: "Ervjia kwifoan",
    date: '2025.6.20',
    image: "https://images.pexels.com/photos/4095421/pexels-photo-4095421.jpeg",
    content: `The Dajia Matsu Pilgrimage is one of Taiwan’s largest and most iconic religious events, often recognized as one of the “world’s three major religious festivals.” Taking place each lunar March, the nine-day, eight-night journey begins at Dajia Jenn Lann Temple and covers around 300 kilometers across multiple counties. It attracts over a million devotees and visitors every year.

    The departure ceremony is one of the festival’s most electrifying highlights. As the palanquin carrying Matsu is lifted from the temple gate amid drums, firecrackers, and cheering crowds, the atmosphere reaches an emotional peak. Devotees rush to crawl under the palanquin—a ritual believed to cleanse misfortune and bring divine protection. Along the route, traditional performance troupes, lion dances, musicians, and folk arts teams create a rich cultural landscape.

    The pilgrimage emphasizes blessings, peace, and companionship. Thousands walk alongside the deity day and night, journeying through cities, towns, and countryside. Residents provide free food, drinks, and resting areas known as “pilgrim hotels,” reflecting the generosity and communal spirit deeply rooted in Taiwanese culture.

    For international visitors, the Dajia Matsu Pilgrimage offers an extraordinary opportunity to witness Taiwan’s living traditions. The combination of faith, emotion, music, and community support makes it one of the most immersive cultural experiences in Asia.
    `
  },
  {
    id: 3,
    title: "hgtha Zhenlan",
    date: '2025.7.18',
    image: "https://www.travel.taipei/image/90806/?r=1515479579958",
    content: `The Dajia Matsu Pilgrimage is one of Taiwan’s largest and most iconic religious events, often recognized as one of the “world’s three major religious festivals.” Taking place each lunar March, the nine-day, eight-night journey begins at Dajia Jenn Lann Temple and covers around 300 kilometers across multiple counties. It attracts over a million devotees and visitors every year.

    The departure ceremony is one of the festival’s most electrifying highlights. As the palanquin carrying Matsu is lifted from the temple gate amid drums, firecrackers, and cheering crowds, the atmosphere reaches an emotional peak. Devotees rush to crawl under the palanquin—a ritual believed to cleanse misfortune and bring divine protection. Along the route, traditional performance troupes, lion dances, musicians, and folk arts teams create a rich cultural landscape.

    The pilgrimage emphasizes blessings, peace, and companionship. Thousands walk alongside the deity day and night, journeying through cities, towns, and countryside. Residents provide free food, drinks, and resting areas known as “pilgrim hotels,” reflecting the generosity and communal spirit deeply rooted in Taiwanese culture.

    For international visitors, the Dajia Matsu Pilgrimage offers an extraordinary opportunity to witness Taiwan’s living traditions. The combination of faith, emotion, music, and community support makes it one of the most immersive cultural experiences in Asia.
    `
  },
  {
    id: 4,
    title: "Eajiae Zhenlan",
    date: '2025.2.5',
    image: "https://cyberisland.teldap.tw/S/JQjyuytdcMmyblySsltwmTSleH",
    content: `The Dajia Matsu Pilgrimage is one of Taiwan’s largest and most iconic religious events, often recognized as one of the “world’s three major religious festivals.” Taking place each lunar March, the nine-day, eight-night journey begins at Dajia Jenn Lann Temple and covers around 300 kilometers across multiple counties. It attracts over a million devotees and visitors every year.

    The departure ceremony is one of the festival’s most electrifying highlights. As the palanquin carrying Matsu is lifted from the temple gate amid drums, firecrackers, and cheering crowds, the atmosphere reaches an emotional peak. Devotees rush to crawl under the palanquin—a ritual believed to cleanse misfortune and bring divine protection. Along the route, traditional performance troupes, lion dances, musicians, and folk arts teams create a rich cultural landscape.

    The pilgrimage emphasizes blessings, peace, and companionship. Thousands walk alongside the deity day and night, journeying through cities, towns, and countryside. Residents provide free food, drinks, and resting areas known as “pilgrim hotels,” reflecting the generosity and communal spirit deeply rooted in Taiwanese culture.

    For international visitors, the Dajia Matsu Pilgrimage offers an extraordinary opportunity to witness Taiwan’s living traditions. The combination of faith, emotion, music, and community support makes it one of the most immersive cultural experiences in Asia.
    `
  },
  {
    id: 5,
    title: "ajiafe Zhenlan",
    date: '2025.6.20',
    image: "https://images.pexels.com/photos/4095421/pexels-photo-4095421.jpeg",
    content: `The Dajia Matsu Pilgrimage is one of Taiwan’s largest and most iconic religious events, often recognized as one of the “world’s three major religious festivals.” Taking place each lunar March, the nine-day, eight-night journey begins at Dajia Jenn Lann Temple and covers around 300 kilometers across multiple counties. It attracts over a million devotees and visitors every year.

    The departure ceremony is one of the festival’s most electrifying highlights. As the palanquin carrying Matsu is lifted from the temple gate amid drums, firecrackers, and cheering crowds, the atmosphere reaches an emotional peak. Devotees rush to crawl under the palanquin—a ritual believed to cleanse misfortune and bring divine protection. Along the route, traditional performance troupes, lion dances, musicians, and folk arts teams create a rich cultural landscape.

    The pilgrimage emphasizes blessings, peace, and companionship. Thousands walk alongside the deity day and night, journeying through cities, towns, and countryside. Residents provide free food, drinks, and resting areas known as “pilgrim hotels,” reflecting the generosity and communal spirit deeply rooted in Taiwanese culture.

    For international visitors, the Dajia Matsu Pilgrimage offers an extraordinary opportunity to witness Taiwan’s living traditions. The combination of faith, emotion, music, and community support makes it one of the most immersive cultural experiences in Asia.
    `
  },
  {
    id: 6,
    title: "HGajia Zhenlan",
    date: '2025.7.18',
    image: "https://www.travel.taipei/image/90806/?r=1515479579958",
    content: `The Dajia Matsu Pilgrimage is one of Taiwan’s largest and most iconic religious events, often recognized as one of the “world’s three major religious festivals.” Taking place each lunar March, the nine-day, eight-night journey begins at Dajia Jenn Lann Temple and covers around 300 kilometers across multiple counties. It attracts over a million devotees and visitors every year.

    The departure ceremony is one of the festival’s most electrifying highlights. As the palanquin carrying Matsu is lifted from the temple gate amid drums, firecrackers, and cheering crowds, the atmosphere reaches an emotional peak. Devotees rush to crawl under the palanquin—a ritual believed to cleanse misfortune and bring divine protection. Along the route, traditional performance troupes, lion dances, musicians, and folk arts teams create a rich cultural landscape.

    The pilgrimage emphasizes blessings, peace, and companionship. Thousands walk alongside the deity day and night, journeying through cities, towns, and countryside. Residents provide free food, drinks, and resting areas known as “pilgrim hotels,” reflecting the generosity and communal spirit deeply rooted in Taiwanese culture.

    For international visitors, the Dajia Matsu Pilgrimage offers an extraordinary opportunity to witness Taiwan’s living traditions. The combination of faith, emotion, music, and community support makes it one of the most immersive cultural experiences in Asia.
    `
  },
  {
    id: 7,
    title: "Dajia Zhnrfrfan",
    date: '2025.2.5',
    image: "https://cyberisland.teldap.tw/S/JQjyuytdcMmyblySsltwmTSleH",
    content: `The Dajia Matsu Pilgrimage is one of Taiwan’s largest and most iconic religious events, often recognized as one of the “world’s three major religious festivals.” Taking place each lunar March, the nine-day, eight-night journey begins at Dajia Jenn Lann Temple and covers around 300 kilometers across multiple counties. It attracts over a million devotees and visitors every year.

    The departure ceremony is one of the festival’s most electrifying highlights. As the palanquin carrying Matsu is lifted from the temple gate amid drums, firecrackers, and cheering crowds, the atmosphere reaches an emotional peak. Devotees rush to crawl under the palanquin—a ritual believed to cleanse misfortune and bring divine protection. Along the route, traditional performance troupes, lion dances, musicians, and folk arts teams create a rich cultural landscape.

    The pilgrimage emphasizes blessings, peace, and companionship. Thousands walk alongside the deity day and night, journeying through cities, towns, and countryside. Residents provide free food, drinks, and resting areas known as “pilgrim hotels,” reflecting the generosity and communal spirit deeply rooted in Taiwanese culture.

    For international visitors, the Dajia Matsu Pilgrimage offers an extraordinary opportunity to witness Taiwan’s living traditions. The combination of faith, emotion, music, and community support makes it one of the most immersive cultural experiences in Asia.
    `
  },
    {
    id: 8,
    title: "Djgajia kroipen",
    date: '2025.6.20',
    image: "https://images.pexels.com/photos/4095421/pexels-photo-4095421.jpeg",
    content: `The Dajia Matsu Pilgrimage is one of Taiwan’s largest and most iconic religious events, often recognized as one of the “world’s three major religious festivals.” Taking place each lunar March, the nine-day, eight-night journey begins at Dajia Jenn Lann Temple and covers around 300 kilometers across multiple counties. It attracts over a million devotees and visitors every year.

    The departure ceremony is one of the festival’s most electrifying highlights. As the palanquin carrying Matsu is lifted from the temple gate amid drums, firecrackers, and cheering crowds, the atmosphere reaches an emotional peak. Devotees rush to crawl under the palanquin—a ritual believed to cleanse misfortune and bring divine protection. Along the route, traditional performance troupes, lion dances, musicians, and folk arts teams create a rich cultural landscape.

    The pilgrimage emphasizes blessings, peace, and companionship. Thousands walk alongside the deity day and night, journeying through cities, towns, and countryside. Residents provide free food, drinks, and resting areas known as “pilgrim hotels,” reflecting the generosity and communal spirit deeply rooted in Taiwanese culture.

    For international visitors, the Dajia Matsu Pilgrimage offers an extraordinary opportunity to witness Taiwan’s living traditions. The combination of faith, emotion, music, and community support makes it one of the most immersive cultural experiences in Asia.
    `
  },
  {
    id: 9,
    title: "HITM Zehenlan",
    date: '2025.7.18',
    image: "https://www.travel.taipei/image/90806/?r=1515479579958",
    content: `The Dajia Matsu Pilgrimage is one of Taiwan’s largest and most iconic religious events, often recognized as one of the “world’s three major religious festivals.” Taking place each lunar March, the nine-day, eight-night journey begins at Dajia Jenn Lann Temple and covers around 300 kilometers across multiple counties. It attracts over a million devotees and visitors every year.

    The departure ceremony is one of the festival’s most electrifying highlights. As the palanquin carrying Matsu is lifted from the temple gate amid drums, firecrackers, and cheering crowds, the atmosphere reaches an emotional peak. Devotees rush to crawl under the palanquin—a ritual believed to cleanse misfortune and bring divine protection. Along the route, traditional performance troupes, lion dances, musicians, and folk arts teams create a rich cultural landscape.

    The pilgrimage emphasizes blessings, peace, and companionship. Thousands walk alongside the deity day and night, journeying through cities, towns, and countryside. Residents provide free food, drinks, and resting areas known as “pilgrim hotels,” reflecting the generosity and communal spirit deeply rooted in Taiwanese culture.

    For international visitors, the Dajia Matsu Pilgrimage offers an extraordinary opportunity to witness Taiwan’s living traditions. The combination of faith, emotion, music, and community support makes it one of the most immersive cultural experiences in Asia.
    `
  },
    {
    id: 10,
    title: "Taiwan’s AI-driven Taiwan’s AI-driven",
    date: '2025.2.5',
    image: "https://cyberisland.teldap.tw/S/JQjyuytdcMmyblySsltwmTSleH",
    content: `The Dajia Matsu Pilgrimage is one of Taiwan’s largest and most iconic religious events, often recognized as one of the “world’s three major religious festivals.” Taking place each lunar March, the nine-day, eight-night journey begins at Dajia Jenn Lann Temple and covers around 300 kilometers across multiple counties. It attracts over a million devotees and visitors every year.

    The departure ceremony is one of the festival’s most electrifying highlights. As the palanquin carrying Matsu is lifted from the temple gate amid drums, firecrackers, and cheering crowds, the atmosphere reaches an emotional peak. Devotees rush to crawl under the palanquin—a ritual believed to cleanse misfortune and bring divine protection. Along the route, traditional performance troupes, lion dances, musicians, and folk arts teams create a rich cultural landscape.

    The pilgrimage emphasizes blessings, peace, and companionship. Thousands walk alongside the deity day and night, journeying through cities, towns, and countryside. Residents provide free food, drinks, and resting areas known as “pilgrim hotels,” reflecting the generosity and communal spirit deeply rooted in Taiwanese culture.

    For international visitors, the Dajia Matsu Pilgrimage offers an extraordinary opportunity to witness Taiwan’s living traditions. The combination of faith, emotion, music, and community support makes it one of the most immersive cultural experiences in Asia.
    `
  },
]);

onMounted(() => {
  lenis.value = new Lenis({
    duration: 1.5,
    smooth: true,
  });

  const navEntry = performance.getEntriesByType("navigation")[0];
  
  if (navEntry && navEntry.type === 'reload') {
    // 如果是按 F5 重新整理，強制滾回頂部
    window.scrollTo(0, 0);
    lenis.value.scrollTo(0, { immediate: true });
  } 
  // 如果是 'navigate' (點連結) -> Router 會處理歸零
  // 如果是 'back_forward' (按上一頁) -> Router 會處理回到舊位置
  // 所以其他情況我們都不用管，只要抓 'reload' 就好！

  function raf(time) {
    if (!lenis.value) return;
    lenis.value.raf(time);
    requestAnimationFrame(raf);
  }
  requestAnimationFrame(raf);

  ctx.value = gsap.context(() => {
    ScrollTrigger.create({
      trigger: mainSection.value,
      start: "top top",
      end: "bottom bottom",
      pin: ".news-pin-target",
      pinSpacing: false,
    });

    const parallaxCards = document.querySelectorAll(".news-parallax-card");
    parallaxCards.forEach((el) => {
      const speed = parseFloat(el.getAttribute("data-speed"));;
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
  // behavior: 'auto' 代表瞬間跳轉，不要滑動 (重新整理通常不需要滑動特效)
  window.scrollTo({ top: 0, behavior: 'auto' });
});


// 2. ★★★ 加入這段安全煞車 ★★★
// 這會在「按下連結，但還沒換頁」的那一瞬間執行
onBeforeRouteLeave((to, from, next) => {
  // 立即殺死 Lenis，防止它在換頁過程中干擾卷軸位置
  if (lenis.value) {
    lenis.value.destroy(); 
    lenis.value = null;
  }
  next();
});

onUnmounted(() => {
  if (ctx.value) ctx.value.revert();
  if (lenis.value) lenis.value.destroy();
});
</script>

<template>
  <div class="news-page-container">
    <section ref="mainSection" class="news-parallax-section">
      <div class="news-sticky-title-wrapper news-pin-target">
        <h5 class="news-main-text">I solemnly swear that I am up to no good.</h5>
      </div>

      <div class="news-cards-container">
        <div
          v-for="card in cards"
          :key="card.id"
          class="news-parallax-card"
          :style="card.style"
          :data-speed="card.speed"
        >
          <div class="news-card-inner">
            <img :src="card.src" alt="Magic Item" />
          </div>
        </div>
      </div>
    </section>

    <section class="news-updates-section">
      <div class="news-updates-header">
        <h2 class="news-updates-title">UPDATES</h2>
      </div>

      <div class="news-updates-grid">
        <NewsCard v-for="item in allNewsData" :key="item.id" :data="item" :link="`/news/${item.id}`"/>
      </div>
    </section>
  </div>
</template>

<style lang="scss" scoped>
.news-page-container {
  width: 100%;
  position: relative;
  background-color: #0a0a0a;
  color: #ffffff;
  overflow-x: hidden;
}

/* --- 視差區塊樣式 --- */
.news-parallax-section {
  position: relative;
  width: 100%;
  height: 950vh;
  overflow: hidden;
  background-color: #0a0a0a;
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
}

.news-main-text {
  line-height: 1.1;
  text-align: center;
  color: #fff;
  mix-blend-mode: exclusion;
}

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
  width: 270px;
  height: 290px;
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
  background-color: #0a0a0a;
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
  grid-template-columns: repeat(3, 1fr);
  gap: 48px;
  max-width: 1176px;
  margin: 0 auto;
}

/* 響應式 */
@media (max-width: 768px) {
  .news-updates-grid {
    grid-template-columns: 1fr;
  }
  .news-parallax-section {
    height: 450vh;
  }
  .news-parallax-card {
    width: 160px;
    height: 180px;
  }
}


</style>
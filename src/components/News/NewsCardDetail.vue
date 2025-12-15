<script setup>
import { ref,computed, onMounted, onUnmounted, watch, nextTick } from 'vue'
import { useRoute } from 'vue-router'
import Lenis from "lenis";

const route = useRoute()
const lenis = ref(null)


const allNewsData = [
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
]

// 3. 找出目前要顯示的那一篇
const currentArticle = computed(() => {
  return allNewsData.find(item => item.id == route.params.id)
})

// 修改後的 scrollToTop
const scrollToTop = (isSmooth = false) => {
  if (isSmooth && lenis.value) {
    // ★ 模式 A：滑順滾動 (點擊選單時用)
    // duration: 2 代表花 2 秒鐘滑上去 (你可以自己改數字，越小越快)
    // lock: true 代表滾動過程中鎖住滑鼠，避免使用者亂動打斷動畫
    lenis.value.scrollTo(0, { duration: 1, lock: false }); 
  } else {
    // ★ 模式 B：瞬間移動 (剛進來或重整時用)
    window.scrollTo(0, 0); // 原生歸零
    if (lenis.value) {
      lenis.value.scrollTo(0, { immediate: true }); // Lenis 歸零
    }
  }
}

// 當 route.params.id 改變時 (從新聞A -> 新聞B)，強制執行歸零
watch(
  () => route.params.id,
  () => {
    nextTick(() => {
      // 傳入 true，開啟滑順模式
      scrollToTop(true);
    });
  }
);

onMounted(() => {
  // 1. 初始化 Lenis
  lenis.value = new Lenis({
    duration: 1.5,
    smooth: true,
  });

  // 2. 啟動 RAF
  function raf(time) {
    lenis.value?.raf(time);
    requestAnimationFrame(raf);
  }
  requestAnimationFrame(raf);
  scrollToTop(false);
});

onUnmounted(() => {
  if(lenis.value) lenis.value.destroy();
})
</script>

<template>
  <div class="detail-page">
    <div class="bread-crumb">
      <router-link to="/news">News</router-link>
      <span> > </span>
      <span>{{ currentArticle?.title }}</span>
    </div>
    <div class="content-container">
      <aside>
        <ul>
          <li v-for="item in allNewsData" :key="item.id">
            <router-link :to="`/news/${item.id}`">
              <div>
                <h5>{{ item.title }}</h5>
                <p>{{ item.date }}</p>
              </div>
              <span><font-awesome-icon :icon="['fas', 'caret-down']" size="3x" class="arrow-icon"/></span>
            </router-link>
          </li>
        </ul>
      </aside>

      <main>
        <Transition name="fade" mode="out-in">
          
          <div v-if="currentArticle" :key="currentArticle.id">
            <img :src="currentArticle.image" alt="#">
            <div class="text-area">
              <h3>{{ currentArticle.title }}</h3>
              <h5>{{ currentArticle.date }}</h5>
              <p>{{ currentArticle.content }}</p>
            </div>
          </div>

        </Transition>
      </main>
    </div>
  </div>
</template>

<style lang="scss" scoped>
  .detail-page{
    color: white;
    max-width: 1200px;
    width: 100%;
    margin: 100px auto 0;
    min-height: 100vh;
  }
  .bread-crumb{
    display: flex;
    gap: 20px;
    margin: 0 0 100px 80px;
  }
  .bread-crumb a{
    display: block;
    text-decoration: none;
    color: white;
    cursor: pointer;
    position: relative;
    z-index: 9999;
  }
  .content-container{
    display: flex;
    margin-left: 120px;
  }
  .content-container aside{
    width: 315px;
    flex-shrink: 0;
    margin-right: 64px;
    z-index: 9999;
  }
  .content-container ul{
    background-color: #fff;
    border-radius: 5px
  }
  .content-container ul a{
    display: flex;
    border: 1px solid red;
    text-decoration: none;
    color: #000;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
  }

/*【關鍵步驟】處理文字包覆層 (a 裡面的那個 div) */
/* 必須設定 min-width: 0，Flex 子元素才會縮小，否則會無限撐開 */
.content-container ul a > div {
  flex: 1;        /* 讓這個 div 佔據所有剩餘寬度 */
  min-width: 0;   /* ★超級關鍵：沒有這行，省略號永遠出不來 */
  margin-right: 15px; /* 避免文字跟右邊的箭頭 icon 撞在一起 */
}

.content-container ul a h5 {
  margin: 0;
  /* --- 省略號 --- */
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

  .content-container img{
    width: 100%;
    max-width: 820px;
    height: auto; 
    display: block; 
    margin-bottom: 60px; 
  }
  .article-container{
    width: 100%;
    max-width: 800px;
    height: auto; 
    display: block; 
    margin-bottom: 20px; 
  }
  .text-area{
    background-color: #fff;
    color: #000;
    white-space: pre-wrap;
    padding: 40px;
  }
  .text-area h3{
    margin-bottom: 20px;
  }
   .text-area h5{
    margin-bottom: 20px;
  }
  .icon-item{
    color: #000;
    z-index: 99999;
  }
  .arrow-icon{
    transition: all 1s ease;
  }
  .router-link-active .arrow-icon {
    transform: rotate( -90deg);
  }

  /* 進場與離場的過程：設定時間與曲線 */
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.4s ease, transform 0.4s ease;
  }

  /* 進場前 (還透明) & 離場後 (變透明) */
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
    transform: translateY(10px);
  }
  
</style>
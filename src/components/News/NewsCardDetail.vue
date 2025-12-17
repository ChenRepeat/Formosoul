<script setup>
import { computed, nextTick, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()


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

const currentArticle = computed(() => {
  return allNewsData.find(item => item.id == route.params.id)
})


// ★★★ 新增：自訂時間的平滑滾動函式 ★★★
// duration = 毫秒 (例如 1000 = 1秒)
const scrollToTop = (duration = 800) => {
  const start = window.scrollY; // 起點
  const startTime = performance.now();

  const animate = (currentTime) => {
    const elapsed = currentTime - startTime;
    const progress = Math.min(elapsed / duration, 1);
    
    // Easing 函數 (讓滾動有「快 -> 慢」的煞車感，比較自然)
    // 這裡用的是 easeOutCubic
    const ease = 1 - Math.pow(1 - progress, 3);
    
    window.scrollTo(0, start * (1 - ease));

    if (progress < 1) {
      requestAnimationFrame(animate);
    }
  };

  requestAnimationFrame(animate);
};

const goBack = () => {
  if (window.history.state.back) {
    router.back(); // 有上一頁 -> 回上一頁 (保留捲動位置)
  } else {
    router.push('/news'); // 沒上一頁 -> 回列表
  }
}

watch(
  () => route.params.id,
  () => {
    nextTick(() => {
      scrollToTop(1000);
    });
  }
);
</script>

<template>
  <div class="detail-page">
        <!-- 麵包屑 -->
    <h6 class="page-guide">
        <router-link to="/news">News</router-link>
        <font-awesome-icon icon="fa-solid fa-angle-right" />
        {{ currentArticle?.title }}
    </h6>
    <div class="content-container">
      <aside>
        <ul>
          <li v-for="item in allNewsData" :key="item.id">
            <router-link :to="`/news/${item.id}`" replace>
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
    <div class="btn-back-layout">
          <a href="#" class="back-to-news" @click.prevent="goBack">
          <font-awesome-icon :icon="['fas', 'arrow-left']" class="back-icon" />
          <p>Back to previous page</p> 
      </a>
    </div>
  </div>
</template>

<style lang="scss" scoped>
  .detail-page{
    color: white;
    max-width: 1200px;
    width: 100%;
    margin: 100px auto ;
    min-height: 100vh;
    position: relative;
  }
  // .bread-crumb{
  //   display: flex;
  //   gap: 20px;
  //   margin: 0 0 100px 80px;
  // }
  // .bread-crumb a{
  //   display: block;
  //   text-decoration: none;
  //   color: white;
  //   cursor: pointer;
  //   position: relative;
  //   z-index: 99;
  // }
  // .bread-crumb a:hover{
  //   color: blue;
  // }
  .content-container{
    display: flex;
    margin-left: 120px;
  }
  .content-container aside{
    width: 315px;
    flex-shrink: 0;
    margin-right: 64px;
    z-index: 99;
  }
  .content-container ul{
    background-color: #fff;
    border-radius: 5px
  }
  .content-container ul a{
    display: flex;
    border-bottom: 1px solid #000;
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
    border-radius: 7px;
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
    border-radius: 7px;
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
  .back-to-news{
    display: inline-flex;
    padding: 8px 40px;
    justify-content: center;
    align-items: center;
    border-radius: 7px;
    background: var(--Blue900, #0A3D70);
    color: #fff;
    text-decoration: none;
    position: relative;
    margin-top: 140px;
    margin-right: -60px;
    transition: all 0.3s ease;
  }

  /* ★ Hover 效果 ★ */
.back-to-news:hover {
  background-color: #164e8a; /* 1. 背景變亮一點的藍色，增加互動感 */
  transform: translateY(-3px); /* 2. 按鈕整體稍微往上浮起 */
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3); /* 3. 加一點陰影，更有立體感 */
}

/* 當滑鼠指到按鈕時，讓裡面的 icon 往左動 */
.back-to-news:hover .back-icon {
  transform: translateX(-5px); /* 4. 關鍵！箭頭往左跑，暗示「回去」 */
}

.back-to-news p{
  letter-spacing: 0.8px;
}
.back-icon{
  width: 24px;
  height: 24px;
  transition: all 0.3s ease;
}
.btn-back-layout{
  display: flex;
  justify-content: flex-end;
}
.page-guide{
padding-bottom: 60px;
}
.page-guide a{
  text-decoration: none;
  color: #fff;
}
.page-guide a:hover{
  color: $color-fsBlue;
}

</style>
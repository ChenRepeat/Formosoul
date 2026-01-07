<script setup>
import { computed, nextTick, watch, onMounted, onUnmounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Backgroundaction from "@/components/backgroundaction.vue";
import { useNewsData } from "@/stores/news";
import { useLangStore } from '@/stores/lang';

const route = useRoute()
const router = useRouter()
const langStore = useLangStore();



const baseUrl = import.meta.env.BASE_URL;
const newsDataStore = useNewsData(); // 吃pinia

const allNewsData = computed(() => newsDataStore.allNewsData);

const currentArticle = computed(() => {
  if (!allNewsData.value || allNewsData.value.length === 0) {
    return null;
  }
  const found = allNewsData.value.find((item) => {
    return item.id == route.params.id;
  });
  const target = found || items.value[0];
  if (!target) return null;
  return {
    ...target,
    displayTitle: target[`title_${langStore.dbSuffix}`],
    displayContext: target[`content_${langStore.dbSuffix}`],
  };
});
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
const isEnglish = computed(() => {
  return langStore.locale === 'en-US'; 
});
watch(
  () => route.params.id,
  () => {
    nextTick(() => {
      scrollToTop(1000);
    });
  }
);

onMounted( async () => {
  newsDataStore.get_newsinfo();
  await nextTick();
})
</script>

<template>
  <div class="detail-page">
    <Backgroundaction class="bg-wrapper"></Backgroundaction>
        <!-- 麵包屑 -->
    <h6 class="page-guide">
        <router-link to="/news">{{$t('nav.news')}}</router-link>
        <font-awesome-icon icon="fa-solid fa-angle-right" />
        <h6>{{ currentArticle?.displayTitle }}</h6>
    </h6>
    <div class="content-container">
      <aside>
        <ul>
          <li v-for="(item,key) in allNewsData" :key="key">
            <router-link :to="`/news/${item.id}`" replace>
              <div>
                <h5 v-if="isEnglish">{{ item.title_en }}</h5>
                <h5 v-else>{{ item.title_zh }}</h5>
                <p>{{ item.update }}</p>
              </div>
              <span><font-awesome-icon :icon="['fas', 'caret-down']" size="3x" class="arrow-icon"/></span>
            </router-link>
          </li>
        </ul>
      </aside>
      <main>
        <Transition name="fade" mode="out-in">
          
          <div v-if="currentArticle" :key="currentArticle.id">
            <img :src="`${ baseUrl }${currentArticle.pic}`" alt="#">
            <div class="text-area">
              <h3 >{{ currentArticle?.displayTitle }}</h3>
              <h5>{{ currentArticle.update }}</h5>
              <p>{{ currentArticle.displayContext }}</p>

              <div class="btn-back-layout">
                    <a href="#" class="back-to-news" @click.prevent="goBack">
                    <font-awesome-icon :icon="['fas', 'arrow-left']" class="back-icon" />
                    <p> {{$t('nav.backPrepage')}}</p> 
                </a>
              </div>
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
    min-height: 100vh;
    position: relative;
    margin: 180px auto 0; /* 確保父容器本身在頁面置中 */
  }

  .bg-wrapper {
  position: fixed; // 或是 absolute
  top:0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 0;     // 確保在底層
  pointer-events: none; // 讓滑鼠事件穿透到下層按鈕
}
.page-guide{
    padding-bottom: 100px;
      margin-left: -40px;
    h6{
      display: inline-block;
    }
}
.content-container, .page-guide {
  position: relative;
  z-index: 10; // 確保內容在背景之上
}
  .content-container{
    display: flex;
    width: 100%;           /* 寬度設為 100% */
  max-width: 1200px;     /* 限制最大寬度 */
  margin: 0 auto;

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
    opacity: 0.5; 
    transition: opacity 0.3s ease;
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
  .content-container ul a:hover {
    opacity: 1;
  }

  .content-container ul li a.router-link-active {
    opacity: 1;
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
    margin: 140px 0 60px 0;
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
    margin-right: 8px;
  }
  .btn-back-layout{
    display: flex;
    justify-content: flex-end;
    gap: 8px;
  }

.page-guide a{
  text-decoration: none;
  color: #fff;
}
.page-guide a:hover{
  color: $color-fsBlue;
}

</style>
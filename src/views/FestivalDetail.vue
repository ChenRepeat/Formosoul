
<script setup>
  import { computed, onMounted } from "vue";
  import { useRoute, useRouter } from "vue-router";
  import { useEventData } from "@/stores/event";
  import { storeToRefs } from "pinia";
  import { useLangStore } from '@/stores/lang';

  const baseUrl = import.meta.env.BASE_URL;
  const langStore = useLangStore();
  const eventData = useEventData();
  const { eventDatas } = storeToRefs(eventData);

  const items = computed(() => eventDatas.value || []);

  const route = useRoute();
  const router = useRouter();

  const currentSlug = computed(() => route.params.slug);

  const currentFestival = computed(() => {
    if (items.value.length === 0) return null;
    const found = items.value.find((item) => {
      return item.title_en_s?.join("-") === currentSlug.value;
    });
    return found || items.value[0];
  });
  function goBack() {
    if (window.history.length > 1) {
      router.back();
    } else {
      router.push({ name: "AnnualEvent" });
    }
  }
  const formattedIntro = computed(() => {
  const text = currentFestival.value?.introL_en;
  if (!text) return "";
  return text.replace(/\n/g, '<br />');
});
const isEnglish = computed(() => {
  return langStore.locale === 'en-US'; 
});
  function scrollToTop() {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
  onMounted(() => {
    eventData.loadeventData();
  });
</script>

<template>
  <section class="festival-detail-page">
    <div class="detail-container">
      <!-- Hero 圖片 -->
      <div class="hero-media">
        <img
          :src="`${baseUrl}${currentFestival.pic}`"
          :alt="currentFestival.title"
        />
      </div>

      <!-- 文字內容 -->
      <article class="detail-content">
        <p class="breadcrumb" v-if="isEnglish">Annual Event · {{ currentFestival.title_en }}</p>
        <p class="breadcrumb" v-else>Annual Event · {{ currentFestival.title_zh }}</p>

        <h1 class="detail-title" v-if="isEnglish">{{ currentFestival.title_en }}</h1>
        <h1 class="detail-title" v-else>{{ currentFestival.title_zh }}</h1>

        <p class="detail-date">
          {{ currentFestival.launchDate }}
        </p>

        <div class="detail-body">
          <p v-if="isEnglish">{{ currentFestival.introL_en }}</p>
          <p v-else>{{ currentFestival.introL_zh }}</p>
        </div>

        <!-- 底部按鈕 -->
        <div class="detail-actions">
          <button class="btn-back" @click="goBack">
            ← {{ $t('nav.backPrepage') }}
          </button>
        </div>
      </article>
    </div>

    <!-- 右下角回到最上面的小按鈕 -->
    <button class="scroll-top-btn" @click="scrollToTop">
      ↑
    </button>
  </section>
</template>

<style scoped lang="scss">
@import "/src/assets/_variables.scss";

.festival-detail-page {
  padding: 180px 0 80px;
  background-color: $color-fsTitle;
  position: relative;
  background-color: #000;
}

.detail-container {
  max-width: 1200px;
  margin: 0 auto;
  background: #0b1724;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
}

/* 上半部 hero 圖片 */
.hero-media {
  width: 100%;
  max-height: 520px;
  overflow: hidden;
}
.hero-media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* 下半部白底內容 */
.detail-content {
  background: #f6f6f6;
  padding: 32px 40px 40px;
  color: #222;
}

.breadcrumb {
  font-size: 13px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: #777;
  margin-bottom: 12px;
}

.detail-title {
  font-size: 28px;
  line-height: 1.4;
  margin-bottom: 8px;
}

.detail-date {
  font-size: 14px;
  letter-spacing: 0.18em;
  color: #555;
  margin-bottom: 24px;
}

.detail-body {
  display: flex;
  flex-direction: column;
  gap: 16px;
  white-space: pre-wrap;
}

/* 底部「返回上一頁」按鈕 */
.detail-actions {
  margin-top: 32px;
  display: flex;
  justify-content: flex-end;
}

.btn-back {
  padding: 10px 20px;
  border-radius: 999px;
  border: none;
  background: #1a3a6b;
  color: #fff;
  font-size: 14px;
  cursor: pointer;
}

/* 右下角「回到頂部」小按鈕 */
.scroll-top-btn {
  position: fixed;
  right: 24px;
  bottom: 24px;
  width: 36px;
  height: 36px;
  border-radius: 999px;
  border: none;
  background: #1a3a6b;
  color: #fff;
  font-size: 18px;
  cursor: pointer;
  z-index: 5;
}

/* RWD */
@media (max-width: 767px) {
  .festival-detail-page {
    padding: 40px 0 60px;
  }

  .detail-container {
    border-radius: 0;
  }

  .detail-content {
    padding: 24px 18px 28px;
  }

  .detail-title {
    font-size: 22px;
  }
}
</style>

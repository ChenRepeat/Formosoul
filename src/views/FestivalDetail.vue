<script setup>
import { computed } from "vue";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();

// 和 AnnualEvent.vue 一樣的資料結構（之後可以抽到共用檔）
const items = [
  {
    id: 1,
    slug: "yanshui-beehive-fireworks-festival",
    title: "Yanshui Beehive Fireworks Festival",
    date: "2026.03.03",
    image: "/tjd103/festivals/Taiwan Lantern Festival.png",
  },
  {
    id: 2,
    slug: "dajia-matsu-pilgrimage-departure",
    title: "Dajia Matsu Pilgrimage Departure",
    date: "2026.04.10",
    image: "/tjd103/festivals/DajiaMatsuPilgrimageDeparture.png",
  },
  {
    id: 3,
    slug: "taiwan-lantern-festival",
    title: "Taiwan Lantern Festival",
    date: "2026.02",
    image: "/tjd103/festivals/Toucheng Pole-Climbing Ghost Festival.png",
  },
  {
    id: 4,
    slug: "dragon-boat-festival-races",
    title: "Dragon Boat Festival Races",
    date: "2026.06.19",
    image: "/tjd103/festivals/DragonBoatFestivalRaces.png",
  },
];

const currentSlug = computed(() => route.params.slug);

const currentFestival = computed(() => {
  return items.find((item) => item.slug === currentSlug.value) ?? items[0];
});

function goBack() {
  // 回到上一頁；如果沒有上一頁，就回 AnnualEvent
  if (window.history.length > 1) {
    router.back();
  } else {
    router.push({ name: "AnnualEvent" });
  }
}

function scrollToTop() {
  window.scrollTo({ top: 0, behavior: "smooth" });
}
</script>

<template>
  <section class="festival-detail-page">
    <div class="detail-container">
      <!-- Hero 圖片 -->
      <div class="hero-media">
        <img
          :src="currentFestival.image"
          :alt="currentFestival.title"
        />
      </div>

      <!-- 文字內容 -->
      <article class="detail-content">
        <p class="breadcrumb">
          Annual Event · {{ currentFestival.title }}
        </p>

        <h1 class="detail-title">
          {{ currentFestival.title }}
        </h1>

        <p class="detail-date">
          {{ currentFestival.date }}
        </p>

        <div class="detail-body">
          <!-- 這裡文字你可以之後改成真正介紹，我先放 placeholder 給你位置 -->
          <p>
            The {{ currentFestival.title }} is one of Taiwan&apos;s most
            iconic festivals, blending local folk beliefs, history, and
            community spirit. On this night, the entire town transforms into
            an open-air stage filled with light, sound, and ritual energy.
          </p>
          <p>
            Visitors can experience traditional performances, follow the
            procession route, and taste regional street food: from grilled
            snacks and handmade desserts to temple-front vendors selling
            incense and offerings. It&apos;s not only a religious event, but
            also a living museum of Taiwanese culture.
          </p>
          <p>
            For international travelers, this festival offers a rare chance to
            observe how modern city life coexists with centuries-old ritual
            practices. Remember to prepare ear protection and follow local
            safety instructions if you plan to join the most intense
            activities up close.
          </p>
        </div>

        <!-- 底部按鈕 -->
        <div class="detail-actions">
          <button class="btn-back" @click="goBack">
            ← Back to previous page
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
  padding: 60px 0 80px;
  background-color: $color-fsTitle;
  position: relative;
}

.detail-container {
  max-width: 1200px;
  margin: 0 auto;
  background: #0b1724;
  border-radius: 16px;
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
  font-size: 15px;
  line-height: 1.8;
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

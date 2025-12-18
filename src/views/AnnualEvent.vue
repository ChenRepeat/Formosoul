<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

const items = ref([
  {
    id: 1,
    slug: "yanshui-beehive-fireworks-festival",
    titleLines: ["Yanshui", "Beehive", "Fireworks", "Festival"],
    date: "2026.03.03",
    image: "festivals/TaiwanLanternFestival.png",
    youtube: "https://www.youtube.com/watch?v=3sV3BTumzDg",
  },
  {
    id: 2,
    slug: "dajia-matsu-pilgrimage-departure",
    titleLines: ["Dajia", "Matsu", "Pilgrimage", "Departure"],
    date: "2026.04.10",
    image: "festivals/DajiaMatsuPilgrimageDeparture.png",
    youtube: "https://www.youtube.com/watch?v=nAAapURnWjw",
  },
  {
    id: 3,
    slug: "taiwan-lantern-festival",
    titleLines: ["Taiwan", "Lantern", "Festival"],
    date: "2026.02",
    image: "festivals/TouchengPole-ClimbingGhostFestival.png",
    youtube: "https://www.youtube.com/watch?v=zbiP-ndTUW0",
  },
  {
    id: 4,
    slug: "dragon-boat-festival-races",
    titleLines: ["Dragon", "Boat", "Festival", "Races"],
    date: "2026.06.19",
    image: "festivals/DragonBoatFestivalRaces.png",
    youtube: "https://www.youtube.com/watch?v=ucIr3GvImE4",
  },
    {
    id: 5,
    slug: "Yanshui Beehive Fireworks Festival",
    titleLines: ["Yanshui", "Beehive", "Fireworks", "Festival"],
    date: "2026.06.19",
    image: "festivals/YanshuiBeehiveFireworksFestival.png",
    youtube: "https://www.youtube.com/watch?v=LXCtJDd-qpw",
  },
]);

const VISIBLE_COUNT = 4;
const currentIndex = ref(0);
const hoveredIndex = ref(null);
const intervalMs = 4000;
const timerId = ref(null);
const isMobile = ref(false);
const DOT_COUNT = 4;

//  16 筆資料 → 4 組 → 4 顆點
const totalPages = computed(() => Math.ceil(items.value.length / VISIBLE_COUNT)); // 16/4=4
const currentPage = computed(() =>
  Math.floor(currentIndex.value / VISIBLE_COUNT) % totalPages.value
);

//  點點點擊：跳到那一組的第一張（0/4/8/12）
function goToPage(page) {
  const maxStart = Math.max(0, items.value.length - VISIBLE_COUNT); // 16-4=12
  const target = page * VISIBLE_COUNT;
  currentIndex.value = Math.min(target, maxStart);

  // 點點點完建議重啟計時，避免剛好 0.1 秒後又自動跳
  startAutoSlide();
}


const visibleItems = computed(() => {
  const total = items.value.length;
  const result = [];
  for (let offset = 0; offset < VISIBLE_COUNT; offset++) {
    const idx = (currentIndex.value + offset) % total;
    result.push(items.value[idx]);
  }
  return result;
});

function nextSlide() {
  const maxStart = Math.max(0, items.value.length - VISIBLE_COUNT); // 12
  if (currentIndex.value >= maxStart) currentIndex.value = 0;
  else currentIndex.value += 1;
}

function prevSlide() {
  const maxStart = Math.max(0, items.value.length - VISIBLE_COUNT);
  if (currentIndex.value <= 0) currentIndex.value = maxStart;
  else currentIndex.value -= 1;
}

function goTo(index) {
  currentIndex.value = index;
}

function handleMouseEnter(visibleIdx) {
  if (isMobile.value) return;
  hoveredIndex.value = visibleIdx;
}

function handleMouseLeave() {
  hoveredIndex.value = null;
}

// 有 youtube 且 hover 到才播放
function isPlaying(visibleIdx, item) {
  return hoveredIndex.value === visibleIdx && !!item.youtube;
}

// 點擊 → 詳細頁
function openDetail(item) {
  router.push({
    name: "FestivalDetail",
    params: { slug: item.slug },
  });
}


function getYouTubeId(url) {
  if (!url) return "";
  // embed/xxxx
  const embedMatch = url.match(/\/embed\/([a-zA-Z0-9_-]{6,})/);
  if (embedMatch) return embedMatch[1];
  // watch?v=xxxx
  const watchMatch = url.match(/[?&]v=([a-zA-Z0-9_-]{6,})/);
  if (watchMatch) return watchMatch[1];
  // youtu.be/xxxx
  const shortMatch = url.match(/youtu\.be\/([a-zA-Z0-9_-]{6,})/);
  if (shortMatch) return shortMatch[1];
  return "";
}

function getYouTubeSrc(item) {
  const id = getYouTubeId(item.youtube);
  if (!id) return "";
  return `https://www.youtube.com/embed/${id}?autoplay=1&mute=1&controls=0&loop=1&playlist=${id}&playsinline=1&rel=0`;
}



function updateIsMobile() {
  if (typeof window === "undefined") return;
  isMobile.value = window.innerWidth < 768;
}

function startAutoSlide() {
  if (isMobile.value) return;
  stopAutoSlide();
  timerId.value = setInterval(() => {
    if (hoveredIndex.value === null) nextSlide();
  }, intervalMs);
}

function stopAutoSlide() {
  if (timerId.value) {
    clearInterval(timerId.value);
    timerId.value = null;
  }
}


onMounted(() => {
  updateIsMobile();
  window.addEventListener("resize", updateIsMobile);
  startAutoSlide();
});

onBeforeUnmount(() => {
  stopAutoSlide();
  window.removeEventListener("resize", updateIsMobile);
});
</script>

<template>
  <section class="annual-event-page">
    <div class="festival-shell">
      <button class="nav-btn nav-prev" @click="prevSlide">‹</button>
      <button class="nav-btn nav-next" @click="nextSlide">›</button>

      <div class="festival-carousel">
        <div class="carousel-inner">
          <div class="carousel-track">
            <div
              v-for="(item, visibleIndex) in visibleItems"
              :key="item.id"
              class="slide"
              :class="{ 'is-hovered': hoveredIndex === visibleIndex }"
              @mouseenter="handleMouseEnter(visibleIndex)"
              @mouseleave="handleMouseLeave"
              @click="openDetail(item)"
            >
              <div class="slide-tilt">
                <div class="media-wrapper">
                  <!-- 底層：YouTube 影片（只在 hover 時顯示 iframe） -->
                  <div
                    v-if="item.youtube"
                    class="slide-video-wrapper"
                    :class="{ 'is-visible': isPlaying(visibleIndex, item) }"
                  >
                    <iframe
                      :src="getYouTubeSrc(item)"
                      title="Festival video"
                      frameborder="0"
                      allow="autoplay; encrypted-media; picture-in-picture"
                      allowfullscreen
                    ></iframe>
                  </div>

                  <!-- 中層：圖片（hover 時淡出） -->
                  <img
                    class="slide-image"
                    :class="{ 'is-hidden': isPlaying(visibleIndex, item) }"
                    :src="item.image"
                    :alt="item.titleLines.join(' ')"
                  />

                  <!-- 最上層：遮罩＋文字（未 hover 時顯示，hover 時淡出） -->
                  <div
                    class="slide-overlay"
                    :class="{ 'overlay-hidden': isPlaying(visibleIndex, item) }"
                  >
                    <div class="slide-text">
                      <p
                        class="slide-title-line"
                        v-for="(line, i) in item.titleLines"
                        :key="i"
                      >
                        {{ line }}
                      </p>
                      <p class="slide-date">{{ item.date }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="carousel-dots">
        <button
          v-for="p in totalPages"
          :key="p"
          class="dot"
          :class="{ active: (p - 1) === currentPage }"
          @click="goToPage(p - 1)"
        ></button>
      </div>


    </div>
  </section>
</template>

<style scoped lang="scss">
@import "/src/assets/_variables.scss";

.annual-event-page {
  position: relative;
  padding: 130px 0 80px;
  display: flex;
  justify-content: center;
}

.festival-carousel {
  width: 100%;
  max-width: 1452px;
  margin: 0 auto;
  padding: 24px 0 16px;
  overflow: visible;
  background: radial-gradient(circle at center, #00000080, #000000);
  clip-path: polygon(4% 0, 100% 0, 96% 100%, 0 100%);
}

.festival-shell {
  overflow: visible;
  position: relative;
  width: 100%;
  max-width: 1452px;
  margin: 0 auto;
}

.carousel-inner {
  transform: none;
}

.carousel-track {
  display: flex;
  gap: 8px;
  height: 600px;
  padding: 0 48px;
}

/* arrows */
.nav-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 50;
  width: 40px;
  height: 40px;
  border-radius: 999px;
  border: none;
  background: #ffffff99;
  backdrop-filter: blur(6px);
  cursor: pointer;
  font-size: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.nav-prev {
  left: -28px;
}
.nav-next {
  right: -28px;
}
.nav-btn:hover {
  background: #ffffffdd;
}

/* 單張卡片：斜 + hover 放大 */
/* 卡片：只用 flex 擠開，不要 scale */
.slide {
  flex: 1;
  min-width: 0;
  cursor: pointer;
  transition: flex 0.45s ease;
  transform: none;
}
.slide.is-hovered {
  flex: 6; /* 你要更擠就 7 或 8 */
  transform: scale(1.02);   /* 你要更大可以 1.04 */
}

/* 卡片外形：用 clip-path 做平行四邊形（文字不會歪） */
.slide-tilt {
  height: 100%;
  overflow: visible;
  border-radius: 16px;
  background: #000; /* 避免邊緣有縫時露底色 */
  clip-path: polygon(8% 0, 100% 0, 92% 100%, 0 100%);
  box-shadow: 0 18px 40px rgba(0, 0, 0, 0.45);
  isolation: isolate; /* ✅ 讓 iframe 不會跑出裁切外 */
}

.media-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;               /* 一定要 */
  border-radius: 16px;            /* 讓四角圓角一致 */
  background: #000;               /* 防止邊緣露底 */
  clip-path: polygon(8% 0, 100% 0, 92% 100%, 0 100%); /* 平行四邊形 */
}

/* 底層影片 */
.slide-video-wrapper {
  position: absolute;
  inset: 0;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.35s ease;
}
.slide-video-wrapper.is-visible {
  opacity: 1;
  pointer-events: auto;
}
.slide-video-wrapper iframe {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transform: scale(1.08);
}

/* 中層圖片 */
.slide-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transform: scale(1.08); /* 蓋住 clip-path 斜邊 */
}
.slide-image.is-hidden {
  opacity: 0;
}

/* 最上層遮罩 + 文字：完全置中 */
.slide-overlay {
  position: absolute;
  inset: 0;
  background: radial-gradient(
    circle at center,
    #000000aa 0%,
    #000000dd 55%,
    #000000ee 100%
  );
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 24px;
  transition: opacity 0.35s ease;
}
.overlay-hidden {
  opacity: 0;
}

.slide-text {
  text-align: center;
  color: #fff;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Noto Sans TC",
    sans-serif;
}
.slide-title-line {
  font-size: 20px;
  letter-spacing: 0.08em;
  line-height: 1.4;
  text-shadow: 0 0 8px rgba(0, 0, 0, 0.7);
}
.slide-date {
  margin-top: 16px;
  font-size: 14px;
  letter-spacing: 0.18em;
}

/* dots */
.carousel-dots {
  margin-top: 12px;
  display: flex;
  justify-content: center;
  gap: 8px;
}
.dot {
  width: 8px;
  height: 8px;
  border-radius: 999px;
  border: none;
  background: #ffffff55;
  cursor: pointer;
}
.dot.active {
  width: 22px;
  background: #ffffffdd;
}

/* RWD */
@media (max-width: 1023px) {
  .carousel-track {
    padding: 0 24px;
    height: 320px;
  }
}

@media (max-width: 767px) {
  .annual-event-page {
    padding: 40px 0 60px;
  }

  .festival-carousel {
    padding: 16px 0;
    clip-path: none; /* 手機先改回矩形，避免太擠 */
  }

  .carousel-inner {
    transform: none;
  }

  .carousel-track {
    flex-direction: column;
    height: auto;
    padding: 0 16px;
    gap: 12px;
  }

  .slide,
  .slide.is-hovered {
    flex: none;
    height: 220px;
    transform: none;
  }

  .slide-tilt {
    transform: none;
    border-radius: 12px;
  }

  .swiper-pagination {
    display: none ;
  }

  .nav-btn {
    display: flex;
    width: 32px;
    height: 32px;
    font-size: 18px;
  }
  .nav-prev { left: -16px; }
  .nav-next { right: -16px; }

}
</style>

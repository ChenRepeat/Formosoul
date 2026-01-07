<script setup>
import { useEventData } from "@/stores/event";
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { useRouter } from "vue-router";
import { useLangStore } from "@/stores/lang";

const baseUrl = import.meta.env.BASE_URL;

const langStore = useLangStore();
const eventData = useEventData();
const router = useRouter();

const items = computed(() => eventData.eventDatas || []);

/** ===== Config ===== **/
const VISIBLE_COUNT = 4;
const intervalMs = 4000;

const HOVER_DELAY = 350; // hover 慢一點再開影片（250~600 自己調）
const DRAG_THRESHOLD_RATIO = 0.25; // 拖曳超過「單張寬」25%就換
const FLICK_VELOCITY = 0.7; // 甩動速度門檻(px/ms)

/** ===== State ===== **/
const currentIndex = ref(0);
const hoveredIndex = ref(null);

const timerId = ref(null);
const isMobile = ref(false);

const hoverTimer = ref(null);

const isDragging = ref(false);
const viewportEl = ref(null);
const trackEl = ref(null);

const drag = ref({
  active: false,
  startX: 0,
  dx: 0,
  startTime: 0,
  moved: false,
});

/** ===== Helpers ===== **/
const isEnglish = computed(() => langStore.locale === "en-US");

function getTitleLines(item) {
  const v = item?.title_en_s;
  if (Array.isArray(v)) return v;
  if (typeof v === "string" && v.trim()) return v.split(/\s+/);
  if (item?.title_en) return String(item.title_en).split(/\s+/);
  return [];
}

function getPicSrc(item) {
  const pic = item?.pic || item?.image || item?.img || "";
  return pic ? `${baseUrl}${pic}` : "";
}

function getYouTubeId(url) {
  if (!url) return "";
  const embedMatch = url.match(/\/embed\/([a-zA-Z0-9_-]{6,})/);
  if (embedMatch) return embedMatch[1];
  const watchMatch = url.match(/[?&]v=([a-zA-Z0-9_-]{6,})/);
  if (watchMatch) return watchMatch[1];
  const shortMatch = url.match(/youtu\.be\/([a-zA-Z0-9_-]{6,})/);
  if (shortMatch) return shortMatch[1];
  const shortsMatch = url.match(/\/shorts\/([a-zA-Z0-9_-]{6,})/);
  if (shortsMatch) return shortsMatch[1];
  return "";
}

function getYouTubeSrc(item) {
  const id = getYouTubeId(item.video);
  if (!id) return "";
  return `https://www.youtube.com/embed/${id}?autoplay=1&mute=1&controls=0&loop=1&playlist=${id}&playsinline=1&rel=0`;
}

function isPlaying(visibleIdx, item) {
  return hoveredIndex.value === visibleIdx && !!item?.video;
}

/** ===== Dots (4 items per page) ===== **/
const totalPages = computed(() => {
  const total = items.value.length;
  return total === 0 ? 0 : Math.ceil(total / VISIBLE_COUNT);
});

const currentPage = computed(() => {
  if (totalPages.value === 0) return 0;
  return Math.floor(currentIndex.value / VISIBLE_COUNT) % totalPages.value;
});

function goToPage(page) {
  const total = items.value.length;
  if (total === 0) return;

  const maxStart = Math.max(0, total - VISIBLE_COUNT);
  const target = page * VISIBLE_COUNT;

  currentIndex.value = Math.min(target, maxStart);
  startAutoSlide();
}

/** ===== Visible items ===== **/
const visibleItems = computed(() => {
  const total = items.value.length;
  if (total === 0) return [];

  const result = [];
  for (let offset = 0; offset < VISIBLE_COUNT; offset++) {
    const idx = (currentIndex.value + offset) % total;
    result.push(items.value[idx]);
  }
  return result;
});

/** ===== Navigation (infinite) ===== **/
function nextSlide() {
  const total = items.value.length;
  if (total === 0) return;
  currentIndex.value = (currentIndex.value + 1) % total;
}

function prevSlide() {
  const total = items.value.length;
  if (total === 0) return;
  currentIndex.value = (currentIndex.value - 1 + total) % total;
}

/** ===== Hover delay ===== **/
function handleMouseEnter(visibleIdx) {
  if (isMobile.value) return;
  if (isDragging.value) return;

  if (hoverTimer.value) clearTimeout(hoverTimer.value);
  hoverTimer.value = setTimeout(() => {
    hoveredIndex.value = visibleIdx;
  }, HOVER_DELAY);
}

function handleMouseLeave() {
  if (hoverTimer.value) {
    clearTimeout(hoverTimer.value);
    hoverTimer.value = null;
  }
  hoveredIndex.value = null;
}

/** ===== Drag ===== **/
function getStepWidth() {
  const el = viewportEl.value;
  if (!el) return 0;
  return el.clientWidth / VISIBLE_COUNT;
}

function setTrackTranslate(px) {
  if (!trackEl.value) return;
  trackEl.value.style.transform = `translateX(${px}px)`;
}

function setTrackTransition(on) {
  if (!trackEl.value) return;
  trackEl.value.style.transition = on ? "transform 260ms ease" : "none";
}

function onDragStart(e) {
  if (isMobile.value) return;

  drag.value.active = true;
  drag.value.moved = false;
  isDragging.value = true;

  hoveredIndex.value = null;
  stopAutoSlide();

  drag.value.startX = e.clientX;
  drag.value.dx = 0;
  drag.value.startTime = performance.now();

  setTrackTransition(false);
  viewportEl.value?.setPointerCapture?.(e.pointerId);
}

function onDragMove(e) {
  if (!drag.value.active) return;

  const dx = e.clientX - drag.value.startX;
  drag.value.dx = dx;

  if (Math.abs(dx) > 6) drag.value.moved = true;

  // 阻尼讓手感更像 iOS
  const step = getStepWidth();
  const resistance = step ? Math.min(1, step / (Math.abs(dx) + step)) : 1;
  const damped = dx * (0.9 + 0.1 * resistance);

  setTrackTranslate(damped);
}

function onDragEnd() {
  if (!drag.value.active) return;
  drag.value.active = false;

  const dx = drag.value.dx;
  const dt = performance.now() - drag.value.startTime;
  const v = dt > 0 ? Math.abs(dx) / dt : 0;

  const step = getStepWidth();
  const threshold = step * DRAG_THRESHOLD_RATIO;

  let delta = 0;
  if (v > FLICK_VELOCITY) delta = dx < 0 ? 1 : -1;
  else if (Math.abs(dx) > threshold) delta = dx < 0 ? 1 : -1;

  if (delta !== 0) {
    // infinite
    const total = items.value.length;
    if (total > 0) currentIndex.value = (currentIndex.value + delta + total) % total;
  }

  // 吸附回去
  setTrackTransition(true);
  setTrackTranslate(0);

  window.setTimeout(() => {
    isDragging.value = false;
    startAutoSlide();
  }, 280);
}

/** ===== Click ===== **/
function openDetail(item) {
  router.push({
    name: "FestivalDetail",
    params: { slug: item.title_en_s.join("-") },
  });
}

function onSlideClick(item) {
  // 拖曳中/有滑動就不算 click
  if (isDragging.value || drag.value.moved) return;
  openDetail(item);
}

/** ===== Auto slide ===== **/
function updateIsMobile() {
  if (typeof window === "undefined") return;
  isMobile.value = window.innerWidth < 768;
}

function startAutoSlide() {
  if (isMobile.value) return;
  stopAutoSlide();
  timerId.value = setInterval(() => {
    if (hoveredIndex.value === null && !isDragging.value) nextSlide();
  }, intervalMs);
}

function stopAutoSlide() {
  if (timerId.value) {
    clearInterval(timerId.value);
    timerId.value = null;
  }
}

/** ===== Lifecycle ===== **/
onMounted(() => {
  eventData.loadeventData();
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
    <div class="festival-shell" :class="{ 'is-dragging': isDragging }">
      <button class="nav-btn nav-prev" type="button" @click="prevSlide">‹</button>
      <button class="nav-btn nav-next" type="button" @click="nextSlide">›</button>

      <div class="festival-carousel">
        <div
          class="carousel-inner"
          ref="viewportEl"
          @pointerdown="onDragStart"
          @pointermove="onDragMove"
          @pointerup="onDragEnd"
          @pointercancel="onDragEnd"
        >
          <div class="carousel-track" ref="trackEl">
            <div
              v-for="(item, visibleIndex) in visibleItems"
              :key="item.id || `${currentIndex}-${visibleIndex}`"
              class="slide"
              :class="{ 'is-hovered': hoveredIndex === visibleIndex }"
              @mouseenter="handleMouseEnter(visibleIndex)"
              @mouseleave="handleMouseLeave"
              @click="onSlideClick(item)"
            >
              <!-- ✅ Video：放在最外層（不吃 clip-path，所以會是長方形） -->
              <div
                v-if="item.video"
                class="slide-video-wrapper"
                :class="{ 'is-visible': isPlaying(visibleIndex, item) }"
              >
                <iframe
                  class="youtube-iframe"
                  :src="getYouTubeSrc(item)"
                  frameborder="0"
                  allow="autoplay; encrypted-media; picture-in-picture"
                  allowfullscreen
                ></iframe>
              </div>

              <!-- ✅ Card：維持平行四邊形 -->
              <div class="slide-tilt">
                <div class="media-wrapper">
                  <img
                    class="slide-image"
                    :class="{ 'is-hidden': isPlaying(visibleIndex, item) }"
                    :src="getPicSrc(item)"
                    :alt="item.title_en || ''"
                  />

                  <div
                    class="slide-overlay"
                    :class="{ 'overlay-hidden': isPlaying(visibleIndex, item) }"
                  >
                    <div class="slide-text">
                      <template v-if="isEnglish">
                        <p
                          v-for="(line, i) in getTitleLines(item)"
                          :key="i"
                          class="slide-title-line"
                        >
                          {{ line }}
                        </p>
                      </template>

                      <p v-else class="slide-title-line">{{ item.title_zh }}</p>
                      <p class="slide-date">{{ item.launchDate }}</p>
                    </div>
                  </div>
                </div>
                <!-- /media-wrapper -->
              </div>
              <!-- /slide-tilt -->
            </div>
            <!-- /slide -->
          </div>
          <!-- /track -->
        </div>
        <!-- /inner -->
      </div>
      <!-- /carousel -->

      <div class="carousel-dots">
        <button
          v-for="p in totalPages"
          :key="p"
          class="dot"
          :class="{ active: p - 1 === currentPage }"
          type="button"
          @click="goToPage(p - 1)"
        />
      </div>
    </div>
  </section>
</template>


<style scoped lang="scss">
@import "/src/assets/_variables.scss";

/* ===== Layout ===== */
.annual-event-page {
  position: relative;
  padding: 52px 0 20px;
  display: flex;
  justify-content: center;
  overflow-x: clip;
}

.festival-shell {
  position: relative;
  width: 100%;
  margin: 0 auto;
  max-width: 1200px;
  overflow: visible;
}

.festival-carousel {
  width: 100%;
  margin: 0 auto;
  padding: 24px 55px 16px;
  background: radial-gradient(circle at center, #00000080, #000000);
}

/* ===== Viewport / Track ===== */
.carousel-inner {
  overflow: hidden;
  touch-action: pan-y;
  user-select: none;
  position: relative;
}

.carousel-track {
  display: flex;
  height: 600px;
  will-change: transform;
}

.festival-shell.is-dragging .carousel-track {
  cursor: grabbing;
}

/* ===== Arrows ===== */
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
  left: 12px;
}

.nav-next {
  right: 12px;
}

.nav-btn:hover {
  background: #ffffffdd;
}

/* ===== Slide sizing (flex expand on hover) ===== */
.slide {
  position: relative;
  flex: 1;
  min-width: 0;
  cursor: pointer;
  transition: flex 0.45s ease, transform 0.45s ease;
  transform: none;
}

.slide.is-hovered {
  flex: 6;
  transform: scale(1.02);
}

.festival-shell.is-dragging .slide,
.festival-shell.is-dragging .slide.is-hovered {
  transition: none !important;
  flex: 1 !important;
  transform: none !important;
}

/* ===== Shape (parallelogram) ===== */
.slide-tilt {
  height: 100%;
  background: #000;
  overflow: visible;
  isolation: isolate;
  clip-path: polygon(14% 0%, 100% 0%, 86% 100%, 0% 100%);
}

.media-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
  background: #000;
  clip-path: polygon(14% 0%, 100% 0%, 86% 100%, 0% 100%);
}

/* ===== Video layer ===== */
.slide-video-wrapper {
  position: absolute;
  inset: 6%;         /* 控制影片離邊距 */
  background: #000;
  opacity: 0;
  z-index: 10;
  overflow: hidden;
  pointer-events: none;
  transition: opacity .35s ease;
}

.slide-video-wrapper.is-visible {
  opacity: 1;
  pointer-events: auto;
}

.youtube-wrap {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #000;
}

.youtube-iframe {
  width: 100%;
  height: 100%;
  border: 0;
}

/* ===== Image layer ===== */
.slide-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transform: scale(1.08);
  transition: opacity 0.35s ease;
}

.slide-image.is-hidden {
  opacity: 0;
}

/* ===== Overlay text ===== */
.slide-overlay {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;

  padding: 40px 24px;
  background: linear-gradient(
    to bottom,
    rgba(0, 0, 0, 0.35) 0%,
    rgba(0, 0, 0, 0.65) 55%,
    rgba(0, 0, 0, 0.85) 100%
  );

  transition: opacity 0.35s ease;
}

.slide-overlay.overlay-hidden {
  opacity: 0;
}

.slide-text {
  text-align: center;
  color: #fff;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Noto Sans TC", sans-serif;
}

.slide-title-line {
  font-size: 22px;
  font-weight: 700;
  letter-spacing: 0.06em;
  line-height: 1.3;
  text-shadow: 0 4px 14px rgba(0, 0, 0, 0.75);
}

.slide-date {
  margin-top: 14px;
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 0.14em;
  opacity: 0.95;
}

/* ===== Dots ===== */
.carousel-dots {
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

/* ===== RWD ===== */
@media (max-width: 1023px) {
  .carousel-track {
    padding: 0 20px;
    height: 320px;
  }
}

@media (max-width: 767px) {
  .annual-event-page {
    padding: 40px 0 60px;
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

  .nav-btn {
    width: 32px;
    height: 32px;
    font-size: 18px;
    display: flex;
  }

  .nav-prev {
    left: -16px;
  }

  .nav-next {
    right: -16px;
  }
}
</style>

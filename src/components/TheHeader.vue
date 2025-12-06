<script setup>
import { ref } from 'vue';
import siteLogo from '@/assets/logo_white_S.png'; 
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const isMenuOpen = ref(false);
const isEnglish = ref(false)
function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value;
}
function toggleLang(){
  isEnglish.value = !isEnglish.value
}
</script>

<template>
  <div class="header-outer-case dp-flex">
    <a href="/"><img :src="siteLogo" alt="SiteLogo" /></a>

    <div class="header-link liquidGlass-wrapper dp-flex-col" :class="{ open: isMenuOpen }">

      <!-- 玻璃效果層 -->
      <div class="liquidGlass-effect"></div>
      <div class="liquidGlass-tint"></div>
      <div class="liquidGlass-shine"></div>

      <!-- 內容層 -->
      <div class="liquidGlass-content dp-flex" style="align-items: center; gap: 16px; width: auto;">
        
        <div class="header-lang-trigger dp-flex"  @click="toggleLang" :class="{ right: isEnglish }">
          <div class="header-lang-switcher" :class="{ right: isEnglish }"></div>
          <h6 class="trigger-lang">EN</h6>
          <h6 class="trigger-lang">CN</h6>
        </div>

        <div class="header-icons-list dp-flex">
          <a href="/shop"><font-awesome-icon icon="fa-solid fa-bag-shopping" class="header-icon"/></a>
          <a href="/member"><font-awesome-icon icon="fa-regular fa-circle-user" class="header-icon"/></a>
          
          <div class="transition hamburger-btn dp-flex-col" 
               @click="toggleMenu"
               :class="{ 'active': isMenuOpen }">
            <div class="bar bar1 transition"></div>
            <div class="bar bar2 transition"></div>
            <div class="bar bar3 transition"></div>
          </div>

          <font-awesome-icon icon="fa-solid fa-grip-vertical" class="header-icon draggable-icon" />
        </div>
      </div>

      <ul class="burger-list" :class="{ 'active': isMenuOpen }">
        <li><a href="/"><h4>Home</h4></a></li>
        <li><a href="/about"><h4>About</h4></a></li>
        <li><a href="/news"><h4>News</h4></a></li>
        <li><a href="/annualevent"><h4>Annual Event</h4></a></li>
        <li><a href="/professorsintroduction"><h4>Professor</h4></a></li>
        <li><a href="/survivalguide"><h4>Survival Guide</h4></a></li>
        <li><a href="/classes"><h4>Classes</h4></a></li>
        <li><a href="/policy"><h4>Policy</h4></a></li>
      </ul>

    </div>
  </div>

  <!-- SVG FILTER 玻璃遮罩 -->
  <svg style="display:none">
    <filter id="glass-distortion" x="0" y="0" width="100%" height="100%">
      <feTurbulence type="fractalNoise" baseFrequency="0.01" numOctaves="1" seed="5" result="turbulence" />
      <feGaussianBlur in="turbulence" stdDeviation="2" result="softMap" />
      <feDisplacementMap in="SourceGraphic" in2="softMap" scale="200" xChannelSelector="R" yChannelSelector="G"/>
    </filter>
  </svg>
</template>

<style scoped lang="scss">
.header-outer-case {
  padding: 0 40px 0 60px;
  height: 100px;
  justify-content: space-between;
  align-items: center;
  position: fixed;
  width: 100%;
  top: 0;
  left: 0;
  z-index: 1000;
}

img { object-fit: none; }

.trigger-lang { color: $color-fsWhite; margin: 0; }

.header-lang-trigger {
  width: 85px;
  height: 40px;
  border: 1px solid $color-fsWhite;
  padding: 0 10px;
  justify-content: space-around;
  align-items: center;
  border-radius: 20px;
  position: relative;
  cursor: pointer;
  background-color: unset;
  transition: all 1s 0.3s;
}
.header-lang-trigger.right{
  border: 1px solid $color-fsTitle;
  background-color: $color-fsTitle;
}
.header-lang-switcher{
  width: 30px;
  height: 30px;
  position: absolute;
  background-color: $color-fsTitle;
  z-index: 1001;
  border-radius: 50%;
  top: 4px;
  left: 9px;
  transition: all 1s ease;
}
.header-lang-switcher.right{
  left: 44px;
  background-color: $color-fsWhite;

}
.header-link {
  border-radius: 36px;
  padding: 12px 40px;
  height: 64px;
  gap: 16px;
  position: absolute;
  flex-direction: column; 
  overflow: hidden;
  transition: all 1s ease;
  right: 0;
  top: 0;
  transform: translateY(0);
}

.header-link.open {
  height: 100vh; 
  transform: translateY(45vh);
}

/* --- XXXXX 玻璃效果 勿動 XXXXX --- */
.liquidGlass-wrapper {
  position: relative;
  border-radius: 36px;
  overflow: hidden;
  flex-direction: column;
  background: rgba(255, 255, 255, 0.12);
  box-shadow: 
    inset 0 2px 6px rgba(255,255,255,0.2),
    0 4px 10px rgba(0,0,0,0.2);
  backdrop-filter: blur(8px); 
}

.liquidGlass-effect { position: absolute; inset: 0; backdrop-filter: blur(4px); filter: url(#glass-distortion); z-index: 0; }
.liquidGlass-tint { position: absolute; inset: 0; background: rgba(255, 255, 255, 0.28); z-index: 1; }
.liquidGlass-shine { position: absolute; inset: 0; box-shadow: inset 2px 2px 1px rgba(255,255,255,0.4), inset -2px -2px 2px rgba(255,255,255,0.2); z-index: 2; }

.liquidGlass-content { position: relative; z-index: 10; align-items: center; gap: 16px; width: 100%; }

.header-icons-list { gap: 16px; align-items: center; }

.header-icon { color: $color-fsWhite; font-size: 32px; cursor: pointer; }
.draggable-icon { cursor: move ; position: relative; z-index: 1001;}

.burger-list {
  list-style: none;
  padding: 20px;
  margin: 0;
  background: transparent;
  border-radius: 12px;
  opacity: 0;
  transform: translateY(100%);
  transition: all 0.3s ease;
  width: 100%;
  z-index: -2;
}

.burger-list li { margin: 10px 0; }
.burger-list li a { color: $color-fsWhite; text-decoration: none; padding: 8px 12px; display: block; border-radius: 4px; transition: background-color 0.3s; }
.burger-list li a:hover { background-color: rgba(255, 255, 255, 0.1); }
.burger-list.active { opacity: 1; transform: translateY(0); z-index: 11;}

/* 漢堡按鈕 */
.bar { background-color: $color-fsWhite; border-radius: 8px; }
.hamburger-btn { justify-content: center; gap: 8px; align-items: end; cursor: pointer; }
.bar1 { width: 16px; height: 2px; }
.bar2 { width: 12px; height: 1px; }
.bar3 { width: 24px; height: 3px; }
.transition { transition: 0.3s ease; }
.hamburger-btn.active .bar1 { transform: rotate(-45deg) translate(-8px, 5px); width: 24px; height: 3px; }
.hamburger-btn.active .bar2 { width: 0; height: 0; opacity: 0; }
.hamburger-btn.active .bar3 { transform: rotate(45deg) translate(-9px, -5px); }
</style>

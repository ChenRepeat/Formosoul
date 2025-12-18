<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import gsap from 'gsap'; 
import Wave from '@/components/Wave.vue';

// 1. 綁定 DOM 元素
const mapSectionRef = ref(null);    // 世界地圖區
const airMapRef = ref(null);        // 台灣機場區
const portkeyRef = ref(null);       // 港口卡片區

// 2. 控制動畫開關 (進入 true, 離開 false)
const isMapVisible = ref(false);
const isAirMapVisible = ref(false);
const isPortkeyVisible = ref(false);

let observer;

const airports = ref([
  { id: 1, name: 'Taipei Songshan Airport', code: 'TSA', top: 75, left: 93 }, 
  { id: 2, name: 'Taiwan Taoyuan Int. Airport', code: 'TPE', top: 53, left: 85 },
  { id: 3, name: 'Taichung Int. Airport', code: 'RMQ', top: 20, left: 59 },
  { id: 4, name: 'Kaohsiung Int. Airport', code: 'KHH', top: 12, left: 31 },
]);

// 港口位置
const shipLocations = ref([
  { id: 1, name: 'Wuling', lat: '24.1372° N', lng: '121.2757° E', style: { top: '30%', left: '0%' } },
  { id: 2, name: 'Sizihwan', lat: '22.625809° N', lng: '120.264044° E', style: { top: '12%', left: '20%' } },
  { id: 3, name: 'Xiaoliuqiu', lat: '22.338644° N', lng: '120.369817° E', style: { top: '0%', left: '50%' } },
  { id: 4, name: 'Calligraphy Greenway', lat: '24.151290° N', lng: '120.663210° E', style: { top: '8%', right: '20%' } },
  { id: 5, name: 'Luye Highland', lat: '22.913389° N', lng: '121.119611° E', style: { bottom: '15%', left: '10%' } },
  { id: 6, name: 'Ci Sing Tan', lat: '24.032889° N', lng: '121.627944° E', style: { bottom: '0%', left: '37%' } },
  { id: 7, name: 'Kinmen', lat: '24.44° N', lng: '118.33° E', style: { bottom: '40%', right: '0%' } },
]);

const islandRef = ref(null);
let floatCtx; 

onMounted(() => {
  // GSAP 漂浮動畫
  floatCtx = gsap.context(() => {
    gsap.to(islandRef.value, {
      y: 100,
      duration: 4,
      repeat: -1,
      yoyo: true,
      ease: "sine.inOut"
    });
  });

  observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      //地圖
      if (entry.target === mapSectionRef.value) {
        isMapVisible.value = entry.isIntersecting;
      }
      //機場
      if (entry.target === airMapRef.value) {
        isAirMapVisible.value = entry.isIntersecting;
      }
      //港口
      if (entry.target === portkeyRef.value) {
        isPortkeyVisible.value = entry.isIntersecting;
      }
    });
  }, { threshold: 0.3 }); 

  // 監控
  if (mapSectionRef.value) observer.observe(mapSectionRef.value);
  if (airMapRef.value) observer.observe(airMapRef.value);
  if (portkeyRef.value) observer.observe(portkeyRef.value);
});

onUnmounted(() => {
  floatCtx && floatCtx.revert(); 
  if (observer) observer.disconnect();
});
</script>

<template>
  <Wave/>
  <div class="about-container">

    <div class="island-text">
      <h4>About</h4>
      <h1>Formosoul</h1>
      <h6 class="fw200">— A magical nexus weaving through diverse civilizations.</h6>
      <br>
      <p class="lore-text fw200">
        Hidden within the clouds ten thousand feet above Taiwan, the Academy is a modern floating island suspended in the heavens. Guarded by powerful illusions and invisible to the naked eye, only the chosen ones holding an "Enrolment Letter" may pierce the veil of mist to enter this sanctuary of magic.
        <br>
        We are dedicated to exploring the ultimate equilibrium of magic: intertwining the ancient, natural spiritual energies preserved within the island of Taiwan with the precise and rigorous spellcasting techniques of the West. At Formosoul, we do not merely teach magic; within the intertwining and resonance of diverse systems, we manifest the most balanced and formidable magical framework of the modern era.
      </p>
    </div>
    
    <img 
      ref="islandRef" 
      src="/public/About/about-island.png" 
      class="island-img" 
      alt="#"
    >
    
    <div class="location-container" ref="mapSectionRef" :class="{ 'active': isMapVisible }">
      <h2>Location</h2>
      <div class="map-container">
        <img src="/public/About/world-map.png" alt="#">
        <span class="pin-point">
          <font-awesome-icon :icon="['fas', 'location-dot']"/>
        </span>
      </div>
    </div>

    <div class="travel-modes">
      <h2>Modes of Travel</h2>
      <p class="fw200">Please ensure you have all necessary travel permits from the Ministry of Magic. For Muggle visitors, we recommend the 'By Air' option.</p>
    </div>

    <div class="by-air">
      <div class="list-category-group">
        <div class="list-dock">
          <div class="list-liquidGlass">
            <font-awesome-icon class="list-faIcon" icon="fa-solid fa-plane" />
          </div>
        </div>
      </div>
      <h5>By Air<br><span class="fw200">(For everyone)</span></h5>
    </div>

    <div class="air-map-section" ref="airMapRef">
      <div class="taiwan-map-container" :class="{ 'show-details': isAirMapVisible }">
        <img src="/public/About/taiwan-map.png" class="taiwan-map" alt="Taiwan Map">
        
        <div 
          v-for="(point, index) in airports" 
          :key="point.id"
          class="map-point"
          :style="{ 
            top: point.top + '%', 
            left: point.left + '%',
            '--delay': index * 0.2 + 's' 
          }"
        >
          <div class="icon-wrapper">
            <!-- <font-awesome-icon :icon="['fas', 'plane']" class="airport-icon" /> -->
          </div>
          <div class="dot"></div>
          
          <div class="popup-content">
              <div class="dashed-line"></div>
              <p class="label-text">{{ point.name }} <span class="code">({{ point.code }})</span></p>
          </div>
        </div>
      </div>
    </div>

    <div class="portkey-section" ref="portkeyRef" :class="{ 'active': isPortkeyVisible }">
      
      <div 
        v-for="(item, index) in shipLocations" 
        :key="item.id" 
        class="ship-location-card"
        :style="[item.style, { '--delay': index * 0.15 + 's' }]" 
      >
        <h3>{{ item.name }}</h3>
        <div class="divider"></div> 
        <p class="coords">
          {{ item.lat }} <br>
          {{ item.lng }}
        </p>
      </div>

      <div class="by-sea">
        <div class="list-category-group">
          <div class="list-dock">
            <div class="list-liquidGlass">
              <font-awesome-icon class="list-faIcon" icon="fa-solid fa-anchor" />
            </div>
          </div>
        </div>
        <div>
          <h5>Portkey<br><span class="fw200">(For magical partners)</span></h5>
          <p class="fw200">Ensure your target coordinates are precise to prevent accidental misdirection.</p>
        </div>
      </div>
    </div>

  </div>
</template>

<style lang="scss" scoped>
  .about-container {
    position: relative; 
    overflow: hidden;
    color: #fff;
    background-color: #000;   
  }

  .island-text {
    max-width: 760px;
    margin: 180px auto 140px; 
    color: white;
    position: relative; 
    z-index: 15;
    & h1{
      margin: 12px 0;
    }
  }
  .lore-text {
    text-align: justify;
    hyphens: auto;
    -webkit-hyphens: auto;
    margin-top: 20px;
    position: relative; 
    z-index: 15;
  }

  .island-img {
    display: block;      
    margin: 140px auto 315px;
    width: 50%;          
    height: auto;
    position: relative;  
    z-index: 10;
  }

  .location-container{
    width: 100%;
    margin: 0 auto;
    text-align: center;
    position: relative;
    z-index: 5;
  }
  .location-container h2{ margin-bottom: 110px; }
  .location-container img{ display: block; width: 50%; height: auto; margin: 0 auto; }
  .map-container{ position: relative; }
  
  /* --- World Map Pin --- */
  .pin-point {
    position: absolute;
    top: 59%; left: 56.5%;   
    transform: translate(-50%, -100%) scale(0); 
    opacity: 0; 
    color: #FF9010;
    font-size: 40px;
    z-index: 20;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;

    &::after {
      content: '';
      position: absolute;
      width: 20px; height: 20px;
      background-color: rgba(255, 144, 16, 0.6);
      border-radius: 50%;
      z-index: -1;
      top: 80%; left: 50%;
      transform: translate(-50%, -50%);
      opacity: 0; 
    }
  }

  .location-container.active {
    .pin-point {
      animation: pop-in 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards, glow-breath 2.5s ease-in-out infinite 0.6s; 
      opacity: 1;
    }
    .pin-point::after {
      animation: radar-wave 2s infinite cubic-bezier(0, 0, 0.2, 1);
    }
  }

  @keyframes pop-in {
    0% { opacity: 0; transform: translate(-50%, -100%) scale(0.5); }
    100% { opacity: 1; transform: translate(-50%, -100%) scale(1); }
  }

  @keyframes glow-breath {
    0%, 100% { filter: drop-shadow(0 0 5px rgba(255, 144, 16, 0.6)); transform: translate(-50%, -100%) scale(1); }
    50% { filter: drop-shadow(0 0 20px rgba(255, 144, 16, 0.9)); transform: translate(-50%, -105%) scale(1.1); }
  }

  @keyframes radar-wave {
    0% { transform: translate(-50%, -50%) scale(1); opacity: 0.8; }
    100% { transform: translate(-50%, -50%) scale(4); opacity: 0; }
  }

  .travel-modes{ 
    width: 635px; 
    margin: 240px auto 0; 
    position: relative;
    z-index: 15;
  }
  .travel-modes h2{ margin-bottom: 40px; }

  /* --- Icons --- */
  .list-category{ justify-content: center; } 
  .list-category-group{ margin-right: 50px; }
  .list-dock{
    width: 120px; height: 120px;
    border-radius: 50%;
    backdrop-filter: blur(5px);
    box-shadow: inset 0 0 120px rgba(255, 255, 255, 0.2);
  }
  .list-liquidGlass{
    width: 120px; height: 120px;
    border-radius: 50%;
    backdrop-filter: blur(15px);
    box-shadow: 0 0 8px rgba(255, 255, 255, 0.5) ;
    position: relative;
  }
  .list-category-group .list-faIcon{
    font-size: 48px; color: $color-fsWhite;
    position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
  }
  .list-category-group:hover .list-dock{ transform: scale(1.1); }
  .by-air{ display: flex; align-items: center; justify-content: center; margin: 215px 0;    position: relative;
    z-index: 15; }

  /* --- Taiwan Map Section --- */
  .air-map-section {
    width: 100%; display: flex; justify-content: center;
    padding: 50px 0 0; margin-bottom: 372px;
        position: relative;
    z-index: 15;
  }
  .taiwan-map-container {
    position: relative; width: 60%; max-width: 800px;
    .taiwan-map { width: 100%; display: block; height: auto; }
        position: relative;
    z-index: 15;
  }
  .map-point {
    position: absolute; width: 20px; height: 20px;
    transform: translate(-50%, -50%); z-index: 10;
  }

  /* Airport Animations */
  .icon-wrapper {
    position: absolute; top: 0; left: 50%;
    transform: translate(-50%, 0);
    opacity: 0;
    transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) calc(var(--delay) + 0.1s); 
    pointer-events: none; z-index: 3;
    
    //.airport-icon { font-size: 30px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5)); }
    // 改成圓點
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #fff;
    box-shadow: 0 0 5px #fff;
  }
  .popup-content {
    position: absolute; bottom: 20px; left: 50%;
    transform: translateX(-50%);
    display: flex; flex-direction: column-reverse; 
    align-items: center; width: 200px; pointer-events: none; 
  }
  .dashed-line {
    width: 1px; height: 0;
    border-left: 1px dashed rgba(255, 255, 255, 0.6);
    opacity: 0;
    transition: height 0.7s ease-out calc(var(--delay) + 0.2s), opacity 0.3s calc(var(--delay) + 0.2s);
  }
  .label-text {
    color: #fff; font-size: 14px; margin-bottom: 5px; text-align: center;
    opacity: 0; transform: translateY(10px);
    transition: all 0.5s ease calc(var(--delay) + 0.4s);
    text-shadow: 0 2px 4px rgba(0,0,0,0.8);
    white-space: nowrap;
    .code { font-size: 12px; color: #ccc; }
  }

  /* Active State for Airport Map */
  .taiwan-map-container.show-details {
    .icon-wrapper { opacity: 1; transform: translate(-50%, -10px); }
    .dashed-line { height: 100px; opacity: 1; }
    .label-text { opacity: 1; transform: translateY(0); }
  }

  /* --- Portkey Section --- */
  .by-sea{ 
    display: flex; width: 560px; margin: 0 auto; 
    & h5{
      margin-bottom: 20px;
    }
  }
  .portkey-section {
    position: relative; width: 100%; max-width: 1200px;
    margin: 0 auto 220px; min-height: 900px;
    display: flex; align-items: center;
        position: relative;
    z-index: 15;
  }

  .ship-location-card {
    position: absolute; 
    width: fit-content; max-width: 160px; height: auto;
    padding: 20px; display: flex; flex-direction: column;
    
    background: rgba(255, 255, 255, 0.08); 
    backdrop-filter: blur(10px);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;

    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.6s ease, transform 0.6s ease, background 0.3s; 
    
    &:hover {
      transform: scale(1.05); 
      background: rgba(255, 255, 255, 0.15);
      z-index: 5;
      transition-delay: 0s !important; 
    }

    h3 { font-size: 18px; font-weight: 500; margin-bottom: 20px; letter-spacing: 1px; }
    .divider { width: 20px; height: 1px; background-color: #777; margin: 20px 0; }
    .coords { font-size: 12px; color: #bbb; }
  }

  .portkey-section.active .ship-location-card {
    opacity: 1;
    transform: translateY(0);
    transition-delay: var(--delay);
  }

  @media (max-width: 768px) {
    .portkey-section {
      flex-direction: column; height: auto; padding: 60px 20px;
    }
    .ship-location-card {
      position: relative; 
      top: auto !important; left: auto !important;
      right: auto !important; bottom: auto !important;
      transform: none !important; /* 手機版不使用位移動畫 */
      
      width: 100%; margin-bottom: 15px; display: flex; align-items: center;
    }
    .ship-location-card .divider { display: none; } 
    .ship-location-card h3 { margin-bottom: 0; }
    .ship-location-card .coords { text-align: right; }
  }
</style>
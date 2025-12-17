<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import gsap from 'gsap'; 
import Wave from '@/components/Wave.vue';

const airports = ref([
  { id: 1, name: 'Taipei Songshan Airport', code: 'TSA', top: 75, left: 93 }, 
  { id: 2, name: 'Taiwan Taoyuan Int. Airport', code: 'TPE', top: 53, left: 85 },
  { id: 3, name: 'Taichung Int. Airport', code: 'RMQ', top: 20, left: 59 },
  { id: 4, name: 'Kaohsiung Int. Airport', code: 'KHH', top: 12, left: 31 },
]);

// 港口位置
const shipLocations = ref([
  { 
    id: 1, 
    name: 'Wuling', 
    lat: '24.1372° N', 
    lng: '121.2757° E',
    style: { top: '30%', left: '0%' } 
  },
  { 
    id: 2, 
    name: 'Sizihwan', 
    lat: '22.625809° N', 
    lng: '120.264044° E',
    style: { top: '12%', left: '20%' } 
  },
  { 
    id: 3, 
    name: 'Xiaoliuqiu', 
    lat: '22.338644° N', 
    lng: '120.369817° E',
    style: { top: '0%', left: '50%' } 
  },
  { 
    id: 4, 
    name: 'Calligraphy Greenway', 
    lat: '24.151290° N', 
    lng: '120.663210° E',
    style: { top: '8%', right: '20%' } 
  },
  { 
    id: 5, 
    name: 'Luye Highland', 
    lat: '22.913389° N', 
    lng: '121.119611° E',
    style: { bottom: '15%', left: '10%' } 
  },
  { 
    id: 6, 
    name: 'Ci Sing Tan', 
    lat: '24.032889° N', 
    lng: '121.627944° E',
    style: { bottom: '0%', left: '37%' } 
  },
  { 
    id: 7, 
    name: 'Kinmen', 
    lat: '24.44° N', 
    lng: '118.33° E',
    style: { bottom: '40%', right: '0%' } 
  },
]);

const islandRef = ref(null);
let floatCtx; // 存放 GSAP context

onMounted(() => {
  floatCtx = gsap.context(() => {
    
    // 動畫一：垂直漂浮 (模擬波浪起伏)
    gsap.to(islandRef.value, {
      y: 100,           // 向下位移
      duration: 4,      // 持續時間
      repeat: -1,      // 循環
      yoyo: true,      // 反向
      ease: "sine.inOut" // 正弦曲線搭波浪
    });
    gsap.fromTo(islandRef.value, 
      { 
        rotation: -1.5 // 起始
      },
      {
        rotation: 1.5, // 結束
        duration: 4,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
        transformOrigin: "50% 70%" // 設定重心
      }
    );

  });
});

onUnmounted(() => {
  floatCtx && floatCtx.revert(); // 組件卸載時清理動畫
});
</script>

<template>
  <div class="about-container">

    <div class="island-text">
      <h2>About</h2>
      <h1>Formosoul</h1>
      <p class="lore-text">— A magical nexus weaving through diverse civilizations.Hidden within the clouds ten thousand feet above Taiwan, the Academy is a modern floating island suspended in the heavens. Guarded by powerful illusions and invisible to the naked eye, only the chosen ones holding an "Enrolment Letter" may pierce the veil of mist to enter this sanctuary of magic.We are dedicated to exploring the ultimate equilibrium of magic: intertwining the ancient, natural spiritual energies preserved within the island of Taiwan with the precise and rigorous spellcasting techniques of the West. At Formosoul, we do not merely teach magic; within the intertwining and resonance of diverse systems, we manifest the most balanced and formidable magical framework of the modern era.
      </p>
    </div>
    
    <img 
      ref="islandRef" 
      src="/public/About/about-island.png" 
      class="island-img" 
      alt="#"
    >
    
    <Wave class="wave-container"/>
    <div class="location-container">
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
      <p>Please ensure you have all necessary travel permits from the Ministry of Magic. For Muggle visitors, we recommend the 'By Air' option.</p>
    </div>
    <!-- 台灣島與機場 -->
    <div class="by-air">
      <div class="list-category-group">
        <div class="list-dock">
          <div class="list-liquidGlass">
            <font-awesome-icon class="list-faIcon" icon="fa-solid fa-plane" />
          </div>
        </div>
      </div>
    <h5>
      By Air
      <br>
      (For everyone)
    </h5>
    </div>
    <div class="air-map-section">
    
    <div class="taiwan-map-container">
      <img src="/public/About/taiwan-map.png" class="taiwan-map" alt="Taiwan Map">
      <div 
        v-for="point in airports" 
        :key="point.id"
        class="map-point"
        :style="{ top: point.top + '%', left: point.left + '%' }"
      >

      <div class="icon-wrapper">
        <font-awesome-icon :icon="['fas', 'plane']" class="airport-icon" />
      </div>
        <div class="dot"></div>
        
        <div class="popup-content">
           <div class="dashed-line"></div>
           <p class="label-text">{{ point.name }} <span class="code">({{ point.code }})</span></p>
        </div>
      </div>
      
    </div>
    </div>
    <!-- 港口 -->
    <div class="portkey-section">
    
      <div 
        v-for="item in shipLocations" 
        :key="item.id" 
        class="ship-location-card"
        :style="item.style"
      >
        <h3>{{ item.name }}</h3>
        <div class="divider"></div> <p class="coords">
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
              <h5>
                Portkey
                <br>
                (For magical partners)
              </h5>
              <p>Ensure your target coordinates are precise to prevent accidental misdirection.</p>
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
    margin: 120px auto 140px; 
    color: white;
    position: relative; 
    z-index: 10;
  }
//英文字排版對齊
  .lore-text {
  text-align: justify;
  hyphens: auto;
  -webkit-hyphens: auto;
  }

  .island-img {
    display: block;      
    margin: 0 auto;
    width: 50%;          
    height: auto;
    position: relative;  
    z-index: 10;
  }
  .wave-container{
    margin-top: -200px;
  }
  .location-container{
    width: 100%;
    margin: 0 auto;
    text-align: center;
    position: relative;
  }
  .location-container h2{
    margin-bottom: 110px;
  }
  .location-container img{
    display: block;
    width: 50%;
    height: auto;
    margin: 0 auto;
  }
  .map-container{
    position: relative;
  }
  .pin-point{
    position: absolute;
    top: 59%;    
    left: 56.5%;   
    transform: translate(-50%, -100%); 
    color: #FF9010;
    font-size: 40px;
    z-index: 20;
  }
  .travel-modes{
    width: 635px;
    margin: 240px auto 0;
  }
  .travel-modes h2{
    margin-bottom: 40px;
  }

//by-air圖示
.list-category{
  justify-content: center;
} 

.list-category-group{
  margin-right: 50px;
  cursor: pointer;
}

.list-dock{
  width: 120px;
  height: 120px;
  border-radius: 50%;
  backdrop-filter: blur(5px);
  box-shadow: inset 0 0 120px rgba(255, 255, 255, 0.2);
}

.list-liquidGlass{
  width: 120px;
  height: 120px;
  border-radius: 50%;
  backdrop-filter: blur(15px);
  box-shadow: 0 0 8px rgba(255, 255, 255, 0.5) ;

  position: relative;
}

.list-category-group .list-faIcon{
  font-size: 48px;

  color: $color-fsWhite;
  
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.list-category-group:hover .list-dock{
  transform: scale(1.1);
}

.by-air{
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 215px 0;
}

//台灣島與機場位置
.air-map-section {
  width: 100%;
  display: flex;
  justify-content: center;
  padding: 50px 0 0;
  margin-bottom: 372px;
}

.taiwan-map-container {
  position: relative;
  width: 60%;
  max-width: 800px;
  
  .taiwan-map {
    width: 100%;
    display: block;
    height: auto;
  }
}

.map-point {
  position: absolute;
  width: 20px;
  height: 20px;
  /* 讓點的中心對準座標，而不是左上角對準 */
  transform: translate(-50%, -50%);
  cursor: pointer;
  z-index: 10;

  &:hover {
    .icon-wrapper {
      opacity: 1;
      transform: translate(-50%, -40px); 
    }
    .dashed-line {
      height: 100px;
      opacity: 1;
    }
    .label-text {
      opacity: 1;
      transform: translateY(0);
      transition-delay: 0.2s; /*延遲文字 */
    }
  }
}

.icon-wrapper {
  position: absolute;
  top: 0;
  left: 50%;
  transform: translate(-50%, 0); // 預設位置在點的正上方
  opacity: 0;
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
  pointer-events: none; // 避免滑鼠指到它
  z-index: 3;

  .airport-icon {
    font-size: 30px;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
  }
}

.dot {
  width: 15px;
  height: 15px;
  background-color: #00529F;
  border-radius: 50%;
  margin: 0 auto;
  box-shadow: 0 0 10px #00529F, 0 0 20px #00529F;
  animation: pulse 2s infinite; // 呼吸燈動畫
  position: relative;
  z-index: 2;
}

.popup-content {
  position: absolute;
  bottom: 10px; // 從點的上方開始
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-direction: column-reverse; // 讓線在下，文字在上
  align-items: center;
  width: 200px; //避免文字換行
  pointer-events: none; // 避免擋到滑鼠
}

//虛線
.dashed-line {
  width: 1px;
  height: 0; 
  border-left: 1px dashed rgba(255, 255, 255, 0.6);
  transition: height 0.4s ease-out, opacity 0.3s;
  opacity: 0;
}

//文字
.label-text {
  color: #fff;
  font-size: 14px;
  margin-bottom: 5px;
  text-align: center;
  opacity: 0;
  transform: translateY(10px);
  transition: opacity 0.4s ease, transform 0.4s ease;
  text-shadow: 0 2px 4px rgba(0,0,0,0.8);
  
  .code {
    font-size: 12px;
    color: #ccc;
  }
}

// 呼吸燈動畫 Keyframes
@keyframes pulse {
  0% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.3); opacity: 0.7; }
  100% { transform: scale(1); opacity: 1; }
}
  @media (max-width: 768px) {
    .island-text {
      width: 90%;
      margin: 60px auto;
    }
    .island-img {
      width: 80%;
    }
  }

  @media (min-width: 1024px) {
    .about-container { 
      min-height: 100vh;
    }
  }

  //港口
  .by-sea{
  display: flex;
    width: 560px;
    margin: 0 auto;
  }
  .portkey-section {
  position: relative;
  width: 100%;
  max-width: 1200px;
  margin: 0 auto 220px;
  min-height: 900px;
  display: flex;
  align-items: center;
}

/* 船旁邊卡片 */
.ship-location-card {
  position: absolute; 
  width: fit-content;
  max-width: 160px;
  height: auto;
  padding: 20px;
  display: flex;
  flex-direction: column;
  
  /* 毛玻璃效果 */
  background: rgba(255, 255, 255, 0.08); 
  backdrop-filter: blur(10px);
  border-radius: 8px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  
  color: #fff;
  transition: transform 0.3s ease, background 0.3s;
  
  &:hover {
    transform: scale(1.05);
    background: rgba(255, 255, 255, 0.15);
    z-index: 5;
  }

  h3 {
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 20px;
    letter-spacing: 1px;
  }

  .divider {
    width: 20px;
    height: 1px;
    background-color: #777;
    margin: 20px 0;
  }

  .coords {
    font-size: 12px;
    color: #bbb;
  }
}

@media (max-width: 768px) {
  .portkey-section {
    flex-direction: column; 
    height: auto;
    padding: 60px 20px;
  }
  .ship-location-card {
    position: relative; 
    top: auto !important;
    left: auto !important;
    right: auto !important;
    bottom: auto !important;
    transform: none !important;
    
    width: 100%; 
    margin-bottom: 15px;
    display: flex;
    align-items: center;
  }
  
  .ship-location-card .divider { display: none; } 
  .ship-location-card h3 { margin-bottom: 0; }
  .ship-location-card .coords { text-align: right; }
}
</style>
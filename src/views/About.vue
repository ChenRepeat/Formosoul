<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import gsap from 'gsap'; 
import Wave from '@/components/Wave.vue';

const airports = ref([
  { id: 1, name: 'Taipei Songshan Airport', code: 'TSA', top: 25, left: 70 }, 
  { id: 2, name: 'Taiwan Taoyuan Int. Airport', code: 'TPE', top: 20, left: 60 },
  { id: 3, name: 'Taichung Int. Airport', code: 'RMQ', top: 45, left: 45 },
  { id: 4, name: 'Kaohsiung Int. Airport', code: 'KHH', top: 80, left: 30 },
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
      <p>Quidam officiis similique sea ei, vel tollit indoctum efficiendi ei, at nihil tantas platonem eos. 
      <br>
      Quidam officiis similique sea ei, vel tollit indoctum efficiendi ei, at nihil tantas platonem eos. 
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
    <div class="by-air">
      <img src="/public/About/travel-air.png" alt="#">
      <h5>
        By Air
        <br>
        (For everyone)
      </h5>
    </div>
    <div class="map-section">
    
    <div class="taiwan-map-container">
      <img src="/public/About/taiwan-map.png" class="taiwan-map" alt="Taiwan Map">

      <div 
        v-for="point in airports" 
        :key="point.id"
        class="map-point"
        :style="{ top: point.top + '%', left: point.left + '%' }"
      >
        <div class="dot"></div>
        
        <div class="popup-content">
           <div class="dashed-line"></div>
           <p class="label-text">{{ point.name }} <span class="code">({{ point.code }})</span></p>
        </div>
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
  }

  .island-text {
    max-width: 760px;
    margin: 120px auto 140px; 
    color: white;
    position: relative; 
    z-index: 10;
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

  .map-section {
  width: 100%;
  display: flex;
  justify-content: center;
  padding: 50px 0;
}

.taiwan-map-container {
  position: relative; /* 關鍵：讓裡面的點以這張圖為基準 */
  width: 60%;         /* 控制地圖大小 */
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

  // 當滑鼠移到整個座標點區域時觸發
  &:hover {
    .dashed-line {
      height: 100px; /* 線條伸長的高度，依需求調整 */
      opacity: 1;
    }
    .label-text {
      opacity: 1;
      transform: translateY(0);
      transition-delay: 0.2s; /* 線條長出來後，文字才出來 */
    }
  }
}

// A. 發光點樣式
.dot {
  width: 12px;
  height: 12px;
  background-color: #4ECDC4; // 你的主題色
  border-radius: 50%;
  margin: 0 auto;
  box-shadow: 0 0 10px #4ECDC4, 0 0 20px #4ECDC4;
  animation: pulse 2s infinite; // 呼吸燈動畫
  position: relative;
  z-index: 2;
}

// B. 彈出內容容器 (往上長)
.popup-content {
  position: absolute;
  bottom: 10px; // 從點的上方開始
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-direction: column-reverse; // 讓線在下，文字在上
  align-items: center;
  width: 200px; // 給寬度避免文字換行太醜
  pointer-events: none; // 避免擋到滑鼠
}

// C. 虛線
.dashed-line {
  width: 1px;
  height: 0; // 預設高度 0
  border-left: 1px dashed rgba(255, 255, 255, 0.6);
  transition: height 0.4s ease-out, opacity 0.3s;
  opacity: 0;
}

// D. 文字標籤
.label-text {
  color: #fff;
  font-size: 14px;
  margin-bottom: 5px;
  text-align: center;
  opacity: 0;
  transform: translateY(10px); // 稍微往下位移，浮現時往上滑
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
</style>
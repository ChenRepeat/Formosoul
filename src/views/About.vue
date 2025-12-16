<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import gsap from 'gsap'; // 1. 引入 GSAP
import Wave from '@/components/Wave.vue';

// 2. 建立圖片的 ref
const islandRef = ref(null);
let floatCtx; // 用來存放 GSAP context，方便銷毀

onMounted(() => {
  // 使用 gsap.context 是 Vue/React 中使用 GSAP 的最佳實踐 (方便清理)
  floatCtx = gsap.context(() => {
    
    // 動畫一：垂直漂浮 (模擬波浪起伏)
    gsap.to(islandRef.value, {
      y: 100,           // 向下移動 20px (幅度可依喜好調整)
      duration: 4,   // 持續時間 (配合你的波浪速度，越慢越悠閒)
      repeat: -1,      // 無限循環
      yoyo: true,      // 到達終點後反向播放 (達成上下來回效果)
      ease: "sine.inOut" // 關鍵！使用正弦曲線緩動，跟 Canvas 波浪最搭
    });

    // 動畫二 (選用)：輕微搖擺 (模擬船或島嶼在水上的重心不穩)
    gsap.to(islandRef.value, {
      rotation: 1.5,   // 輕微旋轉 1.5 度
      duration: 3.2,   // 時間跟上下漂浮錯開，感覺會更自然有機
      repeat: -1,
      yoyo: true,
      ease: "sine.inOut",
      transformOrigin: "50% 60%" // 設定旋轉軸心在圖片中下方，比較像浮在水上
    });

  });
});

onUnmounted(() => {
  floatCtx && floatCtx.revert(); // 組件卸載時清理動畫，避免效能洩漏
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
    <h2>Location</h2>
    <div class="map-container">
      <img src="/public/About/world-map.png" alt="#">
      <span class="pin-point">
        <font-awesome-icon :icon="['fas', 'location-dot']"/>
      </span>
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
  .map-container{
    width: 100%;
    margin: 0 auto;
    text-align: center;
    position: relative;
  }
  .map-container h2{
    margin-bottom: 110px;
  }
  .map-container img{
    display: block;
    width: 50%;
    height: auto;
    margin: 0 auto;
  }

.pin-point {
  position: absolute;
  top: 59%;    
  left: 56.5%;   
  transform: translate(-50%, -100%); 
  color: #FF9010;
  font-size: 40px;
  z-index: 20;
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
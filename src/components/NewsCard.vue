<!-- 20251202：首次與Michael完成組件，以茲紀念 -->
<script setup>

import { ref } from 'vue'

// 圖片陣列
const cards = ref([
  {
    id: 1,
    img: 'https://cyberisland.teldap.tw/S/JQjyuytdcMmyblySsltwmTSleH',
    event: 'Taiwan’s AI-driven',
    date: '2025.2.5',
  },
  {
    id: 2,
    img: 'https://images.pexels.com/photos/4095421/pexels-photo-4095421.jpeg',
    event: 'Taiwan’s AI-driven',
    date: '2025.6.20',
  },
  {
    id: 3,
    img: 'https://www.travel.taipei/image/90806/?r=1515479579958',
    event: 'Taiwan’s Flowers-driven',
    date: '2025.7.18',
  },
])
</script>

<template>
  <div v-for="card in cards" :key="card.id" class="newscard-container">
    <div class="newscard-container-info">
      <img class="newscard-container-pics" :src="card.img" alt="" />
      <div class="newscard-container-text">
        <h4>{{ card.event }}</h4>
        <h5>{{ card.date }}</h5>
      </div>
    </div>

    <div class="triangle-black-large">
      <div class="triangle-white-small"></div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.newscard-container {
  width: 380px;
  height: 380px;
  border-radius: 7px;
  position: relative;
  overflow: hidden;
}

.newscard-container-info {
  height: 100%;

}

.newscard-container-pics {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  z-index: 2;
  transition: filter 0.3s ease;

  clip-path: polygon(
    0 0,
    /* 左上 */ 100% 0,
    /* 右上 */ 100% calc(100% - 130px),
    /* 右側切點 */ calc(100% - 130px) 100%,
    /* 底部切點 */ 0 100% /* 左下 */
  );
    filter: brightness(0.5)
}

.newscard-container-text {
  height: 100%;
  color: $color-fsWhite;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 24px 40px;
  line-height: 1;
  text-shadow: 1px 1px 2px #000, 2px 2px 4px rgba(0, 0, 0, 0.3);
  position: relative;
  z-index: 5;
  transition: all 0.5s ease;
}

// 右下白色三角(common statement)
.triangle-white-small {
  width: 56px;
  height: 56px;
  background: linear-gradient(135deg, transparent 50%, #fff 50%);
  // 畫一個漸層背景，方向是 135 度，前 50% 是透明的，後 50% 是白色的，no模糊。 Hard Stop - 前一個顏色結束在 50%，後一個顏色馬上從 50% 開始，中間就沒有過渡空間，變成一條銳利的直線
  position: absolute;
  bottom: 16px;
  right: 16px;
  border-bottom-right-radius: 7px;
  transition: all 1s ease;
}

// 以下為hover效果
.newscard-container:hover .triangle-white-small {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, transparent 50%, #fff 50%);
  position: absolute;
  bottom: 0;
  right: 0;
  border-bottom-right-radius: 7px;

}


.newscard-container:hover .newscard-container-text{
    opacity: 0;
  }
.newscard-container:hover .newscard-container-pics {
  filter: brightness(1.1);
}

.newscard-container:hover {
  cursor: pointer;
}
</style>

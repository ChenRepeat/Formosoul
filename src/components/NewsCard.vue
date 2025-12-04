<!-- 20251202：首次與Michael完成組件，以茲紀念 -->

<script setup>
import { ref } from 'vue'

// 圖片陣列
const cards = ref([
  {
    id: 1,
    img: 'https://cyberisland.teldap.tw/S/JQjyuytdcMmyblySsltwmTSleH',
    event: 'Taiwan’s AI-driven',
    date: '2025.11.20',
  },
  {
    id: 2,
    img: 'https://images.pexels.com/photos/4095421/pexels-photo-4095421.jpeg',
    event: 'Taiwan’s AI-driven',
    date: '2025.11.20',
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
}

.newscard-container-text {
  height: 100%;
  color: $color-fsWhite;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 14px 30px 28px;
  text-shadow: 2px 2px 4px #000, 4px 4px 8px rgba(0, 0, 0, 0.3);
  position: relative;
  z-index: 5;
}

// 右下白色三角(common statement)
.triangle-white-small {
  width: 56px;
  height: 56px;
  background: linear-gradient(135deg, transparent 50%, #fff 50%);
  position: absolute;
  bottom: 16px;
  right: 16px;
  border-bottom-right-radius: 7px;
  transition: all 1s ease;
}

.newscard-container:hover .triangle-white-small {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, transparent 50%, #fff 50%);
  position: absolute;
  bottom: 0;
  right: 0;
  border-bottom-right-radius: 7px;
}

.newscard-container:hover .newscard-container-pics {
  filter: brightness(1.1);
}

.newscard-container:hover {
  cursor: pointer;
}
</style>

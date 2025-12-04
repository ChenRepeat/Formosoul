<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';

const images = [
  '../../public/testball/1.png',
  '../../public/testball/2.png',
  '../../public/testball/3.png',
  '../../public/testball/4.png',
  '../../public/testball/5.png',
];

const positions = ref(
  images.map(() => ({
    x: Math.random() * 80,
    y: Math.random() * 80,
    vx: (Math.random() - 0.5) * 2,
    vy: (Math.random() - 0.5) * 2,
    zindex: Math.floor(Math.random() * 5)
  }))
)

let interval
onMounted(() => {
  interval = setInterval(() => {
    positions.value = positions.value.map(pos => {
      let newX = pos.x + pos.vx
      let newY = pos.y + pos.vy
      let newVx = pos.vx
      let newVy = pos.vy

      if (newX < 0 || newX > 90) newVx = -newVx
      if (newY < 0 || newY > 90) newVy = -newVy

      return {
        x: Math.max(0, Math.min(90, newX)),
        y: Math.max(0, Math.min(90, newY)),
        vx: newVx,
        vy: newVy,
        zindex: pos.zindex
      }
    })
  }, 50)
})

onUnmounted(() => clearInterval(interval))
</script>

<template>

    <div class="backgrond">
      <div class="backgroundball">
      <img 
        v-for="(img, idx) in images" 
        :key="idx"
        :src="img" 
        :style="{ 
          left: positions[idx].x + '%', 
          top: positions[idx].y + '%',
          zIndex: positions[idx].zindex
        }"
      />
      </div>
    </div>

</template>

<style>
.backgrond {
  /* fix能覆蓋整個窗口 */
  position: fixed;  
  top: 0;
  left: 0;
  width: 100%;       
  height: 100vh;    
  overflow: hidden;  
  z-index: -2;
}


.backgroundball {
  position: absolute;
  top: -30px;
  left: -30px;
  right: -30px;
  bottom: -30px;
  background-color: #00529F;
  filter: blur(20px);
  z-index: -1; 
}

.backgroundball > img{
  position: absolute;
  width: 200px;
  height: 200px;
  object-fit: cover;
  transform: translate(-50%, -50%);
  transition: box-shadow 0.3s ease-in-out;
}
</style>
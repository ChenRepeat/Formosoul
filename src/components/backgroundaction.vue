<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import IconBall from './icons/SVG/IconBall.vue';




const colors = ['rgba(10, 61, 112, 0.5)', 'rgba(187, 71, 2, 0.3)', 'rgba(255, 144, 16, 0.3)'];

const ballCount = 3; 
const scales = [2.5, 3, 3.5, 4, 4.5, 5];
const new_scales = [];

function randomscales() {
  while (new_scales.length < ballCount) {
    const randomIndex = Math.floor(Math.random() * scales.length);
    const randomValue = scales[randomIndex];
    new_scales.push(randomValue);
  }
}

randomscales();

const positions = ref(
  // 建立一個長度為 ballCount 的陣列
  Array.from({ length: ballCount }, ( _, index) => ({
    x: Math.random() * 80,
    y: Math.random() * 80,
    vx: (Math.random() - 0.5) * 1,
    vy: (Math.random() - 0.5) * 1,
    scale: new_scales[index % new_scales.length],
    color: colors[index % colors.length]
  }))
);

let interval;

onMounted(() => {
  interval = setInterval(() => {
    positions.value = positions.value.map(pos => {
      let newX = pos.x + pos.vx;
      let newY = pos.y + pos.vy;
      let newVx = pos.vx;
      let newVy = pos.vy;

      if (newX < 0 || newX > 90) newVx = -newVx;
      if (newY < 0 || newY > 90) newVy = -newVy;

      return {
        ...pos,
        x: Math.max(0, Math.min(90, newX)),
        y: Math.max(0, Math.min(90, newY)),
        vx: newVx,
        vy: newVy,
      };
    });
  }, 50);
});

onUnmounted(() => clearInterval(interval));
</script>

<template>
  <div class="backgrond">
    <div class="backgroundball">
      <div 
        v-for="(pos, index) in positions" 
        :key="index"
        class="ballbox"
        :style="{
          left: pos.x + '%',
          top: pos.y + '%',
          transform: `scale(${pos.scale})`
        }"
      >
        <IconBall :size="100" :color="pos.color" />
      </div>
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
  background-color: black;
  z-index: -1; 
}

.backgroundball > img{
  position: absolute;
  width: 300px;
  height: 300px;
  object-fit: cover;
  transform: translate(-50%, -50%);
  transition: box-shadow 0.3s ease-in-out;
}

.ballbox {
  position: absolute;
  transition: all 0.05s linear;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
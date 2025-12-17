<script setup >
import { onMounted, onUnmounted, ref } from 'vue';

const images = [
  'Home/home-about-badge.png',
  'Home/home-annual-lantern.png',
  'Home/home-class-book.png',
  'Home/home-news-owl.png',
  'Home/home-policy-scroll.png',
  'Home/home-professor-people.png',
  'Home/home-shopping-money.png',
  'Home/home-survival-compass.png',
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
  }, 150)
})

onUnmounted(() => clearInterval(interval));
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
  background-color: black;
  filter: blur(20px);
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
</style>
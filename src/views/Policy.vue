<script setup lang="ts">
import MemberPagebutton from '@/components/Member/MemberPagebutton.vue';
import TheFooter from '@/components/TheFooter.vue';
import { onMounted, onUnmounted, ref } from 'vue';

const images = [
  './src/components/testball/1.png',
  './src/components/testball/2.png',
  './src/components/testball/3.png',
  './src/components/testball/4.png',
  './src/components/testball/5.png'
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
          zindex: positions[idx].zindex
        }"
      />
      </div>
    </div>
  <MemberPagebutton />
  <TheFooter></TheFooter>

</template>

<style>
@media (min-width: 1024px) {
  .about {
    /* min-height: 100vh; */
    display: flex;
    align-items: center;
  }
}


.backgrond {
  position: absolute;
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

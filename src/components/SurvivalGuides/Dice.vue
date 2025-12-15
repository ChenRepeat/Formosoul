<template>
  <div class="scene">
    <!-- :class="{ 'rotatetox': rotatex}" -->
    <div class="cube"  :style="{ transform: `rotateX(${Xdeg}deg) rotateY(${Ydeg}deg)` }">
        <div class="face face-top">
            <div class="pip pip-red center"></div>
        </div>
        <div class="face face-left">
            <div class="pip pip-black top-left"></div>
            <div class="pip pip-black bottom-right"></div>
        </div>
        <div class="face face-right">
            <div class="pip pip-red  top-left"></div>
            <div class="pip pip-red  top-right"></div>
            <div class="pip pip-red  bottom-left"></div>
            <div class="pip pip-red  bottom-right"></div>
        </div>
        <div class="face face-front">
        <!-- 3 -->
        <div class="pip pip-black top-left"></div>
        <div class="pip pip-black bottom-right"></div>
        <div class="pip pip-black  center"></div>

        </div>
        <div class="face face-back">
            <div class="pip pip-black  top-left"></div>
            <div class="pip pip-black  top-right"></div>
            <div class="pip pip-black center"></div>
            <div class="pip pip-black  bottom-left"></div>
            <div class="pip pip-black  bottom-right"></div>
        </div>
      <!-- 5 -->
        <div class="face face-bottom">
            <div class="pip pip-black  top-left"></div>
            <div class="pip pip-black  top-center"></div>
            <div class="pip pip-black  top-right"></div>
            <div class="pip pip-black  bottom-left"></div>
            <div class="pip pip-black  bottom-center"></div>
            <div class="pip pip-black  bottom-right"></div>
        </div>
      <!-- 6 -->
    </div>
  </div>
</template>

<script setup>
  const props = defineProps({
    Xdeg: {
      type: [String, Number],
      default: 0,
    },

    Ydeg: {
    type: [String, Number],
    default: 0
    }


  });
</script>

<style lang="scss" scoped>
/* 骰子大小 */
.scene {
  --size: 60px;

  width: var(--size);
  height: var(--size);
  margin: 0 auto;
  perspective: calc(var(--size) * 4); 
}

.cube{
  width: 100%;
  height: 100%;
  position: relative;
  transform-style: preserve-3d;
    /* 轉視角 */
  transform: rotateX(150deg) rotateY(225deg);
  background-color: #f5f5dc; 
  transform-origin: center center;
  scale: 0.99;
  transition: transform 2s cubic-bezier(0.1, 0.7, 0.1, 1);
//  &.rotatetox{
//       transform: rotateX(150deg);
//   } 
}

.face {
  position: absolute;
  width: 100%;
  height: 100%;
  background: #f5f5dc;
  border: 2px solid #f5f5dc; 
  border-radius: 10%;     
  box-sizing: border-box;
}


.face-top    { transform: rotateX(90deg) translateZ(calc(var(--size) / 2)); }
.face-left   { transform: rotateY(-90deg) translateZ(calc(var(--size) / 2)); }
.face-right  { transform: rotateY(90deg) translateZ(calc(var(--size) / 2)); }
.face-front  { transform: rotateY(0deg) translateZ(calc(var(--size) / 2)); }
.face-back   { transform: rotateY(180deg) translateZ(calc(var(--size) / 2)); }
.face-bottom { transform: rotateX(-90deg) translateZ(calc(var(--size) / 2)); }

.pip {
  position: absolute;
  width: 20%;  
  height: 20%;
  border-radius: 50%;
}

.pip-red { background-color: #d92b2b; }
.pip-black { background-color: #222; }


.center {
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
}
.top-left { top: 12.5%; left: 12.5%; }       
.top-right { top: 12.5%; right: 12.5%; }
.bottom-left { bottom: 12.5%; left: 12.5%; }
.bottom-right { bottom: 12.5%; right: 12.5%; }
.top-center{ bottom: 38.5%; right: 12.5%; }
.bottom-center{ top: 38.5%; left: 12.5%;}
</style>
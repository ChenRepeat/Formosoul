<script setup>
import { ref, onMounted } from 'vue';
const canvasRef = ref(null);

onMounted(()=>{
  const canvas = canvasRef.value
  let context = canvas.getContext('2d')      
  canvas.width = 520;
  canvas.height = 800;
  const pi = x => {return x * Math.PI/180};
  const angle = 10;
  const deg = 360 / angle;

  let timer = 0

  setInterval(drawing, 20)
  function drawing(){
    timer++
    const gradient = context.createLinearGradient(0, 0, 800, 0);
      
      const offset = 0.6;
      
      gradient.addColorStop(Math.max(0, offset - 0.5), '#041426');
      gradient.addColorStop(Math.max(0, offset), '#F0F7FF');
      gradient.addColorStop(Math.min(1, offset + 0.5), '#041426');
      gradient.addColorStop(Math.min(1, offset + 1), '#F0F7FF');
    let act = timer % 360
    context.clearRect(0, 0, canvas.width, canvas.height);
    context.beginPath();
    for(let i = 0; i < angle ;i++){
      context.arc(260,400, 150, pi((angle+i+1)*deg+act), pi((angle+i+1)*deg+act));
      context.arc(260,400, 200, pi((angle+i+1.5)*deg-act), pi((angle+i+1.5)*deg+act));
      context.arc(260,400, 150, pi((angle+i+2)*deg-act), pi((angle+i+2)*deg+act));
      context.arc(260,400, 200, pi((angle+i+2.5)*deg+act), pi((angle+i+2.5)*deg-act));

      context.closePath();
      context.strokeStyle = gradient;
      context.lineCap = 'round';
      context.lineJoin = 'round';      
      context.stroke();
    }
     
}
})
</script>
<template>
  <div class="canvas-container">
    <canvas ref="canvasRef" class="canvas"></canvas>
  </div>
</template>
<style scoped lang="scss">
  .canvas-container{
    width: 100%;
    height: 100%;
  }
  .canvas{
    margin: auto;
  }
</style>
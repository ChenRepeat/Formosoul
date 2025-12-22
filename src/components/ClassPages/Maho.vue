<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const canvasRef = ref(null);
const containerRef = ref(null);
let animationId = null;

onMounted(() => {
  const canvas = canvasRef.value;
  const container = containerRef.value;
  const context = canvas.getContext('2d');
  const updateSize = () => {
    canvas.width = container.clientWidth;
    canvas.height = container.clientHeight;
  };

  updateSize();
  window.addEventListener('resize', updateSize);

  const pi = x => { return x * Math.PI / 180 };
  const angle = 10;
  const deg = 360 / angle;
  let timer = 0;

  function drawing() {
    timer++;
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    
    const gradient = context.createLinearGradient(0, 0, 800, 0);
    const offset = 0.6;
    gradient.addColorStop(Math.max(0, offset - 0.5), '#041426');
    gradient.addColorStop(Math.max(0, offset), '#F0F7FF');
    gradient.addColorStop(Math.min(1, offset + 0.5), '#041426');
    gradient.addColorStop(Math.min(1, offset + 1), '#F0F7FF');

    let act = timer % 360;
    context.clearRect(0, 0, canvas.width, canvas.height);
    context.beginPath();

    for (let i = 0; i < angle; i++) {
      context.arc(centerX, centerY, 150, pi((angle + i + 1) * deg + act), pi((angle + i + 1) * deg + act));
      context.arc(centerX, centerY, 200, pi((angle + i + 1.5) * deg - act), pi((angle + i + 1.5) * deg + act));
      context.arc(centerX, centerY, 150, pi((angle + i + 2) * deg - act), pi((angle + i + 2) * deg + act));
      context.arc(centerX, centerY, 200, pi((angle + i + 2.5) * deg + act), pi((angle + i + 2.5) * deg - act));

      context.closePath();
      context.strokeStyle = gradient;
      context.lineCap = 'round';
      context.lineJoin = 'round';
      context.stroke();
    }
    
    animationId = requestAnimationFrame(drawing);
  }

  drawing();
});

onUnmounted(() => {
  window.removeEventListener('resize', () => {});
  cancelAnimationFrame(animationId);
});
</script>

<template>
  <div ref="containerRef" class="canvas-container">
    <canvas ref="canvasRef" class="canvas"></canvas>
  </div>
</template>

<style scoped lang="scss">
.canvas-container {
  width: 100%;
  height: 100%;
  min-height: 100%; 
  display: flex;
}

</style>
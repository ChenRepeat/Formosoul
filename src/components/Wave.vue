<template>
  <div class="canvas-container">
    <canvas ref="canvasRef" id="canvas"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const canvasRef = ref(null);

// baseY: 起始座標    speed: 速度   curveIntensity: 波動幅度    offset: 波動的相位偏移

// colorStart: 漸層起始   colorEnd: 漸層結束    gradientOffset: 漸層偏移    lineWidth: 線的粗細

// 新增 line 格式: [baseY, speed, curveIntensity, offset, colorStart, colorEnd, gradientOffset, lineWidth]
const line = [
  [260, 0.8, 120, 0, '#FF6B6B', '#4ECDC4', 0, 2],
  [90, 0.6, 170, 0.8, '#A8E6CF', '#FFD93D', 0.2, 1],
  [130, 0.5, 18, 1.6, '#6C5CE7', '#A29BFE', 0.4, 3],
  [170, 0.7, 22, 2.4, '#FD79A8', '#FDCB6E', 0.6, 5],
  [210, 0.55, 16, 3.2, '#00B894', '#00CEC9', 0.8, 0.25],
  [250, 0.85, 19, 4.0, '#E17055', '#FAB1A0', 1.0, 0.75]
];


const gradientSpeed = 0.0005;

onMounted(() => {
  const canvas = canvasRef.value;
  const context = canvas.getContext('2d');
  
  function resizeCanvas() {
    canvas.width = window.innerWidth;
    //  高度一定要小於起始座標 + 波動幅度，除非想要不完整的波段
    canvas.height = 600;
  }
  
  resizeCanvas();
  window.addEventListener('resize', resizeCanvas);

  class FlowingLine {
    constructor(baseY, speed, curveIntensity, offset, colorStart, colorEnd, gradientOffset, lineWidth) {
      this.baseY = baseY;
      this.speed = speed;
      this.curveIntensity = curveIntensity;
      this.offset = offset;
      this.progress = 0;
      this.colorStart = colorStart;
      this.colorEnd = colorEnd;
      this.gradientOffset = gradientOffset;
      this.gradientProgress = 0;
      this.lineWidth = lineWidth;
    }

    update(deltaTime) {
      this.progress += this.speed * deltaTime * 0.001;
      if (this.progress >= Math.PI * 2) {
        this.progress = 0;
      }
      this.gradientProgress += gradientSpeed * deltaTime;
      if (this.gradientProgress >= 1) {
        this.gradientProgress = 0;
      }
    }

    draw() {
      const w = canvas.width;
      const segments = 100;
      const segmentWidth = w / segments;

      const gradient = context.createLinearGradient(0, 0, w, 0);
      
      const offset = this.gradientProgress;
      
      gradient.addColorStop(Math.max(0, offset - 0.5), this.colorEnd);
      gradient.addColorStop(Math.max(0, offset), this.colorStart);
      gradient.addColorStop(Math.min(1, offset + 0.5), this.colorEnd);
      gradient.addColorStop(Math.min(1, offset + 1), this.colorStart);

      context.beginPath();
      
      const startY = this.baseY + Math.sin(this.progress + this.offset) * this.curveIntensity;
      context.moveTo(0, startY);

      for (let i = 0; i <= segments; i++) {
        const x = (i / segments) * w;
        const t = (x / w) * Math.PI * 3 + this.progress + this.offset;
        const y = this.baseY + Math.sin(t) * this.curveIntensity;
        
        context.lineTo(x, y);
      }
      
      context.strokeStyle = gradient;
      context.lineWidth = this.lineWidth;
      context.lineCap = 'round';
      context.lineJoin = 'round';
      context.stroke();
    }
  }

  const lines = [];
  for (let i = 0; i < line.length; i++) {
    lines.push(new FlowingLine(
      line[i][0],line[i][1],line[i][2],line[i][3],line[i][4],line[i][5],line[i][6],line[i][7],
    ));
  }

  let lastTime = 0;
  let animationId;

  function animate(currentTime) {
    const deltaTime = currentTime - lastTime;
    lastTime = currentTime;
    context.clearRect(0, 0, canvas.width, canvas.height);
    
    lines.forEach(line => {
      line.update(deltaTime);
      line.draw();
    });

    animationId = requestAnimationFrame(animate);
  }

  animate(0);

  onUnmounted(() => {
    window.removeEventListener('resize', resizeCanvas);
    if (animationId) {
      cancelAnimationFrame(animationId);
    }
  });
});
</script>

<style scoped>
.canvas-container {
  width: 100%;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: 0;left: 0;
  z-index: 0;
}
</style>
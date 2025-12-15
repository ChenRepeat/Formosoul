<script setup>
import { ref, onMounted } from 'vue';
import BasicButton from '../BasicButton.vue';

const canvasRef = ref(null);
const context = ref(null);
const isDrawing = ref(false);
const brushColor = ref('#FF9010');
const brushSize = ref(5);

onMounted(() => {
  const canvas = canvasRef.value;
  context.value = canvas.getContext('2d');
  canvas.width = 150;
  canvas.height = 540;
  context.value.lineCap = 'round';
  context.value.lineJoin = 'round';
});

const startDrawing = (e) => {
  isDrawing.value = true;
  const { offsetX, offsetY } = e;
  context.value.beginPath();
  context.value.moveTo(offsetX, offsetY);
};

const draw = (e) => {
  if (!isDrawing.value) return;

  const { offsetX, offsetY } = e;
  context.value.lineWidth = brushSize.value;
  context.value.strokeStyle = brushColor.value;
  context.value.lineTo(offsetX, offsetY);
  context.value.stroke();
};

const stopDrawing = () => {
  isDrawing.value = false;
  context.value.closePath();
};

const clearCanvas = () => {
  context.value.clearRect(0, 0, canvasRef.value.width, canvasRef.value.height);
};
</script>

<template>
  <div class="canvas-container dp-flex-col"
      @mousedown.stop
      @touchstart.stop>
    <canvas
      ref="canvasRef"
      @mousedown="startDrawing"
      @mousemove="draw"
      @mouseup="stopDrawing"
      @mouseleave="stopDrawing"
    ></canvas>
    <div class="toolbar dp-flex-col">
      <div class="dp-flex tool-case">
        <!-- <p>Color</p> -->
        <input type="color" v-model="brushColor" />
        <!-- <p>Bursh Size</p> -->
        <input type="range" min="1" max="20" v-model="brushSize" />
      </div>
      <div class="dp-flex btn-case">
        <BasicButton  @click="clearCanvas">Clear</BasicButton>
        <BasicButton  @click="">Save</BasicButton>
      </div>
    </div>
  </div>
</template>


<style lang="scss" scoped>
.canvas-container {
  align-items: center;
  gap: 10px;
}
canvas {
  cursor: crosshair;
  border: 2px double $color-fsTitle;
}
.toolbar {
  gap: 10px;
  padding: 10px;
}
.tool-case{
  gap: 16px;
  justify-content: center;
}
.btn-case{
  gap: 16px;
  justify-content: center;
}
</style>
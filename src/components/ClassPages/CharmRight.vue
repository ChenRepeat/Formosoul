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
  context.value.fillStyle = "#FFFCC2";
  context.value.fillRect(0,0,canvasRef.value.width, canvasRef.value.height);
  
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
  const dataUrl = canvasRef.value.toDataURL();
  emit('save-image', dataUrl);
};
const emit = defineEmits(['save-image']);
const stopDrawing = () => {
  isDrawing.value = false;
  context.value.closePath();

};

const clearCanvas = () => {
  context.value.clearRect(0, 0, canvasRef.value.width, canvasRef.value.height);
  context.value.fillStyle = "#FFFCC2";
  context.value.fillRect(0,0,canvasRef.value.width, canvasRef.value.height);
  emit('save-image', 'Classes/charms/charm13.png');
};


const save = () => {
  const finalData = canvasRef.value.toDataURL();
};
// 可以用 context.drawImage(Image, dX, dY, dWidth, dHeight); 把base64寫回canvas

</script>

<template>
  <div class="canvas-container dp-flex-col"
      @mousedown.stop
      @touchstart.stop>
      <h5>{{$t('classes.drawText1')}}</h5>
    <canvas
      ref="canvasRef"
      @mousedown="startDrawing"
      @mousemove="draw"
      @mouseup="stopDrawing"
      @mouseleave="stopDrawing"
    ></canvas>
    <div class="toolbar dp-flex-col">
      <div class="dp-flex tool-case">
        <div class="dp-flex">
          <p>Width</p><input type="range" min="1" max="20" v-model="brushSize" />
        </div>
        <div class="dp-flex">
          <p>Color</p><input type="color" v-model="brushColor" />
        </div>
      </div>
      <div class="dp-flex btn-case">
        <BasicButton
          @click="clearCanvas"
          class="btn-black"
        >Clear</BasicButton>
        <BasicButton  
          @click="save"
          class="btn-black"
        >Save</BasicButton>
      </div>
    </div>
  </div>
</template>


<style lang="scss" scoped>
.canvas-container {
  align-items: center;
  gap: 10px;
  width: 80%;
}
canvas {
  cursor: crosshair;
  outline: 5px solid $color-fsTitle;
  outline-offset: -10px;
}
.toolbar {
  gap: 10px;
  padding: 10px;
  width: 100%;
}
.tool-case{
  width: 100%;
  gap: 16px;
  justify-content: center;
  div{
    gap: 8px;
    width: 45%;
  }
  p{
    display: inline-block;
  }
  input{
    width: 80%;
  }
}
.btn-case{
  gap: 16px;
  justify-content: center;
}
</style>
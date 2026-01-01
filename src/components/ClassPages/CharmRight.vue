<script setup>
import { ref, onMounted } from 'vue';
import { useclassesStore } from '@/stores/classes';
import { useMemberStore } from '@/stores/member';
import BasicButton from '../BasicButton.vue';

const classStore = useclassesStore();
const memberStore = useMemberStore();

const canvasRef = ref(null);
const context = ref(null);
const isDrawing = ref(false);
const brushColor = ref('#FF9010');
const brushSize = ref(5);

const imgObj = new Image();
const initialImgSrc = ref('');

const initCanvas = () => {
  const canvas = canvasRef.value;
  if (!canvas) return;
  context.value = canvas.getContext('2d');
  canvas.width = 150;
  canvas.height = 540;
  context.value.lineCap = 'round';
  context.value.lineJoin = 'round';
  context.value.fillStyle = "#FFFCC2";
  const savedImg = memberStore.memberData.charmImg;
  initialImgSrc.value = savedImg; 
  if (savedImg) {
    imgObj.onload = () => {
      context.value.fillRect(0, 0, canvas.width, canvas.height);
      context.value.drawImage(imgObj, 0, 0, canvas.width, canvas.height);
    };

    imgObj.onerror = (err) => {
      console.error("圖片載入失敗", err);
      context.value.fillRect(0, 0, canvas.width, canvas.height);
    };
    imgObj.src = savedImg;
  }
};


const getPos = (e) => {
  const canvas = canvasRef.value;
  if (e.touches && e.touches.length > 0) {
    const rect = canvas.getBoundingClientRect();
    return {
      x: e.touches[0].clientX - rect.left,
      y: e.touches[0].clientY - rect.top
    };
  }
  return { x: e.offsetX, y: e.offsetY };
};

const startDrawing = (e) => {
  isDrawing.value = true;
  const { x, y } = getPos(e);
  context.value.beginPath();
  context.value.moveTo(x, y);
};

const draw = (e) => {
  if (!isDrawing.value) return;

  const { x, y } = getPos(e);
  context.value.lineWidth = brushSize.value;
  context.value.strokeStyle = brushColor.value;
  context.value.lineTo(x, y);
  context.value.stroke();
  const dataUrl = canvasRef.value.toDataURL();
  classStore.imgShare(dataUrl);
};

const stopDrawing = () => {
  isDrawing.value = false;
  context.value.closePath();
};

const clearCanvas = () => {
  context.value.clearRect(0, 0, canvasRef.value.width, canvasRef.value.height);
  context.value.fillStyle = "#FFFCC2";
  context.value.fillRect(0, 0, canvasRef.value.width, canvasRef.value.height);
  classStore.imgShare('Classes/charms/charm13.png');
};

const resetCanvas = () => {
  context.value.clearRect(0, 0, canvasRef.value.width, canvasRef.value.height);
  context.value.fillStyle = "#FFFCC2";
  context.value.fillRect(0, 0, canvasRef.value.width, canvasRef.value.height);

  if (initialImgSrc.value) {
    context.value.drawImage(imgObj, 0, 0, canvasRef.value.width, canvasRef.value.height);
    classStore.imgShare(initialImgSrc.value);
  } else {
    classStore.imgShare('');
  }
};

const save = () => {
  const finalData = canvasRef.value.toDataURL();
  memberStore.saveGameResult('charm', { img: finalData });
  memberStore.gameData.charm.img = finalData;
  memberStore.memberData.charmImg = finalData;
  initialImgSrc.value = finalData;
  imgObj.src = finalData; 
};

onMounted(() => {
  initCanvas();
});

</script>

<template>
  <div class="canvas-container dp-flex-col"
      @mousedown.stop
      @touchstart.stop>
      <div class="title-case dp-flex">
        <h5>{{$t('classes.drawText1')}}</h5>
        <div class="game-info"><p>?</p></div>
      </div>
    <canvas
      ref="canvasRef"
      @mousedown="startDrawing"
      @mousemove="draw"
      @mouseup="stopDrawing"
      @mouseleave="stopDrawing"
      @touchstart="startDrawing"
      @touchmove="draw"
      @touchend="stopDrawing"
    ></canvas>
    <div class="toolbar dp-flex-col">
      <div class="dp-flex tool-case">
        <div class="dp-flex">
          <p>{{$t('classes.charmInput1')}}</p><input type="range" min="1" max="20" v-model="brushSize" />
        </div>
        <div class="dp-flex">
          <p>{{$t('classes.charmInput2')}}</p><input type="color" v-model="brushColor" />
        </div>
      </div>
      <div class="dp-flex btn-case">
        <BasicButton
          @click="clearCanvas"
          class="btn-black i18n-anim"
        >{{$t('classes.charmBtn1')}}</BasicButton>
        <BasicButton
          @click="resetCanvas"
          class="btn-black i18n-anim"
        >{{$t('classes.charmBtn2')}}</BasicButton>
        <BasicButton  
          @click="save"
          class="btn-black i18n-anim"
        >{{$t('classes.charmBtn3')}}</BasicButton>
      </div>
    </div>
  </div>
</template>


<style lang="scss" scoped>
.canvas-container {
  align-items: center;
  gap: 10px;
  width: 80%;
  position: relative;
  .title-case{
    width: 100%;
    justify-content: center;
    align-items: end;
    gap: 12px;
    p{
      text-align: center;
      border: 3px solid $color-fsRed;
      width: 18px;
      height: 18px;
      line-height: 18px;
      border-radius: 50%;
    }
  }
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
  gap: 8px;
  justify-content: center;
  div{
    gap: 8px;
    width: 45%;
    justify-content: center;
  }
  p{
    display: inline-block;
  }
  input{
    width: 40%;
  }
}
.btn-case{
  gap: 16px;
  justify-content: center;
}
</style>
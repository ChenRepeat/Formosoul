<script setup>
import Bowl from './bowl.vue';
import Dice from './Dice.vue';
import Hand from './Hand.vue';
import HandBack from './HandBack.vue';
import { ref, computed, onMounted, onUnmounted, defineEmits } from "vue";

// ================ 鍵盤esc關閉 ================ 
const emit = defineEmits(['close-game'])
const handleKey = (e) => { if (e.code === 'Escape') emit('close-game');
};

// ================ 滑鼠 area ================ 
// ================ 滑鼠跟隨 ================ 
const mouseX = ref(0);
const mouseY = ref(0);
// ================ 滑鼠跟隨 更新座標's function  ================ 
const updateMouse = (e) => {
  mouseX.value = e.clientX;
  mouseY.value = e.clientY;
}




// ================ 抓取與丟骰子 area ================ 
const isGrabbing = ref(false); // 是否正在按住
// 骰子狀態
const currentX = ref(0)
const currentY = ref(0)
const diceDropY = ref(-300); // 骰子垂直高度 (負數在上面)
const diceOpacity = ref(0); // 透明度

// 掉落動畫 wrapper 
const dropWrapperStyle = computed (()=> ({
  // 位移
  transform: `translate(-50%, calc(-50% + ${diceDropY.value}px))`,
  opacity: diceOpacity.value,
  transition: 'transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s'
}));

// 按下 握拳
const handleMouseDown = () => {
  isGrabbing.value = true;
  diceOpacity.value = 0;
  diceDropY.value = -300;
}

// 放開 丟骰子
const handleMouseUp = () => {
  isGrabbing.value = false;
  throwDice();
}

// 丟骰子
const throwDice = () => {
  // 設定隨機旋轉角度
  // 加 720 是為了確保它至少轉兩圈
  currentX.value = Math.floor(Math.random()*360) +720;
  currentY.value = Math.floor(Math.random()*360) +720;

  // 讓外層的 Wrapper 掉下來
  diceDropY.value = 0; // 落到碗的中間
  diceOpacity.value = 1;
}



// ================ 生命週期 ================ 
onMounted(()=>{
  window.addEventListener('mousemove', updateMouse)
  window.addEventListener('mousedown', handleMouseDown)
  window.addEventListener('mouseup', handleMouseUp)
  window.addEventListener('keydown', handleKey);

})

onUnmounted (() => {
  window.addEventListener('mousemove', updateMouse)
  window.addEventListener('mousedown', handleMouseDown)
  window.addEventListener('mouseup', handleMouseUp)
  window.addEventListener('keydown', handleKey);
})

</script>

<template>
    <div class="drop-wrapper" :style="dropWrapperStyle">
      <Dice :Xdeg="currentX" :Ydeg="currentY"></Dice>
    </div>

    <div class="playerbox">
        <h3>Player</h3>
        <div class="scroebox">
            <h3>Score：</h3>
            <h3>3</h3>
        </div>
    </div>
    <div class="bowlbox">
        <Bowl></Bowl>
    </div>
    <div class="rollHand" 
    :style="{
      top: mouseY + 'px',
      left: mouseX + 'px',
    }"
    >
      <HandBack v-if="isGrabbing"></HandBack>
      <hand v-else></hand>
    </div>

    <div class="bankerbox">
        <h3>banker</h3>
        <div class="scroebox">
            <h3>Score：</h3>
            <h3>3</h3>
        </div>
    </div>
</template>


<style scoped>

    .drop-wrapper {
      position: absolute;
      left: 50%;
      top: 50%;
      z-index: 50;
      pointer-events: none;
   }

    .drop-wrapper :deep(.cube) {
        transition: transform 1s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }

    .bowlbox{
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%); 
    }

    .bankerbox,
    .playerbox{
        width: 200px;
        height: 200px;
    }

    .bankerbox{
        position: absolute;
        margin-left: auto;
        top: 50%;
        right: 0;
        transform: translate(0, -50%); 
    }
    .playerbox{
        position: absolute;
        margin-left: auto;
        top: 50%;
        left: 0;
        transform: translate(0, -50%); 

    }

    .scroebox > h3:last-child{
        text-align: center;
    }
    .scroebox{
        background-color: #EEEEEE;
    }

  .rollHand {
        position: fixed;
        z-index: 999;
        pointer-events: none;
        transform: translate(-50%, -50%);
        width: 280px;
  }
  


</style>
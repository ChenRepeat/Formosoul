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
});

// 骰子旋轉並算出分數
const dice_count = 3;
const dicelist = ref(Array.from({length: dice_count}, () => ({
    x: 0,
    y: 0,
    score: '_'
})));

const isRolling = ref(false);

const totalscore = computed(() => {
    let total = 0;
    
    for(let i = 0; i < dicelist.value.length; i++){
        const currentscore = dicelist.value[i].score;

        if(typeof currentscore !== 'number'){
            return '?';
        }

        total += currentscore;

    }

    return total;
})
// const currentX = ref(0);
// const currentY = ref(0);
// const score = ref('_');

// 正則化角度
function normalizeAngle(angle){
    const mod = Math.round(((angle % 360)+ 360) % 360);
    if(mod >= 315 || mod < 45) return 0;
    if(mod >= 45 && mod <135) return 90;
    if(mod >= 135 && mod < 225) return 180;
    return 270;
};



function getSingleDiceScore(rawX, rawY){
    const x = normalizeAngle(rawX);
    const y = normalizeAngle(rawY);

    if( x === 0){
        if(y === 0) return 3;
        if(y === 90) return 2;
        if(y === 180) return 5;
        if(y === 270) return 4;
    }

    if (x === 180){
        if(y === 0) return 6;
        if(y === 90) return 4;
        if(y === 180) return 3;
        if(y === 270) return 2;
    }

    if(x === 90) return 6;

    if(x === 270) return 1;

    return '?';
};

function randomRoll(){
    if(isRolling.value) return;
    isRolling.value = true;
    dicelist.value.forEach(die => die.score = '_');
    dicelist.value.forEach((dice) => {
        // 360 度 轉 5 圈
        const circle = Math.floor(Math.random() * 10) + 2;
        const baseSpins = 360 * circle;
        // 直接確保落地會是90的倍數
        const randomFaceX = Math.floor(Math.random() * 4) * 90;
        const randomFaceY = Math.floor(Math.random() * 4) * 90;
        
        dice.x += (baseSpins + randomFaceX);
        dice.y += (baseSpins + randomFaceY);
    });
    
    setTimeout(() => {
        dicelist.value.forEach((dice) => {
            dice.score = getSingleDiceScore(dice.x, dice.y);
        });

        console.log(dicelist.value.map(d => d.score));
        console.log("總分：", totalscore.value);

        isRolling.value = false;
    }, 3000);
};



</script>

<template>
    <div class="drop-wrapper" :style="dropWrapperStyle">
      <Dice :Xdeg="currentX" :Ydeg="currentY"></Dice>
    </div>

    <div class="playerbox">
        <h3>Player</h3>
        <div class="scorebox">
            <h3>Score：</h3>
            <h3>{{ totalscore }}</h3>
        </div>
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


    <div class="bowlbox" >
        <Bowl>
        </Bowl>
    </div>
    <div class="dice-container" @click="randomRoll">
        <div class="dicebox" v-for="(dice, index) in dicelist" :key="index">
            <Dice :Xdeg="dice.x" :Ydeg="dice.y"></Dice>
        </div>
    </div>
    <div class="bankerbox">
        <h3>banker</h3>
        <div class="scorebox">
            <h3>Score：</h3>
            <h3>3</h3>
        </div>
    </div>
</template>


<style lang="scss" scoped>

    .drop-wrapper {
      position: absolute;
      left: 50%;
      top: 50%;
      z-index: 50;
      pointer-events: none;
   }

    .drop-wrapper:deep(.cube) {
        transition: transform 1s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }



    .bowlbox{
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);

        
    }
    .dice-container {
        position: relative;
        width: 500px;
        height: 500px;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);    
    }

    .dicebox {
        position: absolute;
        width: 80px;
        height: 80px;
        z-index: 550;
        top: 0; 
        left: 0;
        // margin: 0 auto;
    }

    /* 個別調整位置 */
    .dicebox:nth-child(1) {
        left: 80%;
        top: 40%;
        // transform: translate(-50%, -50%);
    }


    .dicebox:nth-child(2) {
        left: 10%;
        top: 20%;
    }

    .dicebox:nth-child(3) {
        left: 10%;
        top: 75%;
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

    .scorebox > h3:last-child{
        text-align: center;
    }
    .scorebox{
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
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
const mouseX = ref(0);
const mouseY = ref(0);
const updateMouse = (e) => {
  mouseX.value = e.clientX;
  mouseY.value = e.clientY;
}

const diceOrigins = [
    {left: 0.8, top: 0.4},
    {left: 0.1, top: 0.2},
    {left: 0.1, top: 0.75},

]

// ================ 抓取與丟骰子 area ================ 
const isGrabbing = ref(false); // 是否正在按住
// const diceDropY = ref(0); 
// const diceDropX = ref(0);
const diceOpacity = ref(1); // 透明度

// 骰子掉落與出現的樣式 (統一管理三顆骰子的掉落動畫)
// const diceAnimStyle = computed (()=> ({
//   transform: `translate(${diceDropX.value}px, ${diceDropY.value}px)`,
//   opacity: diceOpacity.value,
//   transition: 'transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s'
// }));

// 按下 握拳 (準備丟)
const handleMouseDown = () => {
    // if (isRolling.value) return;
    isGrabbing.value = true;
    // 1. 骰子隱藏並回到空中
    diceOpacity.value = 0;
    const containerwidth = 500;
    const containerheight = 500;

    const containerleft = (window.innerWidth - containerwidth) / 2;
    const containertop = (window.innerHeight - containerheight) / 2;
    dicelist.value.forEach((dice, index) => {
        const originX = containerleft + (containerwidth * diceOrigins[index].left);
        const originY = containertop + (containerheight * diceOrigins[index].top);

        // -40 因為骰子大小約80 要讓骰子回歸中心
        dice.translateX = mouseX.value - originX - 40; 
        dice.translateY = mouseY.value - originY - 40;
    });

}

// 放開 丟骰子
const handleMouseUp = () => {
  // 防止重複觸發
  if(!isGrabbing.value) return; 
  isGrabbing.value = false;
  throwDice();
}

// 丟骰子動作
const throwDice = () => {
  // 1. 讓骰子掉下來 (視覺)
  diceOpacity.value = 1;
  dicelist.value.forEach(dice => {
      dice.translateX = 0;
      dice.translateY = 0;
  });
  // 2. 讓骰子開始旋轉 (數據與內部動畫)
  randomRoll();
}

// ================ 骰子邏輯與計算 ================ 
const dice_count = 3;
const dicelist = ref(Array.from({length: dice_count}, () => ({
    x: 0,
    y: 0,
    score: '_',
    translateX: 0,
    translateY: 0,
})));

const isRolling = ref(false);

const totalscore = computed(() => {
    let total = 0;
    for(let i = 0; i < dicelist.value.length; i++){
        const currentscore = dicelist.value[i].score;
        if(typeof currentscore !== 'number') return '?';
        total += currentscore;
    }
    return total;
})

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
    
    // 這裡原本有 setTimeout 3000ms 才會顯示分數
    // 我們讓他在旋轉結束後才結算
    dicelist.value.forEach((dice) => {
        // 360 度 轉 5 圈
        const circle = Math.floor(Math.random() * 10) + 2;
        const baseSpins = 360 * circle;
        const randomFaceX = Math.floor(Math.random() * 4) * 90;
        const randomFaceY = Math.floor(Math.random() * 4) * 90;
        
        dice.x += (baseSpins + randomFaceX);
        dice.y += (baseSpins + randomFaceY);
    });
    
    console.log(mouseX.value);

    setTimeout(() => {
        dicelist.value.forEach((dice) => {
            dice.score = getSingleDiceScore(dice.x, dice.y);
        });
        isRolling.value = false;
    }, 3000); 
};


// ================ 生命週期 ================ 
onMounted(()=>{
  window.addEventListener('mousemove', updateMouse)
  window.addEventListener('mousedown', handleMouseDown)
  window.addEventListener('mouseup', handleMouseUp)
  window.addEventListener('keydown', handleKey);
})

onUnmounted (() => {
  window.removeEventListener('mousemove', updateMouse)
  window.removeEventListener('mousedown', handleMouseDown)
  window.removeEventListener('mouseup', handleMouseUp)
  window.removeEventListener('keydown', handleKey);
});

</script>

<template>
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
        <Bowl></Bowl>
    </div>

    <div class="dice-container">
        <div class="dicebox" 
             v-for="(dice, index) in dicelist" 
             :key="index"
             :style="{
                transform: `translate(${dice.translateX}px, ${dice.translateY}px)`,
                opacity: diceOpacity,
                transition: 'transform 0.5s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.3s'
             }"
        >
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

    /* 移除了 .drop-wrapper 樣式 */

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
        /* 讓容器本身不擋住滑鼠事件，確保可以點擊背景或其他操作 */
        pointer-events: none; 
    }

    .dicebox {
        position: absolute;
        width: 80px;
        height: 80px;
        z-index: 550;
        
        /* 這裡非常重要！
          因為我們用了 diceAnimStyle (裡面有 transform)，
          如果這裡也寫 transform: translate(...) 會產生衝突。
          所以原本 CSS 的 transform 被拿掉了，位置完全依賴 top/left。
        */
        
        /* 讓骰子本身可以響應 3D 變換 */
        transform-style: preserve-3d;
    }


    .dicebox:nth-child(1) {
        left: 80%;
        top: 40%;
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
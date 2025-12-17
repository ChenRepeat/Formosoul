<script setup>
    
    import Bowl from './bowl.vue';
    import Dice from './Dice.vue';
    import Hand from './Hand.vue';
    import HandBack from './HandBack.vue';
    import { ref, computed, onMounted, onUnmounted, defineEmits } from "vue";
    

// ================ 鍵盤esc關閉 ================ 
const emit = defineEmits(['close-game']);
const handleKey = (e) => { if (e.code === 'Escape') emit('close-game');
};

// ================ 新增：遊戲管理狀態 ================
// IDLE(閒置/抓骰子) -> ROLLING(玩家轉) -> CHOOSING(選大小) -> BANKER_ROLLING(莊家轉) -> RESULT(結算)
const gameState = ref('IDLE');
const playerChoice = ref(''); // 玩家選 'BIG' 或是 'SAMLL'
const finalMessage = ref(''); // 顯示贏或輸
 
// ================ 新增： 莊家 ================
const bankerDicelist = ref(Array.from({length: dice_count}, () => ({
    x: 0,
    y: 0,
    score: '_',
    translateX: 0,
    translateY: 0,
})));

// 莊家的分數計算
const bankerTotalScore = computed (()=> {

    if (gameState.value === 'IDLE' || gameState.value === 'ROLLING' || gameState.value === 'CHOOSING') {
        return '-';
    }

    let total = 0;
    for(let i = 0; i < bankerDicelist.value.length; i++){
        const currentscore = bankerDicelist.value[i].score;
        if(typeof currentscore !== 'number') return '-';
        total += currentscore;
    }
    return total;
})


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


// 按下 握拳 (準備丟)
const handleMouseDown = () => {
    // if (isRolling.value) return;
    // 只有在閒置狀態下才能抓骰子
    if(gameState.value !== 'IDLE') return;

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
        if(typeof currentscore !== 'number') return '-';
        total += currentscore;
    }
    return total;
})

// 正則化角度
function normalizeAngle(angle){

    let normalized = angle % 360;
    if (normalized < 0) normalized += 360;
    
    if(normalized >= 315 || normalized < 45) return 0;
    if(normalized >= 45 && normalized < 135) return 90;
    if(normalized >= 135 && normalized < 225) return 180;
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

    return '_';
};

function randomRoll(){
    if(isRolling.value) return;
    isRolling.value = true;

    // 新增： 設定狀態為： 玩家轉動中
    gameState.value = 'ROLLING';

    dicelist.value.forEach(die => die.score = '_');
    
    // 這裡原本有 setTimeout 3000ms 才會顯示分數
    // 我們讓他在旋轉結束後才結算
    dicelist.value.forEach((dice) => {
        // 360 度 轉 5 圈
        const circle = Math.floor(Math.random() * 10) + 2;
        const baseSpins = 360 * circle;
        const randomFaceX = Math.floor(Math.random() * 4) * 90;
        const randomFaceY = Math.floor(Math.random() * 4) * 90;
        
        dice.x = (baseSpins + randomFaceX);
        dice.y = (baseSpins + randomFaceY);
    });
    
    console.log(mouseX.value);

    setTimeout(() => {
        dicelist.value.forEach((dice) => {
            dice.score = getSingleDiceScore(dice.x, dice.y);
        });
        isRolling.value = false;

        // 新增： 轉完後，不要結束，要進入「選擇模式」
        gameState.value = 'CHOOSING';
    }, 3000); 
};

// =============== 新增：Banker 回合 ===============
// 玩家選擇：比大或比小
const chooseSide = (choice) => {
  playerChoice.value = choice;
  startBankerTurn();
}

// 莊家丟骰子 (自動)
function startBankerTurn (){
  gameState.value = 'BANKER_ROLLING';

  // 重置莊家分數
  bankerDicelist.value.forEach(die => die.score = '_');

  // 莊家骰子動畫 similar to player's animation , 用 setTimeout 延遲 100毫秒再開始轉, 這樣瀏覽器才有時間先把骰子「畫」在 0度的位置
  setTimeout(()=>{
      bankerDicelist.value.forEach((dice) => {
        const circle = Math.floor(Math.random() * 10) + 2;
        const baseSpins = 360 * circle;
        const randomFaceX = Math.floor(Math.random() * 4) * 90;
        const randomFaceY = Math.floor(Math.random() * 4) * 90;
        
        dice.x += (baseSpins + randomFaceX);
        dice.y += (baseSpins + randomFaceY);
    });
  }, 100);
    
  // 結算，時間要稍微往後推一點點，配合上面的延遲
    setTimeout(() => {
        bankerDicelist.value.forEach((dice) => {
            dice.score = getSingleDiceScore(dice.x, dice.y);
        });
        
        checkWinner(); // 比輸贏
        gameState.value = 'RESULT'; // 進入結算畫面
    }, 3100); 
};

// 判斷輸贏

const checkWinner = () => {
  const pScore = totalscore.value;
  const bScore = bankerTotalScore.value;
  let isWin = false;

  // 平手
  if (pScore == bScore) {
    finalMessage.value = 'DRAW';
    return;
  }

  // 判斷輸贏
  if (playerChoice.value == 'BIG') {
    isWin = pScore > bScore;
  } else {
    isWin = pScore < bScore;
  }

  finalMessage.value = isWin? "YOU WIN!" : "YOU LOSE..";
}

//  重置遊戲 init , play again
const resetGame = () => {
  gameState.value = "IDLE";
  playerChoice.value = '';

  // 分數顯示先歸零
  dicelist.value.forEach(d => d.score = '_');
  bankerDicelist.value.forEach(d => d.score = '_');
}


// ================ 生命週期 ================ 
onMounted(()=>{
  document.body.style.overflow = 'hidden';
  document.documentElement.style.overflow = 'hidden';
  window.addEventListener('mousemove', updateMouse)
  window.addEventListener('mousedown', handleMouseDown)
  window.addEventListener('mouseup', handleMouseUp)
  window.addEventListener('keydown', handleKey);
})

onUnmounted (() => {
  document.body.style.overflow = '';
  document.documentElement.style.overflow = '';
  window.removeEventListener('mousemove', updateMouse)
  window.removeEventListener('mousedown', handleMouseDown)
  window.removeEventListener('mouseup', handleMouseUp)
  window.removeEventListener('keydown', handleKey);
});

</script>

<template>
    <div class="playerbox">
        <h4>Player {{ result }}</h4>
        <div class="scorebox">
            <h3>Score：</h3>
            <h2 class="scores">{{ totalscore }}</h2>
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
             v-show="gameState === 'IDLE' || gameState === 'ROLLING' || gameState === 'CHOOSING'" 
             v-for="(dice, index) in dicelist" 
             :key="'player-'+index"
             :style="{
                left: (diceOrigins[index].left * 100) + '%',
                top: (diceOrigins[index].top * 100) + '%',
                
                transform: `translate(${dice.translateX}px, ${dice.translateY}px)`,
                opacity: diceOpacity,
                transition: 'transform 0.5s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.3s'
             }"
        >
            <Dice :Xdeg="dice.x" :Ydeg="dice.y"></Dice>       
        </div>
        <div class="dicebox banker-dice" 
             v-if="gameState === 'BANKER_ROLLING' || gameState === 'RESULT'"
             v-for="(dice, index) in bankerDicelist" 
             :key="'banker-'+index"
             :style="{ 
                left: (diceOrigins[index].left * 100) + '%',
                top: (diceOrigins[index].top * 100) + '%',

                transform: `translate(${dice.translateX}px, ${dice.translateY}px)`,
                opacity: diceOpacity,
                transition: 'transform 0.5s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.3s'              
             }"
        >
            <Dice :Xdeg="dice.x" :Ydeg="dice.y" style="filter: hue-rotate(180deg) brightness(0.9);"></Dice>
        </div>
    </div>

    <div class="bankerbox">
        <h4>banker</h4>
        <div class="scorebox">
            <h3>Score：</h3>
            <h2 class="scores">{{  bankerTotalScore }}</h2>
        </div>
    </div>

    <div v-if="gameState === 'CHOOSING'" class="overlay-modal">
        <div class="modal-content">
            <h2>Make a Choice!</h2>
            <p>Your Score: {{ totalscore }}</p>
            <div class="btn-group">
                <button class="btn-big" @click="chooseSide('BIG')">BIG</button>
                <button class="btn-small" @click="chooseSide('SMALL')">SMALL</button>
            </div>
        </div>
    </div>

    <div v-if="gameState === 'RESULT'" class="overlay-modal">
        <div class="modal-content">
            <h1>{{ finalMessage }}</h1>
            <p>You chose: <strong>{{ playerChoice }}</strong></p>
            <div class="result-details">
                <span>Player: {{ totalscore }}</span> vs <span>Banker: {{ bankerTotalScore }}</span>
            </div>
            <button class="btn-retry" @click="resetGame">PLAY AGAIN</button>
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


    // .dicebox:nth-child(1) { left: 80%; top: 40%;}
    // .dicebox:nth-child(2) { left: 10%; top: 20%;}
    // .dicebox:nth-child(3) { left: 10%; top: 75%;}

    .banker-dice {
      z-index: 600;
    }


    .bankerbox,
    .playerbox{
        width: 200px;
        height: 200px;
        text-align: center;
        border: 5px solid $color-fsGold;
        border-radius: 7px;
        box-sizing: border-box;
        padding-bottom: 20px;
    }

    .scores {
        font-weight: bold;
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
        background-color: $color-fsWhite;
    }

    .rollHand {
            position: fixed;
            z-index: 999;
            pointer-events: none;
            transform: translate(-50%, -50%);
            width: 280px;
    }

    .overlay-modal {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.7); 
        z-index: 2000; 
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        padding: 30px 50px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        border: 5px solid $color-fsGold;
        min-width: 300px;
        animation: popIn 0.3s ease-out;
    }

    @keyframes popIn {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .btn-group {
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-top: 20px;
    }

    button {
        padding: 12px 30px;
        font-size: 20px;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        font-weight: bold;
        color: $color-fsWhite;
        transition: transform 0.2s;
        &:hover { transform: scale(1.1); }
    }

    .btn-big { background-color: $color-fsRed; box-shadow: 0 4px 0 $color-fsRed}
    .btn-small { background-color: $color-fsGreen; box-shadow: 0 4px 0 $color-fsGreen; }
    .btn-retry { background-color: $color-fsBlue; margin-top: 20px; box-shadow: 0 4px 0 $color-fsBlue; }

    .result-details {
        font-size: 24px;
        margin: 15px 0;
        font-weight: bold;
        color: $color-fsTitle;
    }
</style>
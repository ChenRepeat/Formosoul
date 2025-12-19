<script setup>    
import { ref, onMounted, onUnmounted , defineEmits, computed} from "vue";
import gsap from "gsap";
// import { prawningData } from "./gamePrawningData"; // 物品是寫死的

// 遊戲狀態 
/*
會擺盪：鉤子左右搖。
會發射：按空白鍵或點擊，鉤子射出去。
會抓東西：碰到蝦子會抓回來加分。
*/

// 圖片路徑打包
const publicPath = import.meta.env.BASE_URL;
const getImgUrl = (path) => {
    if (path.startsWith('/')) {
        path = path.substring(1); // 變成 'SurvivalGuide/...'
    }
    return `${publicPath}${path}`;
}

// music 
const bgmAudio = ref(null);
const isMuted = ref(false);
const toggleMute = () => { // 切換靜音
    isMuted.value = !isMuted.value
    if(bgmAudio.value) {
        bgmAudio.value.muted = isMuted.value;
    }
}

const emit = defineEmits(['close-game']);

const score = ref(0);
const gameArea = ref(null);
const gameHook = ref(null); // 鉤子
const gameLine = ref(null); // 繩子;魚線
const gameClaw = ref(null); // 鉤子頭

// 加入時間，倒數計時
const timeLeft = ref(30);
// 加入 遊戲結束
const isGameOver = ref(false);
// 加入 遊戲準備畫面
const isGameReady = ref(true); 

// 物品 & 資料
// const items = ref(prawningData) // 物品是寫死的寫法 - 連到 JS
const items = ref([]);

// 隨機生成物品種類
const generateRandomItems = (count) => {
    const newItems = [];


    const imageLibrary = {
        shrimp: ['shrimp1.png', 'shrimp2.png', 'shrimp3.png'],
        trash:  ['bag.png', 'shoes.png', 'tire.png', 'can.png'],
        treasure: ['treasure.png', 'treasure2.png'] 
    };

    // 建立暫存清單
    let pendingList = [];

    // 先把每一張圖都加進去一次
    // 遍歷 imageLibrary 裡所有的種類和檔案
    for (const type in imageLibrary) {
        imageLibrary[type].forEach(filename => {
            pendingList.push({ type, filename });
        });
    }

    while (pendingList.length < count) {

        // 決定種類
        let type = 'shrimp';
        const rand = Math.random();
        if (rand > 0.6) type = 'trash';    // 40% 機率 is trash
        if (rand > 0.95) type = 'treasure'; // 5% 機率 is treasure

        const list = imageLibrary[type];
        const filename = list[Math.floor(Math.random() * list.length)];

        pendingList.push({ type, filename });
    }
 
    // 洗牌
    pendingList.sort(() => Math.random() - 0.5);

    // 給座標、分數、ID，生成最終資料
    pendingList.forEach((item, index) => {
        let { type, filename } = item;
        let itemScore = 0; 
        let width = 80;
        let height = 80;

        // 設定分數跟大小
        if(type == 'shrimp') {
            itemScore = 100;
        } else if (type === 'trash') {
            itemScore = -50;
            
            if (filename === 'shoes.png') { width = 60; height = 60; itemScore = -500; }
            if (filename === 'tire.png') { width = 100; height = 100; itemScore = -300; }

        } else { // treasure
            itemScore = 300; // 普通寶箱
            width = 60; height = 60;
    
            if (filename === 'treasure2.png') { width = 40; height = 40; itemScore = 500;}
        }

        let x = 0;
        let y = 0;
        let isOverlapping = true;
        let attempts = 0;

        // 嘗試找位置，最多試 100 次，找不到就直接開始
        while (isOverlapping && attempts < 100) {
            attempts++;
            isOverlapping = false; // 先假設沒重疊

            // 隨機座標
            x = Math.floor(Math.random() * 950) + 50; 
            y = Math.floor(Math.random() * 250) + 400;

            // 檢查跟「已經生成好」的物品有沒有太近
            for (const existingItem of newItems) {
                const dx = x - existingItem.x;
                const dy = y - existingItem.y;
                const distance = Math.sqrt(dx*dx + dy*dy);
                
                if (distance < 80) { // 如果距離小於 80px
                    isOverlapping = true; // 發生重疊
                    break; 
                }
            }
        }

        const fullSrc = `/SurvivalGuide/GamePrawning_remove_background/${filename}`;
        
        newItems.push({
            id: index + 1,
            type,
            x, y,
            width, height,
            score: itemScore,
            caught: false,
            src: fullSrc
        });
    });

    return newItems;
}


// 動畫控制的變數
let swingBetween = null;
let shootBetween = null;
let isShooting = false;
// 新增 計時器的 variable
let timerInterval = null;

// 遊戲流程控制
//  初始化/重置
const initGame = () => {
    // 重置資料數據
    score.value = 0;
    timeLeft.value = 30;
    isGameOver.value = false;
    isGameReady.value = true;

    // 清除舊狀態
    clearInterval(timerInterval);
    if(swingBetween) swingBetween.kill();
    if(shootBetween) shootBetween.kill();
    isShooting = false;

    items.value = generateRandomItems(20);
    
    gsap.set(gameHook.value, {rotation: 0});
    gsap.set(gameLine.value, {height: '50px'});
}

// 真正開始
const startGameAction = () => {
    if(!isGameReady.value) return;

    isGameReady.value = false;

    // 加入聲音控制
    if(bgmAudio.value) {
        bgmAudio.value.volume = 0.3;
        bgmAudio.value.currentTime = 0; 
        bgmAudio.value.play().catch(e => console.log("播放失敗", e));
    }

    startSwing();
    startTimer();
}

// Timer
const startTimer = () => {
    timerInterval = setInterval(()=>{
        if(timeLeft.value > 0) {
            timeLeft.value--;
        } else {
            gameOver();
        }
    }, 1000);
}

const gameOver = () => {
    isGameOver.value = true;
    clearInterval(timerInterval); // stop 計時

    // 停止擺盪
    if(swingBetween) swingBetween.pause();
    if(bgmAudio.value) {
        bgmAudio.value.pause();
    }
}


// 1 擺盪
const startSwing = ()=> {
    isShooting = false;

    // 使用 GSAP 讓鉤子左右搖擺
    swingBetween = gsap.fromTo(gameHook.value, 
        { rotation: -60},
        {
        rotation: 60,
        duration: 2.5,
        ease: 'sine.inOut', //鐘擺自然晃動
        yoyo: true,
        repeat: -1, // infinite
        transformOrigin: "top center"
    });
}
// 2 發射鉤子
const shoot = () => {
    if(isShooting || isGameOver.value) return; // 如果已經正在發射，就不能按
    isShooting = true;
    swingBetween.pause(); // 暫停搖擺

    // 接下來讓繩子變長
    shootBetween = gsap.to(gameLine.value, {
        height: '1000px',
        duration: 1,
        ease: 'none',  // 等速
        onUpdate: checkHit, // 每一瞬間都檢查 有沒有撞到
        onComplete: () => comeBack(null) // 伸到底都沒抓到，就撤回
    });
}

// 3 收回鉤子
const comeBack = (caughtItem) => {
    // 可以用收回速度來判斷是否有抓到東西 ，1.5s比較慢 有抓到，1s比較快 沒抓到
    const duration = caughtItem ? 1.5 : 0.5;  // 如果抓到物品（caughtItem 存在）→ 使用 1.5 秒的動畫時間，否則 → 使用 0.5 秒的動畫時間
    
    gsap.to(gameLine.value, {
        height: '50px',
        duration: duration,
        ease: 'power1.out',
        onUpdate: () => {
            // 如果有抓到東西，要讓東西跟著鉤子一起動
            if(caughtItem) updateItemPosition(caughtItem);
        },
        onComplete: () => { 
            // 收回完成
            if(caughtItem) {
                score.value += caughtItem.score; // 加分
                // 接著讓物品消失 (從陣列中移除)
                for (let i = 0; i < items.value.length; i++) {
                if (items.value[i].id === caughtItem.id) {
                    items.value.splice(i, 1);  // 從陣列移除
                    break;  // 只移除一個，找到就跳出
                    }
                }
            }
            if (!isGameOver.value) {
                swingBetween.resume(); // 繼續擺盪
                isShooting = false; // 解除鎖定
            }
        }
    })
}

// 4 碰撞偵測：檢查有沒有撞到 (HIT)
const checkHit = () => {
    // 鉤子頭現在在畫面上的位置 , 找鉤子頭的位置 跟 遊戲區的位置
    const clawRect = gameClaw.value.getBoundingClientRect();
    const gameRect = gameArea.value.getBoundingClientRect();
    
    // 算出鉤子頭在遊戲區的相對座標 (x, y)
    const clawX = clawRect.left - gameRect.left + (clawRect.width / 2)
    const clawY = clawRect.top - gameRect.top + (clawRect.height / 2)

    // 檢查每一個物品 , for of 拿內容物，for in 拿編號，這邊要用for of 
    for(let item of items.value) {
        if(item.caught) continue; // 被抓到的就跳過

        // 計算距離公式 
        const dx = clawX - (item.x + item.width/2)
        const dy = clawY - (item.y + item.height/2)
        const distance = Math.sqrt(dx*dx + dy*dy) ;

        // 如果這距離 <50, 代表抓到了
        if (distance < 50 ) {
            shootBetween.kill(), // 停止 伸長
            item.caught = true;
            comeBack(item);
            return; // 結束檢查
        }
    } 
}
// 5 更新物品位置 (讓它黏在鉤子上)
const updateItemPosition = (item) => {
    const clawRect = gameClaw.value.getBoundingClientRect();
    const gameRect = gameArea.value.getBoundingClientRect();
    // 強制把物品座標 = 鉤子座標
    item.x = clawRect.left - gameRect.left - (item.width / 2);
    item.y = clawRect.top - gameRect.top;
}


// 新增：點擊畫面處理開始
const handleAreaClick = () => {
    if(isGameReady.value) {
        startGameAction(); // 如果是準備中，點擊開始
    } else {
        shoot(); // 如果是遊戲中，點擊發射
    }
}


// 6 鍵盤控制 (空白鍵發射)
const handleKey = (e) => { 
    if (e.code === 'Escape') {
        emit('close-game');
        return;
    }

    if (e.code === 'Space') {
        if (isGameReady.value) {
            startGameAction(); // 空白鍵也可以開始
        } else {
            shoot();
        }
    }
};


// 生命週期 , 開始 modal 鎖住scroll
onMounted (()=>{
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';

    initGame();
    window.addEventListener('keydown', handleKey);
});

onUnmounted (()=> {
    document.body.style.overflow = '';
    document.documentElement.style.overflow = '';

    clearInterval(timerInterval);
    if(swingBetween) swingBetween.kill();
    if(shootBetween) shootBetween.kill();
    window.addEventListener('keydown', handleKey);
    if(bgmAudio.value) {
        bgmAudio.value.pause();
    }
});

</script>



<template>
    <div class="game-prawning-container " 
    ref="gameArea" @click="handleAreaClick"
    :style="{ backgroundImage: `url(${getImgUrl('/SurvivalGuide/prawning_background.png')})` }"
    >
        <audio ref="bgmAudio" :src="getImgUrl('/SurvivalGuide/prawning_bgm.mp3')" loop></audio>

        <div class="ui-mute" @click.stop="toggleMute">
            <span v-if="!isMuted">🔊</span> 
            <span v-else>🔇</span>
        </div>

        <div class="ui-score" :class="{ 'score-up': score >= 500 }">Score: {{ score }}</div>

        <div class="ui-timer" :class="{ 'urgent': timeLeft <= 10 }">
            Time: {{ timeLeft }}s
        </div>

        <div class="hook-wrapper" ref="gameHook"> 
            <div class="line" ref="gameLine">
                <div class="claw" ref="gameClaw">⚓</div>
            </div>
        </div>

        <div v-for="item in items"
        :key="item.id"
        class="item"
        :style="{
            left: item.x + 'px',
            top: item.y + 'px',
            width: item.width + 'px',
            height: item.height + 'px',
        }">
        <!-- 讀取上面 const items 資料裡面的 src 屬性，當作圖片的路徑 -->
        <img :src="getImgUrl(item.src)" class="item-img" />
        </div>

        <div v-if="isGameReady" class="start-screen-overlay">
            <div class="start-text">
                <h1>READY?</h1>
                <p>Click or Press Space to Start</p>
            </div>
        </div>

        <div v-if="isGameOver" class="game-over-text">
            <div class="result-title">TIME'S UP!</div>
            <h3>Your score is:  {{  score }}</h3>
            <div v-if="score >= 500" class="result-msg win"><h1>CONGRATS!</h1></div>
            <div v-else class="result-msg lose">
                <h1>TRY AGAIN!</h1>
            </div>

            <div class="action-buttons">
                <button class="btn-action btn-restart" @click.stop="initGame">
                    PLAY AGAIN ⟳
                </button>

                <button class="btn-action btn-exit" @click.stop="emit('close-game')">
                    EXIT
                </button>
            </div>
        </div>

    </div>
</template>

<style scoped lang="scss">
.game-prawning-container {
    width: 100%;
    height: 100%;

    background-color: #000;
    // background-image: url(/SurvivalGuide/prawning_background.png);
    background-size: cover;
    background-repeat: no-repeat;

    border-radius: 7px;
    position: relative;
    overflow: hidden;
    cursor: crosshair; // 鼠標變成 十字準心
}

// 準備畫面
.start-screen-overlay {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 50;
    cursor: pointer;
}

.start-text {
    text-align: center;
    color: $color-fsWhite;
    animation: pulse 1s infinite alternate;
    font-weight: bold;
}


// 分數
.ui-score {
 position: absolute;
    top: 20px;
    left: 2%;
    color: $color-fsWhite;
    font-size: 28px;
    font-weight: bold;
    z-index: 10;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
}

.score-up {
    color: $color-fsRed;
    transform: scale(1.1);
}

// volume
.ui-mute {
    position: absolute;
    top: 20px;
    left: 16%;
    right:auto;
    
    font-size: 30px;
    cursor: pointer;
    z-index: 60; 
    filter: drop-shadow(2px 2px 2px rgba(0,0,0,0.8));
    transition: transform 0.2s;

    &:hover {
        transform: scale(1.2);
    }
}

// Timer
.ui-timer {
    position: absolute;
    top: 20px;
    right: 16%;
    left: auto;

    color: $color-fsWhite;
    font-size: 28px;
    font-weight: bold;
    z-index: 10;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.8);

    transform-origin: right center;
    
    /* 倒數 10 秒變紅色跳動 */
    &.urgent {
        color: $color-fsRed;
        animation: pulse 0.5s infinite alternate;
    }
}

@keyframes pulse {
    from { 
        transform: scale(1); 
    }
    to { 
        transform: scale(1.1); 
    }       
}

// Finish: Time's up
.game-over-text {
    position: absolute;
    top: 53%;
    left: 50%;
    transform: translate(-50%, -50%);

    width: 100%;
    text-align: center;

    pointer-events: auto;
    z-index: 20;
}

.result-title {
    font-size: 80px;
    color: $color-fsWhite;
    font-weight: 900;
    text-shadow: 0 0 20px rgba(0,0,0,0.8);
    margin-bottom: 0px;    
}

.game-over-text h3 {
    color: $color-fsWhite;
    text-align: center;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
    font-weight: bold;
}

.result-msg h1{
    margin-top: 10px;
    text-align: center;
}

.win {
    color: $color-fsGold300;
    text-shadow: 0 0 10px rgba(255, 204, 70, 0.8);
    animation: bounce 1s infinite;
    font-weight: bold;

}

.lose {
    color: $color-fsWhite; 
    font-weight: bold;

    animation: heartbeat 2.5s infinite ease-in-out;
}
@keyframes heartbeat {
    0% { transform: scale(1); }
    10% { transform: scale(1.1); } 
    20% { transform: scale(1); }
    30% { transform: scale(1.1); }
    40% { transform: scale(1); }
    100% { transform: scale(1); } 
}


.action-buttons {
    display: flex;
    justify-content: center;
    gap: 20px; /* 按鈕之間的距離 */
    margin-top: 20px;
}

.btn-action {
    padding: 12px 30px;
    font-size: 20px;
    font-weight: bold;
    border-radius: 50px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);

    &:hover {
        transform: scale(1.1);
    }
    
    &:active {
        transform: scale(0.95);
    }
}

.btn-restart {
    background-color: $color-fsGreen; 
    color: $color-fsWhite;
    border: 3px solid $color-fsWhite;
}

.btn-exit {
    background-color: $color-fsRed;
    color: $color-fsWhite;
    border: 3px solid $color-fsWhite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}


.hook-wrapper {
    position: absolute;
    top: 150px; 
    left: 51%;
    width: 0;
    height: 0;
    z-index: 5;
}

.line {
    width: 4px;
    height: 50px;
    background: $color-fsWhite;
    margin: 0 auto;
    box-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    position: relative;
}

.claw {
    position: absolute;
    top: 95%;
    left: 50%;
    transform: translateX(-50%);
    font-size: 35px;
    line-height: 1;
    z-index: 2;
}

/* 物品 */
.item {
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    user-select: none; // 讓別人無法用滑鼠拖拉選取元素內的文字，預防意外複製。
}

.item-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    filter: drop-shadow(0 5px 5px rgba(0,0,0,0.5));
}


</style>
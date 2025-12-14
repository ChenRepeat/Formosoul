<script setup>    
import { ref, onMounted, onUnmounted } from "vue";
import gsap from "gsap";
import { prawningData } from "./gamePrawningData";




// 遊戲狀態 
/*
會擺盪：鉤子左右搖。
會發射：按空白鍵或點擊，鉤子射出去。
會抓東西：碰到蝦子會抓回來加分。
*/

const score = ref(0);
const gameArea = ref(null);
const gameHook = ref(null); // 鉤子
const gameLine = ref(null); // 繩子;魚線
const gameClaw = ref(null); // 鉤子頭

// 物品 & 資料
const items = ref(prawningData)

// 動畫控制的變數
let swingBetween = null;
let shootBetween = null;
let isShooting = false;

// 遊戲 logic 
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
    if(isShooting) return; // 如果已經正在發射，就不能按
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
            swingBetween.resume(); // 繼續擺盪
            isShooting = false; // 解除鎖定
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
            item.caught = true,
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

// 6 鍵盤控制 (空白鍵發射)
const handleKey = (e) => { if (e.code ==='Space') shoot();};

// 生命週期 , 開始 modal 鎖住scroll
onMounted (()=>{
    document.body.style.overflow = 'hidden';
    startSwing();
    window.addEventListener('keydown', handleKey);
});

onUnmounted (()=> {
    document.body.style.overflow = '';
    if(swingBetween) swingBetween.Kill();
    if(shootBetween) shootBetween.Kill();
    window.addEventListener('keydown', handleKey);
});

</script>



<template>
    <div class="game-prawning-container" 
    ref="gameArea" @click="shoot">
        <div class="ui-score">Score: {{ score }}</div>
        <div class="hook-wrapper" ref="gameHook"> 
            <div class="line" ref="gameLine"></div>
            <div class="claw" ref="gameClaw">⚓</div>
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
        <!-- 「去讀取上面 const items 資料裡面的 src 屬性，當作圖片的路徑」。 -->
        <img :src="item.src" class="item-img" />
        </div>

    </div>
</template>

<style scoped lang="scss">
.game-prawning-container {
    width: 100%;
    height: 100%;

    background-color: #000;
    background-image: url(/SurvivalGuide/prawning_background.png);
    background-size: cover;
    background-repeat: no-repeat;

    border-radius: 7px;
    position: relative;
    overflow: hidden;
    cursor: crosshair; // 鼠標變成 十字準心
}
.ui-score {
 position: absolute;
    top: 20px;
    left: 20px;
    color: white;
    font-size: 28px;
    font-weight: bold;
    z-index: 10;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
}

.hook-wrapper {
    position: absolute;
    top: 150px; /* 旋轉點藏在上面一點 */
    left: 51%;
    width: 0;
    height: 0;
    z-index: 5;
}

/* 繩子 */
.line {
    width: 4px;
    height: 50px; /* 初始長度 */
    background: #fff;
    margin: 0 auto;
    box-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    position: relative;
}


/* 鉤子頭 */
.claw {
    position: absolute;
    top: 100%; /* 永遠黏在繩子尾巴 */
    left: 50%;
    transform: translateX(-50%);
    font-size: 35px;
    line-height: 1;
}

/* 物品 */
.item {
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    user-select: none; // 讓使用者無法用滑鼠拖拉選取元素內的文字，防止意外複製。
}

.item-img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* 保持圖片比例 */
    filter: drop-shadow(0 5px 5px rgba(0,0,0,0.5));
}


</style>
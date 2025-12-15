<template>

        <div class="playerbox">
            <h3>Player</h3>
            <div class="scorebox">
                <h3>Score：</h3>
                <h3>{{ totalscore }}</h3>
            </div>
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

<script setup>
import { computed, ref } from 'vue';
import Bowl from './bowl.vue';
import Dice from './Dice.vue';
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

<style lang="scss" scoped>
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
</style>
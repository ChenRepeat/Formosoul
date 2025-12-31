<template>
    <div class="membercard-wrapper">
        <div class="membercard">
            <!-- 多做hover後可以有資訊讓使用者觀看 -->
                <div class="helmetcolor" :class="{'active':pointsSatus.mot == 1}">
                    <IconHelmet size="143" />
                </div>
                <div class="buecolor" :class="{'active':pointsSatus.bue == 1}">
                    <!-- 改用figma -->
                    <IconBuecard size="137"/>
                </div>
                <div class="dicecolor" :class="{'active':pointsSatus.dice == 1}">
                    <IconDice />
                </div>
                <div class="shrimpcolor" :class="{'active':pointsSatus.shrimp == 1}" >
                    <IconShrimp />
                </div>
                <div class="wandcorecolor" :class="{'active':pointsSatus.member_wandcore == 1}" >
                    <IconWandCore />
                </div>
                <div class="ringcolor" :class="{'active':pointsSatus.ring == 1}" >
                    <IconButton />
                </div>
                <!-- <div class="potioncolor" >
                     改用figma 
                    <IconButton size="74"/>
                </div> -->
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';

import IconHelmet from '@/components/icons/SVG/IconHelmet.vue';
import IconDice from '@/components/icons/SVG/IconDice.vue';
import IconShrimp from '@/components/icons/SVG/IconShrimp.vue';
import IconWandCore from '@/components/icons/SVG/IconWandCore.vue';
import IconBuecard from '@/components/icons/SVG/IconBuecard.vue';
import IconButton from '@/components/icons/SVG/IconButton.vue';


const props = defineProps({
    withouteditbtn:{
        type: Boolean,
        default: false,
    }
});

// const activeIcons = ref({
//     helmet: false,
//     bue: false,
//     potion: false,
//     dice: false,
//     shrimp: false,
//     wandcore: false,
// });

// function toggleIcon(key){
//     activeIcons.value[key] = !activeIcons.value[key]; 
// };

const pointsSatus = ref({
    dice: 0, 
    shrimp: 0, 
    ring: 0, 
    bue: 0, 
    mot: 0, 
    member_wandcore: 0
})

const get_pointscard = () => {
        const storedUser = localStorage.getItem('user'); 
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/getPointsCard.php`;
        if(!storedUser) return;
        const userData = JSON.parse(storedUser);
        const { member_ID } = userData;
        // 這邊的member_ID 解構賦值 就只是拿資料而已

        return fetch(API_URL, {
            method: 'POST', // 通常都用POST
            headers: { // 這邊是固定的
                'Content-Type' : 'application/json'
            },
            body: JSON.stringify({
                member_ID
            })} // 讓member_id字串化
        ).then( res => res.json() // 固定這樣寫，res 回傳json，這時候還沒拿到資料！
        ).then( pointscard_res => { // 這邊就是要把資料拿出來用
            console.log(pointscard_res)
            if(pointscard_res.success && pointscard_res.data){
                // console.log('骰子完成')
                pointsSatus.value = pointscard_res.data
            }
        }
    );
};

onMounted (()=>{
    get_pointscard();
})


</script>

<style lang="scss" scoped>
    .membercard-wrapper{
        background-color: #EEEEEE;
        width: 500px;
        height: 300px;
        border: 0;
        border-radius: 8px;
        margin: 0 auto;
        display: flex;
        padding-top: 32px;
        justify-content: space-between;
        position: relative;
    }
    
    .helmetcolor{
        width: 145px;
        height: 145px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 12px;
        left: 12px; 
        // transform: rotate(240deg); 
        transform: scaleX(-1) rotate(-10deg)                 

    }

    .buecolor{
        width: 119px;
        height: 119px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        bottom: 12px; 
        left: 12px; 
    }



    .potioncolor{
        width: 76px;
        height: 76px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        bottom: 60px; 
        left: 140px;               

    }
    
    .dicecolor{
        width: 142px;
        height: 142px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 12px;
        right: 12px; 
    }

    .shrimpcolor{
        width: 131px;
        height: 131px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        bottom: 24px;
        right: 12px;        
    }

    .wandcorecolor{
        width: 180px;
        height: 180px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 12px;
        right: 140px;         
    }

    .ringcolor {
        position: absolute;
        bottom: 18%;
        left: 28%;
    }


    .helmetcolor, .buecolor, 
    .potioncolor, .dicecolor, 
    .shrimpcolor, .wandcorecolor,
    .ringcolor {
        cursor: pointer; 
        color: #B0B0B0;

        &.active {
            color: $color-fsRed;
        }
    }


</style>
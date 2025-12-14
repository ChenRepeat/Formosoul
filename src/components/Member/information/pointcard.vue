<template>
    <div class="membercard-wrapper">
        <div class="membercard">
            <!-- 多做hover後可以有資訊讓使用者觀看 -->
                <div class="helmetcolor" :class="{ active: activeIcons.helmet}" @click="toggleIcon('helmet') ">
                    <IconHelmet size="143" />
                </div>
                <div class="buecolor" :class="{ active: activeIcons.bue}" @click="toggleIcon('bue') ">
                    <!-- 改用figma -->
                    <IconBuecard size="137"/>
                </div>
                <div class="potioncolor" :class="{ active: activeIcons.potion}" @click="toggleIcon('potion') ">
                    <!-- 改用figma -->
                    <IconButton size="74"/>
                </div>
                <div class="dicecolor" :class="{ active: activeIcons.dice}" @click="toggleIcon('dice') ">
                    <IconDice />
                </div>
                <div class="shrimpcolor" :class="{ active: activeIcons.shrimp}" @click="toggleIcon('shrimp') ">
                    <IconShrimp />
                </div>
                <div class="wandcorecolor" :class="{ active: activeIcons.wandcore}" @click="toggleIcon('wandcore') ">
                    <IconWandCore />
                </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

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


const activeIcons = ref({
    helmet: false,
    bue: false,
    potion: false,
    dice: false,
    shrimp: false,
    wandcore: false,
});

function toggleIcon(key){
    activeIcons.value[key] = !activeIcons.value[key]; 
};

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


    .helmetcolor, .buecolor, 
    .potioncolor, .dicecolor, 
    .shrimpcolor, .wandcorecolor {
        cursor: pointer; 
        color: #B0B0B0;

        &.active {
            color: #041426;
        }
    }


</style>
<script setup>
import BasicButton from '../BasicButton.vue';
import { ref } from 'vue';
import CoreGame from './CoreGame.vue';
const currentView = ref('history')
const passedGames = ref({wand: false});
const storedUser = localStorage.getItem('user');
const userData = JSON.parse(storedUser);
const saved = localStorage.getItem('game_progress');
        // if (saved) {
        //     const progress = JSON.parse(saved);
        //     passedGames.value.wand = !!progress.wand;
        // }
function showGame(){
    // currentView.value = 'game';
    if(storedUser && saved){
        console.log(userData.wandcore_ID);
        const progress = JSON.parse(saved);
        passedGames.value.wand = !!progress.wand;
        currentView.value = 'game';

    }else if(!storedUser && saved){
        const progress = JSON.parse(saved);
        passedGames.value.wand = !!progress.wand;
        currentView.value = 'game';
    }else{
        currentView.value = 'game';
    }

}

</script>

<template>

    <main 
    v-if="currentView === 'history'" 
    class="coregame-dock">
        <div class="coregame-top dp-flex">
            <img src="../../../public/Home/game/poking_real.png" alt="">
        </div>
        <div class="coregame-down">

            <h5>{{ $t('coreselection.pokinglottery')}}</h5>
            <p>{{ $t('coreselection.historyIntro')}}</p>

        </div>
        <BasicButton class="btn-yellow-fill" @click="showGame">{{ $t('coreselection.btngame')}}</BasicButton>
    </main>
    <CoreGame v-if="currentView === 'game'" :wand-passed="passedGames.wand"/>

</template>

<style scoped lang="scss">

    .coregame-dock{
        width: 100%;
    }

    .coregame-top{
        height: 40%;
        justify-content: center;
        align-items: center;
        margin-bottom: 5%;
    }

    .coregame-top img{
        width: 30%;
        height: 30%;
        object-fit: contain;
    }

    .coregame-down{
        margin: 0 auto 40px;
        width: 80%;
    }

    .coregame-down h5{
        text-align: center;
        margin-bottom: 20px;
    }

    .btn-yellow-fill{
        display: block;
        margin: 0 auto;
    }

</style>
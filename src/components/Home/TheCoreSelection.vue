<script setup>
import BasicButton from '../BasicButton.vue';
import CoreGame from './CoreGame.vue';
import CoreShow from './CoreShow.vue';
import GameHistory from './GameHistory.vue';


</script>

<template>
    <div class="coregame-wrapper">
        <div class="coregame-intro dp-none">

            <main class="coregame-dock dp-flex">
                <div class="coregame-left">
                    <h3>The Core Selection</h3>
                    <p>Welcome to Formosoul Institute!<br>
                        <span class="fw200">「 The Wand Chooses the Wizard. 」</span> <br>
                        When your Soul achieves perfect balance with the material’s Essence, the spirit will reach out—signaled by warmth, tremor, or light. <br>
                        This core will then become the soul of your wand, accompanying your magical journey.</p>
        
                </div>
                <div class="coregame-right dp-flex">
                    <img src="../../../public/Home/game/poking lottery.png" alt="">
                </div>
            </main>
            <nav class="dp-flex">
                <BasicButton class="btn-gray-fill">Uncover the Ritual's Origin</BasicButton>
                <BasicButton class="btn-yellow-fill">Begin the Core Sensing</BasicButton>
            </nav>
        </div>

        <GameHistory class="dp-none"/>
        <CoreGame class="dp-none"/>
        <CoreShow class=""/>

    </div>


</template>

<style scoped lang="scss">
    .coregame-wrapper{
    color: $color-fsWhite;
        padding: 40px 60px;
        margin: auto 0;
    }

    .coregame-dock{  
        gap: 40px;
    }

    .coregame-left, .coregame-right{
        width: 50%;
    }


//左邊文字------------------------------------
    .coregame-left h3{
        margin-bottom: 40px;
    }
    .coregame-left span{
        display: inline-block;
        margin: 16px 0;
        font-size: 2rem;
    }

    
    
    //右邊圖------------------------------------
    .coregame-right{
        justify-content: center;
        align-items: end;
    }

    .coregame-right img{
        width: 90%;
        height: 90%;
        object-fit: contain;
        object-position: bottom right;
        filter: drop-shadow(0 0 20px $color-fsWhite);   //因為要沿著櫃子加陰影，不能用box-shadow
        animation: blink 1.3s ease-in-out infinite;
    }

    @keyframes blink{
        0%, 100%{
            filter: drop-shadow(0 0 20px $color-fsWhite); 
        }
        50%{
            filter: drop-shadow(0 0 0px transparent);
        }
    }


//下方按鈕------------------------------------
    nav{
        justify-content: center;
        gap: 40px;
        margin-top: 100px;
    }


</style>
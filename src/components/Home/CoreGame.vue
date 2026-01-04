<script setup>
    import { ref, onMounted } from 'vue';
    import CoreShow from './CoreShow.vue';
    import MemberLedger from "@/components/Member/information/memberLedger.vue";


    //設定目前顯示頁面
    const currentView = ref('game');

    // 蓋章
    const showCardOverlay = ref(false);
    const passedGames = ref({ shrimp: false, dice: false, ringtoss: false, bue: false, bike: false, wand:   false });
    const activeTriggers = ref({ shrimp: false, dice: false, ringtoss: false, bue: false, bike: false, wand: false });

    onMounted(() => {
    const saved = localStorage.getItem('game_progress');
    if (saved) {
        const progress = JSON.parse(saved);
        passedGames.value.shrimp = !!progress.shrimp; 
        passedGames.value.dice = !!progress.dice;
        passedGames.value.ringtoss = !!progress.ringtoss;
        passedGames.value.bue = !!progress.bue;
        passedGames.value.bike = !!progress.bike;
        passedGames.value.wand = !!progress.wand;
    }
});

function handleCoreSelected() {
    // if (passedGames.value.wand) {
    //     showCardOverlay.value = true;
    // } else {
        setTimeout(() => {
            showCardOverlay.value = true; 

            setTimeout(() => {
                activeTriggers.value.wand = true; 

                setTimeout(() => {
                    passedGames.value.wand = true;

                    const currentProgress = JSON.parse(localStorage.getItem('game_progress') || '{}');
                    currentProgress.wand = true;
                    localStorage.setItem('game_progress', JSON.stringify(currentProgress));
                    activeTriggers.value.wand = false;
                }, 600); 
            }, 500);
        }, 300);
    }
// }


    function showCore(){
        currentView.value = 'core';
    }

    //接收子組件傳來的事件 - 重新開始遊戲 要做的事情
    function doRestartGame(){
        currentView.value = 'game';
    }



</script>

<template>
    <main 
    v-if="currentView === 'game'"
    class="coregame-dock dp-flex">
        <div class="coregame-left dp-flex">
            <img   
            src="/Home/game/poking lottery.png"
            alt="感應杖心"
            @click="showCore">
        </div>

        <div class="coregame-right">
            <div class="coregame-guide">
                <h5>{{ $t('coreselection.gameGuide')}}</h5>
                <p>{{ $t('coreselection.gameIntro1')}}<br> 
                    {{ $t('coreselection.gameIntro2')}}<br> <br>
                    {{ $t('coreselection.gameIntro3')}}
                </p>
            </div>

            
        </div>
    </main>
    <CoreShow
    v-if="currentView === 'core'" 
    @restart-coregame="doRestartGame"    
    @wand-selected="handleCoreSelected"
    /> 
    <!-- @restart-coregame="doRestartGame"  監聽 restart-game 事件，如果子組件有發送，接收到後要做什麼事 -->

    <div v-if="showCardOverlay" class="ledger-overlay-in-game">
        <div class="card-modal">
            <MemberLedger
                :passedGames="passedGames" 
                :activeTriggers="activeTriggers"
            />
            <button class="btn-close-card" @click="showCardOverlay = false">CLOSE LEDGER</button>
        </div>
    </div>

</template>

<style scoped lang="scss">

// 蓋章
.ledger-overlay-in-game {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: rgba(0, 0, 0, 0.85); 
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 5000; 
}

.card-modal {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
}

.btn-close-card {
    padding: 10px 25px;
    background-color: $color-fsRed;
    color: $color-fsWhite;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    font-weight: bold;
}


    .coregame-dock{  
        gap: 40px;
    }
    .coregame-left, .coregame-right{
        width: 50%;
    }

//左邊圖------------------------------------
    .coregame-left{
        justify-content: center;
        align-items: center;
    }

    .coregame-left img{
        width: 90%;
        height: 90%;
        object-fit: contain;
        object-position: center left;
        cursor: pointer;
        filter: drop-shadow(0 0 20px $color-fsWhite);   //因為要沿著櫃子加陰影，不能用box-shadow
        animation: blink 1.3s ease-in-out infinite;


        &:hover{                   //hover時取消閃爍
            animation: none;
            filter: none;
        }
    }

    @keyframes blink{
        0%, 100%{
            filter: drop-shadow(0 0 0px transparent);
        }
        50%{
            filter: drop-shadow(0 0 20px $color-fsWhite);
        }
    }

//右邊文字 guide------------------------------------

    .coregame-guide h5{
        margin-bottom: 40px;
    }




</style>
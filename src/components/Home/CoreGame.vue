<script setup>
    import { ref, onMounted, computed, watch } from 'vue';
    import CoreShow from './CoreShow.vue';
    import MemberLedger from "@/components/Member/information/memberLedger.vue";
    import { useMemberStore } from '@/stores/member';

    const memberStore = useMemberStore();
    const passTimes = ref(memberStore.gameData?.wand?.pass || 0);
    const emit = defineEmits(['refresh-data']);
    const handleRefreshData = () => {
        console.log('coregame');
        emit('refresh-data');
    }

    const props = defineProps({
        wandPassed: Boolean
    });

    //設定目前顯示頁面
    const currentView = ref('game');

    // 蓋章
    const showCardOverlay = ref(false);
    const passedGames = ref({ shrimp: false, dice: false, ringtoss: false, bue: false, bike: false, wand: false});

watch(
        ()=> props.wandPassed,
        (newValue) => {
            passedGames.value.wand = newValue;
            console.log('Props 更新了！wand 現在是:', newValue);
        },
        { immediate: true}
    )

const isLoggedIn = computed(() => {
    const user = localStorage.getItem('user');
    return !!user;
});
    const activeTriggers = ref({ shrimp: false, dice: false, ringtoss: false, bue: false, bike: false, wand: false });
    console.log(passedGames.value.wand);
    console.log(activeTriggers.value.wand);
    const initGameStatus = () => {
        const status = memberStore.pointsStatus || {};
    
        if (isLoggedIn.value) {
            // 會員：從資料庫讀取
            passedGames.value.shrimp   = status.shrimp >= 1;
            passedGames.value.dice     = status.dice >= 1;
            passedGames.value.ringtoss = status.ring >= 1;
            passedGames.value.bue      = status.bue >= 1;
            passedGames.value.bike     = status.mot >= 1;
            // passedGames.value.wand = Number(status.member_wandcore) >= 1;           
            // console.log('檢查魔杖是否已過關:', passedGames.value.wand);
            // console.log('[會員模式] 從資料庫讀取狀態:', passedGames.value);
        } else {
            // 訪客：從 localStorage 讀取
            const saved = localStorage.getItem('game_progress');
            if (saved) {
                const progress = JSON.parse(saved);
                passedGames.value.shrimp   = !!progress.shrimp;
                passedGames.value.dice     = !!progress.dice;
                passedGames.value.ringtoss = !!progress.ringtoss;
                passedGames.value.bue      = !!progress.bue;
                passedGames.value.bike     = !!progress.bike;
                // passedGames.value.wand     = !!progress.wand;
            }
            
            // console.log('[訪客模式] 從 localStorage 讀取狀態:', passedGames.value);
        }
    };

async function handleCoreSelected() {
    const isFirstPass = !passedGames.value.wand;
    // 有問題
    // console.log(passedGames.value.wand);
    passedGames.value.wand = true;
    passTimes.value += 1;
    console.log(passedGames.value.wand);
    console.log(activeTriggers.value.wand);
        setTimeout(() => {
            showCardOverlay.value = true; 
            if (isFirstPass) {
                memberStore.rewards_coupon();
                // console.log('%c[魔杖動畫] 觸發蓋章動畫', 'color: red; font-weight: bold');
                setTimeout(() => {
                    activeTriggers.value.wand = true; 
                    setTimeout(() => {
                        activeTriggers.value.wand = false;
                    }, 600); 
                }, 500);
            }
        }, 800);
        
        (async ()=> {
            try{
                if (isLoggedIn.value) {
                // 會員：更新資料庫
                await memberStore.stampOnepoint('member_wandcore');
                // 儲存成功後更新 store，但不影響當前的 passedGames
                await memberStore.fetchPointsStatus();
            } else {
                // 訪客：更新 localStorage
                const currentProgress = JSON.parse(localStorage.getItem('game_progress') || '{}');
                currentProgress.wand = true;
                localStorage.setItem('game_progress', JSON.stringify(currentProgress));
            }
        } catch (err) {
            console.error("[魔杖背景] 儲存失敗:", err);
        }
        })();
    }
                    
    function showCore(){
            currentView.value = 'core';
    }

    //接收子組件傳來的事件 - 重新開始遊戲 要做的事情
    function doRestartGame(){
        currentView.value = 'game';
    }
const ledgerClose = ()=> showCardOverlay.value = false;

onMounted(async () => {

    if (isLoggedIn.value) {
        // 只需要確保「點數狀態」抓回來就好
        await memberStore.fetchPointsStatus(); 
        initGameStatus();
    } else {
        initGameStatus();
    }
    // initGame();
    // window.addEventListener('keydown', handleKey);
});

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
    @refresh-data="handleRefreshData"
    /> 
    <!-- @restart-coregame="doRestartGame"  監聽 restart-game 事件，如果子組件有發送，接收到後要做什麼事 -->

    <div v-if="showCardOverlay" class="ledger-overlay-in-game">
        <div class="card-modal">
            <MemberLedger
                :passedGames="passedGames" 
                :activeTriggers="activeTriggers"
                @closeLedger="ledgerClose"
            />
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
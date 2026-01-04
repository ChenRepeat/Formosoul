<script setup>
    import { ref, onMounted, computed } from 'vue';
    import CoreShow from './CoreShow.vue';
    import MemberLedger from "@/components/Member/information/memberLedger.vue";
    import { useMemberStore } from '@/stores/member';
    // import { ca } from 'element-plus/es/locale';
    const memberStore = useMemberStore();
    const passTimes = ref(memberStore.gameData?.wand?.pass || 0);

    const isLoggedIn = computed(() => {
    const user = localStorage.getItem('user');
        return !!user;
    });

    //設定目前顯示頁面
    const currentView = ref('game');

    // 蓋章
    const showCardOverlay = ref(false);
    const passedGames = ref({ shrimp: false, dice: false, ringtoss: false, bue: false, bike: false, wand: false });
    const activeTriggers = ref({ shrimp: false, dice: false, ringtoss: false, bue: false, bike: false, wand: false });

    const initGameStatus = () => {
        const status = memberStore.pointsStatus || {};
    
        if (isLoggedIn.value) {
            // 會員：從資料庫讀取
            passedGames.value.shrimp   = status.shrimp >= 1;
            passedGames.value.dice     = status.dice >= 1;
            passedGames.value.ringtoss = status.ring >= 1;
            passedGames.value.bue      = status.bue >= 1;
            passedGames.value.bike     = status.mot >= 1;
            passedGames.value.wand     = status.member_wandcore >= 1;
            console.log('檢查魔杖是否已過關:', passedGames.value.wand);
            console.log('[會員模式] 從資料庫讀取狀態:', passedGames.value);
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
                passedGames.value.wand     = !!progress.wand;
            }
            
            console.log('[訪客模式] 從 localStorage 讀取狀態:', passedGames.value);
        }
    };

async function handleCoreSelected() {
    console.log('[魔杖點擊] 瞬間狀態:', {
        local: passedGames.value.wand,
        store: memberStore.pointsStatus.member_wandcore
    });

    const isAlreadyPassed = passedGames.value.wand || memberStore.pointsStatus.member_wandcore >= 1;
    const isFirstPass = !isAlreadyPassed;
    console.log('[魔杖] 是否第一次通關:', isFirstPass);
    
    passedGames.value = { 
        ...passedGames.value, 
        wand: true 
    };
    passTimes.value += 1;

        setTimeout(() => {
            showCardOverlay.value = true; 
            if (isFirstPass) {
                console.log('%c[魔杖動畫] 觸發蓋章動畫', 'color: red; font-weight: bold');
                setTimeout(() => {
                    activeTriggers.value.wand = true; 
                    setTimeout(() => {
                        activeTriggers.value.wand = false;
                    }, 600); 
                }, 500);
            } else {
                console.log('[魔杖動畫] 已經過關過，跳過蓋章動畫');
            }
        }, 300);
        
        (async ()=> {
            try{
                if (isLoggedIn.value) {
                // 會員：更新資料庫
                await memberStore.stampOnepoint('member_wandcore').catch(err => 
                    console.error("[魔杖] 蓋章失敗:", err)
                );
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

onMounted(() => {
    if (isLoggedIn.value) {
        // 兩個 API 同時發出去，誰快誰先跑 init
        memberStore.fetchPointsStatus().then(() => {
            initGameStatus();
        });

        memberStore.loadMemberData().then(() => {
            initGameStatus();
        });
    } else {
        initGameStatus();
    }
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
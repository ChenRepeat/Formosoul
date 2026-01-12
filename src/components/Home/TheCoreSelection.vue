<script setup>
import { useAuthStore } from '@/stores/autoStore';
import BasicButton from '../BasicButton.vue';
import CoreGame from './CoreGame.vue';
import GameHistory from './GameHistory.vue';
import { onMounted, ref, watch } from 'vue';
import axios from 'axios';

const currentView = ref('intro')

const authStore = useAuthStore();
const emit = defineEmits(['refresh-data']);
const handleRefreshData = () => {
    console.log('selection');
    emit('refresh-data');
}

// 1 判斷是否登入 要先搶先一步判斷這個集點卡到底有無蓋章過
const passedGames = ref({ shrimp: false, dice: false, ringtoss: false, bue: false, bike: false, wand: false });
// 從history進入會變成false
//把資料從 getPointsCard.php 拿出來用
    const getMemberInfo = () => {
        const currentProgress = JSON.parse(localStorage.getItem('game_progress') || '{}');
        // currentProgress.wand = true;
        // localStorage.setItem('game_progress', JSON.stringify(currentProgress));
        const storedUser = localStorage.getItem('user');
        const storeGuest = sessionStorage.getItem('guest')
        if(storedUser) {
            console.log('找到USER')
            const get_pointscard = async ()=> {
            const apiBase = import.meta.env.VITE_API_BASE;
            const API_URL = `${apiBase}/getPointsCard.php`
            if(!storedUser) return; 
            const userData = JSON.parse(storedUser);
            const { member_ID } = userData;
            try {
                const response = await axios.post(API_URL, { member_ID});
                const pointscard_res = response.data;
                console.log('取得學分登記卡資料：', pointscard_res);
                if(pointscard_res.success && pointscard_res.data){
                    passedGames.value.wand = Number(pointscard_res.data.member_wandcore) >= 1;
                    console.log('魔杖是否已過關:', passedGames.value.wand)
                }
            }catch(error){
                console.error('學分登記卡 API 讀取失敗:', error)      
            }
        };
        get_pointscard();
        } else if (!storedUser && storeGuest) {
            console.log('沒登入但有魔杖')
            const saved = localStorage.getItem('game_progress');
            if (saved) {
                const progress = JSON.parse(saved);
                passedGames.value.wand = !!progress.wand;
        } else {
            console.log('沒登入也沒有魔杖')
            
        }
    }
}

onMounted (()=> {
    getMemberInfo();
})

watch(
    () => authStore.isLoginModalOpen,
    (newvalue) => {
        if(newvalue == true){
            currentView.value = 'intro';
        }else{
            setTimeout(() => {
                currentView.value = 'intro';
            }, 1500);
            
        }
    }
);

//注意函數不要跟自訂的標籤名稱相同
function showHistory(){
    currentView.value = 'history';
}

function showGame(){
    currentView.value = 'game';
}

function showCore(){
    currentView.value = 'core';
}

</script>

<template>
    <div @refresh-data="handleRefreshData" class="coregame-wrapper">
        <div v-if="currentView === 'intro'" class="coregame-intro">   
            <!-- 在標籤中，currentView 不用再寫.value，因為 vue 會自動解開 ref
                 function 需要是因為那是 js -->
            <main class="coregame-dock dp-flex">
                <div class="coregame-left">
                    <h3>{{ $t('coreselection.thecoreselection')}}</h3>
                    <p>{{ $t('coreselection.welcome')}}<br>
                        <span class="fw200">{{ $t('coreselection.intro1')}}</span> <br>
                        {{ $t('coreselection.intro2')}} <br>
                        {{ $t('coreselection.intro3')}}</p>
        
                </div>
                <div class="coregame-right dp-flex">
                    <img src="/Home/game/poking lottery.png" alt="">
                </div>
            </main>
            <nav class="dp-flex">
                <BasicButton class="btn-gray-fill" @click="showHistory">{{ $t('coreselection.btnhistory')}}</BasicButton>
                <BasicButton class="btn-yellow-fill" @click="showGame">{{ $t('coreselection.btngame')}}</BasicButton>
            </nav>
        </div>

        <GameHistory v-else-if="currentView === 'history'" :wand-passed="passedGames.wand"/>
        <CoreGame 
  v-else-if="currentView === 'game'"
  :wand-passed="passedGames.wand"
  @refresh-data="handleRefreshData"
/>        

    </div>


</template>

<style scoped lang="scss">



//網頁
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
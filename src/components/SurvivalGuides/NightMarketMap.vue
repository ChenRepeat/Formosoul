<script setup>
import SurvivalTextFrame  from "@/components/SurvivalTextFrame.vue";
import SurvivalFoodIntroductionFrame from "../SurvivalFoodIntroductionFrame.vue";
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { RouterLink, useRouter } from "vue-router";
import { popupFrames, infoFrames } from "@/components/SurvivalGuides/nightMarketData.js";
import GamePrawning from '@/components/SurvivalGuides/GamePrawning.vue';
import GameDice from "./GameDice.vue";
import GameRingToss from "./GameRingToss.vue";
import BasicButton from "../BasicButton.vue";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';


const isShow = ref(-1);
const animationWelcome = ref(false)
const isGameLocked = ref(true);
//
const isMapReady = ref(false);
// 路由
const router = useRouter();

// 定義 hover 狀態
const isHover = ref(null)

// ==================== 從js引入 text frames 資料 ==================== 
const nmFrames = ref(popupFrames);

// ==================== 從js引入 food introduction frames 資料 ==================== 
const nmfFrames = ref(infoFrames);

// ==================== 控制介紹彈窗的資訊，要抓index ====================
const currentInfoData = ref(null);


// 只要傳入 index (第幾個食物)，它就會自動去抓上面的資料
// 你給我一個號碼（index），我就去倉庫（nmfFrames）把對應的資料拿出來，放到電視螢幕（currentInfoData）上播放
const openModal = (index) => {
    if (nmfFrames.value[index]) {
        currentInfoData.value = nmfFrames.value[index];
        // 左邊current這變數是接收到物件
    }
}

// 控制遊戲視窗的部分，開關
const isGameModalOpen = ref(false);
const activeGame = ref('') // 記錄要玩哪個遊戲 ex.'prawning', 'dice'...

const startGamePlay = () => {
    if(!currentInfoData.value) return;
    const currentID = currentInfoData.value.id;

    // 判斷 & 切換
    if(currentID.includes('prawning')) {
        activeGame.value = 'prawning'; // 設定遊戲為釣蝦
        isGameModalOpen.value = true; // 打開遊戲視窗
        currentInfoData.value = null; // 關閉介紹視窗
    }
    else if(currentID.includes('dice')) {
        activeGame.value = 'dice'; 
        isGameModalOpen.value = true; 
        currentInfoData.value = null; 
    }
    else if(currentID.includes('ring-toss')) {
        activeGame.value = 'ring-toss';
        isGameModalOpen.value = true; 
        currentInfoData.value = null; 
    }
};

const csFrame = ref([
    {
    id:'welcome',
    text:'survivalguide.welcometexttitle',
    description: "survivalguide.welcometextdescription",
    button: "survivalguide.startbutton",
    width:'400px',
    height:'auto',
    },
])
const welcomeFrame = computed(()=> csFrame.value[0])

onMounted (()=>{
    isShow.value = 1001;

    setTimeout(() => {
        isMapReady.value = true;
    }, 500);

    setTimeout(()=>{
        animationWelcome.value=true;
    },50)
})

function closeWelcomeFrame (){
    isShow.value = -1
    isGameLocked.value = false;
    animationWelcome.value = false;
}

</script>

<template>
        <main class="survival-night-market-case">
            <div class="survival-night-market-case-wrapper-outer">
            <div class="survival-night-market-case-wrapper" :class="{ 'locked': !isMapReady }" >
<!---------------------------------------- back btn -------------------------------------------->          
                <div v-show="!isGameModalOpen">
                    <RouterLink :to="{
                        name:'SurvivalGuide'
                    }">
                        <BasicButton class="btn-blue-fill"><font-awesome-icon icon="fa-solid fa-angle-left" />{{ $t("survivalguide.backbutton") }}</BasicButton>
                    </RouterLink>
                </div>

                <img class='survival-night-market-case-map' src="/SurvivalGuide/night_market_map_bg-min-no-logo.png" alt="map-base">

<!------------------- 點入的歡迎頁面 ------------------->
                <div v-if="isGameLocked" class="start-overlay"></div>
                <SurvivalTextFrame class="welcome-text-frame"
                :style="{ zIndex: isShow}"  
                :class="{ 'is-visible': animationWelcome }" 
                :text="$t(welcomeFrame.text)"
                            :description="$t(welcomeFrame.description)"
                :width="welcomeFrame.width"
                :height="welcomeFrame.height"
                tag="h4"
                align="center"
                @click="closeWelcomeFrame"
                >
                <template #descriptionExtra>
                    <font-awesome-icon icon="fa-solid fa-eye" />
                    <font-awesome-icon icon="fa-solid fa-eye" />
                </template>
                <template #textButton>
                    {{ $t("survivalguide.startbutton") }}
                </template>
                </SurvivalTextFrame>
<!-------------------------------------- map 連API用 區塊 -------------------------------------------->
                <div class="map-api-wrapper"
                @click="openModal(9)"
                >
                    <img class='map-api' src="/SurvivalGuide/map_api.png" alt="map-api">
                </div>
<!---------------------------------------- v-for -------------------------------------------->
                <div v-for="(item, index) in nmFrames" :key="index"
                @mouseenter="isHover = item.id" 
                @mouseleave="isHover = null" 
                @click="openModal(index)"
                :class="[{'nm-is-active': isHover == item.id,}, item.class ]">
                <img :class="item.imgclass" :src="item.imgurl" :alt="item.imgalt">
                    
                    <SurvivalTextFrame :class="`text-frame-${item.id}`"          :description="$t(item.description)"
                        :width="item.width" 
                        :height="item.height"
                        tag="h5"
                        align="center"
                        :showButton="false">
                    </SurvivalTextFrame>
                </div>

<!---------------------------------------- 擋板 區塊 -------------------------------------------->
                <div class="stall-wrapper-hide">
                    <img class="stall-hide" src="/SurvivalGuide/hide-mask-group.png" alt="hide">
                
                </div>
<!---------------------------------------- 共用部分 -------------------------------------------->
<!-- currentInfoData 是一整個「背包」，而 .mainImg 是伸手進去拿「背包裡的相機」, .是的或是裡面的意思，拿取 currentInfoData 這個袋子 裡面的 mainImg 那樣東西-->
                <div v-if="currentInfoData">
                    <SurvivalFoodIntroductionFrame 
                    @close="currentInfoData = null"
                    @play="startGamePlay"
                    :mainImg="currentInfoData.mainImg"
                    :title="currentInfoData.id === 'map-api' ? '' : $t(currentInfoData.title)"
                    :subTitle="currentInfoData.subTitle ? $t(currentInfoData.subTitle) : ''"
                    :text="currentInfoData.text ? $t(currentInfoData.text) : ''"
                    :subImg1="currentInfoData.subImg1"
                    :subImg2="currentInfoData.subImg2"
                    :subImg3="currentInfoData.subImg3"
                    :subImg4="currentInfoData.subImg4"
                    :buttonText="currentInfoData.buttonText ? $t(currentInfoData.buttonText) : ''"
                    ></SurvivalFoodIntroductionFrame>
                </div>
            </div>
<!---------------------------------------- 遊戲 Modal 視窗 -------------------------------------------->
            <div v-if="isGameModalOpen" class="game-modal-overlay">
                <div class="game-content-modal">
                    <button class="close-game-btn" @click="isGameModalOpen = false">
                        EXIT GAME
                    </button>
                    <GameRingToss v-if="activeGame == 'ring-toss'" @close-game="isGameModalOpen = false"  />
                    <GamePrawning v-if="activeGame == 'prawning'" @close-game="isGameModalOpen = false" />
                    <GameDice v-if="activeGame == 'dice'" @close-game="isGameModalOpen = false" />
                </div>
            </div>
         </div>
        </main>
</template>


<style scoped lang="scss">
// outer
.survival-night-market-case-wrapper-outer {
    height: 100vh;
    display: flex;
    align-items: center;
}

// 遮罩
.start-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    
    background-color: rgba(0, 0, 0, 0.7); 
    
    z-index: 1000; 
    
    pointer-events: auto; 
    cursor: default;
    transition: opacity 0.5s;
}

// 
.welcome-text-frame {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, calc(-50% + 20px)); 
    outline: 1px solid $color-fsTitle;
    outline-offset: -10px;

    transition: opacity 1s ease-out, transform 1s ease-out; 
    
    opacity: 0; 
    z-index: 1001;
} 
.welcome-text-frame.is-visible {
    opacity: 1; 
    transform: translate(-50%, -50%);;
}

// 新增回到前頁按鈕
.btn-blue-fill{
  background-color: $color-fsBlue900;
  color: $color-fsWhite;
  position: absolute;
  bottom: 8%;
  left: 3%;

  display: flex;
  justify-content: center;
  align-items: center;
  gap: 4px;
  
  box-shadow: 4px 4px 0px rgba(0, 0, 0, 0.4); 
  border: 2px solid white;
  z-index: 1001;
  transition: all 0.3s ease-in-out;

  &.btn-blue-fill:hover {
    transform: scale(1.025);
  }
}

// map 的區塊設定
.survival-night-market-case {
    background-color: #000;
    width: 100%; 
    height: auto;
    position: relative;
    overflow: hidden;
    
}

.survival-night-market-case-wrapper {
    position: relative;
    width: 100%;
    height: auto;
    margin: 0;    
    padding: 0;
    overflow: hidden;
    display: block;
    
}

.survival-night-market-case-map {
    position: relative;
    width: 100%;
    height: auto;

    display: block;
    // z-index: 0;
}

// =====================    map api 的區塊 ===================
.map-api-wrapper {
    position: absolute;
    width: 13%;
    bottom: 25.3%;
    right: 13%;

    cursor: pointer;

        &:hover {
        .map-api {
            transform: scale(1.025);
            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }
}

// ===================== 滷肉飯 攤位的區塊 ===================== 
.stall-wrapper-pork-rice {
    position: absolute;
    width: 8.75%;
    top: 14.1%;
    right: 24.6%;

    // z-index: 100;  
    cursor: pointer;

    &:hover {

        // z-index: 200; 

        .stall-pork-rice {
            transform: scale(1.025);
            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }
}

.text-frame-rice {
    opacity: 0;
    position: absolute;
    z-index: 101;
    left: 38%;
    bottom: -60%;
    transform: translateX(-50%) translateY(10px);
    margin-bottom: 10px;
    text-align: center;
    transition: all 0.5s ease-in-out;
    pointer-events: none;
}

.stall-wrapper-pork-rice:hover .text-frame-rice {
    opacity: 1;     
    pointer-events: auto; 
    transform: translate(-50%, 60%);
    outline: 1px solid $color-fsTitle;
    outline-offset: -10px;
}

// ===================== 珍珠奶茶 攤位的區塊 ===================== 

.stall-wrapper-bubble-tea {
    position: absolute;
    width: 9.25%;
    top: 6.1%;
    left: 40.25%;

    // z-index: 100;  
    cursor: pointer;

    &:hover {

        // z-index: 200; 

        .stall-bubble-tea {
            transform: scale(1.025);
            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }
}

.text-frame-bubble {
    opacity: 0;
    position: absolute;
    z-index: 101;
    left: 38%;
    bottom: -60%;
    transform: translateX(-40%) translateY(10px);
    margin-bottom: 10px;
    text-align: center;
    transition: all 0.5s ease-in-out;
    pointer-events: none;
}

.stall-wrapper-bubble-tea:hover .text-frame-bubble {
    opacity: 1;     
    pointer-events: auto; 
    transform: translate(-40%, 60%);
    outline: 1px solid $color-fsTitle;
    outline-offset: -10px;
}
// ===================== 臭豆腐 攤位的區塊 ===================== 

.stall-wrapper-sticky-tofu {
    position: absolute;
    width: 9.2%;
    top: 19%;
    left: 21.2%;

    // z-index: 100;  
    cursor: pointer;

    &:hover {

        // z-index: 200; 

        .stall-sticky-tofu {
            transform: scale(1.025);
            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }
}

.text-frame-tofu {
    opacity: 0;
    position: absolute;
    z-index: 101;
    left: 38%;
    bottom: -60%;
    transform: translateX(-30%) translateY(10px);
    margin-bottom: 10px;
    text-align: center;
    transition: all 0.5s ease-in-out;
    pointer-events: none;
}

.stall-wrapper-sticky-tofu:hover .text-frame-tofu {
    opacity: 1;     
    pointer-events: auto; 
    transform: translate(-30%, 50%);
    outline: 1px solid $color-fsTitle;
    outline-offset: -10px;
}

// ===================== 雞排 攤位的區塊 ===================== 

.stall-wrapper-chicken {
    position: absolute;
    width: 9.2%;
    top: 27.4%;
    left: 12.8%;

    // z-index: 100;  
    cursor: pointer;


    &:hover {

        // z-index: 200; 

        .stall-chicken {
            transform: scale(1.025);
            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }
}   

.text-frame-chicken {
    opacity: 0;
    position: absolute;
    z-index: 101;
    left: 38%;
    bottom: -60%;
    transform: translateX(-30%) translateY(10px);
    margin-bottom: 10px;
    text-align: center;
    transition: all 0.5s ease-in-out;
    pointer-events: none;
}

.stall-wrapper-chicken:hover .text-frame-chicken {
    opacity: 1;     
    pointer-events: auto; 
    transform: translate(-30%, 60%);
    outline: 1px solid $color-fsTitle;
    outline-offset: -10px;
}


// ===================== 大腸包小腸 攤位的區塊 ===================== 

.stall-wrapper-sausage {
    position: absolute;
    width: 9.8%;
    top: 9%;
    right: 34%;

    // z-index: 100;  
    cursor: pointer;


    &:hover {

        // z-index: 200; 

        .stall-sausage {
            transform: scale(1.025);
            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }
}

.text-frame-sausage {
    opacity: 0;
    position: absolute;
    z-index: 101;
    left: 38%;
    bottom: -60%;
    transform: translateX(-50%) translateY(10px);
    margin-bottom: 10px;
    text-align: center;
    transition: all 0.5s ease-in-out;
    pointer-events: none;
}

.stall-wrapper-sausage:hover .text-frame-sausage {
    opacity: 1;     
    pointer-events: auto; 
    transform: translate(-50%, 70%);
    outline: 1px solid $color-fsTitle;
    outline-offset: -10px;
}

// ===================== 蔥油餅 攤位的區塊 ===================== 

.stall-wrapper-oil {
    position: absolute;
    width: 9.5%;
    top: 22.2%;
    right: 14.7%;

    // z-index: 100;  
    cursor: pointer;


    &:hover {

        // z-index: 200; 

        .stall-oil {
            transform: scale(1.025);
            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }
}

.text-frame-oil {
    opacity: 0;
    position: absolute;
    z-index: 101;
    left: 38%;
    bottom: -60%;
    transform: translateX(-50%) translateY(10px);
    margin-bottom: 10px;
    text-align: center;
    transition: all 0.5s ease-in-out;
    pointer-events: none;
}

.stall-wrapper-oil:hover .text-frame-oil {
    opacity: 1;     
    pointer-events: auto; 
    transform: translate(-50%, 60%);
    outline: 1px solid $color-fsTitle;
    outline-offset: -10px;
}

// ===================== 共同 的區塊 ===================== 
.map-api,
.stall-pork-rice, 
.stall-bubble-tea, 
.stall-chicken,
.stall-sticky-tofu,
.stall-sausage,
.stall-oil,
.game-dice,
.game-prawning,
.game-ring-toss {
    width: 100%;
    height: auto;
    display: block;

    transition: all 0.3s ease-in-out;
    filter: drop-shadow(0 5px 5px rgba(0,0,0,0.3));
}

// ===================== 遮罩 攤位的區塊 ===================== 

.stall-wrapper-hide {
    position: absolute;
    width: 6.3%;
    right: 9.7%;
    top: 31.6%;
    z-index: 0;  
}

.stall-hide {
    width: 100%;
    height: auto;
    display: block;
}

// ===================== dice 遊戲的區塊 ======================
.game-wrapper-dice {
    position: absolute;
    width: 18.1%;
    bottom: -1%;
    right: 27.3%;

    z-index: 101;  
    cursor: pointer;


    &:hover {

        // z-index: 200;

        .game-dice {
            
            transform: scale(1.025);

            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }   
} 


// .text-frame{

//     &.text-frame-game-dice{}

// }
.text-frame-game-dice {
    opacity: 0;
    position: absolute;
    z-index: 101;
    left: 25%;
    bottom: -70%;
    transform: translateX(-50%) translateY(-50%);
    margin-bottom: 10px;
    text-align: center;
    transition: all 0.5s ease-in-out;
    pointer-events: none;
}

.game-wrapper-dice:hover .text-frame-game-dice{
    opacity: 1;     
    pointer-events: auto; 
    transform: translate(100%, -160%);
    outline: 1px solid $color-fsTitle;
    outline-offset: -10px;
}

// ===================== prawing 遊戲的區塊 ===================
.game-wrapper-prawning {
    position: absolute;
    width: 31.1%;
    bottom: 11%;
    left: 13.3%;

    // z-index: 100;  
    cursor: pointer;


    &:hover {

        // z-index: 200;

        .game-prawning {
            
            transform: scale(1.025);

            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }   
} 
.text-frame-game-prawning {
    opacity: 0;
    position: absolute;
    z-index: 101;
    left: 38%;
    bottom: -60%;
    transform: translateX(-50%) translateY(-50%);
    margin-bottom: 10px;
    text-align: center;
    transition: all 0.5s ease-in-out;
    pointer-events: none;
}

.game-wrapper-prawning:hover .text-frame-game-prawning{
    opacity: 1;     
    pointer-events: auto; 
    transform: translate(120%, -80%);
    outline: 1px solid $color-fsTitle;
    outline-offset: -10px;
}

// ===================== ring-toss 遊戲的區塊 =================
.game-wrapper-ring-toss {
    position: absolute;
    width: 25.5%;
    bottom: 23.1%;
    left: 45.6%;    

    // z-index: 100;  
    cursor: pointer;


    &:hover {

        // z-index: 200;

        .game-ring-toss {
            
            transform: scale(1.025);

            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }   
} 
.text-frame-game-ring-toss {
    opacity: 0;
    position: absolute;
    z-index: 101;
    left: 45%;
    bottom: -70%;
    transform: translateX(-50%) translateY(-50%);
    margin-bottom: 10px;
    text-align: center;
    transition: all 0.5s ease-in-out;
    pointer-events: none;
}

.game-wrapper-ring-toss:hover .text-frame-game-ring-toss{
    opacity: 1;     
    pointer-events: auto; 
    transform: translate(-180%, -220%);
    outline: 1px solid $color-fsTitle;
    outline-offset: -10px;
}

// ===================== 遊戲 Modal 的區塊 =================
.game-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0,0,0,0.9); 
    z-index: 999;

    display: flex;
    justify-content: center;
    align-items: center;
}
.game-content-modal {
    width: 85vw;
    height: 85vh;
    max-width: 1200px;
    background-color: #fff;
    border: 10px solid $color-fsBlue50;
    background-repeat: 7px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 0 50px rgba(0,0,0,0.8);
    border-radius: 7px;
}
.close-game-btn{
    position: absolute;
    top: 15px;
    right: 20px;
    padding: 10px 24px;
    background-color: $color-fsGold300;
    color: $color-fsTitle;
    font-weight: bold;
    font-size: 16px;
    border: 2px solid white;
    border-radius: 30px;
    cursor: pointer;
    z-index: 4000; /* 確保按鈕在最上層 */
    transition: transform 0.3s;

    &:hover {
        transform: scale(1.1);
        background-color: #ff6b81;
        color: $color-fsWhite;
    }    
}

.locked {
    pointer-events: none;
}
</style>
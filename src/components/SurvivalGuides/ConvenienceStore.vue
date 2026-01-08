<script setup>
import SurvivalTextFrame  from "@/components/SurvivalTextFrame.vue";
import SurvivalCSIntroductionFrame from "@/components/SurvivalCSIntroductionFrame.vue";
import { ref, onMounted, computed, onUnmounted } from 'vue'
import  { csIntroFrame  } from "@/components/SurvivalGuides/convenienceStoreData.js";
import { RouterLink, useRouter } from "vue-router";
import BasicButton from "../BasicButton.vue";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();


// 統一彈窗組件：
const getPopupClass = computed (()=>{
    if(!activeItemId.value) return '';

    if(leftItems.includes(activeItemId.value)){
        return 'popup-frame-left'
    }
    if(rightItems.includes(activeItemId.value)){
        return 'popup-frame-right'
    }

    if(activeItemId.value == 'twegg') return 'popup-frame-middle';
    if(activeItemId.value == 'twnoodle') return 'popup-frame-up';

    return '';
})

// 動態取得上下左右 text frane 座標的 Computed
const popupStyle = computed(()=>{
    if(!activeItemData.value || !activeItemData.value.pos) return {};

        const pos = activeItemData.value.pos;
        const styleObj = {
            top: pos.top,
            bottom: 'auto'
        };
        if (pos.left) {
                styleObj.left = pos.left;
                styleObj.right = 'auto';
            } else if (pos.right) {
                styleObj.right = pos.right;
                styleObj.left = 'auto';
            }

            return styleObj;
})

const isTextShow = ref(false)

// 1 亂數店員打招呼：
const randomTextArray = []
for( let i = 0; i < 10; i++) {
    randomTextArray.push(`conveniencestore.shopkeeper.greetings.text${i+1}`);
}

// 3 做放變數接字串
const randomResult = ref('')
const randomLocationTop = ref(null)
const randomLocationLeft = ref(null)
// 給數字用null 

const randomLocation = () => {
    randomLocationTop.value = Math.floor(Math.random() * 20 +10)

    randomLocationLeft.value = Math.floor(Math.random() * 50 + 20)
}

// 2 接下來開始做隨機：
const randomTextWord = () => {
    isTextShow.value = true;
    // Math.random是0-1的亂數，如果要設定要在random()
    randomResult.value = randomTextArray[Math.floor(Math.random()* 10)];

    // 開始做 textframe的 隨機 location
    randomLocation();
    // < 25.1 or > 54.5
    while (randomLocationLeft.value > 25.1 && randomLocationLeft.value < 54.5) {
        randomLocation();
    }

};

const isShow = ref(-1);
const animationWelcome = ref(false)

const isHover = ref(null);

const isGameLocked = ref(true);

// 換臉
const faces = {
    shock: '/tjd103/SurvivalGuide/ConvenienceStore/shock2.png',
    left:  '/tjd103/SurvivalGuide/ConvenienceStore/left3.png',
    right: '/tjd103/SurvivalGuide/ConvenienceStore/right2.png',
}

const currentFace = ref('');
const showFaceOverlay = ref(false); 
const currentFaceClass = ref('');

const leftItems = ['maitea', 'jinjin', 'threeone', 'twbeer', 'twchips','twegg'];
const rightItems = ['manhan', 'kuaikuai', 'puffs', 'twnoodle', 'twpie']

// 處理點擊功能：
const activeItemId = ref(null);

// 加入：防止重複點擊
const isShopkeeperAnimating = ref(false);


// 宣告一個叫做itemClick的function，它會接收一個叫 id 的參數，當這個函式被呼叫時，就執行大括號{}裡面的程式
const itemClick = (id) => {
    if(activeItemId.value == id) {
        clearAllStates();
        return;
    }
    clearAllStates();

    if (id == 'shopkeeper') {
        handleShopkeeperClick();
    } else {
        handleProductClick(id);
    }
}
    // 建立一個統一清除狀態的工具函式
const clearAllStates = () => {
    activeItemId.value = null;
    isTextShow.value = false;
    showFaceOverlay.value = false;
    currentFaceClass.value= '';
}
// 店員點擊處理
const handleShopkeeperClick = ()=> {
    if(isShopkeeperAnimating.value) return;

    activeItemId.value = 'shopkeeper'; // 賦值
    isShopkeeperAnimating.value = true;

    // 正臉
    currentFace.value = faces.shock;
    showFaceOverlay.value = true;
    currentFaceClass.value = 'face-shock';

    randomTextWord();

    setTimeout(()=>{
        // 檢查目前顯示的角色是不是店員, 如果是店員就清掉動畫or狀態
        if(activeItemId.value == 'shopkeeper') {
            clearAllStates()
        }
        // 不管怎樣，都要把動畫結束掉
        isShopkeeperAnimating.value = false;
    }, 1500);
}
// 商品點擊處理
const handleProductClick = (id) => {
    // 點商品時，把「目前被選中的東西」更新成這個商品的ID。這邊的 = 是賦值，不是比較(==)，是程式執行流程 
    activeItemId.value = id; 
    isTextShow.value = false;

    if(leftItems.includes(id)) {
        currentFace.value = faces.left;
        showFaceOverlay.value = true;
        currentFaceClass.value = 'face-left';
    } else if (rightItems.includes(id)) {
        currentFace.value = faces.right;
        showFaceOverlay.value = true;
        currentFaceClass.value = 'face-right';
    }
  }
    // // 如果點擊的是贏同一個商品，關閉 取消所有狀態
    // if (activeItemId.value == id) 

    // if(id === 'shopkeeper'){
    //     if (isShopkeeperAnimating.value) return;

    //     isShopkeeperAnimating.value = true;
    //     activeItemId.value = null;
    //     currentFace.value = faces.shock;
    //     showFaceOverlay.value = true;
    //     currentFaceClass.value = 'face-shock';
    //     randomTextWord();

    //     setTimeout(()=>{
    //         showFaceOverlay.value = false;
    //         currentFaceClass.value = '';
    //         isTextShow.value = false;
    //         isShopkeeperAnimating.value = false;
    //     }, 1500);
    //     return
    // }

    // if (activeItemId.value === id) {
    //     activeItemId.value = null;
    //     showFaceOverlay.value = false; 
    //     currentFaceClass.value = '';
    //     return;
    // } 
    // activeItemId.value = id;
    // isTextShow.value = false;

    // if (leftItems.includes(id)) {
    //     currentFace.value = faces.left;
    //     showFaceOverlay.value = true;
    //     currentFaceClass.value = 'face-left'; 
    // } 
    // else if (rightItems.includes(id)) {
    //     currentFace.value = faces.right;
    //     showFaceOverlay.value = true; 
    //     currentFaceClass.value = 'face-right'; 
    // } 

const activeItemData = computed(()=>{
    if (!activeItemId.value) return null;
    return csIntroFrame.find(item => item.id == activeItemId.value);
})

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

onMounted(()=>{
    isShow.value = 1001;

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
        <!-- z-index= 數字  這邊寫成動態style是用物件方法寫 isShow其實是值，一開始就是-1 -->
        <main class="survival-convenience-store-case">
            <div class="survival-convenience-store-case-wrapper">
                <img class='survival-convenience-store-case-bg' src="/SurvivalGuide/ConvenienceStore/convenienceStore-bg-min.png" alt="cs-base">

                <div v-if="isGameLocked" class="start-overlay"></div>

        <!------------------- 點入的歡迎頁面 ------------------->
                <img v-show="showFaceOverlay" 
                 :src="currentFace"
                 :class="['face-overlay', currentFaceClass]">

                <div class="shopkeeper-click-area" 
                    @click.stop="itemClick('shopkeeper')"
                    :class="{ 'is-animating': isShopkeeperAnimating }" >
                </div>

                <SurvivalTextFrame 
                v-if="isTextShow"
                class="randomText"
                :class="{'isTextShow': isTextShow}"
                :i18nText="true"
                :text="randomResult" 
                tag="h4" 
                align="center" 
                :showButton= "false" 
                width="20%" height="auto"
                style="padding: 10px;"
                :style="{top:`${randomLocationTop}%`, left:`${randomLocationLeft}%`}"
                />

                <SurvivalTextFrame 
                class="welcome-text-frame"
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
                <span>
                    {{ welcomeFrame.text }}
                </span>
                <template #descriptionExtra>
                    <font-awesome-icon icon="fa-solid fa-eye" />
                    <font-awesome-icon icon="fa-solid fa-eye" />
                </template>                
                <template #textButton>
                    {{ $t("survivalguide.startbutton") }}
                </template>
                </SurvivalTextFrame>
<!------------------------------- 統一食品的彈窗 ------------------------------->
<!---------------------------------------- 麥香茶-------------------------------------------->                
                <div class="mai-tea cs-img"
                @mouseenter="isHover = 'maitea'"
                @mouseleave="isHover = null"
                @click.stop="itemClick('maitea')"
                     :class="{'cs-is-active': isHover =='maitea',
                        'is-open': activeItemId === 'maitea',
                     }">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group.png" alt="">
                </div>
<!---------------------------------------- 津津蘆筍汁 -------------------------------------------->   
                <div class="jin-jin cs-img"
                @mouseenter="isHover = 'jinjin'"
                @mouseleave="isHover = null"
                @click.stop="itemClick('jinjin')"
                     :class="{'cs-is-active': isHover =='jinjin',
                     'is-open': activeItemId === 'jinjin',
                     }">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group2.png" alt="">
                </div>
<!---------------------------------------- 三點一刻 -------------------------------------------->   
                <div class="three-one cs-img"
                @mouseenter="isHover = 'threeone'"
                @mouseleave="isHover = null"
                @click.stop="itemClick('threeone')"
                     :class="{'cs-is-active': isHover =='threeone',
                     'is-open': activeItemId === 'threeone',
                     }">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group3.png" alt="">
                </div>   
<!---------------------------------------- 台灣啤酒 -------------------------------------------->   
                <div class="tw-beer cs-img"
                @mouseenter="isHover = 'twbeer'"
                @mouseleave="isHover = null"
                @click.stop="itemClick('twbeer')"
                     :class="{'cs-is-active': isHover =='twbeer',
                        'is-open': activeItemId === 'twbeer',
                     }">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group4.png" alt="">
                </div>     
<!---------------------------------------- 茶葉蛋 -------------------------------------------->   
                <div class="tw-egg cs-img"
                @mouseenter="isHover = 'twegg'"
                @mouseleave="isHover = null"
                @click.stop="itemClick('twegg')"
                     :class="{'cs-is-active': isHover =='twegg',
                        'is-open': activeItemId === 'twegg',
                     }">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group5.png" alt="">
                </div>    
<!---------------------------------------- 滿漢大餐 -------------------------------------------->   
                <div class="man-han cs-img"
                @mouseenter="isHover = 'manhan'"
                @mouseleave="isHover = null"
                @click.stop="itemClick('manhan')"
                     :class="{'cs-is-active': isHover =='manhan',
                        'is-open': activeItemId === 'manhan',
                     }">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group6.png" alt="">
                </div>  
<!---------------------------------------- 乖乖 -------------------------------------------->   
                <div class="kuai-kuai cs-img"
                @mouseenter="isHover = 'kuaikuai'"
                @mouseleave="isHover = null"
                @click.stop="itemClick('kuaikuai')"
                     :class="{'cs-is-active': isHover =='kuaikuai',
                        'is-open': activeItemId === 'kuaikuai',
                     }">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group7.png" alt="">
                </div>    
<!---------------------------------------- 義美小泡芙 -------------------------------------------->   
                <div class="puffs cs-img"
                @mouseenter="isHover = 'puffs'"
                @mouseleave="isHover = null"
                @click.stop="itemClick('puffs')"
                     :class="{'cs-is-active': isHover =='puffs',
                     'is-open': activeItemId === 'puffs',}">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group8.png" alt="">
                </div> 
<!---------------------------------------- 維力炸醬麵 -------------------------------------------->   
                <div class="tw-noodle cs-img"
                @mouseenter="isHover = 'twnoodle'"
                @mouseleave="isHover = null"
                @click.stop="itemClick('twnoodle')"
                     :class="{'cs-is-active': isHover =='twnoodle',
                     'is-open': activeItemId === 'twnoodle'
                     }">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group9.png" alt="">
                </div> 
<!---------------------------------------- 新貴派 -------------------------------------------->   
                <div class="tw-pie cs-img"
                @mouseenter="isHover = 'twpie'"
                @mouseleave="isHover = null"
                @click.stop="itemClick('twpie')"
                     :class="{'cs-is-active': isHover =='twpie',
                     'is-open': activeItemId === 'twpie'
                     }">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group10.png" alt="">
                </div> 
<!---------------------------------------- 蝦味先-------------------------------------------->   
                <div class="tw-chips cs-img"
                @mouseenter="isHover = 'twchips'"
                @mouseleave="isHover = null"
                @click.stop="itemClick('twchips')"
                     :class="{'cs-is-active': isHover =='twchips',
                     'is-open': activeItemId === 'twchips'}
                     ">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group11.png" alt="">
                </div> 
                
                <SurvivalTextFrame 
                 v-if="activeItemData"   
                 class="popup-frame"
                 :class="getPopupClass"
                 :style="popupStyle"
                 :text="$t(activeItemData.text)"
                 :width="activeItemData.width"
                 tag="p"
                 align="center"
                 @click="activeItemId = null"
                >
                <template #textButton>
                        {{ $t('nightmarket.others.close') }}
                </template>
                </SurvivalTextFrame>
            </div>   
                <!-- <SurvivalTextFrame 
                    v-if="activeItemId === 'maitea'" 
                    class="popup-frame popup-frame-left"
                    :text="$t(activeItemData.text)" 
                    :width="activeItemData.width" 
                    tag="p"
                    align="center"
                    @click.stop="activeItemId = null"
                 >
                    <template #textButton>{{ $t('nightmarket.others.close') }}</template>
                 </SurvivalTextFrame> -->
<!---------------------------------------- back btn -------------------------------------------->    
            <div>
                <RouterLink :to="{
                    name:'SurvivalGuide'
                }">
                    <BasicButton class="btn-blue-fill"><font-awesome-icon icon="fa-solid fa-angle-left" />{{ $t("survivalguide.backbutton") }}</BasicButton>
                </RouterLink>
            </div>
        </main>

</template>


<style scoped lang="scss">
// random text area
.randomText {
    position: absolute;
    z-index: 9999;
    top: 10%;
    left: 0;
    opacity: 0;

    &.isTextShow {
    opacity: 1;
    }
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

// 換臉
.face-overlay {
    position: absolute;
    z-index: 50;
    pointer-events: none;
    display: block;

    &.face-shock {
        width: 9.4%;
        top: 22.7%;    
        left: 45.1%;
    }
    &.face-left {
        width: 9.1%;
        top: 23.6%;    
        left: 45.5%;
    }
    &.face-right {
        width: 9.2%;
        top: 23.5%;    
        left: 45.2%;
    }
}

.shopkeeper-click-area {
    position: absolute;
    z-index: 60;
    cursor: pointer;  
    width: 22%;  
    height: 55%; 
    top: 24%;    
    left: 39%;
    background-color: transparent;

    &.is-animating {
        pointer-events: none; // 動畫中不能點擊
        cursor: default;
    }
}


.btn-blue-fill{
  cursor: pointer;
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
    transform: scale(1.055);
  }
}

// map 區塊設定
.survival-convenience-store-case {
    background-color: #000;
    width: 100%; 
    height: 100vh;
    position: relative;
    overflow: hidden;

    display: flex;
    justify-content: center;
    align-items: center;
}

.survival-convenience-store-case-wrapper {
    position: relative;
    width: 100%;
    height: auto;
    margin: 0;    
    padding: 0;
}

.survival-convenience-store-case-bg {
    width: 100%;
    height: auto;
    display: block;
}

// 
.welcome-text-frame {
    position: absolute;
    left: 57%;
    top: 28%;
    // outline: 1px solid $color-fsTitle;
    // outline-offset: -10px;

    transition: opacity 1s ease-out, transform 1s ease-out; 
    
    opacity: 0; 
    transform: translateY(20px); 
} 
.welcome-text-frame.is-visible {
    opacity: 1; 
    transform: translateY(0);
}


.cs-img {
    position: absolute;
    transition: all 0.3s ease-in-out;
    filter: drop-shadow(0 0 8px rgba(255, 215, 0, 0.7));
    cursor: pointer;
    z-index: 1;

    &.cs-is-active,
    &.cs-img:hover {
    transform: scale(1.025);
    filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
    z-index: 100;
    }


    &.is-open{
        z-index: 999;
    }

    &.mai-tea{
        width: 14%;
        top: 16.5%;
        left: 0.8%;
    }
    &.jin-jin{
        width: 11.8%;
        top: 14.5%;
        left: 13.8%;
    }
    &.three-one{
        width: 13.2%;
        top: 36.9%;
        left: 0.7%;
    }
    &.tw-beer{
        width: 13.2%;
        top: 32.9%;
        left: 12.7%;
    }
    &.tw-egg{
        width: 17.4%;
        bottom: -0.1%;
        left: 26%;
    }
    &.man-han{
        width: 13.7%;
        top: 14.8%;
        right: 0%;
    }
    &.kuai-kuai{
        width: 13.7%;
        top: 36.6%;
        right: 0%;
    }
    &.puffs{
        width: 6.7%;
        top: 41.6%;
        right: 19%;
    }
    &.tw-noodle{
        width: 12.7%;
        top: 0%;
        right: 0%;
    }
    &.tw-pie{
        width: 7.7%;
        top: 53.5%;
        right: 22.5%;
    }
    &.tw-chips{
        width: 6.3%;
        top: 40%;
        left: 33.6%;
    }
    img {
        width: 100%;
        height: auto;
        display: block;
    }
}

.popup-frame{
    // outline: 1px solid $color-fsTitle;
    // outline-offset: -10px;

    position: absolute;
    z-index: 10000;
    
    // min-width: 250px; 
    animation: popUp 0.3s ease-out;

    top: 0;
    left: 0;
    
    &.popup-frame-right {
        transform: none;
    }
    &.popup-frame-left {
        transform: none; // 左邊也不需要位移
    }
    &.popup-frame-middle {
        transform: translate(-50%, -50%);
    }
    &.popup-frame-up{
        transform: translateX(-50%);
    }
}

@keyframes popUp {
    from { 
        opacity: 0; 
    }
    to { 
        opacity: 1; 
    }
}


</style>
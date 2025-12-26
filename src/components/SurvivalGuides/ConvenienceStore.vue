<script setup>
import SurvivalTextFrame  from "@/components/SurvivalTextFrame.vue";
import SurvivalCSIntroductionFrame from "@/components/SurvivalCSIntroductionFrame.vue";
import { ref, onMounted, computed, onUnmounted } from 'vue'
import  { csIntroFrame  } from "@/components/SurvivalGuides/convenienceStoreData.js";
import { RouterLink, useRouter } from "vue-router";
import BasicButton from "../BasicButton.vue";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

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

const itemClick = (id) => {
    if (activeItemId.value === id) {
        activeItemId.value = null;

        showFaceOverlay.value = false; 
        currentFaceClass.value = '';
        return;
    } 
    activeItemId.value = id;
    if(id === 'shopkeeper'){
        currentFace.value = faces.shock;
        showFaceOverlay.value = true;
        currentFaceClass.value = 'face-shock';
    }
    else if (leftItems.includes(id)) {
        currentFace.value = faces.left;
        showFaceOverlay.value = true;
        currentFaceClass.value = 'face-left'; 
    } 
    else if (rightItems.includes(id)) {
        currentFace.value = faces.right;
        showFaceOverlay.value = true; 
        currentFaceClass.value = 'face-right'; 
    } 
    else {
        showFaceOverlay.value = false;
    }
}


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
                    @click.stop="itemClick('shopkeeper')">
                </div>

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

<!---------------------------------------- 麥香茶-------------------------------------------->                
                <div class="mai-tea cs-img"
                @mouseenter="isHover = 'maitea'"
                @mouseleave="isHover = null"
                @click.stop="itemClick('maitea')"
                     :class="{'cs-is-active': isHover =='maitea',
                        'is-open': activeItemId === 'maitea',
                     }">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group.png" alt="">
                     
                <SurvivalTextFrame 
                    v-if="activeItemId === 'maitea'" 
                    class="popup-frame popup-frame-left"
                    :text="activeItemData.text" 
                    :width="activeItemData.width" 
                    tag="p"
                    align="center"
                    @click.stop="activeItemId = null"
                 >
                    <template #textButton>CLOSE</template>
                 </SurvivalTextFrame>
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
                     
                <SurvivalTextFrame 
                    v-if="activeItemId === 'jinjin'" 
                    class="popup-frame popup-frame-left"
                    :text="activeItemData.text" 
                    :width="activeItemData.width" 
                    tag="p"
                    align="center"
                    @click.stop="activeItemId = null"
                 >
                    <template #textButton>CLOSE</template>
                 </SurvivalTextFrame>
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
                     
                <SurvivalTextFrame 
                    v-if="activeItemId === 'threeone'" 
                    class="popup-frame popup-frame-left"
                    :text="activeItemData.text" 
                    :width="activeItemData.width" 
                    tag="p"
                    align="center"
                    @click.stop="activeItemId = null"
                 >
                    <template #textButton>CLOSE</template>
                 </SurvivalTextFrame>
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
                     
                <SurvivalTextFrame 
                    v-if="activeItemId === 'twbeer'" 
                    class="popup-frame popup-frame-left"
                    :text="activeItemData.text" 
                    :width="activeItemData.width" 
                    tag="p"
                    align="center"
                    @click.stop="activeItemId = null"
                 >
                    <template #textButton>CLOSE</template>
                 </SurvivalTextFrame>
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
                     
                <SurvivalTextFrame 
                    v-if="activeItemId === 'twegg'" 
                    class="popup-frame popup-frame-middle"
                    :text="activeItemData.text" 
                    :width="activeItemData.width" 
                    tag="p"
                    align="center"
                    @click.stop="activeItemId = null"
                 >
                    <template #textButton>CLOSE</template>
                 </SurvivalTextFrame>
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
                     
                <SurvivalTextFrame 
                    v-if="activeItemId === 'manhan'" 
                    class="popup-frame popup-frame-right"
                    :text="activeItemData.text" 
                    :width="activeItemData.width" 
                    tag="p"
                    align="center"
                    @click.stop="activeItemId = null"
                 >
                    <template #textButton>CLOSE</template>
                 </SurvivalTextFrame>
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
                     
                <SurvivalTextFrame 
                    v-if="activeItemId === 'kuaikuai'" 
                    class="popup-frame popup-frame-right"
                    :text="activeItemData.text" 
                    :width="activeItemData.width" 
                    tag="p"
                    align="center"
                    @click.stop="activeItemId = null"
                 >
                    <template #textButton>CLOSE</template>
                 </SurvivalTextFrame>
                </div>  
<!---------------------------------------- 義美小泡芙 -------------------------------------------->   
                <div class="puffs cs-img"
                @mouseenter="isHover = 'puffs'"
                @mouseleave="isHover = null"
                @click.stop="itemClick('puffs')"
                     :class="{'cs-is-active': isHover =='puffs',
                     'is-open': activeItemId === 'puffs',}">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group8.png" alt="">
                     
                <SurvivalTextFrame 
                    v-if="activeItemId === 'puffs'" 
                    class="popup-frame popup-frame-right"
                    :text="activeItemData.text" 
                    :width="activeItemData.width" 
                    tag="p"
                    align="center"
                    @click.stop="activeItemId = null"
                 >
                    <template #textButton>CLOSE</template>
                 </SurvivalTextFrame>
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
                     
                <SurvivalTextFrame 
                    v-if="activeItemId === 'twnoodle'" 
                    class="popup-frame popup-frame-up"
                    :text="activeItemData.text" 
                    :width="activeItemData.width" 
                    tag="p"
                    align="center"
                    @click.stop="activeItemId = null"
                 >
                    <template #textButton>CLOSE</template>
                 </SurvivalTextFrame>
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

                <SurvivalTextFrame 
                    v-if="activeItemId === 'twpie'" 
                    class="popup-frame popup-frame-right"
                    :text="activeItemData.text" 
                    :width="activeItemData.width" 
                    tag="p"
                    align="center"
                    @click.stop="activeItemId = null"
                 >
                    <template #textButton>CLOSE</template>
                </SurvivalTextFrame>
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
                <SurvivalTextFrame 
                    v-if="activeItemId === 'twchips'" 
                    class="popup-frame popup-frame-right"
                    :text="activeItemData.text" 
                    :width="activeItemData.width" 
                    tag="p"
                    align="center"
                    @click.stop="activeItemId = null"
                 >
                    <template #textButton>CLOSE</template>
                </SurvivalTextFrame>
                </div>  

            </div>
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
    
    // background-color: rgba(255, 0, 0, 0.5);
    background-color: transparent;
}


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
    outline: 1px solid $color-fsTitle;
    outline-offset: -10px;

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
    filter: drop-shadow(0 5px 5px rgba(0,0,0,0.3));
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
    outline: 1px solid $color-fsTitle;
    outline-offset: -10px;

    position: absolute;
    z-index: 201;
    
    bottom: -200%; 
    left: 50%;
    transform: translateX(-50%); 
    
    min-width: 250px; 
    
    animation: popUp 0.3s ease-out;

    &.popup-frame-left {
    bottom: -180%; 
    left: 80%;
    }
    &.popup-frame-middle {
    bottom: 50%;
    left: 100%;
    }
    &.popup-frame-right {
    left: 0;
    bottom: -180%; 
    right: 60%;
    }
    &.popup-frame-up{
    bottom: 0;
    top: 140%;
    left: 0;
    right: 120%;
    }

}

@keyframes popUp {
    from { 
        opacity: 0; 
        transform: translate(-50%, 10px); 
    }
    to { 
        opacity: 1; 
        transform: translate(-50%, 0); 
    }
}


</style>
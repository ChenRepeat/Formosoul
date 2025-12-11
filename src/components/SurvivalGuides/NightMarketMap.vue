<script setup>
import SurvivalTextFrame  from "@/components/SurvivalTextFrame.vue";
import SurvivalFoodIntroductionFrame from "../SurvivalFoodIntroductionFrame.vue";
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useRouter } from "vue-router";


// 路由
const router = useRouter();

// 定義 hover 狀態
const isHover = ref(null)

// ==================== 引用 text frames 資料 ==================== 
const nmFrames = ref([
    {
    id:'prok-rice',
    text: 'Food: \n Braised Pork Rice',
    width: '200px',
    height: 'auto',
    },
    {
    id: 'oil',
    text: 'Food: \n Scallion pancake',
    width: '200px',
    height: 'auto',    
    },
    {
    id: 'bubble',
    text: 'Food: \n Bubble tea',
    width: '200px',
    height: 'auto',   
    },
    {
    id: 'sausage',
    text: 'Food: \n Taiwanese sausage with sticky rice',    
    width: '200px',
    height: 'auto',       
    },
    {
    id: 'sticky-tofu',
    text: 'Food: \n Sticky tofu',    
    width: '200px',
    height: 'auto',  
    },
    {
    id: 'fried-chicken',
    text: 'Food: \n Fried chicken cutlet',    
    width: '200px',
    height: 'auto',          
    },
    {
    id: 'game-dice',
    text: 'Game: \n Dice',    
    width: '200px',
    height: 'auto',          
    },
    {
    id: 'game-prawning',
    text: 'Game: \n Prawning',    
    width: '200px',
    height: 'auto',          
    },
    {
    id: 'game-ring-toss',
    text: 'Game: \n Ring toss',    
    width: '200px',
    height: 'auto',          
    },    
])

const riceFrame = computed(() => nmFrames.value[0]);
const oilFrame = computed(()=> nmFrames.value[1]);
const bubbleFrame = computed(()=> nmFrames.value[2]);
const sausageFrame = computed(()=> nmFrames.value[3]);
const tofuFrame = computed(()=> nmFrames.value[4]);
const chickenFrame = computed (()=> nmFrames.value[5]);
const diceFrame = computed (()=> nmFrames.value[6]);
const prawningFrame = computed (()=> nmFrames.value[7]);
const ringFrame = computed (()=> nmFrames.value[8]);


// ==================== 引用 food introduction frames 資料 ==================== 

const isShowFoodIntroduction = ref(false);

const nmfFrames = ref([
    {
    id:'food-introduction-rice',
    mainImg: '/SurvivalGuide/rice1.jpg',
    title: 'Braised pork rice',
    subTitle: '(Lu Rou Fan)',
    text:'Lu rou fan, or braised pork rice, is a popular Taiwanese comfort food made from minced or finely chopped pork belly slowly braised in a savory and slightly sweet soy sauce–based gravy with garlic, shallots, five-spice powder, and Shaoxing rice wine. The pork is cooked until tender and flavorful, then served over hot steamed white rice, creating a rich and satisfying meal loved across Taiwan. This dish is simple yet deeply aromatic, with a balance of salty, sweet, and umami flavors that make it a staple in Taiwanese cuisine.',
    subImg1: '/SurvivalGuide/rice2.jpg',
    subImg2:'/SurvivalGuide/rice3.jpg',
    },
    {
    id:'food-introduction-rice',
    mainImg: '/SurvivalGuide/rice1.jpg',
    title: 'Braised pork rice',
    subTitle: '(Lu Rou Fan)',
    text:'Lu rou fan, or braised pork rice, is a popular Taiwanese comfort food made from minced or finely chopped pork belly slowly braised in a savory and slightly sweet soy sauce–based gravy with garlic, shallots, five-spice powder, and Shaoxing rice wine. The pork is cooked until tender and flavorful, then served over hot steamed white rice, creating a rich and satisfying meal loved across Taiwan. This dish is simple yet deeply aromatic, with a balance of salty, sweet, and umami flavors that make it a staple in Taiwanese cuisine.',
    subImg1: '/SurvivalGuide/rice2.jpg',
    subImg2:'/SurvivalGuide/rice3.jpg',
    },

])

const riceIntroductionFrame = computed(()=> nmfFrames.value[0])
const tofuIntroductionFrame = computed(()=> nmfFrames.value[1])


</script>

<template>
        <main class="survival-night-market-case">
            <div class="survival-night-market-case-wrapper">
                <img class='survival-night-market-case-map' src="/SurvivalGuide/night_market_map_bg-min-no-logo.png" alt="map-base">

                <!-------------------------- 夜市美食區塊 ---------------------------->
                <!-------------------------- 滷肉飯 ---------------------------->
                <div class="stall-wrapper-pork-rice" @mouseenter="isHover = 'pork'" @mouseleave="isHover = null" 
                    :class="{'nm-is-active': isHover == 'pork',}">
                    <img class="stall-pork-rice" src="/SurvivalGuide/rice-mask-group.png" alt="braised-pork-stall">
                    
                    <SurvivalTextFrame class='text-frame-rice' 
                        :text="riceFrame.text" 
                        :width="riceFrame.width" 
                        :height="riceFrame.height"
                        tag="h5"
                        align="center"
                        @click="isShowFoodIntroduction =true">
                    <template #textButton>
                        CLICK
                    </template>
                    </SurvivalTextFrame>
                </div>
                <!-------------------------- 彈窗 滷肉飯 美食介紹 區塊 (用組件做) ---------------------------->
                <div>
                    <SurvivalFoodIntroductionFrame class="food-introduction-frame-rice"
                    v-if="isShowFoodIntroduction"
                    @close="isShowFoodIntroduction = false"
                    :mainImg="riceIntroductionFrame.mainImg"
                    :title="riceIntroductionFrame.title"
                    :subTitle="riceIntroductionFrame.subTitle"
                    :text="riceIntroductionFrame.text"
                    :subImg1="riceIntroductionFrame.subImg1"
                    :subImg2="riceIntroductionFrame.subImg2"
                    ></SurvivalFoodIntroductionFrame>
                </div>
                    <!-- :style="{'opacity': 0}", -->

                <!-------------------------- 珍珠奶茶 ---------------------------->
                <div class="stall-wrapper-bubble-tea" @mouseenter="isHover = 'bubble'" @mouseleave="isHover = null"
                     :class="{'nm-is-active': isHover == 'bubble' }">
                    <img class="stall-bubble-tea" src="/SurvivalGuide/bubble-mask-group.png" alt="bubble-tea">

                    <SurvivalTextFrame class="text-frame-bubble"
                        :text="bubbleFrame.text"
                        :width="bubbleFrame.width"
                        :height="bubbleFrame.height"
                        tag= "h5"
                        align="center"
                        @click="">
                    <template #textButton>
                        CLICK
                    </template>
                    </SurvivalTextFrame>
                </div>
                <div class="stall-wrapper-chicken" @mouseenter="isHover='chicken'" @mouseleave="isHover = null"
                     :class="{'nm-is-active': isHover == 'chicken'}">    
                    <img class="stall-chicken" src="/SurvivalGuide/chicken-mask-group.png" alt="fried-chicken">

                    <SurvivalTextFrame class="text-frame-chicken"
                    :text="chickenFrame.text"
                    :width="chickenFrame.width"
                    :height="chickenFrame.height"
                    tag="h5"
                    align="center"
                    @click=""
                    >
                    <template #textButton>
                        CLICK
                    </template>    
                    </SurvivalTextFrame>                     
                
                </div>
                <div class="stall-wrapper-sticky-tofu" @mouseenter="isHover='tofu'" @mouseleave="isHover = null"
                     :class="{'nm-is-active': isHover == 'tofu'}">
                    <img class="stall-sticky-tofu" src="/SurvivalGuide/tofu-mask-group.png" alt="sticky-tofu">

                    <SurvivalTextFrame class="text-frame-tofu"
                    :text="tofuFrame.text"
                    :width="tofuFrame.width"
                    :height="tofuFrame.height"
                    tag="h5"
                    align="center"
                    @click=""
                    >
                    <template #textButton>
                        CLICK
                    </template>    
                    </SurvivalTextFrame>                    
                </div>
                <div class="stall-wrapper-sausage" @mouseenter="isHover = 'sausage'" @mouseleave="isHover = null"
                    :class="{'nm-is-active':ishover == 'sausage'}">
                    <img class="stall-sausage" src="/SurvivalGuide/sausage-mask-group.png" alt="sausage">
                
                    <SurvivalTextFrame class="text-frame-sausage"
                    :text="sausageFrame.text"
                    :width="sausageFrame.width"
                    height="sausageFrame.height"
                    tag="h5"
                    align="center"
                    @click=""
                    >
                    <template #textButton>
                        CLICK
                    </template>    
                    </SurvivalTextFrame>
                </div>
                <div class="stall-wrapper-oil" @mouseenter="isHover= 'oil'" @mouseleave = "isHover = null"
                    :class="{'nm-is-active': isHover =='oil'}", >
                    <img class="stall-oil" src="/SurvivalGuide/oil-mask-group.png" alt="oil">

                    <SurvivalTextFrame class="text-frame-oil"
                    :text="oilFrame.text"
                    :width="oilFrame.width"
                    :height="oilFrame.height"
                    tag="h5"
                    align="center"
                    @click="">
                    <template #textButton>
                        CLICK
                    </template>
                    </SurvivalTextFrame>
                </div>
                <div class="stall-wrapper-hide">
                    <img class="stall-hide" src="/SurvivalGuide/hide-mask-group.png" alt="hide">
                
                </div>

                <!-------------------------- 遊戲 區塊 ---------------------------->
                <div class="game-wrapper-dice" @mouseenter="isHover='game-dice'" @mouseleave="isHover= null"
                     :class="{'nm-is-active': isHover =='game-dice'}", >
                     <img class="game-dice" src="/SurvivalGuide/dice-mask-group.png" alt="game-dice">
                     <SurvivalTextFrame class="text-frame-game-dice"
                    :text="diceFrame.text"
                    :width="diceFrame.width"
                    :height="diceFrame.height"
                    tag="h5"
                    align="center"
                    @click="">
                    <template #textButton>
                        CLICK
                    </template>
                    </SurvivalTextFrame>
                </div> 
        
                <div class="game-wrapper-prawning" @mouseenter="isHover='game-prawning'" @mouseleave="isHover= null"
                     :class="{'nm-is-active': isHover =='game-prawning'}", >
                     <img class="game-prawning" src="/SurvivalGuide/prawning-mask-group.png" alt="game-prawning">

                    <SurvivalTextFrame class="text-frame-game-prawning"
                    :text="prawningFrame.text"
                    :width="prawningFrame.width"
                    :height="prawningFrame.height"
                    tag="h5"
                    align="center"
                    @click="">
                    <template #textButton>
                        CLICK
                    </template>
                    </SurvivalTextFrame>                    
                </div> 
                
                <div class="game-wrapper-ring-toss" @mouseenter="isHover='game-ring-toss'" @mouseleave="isHover= null"
                     :class="{'nm-is-active': isHover =='game-ring-toss'}", >
                     <img class="game-ring-toss" src="/SurvivalGuide/ring-toss-mask-group.png" alt="game-ring-toss">

                    <SurvivalTextFrame class="text-frame-game-ring-toss"
                    :text="ringFrame.text"
                    :width="ringFrame.width"
                    :height="ringFrame.height"
                    tag="h5"
                    align="center"
                    @click="">
                    <template #textButton>
                        CLICK
                    </template>
                    </SurvivalTextFrame>  
                </div> 
            </div>
        </main>
</template>


<style scoped lang="scss">

// map 的區塊設定
.survival-night-market-case {
    background-color: #000;
    width: 100%; 
    height: auto;
    position: relative;
    overflow: auto;
}

.survival-night-market-case-wrapper {
    position: relative;
    width: 100%;
    height: auto;
    margin: 0;    
    padding: 0;

    display: block;
}

.survival-night-market-case-map {
    position: relative;
    width: 100%;

    height: auto;
    display: block;
    // z-index: 0;
}

// ===================== 滷肉飯 攤位的區塊 ===================== 
.stall-wrapper-pork-rice {
    position: absolute;
    width: 8.75%;
    top: 14.1%;
    right: 24.6%;

    z-index: 100;  
    cursor: pointer;

    &:hover {

        z-index: 200; 

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
}

// ===================== 珍珠奶茶 攤位的區塊 ===================== 

.stall-wrapper-bubble-tea {
    position: absolute;
    width: 9.25%;
    top: 6.1%;
    left: 40.25%;

    z-index: 100;  
    cursor: pointer;

    &:hover {

        z-index: 200; 

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
}
// ===================== 臭豆腐 攤位的區塊 ===================== 

.stall-wrapper-sticky-tofu {
    position: absolute;
    width: 9.2%;
    top: 19%;
    left: 21.2%;

    z-index: 100;  
    cursor: pointer;

    &:hover {

        z-index: 200; 

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
}

// ===================== 雞排 攤位的區塊 ===================== 

.stall-wrapper-chicken {
    position: absolute;
    width: 9.2%;
    top: 27.4%;
    left: 12.8%;

    z-index: 100;  
    cursor: pointer;


    &:hover {

        z-index: 200; 

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
}


// ===================== 大腸包小腸 攤位的區塊 ===================== 

.stall-wrapper-sausage {
    position: absolute;
    width: 9.8%;
    top: 9%;
    right: 34%;

    z-index: 100;  
    cursor: pointer;


    &:hover {

        z-index: 200; 

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
}

// ===================== 蔥油餅 攤位的區塊 ===================== 

.stall-wrapper-oil {
    position: absolute;
    width: 9.5%;
    top: 22.2%;
    right: 14.7%;

    z-index: 100;  
    cursor: pointer;


    &:hover {

        z-index: 200; 

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
}

// ===================== 共同 的區塊 ===================== 

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

        z-index: 200;

        .game-dice {
            
            transform: scale(1.025);

            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }   
} 
.text-frame-game-dice {
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

.game-wrapper-dice:hover .text-frame-game-dice{
    opacity: 1;     
    pointer-events: auto; 
    transform: translate(100%, -160%);
}

// ===================== prawing 遊戲的區塊 ===================
.game-wrapper-prawning {
    position: absolute;
    width: 31.1%;
    bottom: 11%;
    left: 13.3%;

    z-index: 100;  
    cursor: pointer;


    &:hover {

        z-index: 200;

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
}

// ===================== ring-toss 遊戲的區塊 =================
.game-wrapper-ring-toss {
    position: absolute;
    width: 25.5%;
    bottom: 23.1%;
    left: 45.6%;    

    z-index: 100;  
    cursor: pointer;


    &:hover {

        z-index: 200;

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
    left: 38%;
    bottom: -60%;
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
}

</style>
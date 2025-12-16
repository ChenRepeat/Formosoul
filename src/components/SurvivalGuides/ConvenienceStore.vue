<script setup>
import { ref, onMounted, computed, onUnmounted } from 'vue'
import SurvivalTextFrame  from "@/components/SurvivalTextFrame.vue";
import SurvivalCSIntroductionFrame from "@/components/SurvivalCSIntroductionFrame.vue";
import  { csIntroFrame  } from "@/components/SurvivalGuides/convenienceStoreData.js";
import { useRouter } from "vue-router";


const isShow = ref(-1);
const animationWelcome = ref(false)

const isHover = ref(null);

const csIntroFrames = ref(csIntroFrame);

const csFrame = ref([
    {
    id:'welcome',
    text:'Welcome!',
    width:'200px',
    height:'auto',
    },
])
const welcomeFrame = computed(()=> csFrame.value[0])

onMounted(()=>{
    isShow.value = 10;

    setTimeout(()=>{
        animationWelcome.value=true;
    },50)
})

function closeWelcomeFrame (){
    isShow.value = -1
}


</script>

<template>
        <!-- z-index= 數字  這邊寫成動態style是用物件方法寫 isShow其實是值，一開始就是-1 -->
        <main class="survival-convenience-store-case">
            <div class="survival-convenience-store-case-wrapper" @click="closeWelcomeFrame">
                <img class='survival-convenience-store-case-bg' src="/SurvivalGuide/ConvenienceStore/convenienceStore-bg-min.png" alt="cs-base">

                <SurvivalTextFrame class="welcome-text-frame"
                :style="{ zIndex: isShow}"  
                :class="{ 'is-visible': animationWelcome }" 
                :text="welcomeFrame.text"
                :width="welcomeFrame.width"
                :height="welcomeFrame.height"
                tag="h4"
                align="center"
                >
                <template #textButton>
                    START
                </template>
                </SurvivalTextFrame>

<!---------------------------------------- 麥香茶-------------------------------------------->                
                <div class="mai-tea cs-img"
                @mouseenter="isHover = 'maitea'"
                @mouseleave="isHover = null"
                     :class="{'cs-is-active': isHover =='maitea'}">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group.png" alt="">
                     
                <!-- <SurvivalCSIntroductionFrame class="text-frame-mai-tea cs-text"
                :text="csIntroFrames[0].text"  
                :width="csIntroFrames[0].width" 
                :height="csIntroFrames[0].height"
                tag="h5"
                align="center"
                ></SurvivalCSIntroductionFrame> -->
                </div>
<!---------------------------------------- 津津蘆筍汁 -------------------------------------------->   
                <div class="jin-jin cs-img"
                @mouseenter="isHover = 'jinjin'"
                @mouseleave="isHover = null"
                     :class="{'cs-is-active': isHover =='jinjin'}">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group2.png" alt="">
                     
                <!-- <SurvivalCSIntroductionFrame class="text-frame-mai-tea cs-text"
                :text="csIntroFrames[0].text"  
                :width="csIntroFrames[0].width" 
                :height="csIntroFrames[0].height"
                tag="h5"
                align="center"              
                ></SurvivalCSIntroductionFrame> -->
                </div>
<!---------------------------------------- 三點一刻 -------------------------------------------->   
                <div class="three-one cs-img"
                @mouseenter="isHover = 'threeone'"
                @mouseleave="isHover = null"
                     :class="{'cs-is-active': isHover =='threeone'}">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group3.png" alt="">
                     
                <!-- <SurvivalCSIntroductionFrame class="text-frame-mai-tea cs-text"
                :text="csIntroFrames[0].text"  
                :width="csIntroFrames[0].width" 
                :height="csIntroFrames[0].height"
                tag="h5"
                align="center"              
                ></SurvivalCSIntroductionFrame> -->
                </div>                
<!---------------------------------------- 台灣啤酒 -------------------------------------------->   
                <div class="tw-beer cs-img"
                @mouseenter="isHover = 'twbeer'"
                @mouseleave="isHover = null"
                     :class="{'cs-is-active': isHover =='twbeer'}">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group4.png" alt="">
                     
                <!-- <SurvivalCSIntroductionFrame class="text-frame-mai-tea cs-text"
                :text="csIntroFrames[0].text"  
                :width="csIntroFrames[0].width" 
                :height="csIntroFrames[0].height"
                tag="h5"
                align="center"              
                ></SurvivalCSIntroductionFrame> -->
                </div>    
<!---------------------------------------- 茶葉蛋 -------------------------------------------->   
                <div class="tw-egg cs-img"
                @mouseenter="isHover = 'twegg'"
                @mouseleave="isHover = null"
                     :class="{'cs-is-active': isHover =='twegg'}">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group5.png" alt="">
                     
                <!-- <SurvivalCSIntroductionFrame class="text-frame-mai-tea cs-text"
                :text="csIntroFrames[0].text"  
                :width="csIntroFrames[0].width" 
                :height="csIntroFrames[0].height"
                tag="h5"
                align="center"              
                ></SurvivalCSIntroductionFrame> -->
                </div>  
<!---------------------------------------- 滿漢大餐 -------------------------------------------->   
                <div class="man-han cs-img"
                @mouseenter="isHover = 'manhan'"
                @mouseleave="isHover = null"
                     :class="{'cs-is-active': isHover =='manhan'}">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group6.png" alt="">
                     
                <!-- <SurvivalCSIntroductionFrame class="text-frame-mai-tea cs-text"
                :text="csIntroFrames[0].text"  
                :width="csIntroFrames[0].width" 
                :height="csIntroFrames[0].height"
                tag="h5"
                align="center"              
                ></SurvivalCSIntroductionFrame> -->
                </div>   
<!---------------------------------------- 乖乖 -------------------------------------------->   
                <div class="kuai-kuai cs-img"
                @mouseenter="isHover = 'kuaikuai'"
                @mouseleave="isHover = null"
                     :class="{'cs-is-active': isHover =='kuaikuai'}">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group7.png" alt="">
                     
                <!-- <SurvivalCSIntroductionFrame class="text-frame-mai-tea cs-text"
                :text="csIntroFrames[0].text"  
                :width="csIntroFrames[0].width" 
                :height="csIntroFrames[0].height"
                tag="h5"
                align="center"              
                ></SurvivalCSIntroductionFrame> -->
                </div>  
<!---------------------------------------- 義美小泡芙 -------------------------------------------->   
                <div class="puffs cs-img"
                @mouseenter="isHover = 'puffs'"
                @mouseleave="isHover = null"
                     :class="{'cs-is-active': isHover =='puffs'}">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group8.png" alt="">
                     
                <!-- <SurvivalCSIntroductionFrame class="text-frame-mai-tea cs-text"
                :text="csIntroFrames[0].text"  
                :width="csIntroFrames[0].width" 
                :height="csIntroFrames[0].height"
                tag="h5"
                align="center"              
                ></SurvivalCSIntroductionFrame> -->
                </div>  
<!---------------------------------------- 維力炸醬麵 -------------------------------------------->   
                <div class="tw-noodle cs-img"
                @mouseenter="isHover = 'twnoodle'"
                @mouseleave="isHover = null"
                     :class="{'cs-is-active': isHover =='twnoodle'}">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group9.png" alt="">
                     
                <!-- <SurvivalCSIntroductionFrame class="text-frame-mai-tea cs-text"
                :text="csIntroFrames[0].text"  
                :width="csIntroFrames[0].width" 
                :height="csIntroFrames[0].height"
                tag="h5"
                align="center"              
                ></SurvivalCSIntroductionFrame> -->
                </div>  
<!---------------------------------------- 新貴派 -------------------------------------------->   
                <div class="tw-pie cs-img"
                @mouseenter="isHover = 'twpie'"
                @mouseleave="isHover = null"
                     :class="{'cs-is-active': isHover =='twpie'}">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group10.png" alt="">
                     
                <!-- <SurvivalCSIntroductionFrame class="text-frame-mai-tea cs-text"
                :text="csIntroFrames[0].text"  
                :width="csIntroFrames[0].width" 
                :height="csIntroFrames[0].height"
                tag="h5"
                align="center"              
                ></SurvivalCSIntroductionFrame> -->
                </div>  
<!---------------------------------------- 蝦味先-------------------------------------------->   
                <div class="tw-chips cs-img"
                @mouseenter="isHover = 'twchips'"
                @mouseleave="isHover = null"
                     :class="{'cs-is-active': isHover =='twchips'}">
                     <img src="/SurvivalGuide/ConvenienceStore/Mask group11.png" alt="">
                     
                <!-- <SurvivalCSIntroductionFrame class="text-frame-mai-tea cs-text"
                :text="csIntroFrames[0].text"  
                :width="csIntroFrames[0].width" 
                :height="csIntroFrames[0].height"
                tag="h5"
                align="center"              
                ></SurvivalCSIntroductionFrame> -->
                </div>  

            </div>
        </main>

</template>


<style scoped lang="scss">

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
    height: auto; // 【關鍵】把 100% 改成 auto
    display: block;
}

// 
.welcome-text-frame {
    position: absolute;
    left: 29%;
    top: 33%;

    // 1. 設置過渡效果 (Transition)
    // 讓 opacity 和 transform 屬性的變化有 1 秒的動畫時間
    // transition 可以用來簡寫多個過渡效果 = 過程，決定了元素從一個狀態（例如 A）到另一個狀態（例如 B）的改變如何發生（花費多少時間、速度曲線
    // transform 屬性改變時（即位置或大小的變化） = 結果，外觀或位置如何改變
    transition: opacity 1s ease-out, transform 1s ease-out; 
    
    // 2. 初始隱藏狀態 (元件渲染時的狀態)
    opacity: 0; // 完全透明
    transform: translateY(20px); // 初始位置在下方 20px
} 

// 3. 最終顯示狀態 (當 animateState 為 true 時)
.welcome-text-frame.is-visible {
    opacity: 1; // 完全不透明
    transform: translateY(0); // 浮到原本的位置
}

.cs-img {
    position: absolute;
    transition: all 0.3s ease-in-out;
    filter: drop-shadow(0 5px 5px rgba(0,0,0,0.3));
    cursor: pointer;

&.cs-img:hover {
    transform: scale(1.025);
    filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
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



.cs-is-active {

}

.cs-is-inactive {

}

</style>
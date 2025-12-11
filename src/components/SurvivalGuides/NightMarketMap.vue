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
    // index 0
    id: 'rice',
    text: 'Food: \n Braised Pork Rice',
    width: '200px',
    height: 'auto',
    },
    {
    // index 1
    id: 'tofu',
    text: 'Food: \n Sticky tofu',    
    width: '200px',
    height: 'auto',  
    },
    {
    // index 2
    id: 'bubble',
    text: 'Food: \n Bubble tea',
    width: '200px',
    height: 'auto',   
    },
    {
    // index 3
    id: 'sausage',
    text: 'Food: \n Taiwanese sausage with sticky rice',    
    width: '200px',
    height: 'auto',       
    },
    {
    // index 4
    id: 'oil',
    text: 'Food: \n Scallion pancake',
    width: '200px',
    height: 'auto',    
    },
    {
    // index 5
    id: 'fried-chicken',
    text: 'Food: \n Fried chicken cutlet',    
    width: '200px',
    height: 'auto',          
    },
    {
    // index 6
    id: 'game-dice',
    text: 'Game: \n Dice',    
    width: '200px',
    height: 'auto',          
    },
    {
    // index 7
    id: 'game-prawning',
    text: 'Game: \n Prawning',    
    width: '200px',
    height: 'auto',          
    },
    {
    // index 8
    id: 'game-ring-toss',
    text: 'Game: \n Ring toss',    
    width: '200px',
    height: 'auto',          
    },    
])

// ==================== 引用 food introduction frames 資料 ==================== 

const currentFoodData = ref(null);

const nmfFrames = ref([
    {
    id:'food-introduction-rice',
    mainImg: '/SurvivalGuide/rice1.jpg',
    title: 'Braised Pork Rice',
    subTitle: '(Lu Rou Fan)',
    text:'Lu rou fan, or braised pork rice, is a popular Taiwanese comfort food made from minced or finely chopped pork belly slowly braised in a savory and slightly sweet soy sauce–based gravy with garlic, shallots, five-spice powder, and Shaoxing rice wine. The pork is cooked until tender and flavorful, then served over hot steamed white rice, creating a rich and satisfying meal loved across Taiwan. This dish is simple yet deeply aromatic, with a balance of salty, sweet, and umami flavors that make it a staple in Taiwanese cuisine.',
    subImg1: '/SurvivalGuide/rice2.jpg',
    subImg2:'/SurvivalGuide/rice3.jpg',
    },
    {
    id:'food-introduction-tofu',
    mainImg: '/SurvivalGuide/tofu1.jpg',
    title: 'Sticky Tofu',
    subTitle: '(Chou Dou Fu)',
    text:"Stinky Tofu is one of Taiwan's most iconic and famous street foods, known for its unique characteristic: 'the stronger the smell, the better the taste.' Made from fermented bean curd, it is typically deep-fried until the skin is golden and crispy, while the inside remains soft and juicy. It is best enjoyed with a side of sweet and sour pickled cabbage and a splash of garlic soy sauce. For travelers, trying Stinky Tofu is the ultimate challenge and a true taste of Taiwanese night market culture.",
    subImg1: '/SurvivalGuide/tofu2.jpg',
    subImg2:'/SurvivalGuide/tofu3.jpg',
    },
    {
    id:'food-introduction-bubble-tea',
    mainImg: '/SurvivalGuide/bubble_tea1.png',
    title: 'Bubble Tea',
    subTitle: '(Boba Tea)',
    text:"Bubble Tea, also known as Pearl Milk Tea, is Taiwan's most iconic beverage that has taken the world by storm. It perfectly blends rich, creamy milk tea with chewy tapioca pearls, known locally as 'boba.' The unique combination of the smooth tea and the springy texture of the pearls creates a delightful experience in every sip. Whether served ice-cold or warm, it is the ultimate symbol of Taiwanese innovations in tea culture.",
    subImg1: '/SurvivalGuide/bubble_tea2.jpg',
    subImg2:'/SurvivalGuide/bubble_tea3.jpg',
    },
    {
    id:'food-introduction-sausage',
    mainImg: '/SurvivalGuide/sausage1.jpg',
    title: 'Taiwanese Sausage With Sticky Rice',
    subTitle: '(Taiwanese Hot Dog)',
    text:"Known as the 'Taiwanese Hot Dog,' this snack features a juicy pork sausage wrapped inside a chewy sticky rice 'bun.' Stuffed with garlic, pickled vegetables, and soy paste, it delivers a perfect mix of textures—crispy skin, tender meat, and soft rice—creating a unique savory and sweet flavor.",
    subImg1: '/SurvivalGuide/sausage2.jpg',
    subImg2:'/SurvivalGuide/sausage3.jpg',
    },
    {
    id:'food-introduction-oil',
    mainImg: '/SurvivalGuide/oil1.jpg',
    title: 'Scallion Pancake',
    subTitle: '(Green Onion Pancake)',
    text:"Scallion Pancakes are a beloved Taiwanese street snack known for their crispy, flaky layers. Made from dough folded with oil and fresh green onions, they are pan-fried until golden brown. Whether eaten plain or topped with an egg and soy paste, they offer a savory, aromatic bite with a satisfyingly chewy texture.",
    subImg1: '/SurvivalGuide/oil2.jpg',
    subImg2:'/SurvivalGuide/oil3.jpg',
    },
    {
    id:'food-introduction-chicken',
    mainImg: '/SurvivalGuide/chicken1.jpg',
    title: 'Fried Chicken Cutlet',
    subTitle: '(Taiwanese Fried Chicken)',
    text:"Famous for its massive size—often bigger than a human face—the Taiwanese Fried Chicken Cutlet is the king of night market snacks. The chicken breast is flattened, battered, and deep-fried to golden perfection. Seasoned generously with salt, pepper, and five-spice powder, it creates a crispy outer shell while keeping the meat incredibly juicy and tender inside.",
    subImg1: '/SurvivalGuide/chicken2.jpg',
    subImg2:'/SurvivalGuide/chicken3.jpg',
    },
    {
    id:'game-introduction-dice',
    mainImg: '/SurvivalGuide/dice1.png',
    title: 'Taiwanese Dice Game',
    subTitle: '(Si-Ba-La)',
    text:"Ready to test your luck? 'Si-Ba-La' is the loudest and most exciting game in the night market! The rules are simple: throw three dice into the bowl and try to beat the stall owner's score. If your points are higher, you win a delicious sausage! But watch out—competition is fierce. Are you ready to challenge the boss?",
    subImg1: '/SurvivalGuide/dice2.png',
    subImg2:'/SurvivalGuide/dice3.png',
    buttonText: 'START GAME'
    },
    {
    id:'game-introduction-dice',
    mainImg: '/SurvivalGuide/prawning1.png',
    title: 'Prwaning',
    subTitle: '(Shrimp Fishing)',
    text:"Looking for a unique late-night challenge? Indoor shrimp fishing is a beloved Taiwanese pastime that tests your patience and skill. You'll sit around a large pool with a small rod, waiting for clever freshwater prawns to take the bait. The reward? In real life, you get to grill your fresh catch right on the spot! Do you have the steady hand and patience to hook a big one right now?",
    subImg1: '/SurvivalGuide/prawning2.png',
    subImg2:'/SurvivalGuide/prawning3.jpg',
    buttonText: 'START GAME'
    },
    {
    id:'game-introduction-ring-toss',
    mainImg: '/SurvivalGuide/ringtoss1.png',
    title: 'Ring Toss',
    subTitle: '(Hoop Toss)',
    text:"Step right up to the ultimate test of aim and precision! Ring Toss is a legendary night market challenge where things are harder than they look. Your goal? Land a plastic ring around the neck of a bottle or a figurine to win it. It sounds easy, but those rings love to bounce away! Do you have the magic touch to claim the big prize? Line up your shot and let it fly!",
    subImg1: '/SurvivalGuide/ringtoss2.png',
    subImg2:'/SurvivalGuide/ringtoss3.jpg',
    buttonText: 'START GAME'
    },
])

// 只要傳入 index (第幾個食物)，它就會自動去抓上面的資料
// 你給我一個號碼（index），我就去倉庫（nmfFrames）把對應的資料拿出來，放到電視螢幕（currentFoodData）上播放
const openModal = (index) => {
    if (nmfFrames.value[index]) {
        currentFoodData.value = nmfFrames.value[index];
        // 左邊current這變數是接收到物件
    }
}

const startGamePlay = () => {
    console.log("遊戲開始！跳轉頁面或執行邏輯...");
    // router.push('/game/dice'); // 例如跳轉頁面
}


</script>

<template>
        <main class="survival-night-market-case">
            <div class="survival-night-market-case-wrapper">
                <img class='survival-night-market-case-map' src="/SurvivalGuide/night_market_map_bg-min-no-logo.png" alt="map-base">

<!-------------------------------------- 夜市美食區塊 -------------------------------------------->
<!---------------------------------------- 滷肉飯-------------------------------------------->
                <div class="stall-wrapper-pork-rice" @mouseenter="isHover = 'pork'" @mouseleave="isHover = null" 
                    :class="{'nm-is-active': isHover == 'pork',}">
                    <img class="stall-pork-rice" src="/SurvivalGuide/rice-mask-group.png" alt="braised-pork-stall">
                    
                    <!-- [] 是指從陣列裡面拿東西 ， ()是指執行，傳遞參數 -->
                    <SurvivalTextFrame class='text-frame-rice' 
                        :text="nmFrames[0].text"  
                        :width="nmFrames[0].width" 
                        :height="nmFrames[0].height"
                        tag="h5"
                        align="center"
                        @click="openModal(0)">
                    <template #textButton>
                        CLICK
                    </template>
                    </SurvivalTextFrame>
                </div>
<!---------------------------------------- 臭豆腐 -------------------------------------------->
                <div class="stall-wrapper-sticky-tofu" @mouseenter="isHover='tofu'" @mouseleave="isHover = null"
                     :class="{'nm-is-active': isHover == 'tofu'}">
                    <img class="stall-sticky-tofu" src="/SurvivalGuide/tofu-mask-group.png" alt="sticky-tofu">

                    <SurvivalTextFrame class="text-frame-tofu"
                    :text="nmFrames[1].text"
                    :width="nmFrames[1].width"
                    :height="nmFrames[1].height"
                    tag="h5"
                    align="center"
                    @click="openModal(1)"
                    >
                    <template #textButton>
                        CLICK
                    </template>    
                    </SurvivalTextFrame>                    
                </div>
<!---------------------------------------- 珍珠奶茶 -------------------------------------------->
                <div class="stall-wrapper-bubble-tea" @mouseenter="isHover = 'bubble'" @mouseleave="isHover = null"
                     :class="{'nm-is-active': isHover == 'bubble' }">
                    <img class="stall-bubble-tea" src="/SurvivalGuide/bubble-mask-group.png" alt="bubble-tea">

                    <SurvivalTextFrame class="text-frame-bubble"
                        :text="nmFrames[2].text"
                        :width="nmFrames[2].width"
                        :height="nmFrames[2].height"
                        tag= "h5"
                        align="center"
                        @click="openModal(2)">
                    <template #textButton>
                        CLICK
                    </template>
                    </SurvivalTextFrame>
                </div>
<!---------------------------------------- 大腸包小腸 -------------------------------------------->
                <div class="stall-wrapper-sausage" @mouseenter="isHover = 'sausage'" @mouseleave="isHover = null"
                    :class="{'nm-is-active':ishover == 'sausage'}">
                    <img class="stall-sausage" src="/SurvivalGuide/sausage-mask-group.png" alt="sausage">
                
                    <SurvivalTextFrame class="text-frame-sausage"
                    :text="nmFrames[3].text"
                    :width="nmFrames[3].width"
                    :height="nmFrames[3].height"
                    tag="h5"
                    align="center"
                    @click="openModal(3)"
                    >
                    <template #textButton>
                        CLICK
                    </template>    
                    </SurvivalTextFrame>
                </div>
<!---------------------------------------- 蔥油餅 -------------------------------------------->
                <div class="stall-wrapper-oil" @mouseenter="isHover= 'oil'" @mouseleave = "isHover = null"
                    :class="{'nm-is-active': isHover =='oil'}", >
                    <img class="stall-oil" src="/SurvivalGuide/oil-mask-group.png" alt="oil">

                    <SurvivalTextFrame class="text-frame-oil"
                    :text="nmFrames[4].text"
                    :width="nmFrames[4].width"
                    :height="nmFrames[4].height"
                    tag="h5"
                    align="center"
                    @click="openModal(4)">
                    <template #textButton>
                        CLICK
                    </template>
                    </SurvivalTextFrame>
                </div>
<!---------------------------------------- 雞排 -------------------------------------------->
                <div class="stall-wrapper-chicken" @mouseenter="isHover='chicken'" @mouseleave="isHover = null"
                     :class="{'nm-is-active': isHover == 'chicken'}">    
                    <img class="stall-chicken" src="/SurvivalGuide/chicken-mask-group.png" alt="fried-chicken">

                    <SurvivalTextFrame class="text-frame-chicken"
                    :text="nmFrames[5].text"
                    :width="nmFrames[5].width"
                    :height="nmFrames[5].height"
                    tag="h5"
                    align="center"
                    @click="openModal(5)"
                    >
                    <template #textButton>
                        CLICK
                    </template>    
                    </SurvivalTextFrame>                     
            
                </div>

                <div class="stall-wrapper-hide">
                    <img class="stall-hide" src="/SurvivalGuide/hide-mask-group.png" alt="hide">
                
                </div>

<!---------------------------------------- 遊戲 區塊 -------------------------------------------->
<!---------------------------------------- 骰子 -------------------------------------------->
                <div class="game-wrapper-dice" @mouseenter="isHover='game-dice'" @mouseleave="isHover= null"
                     :class="{'nm-is-active': isHover =='game-dice'}", >
                     <img class="game-dice" src="/SurvivalGuide/dice-mask-group.png" alt="game-dice">
                     <SurvivalTextFrame class="text-frame-game-dice"
                    :text="nmFrames[6].text"
                    :width="nmFrames[6].width"
                    :height="nmFrames[6].height"
                    tag="h5"
                    align="center"
                    @click="openModal(6)"
                    >
                    <template #textButton>
                        CLICK
                    </template>
                    </SurvivalTextFrame>
                </div> 
<!---------------------------------------- 釣蝦 -------------------------------------------->        
                <div class="game-wrapper-prawning" @mouseenter="isHover='game-prawning'" @mouseleave="isHover= null"
                     :class="{'nm-is-active': isHover =='game-prawning'}", >
                     <img class="game-prawning" src="/SurvivalGuide/prawning-mask-group.png" alt="game-prawning">

                    <SurvivalTextFrame class="text-frame-game-prawning"
                    :text="nmFrames[7].text"
                    :width="nmFrames[7].width"
                    :height="nmFrames[7].height"
                    tag="h5"
                    align="center"
                    @click="openModal(7)">
                    <template #textButton>
                        CLICK
                    </template>
                    </SurvivalTextFrame>                    
                </div> 
<!---------------------------------------- 套圈圈 -------------------------------------------->
                <div class="game-wrapper-ring-toss" @mouseenter="isHover='game-ring-toss'" @mouseleave="isHover= null"
                     :class="{'nm-is-active': isHover =='game-ring-toss'}", >
                     <img class="game-ring-toss" src="/SurvivalGuide/ring-toss-mask-group.png" alt="game-ring-toss">

                    <SurvivalTextFrame class="text-frame-game-ring-toss"
                    :text="nmFrames[8].text"
                    :width="nmFrames[8].width"
                    :height="nmFrames[8].height"
                    tag="h5"
                    align="center"
                    @click="openModal(8)">
                    <template #textButton>
                        CLICK
                    </template>
                    </SurvivalTextFrame>  
                </div> 
<!---------------------------------------- 共用部分 -------------------------------------------->
<!-- currentFoodData 是一整個「背包」，而 .mainImg 是伸手進去拿「背包裡的相機」, .是的或是裡面的意思，拿取 currentFoodData 這個袋子 裡面的 mainImg 那樣東西-->
                <div v-if="currentFoodData">
                    <SurvivalFoodIntroductionFrame 
                    @close="currentFoodData = null"
                    @play="startGamePlay"
                    :mainImg="currentFoodData.mainImg"
                    :title="currentFoodData.title"
                    :subTitle="currentFoodData.subTitle"
                    :text="currentFoodData.text"
                    :subImg1="currentFoodData.subImg1"
                    :subImg2="currentFoodData.subImg2"
                    :buttonText="currentFoodData.buttonText"
                    ></SurvivalFoodIntroductionFrame>
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
}

</style>
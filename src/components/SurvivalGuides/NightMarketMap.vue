<script setup>
    import SurvivalTextFrame  from "@/components/SurvivalTextFrame.vue";
    import { ref, onMounted, onUnmounted, computed } from 'vue'
    import { useRouter } from "vue-router";


</script>

<template>
        <main class="survival-night-market-case">
            <div class="survival-night-market-case-wrapper">
                <img class='survival-night-market-case-map' src="/SurvivalGuide/night_market_map_bg-min.png" alt="map-base">

                <!-------------------------- 夜市美食區塊 ---------------------------->
                <div class="stall-wrapper-pork-rice">
                    <img class="stall-pork-rice" src="/SurvivalGuide/rice-mask-group.png" alt="braised-pork-stall">
                    
                    <div class="popup-info-braised-prok-rice">
                        <!-- <SurvivalTextFrame class='text-frame-rice' 
                            v-show="isHover == 'left'" 
                            :text="leftFrame.text" 
                            :width="leftFrame.width" 
                            :height="leftFrame.height"
                            @click="goNightMarketMap"
                            /> -->
                    </div>
                </div>
                <div class="stall-wrapper-bubble-tea">
                    <img class="stall-bubble-tea" src="/SurvivalGuide/bubble-mask-group.png" alt="bubble-tea">

                </div>
                <div class="stall-wrapper-chicken">    
                    <img class="stall-chicken" src="/SurvivalGuide/chicken-mask-group.png" alt="fried-chicken">
                
                </div>
                <div class="stall-wrapper-sticky-tofu">
                    <img class="stall-sticky-tofu" src="/SurvivalGuide/tofu-mask-group.png" alt="sticky-tofu">
                
                </div>
                <div class="stall-wrapper-sausage">
                    <img class="stall-sausage" src="/SurvivalGuide/sausage-mask-group.png" alt="sausage">
                
                </div>
                <div class="stall-wrapper-oil">
                    <img class="stall-oil" src="/SurvivalGuide/oil-mask-group.png" alt="oil">

                </div>
                <div class="stall-wrapper-hide">
                    <img class="stall-hide" src="/SurvivalGuide/hide-mask-group.png" alt="hide">
                </div> 
                

                <!-------------------------- 遊戲 區塊 ---------------------------->
                <div class="game-wrapper-dice">
                    
                </div>                
                 <div class="game-wrapper-prawning">
                    
                </div>
                <div class="game-wrapper-ring-toss">
                    
                </div>


            </div>

        </main>

</template>


<style scoped lang="scss">


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
    z-index: 0;
}


// ===================== 滷肉飯 攤位的區塊 ===================== 
.stall-wrapper-pork-rice {
    position: absolute;
    width: 8.75%;
    top: 14.1%;
    right: 24.6%;

    z-index: 10;  
    cursor: pointer;

    &:hover .stall-pork-rice {
            transform: scale(1.025);
            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }

// ===================== 珍珠奶茶 攤位的區塊 ===================== 

.stall-wrapper-bubble-tea {
    position: absolute;
    width: 9.25%;
    top: 6.1%;
    left: 40.25%;

    z-index: 10;  
    cursor: pointer;

    &:hover .stall-bubble-tea {
            transform: scale(1.025);

            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }    

// ===================== 臭豆腐 攤位的區塊 ===================== 

.stall-wrapper-sticky-tofu {
    position: absolute;
    width: 9.2%;
    top: 19%;
    left: 21.2%;

    z-index: 10;  
    cursor: pointer;

    &:hover .stall-sticky-tofu {
            transform: scale(1.025);

            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }   

// ===================== 雞排 攤位的區塊 ===================== 

.stall-wrapper-chicken {
    position: absolute;
    width: 9.2%;
    top: 27.4%;
    left: 12.8%;

    z-index: 10;  
    cursor: pointer;


    &:hover .stall-chicken {
            transform: scale(1.025);

            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }    

// ===================== 大腸包小腸 攤位的區塊 ===================== 

.stall-wrapper-sausage {
    position: absolute;
    width: 9.8%;
    top: 9%;
    right: 34%;

    z-index: 10;  
    cursor: pointer;


    &:hover .stall-sausage {
            transform: scale(1.025);

            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }    

// ===================== 蔥油餅 攤位的區塊 ===================== 

.stall-wrapper-oil {
    position: absolute;
    width: 9.5%;
    top: 22.2%;
    right: 14.7%;

    z-index: 10;  
    cursor: pointer;


    &:hover .stall-oil {
            transform: scale(1.025);

            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 1));
        }
    }    

// ===================== 共同 的區塊 ===================== 

.stall-pork-rice, 
.stall-bubble-tea, 
.stall-chicken,
.stall-sticky-tofu,
.stall-sausage,
.stall-oil {
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
    z-index: 10;  
}

.stall-hide {
    width: 100%;
    height: auto;
    display: block;
}

</style>
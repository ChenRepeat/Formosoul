<script setup>
import { defineProps, defineEmits, onMounted, onUnmounted, computed, ref } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    
    const props = defineProps({
        mainImg: {type: String, required: true},
        subImg1: {type:String, default: ''},
        subImg2: {type:String, default: ''},
        subImg3: {type:String, default: ''},
        subImg4: {type:String, default: ''},
        title:{type:String, default: ''},
        subTitle:{type:String, default: ''},
        text:{type:String, default: ''},
        buttonText: {type: String, default: ''}
    })

    // 定義一個往外傳送的訊號，叫 'close'
    const emit = defineEmits(['close', 'play'])

    // 加入 ESC 關閉 
    function escClose (e){
        // e當成keydown(視窗 鍵盤事件), 還有 keydown, keyup, keypress  
        if(e.key === 'Escape') {
            emit('close');
        }
    } 

    // 輪播
    const imgList = computed(()=>{ 
        if (!props.title) {
            return[props.mainImg];
        }
        return [
        props.mainImg, 
        props.subImg3, 
        props.subImg4, 
        props.subImg2, 
        props.subImg1]
        .filter(img => img);
    });
    const currentIndex = ref(0);
    const nextSlide = () => {
    currentIndex.value = (currentIndex.value + 1) % imgList.value.length;
    }
    const prevSlide = () => {
    currentIndex.value = (currentIndex.value - 1 + imgList.value.length) % imgList.value.length;
    }   
    let timer = null;
    const startAutoPlay = () => {
        timer = setInterval(nextSlide, 3000);
    }
    const stopAutoPlay = () => {
        clearInterval(timer);
    }


    // 監聽
    onMounted (()=>{
        window.addEventListener('keydown', escClose); // 如果有發生keydown事件，啟動 escClose的 function

        document.body.style.overflow = 'hidden'; //鎖定背景 - 將網頁最外層設為不可滾動
        if(props.title && imgList.value.length > 1){
            startAutoPlay();
        }
    })

    onUnmounted (()=>{
        window.removeEventListener('keydown', escClose)

        document.body.style.overflow = ''  // 解鎖背景 - 恢復網頁滾動
        stopAutoPlay();
    })
</script>

<template>
    <div class="food-introduction-overlay" @click="emit('close')">
        <div class="food-introduction-frame" :class="{ 'map-mode': !props.title }" @click.stop> <!-- 用.stop來阻止傳到父層，如果不寫點擊彈窗就會吃到父層遮罩的emit('close') 就會直接關閉 -->
            <div class="close-btn" ><font-awesome-icon @click="emit('close')" icon="fa-solid fa-xmark"  style="font-size:32px;"/></div>
            <div class="food-introduction-frame-left"
                @mouseenter="stopAutoPlay"
                @mouseleave="startAutoPlay"
                >
                <div class="carousel-wrapper">
                    <div v-if="!props.title" class="map-container">
                        <div class="map-wrapper">
                            <img class="base-map" :src="props.mainImg" alt="main-map">
                            <div v-if="props.subImg1" class="map-pin pin-1" @click="emit('play', 1)">
                                <img  :src="props.subImg1" alt="pin1">
                            </div>
                            <div v-if="props.subImg2" class="map-pin pin-2" @click="emit('play', 2)">
                                <img :src="props.subImg2" alt="pin2">
                            </div>
                            <div v-if="props.subImg3" class="map-pin pin-3" @click="emit('play', 3)">
                                <img :src="props.subImg3" alt="pin3">
                            </div>
                            <div v-if="props.subImg4" class="map-pin pin-4" @click="emit('play', 4)">
                                <img :src="props.subImg4" alt="pin4">
                            </div>
                        </div>
                    </div>


                    <transition v-else name="fade" mode="out-in">
                        <img :key="currentIndex" 
                             :src="imgList[currentIndex]" 
                             alt="carousel-img">
                    </transition>
                </div>

                <button v-if="props.title && imgList.length > 1" class="nav-btn prev-btn" @click="prevSlide">
                    <font-awesome-icon icon="fa-solid fa-chevron-left" />
                </button>
                <button v-if="props.title && imgList.length > 1" class="nav-btn next-btn" @click="nextSlide">
                    <font-awesome-icon icon="fa-solid fa-chevron-right" />
                </button>

                <div class="dots" v-if="props.title && imgList.length > 1">
                    <span v-for="(img, idx) in imgList" :key="idx" 
                          :class="{'active': idx === currentIndex}"
                          @click="currentIndex = idx"></span>
                </div>
            </div>
            <div v-if="props.title" class="food-introduction-text-frame">
                <h3>{{ props.title }}</h3>
                <h5>{{ props.subTitle }}</h5>
                <p>{{ props.text }}</p>

                <button
                v-if="props.buttonText"
                class="night-market-button"
                @click="emit('play')"
                >
                <p>{{ props.buttonText }}</p>
                </button>
                
            </div>
            <div v-if="props.title" class="food-introduction-frame-right">
                <img :src="props.subImg1" alt="sub-img1">
                <img :src="props.subImg2" alt="sub-img2">
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">

// ============== map api mode ====================
.map-container {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #1a1a1a;
    overflow: hidden;
}

.map-wrapper {
    position: relative;
    height: 90%;
    display: flex;

    img.base-map {
        height: 100% !important; 
        width: auto !important;  
        display: block;
    }
}

.pin-1 { width: 33.2%; top: 8.5%; left: 51.5%; }
.pin-2 { width: 20.3%; top: 34.8%; left: 22.5%; }
.pin-3 { width: 13.6%; top: 68.2%; left: 26%; }
.pin-4 { width: 11.4%; top: 38.5%; left: 61.9%; }


.map-pin {
    position: absolute;
    cursor: pointer;
    z-index: 20;
    transition: transform 0.3s ease;
    // filter: drop-shadow(0 0 10px gold);
        filter: drop-shadow(0 0 10px rgb(230, 1, 255));


    img {
        width: 100% !important;
        height: auto !important;
    }

    &:hover {
        transform: scale(1.15);
    }
}

.food-introduction-frame.map-mode {
    width: 95vw;  
    height: 70vh;
    max-width: none;
    background-color: #1a1a1a;

    .food-introduction-frame-left {
        flex: 1; 
        position: relative;
        overflow: hidden;
    }
}

.map-mode .close-btn {
    color: white;
    z-index: 100;
}
// ==========================
.food-introduction-frame {
    width: 90%;
    max-width: 1200px;
    height: 600px;
    background-color: #fff;
    z-index: 210;
    position: relative;
    border-radius: 7px;
    border: 10px solid $color-fsBlue50;
    overflow: hidden;
    display: flex;
}
.food-introduction-frame-left{
    flex: 1.5;
    height: 100%;
    position: relative; 
    background-color: #1A1A1A;
    overflow: hidden;
}
.food-introduction-frame-left img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.carousel-wrapper {
    width: 100%;
    height: 100%;
    
    img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.3);
    color: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
    transition: 0.3s;
    display: flex;
    justify-content: center;
    align-items: center;

    &:hover {
        background: rgba(0,0,0,0.7);
    }
}
.prev-btn { left: 10px; }
.next-btn { right: 10px; }

.dots {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 10;

    span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        cursor: pointer;
        
        &.active {
            background: #fff;
            transform: scale(1.2);
        }
    }
}

.food-introduction-text-frame{
    flex: 1;
    padding:20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    overflow-y: auto;
    gap: 20px;

}
.food-introduction-frame-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.food-introduction-frame-right img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

// 叉叉關閉按鈕 
.close-btn {
    width: 36px;
    height: 36px;
    cursor: pointer;
    position: absolute;
    top: 12px;
    right: 12px;
    transition: all 0.5s ease;

    display: flex;
    justify-content: center;
    align-items: center;

    // 選取 .close-btn 裡面，第一層的所有東西
    & > * {
         /* 加上兩層白色陰影，製造發光效果 */
        filter: drop-shadow(0 0 2px rgba(255, 255, 255, 0.8)) drop-shadow(0 0 4px rgba(255, 255, 255, 0.5));
    }
    &:hover {
        color: $color-fsRed;
        transform: rotate(360deg);
        transition: all .5s ease;
    }
}

// 遮罩
.food-introduction-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;

    display: flex;
    justify-content: center;
    align-items: center;
}

// 玩遊戲的btn
.night-market-button {
    color: $color-fsTitle;
    margin-top: 20px; 
    padding: 8px 40px;
    font-size: 16px;
    background-color: $color-fsGold300; 
    font-weight: bold;
    border: 1px solid transparent;
    border-radius: 10px;
    cursor: pointer;
    transition: transform 0.2s;

    &:hover {
        transform: scale(1.05); 
        background-color: $color-fsGold300 ;
    }
}

// .bg-frostedGlass {
//   backdrop-filter: blur(30px);  
// }

</style>
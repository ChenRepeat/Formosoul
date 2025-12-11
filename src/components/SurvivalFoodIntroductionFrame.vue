<script setup>
import { defineProps, defineEmits, onMounted, onUnmounted } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    
    const props = defineProps({
        mainImg: {type: String, required: true},
        subImg1: {type:String, default: ''},
        subImg2: {type:String, default: ''},
        title:{type:String, default: ''},
        subTitle:{type:String, default: ''},
        text:{type:String, default: ''},
    })

    // 定義一個往外傳送的訊號，叫 'close'
    const emit = defineEmits(['close'])

    // 加入 ESC 關閉 
    function escClose (e){
        // e當成keydown(視窗 鍵盤事件), 還有 keydown, keyup, keypress  
        if(e.key === 'Escape') {
            emit('close');
        }
    } 

    // 監聽
    onMounted (()=>{
        window.addEventListener('keydown', escClose); // 如果有發生keydown事件，啟動 escClose的 function

        document.body.style.overflow = 'hidden'; //鎖定背景 - 將網頁最外層設為不可滾動
    })

    onUnmounted (()=>{
        window.removeEventListener('keydown', escClose)

        document.body.style.overflow = ''  // 解鎖背景 - 恢復網頁滾動
    })
</script>

<template>
    <div class="food-introduction-overlay" @click="emit('close')">
        <div class="food-introduction-frame" @click.stop> <!-- 用.stop來阻止傳到父層，如果不寫點擊彈窗就會吃到父層遮罩的emit('close') 就會直接關閉 -->
            <div class="close-btn" ><font-awesome-icon @click="emit('close')" icon="fa-solid fa-xmark"  style="font-size:32px;"/></div>
            <div class="food-introduction-frame-left">
                <img :src="props.mainImg" alt="main-img">
            </div>
            <div class="food-introduction-text-frame">
                <h3>{{ props.title }}</h3>
                <h5>{{ props.subTitle }}</h5>
                <p>{{ props.text }}</p>
            </div>
            <div class="food-introduction-frame-right">
                <img :src="props.subImg1" alt="sub-img1">
                <img :src="props.subImg2" alt="sub-img2">
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">

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
}
.food-introduction-frame-left img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.food-introduction-text-frame{
    flex: 1;
    padding: 20px;
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

    &:hover {
        color: $color-fsRed;
        transform: rotate(360deg);
        transition: all 0.5s ease;
    }
}

// 遮罩
.food-introduction-overlay{
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

</style>
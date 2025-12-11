<script setup>
import SurvivalTextFrame  from "@/components/SurvivalTextFrame.vue";
import { ref, onMounted, computed } from 'vue'
import { useRouter } from "vue-router";

const isShow = ref(-1);
const animationWelcome = ref(false)

const csFrame = ref([
    {
    id:'welcome',
    text:'Welcome!',
    width:'300px',
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
                <img class='survival-convenience-store-case-bg' src="/SurvivalGuide/convenience_Store-min.png" alt="">
                <SurvivalTextFrame class="welcome-text-frame"
                :style="{ zIndex: isShow}"  
                :class="{ 'is-visible': animationWelcome }" 
                :text="welcomeFrame.text"
                :width="welcomeFrame.width"
                :height="welcomeFrame.height"
                tag="h2"
                align="center"
                >
                <template #textButton>
                    START
                </template>
                </SurvivalTextFrame>
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
}

.survival-convenience-store-case-wrapper {
    width: 100%;
    height: 100%;
    margin: 0;    
    padding: 0;
}

.survival-convenience-store-case-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    z-index: 0;
}

// 
.welcome-text-frame {
    position: relative;
    left: 25%;
    top: 35%;

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

</style>
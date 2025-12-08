<script setup>

import SurvivalTextFrame  from "@/components/SurvivalTextFrame.vue";
import TheFooter from '@/components/TheFooter.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { ref, onMounted, onUnmounted } from 'vue'

// 定義目前的hover狀態： null 沒動作，'left' = 左邊hover，'right' = 右邊 hover
const isHover = ref(null)

const imgLeft = '/SurvivalGuide/night_market_island_0.png'

const imgRight ='/SurvivalGuide/Gemini_Generated_Image_gxbta6gxbta6gxbt_noback2.png'


// 做後面漂浮的圖片
const moveimg = [
  'SurvivalGuide/lantern_image.png',
  'SurvivalGuide/taiwan_image.png',
  'SurvivalGuide/tofu_image.png',
]

const positions = ref(
  moveimg.map(() => ({
    x: Math.random() * 80,
    y: Math.random() * 80,
    vx: (Math.random() - 0.5) * 2,
    vy: (Math.random() - 0.5) * 2,
    zindex: Math.floor(Math.random() * 5)
  }))
)

let interval
onMounted(() => {
  interval = setInterval(() => {
    positions.value = positions.value.map(pos => {
      let newX = pos.x + pos.vx
      let newY = pos.y + pos.vy
      let newVx = pos.vx
      let newVy = pos.vy

      if (newX < 0 || newX > 90) newVx = -newVx
      if (newY < 0 || newY > 90) newVy = -newVy

      return {
        x: Math.max(0, Math.min(90, newX)),
        y: Math.max(0, Math.min(90, newY)),
        vx: newVx,
        vy: newVy,
        zindex: pos.zindex
      }
    })
  }, 70)
})

onUnmounted(() => clearInterval(interval))

</script>

<template>
  <main class="survival-case"> 
    <SurvivalTextFrame></SurvivalTextFrame>
    <div class="survial-moveimg">
      <img v-for="( img, index) in moveimg" :key="index" :src="img" :style="{ 
          left: positions[index].x + '%', 
          top: positions[index].y + '%',
          zindex: positions[index].z-index
        }" />
    </div>

    <header class="survival-headline-case" :class="{ 'fade-out': isHover !== null }">
      <h5 class="survival-headline-inner1-case">Welcome to survival guide！</h5>
      <p>
        Here, we'll introduce Taiwan's famous night market culture and
        convenience store culture.
      </p>
    </header>

    <section class="survival-heropics-case">
      <div class="survival-heropics-select-case" :class="{ 'fade-out': isHover !== null }">  <!-- 要有切換class才要加: -->
        <font-awesome-icon
          class="survival-arrow"
          icon="fa-solid fa-arrow-left"
        />
        <h2>Select</h2>
        <font-awesome-icon
          class="survival-arrow"
          icon="fa-solid fa-arrow-right"
        />
      </div>

      <div class="survival-heropics-left-case"
      @mouseenter="isHover = 'left'"
      @mouseleave="isHover = null"
      :class="{
        'is-active': isHover == 'left',
        'is-inactive': isHover == 'right'
      }">
        <!-- 要用上面變數的圖片，src要加bind: -->
        <img :src="imgLeft" alt="Night Market" />
        <h2 >Night Market</h2>
        
      </div>
      <div class="survival-heropics-right-case" @mouseenter="isHover = 'right'"
      @mouseleave="isHover = null"
      :class="{
        'is-active': isHover =='right',
        'is-inactive': isHover == 'left'
      }"
      >
        <img :src="imgRight" alt=""
        />
        <h2>Convenience Store</h2>
      </div>
    </section>
  </main>
  <footer></footer>
</template>

<style scoped lang="scss">

.survival-case {
  background-color: #000;  // 設定黑色背景
  min-height: 100vh;       // 【關鍵】強制撐滿整個視窗高度
  width: 100%;             // 確保寬度佔滿

  display: flex;
  flex-direction: column;
  gap: 50px;
  padding-top: 60px;
  padding-bottom: 100px;
  position: relative;
}

.survival-headline-case {
  text-align: center;
  width: 500px;
  margin: 0 auto;
  z-index: 18;
  // 動畫過渡
  transition: opacity 0.4 ease, transform 0.4s ease;
  opacity: 1;

  // disappear (vue控制這個class)
  &.fade-out{
    opacity: 0;
    transform: translate(-50%, -70%);
    pointer-events: none;
  }
  
}

.survival-headline-case {
  color: $color-fsWhite;
}

.survival-headline-inner1-case {
  font-weight: bold;
}

.survival-heropics-case {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 100px;
  position: relative;
  height: 500px;
}

// (select)text area
.survival-heropics-select-case {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  display: flex;
  z-index: 21;
  color: $color-fsWhite;
  align-items: center;
  gap: 20px;
  padding-left: 24px;
  padding-bottom: 80px;
  // 動畫過渡
  transition: opacity 0.4 ease, transform 0.4s ease;
  opacity: 1;

  // disappear (vue控制這個class)
  &.fade-out{
    opacity: 0;
    transform: translate(-50%, -70%);
    pointer-events: none;
  }
}

.survival-arrow {
  font-size: 40px;
}


// left part area
.survival-heropics-left-case {
  width: 50%;
  display: flex;
  flex-direction: column;
  color: $color-fsWhite;
  text-align: center;
  gap: 40px;
  transition: all 1s ease;
  cursor: pointer;

  img {
   width: 500px;
    height: 380px;
    margin: 0 auto;
    z-index: 20;
  }

  &.is-active {
    z-index: 20;
    transform: translateX(200px) scale(1.3);
  }


  &.is-inactive {
    opacity: 0.2;
    filter:blur(2px);
    transform: scale(0.9);
  }
}

// right part area
.survival-heropics-right-case {
  width: 50%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding-top: 50px;
  color: $color-fsWhite;
  text-align: center;
  gap: 40px;
  transition: all 1s ease;
  cursor: pointer;

  img {
    width: 450px;
    margin: 0 auto;
    z-index: 20
  }

  &.is-active {
    z-index: 20;
    transform: translate(-200px) scale(1.3);
  }

  &.is-inactive {
    opacity: 0.2;
    filter: blur(2px);
    transform: scale(0.9);
  }
}

// move img 


.survial-moveimg {
  position: absolute;
  top: -30px;
  left: -30px;
  right: -30px;
  bottom: -30px;
  // filter: blur(0px);
  z-index: 15; 
}
.survial-moveimg img {
  width: 100px;
  position: absolute;
  transform: translate(-50%, -50%);
  transition: box-shadow 0.3s ease-in-out;
}


// hover 效果 切換左右頁

// .survival-heropics-left-case:hover {
//   transform: translateX(250px);
//   scale: (1.3);
// }

// .survival-heropics-case:has(.survival-heropics-left-case:hover)
//   .survival-heropics-select-case {
//   display: none;
// }

// .survival-heropics-left-case:hover ~ .survival-heropics-right-case {
//   transform: translateX(1600px);
// }
</style>

<script setup>
import SurvivalTextFrame  from "@/components/SurvivalTextFrame.vue";
import TheFooter from '@/components/TheFooter.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useRouter } from "vue-router";


// 連結至其他頁面
const router = useRouter();


// 連結到 night market 頁面
function goNightMarketMap () {
  router.push('/survivalrules/nightmarketmap')
};

// 連結到 convenience store 頁面
function goConvenienceStore(){
  router.push('/survivalrules/conveniencestore') 
}


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


// 引用 text frames 資料
const frames = ref([
  {
    id: 'night',
    text: "Taiwan’s night markets are known for food, games. Stinky tofu, braised pork rice,  are must-tries, showing the heart of local Taiwanese culture.",
    width: "280px",
    height: "250px",
    button: "Enter",
  },
  {
    id: 'store',
    text: "Most convenience stores in Taiwan are open 24/7 and offer food, bill payments, shipping, and more—an essential part of daily life.",
    width: '280px',
    height: '250px',
    button: 'Enter'
  }
])

const leftFrame = computed(()=> frames.value[0])
const rightFrame = computed(()=> frames.value[1])

</script>

<template>
    <main class="survival-case"> 
      <div class="survival-case-wrapper">
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
            'img-is-active': isHover == 'left',
            'img-is-inactive': isHover == 'right'
          }">
            <!-- 要用上面變數的圖片，src要加bind: -->
            <img :src="imgLeft" alt="Night Market" />
            <img class="fc-img" src="/SurvivalGuide/friedChicken_br.png" alt="">
            <h2 :class="{
                'text-is-active': isHover == 'left',
            }" >Night Market</h2>
            
            <SurvivalTextFrame class='text-frame-left' 
            v-show="isHover == 'left'" 
            :text="leftFrame.text" 
            :width="leftFrame.width" 
            :height="leftFrame.height"
            @click="goNightMarketMap"
            />
          </div>


          <div class="survival-heropics-right-case" @mouseenter="isHover = 'right'"
          @mouseleave="isHover = null"
          :class="{
            'img-is-active': isHover =='right',
            'img-is-inactive': isHover == 'left'
          }"
          >
            <img :src="imgRight" alt=""
            />
            <img class="bubble-img" src="/SurvivalGuide/bubble_tea.png" alt="">
            <h2 :class="{
                'text-is-active': isHover == 'right',
            }"> Convenience Store </h2>
            <SurvivalTextFrame class='text-frame-right' 
            v-show="isHover == 'right'" 
            :text="rightFrame.text" 
            :width="rightFrame.width" 
            :height="rightFrame.height"
            @click="goConvenienceStore"
            >
              <template v-slot:textButton>
                Click
              </template>
            </SurvivalTextFrame>
          </div>
        </section>
      </div>
    </main>
  <footer></footer>
</template>

<style scoped lang="scss">

.survival-case{
  background-color: #000;
}

.survival-case-wrapper {
  max-width: 1200px;
  margin: 0 auto;  // 設定黑色背景
  min-height: 100vh;       // 強制撐滿整個視窗高度
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
  position: relative;

  img {
   width: 500px;
    height: 380px;
    margin: 0 auto;
    z-index: 20;
  }
}

.survival-heropics-left-case.img-is-active {
    z-index: 20;
    transform: translateX(200px) scale(1.3);
    
  }

.survival-heropics-left-case.img-is-inactive {
    opacity: 0.2;
    filter:blur(2px);
    transform: scale(0.9);
  }

.survival-heropics-left-case h2.text-is-active {
  text-shadow: 2px 2px 4px #000, 4px 4px 8px rgba(0, 0, 0, 0.3);
  z-index: 30;
  transform: translate(230px, -450px);
}

.survival-heropics-left-case .fc-img {
  object-fit: contain;
  width: 300px;
  height: auto;
  position: absolute;
  left: -80px;
  top: -60px;
  transform:rotate(30deg);
  opacity: 0;
  transition: all 1.2s ease-in-out;
  display: block;
}

.survival-heropics-left-case:hover .fc-img {
  opacity: 1;
  display: block;
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
  position: relative;

  img {
    width: 450px;
    margin: 0 auto;
    z-index: 20;
  }

  &.img-is-active {
    z-index: 20;
    transform: translate(-200px) scale(1.3);
  }

  &.img-is-inactive {
    opacity: 0.2;
    filter: blur(2px);
    transform: scale(0.9);
  }

  h2.text-is-active {
  text-shadow: 2px 2px 4px #000, 4px 4px 8px rgba(0, 0, 0, 0.3);
  z-index: 30;
  transform: translate(-200px, -450px);
  } 
}

.bubble-img {
  object-fit: contain;
  height: 140px;
  position: absolute;
  right: -150px;
  top: -70px;
  transform:rotate(-20deg);
  opacity: 0;
  transition: all 1.2s ease-in-out;
  display: block;
}

.survival-heropics-right-case:hover .bubble-img {
  opacity: 1;
  display: block;
}


// ================ text frame position changed ================ 
.text-frame-left {
  right: -100px;
}


.text-frame-right {
  left: -100px;
}

.text-frame-left,
.text-frame-right {
  position:absolute;
  pointer-events: none;
  bottom: 100px;
  z-index: 30;
  opacity: 0;
  transform: translateY(10px);
}

// display text frame
.survival-heropics-left-case.img-is-active .text-frame-left {
  opacity: 1;
  pointer-events: auto;
  // transform: translateY(0);
}

.survival-heropics-right-case.img-is-active .text-frame-right {
  opacity: 1;
  pointer-events: auto;
  // transform: translateY(0);
}


// ================ move img ================ 
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

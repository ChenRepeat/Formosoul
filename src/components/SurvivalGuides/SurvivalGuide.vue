<script setup>
import SurvivalTextFrame  from "@/components/SurvivalTextFrame.vue";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useRouter } from "vue-router";
import Backgroundaction from "../backgroundaction.vue";

// 增加： 進入鎖住，不要直接hover到：
const isMapReady = ref(false);

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

// =========================== 漂浮區塊 ===========================
// 圖片
// const moveimg = [
//   'SurvivalGuide/lantern_image.png',
//   'SurvivalGuide/taiwan_image.png',
//   'SurvivalGuide/tofu_image.png',
// ]

// const positions = ref(
//   moveimg.map(() => ({
//     x: Math.random() * 80,
//     y: Math.random() * 80,
//     vx: (Math.random() - 0.5) * 2,
//     vy: (Math.random() - 0.5) * 2,
//     zindex: Math.floor(Math.random() * 5)
//   }))
// )

// let interval
onMounted(() => {
  // interval = setInterval(() => {
  //   positions.value = positions.value.map(pos => {
  //     let newX = pos.x + pos.vx
  //     let newY = pos.y + pos.vy
  //     let newVx = pos.vx
  //     let newVy = pos.vy

  //     if (newX < 0 || newX > 90) newVx = -newVx
  //     if (newY < 0 || newY > 90) newVy = -newVy

  //     return {
  //       x: Math.max(0, Math.min(90, newX)),
  //       y: Math.max(0, Math.min(90, newY)),
  //       vx: newVx,
  //       vy: newVy,
  //       zindex: pos.zindex
  //     }
  //   })
  // }, 70)

  // 設定 X 秒後解鎖，讓滑鼠可以互動
  setTimeout(() => {
    isMapReady.value = true;
  }, 2000);

})

// onUnmounted(() => clearInterval(interval))


// 引用 text frames 資料
const frames = ref([
  {
    id: 'night',
    text: "Taiwan’s night markets are known for food, games. Stinky tofu, braised pork rice,  are must-tries, showing the heart of local Taiwanese culture.",
    width: "280px",
    height: "250px",
    align: 'left',
    tag: 'p',
  },
  {
    id: 'store',
    text: "Most convenience stores in Taiwan are open 24/7 and offer food, bill payments, shipping, and more—an essential part of daily life.",
    width: '280px',
    height: '250px',
  }
])

const leftFrame = computed(()=> frames.value[0])
const rightFrame = computed(()=> frames.value[1])

</script>

<template>
    <Backgroundaction></Backgroundaction>
    <main class="survival-case"> 
      <div class="survival-case-wrapper" :class="{ 'locked': !isMapReady }">
        <!-- <div class="survial-moveimg">
          <img v-for="( img, index) in moveimg" :key="index" :src="img" :style="{ 
              left: positions[index].x + '%', 
              top: positions[index].y + '%',
              zindex: positions[index].z-index
            }" />
        </div> -->

        <header class="survival-headline-case" :class="{ 'fade-out': isHover !== null }">
          <h3 class="survival-headline-inner1-case">Welcome To Survival Guide！</h3>
          <p>
            Here, we'll introduce Taiwan's famous night market culture <br> and
            convenience store culture.
          </p>
          <img class='taiwan-pic' src="/SurvivalGuide/taiwan_image.png" alt="taiwan_pic">
        </header>

        <section class="survival-heropics-case">
          <div class="survival-heropics-select-case" :class="{ 'fade-out': isHover !== null }">  <!-- 要有切換class才要加: -->
            <font-awesome-icon
              class="survival-arrow"
              icon="fa-solid fa-arrow-left"
            />
            <h5>Select</h5>
            <font-awesome-icon
              class="survival-arrow"
              icon="fa-solid fa-arrow-right"
            />
          </div>

          <div class="survival-heropics-left-case"
          @mouseenter="isHover = 'left'"
          @mouseleave="isHover = null"
          @click="isHover === null ? isHover = 'left' : isHover = null"
          :class="{
            'img-is-active': isHover == 'left',
            'img-is-inactive': isHover == 'right'
          }">
            <!-- 要用上面變數的圖片，src要加bind: -->
          <div class="survival-heropics-left-pics-group-case">
            <img class="island-base" src="/SurvivalGuide/island-base.png" alt=""/>
            <img class="vendor" src="/SurvivalGuide/vendor.png" alt=""/>
          </div>
            <img class="fc-img" :class="{ 'show-deco': isHover == 'left' }" src="/SurvivalGuide/friedChicken_br.png" alt="">
            <img class="tofu-img" :class="{ 'show-deco': isHover == 'left' }" src="/SurvivalGuide/tofu_image.png" alt="">
            <h4 :class="{'text-is-active': isHover == 'left',}" >Night Market</h4>
            
            <SurvivalTextFrame class='text-frame-left' 
            v-show="isHover == 'left'" 
            :text="leftFrame.text" 
            :width="leftFrame.width" 
            :height="leftFrame.height"
            @click="goNightMarketMap"
            />
          </div>


          <div class="survival-heropics-right-case" 
          @mouseenter="isHover = 'right'"
          @mouseleave="isHover = null"
          @click="isHover === null ? isHover = 'right' : isHover = null"
          :class="{
            'img-is-active': isHover =='right',
            'img-is-inactive': isHover == 'left'
          }"
          >
          <div class="survival-heropics-right-pics-group-case">
            <img class="island-base" src="/SurvivalGuide/island-base.png" alt=""/>
            <img class="store-building" src="/SurvivalGuide/store-building.png" alt=""/>
          </div>
            <img class="bubble-img" :class="{ 'show-deco': isHover == 'right' }" src="/SurvivalGuide/bubble_tea.png" alt="">
            <img class="lantern-img" :class="{ 'show-deco': isHover == 'right' }" src="/SurvivalGuide/lantern_image.png" alt="">
            <h4 :class="{
                'text-is-active': isHover == 'right',
            }"> Convenience Store </h4>

            <SurvivalTextFrame class='text-frame-right' 
            v-show="isHover == 'right'" 
            :text="rightFrame.text" 
            :width="rightFrame.width" 
            :height="rightFrame.height"
            @click="goConvenienceStore"
            > <!-- 等同於 element.addEventListener('click', goConvenienceStore) , 不需要傳參數 → 不加 () // 需要傳參數 → 要加 () ex. <div @click="goToPage('home')"></div>
<div @click="addItem(item.id)"></div>-->
              <!-- <template v-slot:textButton>
                Click
              </template> -->
            </SurvivalTextFrame>
          </div>
        </section>
      </div>
    </main>
</template>

<style scoped lang="scss">

.show-deco {
  opacity: 1 !important;
  display: block;
}

.survival-case{
  // background-image: url(/public/Shop/bgi.jpg);
  background-position: right;
}

.survival-case-wrapper {
  max-width: 1200px;
  margin: 0 auto; 
  min-height: 100vh;     
  width: 100%;            

  display: flex;
  flex-direction: column;
  gap: 50px;
  padding-top: 130px;
  padding-bottom: 100px;
  position: relative;
}

.survival-headline-case {
  text-align: center;
  // width: 500px;
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

// .survival-headline-inner1-case {
//   // font-weight: bold;
//   // border: 1px solid red;
// }

.survival-headline-case p {
  text-align: center;
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
  gap: 16px;
  padding-left: 24px;
  padding-bottom: 80px;

  transition: opacity 0.4 ease, transform 0.4s ease;
  opacity: 1;

  &.fade-out{
    opacity: 0;
    transform: translate(-50%, -70%);
    pointer-events: none;
  }
}

.survival-arrow {
  font-size: 24px;
}


// ============================= left part area ============================= 
.survival-heropics-left-case {
  padding-top: 60px;
  width: 50%;
  display: flex;
  flex-direction: column;
  color: $color-fsWhite;
  text-align: center;
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

.survival-heropics-left-case h4.text-is-active {
  text-shadow: 2px 2px 4px #000, 4px 4px 8px rgba(0, 0, 0, 0.3);
  z-index: 30;
  transform: translate(230px, -450px);
}

.survival-heropics-left-case:hover h4{
  font-size: 5.2rem;
}

.survival-heropics-left-case .fc-img {
  object-fit: contain;
  width: 300px;
  height: auto;
  position: absolute;
  left: -10%;
  top: 0%;
  transform:rotate(30deg);
  opacity: 0;
  transition: all 1.2s ease-in-out;
  display: block;
}

// .survival-heropics-left-case:hover .fc-img {
//   opacity: 1;
//   display: block;
// }

.survival-heropics-left-case .tofu-img {
  object-fit: contain;
  width: 80px;
  height: auto;
  position: absolute;
  left: 5%;
  top: 65%;
  opacity: 0;
  transition: all 1.2s ease-in-out;
}

// .survival-heropics-left-case:hover .tofu-img {
//   opacity: 1;
//   display: block;
// }

.survival-heropics-left-pics-group-case {
  position: relative;
  width: 450px;
  height: auto;
  margin: 0 auto;
  z-index: 20;
}

.survival-heropics-left-pics-group-case .vendor {
  position: absolute;
  width: 400px;
  height: auto;
  z-index: 2;
  top: 35px;
  left: 20px;
  animation: floatVendor 3s ease-in-out infinite;
}

@keyframes floatVendor {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-10px); } 
  100% { transform: translateY(0px); }
}

.survival-heropics-left-pics-group-case .island-base {
  position: relative;
  width: 100%;
  height: auto;
  display: block;
  z-index: 1;
}


// ============================= right part area ============================= 
.survival-heropics-right-case {
  width: 50%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding-top: 50px;
  color: $color-fsWhite;
  text-align: center;
  transition: all 1s ease;
  cursor: pointer;
  position: relative;

  img {
    width: 450px;
    height: auto;
    display: block;
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

  h4.text-is-active {
  text-shadow: 2px 2px 4px #000, 4px 4px 8px rgba(0, 0, 0, 0.3);
  z-index: 30;
  transform: translate(-200px, -500px);
  } 
}

.survival-heropics-right-case:hover h4{
  font-size: 5.2rem;
}


.survival-heropics-right-pics-group-case {
  position: relative;
  width: 450px;
  height: auto;
  margin: 0 auto;
  z-index: 20;
}

.survival-heropics-right-pics-group-case .store-building {
  position: absolute;
  width: 300px;
  height: auto;
  z-index: 2;
  top: -15px;
  left: 80px;
  animation: floatStore 3s ease-in-out infinite;
}

@keyframes floatStore {
  0% { transform: translateX(0px); }
  50% { transform: translateX(15px); } 
  100% { transform: translateX(0px); }
}

.survival-heropics-right-pics-group-case .island-base {
  position: relative;
  width: 100%;
  height: auto;
  display: block;
  z-index: 1;
}


.survival-heropics-right-case .bubble-img {
  object-fit: contain;
  height: 140px;
  position: absolute;
  right: -30%;
  top: 0%;
  transform:rotate(-20deg);
  opacity: 0;
  transition: all 1.2s ease-in-out;
  display: block;
}

// .survival-heropics-right-case:hover .bubble-img {
//   opacity: 1;
//   display: block;
// }

.survival-heropics-right-case .lantern-img {
  object-fit: contain;
  width: 80px;
  position: absolute;
  right: 0%;
  top: 65%;
  opacity: 0;
  transition: all 1.2s ease-in-out;
  display: block;
}

// .survival-heropics-right-case:hover .lantern-img {
//   opacity: 1;
//   display: block;
// }

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
}

.survival-heropics-right-case.img-is-active .text-frame-right {
  opacity: 1;
  pointer-events: auto;
}

// 新增：鎖住時的樣式
.locked {
  pointer-events: none;
}

.taiwan-pic {
  width: 60px;
}

// ================ move img ================ 
// .survial-moveimg {
//   position: absolute;
//   top: -30px;
//   left: -30px;
//   right: -30px;
//   bottom: -30px;
//   // filter: blur(0px);
//   z-index: 15; 
// }
// .survial-moveimg img {
//   width: 100px;
//   position: absolute;
//   transform: translate(-50%, -50%);
//   transition: box-shadow 0.3s ease-in-out;
// }



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

//=========================== RWD ===========================
@media (max-width: 1200px) {
  .survival-heropics-left-case.img-is-active {
    transform: translateX(100px) scale(1.2); 
  }
  .survival-heropics-right-case.img-is-active {
    transform: translateX(-100px) scale(1.2);
  }
  .survival-heropics-left-case,
  .survival-heropics-right-case {
    flex: 1; 
  }
  .survival-heropics-left-case h4.text-is-active {
    transform: translate(150px, -400px); 
  }
  .survival-heropics-right-case h4.text-is-active {
    transform: translate(-150px, -400px);
  }
}

@media (max-width: 1080px) {
  .survival-arrow {
  font-size: 20px;
  }
  .survival-heropics-select-case {
    gap: 8px;
  }
}

</style>

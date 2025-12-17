<script setup>
import { ref, onMounted, onUnmounted, nextTick, effect } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import IconWandCore from '@/components/icons/SVG/IconWandCore.vue';
import Swiper from 'swiper';
import { Autoplay, FreeMode,EffectCoverflow  } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-coverflow';


// photo
const all = [  'ProfessorIntroduction/JiutianXuannu.png',
'ProfessorIntroduction/ZhongtanYuanshuai.jpg',
'ProfessorIntroduction/GuanShengDijun.png',
'ProfessorIntroduction/GuanyinPusa.png',
'ProfessorIntroduction/TianshangShengmu.png',
'ProfessorIntroduction/ChenghuangZunshen.png',
'ProfessorIntroduction/HuyeJiangjun.png',
'ProfessorIntroduction/XuantianShangdi.png',
'ProfessorIntroduction/XuantanZhenjun.png',
'ProfessorIntroduction/FazhuShengjun.png',
'ProfessorIntroduction/YuelaoZunshen.png',
'ProfessorIntroduction/LinshuiFuren.png',
'ProfessorIntroduction/BaoshengDadi.png',
'ProfessorIntroduction/YuhuangDadi.png',
'ProfessorIntroduction/BeidouQixing.png',
'ProfessorIntroduction/NandouLiuxing.png',
  ]

//----------------ref----------------------------------
const doubleAll = ref([]) 
const refLists = ref([]) // ul s
const refCard = ref(null) // li
const cardWidth = ref(0) 
let index = 0; // 計數器

const calculateCardWidth = () => {
  // querySelector 只會抓「第一個」符合的元素
  const cardElement = refCard.value[0]
  if (cardElement) {
    // 處理 ref 物件 記得加 .value 
    cardWidth.value = cardElement.offsetWidth // offsetWidth (包含 padding + border) 
  }
  if (refCard.value && refCard.value.length > 0) {
    const cardMargin = parseInt(window.getComputedStyle( refCard.value[0]).marginLeft)
    cardWidth.value += cardMargin * 2 // 左右 margin 都要計入
  }
}
const setAllStyle = (callback) => { 
  refLists.value.forEach((ul, i) => {
    // 把這個 ul 的 style 物件傳給 callback 函式
    if (ul) callback(ul.style, i) 
  })
}
const slider = async() => {
  calculateCardWidth()
  if(!refLists.value.length) return 
  currentTranslateX.value = -index * cardWidth.value 
  setAllStyle((style, i) =>{ // 
    style.transition = `all .5s linear`;
    style.transform = `translateX(${currentTranslateX.value}px)`;
    // if(i == 1) style.transform = `translateX(${-currentTranslateX.value}px)`; // 右移
  })
  await nextTick() // 保證指令寫入DOM 動畫還未完成
  if( index == all.length ){ // 走到複製第一張 
    setTimeout(() => {
      setAllStyle(style => {
        style.transition = '0s';
        void document.body.offsetHeight; // 強迫瀏覽器現在就把高度算出來 (會阻止瀏覽器把兩個指令合併 導致動畫殘留)
        style.transform = 'translateX(0)';
      })
      index = 0 // 回到原始資料開頭
      currentTranslateX.value = 0
    }, 500)
  }
  else if( index > all.length ){ 
    setTimeout(()=>{
      setAllStyle(style => {
        style.transition = '0s';
        void document.body.offsetHeight; 
        style.transform = `translateX(${currentTranslateX.value}px)`;
      })
      index = index % all.length 
      currentTranslateX.value = -index * cardWidth.value // 
    }, 500)
  }
  // setTimeout(slideChanged,5)
} 
const move = () => {
  index ++
  slider()
}
// let timer = ref(null)
for(let i=0; i< all.length * 2; i++){
  let src = all[i % all.length]
  doubleAll.value.push(src); 
} 
const isIn = ref(Array(doubleAll.value.length*3).fill(false))
const InOrOut= (targetIndex, status)=>{ // 更新 mouseenter 的狀態
  isIn.value[targetIndex]=status // 
}
const mouseAt = ref({x:0, y:0}) // 紀錄滑鼠位置
const info = ref([]) // 未來讀 json
const professsor = ref(null)
const job = ref(null)
const skillDetail = ref(null)
const refBigPhoto =ref('')
const clickedPhoto = ref('')
const isOpen = ref(false);
const xStart = ref(0) //起點
const xNow = ref(0)
const distance = ref(0)

;(async ()=>{ //讀 json 
  let jsonFile = await fetch('ProfessorIntroduction/professorInfo.json')
  if(jsonFile){
    info.value = await jsonFile.json()
  }
})()
const openInfo = (index) => {
  if(!info.value[0]) return
  if(index > all.length - 1 ){ index = index - all.length } // 把複製資料的 index 對應到原始資料的
  if(distance.value > 5) return
  professsor.value = info.value[index].name
  job.value = info.value[index].job
  skillDetail.value = info.value[index].skill
  if (index == 0) {
    refBigPhoto.value.style.width =`auto`
    refBigPhoto.value.style.height =`100%`
  }else{
    refBigPhoto.value.style.width =`100%`
    refBigPhoto.value.style.height =`auto`
  }
  clickedPhoto.value = all[index]
  isOpen.value = true
}
const closeInfo = () => {
  isOpen.value = false
}

const isPress =ref(false) 
let originalShift = 0
const currentTranslateX = ref(0)

const onMousemove = (e) => { // mousemove 1px 呼叫一次
  // 1. 偵測滑鼠位置，slideChanged()判斷hover。
  mouseAt.value.x = e.clientX 
  mouseAt.value.y = e.clientY // 以viewport左上角為原點的座標位置
  // 2. 如果滑鼠有按下，偵測移動距離讓user拉動卡片
  if(!isPress.value)return
  if (!refLists.value.length) return;
  
  xNow.value = e.clientX // 滑鼠按下&&移動時的當下位置
  distance.value = xNow.value - xStart.value // 距離按下時的總位移

  let newTranslateX = originalShift + distance.value // 2-1. 計算理論上的新位移

  calculateCardWidth()
  const oneSetWidth = cardWidth.value * all.length

  // 2-2. 左右移動應對
    // 往右拉過第0張 -> 瞬移
    if(newTranslateX > 0){
      newTranslateX -= oneSetWidth
      // originalShift -= oneSetWidth
    } 
    // 往左拉超過原始資料尾 
    else if(newTranslateX < -oneSetWidth  ){
      newTranslateX += oneSetWidth
      // originalShift += oneSetWidth      
    }
    setAllStyle((style)=>{
      style.transform = `translateX(${newTranslateX}px)`;
    }) // 移動一次
    
    currentTranslateX.value = newTranslateX // newTranslateX 是區域變數 寫到 currentTranslateX 才可以在另一個函數用這個值
}
// const timerControl=()=>{ // 計時器控制
//   if(isPress.value){ // 滑鼠按下則停止
//     if(timer.value){
//       clearInterval(timer.value)
//       timer.value = null
//     }
//   }else if(!timer.value)
//     timer.value = setInterval(move, 500) 
// }
// const onPress = e => { 
//   isPress.value = true
//   xStart.value = e.clientX // 按下時開始追蹤起點
//   originalShift = currentTranslateX.value // 取得輪播位移變數 作為起始數值
//   timerControl() // 關閉計時器
//   if (refLists.value.length) 
//   setAllStyle(style =>{
//     style.transition = '0s'; // 關閉動畫
//   })
//   distance.value = 0 // 將移動距離歸零 以便作為click是件事發的判讀條件
// }
const offPress =()=>{
  isPress.value = false
  index = Math.round(Math.abs(currentTranslateX.value) / cardWidth.value) // 更新index 對應到最近的卡片
  
  slider() // 讓新的index 跑動畫 
  // timerControl() // 恢復自動輪播 
  if (refLists.value.length) 
  setAllStyle((style, i)=>{
    style.transition = `all .5s linear`; // 重啟動畫
  }) 
  
}
// const slideChanged =  () => { 
//   const topmostElement = document.elementFromPoint(mouseAt.value.x, mouseAt.value.y) // 離滑鼠最上層的元素 // mouseenter 卡片<li> 會回傳 <img>
//     if(!topmostElement) return
//     const closestLi = topmostElement.closest('.professor-photo-wrapper') // 找離最上層元素最近的(父層)li 
//     // 改抓 class
//     if(closestLi){ // 附近沒有 li
//     const indexNum = Number(closestLi.dataset.index)
//     isIn.value.fill(false)
//     InOrOut(indexNum, true)
//   }
// }
onMounted(() => { // DOM 生成後
    // ------------------------------------swiper 屬性---------------------------------------------

let Carousel = new Swiper(".professor-carousel-container", {
  modules:[Autoplay, FreeMode, EffectCoverflow],
  freemode:true,
  autoplay: {
    delay: 0,
    pauseOnMouseEnter: false,
  },
  centeredSlides: true,
  effect : 'coverflow',
  coverflowEffect: {
    rotate: 2,
    depth: 8,
    modifier: 1.5,
    slideShadows : true
  },
  slidesPerView: 'auto',
  speed: 1500, // FOR MARQUEE SPEED
  loop: true,
  spaceBetween: 12,
  resistance: true,
  resistanceRatio: 0,
  allowTouchMove: true,
  grabCursor: true,
});

// REVERSE MARQUEE
  // DATA SPEED
let reverseMarqueeCarousel = new Swiper(".professor-reverse-carousel-container", {
  modules:[Autoplay, FreeMode, EffectCoverflow],
  freemode:true,

  autoplay: {
    delay: 0,
    pauseOnMouseEnter: false,
    reverseDirection: true, // FOR REVERSE
  },
  centeredSlides: true,
  effect : 'coverflow',
  coverflowEffect: {
    rotate: 5,
    depth: 10,
    modifier: 1.5,
    slideShadows : true
  },
  slidesPerView: 'auto',
  speed: 1250, // FOR MARQUEE SPEED
  loop: true,
  spaceBetween: 12,
  resistance: true,
  resistanceRatio: 0,
  allowTouchMove: true,
});

  // setTimeout(slideChanged,5)

  // timer.value = setInterval(move, 500) 

  document.addEventListener('mousemove', onMousemove)
  document.addEventListener('mouseup', offPress)  
  document.addEventListener('touchstart', onMousemove)
  document.addEventListener('touchend', offPress)
})

onUnmounted(()=>{
  document.removeEventListener('mousemove', onMousemove)
  document.removeEventListener('mouseup', offPress)
  document.removeEventListener('touchstart', onMousemove)
  document.removeEventListener('touchend', offPress)

})

</script>
<template> 
  <section class="professor-page-wrapper "> 
    <!-- class 對應 變更嘗試  -->
    <h2 class="professor-title">Professor</h2>

    <!-- swiper testing -->
    <div class="carousel-field">

  
  <div class="professor-carousel-container upper" > <!-- class 對應 -->
    <ul class="professor-list swiper-wrapper " :ref='(ul)=>{if(ul) refLists[0]=ul}'
      @mousedown.prevent="onPress" >
      <li ref='refCard' class="professor-photo-wrapper swiper-slide upper"
      :class="{'mouse-enter':isIn[index]==true}" 
      v-for="(photo, index) in doubleAll" 
      :data-index="index" 
      :id="'photo'+index"           
      @mouseenter="InOrOut(index, true)" 
      @mouseleave="InOrOut(index, false)">
      <img :src='photo' class="professor-photo "
      :class="{'mouse-enter':isIn[index]==true}" 
      @click="openInfo(index)"
      >
    </li>
  </ul>   
  </div>
  
  <div class="professor-reverse-carousel-container middle">
    <ul class="professor-list swiper-wrapper" :ref='(ul)=>{if(ul) refLists[1]=ul}'  @mousedown.prevent="onPress" >
      <li ref='refCard' class="professor-photo-wrapper middle swiper-slide"
      :class="{'mouse-enter':isIn[index+ doubleAll.length]==true}" 
      v-for="(photo, index) in doubleAll" 
      :data-index="index+ doubleAll.length" 
      :id="'photo-middle'+index" 
      @mouseenter="InOrOut(index+ doubleAll.length, true)" 
      @mouseleave="InOrOut(index+ doubleAll.length, false)">
      <img :src='photo' class="professor-photo"
      :class="{'mouse-enter':isIn[index+ doubleAll.length]==true}" 
      @click="openInfo(index)"
      >
    </li>
  </ul>
</div>

<div class="professor-carousel-container lower" > 
  <ul class="professor-list swiper-wrapper"  :ref='(ul)=>{if(ul) refLists[2]=ul}'  @mousedown.prevent="onPress" >
    <li ref='refCard' class="professor-photo-wrapper lower swiper-slide"
    :class="{'mouse-enter':isIn[index+ doubleAll.length *2]==true}" 
    v-for="(photo, index) in doubleAll" 
    :data-index="index+ doubleAll.length *2" 
    :id="'photo-lower'+index"           
    @mouseenter="InOrOut(index+ doubleAll.length *2, true)" 
    @mouseleave="InOrOut(index+ doubleAll.length *2, false)">
    <img :src='photo' class="professor-photo"
    :class="{'mouse-enter':isIn[index+ doubleAll.length *2]==true}" 
    @click="openInfo(index)"
    >
    </li>
  </ul>
</div>

</div>


    <section :class="{'professor-info':true,'bg-frostedGlass':true, 'active':isOpen==true }">
      <li class="professor-big-photo-frame">
        <img :src="clickedPhoto" class="professor-big-photo" 
        ref="refBigPhoto" alt="Professor Photo" > 
      </li>
      <article class="professor-text">
        <font-awesome-icon icon="fa-solid fa-xmark" class="professor-xmark" @click="closeInfo"/>
        
        <h3 class="professor-name">{{ professsor }}</h3>
        <p class="professor-job">{{ job }}</p>
        <div class="professor-skill-wand">
          <IconWandCore class="professor-wand"/>
          <h5 class="professor-skill">Skills: </h5>
        </div>
        
        <p class="professor-skill-detail">{{ skillDetail }}</p>
      </article>
    </section>
  </section>
</template>

<style scoped lang="scss">
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
}
.professor-title{
color: $color-fsWhite; 
width: fit-content;
margin: 0 auto;
position: relative;
z-index: 80;
}

 // ------------------------大卡片-----------------------------
.professor-info{
  width: fit-content;
  height: fit-content;
  position: fixed;
  top: 0;bottom: 0;left: 0;right: 0;
  margin: auto;
  padding: 80px 60px;
  display: none;
  z-index: 100;
  .professor-big-photo-frame{
    z-index: 105;
    height: 600px;
    width:450px;
    transform: rotate(2deg) ;
    border-radius: 12px;
    overflow: hidden;
    .professor-big-photo{
      height: auto;
      width: 100%;
    }
  }
  .professor-text{
    background-color: $color-fsWhite;
    height: fit-content;
    width: fit-content;//寬暫時值
    padding: 40px 72px;

    border-radius: 8px;
    position: relative;
    top: 0;bottom: 0;
    margin: auto;
    .professor-xmark{ 
      height: 40px;
      width: 40px;
      position:absolute;
      top: 16px;
      right: 16px;
      cursor: pointer;
    }
    .professor-name{
      color: $color-fsTitle;
      margin-top: 40px;
    }
    .professor-job{
      color: $color-fsContent;
    }
    .professor-skill-wand{
      color: $color-fsTitle;
      display: inline-flex;
      margin-top: 20px;
      padding-left: -8px;
      position: relative;
      left: -8px;
      .professor-wand{
        height: 4.48rem; // h5 
        width: 4.48rem; // h5 
      }

    }
    .professor-skill-detail{
      color: $color-fsContent;
    }
  }
}
.professor-info.active{
  display: flex;
}
 // ------------------------大卡片-----------------------------

.professor-page-wrapper{
  // background-color: #000;
  padding-top: 80px;
}
// non-CAROUSEL styles↑-------------

/* CAROUSEL */

.carousel-field {
  width: 100%;
  height: 100vh;
  .professor-reverse-carousel-container,
  .professor-carousel-container,
  .marquee-carousel {
    width: 100%;
    transform: rotate(-2deg) translateY(-30px); 
    position: relative;
    

    &.middle{ //li
      // display: none;
      top: 30px;
    }      
    &.lower{
      top: 60px;
    }
    .swiper-wrapper { // ul
      display: flex;

      -webkit-transition-timing-function: linear !important;
      transition-timing-function: linear !important;
      .swiper-slide { // li
        height: 200px;
        width: 200px;
        flex-shrink: 0;
        overflow: hidden;
        border-radius: 8px;
        
        &.mouse-enter ,&:hover{
        transform: rotate(2deg) scale(1.1); 
        z-index: 20;
        cursor: pointer;
       }  
        .professor-photo { 
          width: 100%;
          filter: brightness(.7);
          position:absolute;
          transition: filter .2s;
          top: -28px; 
          #photo13, #photo14{
            top: -36px;
          }
          #photo8, #photo10, #photo11, #photo12, #photo15{
          top: -50px;
          }
          &.mouse-enter {
          filter: brightness(.9); 
        }
        }

      }

    }
  }
}
</style>



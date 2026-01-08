<script setup>
import { ref, onMounted, onUnmounted, nextTick, effect } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import IconWandCore from '@/components/icons/SVG/IconWandCore.vue';
import Swiper from 'swiper';
import { Autoplay, FreeMode,EffectCoverflow  } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-coverflow';
import { useAuthStore } from "@/stores/autoStore";
const authStore = useAuthStore()


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
const info = ref([]) // 未來讀 json
const mouseAt = ref({x:0, y:0}) // 紀錄滑鼠位置
const professsor = ref('')
const job = ref('')
const skillDetail = ref('')
const refBigPhoto =ref('')
const clickedPhoto = ref('')
const xStart = ref(0) //起點
const xNow = ref(0)
const distance = ref(0)
const isOpen = ref(false);
const isPress =ref(false) 


for(let i=0; i< all.length * 2; i++){
  let src = all[i % all.length]
  doubleAll.value.push(src); 
} 
const isIn = ref(Array(doubleAll.value.length*3).fill(false)) // hover 待修復
const InOrOut= (targetIndex, status)=>{ // 更新 mouseenter 的狀態
  isIn.value[targetIndex]=status 
}

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
  clickedPhoto.value = all[index]
  isOpen.value = true
}
const closeInfo = () => {
  isOpen.value = false
}

const loadedCount = ref(0);
function loadSuccess(){
  loadedCount.value++;
  if (loadedCount.value == doubleAll.value.length) {
    authStore.isLoading = false;
  }
}

const onMousemove = (e) => { // mousemove 1px 呼叫一次
  // 1. 偵測滑鼠位置，slideChanged()判斷hover。
  mouseAt.value.x = e.clientX 
  mouseAt.value.y = e.clientY // 以viewport左上角為原點的座標位置
  // 2. 如果滑鼠有按下，偵測移動距離
  if(!isPress.value)return
  if (!refLists.value.length) return;
  
  xNow.value = e.clientX // 滑鼠按下&&移動時的當下位置
  distance.value = xNow.value - xStart.value // 距離按下時的總位移

}
const offPress =()=>{
  isPress.value = false
}



onMounted(() => { // DOM 生成後
    // ------------------------------------swiper 屬性---------------------------------------------

let Carousel = new Swiper(".professor-carousel-container", {
  modules:[Autoplay, FreeMode, EffectCoverflow],
  // freeMode:true,
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
  loopedSlides: 32,
  speed: 1500, // FOR MARQUEE SPEED
  loop: true,
  loopAdditionalSlides: 10,
  resistance: true,
  resistanceRatio: 0,
  allowTouchMove: true,
  // mousewheel: true,
  grabCursor: true,

});

// REVERSE MARQUEE
  // DATA SPEED
let reverseMarqueeCarousel = new Swiper(".professor-reverse-carousel-container", {
  modules:[Autoplay, FreeMode, EffectCoverflow],
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
  loopedSlides: 32,
  speed: 1250, // FOR MARQUEE SPEED
  loop: true,
  loopAdditionalSlides: 10,
  spaceBetween: 12,
  resistance: true,
  resistanceRatio: 0,
  allowTouchMove: true,
  grabCursor: true,
});


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
    <h2 class="professor-title">{{$t('professor.title')}}</h2>

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
      @click="openInfo(index)" @load="loadSuccess"
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
      <div class="professor-big-photo-frame">
        <img :src="clickedPhoto" class="professor-big-photo" 
        ref="refBigPhoto" alt="Professor Photo" > 
      </div>
      <article class="professor-text">
        <FontAwesomeIcon icon="fa-solid fa-xmark" class="professor-xmark" @click="closeInfo"/>
        
        <h3 class="professor-name">{{$t(professsor)}}</h3>
        <p class="professor-job ">{{$t(job)}}</p>
        <div class="professor-skill-wand">
          <IconWandCore class="professor-wand"/>
          <h5 class="professor-skill">{{$t('professor.skillTitle')}}</h5>
        </div>
        
        <p class="professor-skill-detail">{{$t(skillDetail)}}</p>
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
  width: min-content;
  height: min-content;
  position: absolute;
  top: 0;bottom: 0;
  left: 0;right: 0;
  margin: auto;
  padding: 80px 60px;
  display: none;
  z-index: 100;
  align-items: center;
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
    height: max-content;
    width: max-content;//寬暫時值
    padding: 40px 72px;
    padding-left: 48px;
    border-radius: 8px;
    position: relative;
    top: 0;bottom: 0;
    margin: auto 0;
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
      margin-top: 12px;
    }
    .professor-job, .professor-skill-detail{
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
  }
}
.professor-info.active{
  display: flex; 
}

 // ------------------------大卡片↑-----------------------------

.professor-page-wrapper{
  // background-color: #000;
  padding-top: 80px;
  height:100vh;
}

// non-CAROUSEL styles↑-------------

/* CAROUSEL */

.carousel-field {
  width: 100%;
  // height: 100vh;
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
@media (max-width: 1366px){
.professor-info{
  padding: 80px 30px;
  .professor-big-photo-frame{
    height: 560px;
    width: 420px;
  }
}
}
@media (max-width: 992px){
.professor-page-wrapper{
  min-height: calc(100vw * 3/2);
  .professor-info.active{
    flex-direction: column-reverse; 
    
  }
  .professor-info{
    max-width: 100vw;
    // height: calc(100vh + 120vw);
    padding: 3%;
    margin-top: 80px;
    position: absolute;
    min-height:100vh;
      
      .professor-big-photo-frame{
        height: calc(70vw * 4 / 3);
        width: 70vw;

        max-height: calc(95vw * 4 / 3); 
        max-width: 95vw;
  
      }
      // &.active{
      //   // flex-direction: column;
      // }
      .professor-text{
        // width: 95vw;
        max-width: 95vw;
        
        padding: 8%;
        overflow: visible;
        
      }
    }

}

}

</style>



<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import IconWandCore from '@/components/icons/SVG/IconWandCore.vue';

// photo
const all = [  '/ProfessorIntroduction/Jiǔtiān Xuánnǚ.png',
'/public/ProfessorIntroduction/Zhōngtán Yuánshuài.jpg',
'/ProfessorIntroduction/Guān Shèng Dìjūn.png',
'/ProfessorIntroduction/Guānyīn Púsà.png',
'/ProfessorIntroduction/Tiānshàng Shèngmǔ.png',
'/ProfessorIntroduction/Chénghuáng Zūnshén.png',
'/ProfessorIntroduction/Hǔyé Jiāngjūn.png',
'/ProfessorIntroduction/XuanTian.png',
'/ProfessorIntroduction/Xuántán Zhēnjūn.png',
'/public/ProfessorIntroduction/Fǎzhǔ Shèngjūn.png',
  '/ProfessorIntroduction/YueLao.png',
  '/ProfessorIntroduction/Línshuǐ Fūrén.png',
  '/ProfessorIntroduction/Bǎoshēng Dàdì.png',
  '/ProfessorIntroduction/Yùhuáng Dàdì.png',
  '/ProfessorIntroduction/BeiDou.png',
  '/ProfessorIntroduction/Nándǒu Liùxīng.png',
  ]

//----------------ref----------------------------------
const doubleAll = ref([]) 
const refList = ref(null)
const refCard = ref(null) // li
const cardWidth = ref(0) 
let index = 0; // 計數器
let timer = ref(null)
for(let i=0; i< all.length * 2; i++){
  let src = all[i % all.length]
  doubleAll.value.push(src); 
} 
const isIn = ref(Array(doubleAll.value.length).fill(false))
const InOrOut= (index, status)=>{ 
  isIn.value[index]=status
}
const mouseAt = ref({x:0, y:0}) // 紀錄滑鼠位置
const onMousemove = (e) => { 
  mouseAt.value.x = e.clientX 
  mouseAt.value.y = e.clientY // 以viewport左上角為原點的座標位置
}
const slideChanged =  () => { 
  const topmostElement = document.elementFromPoint(mouseAt.value.x, mouseAt.value.y) // 離滑鼠最上層的元素(先收到滑鼠事件的) // mouseenter 卡片會回傳 <img>
  // console.log(topmostElement)
  if(!topmostElement){ return}
  const closestLi = topmostElement.closest('li[id^="photo"]') // 找離最上層元素最近的(父層)li
  if(!closestLi){ // 附近沒有 li
    isIn.value=isIn.value.fill(false)
    return
  }
  const indexStr = closestLi.id.replace('photo','') 
  const indexNum = Number(indexStr)
  isIn.value = isIn.value.map((_, i)=> i === indexNum ) // i === indexNum? true: false
}
const info = ref([]) // 未來讀 json
const professsor = ref(null)
const job = ref(null)
const skillDetail = ref(null)
const refBigPhoto =ref('')
const clickedPhoto = ref('')
const isOpen = ref(false);
(async ()=>{ //讀 json 
  let jsonFile = await fetch('/public/ProfessorIntroduction/professorInfo.json')
  if(jsonFile){
    info.value = await jsonFile.json()
  }
})()
const openInfo = (index) => {
  if(!info.value[0]){alert(false) ;return}
  if(index > all.length - 1 ){ index = index - all.length } // 把複製資料的 index 對應到原始資料的
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

const xStart = ref(0) //起點
const xNow = ref(0)
const distance = ref(0)
const isPress =ref(false) 
const mousemoveOnPressed = (e)=>{
  if(!isPress)return
  else{
  xNow.value = e.clientX
  // distance/time = speed (need?
  distance.value = xNow.value - xStart.value
  refList.value.style.transform = `translateX(${distance.value}px)` //移動
  xStart.value = xNow.value // 設定新起點
  }
}

onMounted(() => { // DOM 生成後
  setTimeout(slideChanged(),5)
  // querySelector 只會抓「第一個」符合的元素
  const cardElement = document.querySelector('.professor-photo-wrapper')
  if (cardElement) {
    // 處理 ref 物件 記得加 .value 
    cardWidth.value = cardElement.offsetWidth // offsetWidth (包含 padding + border) 
  }
  if (refCard.value && refCard.value.length > 0) {
    const cardMargin = parseInt(window.getComputedStyle( refCard.value[0]).marginLeft)
    cardWidth.value += cardMargin * 2 // 左右 margin 都要計入
  }
  
  const slider = async() => {
    refList.value.style.transition = 'all 1s'
    refList.value.style.transform = `translateX(${-index * cardWidth.value}px)` //左移一張
    await nextTick() // 等一個動畫移動完成
    setTimeout(slideChanged(),5)
    if( index == all.length ){ // 走到複製第一張 
      setTimeout(()=>{
        refList.value.style.transition = '0s '
        index = 0
        refList.value.style.transform = 'translateX(0)'
      }, 900)
      setTimeout(slideChanged(),5)
    }
  } 
  const move = () => {
    index++
    slider()
  }
  timer = setInterval(move, 1000) 
  const onPress =()=>{
    isPress.value = true
    if(timer) clearInterval()
  }
  const offPress =()=>{
    isPress.value = false
    if(!timer) setInterval(move, 1000) 
  }
  document.addEventListener('mousedown', onPress)
  document.addEventListener('mouseup', offPress)
})

onUnmounted(()=>{
  document.removeEventListener('mousedown', onPress)
  document.removeEventListener('mouseup', offPress)
  if(timer)clearInterval(timer)
})

</script>
<template> <!--全域事件綁定(未做): 'touchstart'.,'touchend'-->
  <section class="professor-page-wrapper " @mousemove="onMousemove">
    <h2 class="professor-title">Professor</h2>
    <div class="professor-carousel-container" >
      <ul class="professor-list " ref="refList"  @mousemove="mousemoveOnPressed" ><!--'touchmove'-->
        <li ref='refCard' 
        :class="{'professor-photo-wrapper':true, 'mouse-enter':isIn[index]==true}" 
        v-for="(photo, index) in doubleAll" 
        :id="'photo'+index" >
          <img :src='photo' 
          :class="{'professor-photo':true, 'mouse-enter':isIn[index]==true}" 
          @mouseenter="InOrOut(index, true)" 
          @mouseleave="InOrOut(index, false)"
          @click="openInfo(index)"

           >
        </li>
      </ul>    
    </div>

    <section :class="{'professor-info':true, 'active':isOpen==true }">
      <li class="professor-big-photo-frame">
        <img :src="clickedPhoto" class="professor-big-photo" 
        ref="refBigPhoto" alt="Professor Photo" > 
      </li>
      <article class="professor-text">
        <font-awesome-icon icon="fa-solid fa-xmark" class="xmark" @click="closeInfo"/>
        <h3 class="professor-name">{{ professsor }}</h3>
        <p class="professor-job">{{ job }}</p>
        <h5 class="professor-skill">Skills: </h5>
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
.professor-page-wrapper{
  background-color: $color-fsTitle;
  padding-top: 80px;
  height: 100vh; 
  // position: relative;
  .professor-title{
  color: $color-fsWhite; 
  width: fit-content;
  margin: 0 auto;
  position: relative;
  z-index: 100;
  }
  .professor-carousel-container{
    width: 100%; 
    transform: rotate(-2deg) translateY(-30px); 
    .professor-list{
      display: flex;
      transition: all 1s linear;
      .professor-photo-wrapper{
        height: 200px;
        flex-basis: 200px;
        flex-shrink: 0; // 預設是1
        margin: 0 3px;
        overflow: hidden;
        border-radius: 4px;
        position: relative;
        .professor-photo{
          width: 100%;
          filter: brightness(.7);
          transition: filter .2s;
          position:absolute;
          top: -28px;
          
          #photo13, #photo14{
            top: -36px;
          }
          #photo8, #photo10, #photo11, #photo12, #photo15{
          top: -50px;
          }
        }
      }
      .professor-photo-wrapper.mouse-enter {
        transform: rotate(2deg) scale(1.1); 
        z-index: 20;
        cursor: pointer;
        .professor-photo.mouse-enter {
          filter: brightness(.9); 
        }
      }
    }
  }

}

// ------------------------大卡片-----------------------------
.professor-info{
  width: fit-content;
  height: fit-content;
  position: fixed;
  top: 0;bottom: 0;left: 0;right: 0;
  margin: auto;
  display: none;
  .professor-big-photo-frame{
    z-index: 5;
    height: 600px;
    width:450px;
    transform: rotate(2deg) ;//
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
    // display: none;
    .xmark{ 
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
    .professor-skill{
      color: $color-fsTitle;
      margin-top: 20px;
    }
    .professor-skill-detail{
      color: $color-fsContent;
  
    
    }
  }
}
.professor-info.active{
  display: flex;
}
</style>

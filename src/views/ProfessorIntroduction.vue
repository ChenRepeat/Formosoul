<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue'

const all = [  '/ProfessorIntroduction/Jiǔtiān Xuánnǚ.png',
  '/ProfessorIntroduction/Guān Shèng Dìjūn.png',
  '/ProfessorIntroduction/Fǎzhǔ Shèngjūn.png',
  '/ProfessorIntroduction/Bǎoshēng Dàdì.png',
  '/ProfessorIntroduction/Hǔyé Jiāngjūn.png',
  '/ProfessorIntroduction/Chénghuáng Zūnshén.png',]
  // '/ProfessorIntroduction/Yùhuáng Dàdì.png',
  // '/ProfessorIntroduction/Xuántán Zhēnjūn.png',
  // '/ProfessorIntroduction/Běidǒu Qīxīng.png',
  // '/ProfessorIntroduction/XuanTian.png',
  // '/ProfessorIntroduction/Guānyīn Púsà.png',
  // '/ProfessorIntroduction/Yuèlǎo Zūnshén.png',
  // '/ProfessorIntroduction/Nándǒu Liùxīng.png',
  // '/ProfessorIntroduction/Tiānshàng Shèngmǔ.png',
  // '/ProfessorIntroduction/Línshuǐ Fūrén.png',
  // '/ProfessorIntroduction/Běidǒu Qīxīng.png',
//----------------ref----------------------------------
const doubleAll = ref([]) 
const refList = ref(null)
const refCard = ref(null) // li
const cardWidth = ref(0) 
let timer = ref(null)
  for(let i=0; i< all.length * 2; i++){
    let src = all[i % all.length]
    doubleAll.value.push(src); 
  } 
const isIn = ref(Array(doubleAll.value.length).fill(false))

const inNOut= (index, status)=>{ 
  isIn.value[index]=status
}

const mouseAt = ref({x:0, y:0}) // 紀錄滑鼠位置

const onMousemove = (e) => { 
  mouseAt.value.x = e.clientX 
  mouseAt.value.y = e.clientY // 以viewport左上角為原點的座標位置
}
onMounted(() => { // DOM 生成後
  
  const slideChanged = async () => { 
    await nextTick() // 等圖片切換完
    const topmostElement = document.elementFromPoint(mouseAt.value.x, mouseAt.value.y) // 離滑鼠最上層的元素(先收到滑鼠事件的) // mouseenter 卡片會回傳 <img>

    if(!topmostElement){ return}
    
    const closestLi = topmostElement.closest('li[id^="photo"]') // 找離最上層元素最近的(父層)li
    if(!closestLi){ // 附近沒有 li
      isIn.value=isIn.value.fill(false)
      return
    }
  
    const indexStr = closestLi.id.replace('photo','') 
    const indexNum = Number(indexStr)
      // if (!Number.isInteger(indexNum)) return
    isIn.value = isIn.value.map((_, i)=> i === indexNum ) // i === indexNum? true: false
  }
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
  let index = 0; // 計數器
  const slider = () => {
    refList.value.style.transition = 'all .5s'
    refList.value.style.transform = `translateX(${-index * cardWidth.value}px)` //左移一張
    slideChanged()
    if( index == all.length ){ // 走到複製第一張 
      setTimeout(()=>{
        refList.value.style.transition = '0s '
        index = 0
        refList.value.style.transform = 'translateX(0)'
      }, 500)
      // slideChanged()
    }
    // slideChanged()
  } 
  const move = () => {
    index++
    slider()
  }
 timer = setInterval(move, 1000) 
})

onUnmounted(()=>{
  if(timer){clearInterval(timer)}
})

</script>
<template>
  <section class="professor-wrapper">
    <h2 class="professor-title">Professor</h2>

    <div class="professor-carousel-container" @mousemove="onMousemove">
      <ul class="professor-list" ref="refList">
        <li ref='refCard' :class="{'professor-photo-wrapper':true, 'mouse-enter':isIn[index]==true}" v-for="(photo, index) in doubleAll" :id="'photo'+index" >
          <img :src='photo' :class="{'professor-photo':true, 'mouse-enter':isIn[index]==true}" @mouseenter="inNOut(index, true)" @mouseleave="inNOut(index, false)"></img></li>
      </ul>    
    </div>


  </section>
  <section class="professor-info">
    <div class="professor-big-photo">
    </div>
    <article class="professor-text">
      <!-- <font-awesome-icon icon="fa-solid fa-xmark" class="xmark"/> -->
      
    </article>
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
.professor-wrapper{
  background-color: $color-fsTitle;
  height: 100vh; 
}
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
}
.professor-list{
  display: flex;
  transition: all 1s linear;

  // gap: 12px;
}
.professor-photo-wrapper{
  height: 200px;
  flex-basis: 200px;
  flex-shrink: 0; // 預設是1
  margin: 0 6px;
  overflow: hidden;
  border-radius: 4px;
  
  position: relative;
}

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

.professor-photo-wrapper.mouse-enter {
  transform: rotate(2deg) scale(1.1); 
  z-index: 20;
}
.professor-photo.mouse-enter {

  filter: brightness(.9); 
}

</style>

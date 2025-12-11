<script setup>
import { ref, onMounted, onUnmounted, nextTick, } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

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
const info = ref([]) // 未來讀 json
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
const InOrOut= (index, status)=>{ 
  isIn.value[index]=status
}
const mouseAt = ref({x:0, y:0}) // 紀錄滑鼠位置
const onMousemove = (e) => { 
  mouseAt.value.x = e.clientX 
  mouseAt.value.y = e.clientY // 以viewport左上角為原點的座標位置
}

// info.value = 
const professsor = ref(null)
const job = ref(null)
const skill = ref(null)
const skillDetail = ref(null)
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
    // if (!Number.isInteger(indexNum)) return
  isIn.value = isIn.value.map((_, i)=> i === indexNum ) // i === indexNum? true: false
}
onMounted(() => { // DOM 生成後
  (async ()=>{ //讀 json 的方法
    let jsonFile = await fetch('/public/ProfessorIntroduction/professorInfo.json')
    if(jsonFile){
      info.value = await jsonFile.json()
    }
    console.log(info)})()
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
  let index = 0; // 計數器
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
  
})

onUnmounted(()=>{
  if(timer){clearInterval(timer)}
})

</script>
<template>
  <section class="professor-page-wrapper " @mousemove="onMousemove">
    <h2 class="professor-title">Professor</h2>

    <div class="professor-carousel-container">
      <ul class="professor-list " ref="refList">
        <li ref='refCard' 
        :class="{'professor-photo-wrapper':true, 'mouse-enter':isIn[index]==true}" 
        v-for="(photo, index) in doubleAll" 
        :id="'photo'+index" >
          <img :src='photo' 
          :class="{'professor-photo':true, 'mouse-enter':isIn[index]==true}" 
          @mouseenter="InOrOut(index, true)" 
          @mouseleave="InOrOut(index, false)"
          @click="onClick" ></img>
        </li>
      </ul>    
    </div>

    <section class="professor-info">
      <div class="professor-big-photo-frame">
        <!-- <img :src="src" alt="" class="professor-big-photo"> src還沒宣告 -->
      </div>
      <article class="professor-text">
        <font-awesome-icon icon="fa-solid fa-xmark" class="xmark"/>
        <h3 class="professor-name">{{ professsor }}</h3>
        <p class="professor-job">{{ job }}</p>
        <h5 class="professor-skill">{{ skill }}</h5>
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
        .professor-photo.mouse-enter {
          filter: brightness(.9); 
        }
      }
    }
  }

}





// ------------------------大卡片-----------------------------
.professor-info{}
.professor-big-photo-frame{}
.professor-big-photo{}
.professor-text{
  background-color: $color-fsWhite;
  height: 100px;
  width: 382px;//寬高暫時值
  padding: 16px;
  border-radius: 8px;

  position: fixed;
  top: 0;bottom: 0;left: 0;right: 0;
  margin: auto;
}
.xmark{ 
  height: 40px;
  width: 40px;
  position:absolute;
  top: 16px;
  right: 16px;
}
.professor-name{
  color: $color-fsTitle;
}
.professor-job{
  color: $color-fsContent;
}
.professor-skill{
  color: $color-fsTitle;
  
}
.professor-skill-detail{
  color: $color-fsContent;

}

</style>

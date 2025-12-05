<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const all = [  '/ProfessorIntroduction/Jiǔtiān Xuánnǚ.png',
  '/ProfessorIntroduction/Guān Shèng Dìjūn.png',
  '/ProfessorIntroduction/Fǎzhǔ Shèngjūn.png',
  '/ProfessorIntroduction/Bǎoshēng Dàdì.png',
  '/ProfessorIntroduction/Hǔyé Jiāngjūn.png',
  '/ProfessorIntroduction/Chénghuáng Zūnshén.png',
  '/ProfessorIntroduction/Yùhuáng Dàdì.png',
  '/ProfessorIntroduction/Xuántán Zhēnjūn.png',
  '/ProfessorIntroduction/Běidǒu Qīxīng.png',
  '/ProfessorIntroduction/XuanTian.png',
  '/ProfessorIntroduction/Guānyīn Púsà.png',
  '/ProfessorIntroduction/Yuèlǎo Zūnshén.png',
  '/ProfessorIntroduction/Nándǒu Liùxīng.png',
  '/ProfessorIntroduction/Tiānshàng Shèngmǔ.png',
  '/ProfessorIntroduction/Línshuǐ Fūrén.png',
  '/ProfessorIntroduction/Běidǒu Qīxīng.png',]
//----------------ref----------------------------------
const doubleAll = ref([]) 
const refList = ref(null)
const cardWidth = ref(0) 

for(let i=0; i< all.length * 2; i++){
  let src = all[i % all.length]
  doubleAll.value.push(src); // id= 0 ~ 31, src = 0 ~ 15 
} 
onMounted(() => { // 在 DOM 生成後
  // querySelector 只會抓「第一個」符合的元素
  const cardElement = document.querySelector('.professor-photo-wrapper')
  
  if (cardElement) {
    // 讀取 offsetWidth (包含 padding + border) 
    // 記得要用 .value 寫入
    cardWidth.value = cardElement.offsetWidth 
    
  }
  let index = 0; // 計數器
  const slider = () => {
    refList.value.style.transition = 'all .5s'
    refList.value.style.transform = `translateX(${-index * cardWidth.value}px)` //左移一張}
    if( index == all.length ){ // 超出原始長度走到複製資料第一張 
      setTimeout(()=>{
        refList.value.style.transition = 'none'
        index = 0
        refList.value.style.transform = 'translateX(0)'
      }, 500)
      
    }
    
    
  } 
  const move = () =>{
    index++
    slider()
  }
 setInterval(move, 1500) 
})
  

</script>
<template>
  <main class="professor-wrapper">
    <h2 class="professor-title">Professor</h2>

    <div class="professor-carousel-container">
      <ul class="professor-list" ref="refList">
        <!-- <li class="professor-photo-wrapper" v-for="(photo, index) in all" :id="'photo'+index+1"><img :src='all[index]' class="professor-photo " ></img></li> -->
        <li  class="professor-photo-wrapper" v-for="(photo, index) in doubleAll" :id="'photo'+index"><img :src='photo' class="professor-photo" ></img></li>
      </ul>    
    </div>

  </main>
  
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
  // overflow: auto; // 測試用語法 要拿掉
}
.professor-title{
  color: $color-fsBlue50; 
  width: fit-content;
  margin: 0 auto;
}
.professor-carousel-container{
  width: 100%; 
  height: 250px; 
}
.professor-list{
  display: flex;
  transition: all .5s;

  // transform: rotate(-1deg); 
  // gap: 12px;
}
.professor-photo-wrapper{
  height: 250px;
  flex-basis: 250px;
  flex-shrink: 0; // 預設是1
  // padding: 0 12px;
  overflow: hidden;

  position: relative;
}

.professor-photo{
  width: 100%;

  position:absolute;
  top: -28px;

  #photo13, #photo14{
  top: -36px;
  }
  #photo8, #photo10, #photo11, #photo12, #photo15{
  top: -50px;
  }
}

</style>

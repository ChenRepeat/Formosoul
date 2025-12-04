<script setup>
import { ref, onMounted } from 'vue'

const all = [
  '/ProfessorIntroduction/Jiǔtiān Xuánnǚ.png',
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
  '/ProfessorIntroduction/Běidǒu Qīxīng.png',
]
const refList = ref(null)
const cardWidthRef = ref(0)
// 1. 先定義變數，給初始值 0
const cardWidth = ref(0) 

// 2. 在 DOM 生成後才去抓取寬度
onMounted(() => {
  // 注意：querySelector 只會抓「第一個」符合的元素，剛好適合拿來量單張卡片寬度
  const cardElement = document.querySelector('.professor-photo-wrapper')
  
  if (cardElement) {
    // 3. 讀取 offsetWidth (包含 padding + border) 或 clientWidth (包含 padding)
    // 記得要用 .value 寫入
    cardWidth.value = cardElement.offsetWidth 
    
    console.log('抓到的寬度:', cardWidth.value) // 檢查用
  }
})
let index = 0; // 計數器
const slider = () => {
  if( index > all.length - 1 ) {
    index = 0 // index 大於 圖片陣列 則回到第一張
  } 
  refList.value.style.transform = `translateX(${-index * cardWidth.value}px)` //左移一張
  // 記得要加 value
  // const cardWidth = document.querySelectorAll('.professor-photo-wrapper').width ; // 262px
  
} 
const move = () =>{
  index++
  slider()
}
setInterval(move, 1500)

  

</script>
<template>
  <main class="professor-wrapper">
    <h2 class="professor-title">Professor</h2>

    <div class="professor-carousel-container">
      <ul class="professor-list" ref="refList">
        <!-- ratio: thin [8,11,12,15]top: -50px; [13,14]top: -36px;-->
        <li  class="professor-photo-wrapper"><img :src='all[15]' class="professor-photo top-50" ref="cardWidth"></img></li>
        <li  class="professor-photo-wrapper"><img :src='all[14]' class="professor-photo top-36" ></img></li>
        <li  class="professor-photo-wrapper"><img :src='all[13]' class="professor-photo top-36" ></img></li>
        <li  class="professor-photo-wrapper"><img :src='all[12]' class="professor-photo top-50" ></img></li>
        <li  class="professor-photo-wrapper"><img :src='all[11]' class="professor-photo top-50" ></img></li>

        <!-- size="800" [10]top: -50~-58px;-->
        <li  class="professor-photo-wrapper"><img :src='all[10]' class="professor-photo top-50" ></img></li>

        <!-- size="801" [0]top: -28px;-->
        <li  class="professor-photo-wrapper"><img :src='all[0]' class="professor-photo " ></img></li>
        <!-- size="864" -->
        <li  class="professor-photo-wrapper"><img :src='all[1]' class="professor-photo"></img></li>
        <li  class="professor-photo-wrapper"><img :src='all[2]' class="professor-photo"></img></li>
        <li  class="professor-photo-wrapper"><img :src='all[3]' class="professor-photo"></img></li>
        <li  class="professor-photo-wrapper"><img :src='all[4]' class="professor-photo"></img></li>
          <!-- [4]top: -40px; [864-rest] top:-8px;-->
        <li  class="professor-photo-wrapper"><img :src='all[5]' class="professor-photo"></img></li>
        <li  class="professor-photo-wrapper"><img :src='all[6]' class="professor-photo"></img></li>
        <!-- size="832" -->
        <li  class="professor-photo-wrapper"><img :src='all[7]' class="professor-photo"></img></li>
        <!-- [7] top: -30px;-->
        <li  class="professor-photo-wrapper"><img :src='all[8]' class="professor-photo top-50"></img></li>

        <!-- size="1888*..." -->
        <li  class="professor-photo-wrapper"><img :src='all[9]' class="professor-photo"></img></li>
          <!-- this. img-class與其他不同 之後可寫在id -->
        
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
  outline: 1px solid red;
}
.professor-list{
  display: flex;
  transition: all .5s;

  // transform: rotate(-1deg); 
  gap: 12px;
}
.professor-photo-wrapper{
  height: 250px;
  flex-basis: 250px;
  flex-shrink: 0; // 預設是1
  overflow: hidden;

  position: relative;
}

.professor-photo{
  width: 100%;

  position:absolute;
  top: -28px;
  .top-50{
  top: -50px;
  }
  .top-36{
  top: -36px;
  }
}

</style>

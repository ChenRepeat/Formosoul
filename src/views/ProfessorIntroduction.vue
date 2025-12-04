<script setup>
import { ref } from 'vue'

const all = ref([
  '../../public/ProfessorIntroduction/Jiǔtiān Xuánnǚ.png',
  '../../public/ProfessorIntroduction/Guān Shèng Dìjūn.png',
  '../../public/ProfessorIntroduction/Fǎzhǔ Shèngjūn.png',
  '../../public/ProfessorIntroduction/Bǎoshēng Dàdì.png',
  '../../public/ProfessorIntroduction/Hǔyé Jiāngjūn.png',
  '../../public/ProfessorIntroduction/Chénghuáng Zūnshén.png',
  '../../public/ProfessorIntroduction/Yùhuáng Dàdì.png',
  '../../public/ProfessorIntroduction/Xuántán Zhēnjūn.png',
  '../../public/ProfessorIntroduction/Běidǒu Qīxīng.png',
  '../../public/ProfessorIntroduction/Xuántiān Shàngdì.png',
  '../../public/ProfessorIntroduction/Guānyīn Púsà.png',
  '../../public/ProfessorIntroduction/Yuèlǎo Zūnshén.png',
  '../../public/ProfessorIntroduction/Nándǒu Liùxīng.png',
  '../../public/ProfessorIntroduction/Tiānshàng Shèngmǔ.png',
  '../../public/ProfessorIntroduction/Línshuǐ Fūrén.png',
  '../../public/ProfessorIntroduction/Běidǒu Qīxīng.png',
])


</script>
<template>
  <main class="professor-wrapper">
    <h2 class="professor-title">Professor Introduction</h2>


    <ul class="professor-carousel">
      <!-- ratio: thin [8,11,12,15]top: -50px; [13,14]top: -36px;-->
      <li  class="professor-photo-wrapper"><img :src='all[15]' class="professor-photo" id="top-50"></img></li>
      <li  class="professor-photo-wrapper"><img :src='all[14]' class="professor-photo" ></img></li>
      <li  class="professor-photo-wrapper"><img :src='all[13]' class="professor-photo" ></img></li>
      <li  class="professor-photo-wrapper"><img :src='all[12]' class="professor-photo" ></img></li>
      <li  class="professor-photo-wrapper"><img :src='all[11]' class="professor-photo" ></img></li>

      <!-- size="800" [10]top: -50~-58px;-->
      <li  class="professor-photo-wrapper"><img :src='all[10]' class="professor-photo" ></img></li>

      <!-- size="801" [0]top: -28px;-->
      <li  class="professor-photo-wrapper"><img :src='all[0]' class="professor-photo" ></img></li>
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
      <li  class="professor-photo-wrapper"><img :src='all[8]' class="professor-photo"></img></li>

      <!-- size="1024" -->
      <li  class="professor-photo-wrapper"><img :src='all[9]' class="professor-photo-1024"></img></li>
        <!-- img-class與其他不同 之後可寫在id -->
      
    </ul>
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
  // height: ;
  overflow: auto; // 測試用語法 要拿掉
}
.professor-title{
  color: $color-fsBlue50; // Blue50
}
.professor-carousel{
  display: flex;
  flex-wrap: wrap;
}
.professor-photo-wrapper{
  height: 250px;
  width: 250px;
  overflow: hidden;

  position: relative;
}
.professor-photo{
  width: 100%;

  position:absolute;
  top: -28px;
}
#top-50{
  // width: 100%;

  // position:absolute;
  top: -50px;
}

.professor-photo-1024{
  width: 100%;

  position:absolute;
  // top: -50px;
  right:4px;
  transform: scale(1.25);
}
</style>

<script setup>
//  button 事件使用方法
//  在父組件定義事件
//  import { ref } from 'vue';
//  import Button from './Button.vue';

//  範例如下

//  要使用到事件切換的話，class名在這邊定義
//  const currentClass = ref('btn');

//  切換 class 實例
//  const ch = () => {
//    if (currentClass.value === 'btn') {
//       currentClass.value = 'btn-a';
//    } else {
//       currentClass.value = 'btn';
//    }
//  };
</script>

<template>
  <button><slot></slot></button>
  <!--  
    組件使用方式
    <Button  

      配合程式使用的 class、事件
      :class="currentClass" @click="ch()">
      
      你的內容直接放這，包含fontawsome
      跟html 一樣的使用方式

    </Button>
    
    -->
</template>

<style scoped lang="scss">

// havor效果待補

/* Button 『預設桌機，手機版 <p> 請加最下方class』 */



/* 填色btn -----------------------------------*/

.btn-yellow-fill{
  font-size: 16px;
  font-family: 'Roboto', 'Noto Sans TC', sans-serif;
  color: $color-fsTitle;
  padding: 8px 40px;
  border-radius: 10px;
  border: 1px solid transparent;
  background-color: $color-fsGold300;
  cursor: pointer;
}

.btn-gray-fill{
  font-size: 16px;
  font-family: 'Roboto', 'Noto Sans TC', sans-serif;
  color: $color-fsTitle;
  padding: 8px 40px;
  border-radius: 10px;
  border: 1px solid transparent;
  background-color: $color-fsContent;
  cursor: pointer;
}

.btn-blue-fill{
  font-size: 16px;
  font-family: 'Roboto', 'Noto Sans TC', sans-serif;
  color: $color-fsWhite;
  padding: 8px 40px;
  border-radius: 10px;
  border: 1px solid transparent;
  background-color: $color-fsBlue900;
  cursor: pointer;
}


/* 框線btn -----------------------------------*/
.btn-black{
  font-size: 16px;
  font-family: 'Roboto', 'Noto Sans TC', sans-serif;
  color: $color-fsTitle;
  padding: 8px 40px;
  border-radius: 10px;
  border: 1px solid $color-fsTitle;
  background-color: transparent;
  cursor: pointer;
}

.btn-white{
  font-size: 16px;
  font-family: 'Roboto', 'Noto Sans TC', sans-serif;
  color: $color-fsWhite;
  padding: 8px 40px;
  border-radius: 10px;
  border: 1px solid $color-fsWhite;
  background-color: transparent;
  cursor: pointer;
}

.btn-yellow{
  font-size: 16px;
  font-family: 'Roboto', 'Noto Sans TC', sans-serif;
  color: $color-fsTitle;
  padding: 8px 40px;
  border-radius: 10px;
  border: 1px solid $color-fsGold300;
  background-color: transparent;
  cursor: pointer;
}



/* 手機版 <p> 請加這個class -----------------------------------*/
.btn-moblie{
  font-size: 14px;
}

/* 中文版請加這個class -----------------------------------*/
.btn-ch{
  font-family: 'Noto Sans TC', sans-serif;
}


</style>

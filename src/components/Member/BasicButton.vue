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

<style scoped>
.btn {
  background-color: red;
}
.btn-a {
  background-color: green;
}
.btn:hover {
  background-color: yellow;
}
.btn-a:hover {
  background-color: blue;
}
</style>

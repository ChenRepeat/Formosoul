<script setup>
// import BasicButton from './BasicButton.vue';

// ================ 試試看用 props 寫法 ================
const props = defineProps({
  text: { type: String, required: true },
  width: { type: String, default: '280px'},
  height: { type: String, default: '250px'},
  align: {type: String, default: 'left'},
  tag: {type: String, default: 'p'},
})

const emit = defineEmits(['click'])
</script>


<template>
<div class="survival-text-frame" :style="{ width: props.width, height: props.height }">
  <component 
    :is="props.tag" 
    class="text-content"
    :style="{ textAlign: props.align }" 
  >
    {{ props.text }}
  </component>

  <!-- 用slot的話 預設是 Enter，但可以被其他想要使用的父層覆蓋 -->
  <button class="survival-button" @click="emit('click')"> 
    <slot name="textButton">ENTER</slot>
  </button>
  <!-- 或是可以用下面的方式引用的來寫 -->
   <!-- <basic-button class='btn-yellow-fill' @click="emit('click')">Enter</basic-button> -->
</div>
</template>

  <!-- 等同於這樣： -->
<!-- <button @click="() => emit('click')">
  <slot name="textButton">Enter</slot>
</button> -->

<!-- 也等同於這樣： -->
<!-- <button @click="handleClick">
  <slot name="textButton">Enter</slot>
</button> -->

<!-- <script setup>
function handleClick() {
  emit('click')
}
</script> -->

<style scoped lang="scss">
.survival-text-frame {
  background-color: $color-fsBlue50 ;
  border-radius: 7px;
  min-height: 100px;
  display: flex;           
  flex-direction: column;
  justify-content: center;  
  align-items: center;
  padding: 20px 15px;
}

.survival-text-frame .text-content {
  color: #000;
  margin-bottom: 20px;
  width: 100%;
  white-space: pre-line;
}

.survival-text-frame button {
  // color: #000;
  // display: block;
  // margin: 0 auto;
  // background-color: $color-fsGold300 ;
  // width: 170px;
  // height: 48px;
  // border: none;
  // border-radius: 7px;
  // cursor: pointer;
  font-size: 16px;
  font-family: 'Roboto', 'Noto Sans TC', sans-serif;
  color: $color-fsTitle;
  padding: 8px 40px;
  border-radius: 10px;
  border: 1px solid transparent;
  background-color: $color-fsGold300;
  cursor: pointer;
}

</style>


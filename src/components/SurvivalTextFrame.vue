<script setup>
// ================ 試試看用 props 寫法 ================
const props = defineProps({
  text: { type: String, required: true },
  width: { type: String, default: '280px'},
  height: { type: String, default: '250px'},
})

const emit = defineEmits(['click'])
</script>


<template>
<div class="survival-text-frame" :style="{ width: props.width, height: props.height }">
  <p>{{ props.text }}</p>

  <!-- 用slot的話 預設是 Enter，但可以被其他想要使用的父層覆蓋 -->
  <button @click="emit('click')">
    <slot name="textButton">Enter</slot>
  </button>
</div>
</template>


<style scoped lang="scss">
.survival-text-frame {
  background-color: $color-fsBlue50 ;
  border-radius: 7px;
  padding: 20px 10px;
}

.survival-text-frame p {
  color: #000;
  text-align:left ;
  margin-bottom: 20px;
  margin-left: 24px;  // 可能要調整
}

.survival-text-frame button {
  color: #000;
  display: block;
  margin: 0 auto;
  background-color: $color-fsGold300 ;
  width: 170px;
  height: 48px;
  border: none;
  border-radius: 7px;
  cursor: pointer;
}

</style>


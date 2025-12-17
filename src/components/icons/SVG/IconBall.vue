<template>
  <svg 
    :width="size" 
    :height="size" 
    viewBox="0 0 300 300" 
    fill="none" 
    xmlns="http://www.w3.org/2000/svg"
  >
    <circle cx="150" cy="150" r="150" :fill="color"/>
    
    <image 
      v-if="icon" 
      :href="icon" 
      x="60" 
      y="60" 
      width="180" 
      height="180" 
    />
  </svg>
</template>

<script>
  export default {
    name: 'IconBall',
    props: {
      // 接收圖片路徑 (從 images 陣列傳進來)
      icon: {
        type: String,
        default: ''
      },
      size: {
        type: [String, Number],
        default: 48,
      },
      color: {
        type: [String, Number],
        default: "#D9D9D9", // 預設背景色
      }
    },
  };
</script>

<style scoped>
  svg {
    transition: transform 0.2s ease-in-out;
  }
  /* 滑鼠移過去可以有放大效果 */
  /* svg:hover {
    transform: scale(1.1);
  } */
</style>
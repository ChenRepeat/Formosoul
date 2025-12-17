<template>
  <svg     
    :width="size"
    :height="size"
    viewBox="0 0 570 570" fill="none" xmlns="http://www.w3.org/2000/svg" style="overflow: visible";>
  <rect class="blow" x="0.5" y="0.5" width="565" height="565.731" rx="282.5" fill="#EEEEEE" stroke="black" stroke-width="6"/>
  <rect x="0.5" y="0.5" width="565" height="565.731" rx="282.5" stroke="black"/>
  <rect x="202.5" y="202.761" width="161" height="161.208" rx="80.5" fill="white"/>
  </svg>

</template>

<script>
  export default {
    props: {
      size: {
        type: [String, Number],
        default: 600,
      },
    },
  };
</script>

<style scoped>
  .blow {
    /* drop-shadow(水平偏移 垂直偏移 模糊半徑 顏色) */
    filter: drop-shadow(0px 6px 6px rgba(0, 0, 0, 0.3));
  }
  svg {
    transition: color 0.2s ease-in-out; 
  }
</style>
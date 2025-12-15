<template>
  <svg     
    :width="size"
    :height="size"
    viewBox="0 0 566 567" fill="none" xmlns="http://www.w3.org/2000/svg">
  <rect x="0.5" y="0.5" width="565" height="565.731" rx="282.5" fill="#EEEEEE"/>
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
  svg {
    transition: color 0.2s ease-in-out; 
  }
</style>
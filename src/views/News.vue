<template>
  <NewsCard></NewsCard>
</template>

<style scoped lang="scss">
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
}

body {
  /* background-image: url('https://images.unsplash.com/photo-1534796636912-3b95b3ab5986?q=80&w=1000');
  background-size: cover; */
  background-color: #000;
}
</style>

<script setup>
import NewsCard from '@/components/NewsCard.vue'
</script>

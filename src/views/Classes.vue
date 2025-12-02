<script setup lang="ts">
import ProductCard from '@/components/ProductCard.vue'
import TheFooter from '@/components/TheFooter.vue'
  import TheHeader from '@/components/TheHeader.vue';
</script>

<template>
  <TheHeader />
</template>

<style>
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
}
</style>

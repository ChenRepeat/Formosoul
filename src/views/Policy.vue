<script setup lang="ts">
import IndexBar from '@/components/ClassPages/indexBar.vue';
import Policybutton from '@/components/policy/policybutton.vue';






</script>

<template>
  <Policybutton />
  <router-view />
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

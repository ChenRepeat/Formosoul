<script setup>
import { RouterLink, RouterView } from 'vue-router'
import TheFooter from './components/TheFooter.vue';
import TheHeader from './components/TheHeader.vue';
</script>

<template>
  <div class="wrapper dp-flex-col">
    <TheHeader />
  <main class="content">
    <RouterView />
  </main>
    <TheFooter />
  </div>
</template>

<style lang="scss" scoped>
.wrapper {
  min-height: 100vh;
  background-color: $color-fsTitle;
  position: relative;
}
.content {
  display: block;
  flex-grow: 1;
  overflow: hidden;
  min-height: 1px;
}
.part-time{
  gap: 12px;
}


@media (min-width: 1024px) {
}
</style>

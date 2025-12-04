<script setup>
import { RouterLink, RouterView } from 'vue-router'
import TheFooter from './components/TheFooter.vue';
import TheHeader from './components/TheHeader.vue';
</script>

<template>
  <div class="wrapper dp-flex-col">
    <!-- <TheHeader /> -->
    <div class="dp-flex part-time">
      <RouterLink to="/"><h6>Home</h6></RouterLink>
      <RouterLink to="/about"><h6>About</h6></RouterLink>
      <RouterLink to="/news"><h6>News</h6></RouterLink>
      <RouterLink to="/annualevent"><h6>Annual Event</h6></RouterLink>
      <RouterLink to="/professorsintroduction"><h6>Professor Introduction</h6></RouterLink>
      <RouterLink to="/shop"><h6>Diagon Alley</h6></RouterLink>
      <RouterLink to="/survivalguide"><h6>Survival Guide</h6></RouterLink>
      <RouterLink to="/classes"><h6>Classes</h6></RouterLink>
      <RouterLink to="/member"><h6>Member</h6></RouterLink>
      <RouterLink to="/policy"><h6>Policy</h6></RouterLink>

    </div>
    <main class="content">
      <RouterView />
    </main>
    <TheFooter />
  </div>
</template>

<style scoped>
.wrapper {
  min-height: 100vh;
  background-color: #000;
  position: relative;
}
.content {
  display: block;
  flex-grow: 1;
  overflow: hidden;
  min-height: 1px;
  padding-top: 100px;
}
.part-time{
  gap: 12px;
}


@media (min-width: 1024px) {
}
</style>

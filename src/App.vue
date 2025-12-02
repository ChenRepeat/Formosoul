<script setup>
import { RouterLink, RouterView } from 'vue-router'
import HelloWorld from './components/HelloWorld.vue'
import TheFooter from './components/TheFooter.vue'
</script>

<template>
  <div class="wrapper dp-flex-col">
    <header></header>
    <div>
      <RouterLink to="/">Home</RouterLink>
      <RouterLink to="/about">About</RouterLink>
      <RouterLink to="/news">News</RouterLink>
      <RouterLink to="/annualevent">Annual Event</RouterLink>
      <RouterLink to="/professorsintroduction"
        >Professor Introduction</RouterLink
      >
      <RouterLink to="/shop">Diagon Alley</RouterLink>
      <RouterLink to="/survivalguide">Survival Guide</RouterLink>
      <RouterLink to="/classes">Classes</RouterLink>
      <RouterLink to="/member">Member</RouterLink>
      <RouterLink to="/policy">Policy</RouterLink>
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
}
.content {
  display: block;
  flex-grow: 1;
  overflow: hidden;
  min-height: 1px;
}

@media (min-width: 1024px) {
}
</style>

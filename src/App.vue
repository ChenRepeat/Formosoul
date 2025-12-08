<script setup>
import { RouterLink, RouterView } from 'vue-router'
import TheFooter from './components/TheFooter.vue';
import TheHeader from './components/TheHeader.vue';
import siteLogo from '@/assets/logo_white.svg'; 

</script>

<template>
  <div class="wrapper dp-flex-col">
    <TheHeader />
      <a href="/"><img :src="siteLogo" alt="SiteLogo" class="site-logo"/></a>
    
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
.site-logo{
  position: absolute;
  padding-left: 40px;
  top: 20px;
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

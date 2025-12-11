<script setup>
import { RouterLink, RouterView } from 'vue-router';
import TheFooter from './components/TheFooter.vue';
import TheHeader from './components/TheHeader.vue';
import loginpage from './components/Member/Login/loginpage.vue';
import siteLogo from '@/assets/logo_white.svg'; 
import Popup from './components/popup.vue';

</script>

<template>
  <div class="wrapper dp-flex-col">
    <TheHeader />
    <main class="content">
      <a href="/"><img :src="siteLogo" alt="SiteLogo" class="site-logo"/></a>
      <RouterView />
      <Popup></Popup>
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
  z-index: 2000;
}
.content {
  display: block;
  flex-grow: 1;
  overflow: hidden;
  min-height: 1px;
  // padding-top: 100px;
  position: relative;

}
.part-time{
  gap: 12px;
}


@media (min-width: 1024px) {
}
</style>

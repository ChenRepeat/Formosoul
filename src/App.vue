<script setup>
import { RouterLink, RouterView, useRoute } from 'vue-router';
import TheFooter from './components/TheFooter.vue';
import TheHeader from './components/TheHeader.vue';
import loginpage from './components/Member/Login/loginpage.vue';
import Popup from './components/popup.vue';
import { computed } from 'vue';

const route = useRoute();
const currentLogoSrc = computed(() => {
  return route.meta?.logo || 'src/assets/logo_white.svg';
});
const currentBgClass = computed(() => {
  return route.meta?.bgColor || 'bg-default';
});
</script>

<template>
  <div class="wrapper dp-flex-col"
  :class="currentBgClass">
    <TheHeader :is-black-style="currentBgClass == 'white'"/>
    <main class="content">
      <a href="/"><img :src="currentLogoSrc" alt="SiteLogo" class="site-logo"/></a>
      <RouterView />
      <Popup></Popup>
    </main>
    <img src="" alt="" srcset="">
    <TheFooter />
  </div>
</template>

<style lang="scss" scoped>
.wrapper {
  min-height: 100vh;
  background-color: $color-fsTitle;
  position: relative;
}
.wrapper.white{
  background-color: #fff;
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

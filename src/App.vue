<script setup>
import { RouterLink, RouterView, useRoute } from 'vue-router';
import TheFooter from './components/TheFooter.vue';
import TheHeader from './components/TheHeader.vue';
import loginpage from './components/Member/Login/loginpage.vue';
import Popup from './components/popup.vue';
import { computed, onMounted } from 'vue';
import { useAuthStore } from './stores/autoStore';
import DefaultLogo from '@/assets/logo_white.svg';

const route = useRoute();
const authStore = useAuthStore();

const currentLogoSrc = computed(() => {
  return route.meta?.logo || DefaultLogo;
});
const currentBgClass = computed(() => {
  return route.meta?.bgColor || 'bg-default';
});
const currentLogoDP = computed(() => {
  return route.meta?.display || 'block';
});

onMounted(async () => {
  if(authStore.token){
    await authStore.fetchUser();
  }
});
</script>

<template>
  <div v-if="route.meta.layout === 'admin'">
    <RouterView />
  </div>
  <div v-else class="wrapper dp-flex-col"
  :class="currentBgClass"
  >
    <TheHeader :is-black-style="currentBgClass == 'white' || currentBgClass == 'transparent'"/>
    <main class="content">
      <a href="/" :style="{'display':currentLogoDP}"><img :src="currentLogoSrc" alt="SiteLogo" class="site-logo" /></a>
      <!-- 這個div是登入狀態測試 如果有做好的loading在跟這個交換 -->
      <div v-if="authStore.isLoading" class="loading">
        載入中...
      </div>
      <div v-else>
        <RouterView />
        <Popup></Popup>
      </div>
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
.wrapper.transparent{
  background-color: transparent;
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

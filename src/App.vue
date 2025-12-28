<script setup>
import { RouterLink, RouterView, useRoute } from 'vue-router';
import TheFooter from './components/TheFooter.vue';
import TheHeader from './components/TheHeader.vue';
import loginpage from './components/Member/Login/loginpage.vue';
import Popup from './components/popup.vue';
import { ref, watch, computed, onMounted,nextTick, provide } from 'vue';
import { useAuthStore } from './stores/autoStore';
import DefaultLogo from '@/assets/logo_white.svg';
import { useLangStore } from './stores/lang';
import { gsap } from 'gsap';

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
const hideLogoRWD = computed(() => {
  return route.meta?.hideLogoRWD || false;
});
const langStore = useLangStore();

const execLanguageChange = (changeAction) => {
  const baseTags = ["h1", "h2", "h3", "h4", "h5", "h6", "p", "span", "a", "label",];
  const excludes = [
    ":not(.no-i18n-anim)",
    ":not(.trigger-lang)"
  ].join("");
  const finalTargets = baseTags.map(tag => `${tag}${excludes}`).join(", ");
  const tl = gsap.timeline();
  tl.to(finalTargets, {
    duration: 0.2,
    opacity: 0,
    y: -5,
    ease: "power2.in",
    onComplete: async () => {
      changeAction(); 
      await nextTick();
      gsap.fromTo(finalTargets, 
        { opacity: 0, y: 5 }, 
        { 
          opacity: 1, 
          y: 0, 
          duration: 0.3, 
          ease: "power2.out", 
          stagger: 0.01 
        }
      );
    }
  });
};
provide('execLanguageChange', execLanguageChange);

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
      <RouterLink to="/" :style="{'display':currentLogoDP}" class=" no-i18n-anim">
        <img :src="currentLogoSrc" alt="SiteLogo" class="site-logo" :class="{'dpn':hideLogoRWD}"/>
      </RouterLink>
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
  background-color: #000;
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

@media screen and (max-width: 1200px) {
  .dpn{
    display: none;
  }
}
</style>

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
import Wave from './components/Wave.vue';
import router from './router';
import { useMemberStore } from './stores/member';

const route = useRoute();
const langStore = useLangStore();
const authStore = useAuthStore();
const memberStore = useMemberStore();

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

const execLanguageChange = (changeAction) => {
  const baseTags = ["h1", "h2", "h3", "h4", "h5", "h6", "p", "span", "a", "label", ".i18n-anim"];
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
const charRefs = ref([]);

const startLoadingAnimation = () => {
  if (charRefs.value.length === 0) return;

  gsap.to(charRefs.value, {
    keyframes: [
      { x: 30, y: 10, duration: 0.7, scale: 3, rotateX: 0 },
      { x: 0, y: 0, duration: 0.7, scale: 1, rotateX: 360 }
    ],
    force3D: true,
    overwrite: true,
    stagger: 0.08,
    ease: "power2.out",
    repeat: -1,
  });
};
// --- Loading 畫面邏輯 vv ---
const handleLoadingState = () => {
  if (route.meta.requireLoading) {
    authStore.isLoading = true;
    nextTick(() => {
      if (typeof startLoadingAnimation === 'function') {
        startLoadingAnimation();
      }
    });
  } else {
    authStore.isLoading = false;
  }
};
router.isReady().then(() => {
  handleLoadingState();
});
//line用
watch(
  () => route.query.loginData,
  async (newData) => {
    if (newData) {
      const success = authStore.loginWithLine(newData);
      
      if (success) {
        // 讀取路徑
        const returnUrl = localStorage.getItem('line_return_url');

        //清除紀錄
        localStorage.removeItem('line_return_url');

        //跳轉
        await router.replace(returnUrl || '/');
        
        await memberStore.loadMemberData();
      }
    }
  },
  { immediate: true }
);
watch(
  () => route.path,
  () => {
    handleLoadingState();
  }
);

// --- Loading 畫面邏輯 ^^ ---
const innerH = ref(window.innerHeight).value
const waveConfig = ref(
  [
    [innerH/2, 0.8, 50, 0.2, '#F0F7FF', '#000', 0.8, 2],
    [innerH/2, 0.8, 80, 1, '#F0F7FF', '#000', 0, 1],
    [innerH/2, 0.8, 20, 0.6, '#F0F7FF', '#000', 0, 3.5],
    [innerH/2, 0.8, 110, 0, '#F0F7FF', '#000', 0, 0.2],
  ])
onMounted(async () => {
  await nextTick();
  if (authStore.token) {
    await authStore.fetchUser();
    memberStore.loadMemberData();
  }
})
</script>

<template>
  <div v-if="route.meta.layout === 'admin'">
    <RouterView />
  </div>
  <div v-else class="wrapper dp-flex-col"
  :class="[currentBgClass,{'noScroll':authStore.isLoading}]"
  >
    <TheHeader :is-black-style="currentBgClass == 'white' || currentBgClass == 'transparent'"/>
    <main class="content">
      <RouterLink to="/" :style="{'display':currentLogoDP}" class=" no-i18n-anim">
        <img :src="currentLogoSrc" alt="SiteLogo" class="site-logo" :class="{'dpn':hideLogoRWD}"/>
      </RouterLink>
      <div v-if="authStore.isLoading" class="loading dp-flex">
        <div class="loadin-text dp-flex">
          <h3>Loading</h3>
          <h3 v-for="(char, index) in '....'.split('')" 
            :key="index" 
            class="char"
            :ref="(el) => { if(el) charRefs[index] = el }"
          >
            {{ char === ' ' ? '&nbsp;' : char }}</h3>
        </div>
          <Wave :config=waveConfig :height=innerH />
      </div>
      <div>
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
  position: relative;

}
.noScroll{
  overflow: hidden;
  height: 100vh;
}
.part-time{
  gap: 12px;
}
.loading{
  color: $color-fsWhite;
  align-items: center;
  justify-content: center;
  height: 100vh;
  width: 100vw;
  z-index: 9999;
  background-color: #000;
  position: fixed;
  top: 0; left: 0;
  .loadin-text{
    padding-top: 10%;
  }
}
.char {
  display: inline-block; 
  min-width: 0.3em;
  transition: color 0.3s;
}
@media screen and (max-width: 1366px) {
  .dpn{
    display: none;
  }
}
</style>

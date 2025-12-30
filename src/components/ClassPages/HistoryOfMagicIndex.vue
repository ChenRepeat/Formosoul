<script setup>
  import { ref ,defineEmits } from 'vue';
  import gsap from 'gsap';
  import { usePageNavigation } from './goToPage';
  
  const emit = defineEmits(['flip']);
  const { goToPage } = usePageNavigation(emit);

  const vWidth = window.innerWidth;
  const toNianPage = ref(5);
  const toDuanOoPage = ref(6);
  const toMoonPage = ref(7);
  if(vWidth < 750){
    toNianPage.value = 10;
    toDuanOoPage.value = 12;
    toMoonPage.value = 14;
  }
  const onEnter = (e) => {
    const chars = e.currentTarget.querySelectorAll('.char');
    gsap.killTweensOf(chars); 
    gsap.to(chars, {
      keyframes: [
        { x: 30,y:10, color: '#041426', duration: 0.8,scale: 2,rotateX:0},
        { x: 30, y:10, color: 'transparent', duration: 0.5,scale: 3,rotateX:360},
        { x: 0, y:0, color: 'transparent', duration: 0.3,scale: 3,rotateX:360},
        { x: 0, y:0, color: '#041426', duration: 0.7,scale: 1}
      ],
      force3D: true,
      overwrite: true,
      stagger: 0.08,
      ease: "power2.out" 
    });
};
const onLeave = (e) => {
  const chars = e.currentTarget.querySelectorAll('.char');

  gsap.killTweensOf(chars); 
  gsap.to(chars, {
    x:0,
    y:0,
    scale: 1,
    rotateX:360,
    color: 'inherit',
    stagger: 0.02,
    duration: 0.3,
    ease: 'power2.out',
    overwrite: true,
  });

};
const indexItems = ref([
  { id: 'newYear', text: 'classes.newYearTitle', flipPage:toNianPage },
  { id: 'dragonBoat', text: 'classes.dragonBoatTitle', flipPage:toDuanOoPage },
  { id: 'moonFes', text: 'classes.moonFes', flipPage:toMoonPage },
 
]);
</script>

<template>
  <ul class="history-index-case dp-flex-col">
    <li class="dp-flex" v-for="(item, index) in indexItems" :key="index">
      <h5 class="page-index dp-flex no-i18n-anim"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(item.flipPage)"
      @mouseenter="onEnter"
      @mouseleave="onLeave">
      <span class="text-wrapper">
        <span 
          v-for="(char, index) in $t(item.text).split('')" 
          :key="index" 
          class="char"
        >
          {{ char === ' ' ? '&nbsp;' : char }}
        </span>
      </span>
      </h5>
    </li>
    

  </ul>
</template>

<style lang="scss" scoped>

  .history-index-case{
    gap: 16px;
  }
  li{
    justify-content: start;
    
  }
  .page-index{
    transition: all 0.3s;
    position: relative;
    text-align-last: left;
    align-items: center;
    gap: 24px;
    cursor: pointer;
  }
   .char {
    display: inline-block; 
    min-width: 0.3em;
    transition: color 0.3s;
  }
</style>
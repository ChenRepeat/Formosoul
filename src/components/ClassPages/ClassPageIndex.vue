<script setup>
  import { ref ,defineEmits, markRaw } from 'vue';
  import IconHelmet from '../icons/SVG/IconHelmet.vue';
  import IconFoot from '../icons/SVG/IconFoot.vue';
  import IconHistory from '../icons/SVG/IconHistory.vue';
  import IconBue from '../icons/SVG/IconBue.vue';
  import IconPotion from '../icons/SVG/IconPotion.vue';
  import IconCharm from '../icons/SVG/IconCharm.vue';  
  import gsap from 'gsap';
  import { usePageNavigation } from './goToPage';

  const emit = defineEmits(['flip']);
  const { goToPage } = usePageNavigation(emit);
  
  const vWidth = window.innerWidth;
  const toMotorPage = ref(1);
  const toAnimaPage = ref(3);
  const toHistoryPage = ref(4);
  const toCharmPage = ref(8);
  const toPotionPage = ref(9);
  const toDivitionPage = ref(10);
  if(vWidth < 750){
    toMotorPage.value = 2;
    toAnimaPage.value = 6;
    toHistoryPage.value = 9;
    toCharmPage.value = 15;
    toPotionPage.value = 18;
    toDivitionPage.value = 20;
  }
  const onEnter = (e , colorText , colorIcon) => {
    const chars = e.currentTarget.querySelectorAll('.char');
    const icons = e.currentTarget.querySelectorAll('.icon');
    gsap.killTweensOf(chars,icons); 

    gsap.to(chars, {
      keyframes: [
        { x: 30,y:10, color: colorText, duration: 0.1,scale: 3,rotateX:0},
        { x: 0, y:0, color: colorText, duration: 0.3,scale: 1,rotateX:360}
      ],
      force3D: true,
      overwrite: true,
      stagger: 0.08,
      ease: "power2.out"   // 這裡設 none，因為 ease 已經在 keyframes 裡個別設定了
    });
    gsap.to(icons, {
      color: colorIcon, 
      duration: 0.1,
      force3D: true,
      overwrite: true,
      ease: "bounse.out"   // 這裡設 none，因為 ease 已經在 keyframes 裡個別設定了
    });
};
const onLeave = (e) => {
  const chars = e.currentTarget.querySelectorAll('.char');
  const icons = e.currentTarget.querySelectorAll('.icon');

  gsap.killTweensOf(chars,icons); 
  gsap.to(chars, {
    x:0,
    y:0,
    scale: 1,
    rotateX:360,
    color: 'inherit',    // 恢復原本顏色
    stagger: 0.02,
    duration: 0.3,
    ease: 'power2.out',
    overwrite: true,
  });
  gsap.to(icons, {
      color: 'inherit', 
      duration: 0.1,
      force3D: true,
      overwrite: true,
      ease: "bounse.out"   // 這裡設 none，因為 ease 已經在 keyframes 裡個別設定了
  });
};
//  書籤陣列
const indexItems = ref([
  { id: 'motor', text: 'classes.motorClass', icon: markRaw(IconHelmet), color:'#ffcc46', flipPage:toMotorPage },
  { id: 'animal', text: 'classes.animalTitle', icon: markRaw(IconFoot), color:'#ffcc46', flipPage:toAnimaPage },
  { id: 'history', text: 'classes.historyTitle', icon: markRaw(IconHistory), color:'#ffcc46', flipPage:toHistoryPage },
  { id: 'charm', text: 'classes.charmTitle', icon: markRaw(IconCharm), color:'#ffcc46', flipPage:toCharmPage },
  { id: 'potion', text: 'classes.potionTitle', icon: markRaw(IconPotion), color:'#ffcc46', flipPage:toPotionPage },
  { id: 'divination', text: 'classes.divinationTitle', icon: markRaw(IconBue), color:'#ffcc46', flipPage:toDivitionPage },
]);
</script>

<template>
  <ul class="class-index-case dp-flex-col">
    <li class="dp-flex" v-for="(item, index) in indexItems" :key="index">
      <h5 class="page-index dp-flex no-i18n-anim"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(item.flipPage)"
      @mouseenter="onEnter($event,item.color,item.color)"
      @mouseleave="onLeave">
      <component :is="item.icon" class="icon"/>
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

  .class-index-case{
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
  /* 確保字母可以獨立移動 */
  .char {
    display: inline-block; 
    min-width: 0.3em;
    transition: color 0.3s;
  }
  .page-index::after{
    content: '';
    width: 0;
    height: 1px;
    transition: all 0.3s;
    background-color: $color-fsBlue;
    position: absolute;
    left: 0;
    bottom: 0;
    // transform: scaleX(0);

  }

</style>
<script setup>
  import { ref ,defineEmits } from 'vue';
  import IconHelmet from '../icons/SVG/IconHelmet.vue';
  import IconFoot from '../icons/SVG/IconFoot.vue';
  import IconHistory from '../icons/SVG/IconHistory.vue';
  import IconBue from '../icons/SVG/IconBue.vue';
  import IconPotion from '../icons/SVG/IconPotion.vue';
  import IconCharm from '../icons/SVG/IconCharm.vue';  

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
</script>

<template>
  <ul class="class-index-case dp-flex-col">
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(toMotorPage)"
      >
        <IconHelmet />Motorcycle
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(toAnimaPage)">
        <IconFoot /> Endemic Species
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(toHistoryPage)">
        <IconHistory /> History of Magic
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(toCharmPage)">
        <IconCharm size="43"/>Spell & Charms
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(toPotionPage)">
        <IconPotion />Potions
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(toDivitionPage)">
        <IconBue />Divinations
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
  li:hover .page-index{
    transform: scale(1.1);
    color: $color-fsBlue;
    svg{
      color: $color-fsBlue;
    }
  }
  li:hover  .page-index::after{
    width: 100%;
    // transform: scaleX(1);
  }
</style>
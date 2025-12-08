<template>
  <ul class="class-index-case dp-flex-col">
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(2)"
      >
        <IconHelmet />Motorcycle
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(3)">
        <IconFoot /> Endemic Species
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(4)">
        <IconHistory /> History of Magic
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(5)">
        <IconCharm size="43"/>Spell & Charms
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(6)">
        <IconPotion />Potions
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(7)">
        <IconBue />Divinations
      </h5>
    </li>
  </ul>
</template>

<script setup>
  import { ref ,defineEmits } from 'vue';
  import IconHelmet from '../icons/SVG/IconHelmet.vue';
  import IconFoot from '../icons/SVG/IconFoot.vue';
  import IconHistory from '../icons/SVG/IconHistory.vue';
  import IconBue from '../icons/SVG/IconBue.vue';
  import IconPotion from '../icons/SVG/IconPotion.vue';
  import IconCharm from '../icons/SVG/IconCharm.vue';  

  import { usePageNavigation } from './goToPage';
  
  const emit = defineEmits(['flip'])
  const { goToPage } = usePageNavigation(emit)
</script>

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
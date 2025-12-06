<template>
  <ul class="class-index-case dp-flex-col">
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="turnToPage(2)">
        <IconHelmet />Motorcycle
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop>
        <IconFoot /> Endemic Species
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop>
        <IconHistory /> History of Magic
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop>
        <IconCharm size="43"/>Spell & Charms
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop>
        <IconPotion />Potions
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop>
        <IconBue />Divinations
      </h5>
    </li>
  </ul>
</template>

<script setup>
  import { ref } from 'vue';
  import IconHelmet from '../icons/SVG/IconHelmet.vue';
  import IconFoot from '../icons/SVG/IconFoot.vue';
  import IconHistory from '../icons/SVG/IconHistory.vue';
  import IconBue from '../icons/SVG/IconBue.vue';
  import IconPotion from '../icons/SVG/IconPotion.vue';
  import IconCharm from '../icons/SVG/IconCharm.vue';  
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
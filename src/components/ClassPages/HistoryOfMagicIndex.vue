<script setup>
  import { ref ,defineEmits } from 'vue';

  import { usePageNavigation } from './goToPage';
  
  const emit = defineEmits(['flip']);
  const { goToPage } = usePageNavigation(emit);

  const vWidth = window.innerWidth;
  const toMoonPage = ref(10);
  const toNianPage = ref(11);
  const toDuanOoPage = ref(12);
  if(vWidth < 750){
    toMoonPage.value = 12;
    toNianPage.value = 15;
    toDuanOoPage.value = 17;
  }
</script>

<template>
  <ul class="history-index-case dp-flex-col">
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(10)"
      >
        New Year
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(11)">
        Dragon-boat  Festival
      </h5>
    </li>
    <li class="dp-flex">
      <h5 class="page-index dp-flex"
      @mousedown.stop
      @touchstart.stop
      @click="goToPage(12)">
        Mid-Autumn  Festival
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
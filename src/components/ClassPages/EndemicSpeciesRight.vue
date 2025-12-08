<script setup>
  import { computed, ref, Transition } from 'vue';
  import { usePageNavigation } from './goToPage';
  


  const emit = defineEmits(['flip'])
  const { goToPage } = usePageNavigation(emit)
</script>

<template>
    <div class="animal-right-contain dp-flex-col">
      <div class="animal-top-topic dp-flex">
        <div class="animal-photo-case">
          <img src="../../../public/Classes/Salamander.png" alt="" class="salamander">
        </div>
        <div class="animal-topic-text">

        </div>
      </div>
      <div class="animal-bottom-topic dp-flex">
        <div class="animal-topic-text">

        </div>
        <div class="animal-photo-case">
          <img src="../../../public/Classes/BlueBird.png" alt="" class="blue-bird">
        </div>
      </div>
    </div>

</template>

<style lang="scss" scoped>
  .animal-photo-case{
    width: 320px;
    height: 320px;
    img{
      width: 100%;
      height: 100%;
      border-radius: 8px;
    }
  }

  @keyframes drive{
    0% {
      right: -169px;
      opacity: 0;
    }
    1%{
      right: -100px;
      opacity: 1;
    }
    100% {
      right: 497px;
    }
  }
</style>

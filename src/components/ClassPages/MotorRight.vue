<script setup>
  import BasicButton from '@/components/BasicButton.vue';
  import { computed, ref, Transition } from 'vue';
  import IconBike from '../icons/SVG/IconBike.vue';
  import IconHelmetColor from '../icons/SVG/IconHelmetColor.vue';
  import IconStop from '../icons/SVG/IconStop.vue';
  

  const toggle = ref(1);
  const dialogs = {
    1: {
      paragraph: 'Taiwan’s “motorcycle waterfall” appears during rush hours, when thousands of motorcycles flow down ramps in dense waves, creating a striking visual symbol of the island’s busy urban mobility.'
    },
    2: {
        paragraph: 'Motorcycle modification in Taiwan blends creativity and personal style, from custom paint and decals to engine tuning and upgraded lights, reflecting each rider’s identity while shaping a vibrant and expressive street culture across the island.'
    },
  };
  function changeDialog(i){
    toggle.value = i;
  }
  const currentDialog = computed(() => {
    return dialogs[toggle.value];
  });
</script>

<template>
    <div class="motor-right-contain dp-flex-col">
      <div class="motor-right-title dp-flex-col">
        <h2 class="title-text">Motorcycle</h2>
        <p>Taiwan’s motorcycle culture defines daily life, providing fast, affordable mobility and shaping the island’s unique traffic, social habits, and modern transportation trends.</p>
      </div>
      <div class="motor-right-content dp-flex">
        <Transition name="fade" mode="out-in">
          <p :key="toggle" class="motor-right-dialog">{{currentDialog.paragraph}}</p>
        </Transition>
        <div class="motor-right-iamge-case dp-flex-col">
          <div class="motor-right-iamge"
          :class="{'toggle': toggle == 1,}"
          @click="changeDialog(1)"
          @mousedown.stop
          @touchstart.stop>
            <img src="../../../public\Classes\motor-right1.png" alt="">
            <div class="over-lay"></div>
          </div>
          <div class="motor-right-iamge"
          :class="{'toggle': toggle == 2,}"
          @click="changeDialog(2)"
          @mousedown.stop
          @touchstart.stop>
            <img src="../../../public\Classes\motor-right2.png" alt="">
            <div class="over-lay"></div>
          </div>
        </div>
      </div>
      <div class="motor-right-icons dp-flex">
        <BasicButton class="btn-black"><p>Try Now !</p></BasicButton>
        <div class="bottom-icons">
          <IconBike size= 69 class="icon-bike icon"/>
          <IconHelmetColor size="48" class="icon-helmet icon"/>
          <IconStop size="48" class="icon-stop icon"/>
        </div>
      </div>
    </div>

</template>

<style lang="scss" scoped>
  .motor-right-contain{
    justify-content: space-between;
    gap: 16px;
    height: 100%;
    padding-top: 60px;
    padding-bottom: 12px;
  }
  .motor-right-title{
    gap: 12px;
    width: 406px;
    padding-left: 40px;
  }
  p{
    line-height: 2;
    text-align: left;
    color: $color-fsContent;
  }
  .motor-right-dialog{
    width: 245px;
    padding-top: 24px;
  }
  .motor-right-content{
    align-items: center;
    width: 500px;
    transform: translateX(60px);
    justify-content: space-between;
  }
  .motor-right-iamge-case{
    width: 180px;
    gap: 20px;
    transform: rotate(7deg);
  }
  .motor-right-iamge{
    width: 180px;
    height: 180px;
    transform: scale(0.88);
    position: relative; 
    overflow: hidden;

  }
  img{
    width: 100%;
  }
  .motor-right-iamge.toggle{
    transform: scale(1);
    z-index: 10; 
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
  }
  .over-lay{
    position: absolute; 
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4); 
    z-index: 9; 
    opacity: 1; 
    pointer-events: none; 
    transition: opacity 0.3s ease;
  }

  .toggle .over-lay {
    opacity: 0; 
  }
  .fade-enter-active,.fade-leave-active {
  transition: opacity 0.2s ease;
  }

  .fade-enter-from,.fade-leave-to {
    opacity: 0;
  }
  .motor-right-icons{
    width: 80%;
    justify-content: space-between;
    margin: 0 auto; 
  }
  .bottom-icons{
    width: 190px;
    position: relative;
  }
  .icon{
    position: absolute;
  }
  .icon-bike{top: 15px;right: 0px;
    animation: 5s drive 0.5s linear infinite;
  }
  .icon-helmet{top: 0px;left: 100px;}
  .icon-stop{top: 15px;left: 180px;}
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
      right: 1100px;
    }
  }
</style>
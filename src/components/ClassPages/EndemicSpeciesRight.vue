<script setup>
  import { computed, onMounted, ref} from 'vue';
  import { usePageNavigation } from './goToPage';
  import IconLizard from '../icons/SVG/IconLizard.vue';
  import gsap from 'gsap';
  const emit = defineEmits(['flip'])
  const { goToPage } = usePageNavigation(emit)

  
  onMounted(()=>{
    gsap.set('.l1', { opacity: 1 });
    gsap.from('.l1', {
      duration: 1,
      repeat: -1,
      ease: "none",
      keyframes: {
        "0%":   { opacity: 0.8 },
        "50%":   { opacity: 0.4 },
        "100%":  { opacity: 0.8 },
      }
    });
  })
</script>

<template>
    <div class="animal-right-contain dp-flex-col">
      <div class="animal-top-topic dp-flex">
        <div class="animal-photo-case">
          <img src="/Classes/Salamander.png" alt="" class="salamander">
        </div>
        <div class="animal-topic-text dp-flex-col top">
          <IconLizard v-for="(index) in 5" 
          :key="index" 
          :class="`l${index}`" 
          class="lizard"
          :width='`${(index+4)*6}`' 
          :height='`${(index+5)*6}`' />
          <h5 class="animal-topic-title">{{$t('classes.animalName4')}}</h5>
          <p>{{$t('classes.animalIntro4')}}</p>
        </div>
      </div>
      <div class="animal-bottom-topic dp-flex">
        <div class="animal-topic-text dp-flex-col buttom">
          <h5 class="animal-topic-title">{{$t('classes.animalName5')}}</h5>
          <p>{{$t('classes.animalIntro5')}}</p>
        </div>
        <div class="animal-photo-case">
          <img src="/Classes/BlueBird.png" alt="" class="blue-bird">
        </div>
      </div>
    </div>

</template>

<style lang="scss" scoped>

  .animal-photo-case{
    width: 320px;
    height: 320px;
    position: relative;
    img{
      width: 100%;
      height: 100%;
      border-radius: 8px;
      position: absolute;
      box-shadow: 6px 6px 12px $color-fsTitle;
    }
    .salamander{
      top: -25%;
      left: -10%;
      transform: rotate(10deg);
    }
    .blue-bird{
      top: 15%;
      left: 15%;
      transform: rotate(-15deg);
    }
  }
  .animal-topic-text{
    text-align: left;
    width: 38%;
    justify-content: end;
    position: relative;
    &.buttom{
      width: 35%;
    }
    &animal-topic-text.top{
      padding-right: 6%;
    }
  }
  p{
    line-height: 2;
    color: $color-fsContent;
    hyphens:auto;
  }
  .animal-topic-title{
    color: $color-fsTitle;
  }
  .lizard{
    position: absolute;
    &.l1{top: 0;left: 0;}
    &.l2{top: 0;left: 0;}
    &.l3{top: 0;left: 0;}
    &.l4{top: 0;left: 0;}
    &.l5{top: 0;left: 0;}
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

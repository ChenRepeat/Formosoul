<script setup>
  import { computed, nextTick, onMounted, ref} from 'vue';
  import { usePageNavigation } from './goToPage';
  import IconLizard from '../icons/SVG/IconLizard.vue';
  import gsap from 'gsap';
  import IconBird1 from '../icons/SVG/IconBird1.vue';
  import IconBird2 from '../icons/SVG/IconBird2.vue';
  const aniCase = ref(null);
  const emit = defineEmits(['flip'])
  const { goToPage } = usePageNavigation(emit)
  const lizardAnimation=()=>{
    const items = gsap.utils.toArray(".lizard");
    const tl = gsap.timeline({ repeat: -1 });
    tl.set(items,{opacity: 0})
    items.forEach((item) => {
      tl.to(item, { 
        opacity: 1, 
        duration: 0.5, 
      })
      .to(item, {
        opacity: 0, 
        duration: 0.5, 
        delay: 0.5,
      });
    });
  }
  const birdAnimation = () => {
    const items = gsap.utils.toArray(".bird");
    const tl = gsap.timeline({ repeat: -1 });
    gsap.set(items, { opacity: 0 });

    items.forEach((item) => {
      tl.to(item, { 
        opacity: 1, 
        duration: 0 ,
      })
      .to(item, { 
        opacity: 0, 
        duration: 0,
        delay: 0.2
      });
    });
  };
  const moveBird = () => {
    const randomX = Math.random() * (aniCase.value.offsetWidth);
    const randomY = Math.random() * (aniCase.value.offsetHeight);
    const randomDuration = 1 + Math.random() * 2; 

    gsap.to(('.bird-ani-case'), {
      x: randomX,
      y: randomY,
      duration: randomDuration,
      ease: "power1.inOut",
      onComplete: moveBird
    });
  };
  
  onMounted(()=>{
    lizardAnimation();
    birdAnimation();
    moveBird();
  }
  )
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
          :width='`${((6-index)+4)*6}`' 
          :height='`${((6-index)+5)*6}`' />
          <h5 class="animal-topic-title">{{$t('classes.animalName4')}}</h5>
          <p>{{$t('classes.animalIntro4')}}</p>
        </div>
      </div>
      <div class="animal-bottom-topic dp-flex" ref="aniCase">
        <div class="bird-ani-case">
          <IconBird1 class="bird bd1" color="#00529F"/>
          <IconBird2 class="bird bd2" color="#00529F"/>
        </div>
        <div class="animal-topic-text dp-flex-col buttom" >
          <h5 class="animal-topic-title">{{$t('classes.animalName5')}}</h5>
          <p>{{$t('classes.animalIntro5')}}</p>
        </div>
        <div class="animal-photo-case" >
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
    &.l1{top: 15%;left: -10%;}
    &.l2{top: 0%;left: 10%;transform: rotate(30deg)}
    &.l3{top: -8%;left: 38%;transform: rotate(60deg)}
    &.l4{top: -6%;left: 65%;transform: rotate(90deg)}
    &.l5{top: -10%;left: 90%;transform: rotate(30deg)}
  }
  .bird-ani-case{
    width: 47px;
    height: 47px;
    position: relative;
    z-index: 99;
    .bird{transform: scale(1.2);position: absolute;top: 0;left: 0;filter: drop-shadow(0px 0px 1px $color-fsWhite);}
    .bd2{
      top: 10px;left: 3px;
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

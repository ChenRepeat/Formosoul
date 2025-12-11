<script setup>
  import { SizeType } from 'page-flip';
import BasicButton from '../BasicButton.vue';
import IconQuadStar from '../icons/SVG/IconQuadStar.vue';

  const dialogs = {
    1: 'Today, families enjoy magical mooncakes, lanterns, and storytelling. People cast blessing spells under the full moon, mixing ancient lunar magic with modern celebration and joy.',
    2: 'Villagers lit glowing lanterns, offered enchanted mooncakes, and gazed at the magical moon to honor Chang’e, Wu Gang, and the Jade Rabbit’s mystical deeds.',
  };

  const moonFesPhoto={
    topPhoto:{
      url:'/Classes/MoonFes3.png',
      size:'280px'
    },
    bottomPhoto:{
      url:'/Classes/MoonFes2.png',
      size:'240px'
    }
  }

</script>

<template>
<section class="moonfes-right dp-flex-col">
  <div class="moonfes-right-title">
    <h3>Mid-Autumn  Festival</h3>
    <p>Celebrate Chang’e, Wu Gang, and the Jade Rabbit with glowing lanterns, magical mooncakes, and moonlit blessings shared with family and friends.</p>
  </div>
  <div class="moonfes-contain-case dp-flex">
    <div class="dp-flex-col moonfes-contain-left">
      <p
        v-for="(text, key) in dialogs"
        :key="key"
      >{{ text }}</p>
      <div class="moonfes-img-case">
        <img src="/Classes/MoonFes4.png" alt="">
        <IconQuadStar 
        :size=60 
        :linearStart="'red'"
        :linearEnd="'green'"
        class="star star-1"/>
         <IconQuadStar 
        :size=80 
        :linearStart="'#666'"
        class="star star-2"/>
         <IconQuadStar 
        :size=70 

        class="star star-3"/>
      </div>
    </div>
    <div class="moonfes-right-photo">
      <img
        v-for="(item, key) in moonFesPhoto"
        :key="key"
        :src="item.url"
        :class="key"
      >
    </div>
  </div>
</section>
</template>

<style lang="scss" scoped>
  .moonfes-right{
    gap: 24px;
    justify-content: start;
    width: 90%;
    height: 100%;
  }
  .moonfes-contain-case{
    gap: 12px;
    p{
      margin-bottom: 24px;
      color:$color-fsContent;
    }
  }
  .moonfes-contain-left{
    position: relative;
    width: 50%;
  }
  .moonfes-img-case{
      position: absolute;
      bottom: -45%;
      left: -15%;
      img{
      width: 200px;
      height: 200px;
      transform: rotate(9deg);
      border-radius: 8px;
      box-shadow: 3px 3px 6px $color-fsContent;
    }
  }
  .moonfes-right-photo{
    position: relative;
    width: 50%;
  }
  
  .topPhoto{
    width: 280px;
    height: 280px;
    position: absolute;
    top: 0;left: 10%;
    transform: rotate(9deg);
    border-radius: 8px;
    box-shadow: 3px 3px 6px #000;
  }
  .bottomPhoto{
    width: 240px;
    height: 240px;
    position: absolute;
    left: 10%;bottom: -30%;
    transform: rotate(-9deg);
    border-radius: 8px;
    box-shadow: 3px 3px 6px #000;
  }
  .star{
    position: absolute;
    z-index: 10;
    &.star2{
      right: 20px;
      bottom: -10px;
    }
  }
</style>
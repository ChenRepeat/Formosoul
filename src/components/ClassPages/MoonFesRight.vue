<script setup>
  import { SizeType } from 'page-flip';
import BasicButton from '../BasicButton.vue';

  const dialogs = {
    1: 'Today, families enjoy magical mooncakes, lanterns, and storytelling. People cast blessing spells under the full moon, mixing ancient lunar magic with modern celebration and joy.',
    2: 'Villagers lit glowing lanterns, offered enchanted mooncakes, and gazed at the magical moon to honor Chang’e, Wu Gang, and the Jade Rabbit’s mystical deeds.',
  };

  const moonFesPhoto={
    topPhoto:{
      url:'/Classes/MoonFes2.png',
      size:'280px'
    },
    bottomPhoto:{
      url:'/Classes/MoonFes3.png',
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
    <div class="dp-flex-col">
      <p
        v-for="(text, key) in dialogs"
        :key="key"
      >{{ text }}</p>
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
  }
  .moonfes-contain-case{
    gap: 12px;
    p{
      width: 90%;
      margin-bottom: 24px;
      color:$color-fsContent;
    }
  }


  .topPhoto{
    width: 280px;
    height: 280px;
  }
  .bottomPhoto{
    width: 240px;
    height: 240px;
  }

</style>
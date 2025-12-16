<script setup>
 const dialogs = {
    1: {
        name: 'CHANG-E',
        intro: 'Moon goddess with magical powers, living in a glowing palace, admired each Mid-Autumn Festival.',
        imgSrc: 'Classes/change.png'
    },
    2: {
        name: 'YU-TOO',
        intro: 'Mystical rabbit brews elixirs on the moon, spreading blessings and inspiring lunar legends.',
        imgSrc: 'Classes/yutoo.png'
    },
    3: {
        name: 'WU-GANG',
        intro: 'Immortal chopping a magical tree endlessly on the moon, symbolizing patience and perseverance.',
        imgSrc: 'Classes/woogang.png'
    },
};

</script>

<template>
<section class="moonfes-left">
  <ul class="charactor-intro dp-flex-col">
    <li
      v-for="(dialogs, key, index) in dialogs"
      :key="key"
      class="intro-case dp-flex"
    >
      <div v-if="index == 2" class="inner-intro">
          <h5 style="text-align: right;">{{ dialogs.name}}</h5>
          <p>{{ dialogs.intro}}</p>
      </div>
      <img :src="dialogs.imgSrc" :alt="dialogs.name">
      <div v-if="index != 2" class="inner-intro">
          <h5 style="text-align: left;">{{ dialogs.name}}</h5>
          <p>{{ dialogs.intro}}</p>
      </div>
    </li>
  </ul>
  <img src="/Classes/moonfes1.png" alt="">
</section>
</template>

<style lang="scss" scoped>
  .moonfes-left{
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
    >img{
      box-shadow: unset;
      position: absolute;
      width: 100%;
      height: auto;bottom: -12%;left:0;

    }
    p{
      color: $color-fsContent;
    }
    h5{
      color: $color-fsTitle;
    }
  }
  .intro-case{
    width: 100%;
    gap: 48px;
    align-items: center;
    img{
      width: 120px;
      height: 120px;
      border-radius: 50%;
    }
  }
  .charactor-intro{
    gap: 16px;
  }

</style>
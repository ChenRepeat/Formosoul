<script setup>
const items = {
    1: {
        name: 'JumpRiver',
        imgSrc: 'Classes/DuanOo3.png'
    },
    2: {
        name: 'StandTheEgg',
        imgSrc: 'Classes/DuanOo2.png'
    },
};

</script>

<template>
  <section class="duan-right dp-flex-col">
    <div class="duan-right-top dp-flex">
      <img src="/Classes/DuanOo1.png" alt="BoaZongZu">
      <p>{{$t('classes.dragonBoatIntro8')}}</p>
    </div>
    <div class="duan-right-bottom dp-flex">
      <img
      v-for="(item) in items" :key="item.name" 
      :src="item.imgSrc" 
      :alt="item.name"
      :class="item.name">
    </div>
  </section>
</template>

<style lang="scss" scoped>
  .duan-right{
    width: 100%;
    height: 90%;
    justify-content: space-between;
  }
  img{
    box-shadow: 3px 3px 6px $color-fsTitle;
    border-radius: 8px;
  }
  .duan-right-top{
    gap: 34px;
    justify-content: space-between;
    p{
      padding-top: 13%;
      color: $color-fsContent;
      width: 45%;
    }
    img{
      width: 240px;
      height: 240px;
      transform: rotate(2deg);
    }
  }
  .duan-right-bottom{
    position: relative;
    height: 60%;
    .JumpRiver{
      width: 280px;
      height: 280px;
      position: absolute;
      top: -10%;
      left: -15%;
      transform: rotate(-8deg);
      z-index: 2;

    }
    .StandTheEgg{
      width: 360px;
      height: 360px;
      position: absolute;
      top: 23%;
      right: 0;
      transform: rotate(10deg);

    }
  }
</style>
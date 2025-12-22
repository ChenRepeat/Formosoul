<script setup>

  const dialogs = {
    1: 'classes.moonFesIntro1',
    2: 'classes.moonFesIntro2',
  };

  const moonFesPhoto={
    topPhoto:{
      url:'Classes/MoonFes3.png',
      size:'280px'
    },
    bottomPhoto:{
      url:'Classes/MoonFes2.png',
      size:'240px'
    }
  }

</script>

<template>
<section class="moonfes-right dp-flex-col">
  <div class="moonfes-right-title">
    <h3>{{$t('classes.moonFes')}}</h3>
    <p>{{$t('classes.moonFesIntro')}}</p>
  </div>
  <div class="moonfes-contain-case dp-flex">
    <div class="dp-flex-col moonfes-contain-left">
      <p
        v-for="(text, key) in dialogs"
        :key="key"
      >{{ $t(text) }}</p>
      <div class="moonfes-img-case">
        <img src="/Classes/MoonFes4.png" alt="">
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
  p{
      color:$color-fsContent;
  }
  .moonfes-right{
    gap: 24px;
    justify-content: start;
    width: 100%;
    height: 100%;
  }
  .moonfes-contain-case{
    gap: 12px;
    p{
      margin-bottom: 24px;
    }

  }
  .moonfes-contain-left{
    position: relative;
    width: 68%;
    p:not(:first-child){
      width: 84%;  
    }
  }
  .moonfes-img-case{
      position: absolute;
      bottom: -60%;
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
    top: 0;left: 5%;
    transform: rotate(9deg);
    border-radius: 8px;
    box-shadow: 3px 3px 6px #000;
  }
  .bottomPhoto{
    width: 240px;
    height: 240px;
    position: absolute;
    left: 0%;bottom: -40%;
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
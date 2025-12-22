<script setup>


</script>

<template>
  <section class="duan-left dp-flex-col">
    <div class="duan-title dp-flex-col">
      <h3>{{$t('classes.dragonBoatTitle')}}</h3>
      <p>{{$t('classes.dragonBoatIntro1')}}</p>
    </div>
    <div class="duan-mid-text">
      <p>{{$t('classes.dragonBoatIntro2')}} <br>
          {{$t('classes.dragonBoatIntro3')}}<br>
          {{$t('classes.dragonBoatIntro4')}}<br>
          {{$t('classes.dragonBoatIntro5')}}</p>
    </div>
    <div class="duan-qu-yuan dp-flex">
      <div class="intro dp-flex-col">
        <img src="/Classes/QiYuan.png" alt="">
        <h5>{{$t('classes.quYuan')}}</h5>
      </div>
      <p>{{$t('classes.dragonBoatIntro6')}}
        <br>{{$t('classes.dragonBoatIntro7')}}</p>
    </div>
  </section>
</template>

<style lang="scss" scoped>
  .duan-left{
    width: 100%;
    height: 90%;
    justify-content: space-between;
  }
  .duan-title{
    gap: 12px;
  }
  p{
    color: $color-fsContent;
  }

  .duan-qu-yuan{
    gap: 30px;
    align-items: center;
    img{
      width: 180px;
      height: 180px;
      border-radius: 50%;
    }
  }
</style>
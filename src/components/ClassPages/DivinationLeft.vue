<script setup>
import { computed, ref } from 'vue';
  const showDialog = ref(1); 
  const dialogs = {
    1: {
      name:'classes.bue1Name',
      intro:'classes.bue1Intro',
      imgUrl:'Classes/moBue.png',
    },
    2: {
      name:'classes.bue2Name',
      intro:'classes.bue2Intro',
      imgUrl:'Classes/chiobue.png',
    },
    3: {
      name:'classes.bue3Name',
      intro:'classes.bue3Intro',
      imgUrl:'Classes/xinbue.png',
    },
  };
    function changeDialog(i){
    showDialog.value = i;
  }
  const currentDialog = computed(() => {
    return dialogs[showDialog.value];
  });
</script>

<template>
<section class="divination-left dp-flex-col">
  <div class="title">
    <h2>{{ $t('classes.divinationTitle') }}</h2>
    <p>{{ $t('classes.divinationIntro') }}</p>
  </div>
  <div class="bua-bue-intro">
    <h5>{{ $t('classes.bueTitle') }}</h5>
    <p>{{ $t('classes.bueIntro') }}</p>
  </div>
  <div 
    class="intro-corner dp-flex-col"
    @mousedown.stop
    @touchstart.stop>
    <div class="img-case dp-flex">
      <div class="img-locate"
       v-for="(dialog,key) in dialogs"
       :class="{'shadow': showDialog != key,}">
        <img 
        :key="key"
        :src="dialog.imgUrl" 
        :alt="dialog.name"
        class="mask"
        @click="changeDialog(key)"
        >
        <div class="over-lay mask"></div>
      </div>
    </div>
    <transition name="fade" mode="out-in">
      <div class="intro-text dp-flex-col"
        :key="showDialog">
            <h5>{{$t(currentDialog.name)}}</h5>
            <p>{{ $t(currentDialog.intro) }}</p>
        </div>
    </transition>
  </div>
</section>

</template>

<style lang="scss" scoped>
p{color: $color-fsContent;}
.divination-left{
  height: 100%;
  gap: 60px;
  justify-content: start;
  .bua-bue-intro{
    text-align: left;
  }
}
.intro-corner{
  gap: 40px;
}
.img-case{
  justify-content: center;
  gap: 40px;

  .img-locate{
    position: relative;
    width: 120px;
    height: 120px;
  }

  img{
    width: 100%;
    height: 100%;
  }
  .img-locate:not(:first-child)::before{
    content: '';
    height: 80%;
    position: absolute;
    border: 1px dashed;
    left: -20px;top: 10%;
  }
  
}

.mask{
  mask-image: url('/Classes/Triangle.png');
  mask-mode: alpha ;
  mask-size: contain;
  mask-repeat: no-repeat;
  filter: drop-shadow(100px 100px 100px rgba(0, 0, 0, 1));

}
.over-lay{
  transition: opacity 0.3s ease;
  opacity: 0;     
  position: absolute; 
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color:$color-fsTitle;

}
.shadow {
  .over-lay{
      z-index: 9; 
      opacity: 0.6; 
      pointer-events: none;
  }
}
  
.fade-enter-active,.fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from,.fade-leave-to {
  opacity: 0;
}
</style>
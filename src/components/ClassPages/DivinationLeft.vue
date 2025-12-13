<script setup>
import { computed, ref } from 'vue';
  const showDialog = ref(1); 
  const dialogs = {
    1: {
      name:'Mo-Bue',
      introEn:'Flat signs fall silent, magic fades, the spirits choose not to answer.',
      introCn:'平符靜落，魔力消散，靈界選擇沉默不語。',
      imgUrl:'/Classes/MoonFes3.png',
    },
    2: {
      name:'Chio-Bue',
      introEn:'Twin curves shimmer with mirth, spirits laugh and deny the caster’s wish.',
      introCn:'雙弧閃爍笑意，靈體戲笑，否定施術者的請求。',
      imgUrl:'/Classes/MoonFes3.png',
    },
    3: {
      name:'Xin-Bue',
      introEn:'One light, one shadow align, magic confirms the spell and grants approval.',
      introCn:'一光一影相合，魔法確認儀式，賜予肯定回應。',
      imgUrl:'/Classes/MoonFes3.png',
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
    <h2>Divination</h2>
    <p>Taiwanese divination uses magical sticks and lot casting to seek guidance, predict luck, and explore mystical insights.</p>
  </div>
  <div class="bua-bue-intro">
    <h5>BUA-BUE</h5>
    <p>Wooden or bamboo blocks are tossed magically to answer yes/no questions, revealing luck and divine will.</p>
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
            <h5>{{currentDialog.name}}</h5>
            <p>{{ currentDialog.introEn }}</p>
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
    width: 90px;
    height: 90px;
    filter: drop-shadow(100px 100px 100px rgba(0, 0, 0, 1));

  }
  .img-locate:not(.shadow){
    filter: drop-shadow(100px 100px 100px rgba(0, 0, 0, 1));
    
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
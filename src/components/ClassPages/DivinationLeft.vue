<script setup>
import { computed, ref } from 'vue';
  const showDialog = ref(1); 
  const dialogs = {
    1: {
      name:'Mo-Bue',
      introEn:'The curved backs turn upward, shielding secrets within. A dark omen bars your path; the ancient forces deny your request. Do not push further.',
      introCn:'隆起的背部向上翻轉，將秘密緊鎖其中。這是一個拒絕的暗影徵兆，遠古力量否決了你的請求，不可強行推進。',
      imgUrl:'Classes/moBue.png',
    },
    2: {
      name:'Chio-Bue',
      introEn:'Two open faces stare back, mocking your hesitation. The ether finds your query clouded; clear your mind and cast the runes once more for truth.',
      introCn:'兩個張開的平面凝視著你，嘲弄你的遲疑。乙太界認為你的提問過於模糊，請清空思緒再次施放符文。',
      imgUrl:'Classes/chiobue.png',
    },
    3: {
      name:'Xin-Bue',
      introEn:'A perfect harmony of light and shadow lies before you. The cosmos whispers consent, allowing your magic to flow freely into the woven reality.',
      introCn:'光與影達成完美的和諧，宇宙對你低聲許諾，准許你的魔力在交織的現實中自由流動。',
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
<script setup>
  import { computed, ref, Transition } from 'vue';

  const zi10 = ref(1);
  const dialogs = {
    1: {
        paragraph: 'Today, people enjoy magical fireworks, lantern parades, and family feasts. Red envelopes hold blessing charms, and online spells, music, and games mix ancient magic with modern fun.', 
      },
    2: {
        paragraph: 'Friends ignite brilliant fireworks under a moonlit sky, summoning sparks of enchantment that dance like ancient wishes in the winter air.', 
      },
    3: {
        paragraph: 'Crimson charms and mystical blessings shimmer on the doorpost, inviting good fortune to swirl in with softly glowing festive magic.', 
      },
  };
  function changeDialog(i){
    zi10.value = i;
  }
  const currentDialog = computed(() => {
    return dialogs[zi10.value];
  });
</script>

<template>
  <section class="newyear-right">
      <div class="newyear-right-contain dp-flex-col">
        <div class="newyear-right-photo-case">
        <div class="newyear-right-locate-case right-1"
         :class="{'zi10': zi10 == 1,}"
          @click="changeDialog(1)"
          @mousedown.stop
          @touchstart.stop
          @mouseover.stop
          @mouseenter.stop
          >
          <img 
            src="/Classes/NewYear3.png" 
            alt="" 
            class="newyear-right-photo"
            >
            <div class="over-lay"></div>
          </div>
        <div 
          class="newyear-right-locate-case right-2"
          :class="{'zi10': zi10 == 2,}"
          @click="changeDialog(2)"
          @mousedown.stop
          @mouseenter.stop
          @touchstart.stop
          @mouseover.stop>
          
          <img 
            src="/Classes/NewYear4.png" 
            alt="" 
            class="newyear-right-photo"
            >
          <div class="over-lay"></div>
        </div>
        <div class="newyear-right-locate-case right-3"
          :class="{'zi10': zi10 == 3,}"
          @click="changeDialog(3)"
          @mousedown.stop
          @mouseenter.stop
          @mouseover.stop
          @touchstart.stop>
          <img 
            src="/Classes/NewYear5.png" 
            alt="" 
            class="newyear-right-photo "
           >
          <div class="over-lay"></div>
        </div>
        </div>
        <Transition name="fade" mode="out-in">
          <div class="newyear-right-text dp-flex-col"
          :key="zi10">
              <p>{{ currentDialog.paragraph }}</p>
          </div>
        </Transition>
      </div>
  </section>
</template>

<style lang="scss" scoped>
  .newyear-right{
    width: 100%;
    height: 100%;
    position: relative;
    padding-left: 30%;
    text-align: left;
  }
  .op{
    opacity: 0;
  }
  .newyear-right-contain{
    height: 100%;
    gap: 20%;
    padding-top: 10%;
  }
  .newyear-right-photo-case{
    height: 40%;
    position: relative;
  }
  .newyear-right-locate-case{
    width: 320px;
    height: 320px;
    position: absolute;
    transition: transform 0.3s ease, z-index 0.3s, box-shadow 0.3s;
    border-radius: 8px;
    overflow: hidden;
  }

  .newyear-right-photo{
    width: 100%;
    height: 100%;
    position: relative;
  }
  .over-lay{
    position: absolute; 
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4); 
    z-index: 9; 
    opacity: 1; 
    pointer-events: none; 
    transition: opacity 0.3s ease;
  }

  .newyear-right-locate-case.zi10{
    z-index: 10; 
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
  }

  .newyear-right-locate-case.zi10 .over-lay {
    opacity: 0; 
  }
  .right-1{
    top: 15%;left: 0%;
    transform: rotate(-10deg);
    z-index: 8;
     >.over-lay{
    background-color: rgba(0, 0, 0, 0.6); 
  }
  }
  .right-2{
    top: 10%;left: 20%;
    z-index: 7;
    transform: rotate(5deg);

  }
  .right-3{
    top: 5%; left: 30%;
    transform: rotate(20deg);
    z-index: 6;
    >.over-lay{
    background-color: rgba(0, 0, 0, 0.6); 
    }
  }

  .newyear-right-content{
    width: 100%;
    text-align: left;
    transition: opacity 0.5s ease;
    opacity: 1;
  }
  .newyear-right-diolog2{
    padding-left: 15%;
  }
  p{
    padding-left: 10%;
    color: $color-fsContent;
    line-height: 2;
    
  }
.fade-enter-active,.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,.fade-leave-to {
  opacity: 0;
}
</style>
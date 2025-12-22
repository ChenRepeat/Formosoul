<script setup>
  import { computed, ref, Transition } from 'vue';

  const zi10 = ref(1);
  const dialogs = {
    1: {
        titleTop: 'classes.motorIntro1Title',
        paragraphTop: 'classes.motorIntro1text', 
        titleBottom: 'classes.motorIntro2Title',
        paragraphBottom: 'classes.motorIntro2Text'
    },
    2: {
        titleTop: 'classes.motorIntro3Title',
        paragraphTop: 'classes.motorIntro3Text', 
        titleBottom: 'classes.motorIntro4Title',
        paragraphBottom: 'classes.motorIntro4Text'
    },
    3: {
        titleTop: 'classes.motorIntro5Title',
        paragraphTop: 'classes.motorIntro5Text', 
        titleBottom: 'classes.motorIntro6Title',
        paragraphBottom: 'classes.motorIntro6Text'
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
  <div class="motor-left-main">
    <div class="red"></div>
    <div class="motor-contain dp-flex-col">
      <div class="motor-photo-case">
      <div class="motor-locate-case left-1"
       :class="{'zi10': zi10 == 1,}"
        @click="changeDialog(1)"
        @mousedown.stop
        @touchstart.stop>
        <img 
          src="/Classes/motor-left1.png" 
          alt="" 
          class="motor-photo"
          >
        <div class="over-lay"></div>
      </div>
      <div 
        class="motor-locate-case left-2"
        :class="{'zi10': zi10 == 2,}"
        @click="changeDialog(2)"
        @mousedown.stop
        @touchstart.stop>
        <img 
          src="/Classes/motor-left2.png" 
          alt="" 
          class="motor-photo"
          >
        <div class="over-lay"></div>
      </div>
      <div class="motor-locate-case left-3"
        :class="{'zi10': zi10 == 3,}"
        @click="changeDialog(3)"
        @mousedown.stop
        @touchstart.stop>
        <img 
          src="/Classes/motor-left3.png" 
          alt="" 
          class="motor-photo "
         >
        <div class="over-lay"></div>
      </div>
      </div>
      <Transition name="fade" mode="out-in">
        <div class="motor-text dp-flex-col"
        :key="zi10">
          <div class="motor-diolog1 motor-content">
            <h5>{{$t(currentDialog.titleTop)}}</h5>
            <p>{{ $t(currentDialog.paragraphTop) }}</p>
          </div>
          <div class="motor-diolog2 motor-content">
            <h5>{{ $t(currentDialog.titleBottom) }}</h5>
            <p>{{ $t(currentDialog.paragraphBottom) }}</p>
          </div>
        </div>
      </Transition>
    </div>
  </div>
</template>

<style lang="scss" scoped>
  .motor-left-main{
    width: 100%;
    height: 100%;
    position: relative;
  }
  .red{
    width: 100px;
    height: 100px;
    background-color: #fff;
    position: absolute;
    top: -50px;
    transition: opacity 0.3s ease;
    z-index: -10;
  }
  .op .red{
    opacity: 0;
  }
  .motor-contain{
    height: 100%;
    gap: 60px;
  }
  .motor-photo-case{
    height: 50%;
    position: relative;
  }
  .motor-locate-case{
    width: 280px;
    height: 280px;
    position: absolute;
    transition: transform 0.3s ease, z-index 0.3s, box-shadow 0.3s;
  }

  .motor-photo{
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

  .motor-locate-case.zi10{
    z-index: 10; 
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
  }

  .motor-locate-case.zi10 .over-lay {
    opacity: 0; 
  }
  .left-1{
    top: 10%;left: -10%;
    transform: rotate(8.9deg);
    z-index: 8;
  }
  .left-2{
    top: 20%;left: 35%;
    z-index: 7;
    transform: rotate(-13.9deg);

  }
  .left-3{
    top: 10%; left: 320px;
    transform: rotate(-4.1deg);
    z-index: 6;
  }
  .motor-text{
    gap: 40px;
  }
  .motor-content{
    width: 100%;
    text-align: left;
    transition: opacity 0.5s ease;
    opacity: 1;
  }
  .motor-diolog1{
    padding-right: 15%;
  }
  .motor-diolog2{
    padding-left: 20%;
  }
  p{
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
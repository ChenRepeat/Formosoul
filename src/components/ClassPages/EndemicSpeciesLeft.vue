<script setup>
  import { computed, ref, Transition } from 'vue';

  const zi10 = ref(1);
  const dialogs = {
    1: {
        title: 'Formosan Black Bear',
        paragraph: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
      },
    2: {
        title: 'Formosan Sika Deer',
        paragraph: 'A small, elegant deer once nearly extinct in Taiwan, now restored through conservation efforts..', 
      },
    3: {
        title: 'Formosan Rock Macaque',
        paragraph: 'Taiwan’s only native monkey species, highly social and adaptable, often seen in forests and mountainous regions.', 
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
  <div class="animal-left-main">
    <div class="animal-title">
      <h2>Endemic Species</h2>
      <p>Taiwan’s unique wildlife—from black bears to blue magpies—shows rich biodiversity and highlights the importance of protecting its endemic species.</p>
    </div>
    <div class="animal-contain dp-flex-col">
      <div class="animal-photo-case">
      <div class="animal-locate-case left-1"
       :class="{'zi10': zi10 == 1,}"
        @click="changeDialog(1)"
        @mousedown.stop
        @touchstart.stop>
        <img 
          src="../../../public/Classes/FomoBear.png" 
          alt="" 
          class="animal-photo"
          >
        <div class="over-lay"></div>
      </div>
      <div 
        class="animal-locate-case left-2"
        :class="{'zi10': zi10 == 2,}"
        @click="changeDialog(2)"
        @mousedown.stop
        @touchstart.stop>
        <img 
          src="../../../public/Classes/FomoDear.png" 
          alt="" 
          class="animal-photo"
          >
        <div class="over-lay"></div>
      </div>
      <div class="animal-locate-case left-3"
        :class="{'zi10': zi10 == 3,}"
        @click="changeDialog(3)"
        @mousedown.stop
        @touchstart.stop>
        <img 
          src="../../../public\Classes\FomoWuKong.png" 
          alt="" 
          class="animal-photo "
         >
        <div class="over-lay"></div>
      </div>
      </div>
      <Transition name="fade" mode="out-in">
        <div class="animal-text dp-flex-col"
        :key="zi10">
            <h5>{{currentDialog.title}}</h5>
            <p>{{ currentDialog.paragraph }}</p>
        </div>
      </Transition>
    </div>
  </div>
</template>

<style lang="scss" scoped>
  .animal-left-main{
    width: 100%;
    height: 100%;
    position: relative;
    padding: 0 20px;
    text-align: left;
  }
  h2{
    text-align: center;
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
  .animal-contain{
    height: 100%;
    gap: 60px;
  }
  .animal-photo-case{
    height: 50%;
    position: relative;
  }
  .animal-locate-case{
    width: 300px;
    height: 300px;
    position: absolute;
    transition: transform 0.3s ease, z-index 0.3s, box-shadow 0.3s;
    border-radius: 20px;
    overflow: hidden;

  }

  .animal-photo{
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
    background-color: rgba(255, 255, 255, 0.4); 
    z-index: 9; 
    opacity: 1; 
    pointer-events: none; 
    transition: opacity 0.3s ease;
  }

  .animal-locate-case.zi10{
    z-index: 10; 
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
  }

  .animal-locate-case.zi10 .over-lay {
    opacity: 0; 
  }
  .left-1{
    top: 20%;left: 10%;
    transform: rotate(-15deg);
    z-index: 8;
     >.over-lay{
    background-color: rgba(255, 255, 255, 0.6); 
  }
  }
  .left-2{
    top: 18%;left: 20%;
    z-index: 7;
  }
  .left-3{
    top: 16%; left: 30%;
    transform: rotate(15deg);
    z-index: 6;
    >.over-lay{
    background-color: rgba(255, 255, 255, 0.6); 
    }
  }

  .animal-content{
    width: 100%;
    text-align: left;
    transition: opacity 0.5s ease;
    opacity: 1;
  }
  .animal-diolog2{
    padding-left: 15%;
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
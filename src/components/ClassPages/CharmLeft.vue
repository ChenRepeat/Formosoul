<script setup>
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import { computed, ref } from 'vue';

  const clickCharm = ref('0');
  const charmsRow1 = {
    1: {
        name: '1',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/charms/charm1.png',
    },
    2: {
        name: '2',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/charms/charm2.png',
    },
    3: {
        name: '3',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/charms/charm3.png',
    },
    4: {
        name: '4',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/charms/charm4.png',
    },
    5: {
        name: '5',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/charms/charm5.png',
    },
    6: {
        name: '6',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/charms/charm6.png',
    },
    7: {
        name: '7',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/charms/charm7.png',
    }}
  const charmsRow2 = {
    8: {
        name: '8',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/charms/charm8.png',
    },
    9: {
        name: '9',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/charms/charm9.png',
    },
    10: {
        name: '10',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/charms/charm10.png',
    },
    11: {
        name: '11',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/charms/charm11.png',
    },
    12: {
        name: '12',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/charms/charm12.png',
    },
    13: {
        name: '13',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/charms/charm13.png',
    },
    14: {
        name: '14',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/charms/charm14.png',
    },
  };

  function changeIntro(i){
      clickCharm.value = String(i);
  }
  const currentIntro = computed(() => {
    const selectedId = clickCharm.value;
    if (charmsRow1[selectedId]) {
      return charmsRow1[selectedId];
    }
    if (charmsRow2[selectedId]) {
      return charmsRow2[selectedId];
    }
});
</script>

<template>
  <section class="charm-left dp-flex-col">
    <div class="charm-title">
      <h2>Spells & Charms</h2>
      <p>Taiwanese charms and talismans use magic symbols to protect, bring luck, and ward off evil in daily life and festivals.</p>
    </div>
    <div class="intro-locate dp-flex-col">
      <div class="charm-top-row dp-flex">
        <div class="shadow-case"
          v-for="(charm, key) in charmsRow1" 
          :key="key"
          @click="changeIntro(key)"
          @mousedown.stop
          @touchstart.stop>
          <img 
              :src="charm.imgUrl" 
              :alt="charm.name"
              @click="changeIntro(key)"
              @mousedown.stop
              @touchstart.stop>
          <div class="over-lay"
          :class="{ 'show': clickCharm != key }"></div>
        </div>
      </div>
      <div class="charm-bottom-row dp-flex">
      <div class="shadow-case"
        v-for="(charm, key) in charmsRow2" 
        :key="key"
        @click="changeIntro(key)"
        @mousedown.stop
        @touchstart.stop>
        <img 
            :src="charm.imgUrl" 
            :alt="charm.name"
            @click="changeIntro(key)"
            @mousedown.stop
            @touchstart.stop>
        <div class="over-lay"
        :class="{ 'show': clickCharm != key }"></div>
      </div>
      </div>
      <div class="intro-case dp-flex"
          v-if="clickCharm!=0"
          @click="changeIntro(0)"
          @mousedown.stop
          @touchstart.stop>
          <img
          :src="currentIntro.imgUrl" 
          :alt="currentIntro.name">
          <div class="intro-text dp-flex-col"
          >
          <h6>
            <FontAwesomeIcon icon="fa-solid fa-xmark" 
            class="close"
            @click="changeIntro(0)"
            @mousedown.stop
            @touchstart.stop/>
          </h6>
          <div class="intro-img-case dp-flex">
            <img 
            v-for="(item, key) in currentIntro.recipe" :key="key"
            :src="item.imgUrl"
            :alt="item.name">
          </div>
          <p>{{ currentIntro.intro }}</p>
          </div>
      </div>
      
  </div>
    

  </section>
</template>

<style lang="scss" scoped>
  .charm-title{
    p{
      color: $color-fsContent;
    }
  }
  .charm-left{
    width: 100%;
    height: 100%;
  }
  .charm-top-row{
    gap: 16px;

    img{
      width: 50px;
      cursor: pointer;
    }
  }
  .charm-bottom-row{
    
    gap: 16px;
    justify-content: end;
    img{
      width: 50px;
      cursor: pointer;
    }
  }
  .intro-locate{
    position: relative;
    margin-top: 92px;
    gap: 78px;
  }
  .intro-case{
    width: 90%;
    height: auto;
    max-height: fit-content;
    position: absolute;
    z-index: 20;
    background-color: #fff;
    padding: 100px 80px;
    border-radius: 8px;
    top: 50%; 
    left: 50%;
    transform: translate(-50%, -50%);
    align-items: center;
    gap: 40px;
    img{
      width: 140px;
      height: 140px;
    }
    p{
      color: $color-fsContent;
    }
    .close{
      position: absolute;
      top: 16px;right: 16px;
      
    }
  }
  .intro-img-case{
    gap: 40px;
    img{
      width: 50px;
      height: 50px;
      border-radius: 50%;
    }
  }
  .intro-text{
    justify-content: center;
    
  }
  .shadow-case{
    position: relative;
  }
  .over-lay{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: black; 
    opacity: 0; 
    z-index: 10;
    transition: all 0.5s ease;
}
.show{
    opacity: 0.8;
}
</style>
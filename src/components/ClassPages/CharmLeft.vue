<script setup>
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import gsap from 'gsap';
  import { computed , nextTick, onMounted, ref , Transition, watch } from 'vue';
  import { useclassesStore } from '@/stores/classes';
  import { useMemberStore } from '@/stores/member';

  const memberStore = useMemberStore();
  const classStore = useclassesStore();
  const rightArrow = ref(null);
  const clickCharm = ref('0');
  const showText = ref('0');
  const charmsRow1 = ref({
    1: {
        name: 'classes.charmName1',
        intro: 'classes.charmText1', 
        imgUrl:'Classes/charms/charm1.png',
    },
    2: {        
        name: 'classes.charmName2',
        intro: 'classes.charmText2', 
        imgUrl:'Classes/charms/charm2.png',
    },
    3: {
        name: 'classes.charmName3',
        intro: 'classes.charmText3', 
        imgUrl:'Classes/charms/charm3.png',
    },
    4: {
        name: 'classes.charmName4',
        intro: 'classes.charmText4', 
        imgUrl:'Classes/charms/charm4.png',
    },
    5: {
        name: 'classes.charmName5',
        intro: 'classes.charmText5', 
        imgUrl:'Classes/charms/charm5.png',
    },
    6: {
        name: 'classes.charmName6',
        intro: 'classes.charmText6', 
        imgUrl:'Classes/charms/charm6.png',
    },
    7: {
        name: 'classes.charmName7',
        intro: 'classes.charmText7', 
        imgUrl:'Classes/charms/charm7.png',
    }})
  const charmsRow2 = ref({
    8: {
        name: 'classes.charmName8',
        intro: 'classes.charmText8', 
        imgUrl:'Classes/charms/charm8.png',
    },
    9: {
        name: 'classes.charmName9',
        intro: 'classes.charmText9',  
        imgUrl:'Classes/charms/charm9.png',
    },
    10: {
        name: 'classes.charmName10',
        intro: 'classes.charmText10', 
        imgUrl:'Classes/charms/charm10.png',
    },
    11: {
        name: 'classes.charmName11',
        intro: 'classes.charmText11', 
        imgUrl:'Classes/charms/charm11.png',
    },
    12: {
        name: 'classes.charmName12',
        intro: 'classes.charmText12', 
        imgUrl:'Classes/charms/charm12.png',
    },
    13: {
        name: 'classes.charmName13',
        intro: 'classes.charmText13', 
        imgUrl: 'Classes/charms/charm13.png'
    },
    14: {
        name: 'classes.charmName14',
        intro: 'classes.charmText14',  
        imgUrl:'Classes/charms/charm14.png',
    },
  });
const breathAnimate=()=>{
      gsap.killTweensOf('.userDrowed .over-lay');
      gsap.set('.userDrowed .over-lay', { '--active-blink': 0.8 });

      gsap.to('.userDrowed .over-lay', {
        duration: 1,
        repeat: -1,
        ease: "none",
        keyframes: {
          "0%":   { "--active-blink": 0.8 },
          "50%":   { "--active-blink": 0.4 },
          "100%":  { "--active-blink": 0.8 },
        }
      });
}
const stopAnimate=()=>{
  gsap.killTweensOf('.userDrowed .over-lay');
  gsap.to('.userDrowed .over-lay', {
      duration: 1,
      repeat: -1,
      ease: "none",
      '--active-blink': 0, 
      keyframes: {
          "0%":   { "--active-blink": 0 },
          "100%":  { "--active-blink": 0},
      }})
}
function changeIntro(i) {
  const previousCharm = clickCharm.value;
  clickCharm.value = String(i);
  showText.value = String(i);

  if (clickCharm.value != '13') {
    if (previousCharm == '13' || !gsap.isTweening('.userDrowed .over-lay')) {
      breathAnimate();
    }
  } else {
    gsap.killTweensOf('.userDrowed .over-lay');
    gsap.set('.userDrowed .over-lay', { '--active-blink': 0 });
    stopAnimate();
  }
}
 const currentIntro = computed(() => {
    const selectedId = showText.value;
    if (charmsRow1.value[selectedId]) {
      return charmsRow1.value[selectedId];
    }
    if (charmsRow2.value[selectedId]) {
      return charmsRow2.value[selectedId];
    }
});
watch(
  () => classStore.imgUrl, // 監聽目標
  (imgURL) => {
    if (imgURL != 'Classes/charms/charm13.png') {
    charmsRow2.value[13].imgUrl = imgURL;
    clickCharm.value = '13';
    changeIntro(13);
    stopAnimate();
  }else{
    clickCharm.value = '0';
    charmsRow2.value[13].imgUrl = imgURL;
    changeIntro(0);
    gsap.killTweensOf('.userDrowed .over-lay');
    gsap.set('.userDrowed .over-lay', { '--active-blink': 0.8 });
    breathAnimate(); 
}
}
);
watch(
  () => memberStore.gameData.charm.img, 
  (newImg) => {
    if (newImg && newImg !== '0') {
      charmsRow2.value[13].imgUrl = newImg;
    }
  },
);
onMounted(async () => {
  await nextTick(); // 確保 DOM 渲染完畢
  const userDraw = memberStore.gameData.charm.img;
  if (userDraw && userDraw !== '0') {charmsRow2.value[13].imgUrl = userDraw;}
  
  if (clickCharm.value != '13') {
    breathAnimate();

  }
});
const buyWord = computed(() => {
  if (charmsRow2.value[13].imgUrl == 'Classes/charms/charm13.png') {
    return 'classes.drawText1';
  } else {
    return 'classes.drawText2';
  }
});
</script>

<template>
  <section class="charm-left dp-flex-col">
    <div class="charm-title">
      <h2>{{$t('classes.charmTitle')}}</h2>
      <p>{{$t('classes.charmIntro')}}</p>
    </div>
    <div class="intro-locate dp-flex-col"
    >
      <div class="charm-top-row dp-flex"
        @mousedown.stop
        @touchstart.stop>
        <div class="shadow-case"
          v-for="(charm, key) in charmsRow1" 
          :key="key"
          @click="changeIntro(key)"
          @mousedown.stop
          @touchstart.stop
          >
          <img 
              :src="charm.imgUrl" 
              :alt="charm.name"
              @click="changeIntro(key)"
              @mousedown.stop
              @touchstart.stop>
          <div class="over-lay"
          :class="{ 'show': clickCharm != key }"
          :key="key"></div>
        </div>
      </div>
      <div class="charm-bottom-row dp-flex">
      <div class="shadow-case"
        v-for="(charm, key) in charmsRow2" 
        :key="key"
        @click="changeIntro(key)"
        @mousedown.stop
        @touchstart.stop
        :class="{'userDrowed':key==13}">
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
      <Transition name="fade" mode="out-in">

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
          <p>{{ $t(currentIntro.intro) }}</p>
          <p v-if="showText==13" class="userDraw">{{$t(buyWord)}}
            <font-awesome-icon icon="fa-solid fa-circle-right" class="rightArrow"/>
          </p>
          </div>
      </div>
      </Transition>
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
    margin-top: 10%;
    gap: 36px;
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
    height: 180px;
    position: relative;
    &:hover .over-lay {
      opacity: 0 !important;
      transition: opacity 0.3s ease !important;
    }
  }

  .over-lay{
    --active-blink: 0.8;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 180px;
    background-color: black; 
    opacity: 0; 
    z-index: 10;
    transition: all 0.5s ease;
  }
  .over-lay.show {
    opacity: 0.8 !important;
    transition: opacity 0.5s ease;
  }
  .userDrowed .over-lay {
    opacity: var(--active-blink, 0) !important; 
    transition: none !important;
    height: 180px;
    box-sizing: border-box;
  }
  .over-lay:not(.show) {
    opacity: 0;
  }
  .userDrowed .over-lay:not(.show) {
    opacity: var(--active-blink, 0) !important;
    transition: none !important;
  }

  .userDrowed{
    height: 180px;
    box-sizing: border-box;
  }
.rightArrow{
  animation: changeColor 0.5s ease infinite;
}
@keyframes changeColor {
  0%{color:$color-fsGold}
  50%{color: $color-fsTitle;}
  100%{color:$color-fsGold}
}

.fade-enter-active,.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,.fade-leave-to {
  opacity: 0;
}

</style>
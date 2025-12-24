<script setup>
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import { computed , ref , Transition, watch } from 'vue';
  const rightArrow = ref(null);
  const clickCharm = ref('0');
  const showText = ref('0');
  const props = defineProps(['sharedImage']);
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
        imgUrl:'Classes/charms/charm13.png',
    },
    14: {
        name: 'classes.charmName14',
        intro: 'classes.charmText14',  
        imgUrl:'Classes/charms/charm14.png',
    },
  });

  function changeIntro(i){
    clickCharm.value = String(i);
    showText.value = String(i)
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
watch(() => props.sharedImage, (newVal) => {
  if (newVal != 'Classes/charms/charm13.png') {
    charmsRow2.value[13].imgUrl = newVal;
    clickCharm.value = '13';
  }else{
    clickCharm.value = '0';
    charmsRow2.value[13].imgUrl = newVal;
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
        :class="{userDrowed:key==13}">
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
      width: 70px;
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
    &:hover{
      opacity: 0;
    }
}
.show{
    opacity: 0.8;
}
.userDraw{
  margin-top: 5%;
  text-align: right;
}
.userDrowed{
  animation: changeShadow 1s ease infinite;
}
.rightArrow{
  animation: changeColor 0.5s ease infinite;
}
@keyframes changeColor {
  0%{color:$color-fsGold}
  50%{color: $color-fsTitle;}
  100%{color:$color-fsGold}
}
@keyframes changeShadow {
  0%{box-shadow: 0px 0px 18px $color-fsRed;}
  50%{box-shadow: 0px 0px 6px $color-fsGold;}
  100%{box-shadow: 0px 0px 18px $color-fsRed;}
}
.fade-enter-active,.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,.fade-leave-to {
  opacity: 0;
}
</style>
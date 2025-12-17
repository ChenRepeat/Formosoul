<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ref } from 'vue';

  const stickResult = ref(0);
  const chouqianFes= ref(0);
  const pics = {
    picLeft: {
      imgUrl:'Classes/bue1.jpg',
    },
    picMid: {
      imgUrl:'Classes/bue3.jpg',
    },
    picRight: {
      imgUrl:'Classes/bue2.jpg',
    },
  };
  const chouqianResult={
    1: {name:'大吉',textEn:'The High Council grants you access to primordial magic; your ultimate quest shall succeed now.',textCn:'魔法議會准許您接觸太初魔法；您最終的任務現在將會成功。'},
    2: {name:'中吉',textEn:'A profound connection to the celestial sphere assures a prosperous year of powerful enchantments.',textCn:'與天體領域的深切連結確保您擁有一個充滿強大魔法的繁榮學年。'},
    3: {name:'小吉',textEn:'Remember to properly calibrate your crystal ball; minor errors are overcome by focused intent.',textCn:'記得正確校準您的水晶球；小錯誤能藉由專注的意圖來克服。'},
    4: {name:'吉',textEn:'A dusty, forgotten scroll in the ancient library contains the exact answer you have been seeking.',textCn:'古老圖書館裡一份塵封已久的羊皮卷軸，載有您一直在尋找的確切答案。'},
    5: {name:'末吉',textEn:'Though your invisibility cloak has a small tear, it will still offer minimal protection from detection.',textCn:'雖然您的隱形斗篷有個小破洞，但它仍能提供免於偵測的最低限度保護。'},
    6: {name:'衰',textEn:'A dark hex has been cast upon your prized broomstick; expect serious turbulence during the next flight.',textCn:'一個黑暗的詛咒已施加在您心愛的飛天掃帚上；下次飛行時請預期會有嚴重的亂流。'},
  }

  function chouqian(){
    chouqianFes.value = 0;
    stickResult.value = 0;
    setTimeout(() => {
      chouqianFes.value = 1;
    }, 500);
    setTimeout(() => {
      stickResult.value = Math.floor(Math.random() * 6) + 1;
      chouqianFes.value = 2;
      setTimeout(() => {
        chouqianFes.value = 3;
      }, 3000);
    }, 4500);
  }
  function closeResult(){
    stickResult.value = 0;
    chouqianFes.value = 0;
  }

const isFlipping = ref(false);
const bue1Transform = ref('rotate(90deg) rotateY(180deg)');
const bue2Transform = ref('rotate(-90deg) rotateY(0deg)');
const bue1Result = ref(1);
const bue2Result = ref(1);
const finalResult = ref('');

const buaBue = () => {
  if (isFlipping.value) return;
  
  isFlipping.value = true;
  bue1Result.value = 1;
  bue2Result.value = 1;
  finalResult.value = '';

  bue1Transform.value = 'rotate(90deg) rotateY(0deg)';
  bue2Transform.value = 'rotate(-90deg) rotateY(0deg)';
  
  setTimeout(() => {
    const isBue1Yin = Math.random() < 0.5;
    const isBue2Yin = Math.random() < 0.5;

    setTimeout(() => {
      isFlipping.value = false;
      
      bue1Transform.value = isBue1Yin ? 'rotate(90deg) rotateY(0deg)' : 'rotate(90deg) rotateY(180deg)';
      bue2Transform.value = isBue2Yin ? 'rotate(-90deg) rotateY(0deg)' : 'rotate(-90deg) rotateY(180deg)';

      bue1Result.value = isBue1Yin ? 1 : 0;
      bue2Result.value = isBue2Yin ? 1 : 0;

      if (isBue1Yin && isBue2Yin) {
        finalResult.value = 'Chio-Bue';
      } else if (!isBue1Yin && !isBue2Yin) {
        finalResult.value = 'Mo-Bue'; 
      } else {
        finalResult.value = 'Xin-Bue'; 
      }
      
    }, 1500);
  }, 50);
};
</script>

<template>
  <section class="divination-right dp-flex-col">
    <div class="stick-top dp-flex">
      <div class="left dp-flex-col">
        <div class="intro">
          <h5>Chōuqiān</h5>
          <p>A magical ritual where enchanted lots are drawn to seek guidance and reveal hidden fortunes.</p>
        </div>
        <div class="img-case">
          <img 
          v-for="(pic,key) in pics"
          :src="pic.imgUrl" 
          :alt="key"
          :class="key">
          
        </div>
      </div>
      <div class="right" 
        @mousedown.stop
        @touchstart.stop
        @mouseover.stop>
        <div 
          @click="chouqian"
          :class="{
            'fesStart': chouqianFes == 0,
            'fesShake': chouqianFes == 1,
            'fesCheck': chouqianFes == 2,
            'fesResult': chouqianFes == 3,
        }">
        <div id="qian1">
          <p>{{ chouqianResult[stickResult]?.name }}</p>
          <img src="/Classes/qian.png">
        </div>
        <div id="qian2">
          <img src="/Classes/qian.png">
        </div>
        <div id="qian3">
          <img src="/Classes/qian.png">
        </div>
        <div id="qian4">
          <img src="/Classes/qian.png">
        </div>
        <div id="qian5">
          <img src="/Classes/qian.png">
        </div>
        <div id="qian6">
          <img src="/Classes/qian.png">
        </div>
          <img src="/Classes/qianCase.png" id="qianCase">
        </div>
        <Transition name="fade" mode="out-in">
          <div 
          class="intro-text dp-flex"
          @click="closeResult"
          @mousedown.stop
          @touchstart.stop
          @mouseover.stop
          :class="{'show': chouqianFes == 3}" 
          >
          <h2>{{ chouqianResult[stickResult]?.name }}</h2>
          <p>
            <!-- {{ chouqianResult.stickResult.textCn }}<br><br> -->
            {{ chouqianResult[stickResult]?.textEn }}
          </p>
            <h6 v-if="stickResult != 0">
              <FontAwesomeIcon icon="fa-solid fa-xmark" @click="closeResult" />
            </h6>
          </div>
        </Transition>
      </div>
    </div>
    <div class="bue-game"
    @click="buaBue"
    @mousedown.stop
    @touchstart.stop>
    <h6 :class={dispear:isFlipping} class="clickMe">click ! <font-awesome-icon icon="fa-solid fa-turn-down" /></h6>
    <div class="bue-case bue-l" :class="{ flipping: isFlipping }" :style="{ transform: bue1Transform }">
      <img src="/Classes/bue-ying.png" alt="陰面">
      <img src="/Classes/bue-yang.png" alt="陽面">
    </div>
    <div class="bue-case bue-r" :class="{ flipping: isFlipping }" :style="{ transform: bue2Transform }">
      <img src="/Classes/bue-ying.png" alt="陰面">
      <img src="/Classes/bue-yang.png" alt="陽面">
    </div>
      <transition name="fade" mode="out-in">
        <h3 :key="bue">{{ finalResult}}</h3>
      </transition>
    </div>
  </section>
</template>
  
<style lang="scss" scoped>
  .divination-right{width: 100%;height: 100%;gap: 60px;}
   p{
      color: $color-fsContent;
    }
  .stick-top{
    gap: 44px;
    h5{
      text-align: left;
    }
  }
  .left{
    gap: 60px;
  }
  .right{
    position: relative;
    >div{
      width: 150px;
      height: 300px;
      z-index: 100;
      cursor: pointer;
      img{
        transition: opacity 1s ease;
      }
      >div{
        height: 50%;
        position: absolute;
        transition: scale 1s ease, translate 1s ease;
        img{height: 100%;position: absolute;left: 0;top: 0;transform: scaleX(0.5);}
        p{width: 40%;position: absolute;left: 16px;z-index: 2;color: $color-fsRed;font-weight: bold;opacity: 0;transition: opacity 1s ease;font-size: 12px;}
      }
      #qianCase{
        height: 85%;
        position: absolute;
        bottom: 0;left: 0%;
        transition: opacity 0.5s ease;
      }

      #qian1{top: 0;left: 12%;transform: rotate(-10deg);}
      #qian2{top: -4%;left: 20%;}
      #qian3{top: 2%;left: 36%;transform: rotate(2deg);}
      #qian4{top: 0;left: 43%;transform: rotate(-2deg);}
      #qian5{top: -3%;left: 45%;transform: rotate(12deg);}
      #qian6{top: 3%;left: 52%;transform: rotate(12deg);}
      #qian2,#qian3,#qian4,#qian5,#qian6{transition: opacity 0.5s ease;}
      &.fesStart{
        animation: 3s shufle 0.5s linear infinite forwards;
      }
      &.fesShake{
        transform: rotate(15deg);
        #qianCase{
        height: 100%;left: 22%;top: 15%;}
        #qian1{top: 2%;left: 29%;transform: rotate(-10deg);
        animation: 1.5s qianPull 0.5s linear 1 forwards;
        }
        #qian2{top: 0%;left: 33%;opacity: 1;}
        #qian3{top: 4%;left: 40%;transform: rotate(2deg);opacity: 1;}
        #qian4{top: 0;left: 50%;transform: rotate(-2deg);opacity: 1;}
        #qian5{top: -3%;left: 59%;transform: rotate(12deg);opacity: 1;}
        #qian6{top: 3%;left: 68%;transform: rotate(12deg);opacity: 1;}
        animation: 0.3s shake 0.5s linear 6 forwards;
      }
      &.fesCheck{
        transform: rotate(0deg) scale(2) translate(20% 50%);
        #qian2,#qian3,#qian4,#qian5,#qian6{opacity: 0;}
        #qian1{
        transform: rotate(0deg);
        top: -15%;
        animation: 1s qianScale 0.5s linear 1 forwards;
        }
        #qianCase{
        height: 85%;        
        animation: 3s casePull 0.5s linear 1 forwards;
      }
      }
      &.fesResult{
        #qian1{
          transform: rotate(0deg) scale(2) translateY(30%);          
        }
        #qian2,#qian3,#qian4,#qian5,#qian6{opacity: 0;}
        #qianCase{
        display: none;
      }
      p{opacity: 1;}

      }
    }
    .intro-text{
      position: absolute;
      top: 10%;
      right: 0;
      width: 0;
      height: auto;
      padding: 20px 60px 20px 40px; 
      gap: 32px;
      align-items: center;
      transition: transform 0.5s ease, opacity 0.3s ease 0.3s;
      opacity: 0;
      z-index: -10;
      border-radius: 16px;
      max-height: 50%;
      overflow: hidden;

      h6{
        position: absolute;
        top: 8px;
        right: 8px;
      }
      p{
        text-align: left;
        color: $color-fsContent;
      }
      &.show{
        width: 240%;
        transform: translateX(-42%);
        opacity: 1;
        z-index: 11;
        border: 1px solid $color-fsTitle;
        background-color: $color-fsGold300;
      }
    }
  }
  .img-case{
    position: relative;
    width: 100%;
    height: 200px;
    img{
      position: absolute;
      border-radius: 8px;
      box-shadow: 1px 1px 3px $color-fsTitle;
    }
    .picLeft{
      width: 100px;
      height: 100px;
      bottom: 0;
      left: -32%;
      transform: rotate(-15deg);
      
    }
    .picMid{
      width: 200px;
      height: 200px;
      bottom: 0;
      left: 0%;
      transform: rotate(5deg);
    }
    .picRight{
      width: 120px;
      height: 120px;
      bottom: 0;
      right: 0%;
      transform: rotate(-2deg);

    }
  }
  .bue-game{
    width: 100%;
    height: 100%;
    position: relative;
    
    h3{
      position: absolute;
      bottom: -10%;right: 10%;
    }
  }
  .bue-case {
      width: 250px;
      height: 250px;
      position: absolute;
      transform-style: preserve-3d;
      transition: transform 0.1s;
  }
  .bue-l {
    top: 10%;
    left: 15%;
    perspective: 1000px;
  }

  .bue-r {
    top: 0%;
    right: 15%;
    perspective: 1000px;
  }

  .bue-case img:nth-child(1) {
    transform: rotateY(0deg);
    filter: drop-shadow(4px -4px 4px $color-fsTitle);

  }

  .bue-case img:nth-child(2) {
    transform: rotateY(180deg);
  }

  .bue-l.flipping {
      animation: flipL 1.5s ease-in-out;
  }
  .bue-r.flipping {
      animation: flipR 1.5s ease-in-out;
  }

    .bue-case img {
      top: 0;left: 0;
      position: absolute;
      width: 100%;
      height: 100%;
      backface-visibility: hidden;
      object-fit: cover;
    }


@keyframes flipL {
  0% {
    transform: rotate(90deg) rotateY(0deg) rotateX(0deg);
  }
  25% {
    transform: rotate(90deg) rotateY(900deg) rotateX(900deg);
  }
  50% {
    transform: rotate(90deg) rotateY(1800deg) rotateX(1800deg);
  }
  75% {
    transform: rotate(90deg) rotateY(2700deg) rotateX(2700deg);
  }
  100% {
    transform: rotate(90deg) rotateY(3600deg) rotateX(3600deg);
  }
}
@keyframes flipR {
  0% {
    transform: rotate(-90deg) rotateY(0deg) rotateX(0deg);
  }
  25% {
    transform: rotate(-90deg) rotateY(900deg) rotateX(900deg);
  }
  50% {
    transform: rotate(-90deg) rotateY(1800deg) rotateX(1800deg);
  }
  75% {
    transform: rotate(-90deg) rotateY(2700deg) rotateX(2700deg);
  }
  100% {
    transform: rotate(-90deg) rotateY(3600deg) rotateX(3600deg);
  }
}
.fade-enter-active,.fade-leave-active {
  transition: all 0.2s ease;
}
.fade-enter-from,.fade-leave-to {
  opacity: 0;
}
@keyframes shake{
  0% {
    transform: rotate(15deg) translateX(0);
  }
  50% {
    transform: rotate(-30deg) translateX(-100%);
  }
  100%{
    transform: rotate(15deg) translateX(0);
  }
}
@keyframes shufle{
  0% {transform: rotate(0deg);}
  5% {transform: rotate(-30deg);}
  10%{transform: rotate(0deg);}
  15% {transform: rotate(-30deg);}
  20%{transform: rotate(0deg);}
  100%{transform: rotate(0deg);}
}
@keyframes clickMe{
  0% {transform: rotate(-35deg);color: $color-fsTitle;}
  5% {transform: rotate(-45deg);color: $color-fsGold;}
  10%{transform: rotate(-25deg);color: $color-fsGold300;}
  15% {transform: rotate(-45deg);color: $color-fsGold;}
  20%{transform: rotate(-25deg);color: $color-fsGold300;}
  25%{transform: rotate(-35deg);color: $color-fsTitle;}
  100%{transform: rotate(-35deg);color: $color-fsTitle;}
}
@keyframes qianPull{
  0%{top: 2%;left: 29%;transform: rotate(-10deg);}
  1%{top: 0%;left: 29%;transform: rotate(-10deg);}
  20%{top: -6%;left: 29%;transform: rotate(-10deg);}
  40%{top: -6%;left: 29%;transform: rotate(-10deg);}
  60%{top: -10%;left: 29%;transform: rotate(-10deg);}
  80%{top: -10%;left: 29%;transform: rotate(-10deg);}
  100%{top: -15%;left: 29%;transform: rotate(-10deg);}
}
@keyframes casePull{
  0%{bottom: 0%;left: 0%;opacity: 1;}
  20%{opacity: 0;}
  100%{bottom: -80%;opacity: 0;}
}
@keyframes qianScale{
  0%{transform: rotate(0deg);top: -15%;}
  100%{transform: rotate(0deg) scale(2) translateY(30%);top: 0;}
}
.dispear{
  display: none;
}
.clickMe{
  display: inline-block;
  animation: 4s clickMe 5s linear infinite forwards;
  position: absolute;
  top: 10%;
  left: 15%;
}
</style>
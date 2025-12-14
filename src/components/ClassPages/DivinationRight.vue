<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ref } from 'vue';
  const bue = ref(1);
  const stickResult = ref(0);
  const pics = {
    picLeft: {
      imgUrl:'/Classes/bue1.jpg',
    },
    picMid: {
      imgUrl:'/Classes/bue3.jpg',
    },
    picRight: {
      imgUrl:'/Classes/bue2.jpg',
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
  const bueGameResult={
    1: {name:'Mo-Bue',imgUrl:'/Classes/MoonFes3.png',},
    2: {name:'Chio-Bue',imgUrl:'/Classes/MoonFes3.png',},
    3: {name:'Xin-Bue',imgUrl:'/Classes/MoonFes3.png',},
  }
  function buaBue(){
    const num = Math.random();
    bue.value = Math.floor(num * 3) + 1;
  }
  function chouqian(){
    const num = Math.random()
    stickResult.value = 0;
    setTimeout(() => {
        stickResult.value = Math.floor(num * 6) + 1;
      },800);
  }
  function closeResult(){
    stickResult.value = 0;
  }
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
        <img 
        @click="chouqian"
        src="/Classes/charms/charm1.png" alt=""
        >
        <Transition name="fade" mode="out-in">
          <div 
          class="intro-text dp-flex"
          @click="closeResult"
          @mousedown.stop
          @touchstart.stop
          @mouseover.stop
          :class="{'show': stickResult != 0}" 
          >
          <h2 >{{ chouqianResult[stickResult]?.name }}</h2>
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
      <img :src="bueGameResult[bue].imgUrl" alt="">
      <transition name="fade" mode="out-in">
        <h3 :key="bue">{{ bueGameResult[bue].name }}</h3>
      </transition>
    </div>
  </section>
</template>
  
<style lang="scss" scoped>
  .divination-right{width: 100%;height: 100%;gap: 30px;}
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
    img{
      width: 150px;
      height: 281px;
      z-index: 100;
      cursor: pointer;
    }
    .intro-text{
      position: absolute;
      top: 10%;
      right: 0;
      width: 0;
      height: auto;
      padding: 20px 60px 20px 10px; 
      gap: 16px;
      align-items: center;
      transition:all 1s ease-in;
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
      border: 1px solid $color-fsTitle;
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
    img{
      width: 250px;
      height: 250px;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%,-50%);
      cursor: pointer;
    }
    h3{
      position: absolute;
      bottom: 10%;right: 10%;
    }
  }
.fade-enter-active,.fade-leave-active {
  transition: all 0.2s ease;
}
.fade-enter-from,.fade-leave-to {
  opacity: 0;
}
</style>
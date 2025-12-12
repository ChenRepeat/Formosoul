<script setup>
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import { computed, ref } from 'vue';

  const clickPotion = ref('0');
  const potions = {
    1: {
        name: '1',
        intro: 'Taiwan’s iconic bear, known for its crescent chest mark. It lives in high mountains and symbolizes Taiwan’s wildlife.', 
        imgUrl:'/Classes/potions/potion1.png',
        recipe:{
          1:{
            name:'milk',
            imgUrl:'/Classes/potions/recipeMilk.png',
          },
          2:{
            name:'suger',
            imgUrl:'/Classes/potions/recipeSuger.png',
          },
          3:{
            name:'BlackTea',
            imgUrl:'/Classes/potions/recipeBlackTea.png',
          },
        }
      },
    2: {
        name: '2',
        intro: 'A small, elegant deer once nearly extinct in Taiwan, now restored through conservation efforts..', 
        imgUrl:'/Classes/potions/potion2.png',
        recipe:{
          1:{
            name:'milk',
            imgUrl:'/Classes/potions/recipeMilk.png',
          },
          2:{
            name:'suger',
            imgUrl:'/Classes/potions/recipeSuger.png',
          },
          3:{
            name:'BlackTea',
            imgUrl:'/Classes/potions/recipeBlackTea.png',
          },
        }
      },
    3: {
        name: '3',
        intro: 'Taiwan’s only native monkey species, highly social and adaptable, often seen in forests and mountainous regions.', 
        imgUrl:'/Classes/potions/potion3.png',
        recipe:{
          1:{
            name:'milk',
            imgUrl:'/Classes/potions/recipeMilk.png',
          },
          2:{
            name:'suger',
            imgUrl:'/Classes/potions/recipeSuger.png',
          },
          3:{
            name:'BlackTea',
            imgUrl:'/Classes/potions/recipeBlackTea.png',
          },
        }
      },
      4: {
        name: '3',
        intro: 'Taiwan’s only native monkey species, highly social and adaptable, often seen in forests and mountainous regions.', 
        imgUrl:'/Classes/potions/potion3.png',
        recipe:{
          1:{
            name:'milk',
            imgUrl:'/Classes/potions/recipeMilk.png',
          },
          2:{
            name:'suger',
            imgUrl:'/Classes/potions/recipeSuger.png',
          },
          3:{
            name:'BlackTea',
            imgUrl:'/Classes/potions/recipeBlackTea.png',
          },
        }
      },
      5: {
        name: '3',
        intro: 'Taiwan’s only native monkey species, highly social and adaptable, often seen in forests and mountainous regions.', 
        imgUrl:'/Classes/potions/potion3.png',
        recipe:{
          1:{
            name:'milk',
            imgUrl:'/Classes/potions/recipeMilk.png',
          },
          2:{
            name:'suger',
            imgUrl:'/Classes/potions/recipeSuger.png',
          },
          3:{
            name:'BlackTea',
            imgUrl:'/Classes/potions/recipeBlackTea.png',
          },
        }
      },
      6: {
        name: '3',
        intro: 'Taiwan’s only native monkey species, highly social and adaptable, often seen in forests and mountainous regions.', 
        imgUrl:'/Classes/potions/potion3.png',
        recipe:{
          1:{
            name:'milk',
            imgUrl:'/Classes/potions/recipeMilk.png',
          },
          2:{
            name:'suger',
            imgUrl:'/Classes/potions/recipeSuger.png',
          },
          3:{
            name:'BlackTea',
            imgUrl:'/Classes/potions/recipeBlackTea.png',
          },
        }
      },
      7: {
        name: '3',
        intro: 'Taiwan’s only native monkey species, highly social and adaptable, often seen in forests and mountainous regions.', 
        imgUrl:'/Classes/potions/potion3.png',
        recipe:{
          1:{
            name:'milk',
            imgUrl:'/Classes/potions/recipeMilk.png',
          },
          2:{
            name:'suger',
            imgUrl:'/Classes/potions/recipeSuger.png',
          },
          3:{
            name:'BlackTea',
            imgUrl:'/Classes/potions/recipeBlackTea.png',
          },
        }
      },
      8: {
        name: '3',
        intro: 'Taiwan’s only native monkey species, highly social and adaptable, often seen in forests and mountainous regions.', 
        imgUrl:'/Classes/potions/potion3.png',
        recipe:{
          1:{
            name:'milk',
            imgUrl:'/Classes/potions/recipeMilk.png',
          },
          2:{
            name:'suger',
            imgUrl:'/Classes/potions/recipeSuger.png',
          },
          3:{
            name:'BlackTea',
            imgUrl:'/Classes/potions/recipeBlackTea.png',
          },
        }
      },
  };
    const getMaskStyle = (key) => {
    if (key !== clickPotion.value) { 
        const potion = potions[key];
        if (potion && potion.imgUrl) {
          return {
            'mask-image': `url('${potion.imgUrl}')`,
            '-webkit-mask-image': `url('${potion.imgUrl}')`,
             'background-color': 'black',
             'opacity': '0.8',
          };
        }
      }
      return {}; 
  };
  function changeIntro(i){
      clickPotion.value = String(i);
  }
  const currentIntro = computed(() => {
    return potions[clickPotion.value];
  });
</script>

<template>
  <section class="potion-left dp-flex-col">
    <div class="potion-title">
      <h2>Potions</h2>
      <p>Taiwan’s herbal potions refresh, heal, and bring magical energy with natural herbs and soothing flavors.</p>
    </div>
    <div class="potion-intro">
      <div class="potion-intro-img dp-flex">
        <div class="shadow-case"
          v-for="(potion, key) in potions" 
          :key="key"
          @click="changeIntro(key)"
          @mousedown.stop
          @touchstart.stop>
          <img 
              :src="potion.imgUrl" 
              :alt="potion.name"
              @click="changeIntro(key)"
              @mousedown.stop
              @touchstart.stop>
          <div class="over-lay"
              :style="getMaskStyle(key)">
          </div>
        </div>
      </div>
    <transition name="fade" mode="out-in">
      <div class="intro-case dp-flex"
        v-if="clickPotion!=0"
        @click="changeIntro(0)"
        @mousedown.stop
        @touchstart.stop
        :class="{'op1':clickPotion != 0}">
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
    </transition>

    </div>

  </section>
</template>

<style lang="scss" scoped>
  .potion-intro{
    width: 100%;
    position: relative;
  }
  .potion-left{
    width: 100%;
    height: 100%;
    gap: 100px;
  }
  .potion-title{
    width: 100%;
  }
  .shadow-case{
    width: 25%;
    height: auto;
    position: relative;
    margin: 20px 0;
    img{
      width: 100%;
      height: 100%;
    }
  }
  .potion-intro-img{
    flex-wrap: wrap;
  }
  .intro-case{
    width: 90%;
    height: auto;
    max-height: fit-content;
    position: absolute;
    z-index: 20;
    background-color: #fff;
    padding: 20px 40px 20px 0;
    border-radius: 8px;
    top: 50%; 
    left: 50%;
    transform: translate(-50%, -50%);
    align-items: center;
    gap: 14px;
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
    gap: 20px;
    img{
      width: 50px;
      height: 50px;
      border-radius: 50%;
    }
  }
  .intro-text{
    gap: 20px;
    justify-content: center;
    
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
    mask-repeat: no-repeat;
    mask-size: contain;
    mask-position: center;
    -webkit-mask-repeat: no-repeat;
    -webkit-mask-size: contain;
    -webkit-mask-position: center;
    mask-mode: alpha;
    -webkit-mask-mode: alpha;
}
.show{
    background-color: black;
    opacity: 0.8;
}
.fade-enter-active,.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,.fade-leave-to {
  opacity: 0;
}
</style>
<script setup>
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import { computed, ref } from 'vue';

  const clickPotion = ref('0');
  const potions = {
    1: {
        name: 'Boba Milk Tea',
        intro: 'A creamy blend of black tea and milk, featuring the signature, chewy tapioca.', 
        imgUrl:'Classes/potions/potion7.png',
        recipe:{
          1:{
            name:'milk',
            imgUrl:'Classes/potions/recipeMilk.png',
          },
          2:{
            name:'BlackTea',
            imgUrl:'Classes/potions/recipeBlackTea.png',
          },
          3:{
            name:'Topioca',
            imgUrl:'Classes/potions/recipeTopioca.png',
          },
        }
      },
    2: {
        name: 'Aiyu Lemon Jelly Drink',
        intro: 'Zesty lemon drink featuring slippery, natural aiyu (fig) jelly—a perfect summer cooler.', 
        imgUrl:'Classes/potions/potion2.png',
        recipe:{
          1:{
            name:'Aiyu',
            imgUrl:'Classes/potions/recipeAiyu.png',
          },
          2:{
            name:'Lemon',
            imgUrl:'Classes/potions/recipeLemon.png',
          },
          
        }
      },
    3: {
        name: 'No.1',
        intro: 'Lightly oxidized oolong tea customized with three chewy toppings: boba, pearls, and nata de coco.', 
        imgUrl:'Classes/potions/potion11.png',
        recipe:{
          1:{
            name:'Greentea',
            imgUrl:'Classes/potions/recipeGreentea.png',
          },
          2:{
            name:'Coconut',
            imgUrl:'Classes/potions/recipeCoconut.png',
          },
          3:{
            name:'Aiyu',
            imgUrl:'Classes/potions/recipeAiyu.png',
          },
          4:{
            name:'Topioca',
            imgUrl:'Classes/potions/recipeTopioca.png',
          },
        }
      },
      4: {
        name: 'Winter Melon Lemonade',
        intro: 'A perfectly balanced drink: sweet winter melon combined with the refreshing tartness of lemon.', 
        imgUrl:'Classes/potions/potion3.png',
        recipe:{
          1:{
            name:'Winter Melon',
            imgUrl:'Classes/potions/recipeEastMelon.png',
          },
          2:{
            name:'Lemon',
            imgUrl:'Classes/potions/recipeLemon.png',
          },
        }
      },
      5: {
        name: 'Brown Sugar Boba',
        intro: 'Warm, chewy pearls simmered in rich brown sugar syrup mixed with fresh milk.', 
        imgUrl:'Classes/potions/potion6.png',
        recipe:{
          1:{
            name:'milk',
            imgUrl:'Classes/potions/recipeMilk.png',
          },
          2:{
            name:'suger',
            imgUrl:'Classes/potions/recipeSuger.png',
          },
          3:{
            name:'BlackTea',
            imgUrl:'Classes/potions/recipeBlackTea.png',
          },
          4:{
            name:'Topioca',
            imgUrl:'Classes/potions/recipeTopioca.png',
          },
        }
      },
      6: {
        name: 'Winter Melon Drink with Grass Jelly',
        intro: 'Sweet winter melon juice perfectly paired with smooth, cooling herbal grass jelly cubes.', 
        imgUrl:'Classes/potions/potion5.png',
        recipe:{
          1:{
            name:'Winter Melon',
            imgUrl:'Classes/potions/recipeEastMelon.png',
          },
          2:{
            name:'Lemon',
            imgUrl:'Classes/potions/recipeLemon.png',
          },
          3:{
            name:'Xian Tsou',
            imgUrl:'Classes/potions/recipeXianTsou.png',
          },
        }
      },
      7: {
        name: 'Cheese Foam Green Tea',
        intro: 'Refreshing green tea topped with a thick, savory, and lightly salted cream foam.', 
        imgUrl:'Classes/potions/potion1.png',
        recipe:{
          1:{
            name:'milk',
            imgUrl:'Classes/potions/recipeMilk.png',
          },
          2:{
            name:'suger',
            imgUrl:'Classes/potions/recipeSuger.png',
          },
          3:{
            name:'Greentea',
            imgUrl:'Classes/potions/recipeGreentea.png',
          },
        }
      },
      8: {
        name: 'Winter Melon Drink with Nata de Coco',
        intro: 'Icy, traditional winter melon juice with sweet, chewy pieces of coconut jelly (nata de coco).', 
        imgUrl:'Classes/potions/potion9.png',
        recipe:{
          1:{
            name:'Winter Melon',
            imgUrl:'Classes/potions/recipeEastMelon.png',
          },
          2:{
            name:'Coconut',
            imgUrl:'Classes/potions/recipeCoconut.png',
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
      };
    }
  }
  return { 'display': 'none' };
};
  function changeIntro(i){
    if(clickPotion.value != String(i)){
      clickPotion.value = false;
      setTimeout(() => {
        clickPotion.value = String(i);
      }, 450);
    }else{
      clickPotion.value = false;
    }
  }
  const currentIntro = computed(() => {
    return potions[clickPotion.value];
  });
</script>

<template>
  <section class="potion-left dp-flex-col"
  :class="{'open':clickPotion != 0}">
    <div class="potion-title">
      <h2>Potions</h2>
      <p>Taiwan’s herbal potions refresh, heal, and bring magical energy with natural herbs and soothing flavors.</p>
    </div>
    <div class="potion-intro">
      <div class="potion-intro-img dp-flex"
        :class="{'open':clickPotion != 0}">
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
              :style="getMaskStyle(key)"
              >
          </div>
        </div>
      </div>
      <div class="intro-case dp-flex  bg-frostedGlass"
        v-if="clickPotion != 0"
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
   p{
      color: $color-fsContent;
    }
  .potion-intro{
    width: 100%;
    position: relative;
  }
  .potion-left{
    width: 100%;
    height: 100%;
    gap: 100px;
    transition: gap 1s ease;
    &.open{
      gap: 0px;
    }
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
    row-gap: 0;
    transition: gap 1s ease;
    &.open{
      row-gap: 240px;
    }
  }
  .intro-case{
    width: 90%;
    height: auto;
    max-height: fit-content;
    position: absolute;
    z-index: 20;
    // background-color: #fff;
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
  .over-lay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: black; 
  z-index: 10;
  opacity: 0.8; 
  transition: opacity 0.5s ease;
  mask-repeat: no-repeat;
  mask-size: contain;
  mask-position: center;
  -webkit-mask-repeat: no-repeat;
  -webkit-mask-size: contain;
  -webkit-mask-position: center;

  &:hover {
    opacity: 0;
  }
}

</style>
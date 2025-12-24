<script setup>
import { ref, onMounted } from 'vue';
import BasicButton from '../BasicButton.vue';
import { useRouter } from 'vue-router';
import PotionGame from './PotionGame.vue';
import { faDisplay } from '@fortawesome/free-solid-svg-icons';

//Boolean
const isShow = ref(true)
const beforeStart = ref(true)
const beforeCook = ref(false)
const afterCook = ref(false)
// template 標籤 ref綁定 
const refIngredient = ref(null)
const refWrapper = ref(null)
//variables

// potions[i].recipe.values()  .hasOwn('Classes/potions/recipeMilk.png')

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
let ingredientList = ['Classes/potions/recipeAiyu.png',
  'Classes/potions/recipeBlackTea.png',
  'Classes/potions/recipeCoconut.png',
  'Classes/potions/recipeEastMelon.png',
  'Classes/potions/recipeGreentea.png',
  'Classes/potions/recipeLemon.png',
  'Classes/potions/recipeMilk.png',
  'Classes/potions/recipeSuger.png',
  'Classes/potions/recipeTopioca.png',
  'Classes/potions/recipeXianTsou.png',
]

function shuffle(arr) {
  for (let i = arr.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [arr[i], arr[j]] = [arr[j], arr[i]]; // swap
  }
}

shuffle(ingredientList)

//functions
const start= ()=>{
  isShow.value = false
  beforeStart.value = false
  beforeCook.value = true
  afterCook.value= false

}

const cook=()=>{
  isShow.value = true
  beforeCook.value = false
  afterCook.value= true
}



const dragStart=( e )=>{
 const data = e.target.src // try e.target // refIngredient 抓不到
 // 取得連結字串 之後去食譜物件比對
 
 e.dataTransfer.setData('ingredient', data)
}


const dragEnd=(  )=>{
  refIngredient.value.style.display = `none`
}
onMounted(()=>{
})
// prevent-->drop

</script>
<template>
  <!-- 初始畫面 -->
  <section class="potion-right-initial" v-show="isShow">
    <h2 class="potion-right-title" v-show="isShow" style="margin-bottom: 40%;margin-top: 15%;">Your Own Potion</h2>
     <div  style="margin-bottom: 15%;" v-if="beforeStart"><!-- -->
      <img src="../../../public/Classes/potions/glass.png">
    </div>
    <BasicButton class="potion-right-start btn-black " 
    @click="start" style="width: max-content;" v-if="beforeStart"><!--  -->
      START
    </BasicButton>
  </section>

  <!-- 選材料畫面 -->
  <section v-if="beforeCook" class=" potion-right-game" 
          @mousedown.stop
          @touchstart.stop>
    <img src="../../../public/Classes/potions/eletricPotLid.png" alt="" class="potion-right-pot-lid"> 
    <div class="potion-right-ingredient-wrapper" ref="refWrapper">
      <img v-for="(item, index) in ingredientList" 
          :src="item" :id="'img'+(index + 1)" alt="" 
          class="potion-right-ingredient" 
          @dragstart="dragStart" @dragend="dragEnd"
          ref="refIngredient">
    </div>
    <img src="../../../public/Classes/potions/eletricPot.png" alt=""  class="potion-right-pot">
    <BasicButton class="potion-right-cook btn-black " 
      @click="cook" >
        COOK
    </BasicButton>
  </section>

<!-- 結果畫面 -->
  <section v-else-if="afterCook" class="potion-right-result">
    <div>
      <img :src= afterCook alt="">
      <h5 class="potion-right-result-title"></h5>
      <p class="potion-right-result-intro"></p>
    </div>
    <BasicButton class="potion-right-retry btn-black" v-if="afterCook"
    @click="start">
      Retry
    </BasicButton>
  </section>
</template>

<style scoped>
.potion-right-initial{
  display: flex; 
  flex-direction: column; 
  align-items:center ;
  min-height: 100%;
}
.potion-right-game{
  height: 100%;
  width: 100%;
  position: relative;
  .potion-right-pot-lid{
    width: 190px;

    position: absolute;
    top: -40px ;
    right: -20px;
  }
  
  .potion-right-ingredient-wrapper{
    width: 100%;
    height: 100%;
    position: relative;
    
    .potion-right-ingredient{
      border-radius: 50%;
      position:absolute;
      &:hover{
        cursor: grab;
      }
  
      &#img1, &#img3, &#img6{
        width: 10%;
        &#img1{
          top: 6%;
          left: 7%;
        }
        &#img3{
          top: 60%;
          left: 50%;
        }
        &#img6{
          top: 10%;
          left: 55%;
        }
      
      }
      &#img2, &#img10{
        width: 21%;
        &#img2{
          top:20%;
          left: 50%;
        }
         &#img10{
          top:45%;
          left: 10%;
         }
      }
      &#img7,&#img4{
        width: 25%;
        &#img7{
          top: 48%;
          left: 70%;
        }
        &#img4{
          top: 22%;
          right: 0;
        }
      }
      &#img8{
        width: 23%;
        top: 9%;
        left: 22%;
      }
      &#img9, &#img5{
        width: 15%;
        &#img9{
          top: 20%;
          left: 1%;
        } 
        &#img5{
          top: 32%;
          left: 30%;
        }
      }
    }
  
  }
  .potion-right-pot{
    width: 190px;
  
    position:absolute;
    bottom: 40px;
    right: 0;left: 0;
    margin: auto ;
  }
  .potion-right-cook{
    width: max-content;
    
    position:absolute;
    bottom: 0;
    right: 0;left: 0;
    margin: 0 auto ;
  }
}


.potion-right-retry{
  width: max-content;

  position: absolute;
    bottom: 40px;
    right: 0;left: 0;
    margin: 0 auto ;
}


</style>
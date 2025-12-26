<script setup>
import { ref, onMounted, nextTick } from 'vue';
import BasicButton from '../BasicButton.vue';
// import { faDisplay } from '@fortawesome/free-solid-svg-icons';
import { gsap } from 'gsap/gsap-core';
import { Draggable } from 'gsap/Draggable';
import { potions } from '../ClassPages/potions'


//plugins
gsap.registerPlugin(Draggable)

//Boolean
const isShow = ref(true)
const beforeStart = ref(true)
const beforeCook = ref(false)
const afterCook = ref(false)

// template 標籤 ref綁定 
// const refIngredient = ref(null)
// const refWrapper = ref(null)

// variables
const resultImg = ref('');
const resultTitle = ref('');
const resultIntro = ref('');
let selectList = []
let numCurrectList = []

let ingredientList = [
          {
            name:'milk',
            imgUrl:'Classes/potions/recipeMilk.png',
          },
          {
            name:'BlackTea',
            imgUrl:'Classes/potions/recipeBlackTea.png',
          },
          {
            name:'Topioca',
            imgUrl:'Classes/potions/recipeTopioca.png',
          },
          {
            name:'Aiyu',
            imgUrl:'Classes/potions/recipeAiyu.png',
          },
          {
            name:'Lemon',
            imgUrl:'Classes/potions/recipeLemon.png',
          },
          {
            name:'Greentea',
            imgUrl:'Classes/potions/recipeGreentea.png',
          },
          {
            name:'suger',
            imgUrl:'Classes/potions/recipeSuger.png',
          },
          {
            name:'Xian Tsou',
            imgUrl:'Classes/potions/recipeXianTsou.png',
          },
          {
            name:'Winter Melon',
            imgUrl:'Classes/potions/recipeEastMelon.png',
          },
          {
            name:'Coconut',
            imgUrl:'Classes/potions/recipeCoconut.png',
          },
]
  // potions 改用外部引入

//functions
function shuffle(arr) {
  for (let i = arr.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [arr[i], arr[j]] = [arr[j], arr[i]]; // swap
  }
}
shuffle(ingredientList) // 會直接改動原始陣列

const initDraggable = () => { //清掉上回合的實體

    const oldInstances = Draggable.get('.potion-right-ingredient')
    if(oldInstances){
      if(Array.isArray(oldInstances)){
        oldInstances.forEach(element => element.kill())
      }else { oldInstances.kill()} 
    }
    Draggable.create( '.potion-right-ingredient', {
      type:'x, y', // == default 允許平面上下左右移動
  
      onDragEnd: e => {
       const instance = Draggable.get(e.target)
  
       if (instance.hitTest('.potion-right-pot')){ // default == 0 , 可設定物件跟鍋子重疊 % 加強判定嚴謹程度 
        gsap.to(e.target, { scale: 0, duration: 0.3 });
        selectList.push(e.target.name)
        
      }else{      
        gsap.to(e.target, { x: 0, y: 0, duration: 0.3 });
        // 未通過hitTest 判定為沒有進入鍋子
      }
      console.log(selectList);
      }
    })
}

const start= async()=>{ // 暫定 retry 呼叫同一個 FUNCTION
  isShow.value = false
  beforeStart.value = false
  beforeCook.value = true
  afterCook.value= false

  await nextTick() // 等待DOM更新 直到beforeCook要呈現的標籤都存在 (還沒渲染 但這邊不影響)
  selectList = [] // 清空
  initDraggable()
}

const cook = () => {
  isShow.value = true
  beforeCook.value = false
  afterCook.value= true

  // 食譜key 值從1 開始
  // 判斷食材屬於哪個食譜 (順序為目前計劃) 
  // 1.食材總數 == 飲料食材總數 
  const selectNum = selectList.length 
  
  for(let i = 1; i= 8 ; i++){
    let recipeNum = Object.values(potions[i].recipe).length
    if(selectNum == recipeNum){
      numCurrectList.push(i) // 儲存食材數量對的食譜 屬於幾號飲料
    }
  }
  console.log(numCurrectList.length);

  
  // 2.食材name == potions[遍歷剩下的].recipe[遍歷].name 
  // 3. result 才去抓那杯飲料的圖片&介紹 
  // 4. 不符合食譜或數量大於五直接給怪怪飲料
  // 
}



onMounted(()=>{

})



</script>
<template>
  <!-- 初始畫面 -->
  <section class="potion-right-initial" v-show="isShow">
    <h2 class="potion-right-title" v-show="isShow" style="margin-bottom: 40%;margin-top: 15%;">Your Own Potion</h2>
     <div  style="margin-bottom: 15%;" v-if="beforeStart"><!-- -->
      <img src="/Classes/potions/glass.png">
    </div>
    <BasicButton class="potion-right-start btn-black " 
    @click="start" style="width: max-content;" v-if="beforeStart"><!--  -->
      START
    </BasicButton>
  </section>

  <!-- 選材料畫面 -->
  <section v-if="beforeCook" class="potion-right-game" 
          @mousedown.stop
          @touchstart.stop>
    <img src="/Classes/potions/eletricPotLid.png" alt="" class="potion-right-pot-lid"> 
    <div class="potion-right-ingredient-wrapper" ref="refWrapper">
      <img v-for="(item, index) in ingredientList" 
          :src="item.imgUrl" :name="item.name" :id="'img'+(index + 1)" alt="" 
          class="potion-right-ingredient" 
        
          ref="refIngredient">
          <!-- @dragstart="dragStart" @dragend="dragEnd" -->
    </div>
    <img src="/Classes/potions/eletricPot.png" alt=""  class="potion-right-pot" 
    @drop="dropped">
    <BasicButton class="potion-right-cook btn-black" 
      @click="cook" >
        COOK
    </BasicButton>
  </section>

<!-- 結果畫面 -->
  <section v-else-if="afterCook" class="potion-right-result">
    <div>
      <img :src='resultImg' alt="">
      <h5 class="potion-right-result-title">{{ resultTitle }}</h5>
      <p class="potion-right-result-intro">{{ resultIntro }}</p>
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
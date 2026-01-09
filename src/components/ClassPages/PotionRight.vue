<script setup>
import { ref, onMounted, nextTick } from 'vue';
import BasicButton from '../BasicButton.vue';
import { gsap } from 'gsap/gsap-core';
import { Draggable } from 'gsap/Draggable';
import { potions } from '../ClassPages/potions'
import { newPotions } from '../ClassPages/newPotions'


//plugins
gsap.registerPlugin(Draggable)

//Boolean
const beforeStart = ref(true)
const beforeCook = ref(false)
const afterCook = ref(false)
const hasContent = ref(false)

// template 標籤 ref綁定 
// const refIngredient = ref(null)
// const refWrapper = ref(null)

// variables
const initialBigTitle = ref('classes.potionBigTitle1')
const resultImg = ref('');
const resultTitle = ref('');
const resultIntro = ref('');
const resultBigTitle = ref('classes.potionBigTitle2');

const container = ref(null);

const initialRule = ('classes.potionRule')
let containerWidth
let containerHeight
let potionAll = {}
let selectList = []
let correctList = []
let resultIndex = null

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
potionAll = {...potions, ...newPotions}

//functions 
function shuffle(arr) {
  for (let i = arr.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [arr[i], arr[j]] = [arr[j], arr[i]]; // swap
  }
}
shuffle(ingredientList) // 會直接改動原始陣列

const updateSize = () => {
  if (container.value) {
    containerWidth = container.value.getBoundingClientRect().width;
    containerHeight = container.value.getBoundingClientRect().height;
  }
    
};
const initDraggable = () => { //清掉上回合的實體
    const oldInstances = Draggable.get('.potion-right-ingredient')
    if(oldInstances){
      if(Array.isArray(oldInstances)){
        oldInstances.forEach(element => element.kill())
      }else { oldInstances.kill()} 
    }
    Draggable.create( '.potion-right-ingredient', {
      type:'x, y', // == default 允許平面上下左右移動

      bounds:{left:-40, top:-40, width: containerWidth + 80, height: containerHeight + 80}, // 應該要抓到確切的組件寬高..但先這樣

      onDragEnd: e => {
        const instance = Draggable.get(e.target)
        if(!instance ) return
      if (instance.hitTest('.potion-right-pot','15%')){ // default == 0 , 可設定物件跟鍋子重疊 % 加強判定嚴謹程度 
        gsap.to(e.target, { scale: 0, duration: 0.3 });
        selectList.push(e.target.name)
        
      }else{      
        gsap.to(e.target, { x: 0, y: 0, duration: 0.3 });

        // 未通過hitTest 判定為沒有進入鍋子
      }
      }
    })
}

const start= async()=>{ // 暫定 retry 呼叫同一個 FUNCTION
  beforeStart.value = false
  beforeCook.value = true
  afterCook.value= false
  
  await nextTick() // 等待DOM更新 直到beforeCook要呈現的標籤都存在 (還沒渲染 但這邊不影響)
  selectList = [] // 清空
  correctList= [] 
  resultIndex = null
  updateSize()
  initDraggable()
}

const cook = () => {
  
  beforeCook.value = false
  afterCook.value= true

  // 判斷食材屬於哪個食譜 (順序為目前計劃) 

  // 1.食材總數 == 飲料食材總數 
  const selectNum = selectList.length // 儲存被選到的食材名稱
  if(selectNum == 0){
    resultBigTitle.value = 'classes.potionBigTitle0' // 鍋子燒壞警告
    resultImg.value = "Classes/potions/eletricPot.png"
    hasContent.value = false
    
  }else {
    hasContent.value = true
    resultBigTitle.value = 'classes.potionBigTitle2' 

  }
  for(let i = 1; i<= 17 ; i++){
    if(!selectNum) return
    let recipeNum = 0
    recipeNum = Object.values(potionAll[i].recipe).length 
    
    // 統計各個食譜長度(有幾種原料)
    if(selectNum == recipeNum || selectNum - 1 == recipeNum){
      correctList.push(potionAll[i]) 
      // correctList == 符合數量的完整食譜// index 從 0 
      // 先擴充糖 允許多選一個食材的食譜先進到這邊 
    }
  }

  // 2.食材name == 食譜.recipe[遍歷].name 
  for(let i = 0 ; i < correctList.length; i++){
    if(!correctList.length) return
    let recipe = correctList[i].recipe 
    let recipeName = Object.values(recipe) 
                           .map(item => item.name) // 化為陣列 // 物件不能 .length
    let isMatch = true
    for(let j = 0 ; j < selectList.length ; j++){ 
      if(!recipeName.includes(selectList[j]) && selectList[j] != 'suger') {
        // 如果多了食譜上沒有的糖 仍不要判定為失敗
        isMatch = false
        break} // 一次false 就進入下一圈判斷
    }
    if(isMatch){
      resultIndex = i;
      break
    }else resultIndex = -1 
  }
  
  // 3. (有照食譜) result 才去抓那杯飲料的圖片&介紹 
  if(Number.isInteger(resultIndex) && resultIndex != -1){
    
    resultImg.value = correctList[resultIndex].imgUrl
    resultTitle.value = correctList[resultIndex].name
    resultIntro.value = correctList[resultIndex].resultIntro
  }



  // 4. (例外)不符合食譜神秘飲料區段 random 有3種
  else{
    let index = Math.floor(Math.random() * 3 + 1)

    // resultImg.value = potionAll[index].imgUrl // 測試用
    // resultTitle.value = potionAll[index].name
    // resultIntro.value = potionAll[index].resultIntro


    resultImg.value = `Classes/potions/secretPotion${index}.png`,
    resultTitle.value = `classes.potiongame.secretPotionName${index}`
    resultIntro.value = `classes.potiongame.secretPotion${index}`

  }
}



</script>
<template>
  <!-- 初始畫面 -->
  <section class="potion-right-initial dp-flex-col" v-if="beforeStart">
    <h2 class="potion-right-title" >{{$t(initialBigTitle)}}</h2>
    <div class="potion-right-glass" >
      <img src="/Classes/potions/glass.png">
    </div>
    <p class="potion-right-rule">{{ $t(initialRule) }}</p>
    <BasicButton class="potion-right-start btn-black " 
    @click="start" >
      Start
    </BasicButton>
  </section>

  <!-- 選材料畫面 -->
  <section v-else-if="beforeCook" class="potion-right-game" 
          @mousedown.stop
          @touchstart.stop>
    <img src="/Classes/potions/eletricPotLid.png" alt="" class="potion-right-pot-lid"> 
    <div class="potion-right-ingredient-wrapper" ref="container">
      <img v-for="(item, index) in ingredientList" 
          :src="item.imgUrl" :name="item.name" :id="'img'+(index + 1)" alt="" 
          class="potion-right-ingredient" 
          ref="refIngredient">
    </div>
    <img src="/Classes/potions/eletricPot.png" alt=""  class="potion-right-pot" 
    @drop="dropped">
    <BasicButton class="potion-right-cook btn-black" 
      @click="cook" >
        Cook
    </BasicButton>
  </section>

<!-- 結果畫面 -->
  <section v-else-if="afterCook" class="potion-right-result dp-flex-col">
      <h3 class="potion-right-result-bigtitle">{{$t(resultBigTitle)}}</h3>
      <div class="potion-right-img-wrapper" :class="{'no-content':!hasContent}">
        <img class="potion-right-result-img" :src=resultImg alt="" :class="{'potion-right-glass':!hasContent}" >
      </div>
      <h5 class="potion-right-result-title" v-show="hasContent">{{ $t(resultTitle) }}</h5>
      <p class="potion-right-result-intro" v-show="hasContent">{{ $t(resultIntro) }}</p>
      <BasicButton class="potion-right-retry btn-black" 
      @click="start">
        Retry
      </BasicButton>
  </section>
</template>

<style scoped lang="scss">
.potion-right-initial{
  width: 100%;
  align-items:center ;
  min-height: 100%;
  flex-basis: 0;

  .potion-right-title{
    flex-basis: 0;
    flex-grow: 2;
    color: $color-fsTitle;
    margin-bottom: 20%;
  
  }
  .potion-right-glass{
    flex-basis: 0;
    flex-grow: 5;
    align-items: center;
    // margin-bottom: 5%;
  }
  .potion-right-rule{
    flex-basis: 0;
    flex-grow:2;
    color: $color-fsContent;

    margin-bottom: 10%;
  }
  .potion-right-start{
    flex-basis: 0;

    width: max-content;
  }
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
    // margin: -40px;
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
          top: 40%;
          left: 82%;
        }
        &#img6{
          top: 10%;
          left: 55%;
        }
      }
      &#img2, &#img10{
        width: 21%;
        &#img2{
          top: 32%;
          left: 36%;
        }
         &#img10{
          top:47%;
          left: 8%;
         }
      }
      &#img7{
        width: 25%;
        &#img7{
          top: 55%;
          left: 73%;
        }
      }
      &#img8{
        width: 23%;
        top: 10%;
        left: 23%;
      }
      &#img9, &#img5,&#img4{
        width: 16%;
        &#img4{
          top: 26%;
          right: 21%;
        }
        &#img9{
          top: 25%;
          left: 0%;
        } 
        &#img5{
          top:51%;
          left: 49%;
        }
      }
    }
  
  }
  .potion-right-pot{
    width: 35%;
  
    position:absolute;
    bottom: 10%;
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

.potion-right-result{
  align-items:center ;
  height: 100%;
  width: 100%;
  position: relative;
  .potion-right-result-bigtitle, .potion-right-result-title{
    color: $color-fsTitle;
    flex-basis: 0;
    &.potion-right-result-bigtitle{
      // margin-top: 2%;
      flex-grow: 1;
    }
    &.potion-right-result-title{
      flex-grow: 1;
      padding: 0 5%;
      margin-top: 3%;

    }
    
  }
  .potion-right-img-wrapper{
    flex-basis: 0;
    // flex-grow: 2; 
    &.no-content{
      flex-grow: 2;
      padding: 14%;
    }
    
    .potion-right-result-img{
      width: 100%;
      max-width: 350px;
      max-height: 250px;
      
    }
  }
    .potion-right-result-intro{
      color: $color-fsContent;
      padding: 2% 10%;
      min-height: 11%;
      width: 100%;
      flex-basis: 0;
      flex-grow: 2;
    }

    .potion-right-retry{
      // width:;
      // position: absolute;
      // bottom: 5%;
      flex-basis:  max-content;
      flex-grow: 0;
    }
  
}


</style>
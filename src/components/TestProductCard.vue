<script setup>
import { ref, onMounted } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useRouter } from 'vue-router';

// 宣告常數來接收 useRouter() ，方便後續使用
const router = useRouter();

// 設定路由功能
function goProductDetail(){
  router.push('/shop/productdetail')
};


// 圖片陣列
const cards = ref([
  { id: 1, img: 'Shop/1.png', isLike: false },
  { id: 2, img: 'Shop/2.png', isLike: false },
  { id: 3, img: 'Shop/3.png', isLike: false },
  { id: 4, img: 'Shop/4.png', isLike: false },
  { id: 5, img: 'Shop/5.png', isLike: false },
  { id: 6, img: 'Shop/6.png', isLike: false },
])

// 接收商品陣列 ＊要放在 canvas 之前，程式才能讀得到
const props = defineProps({
  products:{
    type: Array,
    required: true,
    default: () => []  // 預設空陣列，避免報錯  <<要在了解一下原因>>
  }
})

const canvasRefs = ref([])
const pi = x => x * Math.PI / 180
// const function pi(x){
//  x * Math.PI / 180
//} 

function draw(canvasElement, long, Camera, radius, imageSrc) {
  console.log('draw 函數被呼叫，圖片路徑:', imageSrc);
  

  const canvas = canvasElement; 
  if (!canvas) return;
  
  const context = canvas.getContext('2d');
  
  canvas.width = long;
  canvas.height = long;

  const img = new Image();

  
  img.onload = () => {
    context.clearRect(0, 0, long, long);
    
    context.beginPath();
    context.arc(radius, radius, radius, pi(180), pi(270));
    context.arc(long - radius, radius, radius, pi(270), pi(0));
    context.arc(long - radius, long - Camera - radius, radius, pi(0), pi(90));
    context.arc(long - Camera + radius, long - Camera + radius, radius, pi(270), pi(180), true);
    context.arc(long - Camera - radius, long - radius, radius, pi(0), pi(90));
    context.arc(radius, long - radius, radius, pi(90), pi(180));
    context.closePath();

    context.save(); 
    context.clip(); 
    context.drawImage(img, 0, 0, long, long);
    context.restore(); 
  }

  img.src = imageSrc; 
}

onMounted(() => {

  // 遍歷每一個 Canvas DOM 元素並執行繪圖
  // canvasRefs.value 是一個陣列，裡面裝著所有的 canvas 元素
  canvasRefs.value.forEach((canvasEl, index) => {
    const imageUrl = props.products[index].main_pic;
    const finalImageUrl = `${import.meta.env.BASE_URL}${imageUrl}`
    draw(canvasEl, 230, 70, 32, finalImageUrl);
  })
})


// like 切換
//const isLike = ref(false);
function likeHeart(product){
  //isLike.value = !isLike.value;  這樣會讓所有的收藏連動
  product.isLike = !product.isLike;  //只調整點選的那一張
  // 之後要連動到資料庫更新狀態
  
}



</script>

<template>
  <div class="product-case"  >
    <div 
      v-for="(product, index) in products" 
      :key="product.id" 
      class="product-card dp-flex-col"
      @click="goProductDetail"
    >
    <!-- 從商品陣列中取出每一個物件及物件再陣列中的索引
         並且把物件的 id 紀錄為每個 div 的 key 值，讓每個 div 差異化，vue 才能透過 key 值分辨跟單獨處理他們
         如果不這樣做，可能每次的動作都會造成 vue 要重新渲染所有的內容，影響效能，但是有 key 值，vue 就可以只更新有變化的部分
         :key 是 vue 提供的屬性，所以名稱不能改，不然 vue 無法辨識 -->

    <!-- index 取出來，其他標籤跟 function 才能使用，如果不使用就不用取 -->
     
      <canvas 
        :ref="(el) => canvasRefs[index] = el" 
        class="myCanvas"
       
      ></canvas>
      <!-- el 是指 DOM 元素本身 -->

      
      <div class="product-icon dp-flex-col">
        <div class="btn-like" @click.stop="likeHeart(product)">
          <!-- 用 product 作為參數，不用 index 的原因是，會跟其他組件還有後端溝通，所以這樣使用 product 的資料會比較直接，不用再用 index 找，造成 bug-->
          <!-- .stop：阻止向上冒泡（連動）到父層的 click 事件，是vue的事件修飾符 
               如果僅是為了阻止，這樣寫會容易閱讀，也能一眼看出，likeHeart 函數也有比較乾淨的執行邏輯 
               如果想在 likeHeart 函數裡寫，需要另外傳入 $event 來進行 DOM 的操作，每次點擊時，也需要多傳一個 event 物件，因此除非有其他複雜的處理，再用此方法，例如 有條件地阻止事件冒泡、取得點擊座標等等-->
          <!-- <font-awesome-icon v-if="isLike" class="detail-pic-icon" icon="fa-solid fa-heart" /> -->
          <font-awesome-icon v-if="product.isLike" class="detail-pic-icon" icon="fa-solid fa-heart" />
          <font-awesome-icon v-else class="detail-pic-icon" icon="fa-regular fa-heart" />
        </div>
        <font-awesome-icon icon="fa-solid fa-cart-shopping" />
      </div>
  
      <h6 class="product-name" >{{product.name_en}}</h6>
      <div class="product-content dp-flex" >
        <p class="product-tag">#{{product.type}}</p>
        <h6 class="product-price">NT {{product.price}}</h6>
      </div>
      <!-- <h6 class="product-name" >Bamboo Helicopter</h6>
      <div class="product-content dp-flex" >
        <p class="product-tag">#Traditional Toys</p>
        <h6 class="product-price">NT 300</h6>
      </div> -->
    </div>
  </div>

</template>

<style scoped lang="scss">
  .product-case {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    gap: 40px;

    position: relative;
  }
  .product-card{
    width: 270px;
    height: 400px;

    backdrop-filter: blur(5px);
    position: relative;
    border-radius: 32px;
    
    justify-content: center;
    align-items: center;

    cursor: pointer;
  }

 .product-card:hover{
  box-shadow: 0 0 20px rgba(255, 255, 255, 0.5) ;
 }

 .product-card:hover .fa-cart-shopping{
  animation: shake 1s ease-in-out infinite;
 }

 @keyframes shake{
  0%, 100% {
    transform: rotate(0) scale(1);
  }
  10%, 30%, 50%, 70%, 90% {
    transform: rotate(5deg) scale(1.1);
  }
  20%, 40%, 60%, 80% {
    transform: rotate(-5deg) scale(1.1);
  }
 }

  .product-icon{ 
    width: 205px;
    height: 200px;
    justify-content: space-between;
    position: absolute;
    top: 50px;
    right: 25px;
  }

  .product-icon .fa-heart{
   color: $color-fsRed;
   font-size: 24px;
  }

  .product-icon .fa-cart-shopping{
    font-size: 32px;
    align-self: flex-end;
  }

  .btn-like{
    width: fit-content;
  }


  .product-name{
    color: $color-fsWhite;
    align-self: flex-start;
    padding: 12px 20px;
    height: 88px;
  }

  .product-content{
    align-items: center;
    justify-content: space-between;
    width: 230px;
  }

  .product-tag{
    color: $color-fsTitle;
    background-color: $color-fsCaption;
    border-radius: 10px;
    padding: 0 8px;
    font-size: 1.2rem;
  }

  .product-price{
    color: $color-fsRed;
  }

  // RWD--------------------------------
@media screen and (max-width: 1200px) {
    .product-case {
    grid-template-columns: 1fr 1fr 1fr;
  }
}


</style>
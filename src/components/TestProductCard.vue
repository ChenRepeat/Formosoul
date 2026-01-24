<script setup>
import { ref, onMounted, watch, nextTick, computed } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useRouter, useRoute } from 'vue-router';
import { useI18n } from 'vue-i18n';     //語系控制
import { useCartStore } from '@/stores/cart';   //加入購物車按鈕
import { useAuthStore } from "@/stores/autoStore";       //確認登入狀態

// 宣告常數來接收，方便後續使用 ----------------------------------------------
const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

// 設定路由功能
function goProductDetail(id){
  router.push({
    name:'ProductDetail',
    params: { id: id }
  })
};

/* 假資料
// 圖片陣列 ----------------------------------------------
const cards = ref([
  { id: 1, img: 'Shop/1.png', isLike: false },
  { id: 2, img: 'Shop/2.png', isLike: false },
  { id: 3, img: 'Shop/3.png', isLike: false },
  { id: 4, img: 'Shop/4.png', isLike: false },
  { id: 5, img: 'Shop/5.png', isLike: false },
  { id: 6, img: 'Shop/6.png', isLike: false },
])
*/

// 接收商品陣列 ＊要放在 canvas 之前，程式才能讀得到 ----------------------------------------------
const props = defineProps({
  products:{
    type: Array,
    required: true,
    default: () => []  // 預設空陣列，避免報錯  <<要在了解一下原因>>
  },
  withwhite:{
    type:Boolean,
    default: false
  }
})


const canvasRefs = ref([])
const pi = x => x * Math.PI / 180
// const function pi(x){
//  x * Math.PI / 180
//} 

function draw(canvasElement, long, Camera, radius, imageSrc) {
  // console.log('draw 函數被呼叫，圖片路徑:', imageSrc);
  

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

// 為了讓每次接收的資料更新時，圖片可以重新繪製，加上 watch 跟 nextTick
// step1. 將繪製的方式變成一個能獨立執行的函數，就能重複
function drawProductImage(){
  // 使用 nextTick 確保 DOM 已經更新完成
  nextTick(() => {
    //保護機制
    if (canvasRefs.value.length === 0) return;

    canvasRefs.value.forEach((canvasEl, index) => {

      //保護機制
      const product = props.products[index];

      // 【防呆】如果資料還沒來，就安靜地結束，等待 watch 再次呼叫
      if (!product || !product.image || product.image.length === 0) {
        return;
      }

    const imageUrl = props.products[index].image[0];
    const finalImageUrl = `${import.meta.env.BASE_URL}${imageUrl}`
    draw(canvasEl, 230, 70, 32, finalImageUrl);
    })
  })
}



onMounted(() => {
  //step2. 呼叫繪製函數
  drawProductImage();
  
  /* 原方案：掛載時繪製一次，之後有任何變動都不會重新繪製
  // 遍歷每一個 Canvas DOM 元素並執行繪圖
  // canvasRefs.value 是一個陣列，裡面裝著所有的 canvas 元素
  canvasRefs.value.forEach((canvasEl, index) => {
    const imageUrl = props.products[index].images[0];
    const finalImageUrl = `${import.meta.env.BASE_URL}${imageUrl}`
    draw(canvasEl, 230, 70, 32, finalImageUrl);
  })
  */
})

//step3. 監聽 products 的變化。 
// ＊＊現在因為陣列中有isLike，所以他改變就會讓canvas重繪，如果之後從後端取出的還是有isLike，可以先把陣列轉換成沒有isLike的陣列，作為重繪的基礎依據
watch(
  () => props.products,
  () =>{

    // 清空舊的 canvas，避免殘留
    canvasRefs.value = [];
      
    // 重新繪製所有 canvas
    drawProductImage();
  },
  { deep: true,           // deep 
    //immediate: false    //可以不寫，因為預設為false
  } 
);


// like 切換 ----------------------------------------------
//const isLike = ref(false);

// 設定環境變數
const apiBase = import.meta.env.VITE_API_BASE;
const apiURL = `${apiBase}/changeCollection.php`;

async function likeHeart(product){

  // step0 不管有沒有登入，優先讓使用者看到畫面（如果後續執行失敗再變回來即可）
  //isLike.value = !isLike.value;  這樣會讓所有的收藏連動
  product.isLike = !product.isLike;  //只調整點選的那一張
  
  // step1 先判斷登入狀態，再開始處理收藏  =======
  if(authStore.token){

    // 取得需要的商品跟會員 ID 
    // 商品的在商品資料中
    const urlProductID = product.product_ID;   

    // 會員的在 storage
    const storageUser = localStorage.getItem('user');

    // 宣告變數準備裝 ID
    let storageMemberID = null;

    // 2. 如果有抓到 user，就把它轉成物件，再拿出裡面的 member_ID
    if (storageUser) {
        const userObj = JSON.parse(storageUser); // 把字串變成物件
        storageMemberID = userObj.member_ID;     // 拿到 300018
    }

    console.log('網址ID (route.params.id):', urlProductID);
    console.log('會員ID (storage):', storageMemberID);


    // 避免找不到 ID 的情況
    if(!urlProductID || !storageMemberID){
      console.error("找不到商品ID或會員ID");
      return;
    }


    // step2 用 fetch 發送請求  =======
    try{
      const response = await fetch(apiURL,{
        method: 'POST',
        headers: {
          // fetch 必須告訴後端傳的是 JSON
          // 這代表 php 接資料時，不能用$_POST，因為 body 不會是 formData 而是 JSON
          'Content-Type': 'application/json',
        },
        // fetch 必須把物件轉成 JSON 字串
        body: JSON.stringify({
          member_id: storageMemberID,
          product_id: urlProductID
        })
      });

      //fetch 如果有 404(網址錯) 或 500(伺服器報錯) ，不會自動跑到 catch，所以要加寫這行判斷
      //.ok 確認請求狀態碼事不是在成功範圍內
      if (!response.ok) {     
        throw new Error(`伺服器錯誤: ${response.status}`);  //throw  錯誤處理機制，拋出例外來讓 catch 接
      }

      //接回傳的資料 
      //如果接下來沒有要用資料執行其他動作時，不一定要接
      //開發階段為了測試，需要接
      //如果想確保狀態同步，需要接資料來強制校正，讓前端的顯示都以資料庫最後拿到的為準
      const data = await response.json();

      //測試
      console.log('資料庫更新成功:', data);

      //強制校正的語法
      if (data.current_status !== undefined) {
        //如果後端現在是 1，愛心就是亮的；如果是 0，就是暗的

        //寫法一 縮寫 先執行括號裡的判斷 程式會先看右邊的 (data.current_status == 1)。
        //後端回傳1 為true，後段回傳0 為false
        product.isLike = (data.current_status == 1);

        /*寫法二 原始寫法
        if (data.current_status == 1) {
            product.isLike = true; 
        } else {
            product.isLike = false;
        }
        */
      }

    }catch(error){
      console.error('API 錯誤:', error);
      // step3. 如果沒有成功，要把愛心變回來
      product.isLike = !product.isLike;
      alert('收藏失敗，請檢查網路連線');
    }

  }else{
    // 如果沒登入，愛心也要變回來，然後跳出登入視窗
    product.isLike = !product.isLike;
    authStore.openLoginModal();
    authStore.setmemberView('login');
    // router.push(''); 這句表示跳到這頁的最上面  
  }
 
}

//加入購物車按鈕 --------------------------------------------
const cartstore = useCartStore();


// 語系切換  -------------------------------------------------------
const { locale } = useI18n();

// 語系切換對照
const langList = {
    'en-US': 'en',
    'zh-TW': 'zh'
};

const lang = computed( () => {
    return langList[locale.value] || 'en';
});



</script>

<template>
  <div class="product-case"  >
    <div 
      v-for="(product, index) in products" 
      :key="product.product_ID" 
      class="product-card dp-flex-col"
      :class="{ 'withwhite': withwhite}"
      @click="goProductDetail(product.product_ID)"
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
        <font-awesome-icon icon="fa-solid fa-cart-shopping" @click.stop="cartstore.addToCart(product)"/>
      </div>
  
      <h6 class="product-name" :class="{ 'withwhite': withwhite}">{{product[`name_${lang}`]}}</h6>
      <div class="product-content dp-flex" >
        <p class="product-tag" :class="{ 'withwhite': withwhite}">#{{product[`type_${lang}`]}}</p>
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
  box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
      &.withwhite{
      box-shadow: 0 0 20px $color-fsBlue900;
    }
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
    &.withwhite{
      color: $color-fsTitle;
    }
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
    &.withwhite{
      color: $color-fsWhite;
    }
  }

  .product-price{
    color: $color-fsRed;
  }

  // RWD--------------------------------
@media screen and (max-width: 1366px) {
    .product-case {
    grid-template-columns: 1fr 1fr 1fr;
  }
}


</style>
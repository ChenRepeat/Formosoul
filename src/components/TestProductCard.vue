<script setup>
import { ref, onMounted } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';


// 圖片陣列
const cards = ref([
  { id: 1, img: 'Shop/1.png' },
  { id: 2, img: 'Shop/2.png' },
  { id: 3, img: 'Shop/3.png' },
  { id: 4, img: 'Shop/4.png' },
  { id: 5, img: 'Shop/5.png' },
  { id: 6, img: 'Shop/6.png' },
])

const canvasRefs = ref([])
const pi = x => x * Math.PI / 180
// const function pi(x){
//  x * Math.PI / 180
//} 

function draw(canvasElement, long, Camera, radius, imageSrc) {
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
    const imageUrl = cards.value[index].img;
    const finalImageUrl = `${import.meta.env.BASE_URL}${imageUrl}`
    draw(canvasEl, 230, 70, 32, finalImageUrl);
  })
})
</script>

<template>
  <div class="product-case">
    <div 
      v-for="(card, index) in cards" 
      :key="card.id" 
      class="product-card dp-flex-col"
    >
     
      <canvas 
        :ref="(el) => canvasRefs[index] = el" 
        class="myCanvas"
      ></canvas>

      <div class="product-icon dp-flex-col">
        <font-awesome-icon icon="fa-regular fa-heart" />
        <font-awesome-icon icon="fa-solid fa-cart-shopping" />
      </div>
      
  
      <h6 class="product-name">Bamboo Helicopter</h6>
      <div class="product-content dp-flex">
        <p class="product-tag">#Traditional Toys</p>
        <h6 class="product-price">NT 300</h6>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
  .product-case {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;

    // display: flex;
    // flex-wrap: wrap;
    // justify-content: center;
    gap: 40px;

    position: relative;
  }
  .product-card{
    width: 270px;
    height: 370px;

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


  .product-name{
    color: $color-fsWhite;
    align-self: flex-start;
    padding: 12px 20px;
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


</style>
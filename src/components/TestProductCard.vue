<script setup>
import { ref, onMounted } from 'vue'


// 圖片陣列
const cards = ref([
  { id: 1, img: '../../public/Shop/1.png' },
  { id: 2, img: '../../public/Shop/2.png' },
  { id: 3, img: '../../public/Shop/3.png' },
  { id: 4, img: '../../public/Shop/4.png' },
  { id: 5, img: '../../public/Shop/5.png' },
  { id: 6, img: '../../public/Shop/6.png' },
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
    draw(canvasEl, 240, 70, 32, imageUrl);
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
    gap: 40px;
  }
  .product-card{
    width: 280px;
    height: 360px;
    background-color: #ffffff95;
    position: relative;
    border-radius: 32px;
    
    justify-content: center;
    align-items: center;
  }
  .myCanvas{
    transition: all 1s;
  }
  .product-card:hover .myCanvas{
    transform: scale(1.1);
  }

  .product-name{
    color: $color-fsTitle;
    align-self: flex-start;
    padding: 12px 20px;
  }

  .product-content{
    align-items: center;
    justify-content: space-between;
    width: 240px;
  }

  .product-tag{
    color: $color-fsTitle;
    background-color: $color-fsBlue50;
    border-radius: 10px;
    padding: 0 8px;

    // align-self: flex-start;
  }

  .product-price{
    color: $color-fsRed;
  }


</style>
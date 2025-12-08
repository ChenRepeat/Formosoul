<script setup>
import Backgroundaction from '@/components/backgroundaction.vue';
import BasicButton from '@/components/BasicButton.vue';
import TestProductCard from '@/components/TestProductCard.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';



</script>


<template>
<main class="shop-main">

  <!-- 上方選單 -->
  <h6>All Product</h6>
  <div class="dp-flex">
    <div class="shop-search">
      <form class="shop-search-content" action="" method="GET">
          <input type="search" id="" name="" placeholder="Search">
          <button type="submit" class="search-submit-btn">
            <font-awesome-icon icon="fa-solid fa-magnifying-glass" />
          </button>
      </form>
    </div> 

    <nav class="shop-nav dp-flex">
      <h6 class="fw200">New Arrivals</h6>
      <font-awesome-icon icon="fa-solid fa-angle-down" />
    </nav>

  </div>

  <ul class="shop-category dp-flex">
    <li class="shop-category-group">
      <div class="shop-dock">
        <div class="shop-liquidGlass">
          <font-awesome-icon class="shop-faIcon" icon="fa-solid fa-gamepad" />
        </div>
      </div>
      <p> Traditional Toys</p>

    </li>

    <li class="shop-category-group">

      <div class="shop-dock">
        <div class="shop-liquidGlass">
          <font-awesome-icon class="shop-faIcon" icon="fa-solid fa-box-open" />
        </div>
      </div>

      <p>Custom Product</p>
    </li>

    <li class="shop-category-group">

      <div class="shop-dock">
        <div class="shop-liquidGlass">
          <font-awesome-icon class="shop-faIcon" icon="fa-solid fa-ring" />
        </div>
      </div>

      <p>Accessories</p>
    </li>

    <li class="shop-category-group">

      <div class="shop-dock">
        <div class="shop-liquidGlass">
          <font-awesome-icon class="shop-faIcon" icon="fa-solid fa-ticket" />
        </div>
      </div>

      <p>Voucher</p>
    </li>
  </ul>

<!-- 中間商品列表 -->
  <section class="shop-productList">
    
    <TestProductCard />

  </section>

  <!-- 下方頁碼 -->
  <nav class="shop-downlist fw200">
    <font-awesome-icon icon="fa-solid fa-angle-left" />
    <span class="shop-page click">1</span>
    <span class="shop-page">2</span>
    <span class="shop-page">3</span>
    <font-awesome-icon icon="fa-solid fa-angle-right" />
  </nav>
</main>




<!-- <BasicButton class="btn-yellow-fill"/> -->

<Backgroundaction></Backgroundaction>
</template>

<style scoped lang="scss">
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
}

// 上方選單

.shop-main{
  background-image: url(../../public/Shop/bgi.jpg);
  padding: 100px 120px;
  color: $color-fsWhite;
}

.shop-main>h6{
  padding-bottom: 60px;
}

.shop-main>div{
  font-size: 16px;
  justify-content:space-between;

}
.shop-nav{
  align-items: center;
}

// 上方商品分類

.shop-category{
  justify-content: center;
} 

.shop-category-group{
  margin: 40px;
  cursor: pointer;
}

.shop-category-group .shop-faIcon{
  font-size: 48px;
}

.shop-category-group:hover .shop-dock{
  transform: scale(1.1);
}

.shop-dock{
  width: 120px;
  height: 120px;
  border-radius: 50%;
  backdrop-filter: blur(5px);
  box-shadow: inset 0 0 120px rgba(255, 255, 255, 0.2);
}

.shop-liquidGlass{
  width: 120px;
  height: 120px;
  border-radius: 50%;
  backdrop-filter: blur(15px);
  box-shadow: 0 0 8px rgba(255, 255, 255, 0.5) ;

  position: relative;
}

.shop-liquidGlass>svg{
  color: $color-fsWhite;
  
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.shop-category-group p{
  margin-top: 20px;
  text-align: center;
}

//中間商品列表
.shop-productList{
  max-width: 1200px;
  margin: 60px auto 0;
}

// 下方頁碼

.shop-downlist{
  padding: 120px 0 0;
  
  font-size: 16px;
  line-height: 160%;
  margin: 0 auto;
  text-align: center;
}

.shop-page{
  border-radius: 3px;
  border: 1px solid $color-fsWhite;
  display: inline-block;
  width: 25.6px;
  margin: 10px;
  cursor: pointer;
}

.shop-page.click, .shop-page:hover{
  background-color: $color-fsWhite;
  color: $color-fsTitle;
}


</style>

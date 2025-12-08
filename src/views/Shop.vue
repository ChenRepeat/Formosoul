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

      <div class="liquidGlass-wrapper dock">
        <div class="liquidGlass-effect"></div>
        <div class="liquidGlass-tint"></div>
        <div class="liquidGlass-shine"></div>
        <div class="liquidGlass-text">
          <font-awesome-icon class="shop-faIcon fw200" icon="fa-solid fa-gamepad" />
        </div>
      </div>

      <p> Traditional Toys</p>

    </li>

    <li class="shop-category-group">

      <div class="liquidGlass-wrapper dock">
        <div class="liquidGlass-effect"></div>
        <div class="liquidGlass-tint"></div>
        <div class="liquidGlass-shine"></div>
        <div class="liquidGlass-text">
          <font-awesome-icon class="shop-faIcon fw200" icon="fa-solid fa-box-open" />
        </div>
      </div>

      <p>Custom Product</p>
    </li>

    <li class="shop-category-group">

      <div class="liquidGlass-wrapper dock">
        <div class="liquidGlass-effect"></div>
        <div class="liquidGlass-tint"></div>
        <div class="liquidGlass-shine"></div>
        <div class="liquidGlass-text">
          <font-awesome-icon class="shop-faIcon fw200" icon="fa-solid fa-ring" />
        </div>
      </div>

      <p>Accessories</p>
    </li>

    <li class="shop-category-group">

      <div class="liquidGlass-wrapper dock">
        <div class="liquidGlass-effect"></div>
        <div class="liquidGlass-tint"></div>
        <div class="liquidGlass-shine"></div>
        <div class="liquidGlass-text">
          <font-awesome-icon class="shop-faIcon fw200" icon="fa-solid fa-ticket" />
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
    <span>1</span>
    <span>2</span>
    <span>3</span>
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

.dock {
  width: 120px;
  height: 120px;
  border-radius: 50%;
}

.shop-category-group p{
  margin-top: 20px;
  text-align: center;
}


/* LIQUID GLASS STYLES */

.liquidGlass-wrapper {
  position: relative;
  display: flex;
  font-weight: 600;
  overflow: hidden;

  box-shadow: 0 6px 6px rgba(0, 0, 0, 0.2), 0 0 20px rgba(0, 0, 0, 0.1);

  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 2.2);
}

.liquidGlass-effect {
  position: absolute;
  z-index: 0;
  inset: 0;

  backdrop-filter: blur(3px);
  filter: url(#glass-distortion);
  overflow: hidden;
}

.liquidGlass-tint {
  z-index: 1;
  position: absolute;
  inset: 0;
  background: rgba(255, 255, 255, 0.50);
}

.liquidGlass-shine {
  position: absolute;
  inset: 0;
  z-index: 2;

  overflow: hidden;

  //這邊的inset數值有調整過
  box-shadow: inset 1px 1px 1px 0 rgba(255, 255, 255, 0.5),
              inset -1px -1px 1px 0px rgba(255, 255, 255, 0.5);
}

.liquidGlass-text {
  z-index: 3;
  
  //這行是自己設定的，讓icon置中
  margin: auto;
}

//中間商品列表
.shop-productList{
  margin-top: 40px;
}


// 下方頁碼

.shop-downlist{
  padding: 120px 0 0;

  font-size: 16px;
  line-height: 160%;
  margin: 0 auto;
  text-align: center;
}

.shop-downlist span{
  border-radius: 3px;
  border: 1px solid $color-fsWhite;
  display: inline-block;
  width: 25.6px;
  margin: 10px;
}

</style>

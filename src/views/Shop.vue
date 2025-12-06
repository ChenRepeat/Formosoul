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
  <nav class="dp-flex">
    <div class="shop-search">
      <form class="shop-search-content" action="" method="GET">
          <input type="search" id="" name="" placeholder="Search">
          <button type="submit" class="search-submit-btn">
            <font-awesome-icon icon="fa-solid fa-magnifying-glass" />
          </button>
      </form>
    </div> 

    <div class="shop-nav dp-flex">
      <h6 class="fw200">New Arrivals</h6>
      <font-awesome-icon icon="fa-solid fa-angle-down" />
    </div>

  </nav>

  <ul class="shop-category dp-flex">
    <li class="shop-category-group">
      <div>
        <font-awesome-icon class="shop-faIcon fw200" icon="fa-solid fa-gamepad" />
      </div>
      <p> Traditional Toys</p>
    </li>
    <li class="shop-category-group">
      <div>
        <font-awesome-icon class="shop-faIcon fw200" icon="fa-solid fa-box-open" />
      </div>
      <p>Custom Product</p>
    </li>
    <li class="shop-category-group">
      <div>
        <font-awesome-icon class="shop-faIcon fw200" icon="fa-solid fa-ring" />
      </div>
      <p>Accessories</p>
    </li>
    <li class="shop-category-group">
      <div>
        <font-awesome-icon class="shop-faIcon fw200" icon="fa-solid fa-ticket" />
      </div>
      <p>Voucher</p>
    </li>
  </ul>

<!-- 中間商品列表 -->
  <section>
    
    
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




<BasicButton class="btn-yellow-fill"/>

<button class="btn-yellow-fill">next <font-awesome-icon icon="fa-solid fa-angle-right" /> </button>
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
  background-color: $color-fsTitle;
  padding: 100px 120px;
  color: $color-fsWhite;
}

.shop-main>h6{
  padding-bottom: 60px;
}

.shop-main nav{
  font-size: 16px;
  justify-content:space-between;

}
.shop-nav{
  align-items: center;
}

// 中間商品列表
.shop-category-group{
  margin: 40px;
}

.shop-category-group div{
  background-color: $color-fsCaption;
  opacity: 0.3;
  border-radius: 50%;
  width: 120px;
  height: 120px;
}

.shop-category-group .shop-faIcon{
  font-size: 48px;
  opacity: 1;
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

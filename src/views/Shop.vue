<script setup>
import BasicButton from '@/components/BasicButton.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import ProductList from '@/components/SHOP/ProductList.vue';
import ProductDetail from '@/components/SHOP/ProductDetail.vue';
import CartStep1 from '@/components/SHOP/CartStep1.vue';
import CartStep2 from '@/components/SHOP/CartStep2.vue';
import CartStep3 from '@/components/SHOP/CartStep3.vue';



</script>


<template>
<main class="shop-main">

  <!-- 麵包屑 -->
  <h6>All Product</h6>

  <ProductList />
  <ProductDetail />
  <CartStep1 />
  <CartStep2 />
  <CartStep3 />
</main>




<!-- <BasicButton class="btn-yellow-fill"/> -->


</template>

<style scoped lang="scss">

// 麵包屑
.shop-main{
  background-image: url(../../public/Shop/bgi.jpg);
  padding: 180px 120px 100px;
  color: $color-fsWhite;
}

.shop-main>h6{
  padding-bottom: 60px;
}



</style>

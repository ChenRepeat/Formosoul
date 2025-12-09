<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import ProductList from '@/components/SHOP/ProductList.vue';
import ProductDetail from '@/components/SHOP/ProductDetail.vue';



</script>


<template>
<main class="shop-main">

  <!-- 麵包屑 -->
  <h6>All Product</h6>

  <!-- <ProductList /> -->
  <ProductDetail />
  
</main>

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

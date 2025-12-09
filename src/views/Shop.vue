<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import ProductList from '@/components/SHOP/ProductList.vue';
import ProductDetail from '@/components/SHOP/ProductDetail.vue';



</script>


<template>
<main class="shop-main">

  <!-- <ProductList /> -->
  <ProductDetail />
  
</main>

</template>

<style scoped lang="scss">

.shop-main{
  padding: 180px 120px 100px;
  color: $color-fsWhite;

  //暫時背景
  background-image: url(../../public/Shop/bgi.jpg);
  background-size: cover;
  background-position: right; 
}

</style>

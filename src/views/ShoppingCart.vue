<script setup>
import OrderSuccess from '@/components/Cart/OrderSuccess.vue';
import ShoppingCheck from '@/components/Cart/ShoppingCheck.vue';




</script>

<template>
<main class="shoppingcart-main">

  
    <ShoppingCheck /> 
    <OrderSuccess />
   

</main>

</template>

<style scoped lang="scss">

  .shoppingcart-main{
  padding: 180px 120px 100px;
  color: $color-fsWhite;

  //暫時背景
  background-image: url(../../public/Shop/bgi.jpg);
  background-size: cover;
  background-position: right; 
}

</style>
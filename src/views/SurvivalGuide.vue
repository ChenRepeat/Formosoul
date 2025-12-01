<!-- <ProductCard /> 引用 -->
<script setup>
  import ProductCard from '@/components/ProductCard.vue' // 引用

</script>


<template>
<!-- :style="{top: `${position.y}px`, left: `${position.x}px`}" -->
  <main class="survival-case">
    <div ref="lanternRef" class="survial-lantern-case" >
      <img src="/public/SurvivalGuide/lantern-image.png" class="survial-lantern-img-case" alt="">
    </div>

    <section>
      <div class="survival-headline-case">
        <h3 class="survival-headline-inner1-case">Welcome to survival guide！</h3>


      </div>
    </section>
  </main>


</template>


<style scoped lang="scss">
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
}

.survival-headline-case {
  color: $color-primary;
  font-weight: bold;
}



</style>

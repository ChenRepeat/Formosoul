<script setup>
import Backgroundaction from '@/components/backgroundaction.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';


</script>


<template>
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
</style>

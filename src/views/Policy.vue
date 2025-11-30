<script setup lang="ts">
import MemberOrders from '@/components/memberOrders.vue';
import MemberPagebutton from '@/components/memberPagebutton.vue';
import Pagelinebar from '@/components/Pagelinebar.vue';

</script>

<template>
  <MemberPagebutton />
  <Pagelinebar />
  <MemberOrders />
</template>

<style>
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
}
</style>

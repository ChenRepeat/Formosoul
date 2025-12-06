<script setup lang="ts">
import Backgroundaction from '@/components/backgroundaction.vue';
import MemberPagebutton from '@/components/Member/MemberPagebutton.vue';
import { ref } from 'vue';


</script>

<template>

    <MemberPagebutton />
    <!-- <Backgroundaction></Backgroundaction> -->

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

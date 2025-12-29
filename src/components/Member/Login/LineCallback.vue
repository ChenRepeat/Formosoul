<template>
  <h2>line-login</h2>
</template>
<script setup>
import { onMounted, watch } from 'vue';
  import { useRoute, useRouter } from 'vue-router';

  const route = useRoute();
  const router = useRouter();

  onMounted(() => {
    console.log(route.params);
})

const id = Number(route.params.id);

watch(
  () => Number(route.params.id), 
  (newId) => {
    if(!isNaN(newId) && newId > 0 && Number.isInteger(newId)){
    console.log(newId) 

    }else{
      router.push('/')
    }
  },
  //立即執行一次
  { immediate: true }
);
</script>
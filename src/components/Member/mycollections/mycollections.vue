<template>
    <div class="collections">
        <ProductCard :images="myimg"></ProductCard>
    </div>
</template>

<script setup>
import ProductCard from '@/components/ProductCard.vue';
import { ref } from 'vue';
    const myimg = ref([
        { id: 1, img: 'https://images.unsplash.com/photo-1682687220742-aba13b6e50ba?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 2, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 3, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
    ]);


</script>

<style scoped lang="scss">
    .collections{
        max-width: 1200px;
        margin: 0 auto;
    }
</style>
<template>
    <div class="Loginpage" :class="{ 'active': isLoginOpen}">
        123
        <div class="Logincontain" @click="toggleLogin">
            123
            <Logincontain></Logincontain>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Logincontain from './logincontain.vue';

    const isLoginOpen = ref(false);
    const  toggleLogin = () => {
        isLoginOpen.value = !isLoginOpen.value;
    };
</script>

<style lang="scss" scoped>
    .Loginpage{
        color: white;
    }
    .Logincontain{
        color: white;
    }
</style>
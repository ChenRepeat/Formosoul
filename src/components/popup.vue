<template>
    <div 
    class="Loginpage" :class="{ 'active': authStore.isLoginModalOpen}" 
    @click.self="authStore.closeLoginModal()"
    >   
    <!-- <forgetpassword></forgetpassword> -->
    <loginpage></loginpage>
</div>
</template>

<script setup>
    import { useAuthStore } from '@/stores/autoStore';
    import Forgetpassword from './Member/Login/forgetpassword.vue';
    import Loginpage from './Member/Login/loginpage.vue';
    const authStore = useAuthStore();
</script>

<style lang="scss" scoped>
    .Loginpage{
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        z-index: 2000;
        // 這段可以看 authStore.isLoginModalOpen 的值 去決定是否要顯示
        &.active {  
            opacity: 1;
            visibility: visible;
        }
    }


</style>
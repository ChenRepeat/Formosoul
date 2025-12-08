<template>
    <div 
    class="Loginpage" :class="{ 'active': authStore.isLoginModalOpen}" 
    @click.self="authStore.closeLoginModal()"
    >
        <div class="Logincontain">
            <div class="Enrollment">Enrollment</div>
            <div class="Login">Login</div>
        <Logincontain></Logincontain>
        </div>
        
    </div>

</template>

<script setup>
import { ref } from 'vue';
import Logincontain from './logincontain.vue';
import { useAuthStore } from '@/stores/autoStore';

    const authStore =useAuthStore();

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
    .Logincontain{
        background-color: $color-fsBlue50;
        border-radius: 12px;
        padding: 40px;
        width: 50%;
        position: relative;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        animation: slideUp 0.3s ease;

        @keyframes slideUp {
            from {
            opacity: 0;
            transform: translateY(30px);
            }
            to {
            opacity: 1;
            transform: translateY(0);
            }
        }
    }
    .Logincontain > button {
        position: absolute;
        top: 16px;
        right: 16px;
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #666;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s ease;


    }

    .Logincontain > button:hover{
        background-color: #f0f0f0;
        color: #000; 
    }


</style>
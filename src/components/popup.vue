<template>
    <div 
    class="Loginpage" :class="{ 'active': authStore.isLoginModalOpen}" 
    @click.self="authStore.closeLoginModal()"
    @keydown="handleKeyDown"
    >
        <div class="Loginout">
        <cardpage></cardpage>  
        <!-- <loginpage></loginpage> -->
        </div>
    </div>
</template>

<script setup>
    import { useAuthStore } from '@/stores/autoStore';
    import Forgetpassword from './Member/Login/forgetpassword.vue';
    import Loginpage from './Member/Login/loginpage.vue';
    import { onMounted, onUnmounted } from 'vue';
import Cardpage from './Member/Login/cardpage.vue';
    const authStore = useAuthStore();

    function handleKeyDown( e ){
        if(e.key == 'Escape'){
            authStore.closeLoginModal();
        }
    };

    onMounted(() => {
        window.addEventListener('keydown', handleKeyDown);
    });

    onUnmounted(() => {
        window.removeEventListener('keydown', handleKeyDown);
    });
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

    .Loginout{
        width: 90%;
        height: 90%;
        // 轉換成RGB 數值 再調 opacity
        // background-color: rgba(93, 93, 93, 0.5);
        // 在原本的 6 碼色碼後面直接加上 2 碼來代表透明度（範圍 00 到 FF）
        background-color: #5D5D5D80;
        display: flex;
        justify-content: center; 
        align-items: center; 
        border: 0;
        border-radius: 8px;
        position: relative;
    }


</style>
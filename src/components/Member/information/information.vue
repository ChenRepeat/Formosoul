<template>
    <div class="information-wrapper">
        
        <Cardpage v-if="authStore.memberView == 'membercard'" hasgap haspadding fontscolor></Cardpage>
        <Ledgerpage v-else-if="authStore.memberView == 'ledger'"  hasgap haspadding fontscolor withoutbtn></Ledgerpage>
        <!-- withoutbtn -->
    </div>
</template>

<script setup>
    import { inject, provide, ref } from 'vue';
    import Cardpage from '../Login/cardpage.vue';
    import Ledgerpage from '../Login/ledgerpage.vue';
import { useAuthStore } from '@/stores/autoStore';

    
    const authStore = useAuthStore();





</script>

<style scoped>
    .information-wrapper{
        width: 1200px;
        margin: 0 auto;
        padding: 48px 8px 16px 8px;
    }
</style>
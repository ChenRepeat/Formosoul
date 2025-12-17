<template>
    <div class="information-wrapper">
        <!-- <Cardpage></Cardpage>         -->
        <Cardpage v-if="authStore.informationView == 'informationmembercard'" hasgap haspadding fontscolor ></Cardpage>
        <Ledgerpage v-else-if="authStore.informationView == 'informationledger'"  hasgap haspadding fontscolor withoutbtn hascenter></Ledgerpage>
        <!-- withoutbtn -->
    </div>
</template>

<script setup>
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
<template>
    <div class="information-wrapper">
        
        <Cardpage v-if="currentview == 'card'" hasgap></Cardpage>
        <Ledgerpage v-else-if="currentview == 'ledger'" withoutbtn></Ledgerpage>
    </div>
</template>

<script setup>
    import { inject, provide, ref } from 'vue';
    import Cardpage from '../Login/cardpage.vue';
    import Membercard from './membercard.vue';
    import Ledgerpage from '../Login/Ledgerpage.vue';

    const currentview = ref('card');

    const setmemberview = (viewname) => {
        currentview.value = viewname;
    };

    provide('setmemberview', setmemberview);



</script>

<style scoped>
    .information-wrapper{
        width: 1200px;
        margin: 0 auto;
        padding: 48px;
    }
</style>
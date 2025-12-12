<template>
    <div class="information-wrapper">
        
        <Cardpage v-if="currentview == 'membercard'" hasgap haspadding fontscolor></Cardpage>
        <Ledgerpage v-else-if="currentview == 'ledger'"  hasgap haspadding fontscolor></Ledgerpage>
        <!-- withoutbtn -->
    </div>
</template>

<script setup>
    import { inject, provide, ref } from 'vue';
    import Cardpage from '../Login/cardpage.vue';
    import Ledgerpage from '../Login/ledgerpage.vue';

    const currentview = ref('membercard');

    const setmemberview = (viewname) => {
        currentview.value = viewname;
    };

    provide('setmemberview', setmemberview);



</script>

<style scoped>
    .information-wrapper{
        width: 1200px;
        margin: 0 auto;
        padding: 48px 16px 16px 16px;
    }
</style>
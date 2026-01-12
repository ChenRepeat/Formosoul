<template>
    <div  class="information-wrapper">
        <!-- <Cardpage></Cardpage>         -->
        <Cardpage v-if="authStore.informationView == 'informationmembercard'" hasgap haspadding fontscolor ></Cardpage>
        <Ledgerpage v-else-if="authStore.informationView == 'informationledger'"  hasgap haspadding fontscolor withoutbtn hascenter></Ledgerpage>
        <!-- withoutbtn -->
    </div>
</template>

<script setup>
    import { useMemberStore } from '@/stores/member';
import Cardpage from '../Login/cardpage.vue';
    import Ledgerpage from '../Login/ledgerpage.vue';
    import { useAuthStore } from '@/stores/autoStore';
import { onMounted, onUnmounted, watch } from 'vue';

    
    const authStore = useAuthStore();
    const memberStore = useMemberStore();
    watch(
    () => memberStore.memberData.wandcore_ID,
    async () => {
        console.log('兄弟自動更新')
        await memberStore.loadMemberData();
    })
    onMounted(() => {
        memberStore.loadMemberData();
    });
    
    onUnmounted(() => {
        memberStore.loadMemberData();
    });


</script>

<style scoped>
    .information-wrapper{
        width: 1200px;
        margin: 0 auto;
        padding: 48px 8px 16px 8px;
    }
    @media screen and (max-width: 1300px) {
        .information-wrapper{
            width: 900px;

        }
    }
</style>
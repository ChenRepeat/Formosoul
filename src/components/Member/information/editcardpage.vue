<template>
    <div class="cardwrapper dp-flex-col">
        <Membercard></Membercard>
        <BasicButton class="btn-yellow-fill" @click="upload">Save changes</BasicButton>
    </div>
</template>

<script setup>
import BasicButton from '@/components/BasicButton.vue';
import Membercard from './membercard.vue';
import { useAuthStore } from '@/stores/autoStore';
import { useMemberStore } from '@/stores/member';

const authStore = useAuthStore();
const memberStore = useMemberStore();

async function upload(){
    try{
        const payload ={
            name: memberStore.memberData.name,
            avatar: memberStore.imgURL
        };
        // startsWith 代表開頭名稱
        if (payload.avatar.startsWith('data:image')) {
            console.log('圖');
        }
        await new Promise(resolve => setTimeout(resolve, 1000));
        alert('已修改完成');

        authStore.closeLoginModal();
    }catch(error){
        console.error(error);
    }
};

</script>

<style lang="scss" scoped>
    .cardwrapper{
        gap: 72px;
    }

    .btn-yellow-fill{
        width: 50%;
        margin: 0 auto;
    }

</style>
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

const authStore = useAuthStore();

function upload(){
    authStore.closeLoginModal();
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
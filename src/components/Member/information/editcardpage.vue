<template>
    <div class="cardwrapper dp-flex-col">
        <Membercard hasscale></Membercard>
        <BasicButton class="btn-yellow-fill" @click="data_change">Save changes</BasicButton>
    </div>
</template>

<script setup>
import BasicButton from '@/components/BasicButton.vue';
import Membercard from './membercard.vue';
import { useAuthStore } from '@/stores/autoStore';
import { useMemberStore } from '@/stores/member';

const authStore = useAuthStore();
const memberStore = useMemberStore();

    const data_change = async() => {
        const storedUser = localStorage.getItem('user');
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/changememberinformation.php`;
        if(!storedUser) return;
        const userData = JSON.parse(storedUser);
        const { member_ID } = userData;
        const originalName = userData.name;
        const newName = memberStore.memberData.name;
        if (originalName === newName) {
            console.log('名字未變動，直接關閉');
            authStore.closeLoginModal(); // 關閉彈窗
            return; // 使用 return 確保後面的 API 請求不會執行
        }
        try{

            const response = await fetch(API_URL, {
                method: 'POST',
                headers:{
                    'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify({name: newName, member_ID})
            });

            const result = await response.json();
            if(result.success){
                userData.name = newName;
                localStorage.setItem('user', JSON.stringify(userData));
                await upload();
            }else{
                console.log('沒改到');
            }
        }catch(error){
            console.error('沒改到');
        }
    };


async function upload(){
    try{
        // const payload ={
        //     name: memberStore.memberData.name,
        //     avatar: memberStore.imgURL
        // };
        // startsWith 代表開頭名稱
        // if (payload.avatar.startsWith('data:image')) {
        //     console.log('圖');
        // }
            await new Promise(resolve => setTimeout(resolve, 500));
            authStore.closeLoginModal();
            memberStore.data_uptime();
            alert('已修改完成');

    }catch(error){
        console.error(error);
    }
};

</script>

<style lang="scss" scoped>
    .cardwrapper{
        height: 60%;
        justify-content: space-between;
    }

    .btn-yellow-fill{
        width: 50%;
        margin: 0 auto;
    }

</style>
<template>
    <div class="cardwrapper dp-flex-col">
        <Membercard hasscale></Membercard>
        <BasicButton class="btn-yellow-fill" @click="data_change" :disabled="isLoading">
            {{ isLoading ? $t('loginPage.loading') : $t('loginPage.saveBtn')}}
        </BasicButton>
    </div>
</template>

<script setup>
import BasicButton from '@/components/BasicButton.vue';
import Membercard from './membercard.vue';
import { useAuthStore } from '@/stores/autoStore';
import { useMemberStore } from '@/stores/member';
import { ref } from 'vue';

const authStore = useAuthStore();
const memberStore = useMemberStore();
const isLoading = ref(false);
    const data_change = async() => {
        // isLoading.value = true;
        const storedUser = localStorage.getItem('user');
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/changememberinformation.php`;
        if(!storedUser) return;
        const userData = JSON.parse(storedUser);
        const { member_ID } = userData;
        const originalName = userData.name;
        const originalimg = userData.headshot;
        const tempName = memberStore.memberData.tempName;
        const tempimg = memberStore.imgURL;
        if(!tempName){
            authStore.closeLoginModal();
            return
        }
        if (originalName === tempName && originalimg === tempimg) {
            authStore.closeLoginModal();
            return; 
        }

        if(memberStore.memberData.isEditing == true){
            userData.name = tempName;
        };
        try{
            isLoading.value = true;
            const response = await fetch(API_URL, {
                method: 'POST',
                headers:{
                    'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify({name: tempName, member_ID, tempimg: tempimg})
            });

            const result = await response.json();
            if(result.success){
                userData.name = tempName;
                userData.headshot = tempimg;
                // tempName = newName;
                localStorage.setItem('user', JSON.stringify(userData));

                upload();
            }else{
                console.log('沒改到');
            }
        }catch(error){
            console.error('沒改到');
        }finally{
            isLoading.value = false;
        }
    };


async function upload(){
    try{
        authStore.closeLoginModal();
        memberStore.data_uptime();
        memberStore.loadMemberData();
        alert('已修改完成');
        isLoading.value = false;
            
    }catch(error){
        console.error(error);
    }finally{
            isLoading.value = false;
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
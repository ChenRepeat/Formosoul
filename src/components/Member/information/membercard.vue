<template>
    <div class="membercard-wrapper" :class="{'hasscale': hasscale}">
        <div class="membercard">
            <div class="memberphoto">
                <div v-if="!memberStore.imgURL" class="add">+</div>
                <img v-else-if="memberStore.imgURL" :src="memberStore.imgURL" alt="會員頭像">
                <input type="file" class="thefile" @change="fileChange" :disabled="authStore.memberView !== 'cardcontain' || route.path == 'member/information'">
            </div>
            <div class="memberinformation">
                <p>Name: <button
                            class="edit-btn"
                            :class="{ 'without': withouteditbtn}"
                            @click="memberStore.memberData.isEditing = !memberStore.memberData.isEditing"
                            >
                            <font-awesome-icon icon="fa-solid fa-pen-to-square" style="font-size: 20px;" />
                        </button></p>
                <div v-if="memberStore.memberData.isEditing">
                    <input
                        v-model="memberStore.memberData.tempName" 
                        @keyup.enter="saveName(memberStore.memberData)"
                        class="input-text"
                    >
                </div> 
                <h6 v-else class="fw200">{{ memberStore.memberData.name }}</h6>
                <p>Wand Core:</p>
                <h6 class="fw200">{{ memberStore.memberData.wandcore }}</h6>
                <p>Enrollment Number:</p>
                <h6 class="fw200">{{ memberStore.memberData.number }}</h6>
                <p>Enrollment Since:</p>
                <h6 class="fw200">{{ memberStore.memberData.date }}</h6>
            </div>
        </div>
        <img :src="`${publicPath}member/icon.png`" alt="">
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useAuthStore } from '@/stores/autoStore';
import { useRoute } from 'vue-router';
import { useMemberStore } from '@/stores/member';
const publicPath = import.meta.env.BASE_URL;
const route = useRoute();
const authStore = useAuthStore();
const memberStore = useMemberStore();
const props = defineProps({
    withouteditbtn:{
        type: Boolean,
        default: false,
    },
    hasscale:{
        type:Boolean,
        default:false,
    },     
});

const loadMemberData = async () => {
    const storedUser = localStorage.getItem('user');
    // console.log(storedUser);
    const apiBase = import.meta.env.VITE_API_BASE;
    const API_URL = `${apiBase}/getMemberinformation.php`;
    if(!storedUser) return;
    
    const { name: loginName } = JSON.parse(storedUser);

    try{
        const response = await fetch(API_URL, {
            method: 'POST',
            headers:{
                'Content-Type': 'application/json; charset=utf-8'
            },
            // 這裡要對應 PHP 的接收格式
            body: JSON.stringify({name: loginName})
        });
        const result = await response.json();
        if(result.success){
            const dbData = result.data;
            memberStore.memberData.name = dbData.name;
            memberStore.memberData.number = "N" +dbData.member_ID;
            memberStore.memberData.date =  dbData.createdate;
            memberStore.memberData.wandcore = dbData.magical_en || 'Not yet selected';
        }else{
            console.error(result.message);
        }
    }catch(error){
        console.error("Fetch 發生錯誤:", error);
    }
};


const fileChange = ( e ) => {
        let file = e.target.files[0];
        if (!file) return;

        const readFile = new FileReader();
        readFile.readAsDataURL(file);

        readFile.addEventListener('load', () => {
            if (memberStore.imgURL != readFile.result) {
                memberStore.updatePhoto(readFile.result);
            } else {
                return;
            }
        });
};


const saveName = () => {
    memberStore.memberData.name = memberStore.memberData.tempName;
    memberStore.memberData.isEditing = false;

    // console.log(memberStore.memberData.name);
};


onMounted(() => {
    loadMemberData();
});


</script>

<style lang="scss" scoped>
    .membercard-wrapper{
        background-color: #EEEEEE;
        width: 500px;
        height: 300px;
        border: 0;
        border-radius: 8px;
        margin: 0 auto;
        display: flex;
        padding-top: 16px;
        padding-left: 16px;
        padding-right: 16px;
        justify-content: center;
        position: relative;
                &.hasscale{
            transform: scale(1.3);
        }
    }
    .membercard-wrapper > img{
        height: 240px;
        width: 240px;
        object-fit: contain;
        position: absolute;
        left: 380px;
        top: 150px;
    }
    .membercard{
        align-items: start;
        display: grid;
        grid-template-columns: 0.8fr 1.3fr;
        gap: 24px;
    }

    .memberphoto{
        width: 140px;
        height: 180px;
        display: flex;
        position: relative;
        top: 30%;  
        left: 50%;
        transform: translate(-50%, -30%);
        
    }

    .memberphoto > img{
        height: 100%;
        width: 100%;
        object-fit: contain;
    }
    .thefile{
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;    
        opacity: 0;

    }
    .edit-btn{
        background-color: transparent;
        border: 0;
        color: $color-fsContent;
        cursor: pointer;

        &.without{
            display: none;
        }
    }
    .input-text{
        height: 32px;
    }

    .add{
        font: bold 100px Tahoma;
        color: #CCC;
        top: 50%;
        left: 50%;
        position: absolute;
        transform: translate(-50%, -50%);
    }
</style>
<template>
    <div class="membercard-wrapper">
        <div class="membercard">
            <div class="memberphoto">
                <img v-if="imgURL" :src="imgURL" alt="會員頭像">
                <input type="file" class="thefile" @change="fileChange" :disabled="authStore.memberView !== 'cardcontain' || route.path == 'member/information'">
            </div>
            <div v-for="member in members" class="memberinformation">
                <p>Name: <button
                            class="edit-btn"
                            :class="{ 'without': withouteditbtn}"
                            @click="member.isEditing = !member.isEditing"
                            >
                            <font-awesome-icon icon="fa-solid fa-pen-to-square" style="font-size: 20px;" />
                        </button></p>
                <div v-if="member.isEditing">
                    <input 
                        v-model="member.tempName" 
                        @keyup.enter="saveName(member)"
                        class="input-text"
                    >
                </div> 
                <h6 v-else class="fw200">{{ member.name }}</h6>
                <p>Wand Core:</p>
                <h6 class="fw200">{{ member.wandcore }}</h6>
                <p>Enrollment Number:</p>
                <h6 class="fw200">{{ member.number }}</h6>
                <p>Enrollment Since:</p>
                <h6 class="fw200">{{ member.date }}</h6>
            </div>
        </div>
        <img :src="`${publicPath}member/icon.png`" alt="">
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useAuthStore } from '@/stores/autoStore';
import { useRoute } from 'vue-router';
const publicPath = import.meta.env.BASE_URL;
const imgURL = ref(`${publicPath}member/photo.png`);
const route = useRoute();
const authStore = useAuthStore();
const props = defineProps({
    withouteditbtn:{
        type: Boolean,
        default: false,
    },
    imgURL:{
        type: String
    }   
});


const fileChange = ( e ) => {
        let file = e.target.files[0];
        // console.log(file);
        const readFile = new FileReader();
        readFile.readAsDataURL(file);
        readFile.addEventListener('load', () => {
            imgURL.value = readFile.result

        })
};


const saveName = (member) => {
    member.name = member.tempName; // 將暫存值寫回正式名稱
    member.isEditing = false;
    // 這裡通常會呼叫 API 把資料存回資料庫
    console.log('儲存成功:', member.name);
};


// const memberphoto = ref([
//     {
//         images: `${import.meta.env.BASE_URL}member/photo.png`,
//     },
// ]);

// const members = ref([
//     {
//         name: 'H.Potter',
//         wandcore: 'Cornu Cervi Pantotrichum',
//         number: 'L09190116',
//         date: '2025.09.19',
//         isEditing: false, // 控制顯示狀態
//         tempName: 'H.Potter' // 暫存編輯中的文字
//     }
// ]);


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
        padding-top: 32px;
        justify-content: center;
        position: relative;
    }
    .membercard-wrapper > img{
        height: 240px;
        width: 240px;
        object-fit: contain;
        position: absolute;
        left: 340px;
        top: 150px;
    }
    .membercard{
        align-items: start;
        display: grid;
        grid-template-columns: 0.2fr 1.2fr;
        gap: 36px;
    }

    .memberphoto{
        width: 140px;
        height: 180px;
        display: flex;
        position: relative;
        
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
</style>
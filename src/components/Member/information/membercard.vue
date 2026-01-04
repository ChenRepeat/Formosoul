<template>
    <div class="membercard-wrapper" :class="{'hasscale': hasscale}">
        <div class="membercard">
            <div class="memberphoto">
                <div v-if="!memberStore.imgURL" class="add">+</div>
                    <div v-else-if="memberStore.imgURL" class="fiximg">
                        <img :src="memberStore.imgURL" alt="會員頭像">
                    </div>
                <div class="mask" v-if="authStore.memberView == 'cardcontain' || route.path == 'member/information'">
                    <font-awesome-icon icon="fa-solid fa-pen" style="font-size: 20px; "/>
                </div>
                <input type="file" class="thefile" @change="fileChange" :disabled="authStore.memberView !== 'cardcontain' || route.path == 'member/information'">
            </div>

            <div class="memberinformation">
                <p>{{ $t('member.memberName') }}
                    <!-- <button
                        class="edit-btn"
                        :class="{ 'without': withouteditbtn}"
                        @click="memberStore.memberData.isEditing = !memberStore.memberData.isEditing"
                    >
                        <font-awesome-icon icon="fa-solid fa-pen-to-square" style="font-size: 20px;" />
                    </button> -->
                </p>
                
                <div v-if="memberStore.memberData.isEditing">
                    <input
                        v-model="memberStore.memberData.tempName" 
                        @keyup.enter="saveName(memberStore.memberData.tempName)"
                        class="input-text"
                    >
                </div> 
                <h6 v-else-if="isNameNull" class="fw200">{{ memberStore.memberData.name }}</h6>
                <h6 v-else class="fw200">{{ memberStore.memberData.tempName }}</h6>
                <p>{{ $t('member.wandCore') }}</p>
                <h6 class="fw200">{{ memberStore.memberData.wandcore }}</h6>

                <p>{{ $t('member.enrollmentNo') }}</p>
                <h6 class="fw200">{{ memberStore.memberData.number }}</h6>

                <p>{{ $t('member.enrollmentDate') }}</p>
                <h6 class="fw200">{{ memberStore.memberData.date }}</h6>
            </div>
        </div>
        <img :src="`${publicPath}member/icon.png`"  alt="">
    </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
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




const isNameNull = computed(() => {

    const username = localStorage.getItem('user');
    if (!username) return true;
    const nameobj = JSON.parse(username);
    return !nameobj.name;

});

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
        padding-left: 16px;
        padding-right: 16px;
        justify-content: space-between;
        position: relative;
        &.hasscale{
            transform: scale(1.3);
        }
    }

    .shrimp-slot-box, .empty-slot {
        width: 110px;
        height: 110px;
        border: 2px dashed #B0B0B0;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(255,255,255,0.5);
    }

    .membercard-wrapper > img{
        height: 240px;
        width: 240px;
        object-fit: cover;
        position: absolute;
        left: 380px;
        top: 150px;
        z-index: 10;
    }

    // .memberphoto > img:hover{
    //     transform: scale(1.2);
    // }
    .membercard{
        width: 100%;
        height: 100%;
        // display: flex;
        // justify-content: space-between;
        display: grid;
        grid-template-columns: 1fr 1.1fr;
        gap: 24px;
        align-items: start;
    }

    .memberphoto{
        width: 100%;
        height: 100%;
        display: flex;
        position: relative;
        // justify-content: start;
        // top: 50%;  
        // left: 50%;
        // transform: translate(-50%, -50%);
        
    }
    .memberphoto > .fiximg{
        width: 160px;
        height: 160px;
        position: relative;
        top: 30%;  
        left: 50%;
        transform: translate(-50%, -30%);

    }
    .fiximg > img{
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        position: absolute;

    }

    .thefile{
        width: 100%;
        height: 100%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);  
        opacity: 0;

    }


    .mask {
        position: absolute;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        top: 30%;  
        left: 50%;
        transform: translate(-50%, -30%);
        background: rgba(0, 0, 0, 0.3);
        color: #fff;
        opacity: 0;
        transition: opacity 0.4s ease;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .memberphoto:hover .mask {
        opacity: 1;

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
        padding-bottom: 48px;
    }

    .memberinformation{
        align-self: center;
    }
</style>
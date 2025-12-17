<template>
    <div class="membercard-wrapper">
        <div class="membercard">
            <div v-for="img in memberphoto" class="memberphoto">
                <img :src="img.images" alt="1">
            </div>
            <div v-for="member in members" class="memberinformation">
                <p>Name: <button
                            class="edit-btn"
                            :class="{ 'without': withouteditbtn}"
                            >
                            <font-awesome-icon icon="fa-solid fa-pen-to-square" style="font-size: 20px;" />
                        </button></p> 
                <h6 class="fw200">{{ member.name }}</h6>
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
const publicPath = import.meta.env.BASE_URL;
const props = defineProps({
    withouteditbtn:{
        type: Boolean,
        default: false,
    }
});

const memberphoto = ref([
    {
        images: `${import.meta.env.BASE_URL}member/photo.png`,
    },
]);

const members = ref([
    {
        name: 'H.Potter',
        wandcore: 'Cornu Cervi Pantotrichum',
        number: 'L09190116',
        date: '2025.09.19',
    }
]);


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
        
    }

    .memberphoto > img{
        height: 100%;
        width: 100%;

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

    h6{

    }
</style>
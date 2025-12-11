<template>
    <div class="membercard-wrapper">
        <div class="membercard">
            <div v-for="img in memberphoto" class="memberphoto">
                <img :src="img.images" alt="1">
            </div>
            <div v-for="member in members" class="memberinformation">
                <h6>Name: <button
                            class="edit-btn"
                            >
                            <font-awesome-icon icon="fa-solid fa-pen-to-square" style="font-size: 20px;" />
                        </button></h6> 
                <p>{{ member.name }}</p>
                <h6>Wand Core:</h6>
                <p>{{ member.wandcore }}</p>
                <h6>Enrollment Number:</h6>
                <p>{{ member.number }}</p>
                <h6>Enrollment Since:</h6>
                <p>{{ member.date }}</p>
            </div>
        </div>
        <img src="../../../../public/member/icon.png" alt="">
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const memberphoto = ref([
    {
        images: '../../../../public/member/photo.png',
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
        align-items: center;
        display: grid;
        grid-template-columns: 0.2fr 1.2fr;
        gap: 36px;
    }

    .memberphoto{
        width: 140px;
        height: 160px;
        margin: 0 auto;
        
    }

    .memberphoto > img{
        height: 100%;
    }

    .edit-btn{
        background-color: transparent;
        border: 0;
        color: $color-fsContent;
        cursor: pointer;
    }
</style>
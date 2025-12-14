<template>
    
    <div class="cardpage-wrapper" :class="{ 'with-padding': haspadding}">
        <div class="cardcontent" :class="{ 'with-gap': hasgap}">
            <div class="cardpage-left">
                <h2 :class="{ 'fontcolor' : fontscolor}">House Point Ledger</h2>
                <div class="pcontenttop">
                    <p :class="{ 'fontcolor' : fontscolor}">How to Earn Points:</p>
                    <p :class="{ 'fontcolor' : fontscolor}">Points are awarded for completing magical experiences and passing challenges (quizzes, mini-games, and tests) across the site.</p>
                </div>
                <div class="pcontentbottom">
                    <p :class="{ 'fontcolor' : fontscolor}">Redemption:</p>
                    <p :class="{ 'fontcolor' : fontscolor}">Once you accumulate the required amount of points in your Ledger, you may visit the Redemption Counter to exchange your points for exclusive magical rewards and perks, such as limited-edition items or special features!</p>
                </div>
            </div>
            <div class="cardpage-right">
                <Pointcard></Pointcard>
                <h4 :class="{ 'fontcolor' : fontscolor}">Every action counts toward the House Cup!</h4>
            </div>
        </div>
        <div class="buttonarea">
        <BasicButton class="btn-gray-fill" @click="goback"><h6>Go Back Student Card</h6></BasicButton>
        <BasicButton class="btn-yellow-fill" @click="handleEmbark" :class="{ 'without': withoutbtn}"><h6>Embark on the Formosoul Adventure</h6></BasicButton>
        </div>
    </div>
</template>

<script setup>
import BasicButton from '@/components/BasicButton.vue';
import Membercard from '../information/membercard.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useAuthStore } from '@/stores/autoStore';
import Pointcard from '../information/pointcard.vue';
import { faLeaf } from '@fortawesome/free-solid-svg-icons';
import { inject } from 'vue';
import router from '@/router';
    const props = defineProps({
        withoutbtn:{
            type: Boolean,
            default: false,
        },
        hasgap:{
            type: Boolean,
            default: false,
        },
        haspadding:{
            type: Boolean,
            default: false,
        },
        fontscolor:{
            type:Boolean,
            default:false,
        },
    });
    const authStore = useAuthStore();
    const setmemberview = inject('setmemberview');
    function handleEmbark() {
        authStore.closeLoginModal();    
    };

    function goback(){
        setmemberview('membercard');
    };
</script>

<style lang="scss" scoped>
    
    .closebutton{
        width: 36px;
        height: 36px;
        cursor: pointer;
        position: absolute;
        top: 16px;
        right: 16px;
    }

    .cardpage-wrapper{
        padding: 32px 60px 32px 60px;

        display: flex;
        flex-direction: column;
        justify-content: center; 
        align-items: center; 
        border: 0;
        border-radius: 8px;
        position: relative;
        gap: 60px;

        &.with-padding{
            padding: 8px;
        }
    }

    .cardcontent{
        display: grid;
        grid-template-columns: 1fr 1fr;

        &.with-gap{
            gap: 36px;
        }
    }

    h2{
        color: #F0F7FF;
        &.fontcolor{
        color: $color-fsTitle;
        }
    }

    h4{
        color: #F0F7FF;
        text-align: center;
        white-space: nowrap;
        &.fontcolor{
        color: $color-fsTitle;
        }
    }

    p{
        color: #F0F7FF;
        // padding: 60px 60px 60px 0;
        &.fontcolor{
        color: $color-fsTitle;
        }
    }

    .cardpage-right{
        display: flex;
        flex-direction: column;
        gap: 32px;
    }
    .cardpage-left{
        display: flex;
        flex-direction: column;
        gap: 36px;
    }
    .pcontenttop{
        margin-bottom: 36px;
    }
    .btn-yellow-fill{
        // padding: 16px 40px;

        &.without{
            display: none;
        }
    }

    .buttonarea{
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
</style>
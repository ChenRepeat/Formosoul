<template>
    
    <div class="cardpage-wrapper" :class="{ 'with-padding': haspadding}">
        <div class="cardcontent" :class="{ 'with-gap': hasgap}">
            <div class="cardpage-left">
                <h2>House Point Ledger</h2>
                <div class="pcontenttop">
                    <p>How to Earn Points:</p>
                    <p>Points are awarded for completing magical experiences and passing challenges (quizzes, mini-games, and tests) across the site.</p>
                </div>
                <div class="pcontentbottom">
                    <p>Redemption:</p>
                    <p>Once you accumulate the required amount of points in your Ledger, you may visit the Redemption Counter to exchange your points for exclusive magical rewards and perks, such as limited-edition items or special features!</p>
                </div>
            </div>
            <div class="cardpage-right">
                <Pointcard></Pointcard>
                <h4>Every action counts toward the House Cup!</h4>
            </div>
        </div>
        <BasicButton class="btn-yellow-fill" @click="handleEmbark" :class="{ 'without': withoutbtn}">Embark on the Formosoul Adventure</BasicButton>
    </div>
</template>

<script setup>
import BasicButton from '@/components/BasicButton.vue';
import Membercard from '../information/membercard.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useAuthStore } from '@/stores/autoStore';
import Pointcard from '../information/pointcard.vue';
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
    });
    const authStore = useAuthStore();

    function handleEmbark() {
        authStore.closeLoginModal()        
    }
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
    }

    h4{
        color: #F0F7FF;
        text-align: center;
         white-space: nowrap;
    }

    p{
        color: #F0F7FF;
        // padding: 60px 60px 60px 0;
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
</style>
<template>

    <div class="cardpage-wrapper" :class="{ 'with-padding': haspadding}">
        <div class="cardcontent" :class="{ 'with-gap': hasgap}">
            <div class="cardpage-left">
                <h2>Student Enrollment Card</h2>
                <p>This card is your Proof of Identity within our magical community. It confirms your status as a registered member of the Formosoul Academy. You will need this identification to access restricted areas, view privileged content, and participate in official Academy events.</p>
            </div>
            <div class="cardpage-right">
                <Membercard></Membercard>
                <h4>Don't leave the Common Room without it!</h4>
            </div>
        </div>
        <BasicButton class="btn-yellow-fill" @click="gotoledger">Check Your Ledger</BasicButton>
    </div>
</template>

<script setup>
import BasicButton from '@/components/BasicButton.vue';
import Membercard from '../information/membercard.vue'; 
import { useAuthStore } from '@/stores/autoStore';
import { handleError, inject } from 'vue';
    const props = defineProps({
        hasgap:{
            type: Boolean,
            default: false,
        },

        haspadding:{
            type: Boolean,
            default: false,
        },
    });
    const setmemberview = inject('setmemberview');
    const authStore = useAuthStore();

    function gotoledger() {
        setmemberview('ledger');
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

        &.with-gap {
            gap: 30px;
        }
    }

    h2{
        color: $color-fsBlue50;
    }

    h4{
        color: $color-fsBlue50;
        text-align: center;
        white-space: nowrap;
    }

    p{
        color: $color-fsBlue50;
        padding: 60px 60px 60px 0;
    }

    .cardpage-right{
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .btn-yellow-fill{
        padding: 16px 40px;
    }
</style>
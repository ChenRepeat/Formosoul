<template>

    <div class="cardpage-wrapper" :class="{ 'with-padding': haspadding}">
        <div class="cardcontent" :class="{ 'with-gap': hasgap}">
            <div class="cardpage-left">
                <h3 :class="{ 'fontcolor' : fontscolor}">Student Enrollment Card</h3>
                <p :class="{ 'fontcolor' : fontscolor}">This card is your Proof of Identity within our magical community. It confirms your status as a registered member of the Formosoul Academy. You will need this identification to access restricted areas, view privileged content, and participate in official Academy events.</p>
            </div>
            <div class="cardpage-right">
                <Membercard></Membercard>
                <h5 class="fw200" :class="{ 'fontcolor' : fontscolor}">Don't leave the Common Room without it!</h5>
            </div>
        </div>
        <BasicButton class="btn-yellow-fill" @click="gotoledger"><h6>Check Your Ledger</h6></BasicButton>
    </div>
</template>

<script setup>
import BasicButton from '@/components/BasicButton.vue';
import Membercard from '../information/membercard.vue'; 
import { useAuthStore } from '@/stores/autoStore';
    const props = defineProps({
        hasgap:{
            type: Boolean,
            default: false,
        },

        haspadding:{
            type: Boolean,
            default: false,
        },
        fontscolor:{
            type: Boolean,
            default: false,
        },
    });

    const authStore = useAuthStore();

    function gotoledger() {
        authStore.setmemberView('ledger');
        if(!authStore.isLoginModalOpen){
            authStore.setinformationView('informationledger');
        }
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
        gap: 100px;

        &.with-padding{
            padding: 100px 0px 100px 0px;
        }
    }

    .cardcontent{
        display: grid;
        grid-template-columns: 1fr 1fr;

        &.with-gap {
            gap: 0px;
        }
    }

    h3{
        // text-wrap: nowrap;
        color: $color-fsBlue50;
        // margin-right: auto;
        &.fontcolor{
        color: $color-fsTitle;
        }
    }

    h5{
        color: $color-fsBlue50;
        text-align: center;
        text-wrap: nowrap;
        
        &.fontcolor{
        color: $color-fsTitle;
        }
    }

    p{
        color: $color-fsBlue50;
        padding: 40px 16px 60px 0;
        &.fontcolor{
        color: $color-fsTitle;
        }
    }

    .cardpage-right{
        display: flex;
        flex-direction: column;
        gap: 60px;
    }

    .btn-yellow-fill{
        // padding: 16px 40px;
    }
</style>
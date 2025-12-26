<template>

    <div class="cardpage-wrapper" :class="{ 'with-padding': haspadding}">
        <div class="cardcontent" :class="{ 'with-gap': hasgap}">
            <div class="cardpage-left">
                <h3 :class="{ 'fontcolor' : fontscolor}">Student Enrollment Card</h3>
                <p :class="{ 'fontcolor' : fontscolor}">This card is your Proof of Identity within our magical community. It confirms your status as a registered member of the Formosoul Academy. You will need this identification to access restricted areas, view privileged content, and participate in official Academy events.</p>
            </div>
            <div class="cardpage-right">
                <Membercard withouteditbtn></Membercard>
                <h5 class="fw200" :class="{ 'fontcolor' : fontscolor}">Don't leave the Common Room without it!</h5>
            </div>
        </div>        
        <div class="buttonarea" :class="{ 'hascenter': hascenter}">
            <BasicButton class="btn-gray-fill" @click="editcard" :class="{ 'withedit': withedit}"><h6>Edit your membercard</h6></BasicButton>
            <BasicButton class="btn-yellow-fill" @click="gotoledger"><h6>Check Your Ledger</h6></BasicButton>
        </div>
        
    </div>
</template>

<script setup>
import BasicButton from '@/components/BasicButton.vue';
import Membercard from '../information/membercard.vue'; 
import { useAuthStore } from '@/stores/autoStore';
import { useMemberStore } from '@/stores/member';
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
        withedit:{
            type:Boolean,
            default:false,   
        },
        hascenter:{
            type:Boolean,
            default:false,
        },
  
    });

    
    const memberStore = useMemberStore();

    const authStore = useAuthStore();

    function gotoledger() {
        memberStore.data_uptime();
        authStore.setmemberView('ledger');
        if(!authStore.isLoginModalOpen){
            authStore.setinformationView('informationledger');
        }
    }
    

    function editcard(){
        authStore.openLoginModal();
        authStore.setmemberView('cardcontain');
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
    .btn-gray-fill{
        &.withedit{
            display: none;
        }  
    }
    .buttonarea{
        width: 100%;
        display: flex;
        justify-content: space-between;
        &.hascenter{
            justify-content: center;    
        }
    }
</style>
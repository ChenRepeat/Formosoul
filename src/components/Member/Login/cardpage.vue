<template>
    <div class="closebutton" ><font-awesome-icon @click="authStore.closeLoginModal()" icon="fa-solid fa-xmark"  style="font-size: 32px;"/></div>
    <div class="cardpage-wrapper">

        <div class="cardcontent">
            <div class="cardpage-left">
                <h2>Student Enrollment Card</h2>
                <p>This card is your Proof of Identity within our magical community. It confirms your status as a registered member of the Formosoul Academy. You will need this identification to access restricted areas, view privileged content, and participate in official Academy events.</p>
            </div>
            <div class="cardpage-right">
                <Membercard></Membercard>
                <h6>Don't leave the Common Room without it!</h6>
            </div>
        </div>
        <BasicButton class="btn-yellow-fill">Check Your Ledger</BasicButton>
    </div>
</template>

<script setup>
import BasicButton from '@/components/BasicButton.vue';
import Membercard from '../information/membercard.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useAuthStore } from '@/stores/autoStore';
    const authStore = useAuthStore();

    
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
        padding: 40px;
        display: flex;
        justify-content: center; 
        align-items: center; 
        border: 0;
        border-radius: 8px;
        position: relative;
    }
</style>
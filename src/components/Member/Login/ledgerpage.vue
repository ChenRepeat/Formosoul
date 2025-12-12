<template>
    
    <div class="cardpage-wrapper">
        <div class="cardcontent">
            <div class="cardpage-left">
                <h2>Susses</h2>
                <p>This card is your Proof of Identity within our magical community. It confirms your status as a registered member of the Formosoul Academy. You will need this identification to access restricted areas, view privileged content, and participate in official Academy events.</p>
            </div>
            <div class="cardpage-right">
                <Membercard></Membercard>
                <h4>Don't leave the Common Room without it!</h4>
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
    const props = defineProps({
        withoutbtn:{
            type: Boolean,
            default: false,
        }
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
    }

    .cardcontent{
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    h2{
        color: #F0F7FF;
    }

    h4{
        color: #F0F7FF;
        text-align: center;
    }

    p{
        color: #F0F7FF;
        padding: 60px 60px 60px 0;
    }

    .cardpage-right{
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .btn-yellow-fill{
        padding: 16px 40px;

        &.without{
            display: none;
        }
    }
</style>
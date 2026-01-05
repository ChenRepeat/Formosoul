<template>
    
    <div class="cardpage-wrapper" :class="{ 'with-padding': haspadding}">
        <div class="cardcontent" :class="{ 'with-gap': hasgap}">
            <div class="cardpage-left">
                <h3 :class="{ 'fontcolor' : fontscolor}">{{$t('member.pointCardName')}}</h3>
                <div class="pcontenttop">
                    <p :class="{ 'fontcolor' : fontscolor}">{{$t('member.pointGetInfoTitle')}}</p>
                    <p :class="{ 'fontcolor' : fontscolor}">{{$t('member.pointGetInfoText')}}</p>
                </div>
                <div class="pcontentbottom">
                    <p :class="{ 'fontcolor' : fontscolor}">{{$t('member.howToUseInfoTitle')}}</p>
                    <p :class="{ 'fontcolor' : fontscolor}">{{$t('member.howToUseInfoText')}}</p>
                </div>
            </div>
            <div class="cardpage-right">
                <Pointcard />
                <h5 class="fw200" :class="{ 'fontcolor' : fontscolor}">{{$t('member.cardText')}}</h5>
            </div>
        </div>
        <div class="buttonarea" :class="{ 'hascenter': hascenter}">
            <BasicButton class="btn-gray-fill" @click="goback"><h6>{{$t('member.backToMemberCard')}}</h6></BasicButton>
            <BasicButton class="btn-yellow-fill" @click="handleEmbark" :class="{ 'without': withoutbtn}"><h6>{{$t('member.cardWarning')}}</h6></BasicButton>
        </div>
    </div>
</template>

<script setup>
import BasicButton from '@/components/BasicButton.vue';

import { useAuthStore } from '@/stores/autoStore';
import Pointcard from '../information/pointcard.vue';
import router from '@/router';
import { useMemberStore } from '@/stores/member';

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
        hascenter:{
            type:Boolean,
            default:false,
        },
    });
    const memberStore = useMemberStore();
    const authStore = useAuthStore();
    function handleEmbark() {
        authStore.closeLoginModal();
        memberStore.data_uptime();

    };

    function goback(){
        memberStore.data_uptime();
        authStore.closeLoginModal();    
        router.push({ name: 'Information' });
        if(!authStore.isLoginModalOpen){
            authStore.setinformationView('informationmembercard');
        }
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

        &.with-gap{
            gap: 36px;
        }
    }

    h3{
        color: #F0F7FF;
        margin-right: auto;
        margin-bottom: 20px;
        &.fontcolor{
        color: $color-fsTitle;
        }
    }

    h5{
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
        gap: 60px;
    }
    .cardpage-left{
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .btn-yellow-fill{

        &.without{
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
    @media screen and (max-width: 1300px) {
        .cardcontent{
            grid-template-columns: 1fr;
        }
    }
    @media screen and (max-height: 700px) {
        .cardcontent{
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
    }
</style>
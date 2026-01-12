<template>
    <div 
    class="bg-frostedGlass" :class="{ 'active': authStore.isLoginModalOpen, 'membercoreselect': ismembercoreselect}" 
    @click.self="authStore.closeLoginModal()"
    >
        <div class="Loginout">
        <div class="closebutton" ><font-awesome-icon @click="authStore.closeLoginModal()" icon="fa-solid fa-xmark"  style="font-size: 32px; color: #f0f7ff; filter: drop-shadow(0px 0px 2px black);"/></div>
        <TheCoreSelection @refresh-data="handleRefreshData" v-if="authStore.memberView == 'coreselection'"></TheCoreSelection>
        <loginpage v-else-if="authStore.memberView == 'login'"></loginpage>
        <cardpage @refresh-data="handleRefreshData" v-else-if="authStore.memberView == 'membercard'" withedit hascenter></cardpage>
        <ledgerpage v-else-if="authStore.memberView == 'ledger'"></ledgerpage>
        <Editcardpage v-else-if="authStore.memberView == 'cardcontain'"></Editcardpage>
        </div>
    </div>
</template>

<script setup>
    import { useAuthStore } from '@/stores/autoStore';
    import Loginpage from './Member/Login/loginpage.vue';
    import { computed, onMounted, onUnmounted, ref } from 'vue';
    import Cardpage from './Member/Login/cardpage.vue';
    import Ledgerpage from './Member/Login/ledgerpage.vue';
    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    import TheCoreSelection from './Home/TheCoreSelection.vue';
    import Membercard from './Member/information/membercard.vue';
    import Editcardpage from './Member/information/editcardpage.vue';
    import { useRoute } from 'vue-router';
import { useMemberStore } from '@/stores/member';
    const authStore = useAuthStore();
    const memberStore = useMemberStore();
    const route = useRoute();
    const ismembercoreselect = computed(() => 
    authStore.memberView === 'coreselection' && route.path.includes('/member/information')
    )
    const emit = defineEmits(['refresh-data']);
    const handleRefreshData = async () => {
        console.log('popup');
        await memberStore.loadMemberData();
        emit('refresh-data');
    };
    // function handleKeyDown( e ){
    //     if(e.key == 'Escape'){
    //         authStore.closeLoginModal();
    //     }
    // };

    // onMounted(() => {
    //     window.addEventListener('keydown', handleKeyDown);
    // });

    // onUnmounted(() => {
    //     window.removeEventListener('keydown', handleKeyDown);
    // });
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
    .bg-frostedGlass{
        width: 90%;
        height: 90%;
        position: fixed;
        inset: 0;


        margin: 0 auto;
        top: 50%;
        left: 50%;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.6s ease;
        z-index: 2000;
        transform: translate(-50%, -50%) scale(0.3)  ;

        border-radius: 0;
        // 這段可以看 authStore.isLoginModalOpen 的值 去決定是否要顯示
        &.active {  
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1)  ;
            border: 0;
            border-radius: 10px;

        }
    }
    // 這邊加背景色
    .membercoreselect{
        background-color: rgba(0,0,0,0.5);
    }
    .Loginout{
        overflow: auto;
        width: 100%;
        height: 100%;
        // 轉換成RGB 數值 再調 opacity
        // background-color: rgba(93, 93, 93, 0.5);
        // 在原本的 6 碼色碼後面直接加上 2 碼來代表透明度（範圍 00 到 FF）
        display: flex;
        justify-content: center; 
        align-items: center; 
        border: 0;
        border-radius: 8px;
        position: relative;
    }

    @media screen and (max-height: 750px) {
        .bg-frostedGlass{
            height: 95%;
        }
        
    }
    @media screen and (max-height: 650px) {
        .bg-frostedGlass{
            width: 95%;
            height: 100%;
        }
        
    }
</style>
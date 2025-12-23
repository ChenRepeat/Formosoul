<template>
    <!-- 處理版面會跑的問題 寫死高
      -->
        
            <div class="Logincontain">
                    <!-- <div class="closebutton" ><font-awesome-icon @click="authStore.closeLoginModal()" icon="fa-solid fa-xmark"  style="font-size: 32px; color: #f0f7ff;"/></div> -->

                    <div class="pagebutton">
                        <BasicButton 
                        :class="{ 'active-tab': authStore.loginView == 'enrollment'}"
                        @click="authStore.loginView = 'enrollment'"
                        class="btn-blue-fill"><h4>Enrollment</h4></BasicButton>

                        <BasicButton 
                        :class="{ 'active-tab': authStore.loginView == 'loginpage' || authStore.loginView === 'forgetpassword'}"
                        @click="authStore.loginView = 'loginpage'"
                        class="btn-blue-fill"><h4>Login</h4></BasicButton>
                    </div>
                    <div class="pagecontain">
                        <div class="pagetop">
                            <Logincontain v-if="authStore.loginView == 'loginpage'"></Logincontain>


                            <Enrollment v-else-if="authStore.loginView == 'enrollment'"></Enrollment>
                            <Forgetpassword v-else-if="authStore.loginView == 'forgetpassword'"></Forgetpassword>
                        </div>
                        <template v-if="!isotherlogin">
                            <div class="otherlogin">
                                <div class="otherlogin-title">
                                    <hr>
                                    <h6>Other</h6>
                                    <hr>
                                </div>
                                <div class="otherlogin-icon">
                                    <img :src="`${publicPath}member/googleicon.png`" alt="1">
                                    <img :src="`${publicPath}member/lineicon.png`" alt="2">
                                </div>
                            </div>
                        </template>
                    </div>
            </div>

</template>

<script setup>
    import { computed, provide, ref } from 'vue';
    import Logincontain from './logincontain.vue';
    import { useAuthStore } from '@/stores/autoStore';
    import BasicButton from '@/components/BasicButton.vue';
    import Enrollment from '../Login/Enrollment.vue';
    import Forgetpassword from './forgetpassword.vue';
    const publicPath = import.meta.env.BASE_URL;
    const authStore = useAuthStore();
    const sharedEmail = ref('');
    const setEnrollmentEmail = (email) => {
        sharedEmail.value = email;
    }


    provide('sharedEmail', sharedEmail);
    provide('setEnrollmentEmail', setEnrollmentEmail);



    const isotherlogin = computed(() => {
            return  authStore.loginView == 'forgetpassword';
    });
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

    h4{
        font-family: 'Ubuntu','Roboto', 'Noto Sans TC', sans-serif; 
    }
    .Logincontain{
        // display: flex;
        // background-color: $color-fsBlue50;
        border: 0;
        border-radius: 8px;
        // padding: 40px;
        width: 50%;
        height: 80vh;
        margin: 0 auto;       
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        animation: slideUp 0.3s ease;
        position: relative;

        @keyframes slideUp {
            from {
            opacity: 0;
            transform: translateY(30px);
            }
            to {
            opacity: 1;
            transform: translateY(0);
            }
        }
    }
    .pagebutton{
        display: flex;
        border-radius: 8px 8px 0 0;
        border: 0;

    }

    .pagecontain{
        background-color: $color-fsBlue50;
        border: 0;
        border-radius: 0 0 8px 8px;
        height: 90%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;  

    }
    .btn-blue-fill{
        width: 100%;
        border: 0;
        border-radius: 8px 8px 0 0;
        color: $color-fsPopup;
    }

    .btn-blue-fill.active-tab{
        background-color: $color-fsBlue50;
        color: $color-fsTitle;
    }

    .otherlogin{
        width: 100%;
        height: 112px;
        // position: absolute;
        // bottom: 20px;
        margin-bottom: 20px;
    }
    .otherlogin-title{
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 24px;
    }
    .otherlogin-title > hr{
        color: $color-fsCaption;
        width: 35%;
        height: 1px; 
    }

    .otherlogin-title > hr:first-child{
        margin-right: 16px;
    }

    .otherlogin-title > hr:last-child{
        margin-left: 16px;
    }

    .otherlogin-title > h6{
        color: $color-fsCaption;
    }
    .pagetop{
        margin-bottom: 24px;

    }
    .otherlogin-icon{
        display: flex;
        justify-content: center;
        gap: 40px;

    }
    .otherlogin-icon > img{
        height: 48px;
        width: 48px;
        cursor: pointer;

    }
</style>
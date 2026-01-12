<template>
    <div class="login-form">
        <div class="otherlogin">
            <div class="otherlogin-title">
                <hr>
                <h6>{{$t('loginPage.forgetPasswordBtn')}}</h6>
                <hr>
            </div>
        </div>
        <div class="form-group">
            <div class="login-bottom">
                <p>{{$t('loginPage.forgetPwd')}}</p>
            </div>
            <input 
            id="email"
            type="email"
            v-model="email"
            class="input-text"
            :placeholder="$t('loginPage.enrollmentEmail')"
            :disabled="isLoading"
            @keydown="handleKeyDown"
            />
        </div>
        <div v-if="errorMessage" class="error-message"><p>{{ errorMessage }}</p></div>
        <BasicButton
        class="btn-yellow-fill"
        @click="handleforgetpassword"
        :disabled="isLoading"
        >
            {{ isLoading ? $t('loginPage.loading') : $t('loginPage.forgetBtn')}}
        </BasicButton>
    </div>
</template>

<script setup>
    import BasicButton from '@/components/BasicButton.vue';
    import { useAuthStore } from '@/stores/autoStore';
    import { ref } from 'vue';
    import emailjs from '@emailjs/browser';

    const authStore = useAuthStore();

    const email = ref('');
    const isLoading = ref(false);
    const errorMessage = ref('');

    function forgetpasswordAPI(email) {
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/checkemail.php`;
        return fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email
            }),
            credentials: 'include'
        }).then( res => res.json());
        
    }

    async function handleforgetpassword() {
        if(!email.value){
            errorMessage.value = 'Please fill in fields';
            return;
        }

        if(!email.value.includes('@')){
            errorMessage.value = 'Please enter a valid email';
            return;
        }

        isLoading.value = true;
        errorMessage.value = '';

        try{
            const response = await forgetpasswordAPI(email.value);
            if(response.success){
                const decryptedOtp = atob(response.changeotp)
                // console.log(decryptedOtp);
                const templateParams = {
                    email: email.value,
                    changeotp_code: decryptedOtp,
                };
                await emailjs.send(
                    'service_3xw68ou',   // 替換為你的 Service ID
                    'template_wd3b6dg',  // 替換為你的 Template ID
                    templateParams, 
                    'M9dyTlBa0NmdjaERY'    // 替換為你的 Public Key
                )
                authStore.setloginView('loginchangepassword');
            }else{
                errorMessage.value = response.message;
            }
        }catch(error){
            errorMessage.value = error.message || 'Please enter a  email';
        }finally{
            isLoading.value = false;
        }
    };



    function handleKeyDown( e ) {
        if(e.key == 'Enter' && !isLoading.value){
                handleforgetpassword();
        }
        
    }
</script>

<style lang="scss" scoped>
    .login-form{
        width: 70%;
        height: 50%;
        margin: 0 auto;
        // padding-top: 80px;
        padding-bottom: 40px;

    }

    .form-group{
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        width: 100%;

    }
    .input-text{
        margin-bottom: 24px;
    }
    .btn-yellow-fill{
        width: 280px;
        display: flex;
        margin: 0 auto;
        justify-content: center;
        text-wrap: nowrap;
    }


    .otherlogin-title{
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 40px;
        text-wrap: nowrap;

    }
    .otherlogin-title > hr{
        color: $color-fsCaption;
        width: 40%;
        height: 1px; 
    }

    .otherlogin-title > hr:first-child{
        margin-right: 16px;
    }

    .otherlogin-title > hr:last-child{
        margin-left: 16px;
    }

    .otherlogin-title > h6{
        color: $color-fsContent;
        font-family: 'Roboto', 'Noto Sans TC', sans-serif; 
    }

    .login-bottom > p{
        margin-bottom: 16px;
        color: $color-fsCaption;
    }

    .error-message > p{
        color: $color-fsRed;
        text-align: center;
        margin-bottom: 24px;
    }
    @media screen and (min-width: 1400px) {
        .login-form{
            padding-top: 40px;
        }

    }
    @media screen and (max-width: 1366px) {
        .login-form{
            margin-top: 100px;
            margin-bottom: 100px;
        }
        
    }

    @media screen and (max-height: 750px) {
        .login-form{
            margin-top: 60px;
            margin-bottom: 60px;
        }
    }
</style>
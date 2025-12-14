<template>
    <div class="login-form">
        <div class="otherlogin">
            <div class="otherlogin-title">
                <hr>
                <h6>Forget Password</h6>
                <hr>
            </div>
        </div>
        <div class="form-group">
            <div class="login-bottom">
                <p>It seems your memory has been tampered with by an Obliviate Charm! 
    Don't worry, resetting your login incantation only takes a few simple steps. 
    Please enter your registered email address below, and we will dispatch a messenger owl with the link to reset your password.</p>
            </div>
            <input 
            id="email"
            type="email"
            v-model="email"
            class="input-text"
            placeholder="Enter Your Enrollment Email"
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
            {{ isLoading ? 'Loading...' : 'Send Password Reset Link'}}
        </BasicButton>
    </div>
</template>

<script setup>
    import BasicButton from '@/components/BasicButton.vue';
    import { useAuthStore } from '@/stores/autoStore';
    import { inject, ref } from 'vue';

    const setCurrentView = inject('setCurrentView');
    const authStore = useAuthStore();

    const email = ref('');
    const isLoading = ref(false);
    const errorMessage = ref('');

    async function forgetpasswordAPI(email) {
        await new Promise(resolve => setTimeout(resolve, 1500));   

        if(email == 'test@test.com'){
            return{
                token: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...',
                user: {
                    id:1,
                    name: '新註冊用戶',
                    email: 'test@test.com'
                },
                message: '成功',
            }
        }else{
            throw new Error('失敗');
        }
        
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
            authStore.setToken(response.token);
            authStore.setUser(response.user);
            setCurrentView('login');
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
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);   

    }

    .form-group{
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        width: 100%;
        gap: 24px;
        margin-bottom: 16px;

    }
    .input-text{
        margin-bottom: 36px;
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
        color: $color-fsCaption;
    }

    .error-message > p{
        color: $color-fsCaption;
        text-align: center;
        margin-bottom: 24px;
    }
</style>
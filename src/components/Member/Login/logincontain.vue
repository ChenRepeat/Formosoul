<template>
    <div class="login-form">
        <div class="form-group">

            <input 
            id="email"
            type="email"
            v-model="email"
            class="form-input"
            placeholder="E-mail"
            :disabled="isLoading"
            @keydown="handleKeyDown"
            />
        </div>
        <div class="form-group">
            <div class="password-input">
                <input 
                id="password"
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                class="form-input"
                placeholder="Password"
                :disabled="isLoading"
                @keydown="handleKeyDown"
                />
                <button
                type="button"
                class="toggle-password-btn"
                @click="togglePassword"
                :disabled="isLoading"                
                >
                <font-awesome-icon 
                :icon="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"
                style="font-size: 24px;"
                />
                    <!-- {{ showPassword ? '隱藏' : '顯示'}} -->
                </button>
            </div>
        </div>
        <div v-if="errorMessage" class="error-message"><h6>{{ errorMessage }}</h6></div>
        <div class="login-bottom">
            <p>* If your memory has been tampered with by a Memory Charm, click here:<a href="/forgot-password" class="bottom-link">ForgetPassword?</a></p>
        </div>
        <BasicButton
        class="btn-yellow-fill"
        @click="handleLogin"
        :disabled="isLoading"
        >
            {{ isLoading ? 'Loading...' : 'Enter the Academy'}}
        </BasicButton>


    </div>
</template>

<script setup >
import BasicButton from '@/components/BasicButton.vue';
import { useAuthStore } from '@/stores/autoStore';
import { ref } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const authStore = useAuthStore();

const email = ref('');
const password = ref('');
const isLoading = ref(false);
const errorMessage = ref('');
const showPassword = ref(false);

async function loginAPI(email, password) {
    // 測試 isLoading 的效果
    await new Promise(resolve => setTimeout(resolve, 1500));
    // 暫時模擬登入
    if (email === 'test@test.com' && password === 'As345678') {
        // 回傳 JSON
        return {
            token: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...',
            user: {
                id: 1,
                name: '測試人員',
                email: 'test@test.com'
            },
            message: '登入成功'
        };
    } else {
        throw new Error('帳號或密碼錯誤');
    }
    // const response = await fetch('/api/login', {
    //     method: 'POST',
    //     headers: {  'Content-Type': 'application/json'},
    //     body: JSON.stringify({email, password})
    // });
    // 防止
    // const text = await response.text();
    // console.log('後端回傳的內容', text);
    
    // 安全的轉換成JSON
    // let data = null;
    // try{
    //     data = text ? JSON.parse(text) : null;
    // }catch( e ){
    //     throw new Error('目前後端尚未回傳 JSON 格式資料');
    // }

    // if (!response.ok) {
    //     throw new Error(data?.message || 'Login failed');
    // }

    // return data;
    // 如果沒資料會炸開 先不用    
    // if(!response.ok){
    //     const errorData = await response.json();
    //     throw new Error(errorData.message || 'Login failed')
    // }

    // return response.json();
};

async function handleLogin() {
    if(!email.value || !password.value){
        errorMessage.value = 'Please enter your email or password'
        return
    }
    
    if (!email.value.includes('@')) {
        errorMessage.value = 'Please enter a valid email'
        return        
    }

    isLoading.value = true
    errorMessage.value = ''

    try {
        const response = await loginAPI(email.value, password.value);
        
        // 這邊就保存到 store
        authStore.setToken(response.token)
        authStore.setUser(response.user)
        authStore.closeLoginModal();
    } catch (error) {
        errorMessage.value = error.message || 'Login failed,Please tryagain'
    } finally{
        isLoading.value = false

    }
}

function handleKeyDown( e ) {
    if (e.key === 'Enter' && !isLoading.value) {
        handleLogin();        
    }   
}

function togglePassword() {
    showPassword.value = !showPassword.value;
}

</script>

<style lang="scss" scoped>
    .login-form{
        width: 70%;
        padding: 40px;
        height: 544px;
        position: relative;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%); 
        

    }

    .form-group{
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        width: 100%;

        margin-bottom: 16px;

    }
    .password-input{
        position: relative;

    }
    .form-input{
        height: 54px;
        width: 100%;
        padding: 8px;
        border: 2px solid $color-fsCaption;
        border-radius: 4px;
    }
    .form-input::placeholder{
        font-size: 1.6rem;
        font-family: 'Roboto', 'Noto Sans TC', sans-serif; 

    }
    .toggle-password-btn{
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translate(0, -50%);  
        background-color: transparent;
        border: 0;
        color: $color-fsContent;
        cursor: pointer;
    }
    h5{
        color: $color-fsTitle;
    }

    .btn-yellow-fill{
        width: 280px;
        display: flex;
        margin: 0 auto;
        padding-top: 16px;
        padding-bottom: 16px;
        justify-content: center;
        // margin-bottom: 24px;
    }
    .login-bottom{
        width: 100%;
        margin-bottom: 36px;

    }
    .login-bottom > p > a{
        text-decoration: none;
        color: $color-fsBlue900;

    }
    .login-bottom > p{
        text-align: start;
        color: $color-fsCaption;
    }

    .error-message > h6{
        color: $color-fsCaption;
        text-align: center;
        margin-bottom: 36px;
    }
</style>
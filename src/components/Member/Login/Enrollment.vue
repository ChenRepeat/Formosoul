<template>
    <div class="login-form">
        <div class="form-group">
            <input 
            id="email"
            type="email"
            v-model="email"
            class="input-text"
            placeholder="E-mail"
            :disabled="isLoading"
            @keydown="handleKeyDown"
            />
        </div>
        <div class="form-group">
            <div class="otp-input">
                <input 
                id="otp"
                type="text"
                v-model="otp"
                class="input-text"
                placeholder="One-Time Password (OTP)"
                :disabled="!otpSent"
                @keydown="handleKeyDown"
                />
                <BasicButton @click="startCountdown" :disabled="otpLoading" class="btn-blue-fill">
                <p v-if="otpLoading">
                        {{ timer > 0 ? `Resend in ${timer} seconds` : 'Sending...' }}
                </p>
                <p v-else>
                    Summon  the  Code
                </p>
                </BasicButton>
            </div>
        </div>
        <div class="form-group">
            <div class="password-input">
                <input 
                id="password"
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                class="input-text"
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
                :icon="showPassword ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"
                style="font-size: 24px;"
                />
                    <!-- {{ showPassword ? '隱藏' : '顯示'}} -->
                </button>
            </div>
        </div>
        <div v-if="errorMessage" class="error-message"><p>{{ errorMessage }}</p></div>
        <div class="login-bottom">
            <p>* Please enter a string that is 8 to 16 characters long and includes uppercase letters, lowercase letters, and numbers.</p>
        </div>
        <BasicButton
        class="btn-yellow-fill"
        @click="handleEnrollment"
        :disabled="isLoading"
        >
            {{ isLoading ? 'Loading...' : 'Accept Terms and Enroll'}}
        </BasicButton>


    </div>
</template>

<script setup >
import BasicButton from '@/components/BasicButton.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useAuthStore } from '@/stores/autoStore';
import { inject, ref } from 'vue';

const setCurrentView = inject('setCurrentView');
const setSharedEmail = inject('setEnrollmentEmail');
const authStore = useAuthStore();

const email = ref('');
const password = ref('');
const otp = ref('');
const isLoading = ref(false);
const otpLoading = ref(false);
const errorMessage = ref('');
const showPassword = ref(false);
const otpSent = ref(false);
const timer = ref(60);
let intervalId = null;


async function sendOTPAPI(emailValue) {
    await new Promise(resolve => setTimeout(resolve, 1000));

    if(emailValue && typeof emailValue === 'string' && emailValue.includes('@')){
        return{
            message: 'OTP sent successfully',
            expiresIn: 10  // 單位是秒
            };
        }else{
            throw new Error('Invalid email');
        }
}
async function enrollmentAPI(email, password, otp) {
    await new Promise(resolve => setTimeout(resolve, 1500));   
    
    if(email == 'test@test.com' && password == 'As345678' && otp == '123456'){
        return{
            token: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...',
            user: {
                id: 1,
                name: '新註冊用戶',
                email: 'test@test.com'
            },
            message: '註冊成功',
        }
    }else{
        throw new Error('註冊失敗');
    }
}


async function startCountdown() {
    if(!email.value){
        errorMessage.value = 'Please enter your email';
        return;
    }

    if(!email.value.includes('@')){
        errorMessage.value = 'Please enter a valid email';
        return;
    }


    otpLoading.value = true;
    errorMessage.value = '';

    try{
        await sendOTPAPI(email.value);
        otpSent.value = true;
        errorMessage.value = '測試otp 123456';
        timer.value = 60;

        intervalId = setInterval(() => {
            timer.value--;

            if(timer.value <= 0){
                clearInterval(intervalId); // 清除計時器
                otpLoading.value = false; // 解除按鈕
                timer.value = 0;
            }
        }, 1000)
    }catch(error){
        errorMessage.value = error.message || 'Failed to send OTP';
        otpLoading.value = false;
    }
    // 要有倒數計時就不能用用統一個 finally 因為 finally 一定會執行 
    // finally{
    //     otpLoading.value = false;
    // }
};

async function handleEnrollment() {
    if(!email.value || !password.value || !otp.value){
        errorMessage.value = 'Please fill in all fields';
        return;
    }

    if (!email.value.includes('@')) {
        errorMessage.value = 'Please enter a valid email';
        return;
    }

    const hasUppercase = /[A-Z]/.test(password.value);
    const hasLowercase = /[a-z]/.test(password.value);
    const hasNumber = /[0-9]/.test(password.value);

    if (password.value.length < 8) {
        errorMessage.value = 'Password must be at least 8 characters';
        return;
    }else if(!hasUppercase || !hasLowercase || !hasNumber){
        errorMessage.value = 'Passwords must contain uppercase letters, lowercase letters, and numbers.';
        return;
    }



    if (!otpSent.value) {
        errorMessage.value = 'Please request OTP first';
        return;
    }

    isLoading.value = true;
    errorMessage.value = '';

    try{
        const response = await enrollmentAPI(email.value, password.value, otp.value);
        authStore.setToken(response.token);
        authStore.setUser(response.user);
        // 這邊寫成跳到登入頁面 成功才會執行
        setCurrentView('login');
        setSharedEmail(email.value);
    }catch(error){
        errorMessage.value = error.message || 'Enrollment failed, please try again';
    }finally {
        isLoading.value = false;
    }
}

function handleKeyDown(e) {
    if (e.key === 'Enter' && !isLoading.value) {
        handleEnrollment();
    }
}

function togglePassword() {
    showPassword.value = !showPassword.value;
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
    // .otp-input >.input-text{
    //     width: 70%;
    // }
    .password-input{
        position: relative;
    }

    .otp-input{
        position: relative;
        display: grid;
        grid-template-columns: 0.65fr 0.3fr;
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

    .btn-blue-fill{
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        padding: 4px 8px;
    }

    h5{
        color: $color-fsTitle;
    }

    .btn-yellow-fill{
        width: 280px;
        display: flex;
        margin: 0 auto;
        // padding-top: 16px;
        // padding-bottom: 16px;
        justify-content: center;
        // margin-bottom: 36px;
    }
    .login-bottom{
        width: 100%;
        margin-bottom: 36px;

    }
    .login-bottom > p > a{
        text-decoration: none;

    }
    .login-bottom > p{
        text-align: start;
        color: $color-fsCaption;
    }

    .error-message > p{
        color: $color-fsRed;
        margin-bottom: 24px;
    }
</style>
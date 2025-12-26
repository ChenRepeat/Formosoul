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
                <BasicButton @click="startCountdown" :class="btngray ? 'btn-gray-fill' : 'btn-blue-fill'"   :disabled="otpLoading">
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
        <div class="form-group">
            <div class="password-input">
                <input 
                id="confirmpassword"
                v-model="confirmpassword"
                :type="showconfirmPassword ? 'text' : 'password'"
                class="input-text"
                placeholder="Confirm password"
                :disabled="isLoading"
                @keydown="handleKeyDown"
                />
                <button
                type="button"
                class="toggle-password-btn"
                @click="toggleconfirmPassword"
                :disabled="isLoading"                
                >
                <font-awesome-icon 
                :icon="showconfirmPassword ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"
                style="font-size: 24px;"
                />
                </button>
            </div>
        </div>
        <div v-if="errorMessage" class="error-message"><p>{{ errorMessage }}</p></div>
        <div class="login-bottom">
            <p>* Please enter a string that is 8 to 16 characters long and includes uppercase letters, lowercase letters, and numbers.</p>
        </div>
        <div class="checkpolicy dp-flex">
            <input type="checkbox" v-model="checkbox" id="checkbox">
            <div class="agreetext dp-flex"><p>I have read and agree to the Terms of </p>
                <p @click="gotoService" class="Service">Service</p><p>and</p>
                <p @click="gotoPrivacy" class="Privacy">Privacy Policy</p>
                <p>.</p></div>
        </div>
        <BasicButton
        :class="btnclose ?  'btn-close' : 'btn-yellow-fill'"
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
import { inject, ref, watch } from 'vue';
import router from '@/router';
import emailjs from '@emailjs/browser';


const setSharedEmail = inject('setEnrollmentEmail');
const authStore = useAuthStore();

const email = ref('');
const password = ref('');
const otp = ref('');
const confirmpassword = ref('');
const checkbox = ref(false);
const isLoading = ref(false);
const otpLoading = ref(false);
const errorMessage = ref('');
const showPassword = ref(false);
const showconfirmPassword = ref(false);
const otpSent = ref(false);
const timer = ref(60);
const btngray = ref(false);
const btnclose = ref(true);
const otpnumber = ref('');
let intervalId = null;

watch(
  [() => email.value, () => otp.value, () => password.value, () => confirmpassword.value, () => checkbox.value],
  ([newemail, newotp, newpassword, confirmpassword, newcheckbox]) => {
    if (!newemail  || !newotp || !newpassword || !confirmpassword || newcheckbox == false) {
      btnclose.value = true;
    }else{
      btnclose.value = false;
    }
  }
);

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


function enrollmentAPI(email, password, otp) {
    const apiBase = import.meta.env.VITE_API_BASE;
    const API_URL = `${apiBase}/Enrollment.php`;

    return fetch(API_URL, {
        method: 'POST',
        headers:{
            'Content-Type': 'application/json'
        },
        credentials: 'include',
        body: JSON.stringify({
            email, password, otp
        })
    }).then( res => res.json());

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

    const code = generateotp();
    otpLoading.value = true;
    errorMessage.value = '';
    btngray.value = true;

    try{
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/save_otp.php`;
        await fetch( API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email.value, otp: code}),
            credentials: 'include' // 這行是確保 Session Cookie 能運作
        });
        // emailJS API
        const templateParams = {
            email: email.value,
            otp_code: code,
        };

        await emailjs.send(
            'service_3xw68ou',   // 替換為你的 Service ID
            'template_1tux8ni',  // 替換為你的 Template ID
            templateParams, 
            'M9dyTlBa0NmdjaERY'    // 替換為你的 Public Key
        )

        await sendOTPAPI(email.value);
        otpSent.value = true;
        // errorMessage.value = `測試otp ${otpnumber.value}`;
        errorMessage.value = 'Verification code sent!';
        timer.value = 60;
        intervalId = setInterval(() => {
            timer.value--;
            if(timer.value <= 0){
                clearInterval(intervalId); // 清除計時器
                otpLoading.value = false; // 解除按鈕
                timer.value = 0;
                btngray.value = false;
            }
        }, 1000)
    }catch(error){
        errorMessage.value = error.message || 'Failed to send OTP';
        otpLoading.value = false;
    }
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


    if(!confirmpassword.value){
        errorMessage.value = 'Please enter your new password again.';
        return;

    }

    if(confirmpassword.value != password.value){
        errorMessage.value = 'Must be the same as the new password.';
        return;
    }

    if (!otpSent.value) {
        errorMessage.value = 'Please request OTP first.';
        return;
    }
    if (!checkbox.value) {
        errorMessage.value = 'Please agree to the Terms of Service and Privacy Policy.';
        return;
    }
    
    isLoading.value = true;
    errorMessage.value = '';

    try{
        const response = await enrollmentAPI(email.value, password.value, otp.value);
        // 這邊寫成跳到登入頁面 成功才會執行
        if(response.success){
            authStore.setloginView('loginpage');
            setSharedEmail(email.value);
        }else{
            errorMessage.value = response.message;
        }
    }catch(error){
        errorMessage.value = error.message || 'Enrollment failed, please try again';
    }finally {
        isLoading.value = false;
    }
}


function generateotp(){
    let otp = ""
    for (let i = 0; i < 6; i++) {
        otp += Math.floor(Math.random() * 10)
    }
    otpnumber.value = otp
    return otpnumber.value
}

function handleKeyDown(e) {
    if (e.key === 'Enter' && !isLoading.value) {
        handleEnrollment();
    }
}

function togglePassword() {
    showPassword.value = !showPassword.value;
};

function toggleconfirmPassword(){
    showconfirmPassword.value = !showconfirmPassword.value;
}

const gotoPrivacy = () => { 

    router.push({ name: 'Privacypolicy' });

    authStore.isLoginModalOpen = false;
};

const gotoService = () => {
    router.push({ name: 'Returns' });
    authStore.isLoginModalOpen = false;
};
</script>

<style lang="scss" scoped>
    .login-form{
        width: 70%;
        height: 50%;
        margin: 0 auto;
        padding-top: 80px;
        
        // position: absolute;
        // left: 50%;
        // top: 50%;
        // transform: translate(-50%, -50%); 
    }

    .form-group{
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        width: 100%;
        gap: 24px;
        margin-bottom: 16px;

    }
    
    .password-input{
        position: relative;
    }

    .otp-input{
        position: relative;

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
        right: 8px;
        top: 50%;
        transform: translate(0, -50%);
        padding: 4px 8px;
    }

    .btn-blue-fill > p{
        font-size: 14px;
    }
    h5{
        color: $color-fsTitle;
    }

    .btn-yellow-fill{

        display: flex;
        margin: 0 auto;
        justify-content: center;

    }

    .btn-close{
        background-color: $color-fsContent;
        color: $color-fsWhite;
        display: flex;
        margin: 0 auto;
        justify-content: center;    
        pointer-events: none;

    }

    .btn-gray-fill{
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translate(0, -50%);
        padding: 4px 8px;
        pointer-events: none;

    }
    .btn-gray-fill > p{
        font-size: 14px;
    }
    .login-bottom{
        width: 100%;
        margin-bottom: 16px;

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
    }
    .checkpolicy{
        margin-bottom: 36px;
    }
    .agreetext{
        padding-top: 4px;
        padding-left: 8px;
        gap: 4px;
    }
    .agreetext > p{
        font-size: 1.2rem;

    }
    .Service,
    .Privacy{
        cursor: pointer;
        color: $color-fsBlue;
        font-size: 1.2rem;
    }
    .checkpolicy > input[type="checkbox"] {
        transform: scale(1.3);
    }

    .login-bottom> p{
        font-size: 1.2rem;
    }
</style>
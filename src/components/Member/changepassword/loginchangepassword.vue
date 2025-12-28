<template>
    <div class="changepassword">
        <h3>Change password</h3>
        <div class="contain">

            <label><h6>OTP：</h6></label>
            <input
            class="input-text"
            v-model="changeotp"
            type="text" 
            name="changeotp" 
            id="changeotp"
            @input="validatechangeotp"
            placeholder="One-Time Password (OTP)"/>

            <label for="Newpassword"><h6>New password：</h6></label>
            <input 
            v-model="Newpassword"
            type="text"
            name="Newpassword" 
            class="input-text"
            id="Newpassword"
            @input="validateNewPassword"
            placeholder="New password"/>
            
            
            <label for="confirmpassword"><h6>Confirm new password：</h6></label>
            <input 
            class="input-text"
            v-model="confirmpassword"
            type="text" 
            name="confirmpassword" 
            id="confirmpassword"
            @input="validateConfirmPassword"
            placeholder="Confirm new password"/>
            
            <div v-if="errorMessage" class="error-message"><p>{{ errorMessage }}</p></div>
            <p class="text">* Please enter a string that is 8 to 16 characters long and includes uppercase letters, lowercase letters, and numbers.</p>

            <BasicButton class="btn-yellow-fill" id="changepassword" @click="handleChangePassword" :disabled="isLoading">{{ isLoading ? 'Loading...' : 'Save Change'}}</BasicButton>
        </div>  
    </div>
</template>

<script setup>
import BasicButton from '@/components/BasicButton.vue';
import { useAuthStore } from '@/stores/autoStore';
import { ref } from 'vue';

const changeotp = ref('');
const Newpassword = ref('');
const confirmpassword = ref('');
const errorMessage = ref('');
const isLoading = ref(false);
const authStore = useAuthStore();

const validatechangeotp = () => {
    errorMessage.value = '';
    if(!changeotp){
        return;
    }
    const hasOnlyNumbers = /^\d+$/.test(changeotp.value);

    if(changeotp.value.length > 6 && hasOnlyNumbers){
        errorMessage.value = 'The changeotp consists of 6 numbers.';
        return;
    }

    if(!hasOnlyNumbers){
        errorMessage.value = 'The changeotp contains only numbers.';
        return;
    }


}
//  驗證新密碼格式
const validateNewPassword  = () => {
    errorMessage.value = '';
    const password = Newpassword.value;

    if(!password){
        return;
    }


    const hasUppercase = /[A-Z]/.test(Newpassword.value);
    const hasLowercase = /[a-z]/.test(Newpassword.value);
    const hasNumber = /[0-9]/.test(Newpassword.value);

    if (password.length < 8) {
        errorMessage.value = 'Password must be at least 8 characters';
        return;
    }else if(!hasUppercase || !hasLowercase || !hasNumber){
        errorMessage.value = 'Passwords must contain uppercase letters, lowercase letters, and numbers.';
        return;
    }

    // 當輸入確認密碼時才檢查
    if(confirmpassword.value && password !== confirmpassword.value){
        errorMessage.value = 'Must be the same as the new password.';
    }else{
        errorMessage.value = '';
    }
};

// 驗證確認密碼

const validateConfirmPassword = () => {
    errorMessage.value = '';

    if(!confirmpassword.value){
        errorMessage.value = 'Please enter your new password again.';
        return;
    }

    if (Newpassword.value !== confirmpassword.value) {
        errorMessage.value = 'New password must be different from old password.';
    }

};

// 確認鍵

const handleChangePassword  = async() => {

    errorMessage.value = '';

    if(!changeotp.value){
        errorMessage.value = 'Please enter your old password.'
    }

    if(!Newpassword.value){
        errorMessage.value = 'Please enter your New password.';
    }else{
        const hasUppercase = /[A-Z]/.test(Newpassword.value);
        const hasLowercase = /[a-z]/.test(Newpassword.value);
        const hasNumber = /[0-9]/.test(Newpassword.value);
        
        if(Newpassword.value.length < 8){
            errorMessage.value = 'Passwords must be at least 8 characters long';
        }else if(!hasUppercase || !hasLowercase || !hasNumber){
            errorMessage.value = 'Passwords must contain uppercase letters, lowercase letters, and numbers.';
        }else{
            errorMessage.value = '';
        }
    }

    if(!confirmpassword.value){
        errorMessage.value = 'Please enter your new password again.';
    }else if(Newpassword.value !== confirmpassword.value){
        errorMessage.value = 'Must be the same as the new password.';
    }

    if(errorMessage.value){
        errorMessage.value = 'Please confirm all fields.';
        return;
    }

    try{
        // isLoading.value = true;
        const response = await changeAPI(
            changeotp.value,
            Newpassword.value
        );

        if(response.success){
            authStore.setloginView('loginpage');
        }

    errorMessage.value = response.message;
    }catch(error){
        errorMessage.value = error.message || 'changepassword failed, please try again';
    }


    // 補 AJAX 呼叫後端
    // TODO: 這裡放 axios / fetch 請求
    // try { await api.changePassword(...) } catch...
};

function changeAPI(changeotp, Newpassword){
    const apiBase = import.meta.env.VITE_API_BASE;
    const API_URL = `${apiBase}/loginchange.php`;
    return fetch(API_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            changeotp,
            Newpassword
        }),
        credentials: 'include'
    }).then( res => res.json());
};








</script>

<style lang="scss" scoped>
    .changepassword{
        // width: 1200px;
        max-width: 1200px;
        margin: 0 auto;
    }

    h3{
        text-align: center;
        margin: 36px 0 16px 0;
        color: $color-fsTitle;
    }
    .contain{
        width: 50%;
        display: flex;
        flex-direction: column;
        // gap: 12px;
        margin: 0 auto;
        padding-bottom: 100px;
    }

    .input-text{
        margin-bottom: 24px;

    }
    h6{
        color: $color-fsTitle;
    }

    .text{
        color: $color-fsCaption;
        margin-bottom: 40px;


    }


    .error-message > p{
        color: $color-fsRed;

        
    }
</style>
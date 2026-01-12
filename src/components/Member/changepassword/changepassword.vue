<template>
    <div class="changepassword">
        <h3>{{$t('member.PWChange')}}</h3>
        <div class="contain">
            <label for="oldpassword" ><h6>{{$t('member.oldPW')}}</h6></label>
            <input
            class="input-text"
            v-model="oldpassword"
            type="text" 
            name="oldpassword" 
            id="oldpassword"
            :placeholder="$t('member.placeholderOldPW')"/>

            <label for="Newpassword"><h6>{{$t('member.newPW')}}</h6></label>
            <input 
            v-model="Newpassword"
            type="text"
            name="Newpassword" 
            class="input-text"
            id="Newpassword"
            @input="validateNewPassword"
            :placeholder="$t('member.placeholderNewPW')"/>
            
            
            <label for="confirmpassword"><h6>{{$t('member.newPWConf')}}</h6></label>
            <input 
            class="input-text"
            v-model="confirmpassword"
            type="text" 
            name="confirmpassword" 
            id="confirmpassword"
            @input="validateConfirmPassword"
            :placeholder="$t('member.placeholderNewPWConf')"/>
            
            <div v-if="errorMessage" class="error-message"><p>{{ errorMessage }}</p></div>
            <p class="text">{{$t('member.PWWarning')}}</p>

            <BasicButton class="btn-yellow-fill" id="changepassword" @click="handleChangePassword" :disabled="isLoading">{{ isLoading ? $t('loginPage.loading') : $t('loginPage.saveBtnSingular')}}</BasicButton>
        </div>  
    </div>
</template>

<script setup>
import BasicButton from '@/components/BasicButton.vue';
import { ref } from 'vue';

const oldpassword = ref('');
const Newpassword = ref('');
const confirmpassword = ref('');
const errorMessage = ref('');
const isLoading = ref(false);
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
    // 只有當舊密碼有輸入，且新密碼等於舊密碼，才顯示錯誤 避免在用戶還沒輸入舊密碼時就顯示錯誤訊息 因為空字串是false
    if(password === oldpassword.value && oldpassword.value){
        errorMessage.value = 'New password must not be the same as the old password.';
        return;
    }
    // 當輸入確認密碼時才檢查
    if(confirmpassword.value && password !== confirmpassword.value){
        errorMessage.value = 'New password cannot be the same as old password.';
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
        errorMessage.value = 'Must be the same as the new password.';
    }

};

// 確認鍵

const handleChangePassword  = async() => {

    errorMessage.value = '';





    if(!oldpassword.value){
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
        }else if(Newpassword.value === oldpassword.value){
            errorMessage.value = 'New password cannot be the same as old password.';

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
        isLoading.value = true;
        const response = await changeAPI(
            oldpassword.value,
            Newpassword.value
        );

    errorMessage.value = response.message;
    }catch(error){
        errorMessage.value = error.message || 'changepassword failed, please try again';
    }finally{
        isLoading.value = false;

    }


    // 補 AJAX 呼叫後端
    // TODO: 這裡放 axios / fetch 請求
    // try { await api.changePassword(...) } catch...
};

function changeAPI(oldpassword, Newpassword){
    const apiBase = import.meta.env.VITE_API_BASE;
    const API_URL = `${apiBase}/changepassword.php`;
    const storedUser = localStorage.getItem('user');
    const { member_ID } = JSON.parse(storedUser);
    return fetch(API_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            member_ID,
            oldpassword,
            Newpassword
        })
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
        margin: 100px 0 100px 0;
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
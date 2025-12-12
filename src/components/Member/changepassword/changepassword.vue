<template>
    <div class="changepassword">
        <h2>Change password</h2>
        <div class="contain">
            <label for="oldpassword"><h5>Current password:</h5></label>
            <input
            class="form-input"
            v-model="oldpassword"
            type="text" 
            name="oldpassword" 
            id="oldpassword"
            placeholder="請輸入舊密碼"/>
            
            <label for="Newpassword"><h5>New password:</h5></label>
            <input 
            v-model="Newpassword"
            type="text"
            name="Newpassword" 
            class="form-input"
            id="Newpassword"
            @input="validateNewPassword"
            placeholder="請輸入新密碼"/>
            

            <label for="confirmpassword"><h5>Confirm new password:</h5></label>
            <input 
            class="form-input"
            v-model="confirmpassword"
            type="text" 
            name="confirmpassword" 
            id="confirmpassword"
            @input="validateConfirmPassword"
            placeholder="請再次輸入新密碼"/>
            
            <div v-if="errorMessage" class="error-message"><p>{{ errorMessage }}</p></div>

            <h6>* Password must contain at least 8 characters including uppercase and lowercase English letters and numbers.</h6>
            <BasicButton class="btn-yellow-fill" id="changepassword" @click="handleChangePassword">Save changes</BasicButton>
        </div>  
    </div>
</template>

<script setup>
import BasicButton from '@/components/BasicButton.vue';
import { computed, ref } from 'vue';

const oldpassword = ref('');
const Newpassword = ref('');
const confirmpassword = ref('');
const errorMessage = ref('');

//  驗證新密碼格式
const validateNewPassword  = () => {
    errorMessage.value = '';
    const password = Newpassword.value;

    if(!password){
        return;
    }

    if(password.length < 8){
        errorMessage.value = '密碼至少需要8個字元';
        return;
    }

    const hasUppercase = /[A-Z]/.test(Newpassword.value);
    const hasLowercase = /[a-z]/.test(Newpassword.value);
    const hasNumber = /[0-9]/.test(Newpassword.value);

    if(!hasUppercase){
        errorMessage.value = '密碼必須包含大寫字母(A-Z)';
        return;
    }
    if(!hasLowercase){
        errorMessage.value = '密碼必須包含小寫字母(a-z)';
        return;
    }
    if(!hasNumber){
        errorMessage.value = '密碼必須包含數字(0-9)';
        return;
    }
    // 只有當舊密碼有輸入，且新密碼等於舊密碼，才顯示錯誤 避免在用戶還沒輸入舊密碼時就顯示錯誤訊息 因為空字串是false
    if(password === oldpassword.value && oldpassword.value){
        errorMessage.value = '新密碼不得與舊密碼相同';
        return;
    }
    // 當輸入確認密碼時才檢查
    if(confirmpassword.value && password !== confirmpassword.value){
        errorMessage.value = '必須與新密碼相同';
    }else{
        errorMessage.value = '';
    }
};

// 驗證確認密碼

const validateConfirmPassword = () => {
    errorMessage.value = '';

    if(!confirmpassword.value){
        errorMessage.value = '請再輸入一次新密碼';
        return;
    }

    if (Newpassword.value !== confirmpassword.value) {
        errorMessage.value = '必須與新密碼相同';
    }

};

// 確認鍵

const handleChangePassword  = () => {
    // 重製所有錯誤
    errorMessage.value = '';


    // 這邊要再重新檢查一遍

    // 之後要補對於舊密碼的認證
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
            errorMessage.value = '密碼必須包含大寫、小寫字母和數字';
        }else if(Newpassword.value === oldpassword.value){
            errorMessage.value = '新密碼不得與舊密碼相同';

        }
    }

    if(!confirmpassword.value){
        errorMessage.value = '請再輸入一次新密碼';
    }else if(Newpassword.value !== confirmpassword.value){
        errorMessage.value = '必須與新密碼相同';
    }

    if(errorMessage.value){
        errorMessage.value = '請確認所有欄位';
        return;
    }

    console.log('驗證通過！');

    // 補 AJAX 呼叫後端
    // TODO: 這裡放 axios / fetch 請求
    // try { await api.changePassword(...) } catch...
};




</script>

<style lang="scss" scoped>
    .changepassword{
        // width: 1200px;
        max-width: 1200px;
        margin: 0 auto;
    }

    h2{
        text-align: center;
        margin: 100px 0 100px 0;
        color: $color-fsTitle;
    }
    .contain{
        width: 50%;
        display: flex;
        flex-direction: column;
        gap: 24px;
        margin: 0 auto;
        padding-bottom: 48px;
    }

    .form-input{
        height: 54px;
        width: 100%;
        padding: 8px;
        border: 2px solid $color-fsCaption;
        border-radius: 4px;
    }
    h5{
        color: $color-fsTitle;
    }

    h6{
        color: $color-fsCaption;
        text-align: center;
    }

    p{
        color: $color-fsCaption;

    }

    .error-message > p{
        color: $color-fsCaption;
        text-align: center;
        margin-bottom: 24px;
    }
</style>
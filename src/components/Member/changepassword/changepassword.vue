<template>
    <div class="changepassword">
        <h2>Change password</h2>
        <div class="contain">
            <label for="oldpassword"><h5>Current password:</h5></label>
            <input
            v-model="oldpassword"
            type="text" 
            name="oldpassword" 
            id="oldpassword"
            placeholder="請輸入舊密碼"/>
            <span v-if="errors.oldpassword" class="error-text">{{ errors.oldpassword }}</span>
            <label for="Newpassword"><h5>New password:</h5></label>
            <input 
            v-model="Newpassword"
            type="text"
            name="Newpassword" 

            id="Newpassword"
            @input="validateNewPassword"
            placeholder="請輸入新密碼"/>
            <span v-if="errors.Newpassword" class="error-text"><p>{{ errors.Newpassword }}</p></span>

            <label for="confirmpassword"><h5>Confirm new password:</h5></label>
            <input 
            v-model="confirmpassword"
            type="text" 
            name="confirmpassword" 
            id="confirmpassword"
            @input="validateConfirmPassword"
            placeholder="請再次輸入新密碼"/>
            <span v-if="errors.confirmpassword" class="error-text"><p>{{ errors.confirmpassword }}</p></span>

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

const errors = ref({
    oldpassword: '',
    Newpassword: '',
    confirmpassword: '',
});
//  驗證新密碼格式
const validateNewPassword  = () => {
    const password = Newpassword.value;
    errors.value.Newpassword = '';

    if(!password){
        errors.value.Newpassword = '';
        return;
    }

    if(password.length < 8){
        errors.value.Newpassword = '密碼至少需要8個字元';
        return;
    }

    const hasUppercase = /[A-Z]/.test(Newpassword.value);
    const hasLowercase = /[a-z]/.test(Newpassword.value);
    const hasNumber = /[0-9]/.test(Newpassword.value);

    if(!hasUppercase){
        errors.value.Newpassword = '密碼必須包含大寫字母(A-Z)';
        return;
    }
    if(!hasLowercase){
        errors.value.Newpassword = '密碼必須包含大寫字母(a-z)';
        return;
    }
    if(!hasNumber){
        errors.value.Newpassword = '密碼必須包含大寫字母(0-9)';
        return;
    }
    // 只有當舊密碼有輸入，且新密碼等於舊密碼，才顯示錯誤 避免在用戶還沒輸入舊密碼時就顯示錯誤訊息 因為空字串是false
    if(password === oldpassword.value && oldpassword.value){
        errors.value.Newpassword = '新密碼不得與舊密碼相同';
        return;
    }
    // 當輸入確認密碼時才檢查
    if(confirmpassword.value && password !== confirmpassword.value){
        errors.value.confirmpassword = '必須與新密碼相同';
    }else{
        errors.value.confirmpassword = '';
    }
};

// 驗證確認密碼

const validateConfirmPassword = () => {
    errors.value.confirmpassword = '';

    if(!confirmpassword.value){
        errors.value.confirmpassword = '請再輸入一次新密碼';
        return;
    }

    if (Newpassword.value !== confirmpassword.value) {
        errors.value.confirmpassword = '必須與新密碼相同';
    }

};

// 確認鍵

const handleChangePassword  = () => {
    // 重製所有錯誤
    errors.value= {
        oldpassword: '',
        Newpassword: '',
        confirmpassword:'',
    };


    // 這邊要再重新檢查一遍

    // 之後要補對於舊密碼的認證
    if(!oldpassword.value){
        errors.value.oldpassword = '請輸入舊密碼'
    }

    if(!Newpassword.value){
        errors.value.Newpassword = '請輸入新密碼';
    }else{
        const hasUppercase = /[A-Z]/.test(Newpassword.value);
        const hasLowercase = /[a-z]/.test(Newpassword.value);
        const hasNumber = /[0-9]/.test(Newpassword.value);
        
        if(Newpassword.value.length < 8){
            errors.value.length = '密碼至少需要8個字元';
        }else if(!hasUppercase || !hasLowercase || !hasNumber){
            errors.value.Newpassword = '密碼必須包含大寫、小寫字母和數字';
        }else if(Newpassword.value === oldpassword.value){
            errors.value.Newpassword = '新密碼不得與舊密碼相同';

        }
    }

    if(!confirmpassword.value){
        errors.value.confirmpassword = '請再輸入一次新密碼';
    }else if(Newpassword.value !== confirmpassword.value){
        errors.value.confirmpassword = '必須與新密碼相同';
    }

    if(errors.value.oldpassword || errors.value.Newpassword || errors.value.confirmpassword){
        return;
    }

    console.log('驗證通過！');

    // 補 AJAX 呼叫後端
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

    .contain > input{
        height: 36px;
        border: 2px solid $color-fsContent;
        border-radius: 8px;
        padding: 8px;
    }
    h5{
        color: $color-fsTitle;
    }

    h6{
        color: $color-fsCaption;
    }

    p{
        color: $color-fsCaption;

    }

</style>
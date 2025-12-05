<template>
    <div class="changepassword">
        <h2>Change password</h2>
        <div class="contain">
            <label for="oldpassword"><h5>Current password:</h5></label>
            <input type="text" name="oldpassword" id="oldpassword"/>
            <label for="Newpassword"><h5>New password:</h5></label>
            <input type="text" name="Newpassword" id="Newpassword"/>
            <label for="confirmpassword"><h5>Confirm new password:</h5></label>
            <input type="text" name="confirmpassword" id="confirmpassword"/>
            <BasicButton class="btn-yellow-fill" id="changepassword">Save changes</BasicButton>
        </div>  
    </div>
</template>

<script setup>
import BasicButton from '@/components/BasicButton.vue';
import { ref } from 'vue';

const oldpassword = ref('');
const Newpassword = ref('');
const confirmpassword = ref('');


    // const oldpassword = document.querySelector('#oldpassword');
    // const Newpassword = document.querySelector('#Newpassword');
    // const confirmpassword = document.querySelector('#confirmpassword');

    // document.querySelector('#changepassword').addEventListener('click', () => {
    //     if(!oldpassword.value){
    //         alert('請輸入oldpassword');
    //         return;
    //     }
    //     if(!Newpassword.value){
    //         alert('請輸入Newpassword');
    //         return;
    //     }
    //     if(!Newpassword.value !== confirmpassword.value){
    //         alert('password必須要跟Newpassword相同');
    //         return;
    //     }
    // });



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
        height: 28px;
    }
</style>
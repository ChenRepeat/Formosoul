<script setup>
import { onMounted, defineEmits, nextTick } from 'vue'
import {GoogleLogin as GoogleLoginBtn} from 'vue3-google-login'
import { useAuthStore } from '@/stores/autoStore';
import { useMemberStore } from '@/stores/member';

const authStore = useAuthStore();
const memberStore = useMemberStore();

const btnLook = {
  theme:"outline",
  size: "large" ,
  shape: "circle",
  width: 48,
  type : "icon" //
}


function handleCredential(response) { // 取需要的 JWT Json Web Token
  
  const apiBase = import.meta.env.VITE_API_BASE
  fetch(`${apiBase}/google.php`,{

    method: 'POST',
    headers:{
      'Content-Type': 'application/json'
    },
    credentials: 'include',
    // 資安考量改傳raw JWT 到php 用google 提供的函式庫驗證 
    body: JSON.stringify({
      'google_token' : response.credential,
    })
  })
  .then(res => res.json()) // -- ↓開始處理 php 回傳值↓ --
    // 透過.json() 將回傳物件轉為 js 物件
    // 效果== await res.text() + JSON.parse()
  .then(resData =>{
    if(resData.success){ // 登入成功
      authStore.setToken(resData.token) // 傳 token給 pinia 
      authStore.setUser(resData.user)
      memberStore.loadMemberData()

      if(resData.user.isFirstLogin){ // 是否初次登入
        authStore.setmemberView('membercard')
        console.log('as new member');

        
      }else{
        console.log('走舊會員區段');
        authStore.closeLoginModal()

      }
    }
    else{
      console.log(resData.message);
      
    }
  }
    
  )
}

onMounted(async ()=>{

})
</script>

<template>

   <div class="google-login-btn-container">
   <GoogleLoginBtn
   class="g_id_signin"
   id="google-login-btn"
    :callback="handleCredential"
    :button-config= btnLook
   />
  </div>
</template>

<style scpoed>
  .google-login-btn-container{
    display: inline-flex;
    align-self: center;
    width: 40px;
    height: 40px;
    overflow: hidden;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
  }
  .g_id_signin{
    transform: scale(2);

  }
</style>
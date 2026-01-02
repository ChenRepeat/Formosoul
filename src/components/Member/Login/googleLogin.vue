<script setup>
import { onMounted, defineEmits, nextTick } from 'vue'
import {GoogleLogin as GoogleLoginBtn} from 'vue3-google-login'
import { useAuthStore } from '@/stores/autoStore';
import { useMemberStore } from '@/stores/member';

const authStore = useAuthStore();
const memberStore = useMemberStore();

const clientId = import.meta.env.VITE_GOOGLE_CLIENT_ID
// console.log("目前的 Client ID:", clientId); // <--- 檢查這裡是不是 undefined

const emit = defineEmits(['login-success']) // 這好像是多的

const btnLook = {
  theme:"outline",
  size: "large" ,
  shape: "circle",
  width: 48,
  type : "icon" //
}

// function decodeJWT(token) { // 解碼 JWT

//   let base64Url = token.split(".")[1]; // 去除前綴字
//   let base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
//   let jsonPayload = decodeURIComponent( 
//     atob(base64)
//       .split("")
//       .map(function (c) {
//         return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
//       })
//       .join("")

//   );
//   return JSON.parse(jsonPayload) ; // 化為 JS 物件
// }

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
      'client_id': clientId,
    })
  })
  .then(res => res.json()) // -- ↓開始處理 php 回傳值↓ --
  .then(resData =>{
    if(resData.success){ // 登入成功
      authStore.setToken(resData.token) // 傳 token給 pinia 
      authStore.setUser(resData.user)
      memberStore.loadMemberData()

      if(resData.user.isFirstLogin){ // 是否初次登入
        authStore.setmemberView('membercard')
        console.log('as new member');

        // memberStore.memberData.value.name = resData.user.name// ???
        
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
  }

</style>
<script setup>
import { onMounted, defineEmits, nextTick } from 'vue'
import {GoogleLogin as GoogleLoginBtn} from 'vue3-google-login'
import { useAuthStore } from '@/stores/autoStore';

const authStore = useAuthStore();

// const clientId = import.meta.env.VITE_GOOGLE_CLIENT_ID
// console.log("目前的 Client ID:", clientId); // <--- 檢查這裡是不是 undefined

const emit = defineEmits(['login-success'])

const btnLook = {
  theme:"outline",
  size: "large" ,
  shape: "circle",
  width: 48,
  type : "icon" //
}

function decodeJWT(token) { // 解碼 JWT

  let base64Url = token.split(".")[1]; // 去除前綴字
  let base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
  let jsonPayload = decodeURIComponent( 
    atob(base64)
      .split("")
      .map(function (c) {
        return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
      })
      .join("")

  );
  return JSON.parse(jsonPayload) ; // 化為 JS 物件
}

function handleCredential(response) { // 取需要的 JWT Json Web Token

  console.log("Encoded JWT ID token: " + response.credential);

  // 在前端解碼只是先確定讀到的實際內容 預計改成只在php解碼
  const responsePayload = decodeJWT(response.credential);
  console.log(responsePayload );
  console.log("Decoded JWT ID token fields:");
  console.log("  Full Name: " + responsePayload.name); // 存這個 
  console.log("  Given Name: " + responsePayload.given_name);
  console.log("  Family Name: " + responsePayload.family_name);
  console.log("  Unique ID: " + responsePayload.sub); // 
  console.log("  Profile image URL: " + responsePayload.picture); // 大頭貼先不存
  console.log("  Email: " + responsePayload.email);

  
  const apiBase = import.meta.env.VITE_API_BASE
  fetch(`${apiBase}/google.php`,{

    method: 'POST',
    headers:{
      'Content-Type': 'application/json'
    },
    credentials: 'include',
    // 資安考量改傳raw JWT 到php 用google 提供的函式庫驗證 
    body: JSON.stringify({
      'google_token' : response.credential
    })
  })
  .then(res => res.json())
  .then(memberData =>{
    if(memberData){
      
      // 傳 token給 pinia 
      authStore.setToken(response.credential) // 名字可以自己取?
    }}
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
<script setup>
import { onMounted, defineEmits, nextTick } from 'vue'
import {GoogleLogin as GoogleLoginBtn} from 'vue3-google-login'

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





function decodeJWT(token) {

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
  return JSON.parse(jsonPayload); // 化為 JS 物件
}

function handleCredential(response) { // 接收JWT Json Web Token

  console.log("Encoded JWT ID token: " + response.credential);

  const responsePayload = decodeJWT(response.credential);

  console.log("Decoded JWT ID token fields:");
  console.log("  Full Name: " + responsePayload.name);
  console.log("  Given Name: " + responsePayload.given_name);
  console.log("  Family Name: " + responsePayload.family_name);
  console.log("  Unique ID: " + responsePayload.sub);
  console.log("  Profile image URL: " + responsePayload.picture);
  console.log("  Email: " + responsePayload.email);

  // 不確定要不要傳token給父組件
  fetch('google.php',{
    method: 'POST',
    headers:{

    },
    credentials: 'include',

  })

}

onMounted(async ()=>{
  // await loadGoogleSignIn()
  // await nextTick()
  // initGoogleLogin()
})
</script>

<template>

  <!-- <div ></div> -->


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
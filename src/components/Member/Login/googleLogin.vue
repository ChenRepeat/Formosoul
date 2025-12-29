<!-- <script src="https://accounts.google.com/gsi/client" async></script> -->
<script setup>
import { onMounted, defineEmits } from 'vue'
// const clientId = import.meta.env.GOOGLE_CLIENT_ID

const emit = defineEmits(['login-success'])

const loadGoogleSignIn = ()=>{
  return new Promise((resolve, reject) => {
    if(window.google) resolve(window.google) // 如果google物件已經存在 跳到成功載入步驟

    // write <script> content
    const script = document.createElement('script')
    script.src = "https://accounts.google.com/gsi/client" // 載入google官方給軟體開發者工具
    script.async = true // 給瀏覽器指令 下載時不要停掉網頁渲染
    script.defer = true // 下載完 才執行
    
    script.onload = () => resolve(window.google) // load 成功 & 執行完畢
    script.onerror = () => reject(new Error('google SDK login fail')) // load 失敗 // new Error() 會記錄錯誤在第幾行& 哪個funciton

    document.head.appendChild(script) // 將<scipt> 寫入 index.html
  })
} 

const initGoogleLogin = ()=>{ 
  // 用 SDK 的方法設定 API
  window.google.accounts.id.initialize({
    client_id: import.meta.env.GOOGLE_CLIENT_ID
  })
}

google.accounts.id.renderButton(
  document.getElementById("google-login-btn"),
  { 
    type: "icon",  
    shape: "circle", 
    size: "large"
  }
);

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

function handleCredentialResponse(response) { // 接收JWT Json Web Token

  console.log("Encoded JWT ID token: " + response.credential);

  const responsePayload = decodeJWT(response.credential);

  console.log("Decoded JWT ID token fields:");
  console.log("  Full Name: " + responsePayload.name);
  console.log("  Given Name: " + responsePayload.given_name);
  console.log("  Family Name: " + responsePayload.family_name);
  console.log("  Unique ID: " + responsePayload.sub);
  console.log("  Profile image URL: " + responsePayload.picture);
  console.log("  Email: " + responsePayload.email);
}

</script>

<template>
  <!-- g_id_onload contains Google Identity Services settings -->
  <div
    id="g_id_onload"
    data-auto_prompt="false"
    data-callback="handleCredentialResponse"

  ></div>
  <!-- g_id_signin places the button on a page and supports customization -->
  <div class="g_id_signin google-login-btn-container"></div>
</template>


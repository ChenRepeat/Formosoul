<!-- <script src="https://accounts.google.com/gsi/client" async></script> -->
<script setup>
import { onMounted } from 'vue'
const clientId = import.meta.env.GOOGLE_CLIENT_ID


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
    :data-client_id="`${clientId}`"
  ></div>
  <!-- g_id_signin places the button on a page and supports customization -->
  <div class="g_id_signin"
       data-type="icon" 
       data-shape="circle"
       data-click_listener="onClick" ></div>
</template>


<template>
    <div class="login-form">
        <div class="form-group">

            <input 
            id="email"
            type="email"
            v-model="email"
            class="input-text"
            placeholder="E-mail"
            :disabled="isLoading"
            @keydown="handleKeyDown"
            />
        </div>
        <div class="form-group">
            <div class="password-input">
                <input 
                id="password"
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                class="input-text"
                placeholder="Password"
                :disabled="isLoading"
                @keydown="handleKeyDown"
                />
                <button
                type="button"
                class="toggle-password-btn"
                @click="togglePassword"
                :disabled="isLoading"                
                >
                <font-awesome-icon 
                :icon="showPassword ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"
                style="font-size: 24px;"
                />
                    <!-- {{ showPassword ? '隱藏' : '顯示'}} -->
                </button>
            </div>
        </div>
        <div v-if="errorMessage" class="error-message"><p>{{ errorMessage }}</p></div>
        <div class="login-bottom">
            <p>
                * If your memory has been tampered with by a Memory Charm, click here：<a @click="hanldeForgetpassword" class="bottom-link">ForgetPassword</a>
            </p>
        </div>
        <BasicButton
        class="btn-yellow-fill"
        @click="handleLogin"
        :disabled="isLoading"
        >
            {{ isLoading ? 'Loading...' : 'Enter the Academy'}}
        </BasicButton>


    </div>
</template>

<script setup >
    import BasicButton from '@/components/BasicButton.vue';
    import { useAuthStore } from '@/stores/autoStore';
    import { inject, ref, watchEffect, watch } from 'vue';
    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    import { useMemberStore } from '@/stores/member';

    const authStore = useAuthStore();
    const sharedEmail = inject('sharedEmail');
    const email = ref(sharedEmail?.value || '');
    const password = ref('');
    const isLoading = ref(false);
    const errorMessage = ref('');
    const showPassword = ref(false);
    const memberStore = useMemberStore();
    function loginAPI(email, password, name) {
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/memberlogin.php`;

        return fetch(API_URL , {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body:JSON.stringify({
                email, password, name
            }),
            // 允許跨域請求攜帶 Cookie
            credentials: 'include'
        }).then( res => res.json());
        // 之後接回其他資料
        //   .then(member => {
        //   const { id, nickname, avatar} = member; // 解構指定語法
        //   console.log("頭像資料內容:", avatar);
        //   nicknameH1.textContent = nickname;
        //   avatarimg.src = `data:image/png;base64,${avatar}`;
        // // avatarimg.src = `data:image/jpeg;base64,${avatar}`;
        // // avatarimg.src = avatar;
        // });
    };
    const props = defineProps(['googleMessage']) 
    console.log(`google message初始:${props.googleMessage}`);
    if(props.googleMessage) errorMessage.value = props.googleMessage
    watch(
        ()=>props.googleMessage,
        // props.googleMessage 也式是響應式物件 但它傳值，故需使用函式追蹤才會看到更新 (以ref宣告的變數 則為傳址 會去追蹤該變數的更新)
        ()=>{
            // console.log(`Watch 賦值前的google message:${props.googleMessage}`),
            // console.log(`Watch 賦值前的error message:${errorMessage.value}`),
            errorMessage.value = props.googleMessage
            // console.log(`Watch 賦值後的error message:${errorMessage.value}`)
        }
    )

    async function handleLogin() {

        // await new Promise(resolve => setTimeout(resolve, 1500));

        if(!email.value || !password.value){
            errorMessage.value = 'Please enter your email or password'
            return
        }
        
        if (!email.value.includes('@')) {
            errorMessage.value = 'Please enter a valid email'
            return        
        }

        isLoading.value = true
        errorMessage.value = ''

        try {
            const response = await loginAPI(email.value, password.value, name.value);
            if(response.success && response.user.isFirstLogin){
                // 這邊就保存到 store
                authStore.setToken(response.token);
                authStore.setUser(response.user);
                // authStore.closeLoginModal();
                authStore.setmemberView('membercard');
                memberStore.loadMemberData();
            }else if(response.success && !response.user.isFirstLogin){
                authStore.setToken(response.token);
                authStore.setUser(response.user);
                memberStore.loadMemberData();
                authStore.closeLoginModal();
                // authStore.setmemberView('membercard');              
            }else{
                errorMessage.value = response.message;
            }

        } catch (error) {
            errorMessage.value = error.message || 'Login failed,Please tryagain';
        } finally{
            isLoading.value = false

        }
    }

    function hanldeForgetpassword(){

        authStore.setloginView('forgetpassword');

    };

    function handleKeyDown( e ) {
        if (e.key === 'Enter' && !isLoading.value) {
            handleLogin();        
        }   
    }

    function togglePassword() {
        showPassword.value = !showPassword.value;
    }

</script>

<style lang="scss" scoped>
    .login-form{
        width: 70%;
        height: 50%;
        margin: 0 auto;
        // padding-top: 80px;
        padding-bottom: 40px;
        
    }

    .form-group{
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        width: 100%;

        margin-bottom: 16px;

    }
    .password-input{
        position: relative;

    }
    .toggle-password-btn{
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translate(0, -50%);  
        background-color: transparent;
        border: 0;
        color: $color-fsContent;
        cursor: pointer;
    }
    h5{
        color: $color-fsTitle;
    }

    .btn-yellow-fill{
        width: 280px;
        display: flex;
        margin: 0 auto;
        // padding-top: 16px;
        // padding-bottom: 16px;
        justify-content: center;
        // margin-bottom: 24px;
    }
    .login-bottom{
        width: 100%;
        margin-bottom: 36px;

    }
    .login-bottom > p > a{
        text-decoration: none;
        color: $color-fsBlue900;
        cursor: pointer;

    }
    .login-bottom > p{
        // text-align: start;
        color: $color-fsCaption;

    }

    .error-message > p{
        color: $color-fsRed;
        margin-bottom: 24px;
    }
    
    @media screen and (min-width: 1400px) {
        .login-form{
            padding-top: 40px;
        }

    }
    @media screen and (max-width: 1366px) {
        .login-form{
            margin-top: 80px;
        }
        
    }
</style>
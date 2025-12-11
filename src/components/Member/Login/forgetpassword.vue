<template>
    <div class="login-form">
        <div class="otherlogin">
            <div class="otherlogin-title">
                <hr>
                <h6>Forget Password</h6>
                <hr>
            </div>
        </div>
        <div class="form-group">
            <input 
            id="email"
            type="email"
            v-model="email"
            class="form-input"
            placeholder="E-mail"
            :disabled="isLoading"
            @keydown="handleKeyDown"
            />
        </div>
        <div v-if="errorMessage" class="error-message"><p>{{ errorMessage }}</p></div>
        <div class="login-bottom">
            <p>It seems your memory has been tampered with by an Obliviate Charm! 
Don't worry, resetting your login incantation only takes a few simple steps. 
Please enter your registered email address below, and we will dispatch a messenger owl with the link to reset your password.</p>
        </div>
        <BasicButton
        class="btn-yellow-fill"
        @click="handleforgetpassword"
        :disabled="isLoading"
        >
            {{ isLoading ? 'Loading...' : 'Send Password Reset Link'}}
        </BasicButton>
    </div>
</template>

<script setup>
    import BasicButton from '@/components/BasicButton.vue';
import { useAuthStore } from '@/stores/autoStore';
    import { inject, ref } from 'vue';

    const currentView = inject('setCurrebtView');
    const authStore = useAuthStore();

    const email = ref('');
    const isLoading = ref(false);
    const errorMessage = ref('');
</script>

<style lang="scss" scoped>
    .login-form{
        width: 70%;
        padding: 40px;
        height: 544px;
        position: relative;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);

    }

    .form-group{
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        width: 100%;
        gap: 24px;
        margin-bottom: 16px;

    }

    .form-input{
        height: 54px;
        width: 100%;
        padding: 8px;
        border: 2px solid $color-fsCaption;
        border-radius: 4px;
        font-size: 1.6rem;
        font-family: 'Roboto', 'Noto Sans TC', sans-serif; 
    }

    .otherlogin-title{
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 16px;
    }
    .otherlogin-title > hr{
        color: $color-fsCaption;
        width: 35%;
        height: 1px; 
    }

    .otherlogin-title > hr:first-child{
        margin-right: 16px;
    }

    .otherlogin-title > hr:last-child{
        margin-left: 16px;
    }

    .otherlogin-title > h6{
        color: $color-fsCaption;
    }
</style>
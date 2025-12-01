<template>
    <div class="member-page-button">
        <!-- <router-link to="/">Home</router-link> -->
            <button class="page-button">Member information</button>
            <button class="page-button">Change password</button>
            <button class="page-button">Query Orders</button>
            <button class="page-button">My Collections</button>
            <button class="page-button">Coupons</button>
    </div>
</template>

<script setup>
    
</script>

<style scoped>
    .member-page-button{
        margin: 0 auto;
        width: 1200px;
        height: 48px;
        /* background-color: #; */
    }
    .page-button{
        width: 240px;
        height: 48px;
        border: 0;
        background-color: transparent;
        color: #041416;
        cursor: pointer;
        font-size: 16px;
    }
</style>
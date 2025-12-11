<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { RouterLink } from 'vue-router';
</script>

<template>
    <section class="success-dock">

        <div class="success-icondock">
            <font-awesome-icon class="success-icon" icon="fa-solid fa-check-double" />
        </div>

        <h3>Your order has been received !</h3>

        <p class="success-text">
            To check your order, please visit the
            <router-link to="/member/orderslist/orderscontain" class="success-orderlist">Member Center</router-link>
            .
        </p>

    </section>


</template>

<style scoped lang="scss">

    .success-dock{
        box-shadow: 
            inset 0 2px 6px rgba(255,255,255,0.05),
            0 0px 10px rgba(0,0,0,0.5);
        backdrop-filter: blur(70px);  
        border-radius: 10px;    
        margin-top: 40px;
        padding: 60px 60px 80px;
        text-align: center;
    }

    .success-icondock{
        border-radius: 50%;
        border: 1.5px solid $color-fsWhite;
        font-size: 48px;
        width: 100px;
        height: 100px;
        position: relative;
        margin: 0 auto 60px;
    }

    .success-icon{
        position: absolute;
        top:0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
    }

    .success-text{
        padding-top: 60px;
        text-align: center;
    }

    .success-orderlist{
        color: $color-fsGold300;
    }





</style>
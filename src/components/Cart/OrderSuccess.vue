<script setup>
import { RouterLink } from 'vue-router';
import Success from '../icons/SVG/success.vue';
</script>

<template>
    <section class="success-dock">
        <Success color="#fff" strokeWidth="3" roundWidth="1" class="success" delay="0.5"/>
        <h3>{{$t('shoppingcart.ordersuccess')}}</h3>

        <p class="success-text">
            {{$t('shoppingcart.orderText1')}}
            <RouterLink to="/member/orderslist" class="success-orderlist">{{$t('shoppingcart.orderText2')}}</RouterLink>
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
    .success{
        margin: 0 auto 60px;
    }
    .success-text{
        padding-top: 60px;
        text-align: center;
    }

    .success-orderlist{
        color: $color-fsGold300;
    }





</style>
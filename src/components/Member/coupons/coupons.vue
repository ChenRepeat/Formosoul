<template>
    <h3 v-if="havecoupon">{{$t('member.coupons')}}</h3>
    <!-- <h3 >Coupons</h3>    -->
    <Couponcontaion @no-coupon-found="handleNoCoupon"></Couponcontaion>
</template>

<script setup>
import { ref } from 'vue';
import Couponcontaion from './couponcontaion.vue';
    const havecoupon = ref(true);
    const handleNoCoupon = (errorMessage) => {
        havecoupon.value = false;
    };

</script>

<style scoped lang="scss">

    h3{
        margin-top: 100px;
        text-align: center;
    }

    
</style>
<template>
    <div class="member-orders">
        <h2>Query Orders</h2>
    </div>
    <div class="orders">
        <div class="title-orders">
            <span>Number</span>
            <span>Date</span>
            <span>Prices</span>
            <span>Payment</span>
            <span>Status</span>
            <span>Shipping</span>
            <span>Detail</span>
        </div>
        <hr class="title-orders-line">
        <MemberOrdersDetail />
        <div class="orderspage-btn" :currentPage="currentPage">
            
            <button><</button>
            <button>1</button>
            <button>2</button>
            <button>3</button>
            <button>></button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import MemberOrdersDetail from './MemberOrdersDetail.vue';

const currentPage = ref(1);

</script>

<style scoped>
    .member-orders{
        margin: 0 auto;
        width: 1200px;
    }
    h2{ 
        display: flex;
        font-size: 52px;
        align-items: center;
        color: #041416;
        justify-content: center;
    }
    .orders{
        margin: 0 auto;
        width: 1200px;  
    }
    .title-orders{
        width: 100%;
        display: flex;
        justify-content: space-between;
        margin: 0 auto;
        font-weight: bold;
    }
    .title-orders  > span{
        width: 160px;
        display: flex;
        justify-content: center;
    }
    .title-orders-line{
        height: 2px;
        background-color: #041416;
    }
    .orderspage-btn{
        padding-top: 16px;
        display: flex;
        justify-content: center;
        gap: 16px;
    }
    .orderspage-btn > button{
        width: 24px;
        height: 24px;
    }
</style>
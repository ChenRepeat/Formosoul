<template>
    <div class="detail">
        <h2>Orders Detail</h2>
        <div class="detailbar">
            <h3>Orders Information</h3>

        </div>
        <div v-for="order in orders" :key="order.id" class="orders-information">
            <span>Order Number: {{ order.number }}</span>
            <span>Order Date: {{ order.date }}</span>
            <span>Order Status: {{ order.status }}</span>
            <span>Recipient's Name: {{ order.name }}</span>
            <span>Delivery method: {{ order.shipping }}</span>
            <span>pieces: {{ order.pieces }}</span>
            <span>address: {{ order.address }}</span>
            <span>Remark: {{ order.remark }}</span>
        </div>
        <div class="detailbar">
            <h3>Payment Information</h3>
        </div>
        <div class="orders-information">
            <h3>Credit Card Number : VISA/ MASTER/ JCB</h3>
        </div>
        <div class="detailbar">
            <h3>Orders</h3>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';

    const orders = ref([
        {
            number: 'OD20250001',
            date: '2025-01-01',
            status: 'Deliverd',
            name: 'Irene',
            shipping: 'Home delivery',
            pieces: 'one',
            address: '238 No. 31, Lane 45, Section 2, Baoan Street, Shulin District, New Taipei City',
            remark: 'Leave a message for us',
        },
    ]);
</script>

<style lang="scss" scoped>
    .detail{
        margin: 0 auto;
        max-width: 1200px;
    }
    h2{
        text-align: center;
    }
    h3{
        text-align: center;
    }
    .orders-information > h3{
        text-align: start;
    }
    .detailbar{
        border: 0;
        border-radius: 8px;
        background-color: #f0f7ff;
        margin-bottom: 16px;
    }
    .orders-information{
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 16px;
        
    }
    .orders-information > span{
        font-size: 16px;
    }
</style>
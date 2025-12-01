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
        
    </div>
</template>

<script setup>
    
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
        width: calc(100% - 96px);
        display: flex;
        justify-content: space-between;
        margin: 0 auto;
    }
    .title-orders-line{
        height: 2px;
        background-color: #041416;
    }
</style>
<template>
    <div class="detail" @click="activeIndex = 2">
        <h2>Orders Detail</h2>
        <div class="detailbar">
            <h3>Orders Information</h3>
        </div>
        <div v-for="order in orders" :key="order.id" class="orders-information">
            <p>Order Number: {{ order.number }}</p>
            <p>Order Date: {{ order.date }}</p>
            <p>Order Status: {{ order.status }}</p>
            <p>Recipient's Name: {{ order.name }}</p>
            <p>Delivery method: {{ order.shipping }}</p>
            <p>pieces: {{ order.pieces }}</p>
            <p>address: {{ order.address }}</p>
            <p>Remark: {{ order.remark }}</p>
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
        <div v-for="product in products" class="orders-product">
            <img :src="product.images" :alt="1">
            <span><h4>{{ product.name }}</h4><h4>{{ product.spec }}</h4></span>
            <span><h4> Quantity : {{ product.pieces }} 件</h4></span>
            <span><h4>NT$ {{ product.price }}</h4></span>
        </div>
        <div v-for="total in totals" class="total">
            <span><h3>Subtotal:</h3><h6>NT$ {{ total.subtotal }}</h6></span>
            <span><h3>Discount:</h3><h6>NT$ -{{ total.discount }}</h6></span>
            <span><h3>Shipping Fee:</h3><h6>NT$ {{ total.fee }}</h6></span>
            <span><h3>Total:</h3><h6>NT$ {{ total.total }}</h6></span>
        </div>

        <div class="back-to-member">
            <basic-button class="btn-yellow-fill"><router-link to="/policy/orderslist"><h6><font-awesome-icon icon="fa-solid fa-angle-left" /> Back to Query orders</h6></router-link></basic-button>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import BasicButton from '@/components/BasicButton.vue';


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

    const products = ref([
        {
            images: '../../public/product/PelletDrum.jpg',
            name: 'PelletDrum',
            spec: 'large',
            pieces: '1',
            price: '999',

        },
        {
            images: '../../public/product/PelletDrum.jpg',
            name: 'PelletDrum',
            spec: 'large',
            pieces: '1',
            price: '999',

        },


    ]);
    const totals = ref([
        {
            subtotal: '299',
            discount: '9',
            fee: '80',
            total: '300',
        }
    ]);
</script>

<style lang="scss" scoped>
    .detail{
        margin: 0 auto;
        max-width: 1200px;
    }

    h2{
        text-align: center;
        margin: 100px 0 100px 0;
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


    .orders-product{
        display: flex;
        justify-content: space-between;
        padding: 16px;
        border-bottom: 1px solid #ddd;
    }

    .orders-product > img{
        width: 180px;   
        height: 180px;
        object-fit: contain;
    }
    
    .orders-product > span{
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 16px;
    }

    .orders-product > span{
        color: $color-fsTitle;
        
    }
    .orders-product > span:nth-child(3){
        color: $color-fsCaption;
    }

    .orders-product > span:nth-child(4){
        color: $color-fsRed;
    }

    .total{
        width: 50%;
        display: flex;
        flex-direction: column;
        margin-left: auto;
        margin-top: 16px;
        gap: 16px;
    }

    .total > span{
        display: flex;
        justify-content: space-between;
    }

    .total > span > h6{
        display: flex;
        align-items: center;
        justify-content: center;
        color: $color-fsRed;
    }

    .back-to-member{
        margin-top: 32px;
        display: flex;
        justify-content: center;
        padding: 32px;
    }
    .back-to-member > .btn-yellow-fill > a{
        color: $color-fsTitle;
        text-decoration: none;
    }

    .btn-yellow-fill{
        padding-top: 16px;
        padding-bottom: 16px;
    }
</style>
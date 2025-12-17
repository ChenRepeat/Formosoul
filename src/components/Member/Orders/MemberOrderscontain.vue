<template>
    <div class="detail" @click="activeIndex = 2">
        <h3>Orders Detail</h3>
        <div class="detailbar">
            <p>Orders Information</p>
        </div>
        <div v-for="order in orders" :key="order.id" class="orders-information notice">
            <p>Order Number： <span>{{ order.number }}</span></p>
            <p>Order Date： {{ order.date }}</p>
            <p>Order Status： {{ order.status }}</p>
            <p>Recipient's Name： {{ order.name }}</p>
            <p>Delivery method： {{ order.shipping }}</p>
            <p>pieces： {{ order.pieces }}</p>
            <p>address： {{ order.address }}</p>
            <p>Remark： {{ order.remark }}</p>
            <p>* Notice *<br> {{ order.notice }}</p>

        </div>
        <div class="detailbar">
            <p>Payment Information</p>
        </div>
        <div class="orders-information">
            <p>Payment Method： Credit Card (Pay in Full)－VISA/ MASTER/ JCB</p>
        </div>
        <div class="detailbar">
            <p>Products Information</p>
        </div>
        <div v-for="product in products" class="orders-product">
            <img :src="product.images" :alt="1">
            <span><p>{{ product.name }}</p><!--<p>{{ product.spec }}</p>--></span>
            <span><p> {{ product.pieces }} item(s)</p></span>
            <span><p>NT$ {{ product.price }}</p></span>
        </div>
        <div v-for="total in totals" class="total">
            <span><p>Subtotal：</p><p>NT$ {{ total.subtotal }}</p></span>
            <span><p>Discount：</p><p>NT$ -{{ total.discount }}</p></span>
            <span><p>Shipping Fee：</p><p>NT$ {{ total.fee }}</p></span>
            <span><p>Total：</p><p>NT$ {{ total.total }}</p></span>
        </div>

        <div class="back-to-member">
            <router-link to="/member/orderslist"><basic-button class="btn-yellow-fill"><h6><font-awesome-icon icon="fa-solid fa-angle-left" /> Back to Query orders</h6></basic-button></router-link>
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
            pieces: 'two',
            address: '238 No. 31, Lane 45, Section 2, Baoan Street, Shulin District, New Taipei City',
            remark: 'Please leave it with the security guard / front desk.',
            notice: 'To request a return, please email our customer service within the 7-day cooling-off period. For further information, please refer to our Return and Exchange Policy.',
        },
    ]);

    const products = ref([
        {
            images: `${import.meta.env.BASE_URL}product/PelletDrum.jpg`,
            name: 'PelletDrum',
            spec: 'large',
            pieces: '1',
            price: '200',

        },
        {
            images: `${import.meta.env.BASE_URL}product/PelletDrum.jpg`,
            name: 'PelletDrum',
            spec: 'large',
            pieces: '1',
            price: '99',

        },


    ]);
    const totals = ref([
        {
            subtotal: '299',
            discount: '9',
            fee: '80',
            total: '370',
        }
    ]);
</script>

<style lang="scss" scoped>
    .detail{
        margin: 0 auto;
        max-width: 1000px;

        & >h3{
            text-align: center;
            margin: 100px 0 100px 0;
        }

        & .notice{
            & span{
                color: $color-fsRed;
                font-weight: 600;
            }

            & p:last-of-type{
                color: $color-fsBlue;
            }
        }
    }

    .orders-information{
        & >p{
            text-align: start;
        }
    } 

    .detailbar{
        border-radius: 8px;
        background-color: $color-fsBlue50;
        height: 40px;    
        margin: 16px 0 16px;
        padding: 0 20px;
        & p{
            line-height: 40px;
            font-weight: 600;
        }
    }

    .orders-information{
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 16px;
        padding: 0 32px;    
    }


    .orders-product{
        display: flex;
        justify-content: space-between;
        margin: 0 32px;
        padding: 32px;
        border-bottom: 1px solid $color-fsCaption;
    }

    .orders-product > img{
        width: 100px;   
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid $color-fsCaption;
    }
    
    .orders-product > span{
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 16px;
        color: $color-fsTitle;   
    }


    .total{
        width: 30%;
        display: flex;
        flex-direction: column;
        margin: 20px 32px 20px auto;
        gap: 16px;

        & span{
            display: flex;
            justify-content: space-between;
            font-weight: 600;

            & p:last-of-type{
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        & span:last-of-type{

            & p{
                font-size: 2.8rem;
            }
            & p:last-of-type{
                color: $color-fsRed;
            }
        }
    }


    

    .back-to-member{
        margin: 100px 32px;
        display: flex;
        justify-content: center;
    }
    .back-to-member > .btn-yellow-fill > a{
        color: $color-fsTitle;
        text-decoration: none;
    }

</style>
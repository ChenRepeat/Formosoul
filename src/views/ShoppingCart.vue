<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { RouterLink } from 'vue-router';
import OrderSuccess from '@/components/Cart/OrderSuccess.vue';
import ShoppingCheck from '@/components/Cart/ShoppingCheck.vue';
import BasicButton from '../components/BasicButton.vue';
import Backgroundaction from '@/components/backgroundaction.vue';




</script>

<template>
<main class="shoppingcart-main">
    <!-- 麵包屑 -->
    <h6 class="page-guide">
        Shopping Cart   
    </h6>

    <h6 class="page-guide-step">
        <font-awesome-icon icon="fa-solid fa-angle-right" />
        STEP 1. MY CART
    </h6>

    <h6 class="page-guide-step dp-none">
        <font-awesome-icon icon="fa-solid fa-angle-right" />
        STEP 2. PAYMENT & SHIPPING
    </h6>
    
    <h6 class="page-guide-step dp-none">
        <font-awesome-icon icon="fa-solid fa-angle-right" />
        STEP 3. COMPLETED
    </h6>


    <!-- 進度 -->
     <section>
        <ul class="dp-flex cart-dock">
            <li class="cart-dock-block nowblock">
                <h1 class="cart-step fw200 nowstep">1</h1>
                <h6 class="cart-step-text nowtext">MY CART</h6>
            </li>
            <li class="cart-dock-block">
                <h1 class="cart-step fw200">2</h1>
                <h6 class="cart-step-text">PAYMENT & SHIPPING</h6>
            </li>
            <li class="cart-dock-block">
                <h1 class="cart-step fw200">3</h1>
                <h6 class="cart-step-text">COMPLETED</h6>
            </li>
        </ul>
        <hr class="cart-step-bar">
     </section>
    
    <!-- 內容 -->
    <section class="cart-step-content my-cart-dock dp-none">
        <h5>Order Details</h5>
        <div class="orderdetail-head dp-flex fw200">
            <div class="itemhead-image"></div>
            <h6 class="itemhead-name">Product Name</h6>
            <h6 class="itemhead-qty">Quantity</h6>
            <h6 class="itemhead-price">Price</h6>
            <div class="itemhead-icon-delete"></div>   
        </div>
        <hr class="item-bar-head">

        <div class="orderdetail-body dp-flex">
            <div class="item-image">
                <img src="../../public/Shop/2.png" alt="">
            </div>
            <h6 class="item-name">Bamboo Helicopter</h6>
            <div class="item-qty dp-flex">
                <font-awesome-icon class="item-qty-icon" icon="fa-regular fa-square-minus" />
                <h6>1</h6>
                <font-awesome-icon class="item-qty-icon fw200" icon="fa-regular fa-square-plus" />
            </div>
            <h6 class="item-price">NT$ 190</h6>
            <font-awesome-icon class="item-icon-delete" icon="fa-regular fa-trash-can" />
            
        </div>
        <hr class="item-bar">

        <div class="orderdetail-body dp-flex">
            <div class="item-image">
                <img src="../../public/Shop/2.png" alt="">
            </div>
            <h6 class="item-name">Bamboo Helicopter</h6>
            <div class="item-qty dp-flex">
                <font-awesome-icon class="item-qty-icon" icon="fa-regular fa-square-minus" />
                <h6>1</h6>
                <font-awesome-icon class="item-qty-icon fw200" icon="fa-regular fa-square-plus" />
            </div>
            <h6 class="item-price">NT$ 190</h6>
            <font-awesome-icon class="item-icon-delete" icon="fa-regular fa-trash-can" />
            
        </div>
        <hr class="item-bar">

        <div class="item-total dp-flex">
            <h5>Total</h5>
            <div class="item-dollar dp-flex">
                <h5>NT$ </h5>
                <h3>190</h3>
            </div>
        </div>

        <div class="btn-step dp-flex">
            <BasicButton class="btn-gray-fill btn-fix-width">    
                <font-awesome-icon icon="fa-solid fa-angle-left" />
                Back to Shopping
            </BasicButton>
            <BasicButton class="btn-yellow-fill btn-fix-width">
                NEXT
                <font-awesome-icon icon="fa-solid fa-angle-right" />
            </BasicButton>
        </div>
        
        


    </section>

  
    <ShoppingCheck class="cart-step-content"/> 
    <OrderSuccess class="cart-step-content dp-none"/>
    <Backgroundaction></Backgroundaction>

</main>

</template>

<style scoped lang="scss">

    .shoppingcart-main{
        padding: 180px 120px 100px;
        color: $color-fsWhite;
        
        //暫時背景
        // background-image: url(../../public/Shop/bgi.jpg);
        background-size: cover;
        background-position: right; 
    }
// 麵包屑
    .page-guide{
        display: inline;
    }

    .page-guide-step{
        display: inline;
    }

    .dp-none{
        display: none;
    }
 

// 進度

.cart-dock-block{
    flex-basis: 0;
    flex-grow: 1;
    border-bottom: 2.5px solid transparent;
    margin-top: 100px;
}

.nowblock{
    border-bottom: 2.5px solid $color-fsWhite;
    color: $color-fsWhite;
}

.cart-step{
    border-radius: 50%;
    border: 1.5px solid $color-fsContent;
    width: 100px;
    color:  $color-fsContent;
    text-align: center;
    margin: 0 auto 20px;  
}

.nowstep{
    color: $color-fsWhite;
    border: 1.5px solid $color-fsWhite;
}

.cart-step-text{
    text-align: center;
    color: $color-fsContent;
    line-height: 300%;
}

.nowtext{
    color: $color-fsWhite;
}

.cart-step-bar{
    border: none;
    height: 1px;
    background-color: $color-fsWhite;
}


// 內容
.my-cart-dock{
    box-shadow: 
        inset 0 2px 6px rgba(255,255,255,0.05),
        0 0px 10px rgba(0,0,0,0.5);
    backdrop-filter: blur(70px);  
    border-radius: 10px;    
    margin-top: 40px;
    padding: 60px 40px 80px;
    text-align: center;
}

.my-cart-dock>h5{
    margin-bottom: 60px;
}

.orderdetail-head, .orderdetail-body{
    padding: 0 20px;
    align-items: center;
}

.item-bar{
    margin: 20px 0;
    border: none;
    height: 0.5px;
    background-color: $color-fsWhite;
}

.item-bar:last-of-type{
    height: 1px;
}

//head

.item-bar-head{
    margin-bottom: 20px;
}

.itemhead-image{width: 100px; flex-grow: 0;}
.itemhead-name{flex-basis: 0; flex-grow: 4;}
.itemhead-price{flex-basis: 0; flex-grow: 2;}
.itemhead-qty{flex-basis: 0; flex-grow: 3;}
.itemhead-icon-delete{flex-basis: 0; flex-grow: 1;}

//body
.item-image{
    width: 100px;
    height: 100px;

    flex-grow: 0;
}

.item-image>img{
    width: 100%;
    height: 100%;
    border-radius: 10px;
}

.item-name{
    flex-basis: 0;
    flex-grow: 4;
}

.item-price{
    flex-basis: 0;
    flex-grow: 2;
}

.item-qty{
    justify-content: center;
    align-items: center;

    flex-basis: 0;
    flex-grow: 3;
}

.item-qty-icon, .item-icon-delete{
    font-size: 2rem;
    cursor: pointer;
}

.item-qty>h6{
    border-radius: 10px;
    border: 0.5px solid $color-fsWhite;
    width: 70px;
    text-align: center;
    margin: 0 20px;
}

.item-icon-delete{
    flex-basis: 0;
    flex-grow: 1;
}


//total
.item-total{
    justify-content: space-between;
    align-items: center;
}

.item-dollar{
    align-items: center;
}

.item-dollar>h3{
    color: $color-fsRed;
    margin-left: 20px;
}

.btn-step{
    justify-content: space-between;
    margin-top: 100px;
}

</style>
<script setup>
import { useRouter } from "vue-router";     //使用路由功能


// 宣告常數來接收 useRouter() ，方便後續使用
const router = useRouter();

// 設定路由功能
function goShopping(){
    router.push('/shop/productList')
}

function goPayment(){
    router.push('/shoppingcart/checkout')
}


</script>

<template>
    <main class="my-cart-dock">
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
                <img src="../../../public/Shop/2.png" alt="">
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
                <img src="../../../public/Shop/2.png" alt="">
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
            <BasicButton 
            class="btn-gray-fill btn-fix-width"
            @click="goShopping"
            >    
                <font-awesome-icon icon="fa-solid fa-angle-left" />
                Back to Shopping
            </BasicButton>
            <BasicButton 
            class="btn-yellow-fill btn-fix-width"
            @click="goPayment"
            >
                NEXT
                <font-awesome-icon icon="fa-solid fa-angle-right" />
            </BasicButton>
        </div>
        
        


    </main>


</template>

<style scoped lang="scss">

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
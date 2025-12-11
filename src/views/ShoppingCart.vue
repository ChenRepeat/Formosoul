<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import OrderSuccess from '@/components/Cart/OrderSuccess.vue';
import ShoppingCheck from '@/components/Cart/ShoppingCheck.vue';




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

  
    <ShoppingCheck /> 
    <OrderSuccess />
   

</main>

</template>

<style scoped lang="scss">

    .shoppingcart-main{
        padding: 180px 120px 100px;
        color: $color-fsWhite;
        
        //暫時背景
        background-image: url(../../public/Shop/bgi.jpg);
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


// 進度bar
</style>
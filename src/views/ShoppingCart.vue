<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { RouterView, useRoute } from 'vue-router';    //因為computed需要使用到useRoute
import Backgroundaction from '@/components/backgroundaction.vue';
import { computed } from 'vue'; //使用computed的功能，未傳參數但是有傳回值，因為route已經改變


// 步驟切換
// 1: mycart  2: checkout  3: ordersuccess
const route = useRoute();
const currentStep = computed(()=>{      //當前的步驟永遠由路由決定
    if (route.name === 'MyCart') return 1;
    if (route.name === 'CheckOut') return 2;
    if (route.name === 'OrderSuccess') return 3;

    return 1;       // 預設步驟為1
});

</script>

<template>
<main class="shoppingcart-main">
    <!-- 麵包屑 -->
    <h6 class="page-guide">
        Shopping Cart   
    </h6>

    <h6 v-if="currentStep === 1" class="page-guide-step">
        <font-awesome-icon icon="fa-solid fa-angle-right" />
        STEP 1. MY CART
    </h6>

    <h6 v-else-if="currentStep === 2" class="page-guide-step">
        <font-awesome-icon icon="fa-solid fa-angle-right" />
        STEP 2. PAYMENT & SHIPPING
    </h6>
    
    <h6 v-else class="page-guide-step">
        <font-awesome-icon icon="fa-solid fa-angle-right" />
        STEP 3. COMPLETED
    </h6>


    <!-- 進度 -->
    <section>
        <ul class="dp-flex cart-dock">
            <li 
            class="cart-dock-block"
            :class="{nowblock: currentStep === 1}">
                <h1 class="cart-step fw200" :class="{nowstep: currentStep === 1}">1</h1>
                <h6 class="cart-step-text" :class="{nowtext: currentStep === 1}">MY CART</h6>
            </li>
            <li 
            class="cart-dock-block"
            :class="{nowblock: currentStep === 2}">
                <h1 class="cart-step fw200" :class="{nowstep: currentStep === 2}">2</h1>
                <h6 class="cart-step-text" :class="{nowtext: currentStep === 2}">PAYMENT & SHIPPING</h6>
            </li>
            <li 
            class="cart-dock-block"
            :class="{nowblock: currentStep === 3}">
                <h1 class="cart-step fw200" :class="{nowstep: currentStep === 3}">3</h1>
                <h6 class="cart-step-text" :class="{nowtext: currentStep === 3}">COMPLETED</h6>
            </li>
        </ul>
        <hr class="cart-step-bar">
    </section>

    <RouterView></RouterView>

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

    & .nowstep{
        color: $color-fsWhite;
        border: 1.5px solid $color-fsWhite;
    }

    & .nowtext{
        color: $color-fsWhite;
    }
}

.cart-step{
    border-radius: 50%;
    border: 1.5px solid $color-fsContent;
    width: 100px;
    color:  $color-fsContent;
    text-align: center;
    margin: 0 auto 20px;  
}

.cart-step-text{
    text-align: center;
    color: $color-fsContent;
    line-height: 300%;
}

.cart-step-bar{
    border: none;
    height: 1px;
    background-color: $color-fsWhite;
}

</style>
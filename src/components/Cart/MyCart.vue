<script setup>
import { useRouter } from "vue-router";     //使用路由功能
import BasicButton from '../../components/BasicButton.vue';
import OrderList from "../OrderList.vue";
import { useAuthStore } from "@/stores/autoStore";


// 宣告常數來接收 useRouter() ，方便後續使用
const router = useRouter();
const authStore = useAuthStore();
// 設定路由功能
function goShopping(){
    
        
        router.push('/shop/productList')

    
}
// 沒有登入的 cookie token 就跳出登入頁
function goPayment(){
    if(authStore.token){
        router.push('/shoppingcart/checkout');
    }else{
        authStore.openLoginModal();
        authStore.setmemberView('login');
        // router.push(''); 這句表示跳到這頁的最上面
        
    }
}


</script>

<template>
    <main class="my-cart-dock">

        <OrderList
            :showCheck="false" 
            >
        </OrderList>

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
}

.btn-step{
    justify-content: space-between;
    padding: 40px 40px 80px;
}

</style>
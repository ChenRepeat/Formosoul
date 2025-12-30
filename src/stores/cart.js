import { defineStore } from 'pinia';
import { ref } from 'vue';

// 建立 Store ID 'cart' 給 pinia 辨識
export const useCartStore = defineStore('cart', () => {

    // step1 建立資料儲存區(購物車清單)
    const cartList = ref([]);


    // step2 購物車的加入、移除、清空

    const addToCart = computed(() => {

    });

    
    
    const removeItem = computed(() => {

    });


    const clearCart = computed(() => {

    });


    
    
    
    // step3 購物車數量及價格計算









    //需要 export 給組件使用的
    return{

    };

});
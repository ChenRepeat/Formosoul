import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';

// 建立 Store ID 'cart' 給 pinia 辨識
export const useCartStore = defineStore('cart', () => {

    const { t } = useI18n(); 

    // step1 建立資料儲存區(購物車清單)
    const cartList = ref([]);


    // step2 購物車的加入、移除、清空

    const addToCart = ( selectItem, itemQty = 1 ) => {
        // 預設加入購物車時，detail 頁面會傳來這個商品的整包資料 selectItem，以及使用者當下設定的購買數量 itemQty
        // 購買數量如果未傳，預設 1

        // 判斷購物車內是否已經有這個商品了
        const thisID = selectItem.product_ID;
        const hasItem = cartList.value.find(item => item.product_ID === thisID)

        if( !hasItem ){
            // 情況一 商品尚未加入過
            cartList.value.push({
                ...selectItem,
                qty: itemQty,
            });
        }else{
            // 情況二 商品已加入，只要增加數量
            hasItem.qty += itemQty;
        };

        alert(t('productdetail.addSuccess'));
    };

    
    const removeItem = ( deleteItemID ) => {
        const itemIndex = cartList.value.findIndex(item => item.product_ID === deleteItemID)
        
        // 因為 findIndex 的規則中，如果找不到，會回傳 -1
        // 這跟 splice 的規則會有衝突，因為 -1 的索引代表倒數第一個，如果刪除的過程有遇到使用者重複點選刪除鍵等情況，可能會出現刪錯資料的情形
        // 所以需要多寫一個判斷式
        if( itemIndex > -1 ){
            cartList.value.splice(itemIndex, 1)     // 從 itemIndex 開始，刪除一個
            // *用 splice 不用 filter 的原因是因為效能
            // splice 修改原陣列，filter 創造新陣列
        };
    };


    const clearCart = () => {
        cartList.value = [];
    };
    
    
    // step3 購物車商品數量及價格計算

    //數量計算  *購物車 icon 要使用
    const totalQty = computed( () => {
        return cartList.value.reduce((qtySum, item) => qtySum + item.qty ,0);
    });


    //價格計算
    const totalPrice = computed( () => {
        return cartList.value.reduce((priceSum, item) => priceSum + item.qty *item.price ,0);
    });



    //需要 export 給組件使用的
    return{
        cartList,
        addToCart,
        removeItem,
        clearCart,
        totalQty,
        totalPrice,

    };

});
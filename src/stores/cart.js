import { defineStore } from 'pinia';
import { ref, computed, watch } from 'vue';
import { useI18n } from 'vue-i18n';

// 建立 Store ID 'cart' 給 pinia 辨識
export const useCartStore = defineStore('cart', () => {

    const { t } = useI18n(); 

    // step1 建立資料儲存區(購物車清單)
    // const cartList = ref([]);    未存 localStorage 
    // 購物車留下紀錄：存 localStorage 
    // storage 只收字串，所以要經過轉型打包跟還原
    const cartList = ref(
        JSON.parse(localStorage.getItem('MyCart')) || [] 
    );

    watch(
        cartList, 
        (newValue) => {
            localStorage.setItem('MyCart', JSON.stringify(newValue));
        }, 
        { deep: true }
    );



    // step2 購物車的加入、移除、清空、數量加減

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

        // alert(t('shoppingcart.deleteItem')); 不用 alert 是因為使用者只能按確定，沒有選擇權
        // 改用 window.confirm
        const hasDelete = window.confirm(t('shoppingcart.deleteItem'));

        if( hasDelete ){

            const itemIndex = cartList.value.findIndex(item => item.product_ID === deleteItemID)
            
            // 因為 findIndex 的規則中，如果找不到，會回傳 -1
            // 這跟 splice 的規則會有衝突，因為 -1 的索引代表倒數第一個，如果刪除的過程有遇到使用者重複點選刪除鍵等情況，可能會出現刪錯資料的情形
            // 所以需要多寫一個判斷式
            if( itemIndex > -1 ){
                cartList.value.splice(itemIndex, 1)     // 從 itemIndex 開始，刪除一個
                // *用 splice 不用 filter 的原因是因為效能
                // splice 修改原陣列，filter 創造新陣列
            };
        }
    };


    const clearCart = () => {
        cartList.value = [];
    };


    const qtyPlus = (thisItem) => {
        if( thisItem.qty < thisItem.stock ){
            thisItem.qty++;
        }
    };


    const qtyMinus = (thisItem) => {
        if( thisItem.qty > 1 ){
            thisItem.qty--;
        }else{
            
            removeItem( thisItem.product_ID );

        };
    };


    
    
    // step3 購物車商品數量及價格計算

    //數量計算  *購物車 icon 要使用
    const totalQty = computed( () => {
        return cartList.value.reduce((qtySum, item) => qtySum + item.qty ,0);
    });


    //單項商品總價計算
    /* 寫法一
    const unitPrice = computed( () => {
        return function(item) {
            return item.price * item.qty;
        }; 
    });
    */

    // 寫法二
    const unitPrice = computed( () => (thisItem) => {
        return thisItem.price * thisItem.qty;
    });


    // 小計計算
    const totalPrice = computed( () => {
        return cartList.value.reduce((priceSum, item) => priceSum + item.qty *item.price ,0);
    });


    // 運費
    const shippingFeeList = {
        taiwan: 80,
        japan: 120,
        singapore: 150,
        netherlands: 270,
        ireland: 490,
    };

    const selectCountry = ref('taiwan');
    
    //const shippingFee = ref(shippingFeeList.value || 80);  這樣寫代表 會查到整張表，並不是裡面的某個 key 值

    const shippingFee = computed(() => {
        return shippingFeeList[ selectCountry.value ]
    });
    

    // 優惠券折扣
    const discount = ref(0);

    // 最後付款總額
    const finalPrice = computed(() => 
        totalPrice.value + shippingFee.value - discount.value
    );


    // 其他功能 - 取得會員ＩＤ
    const memberID = ref(null);

    const getMemberID = () => {
        const localData = localStorage.getItem('user');     //讀取 localstorage 的資料

        if(localData){
            //return JSON.parse(localData);   //把 localData 轉回原本的格式

            //因為使用 JSON.parse，為了避免 user 裡存的資料可能被修改，或著不是JSON，多補上 try..catch 保護程式
            try{
                const userData = JSON.parse(localData);
                memberID.value = userData.member_ID || null;     // ||null是為了避免出現意外，變成 undifined
               }catch($e){        // 因為在 JS，所以不用有型別名稱，JS會自動把抓到的錯誤塞給變數，也因為在JS，所以變數名稱不用加上$
                console.error('user資料壞掉');            //用 console.error 的原因是，這行會變紅色，比較容易看到   
                memberID.value = null;
               }   
        }else{
            memberID.value = null;
        }
    };

    // <初始化 cart.js 時，先執行一次取得會員ＩＤ 的動作>
    getMemberID();



    // 其他功能 - 取得折價券ＩＤ



    //需要 export 給組件使用的
    return{
        cartList,
        addToCart,
        removeItem,
        clearCart,
        qtyPlus,
        qtyMinus,
        totalQty,
        unitPrice,
        totalPrice,
        shippingFeeList,
        selectCountry,
        shippingFee,
        discount,
        finalPrice,
        memberID,
        getMemberID,
    };

});
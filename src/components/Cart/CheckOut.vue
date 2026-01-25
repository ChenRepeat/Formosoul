<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ref, computed, watch } from 'vue'
import { useRouter } from 'vue-router';
import { useCartStore } from '@/stores/cart';
import { useI18n } from 'vue-i18n';
import { useAddressStore } from '@/stores/addressStore';
import axios from 'axios';
import BasicButton from '../../components/BasicButton.vue';
import OrderList from '../OrderList.vue';
import Couponcontaion from '../Member/coupons/couponcontaion.vue';

const router = useRouter();
const cartstore = useCartStore();
const addrstore = useAddressStore();
const { t, locale } = useI18n();     // 讀取語系狀態
const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;

// coupon -------------------------------------
const hasCoupon = ref(false);
function couponclose(){
    cart_use_coupon().then( res =>{
        if(res.success && res.data.coupons_ID == cartstore.coupon_ID){
            restore_coupon();
            cartstore.coupon_ID = null;
            cartstore.discount = 0;
            hasCoupon.value = false;
            document.body.style.overflow = '' ;
            document.documentElement.style.overflow = '';
            document.querySelector('.shoppingcart-main').style.paddingRight = 120 + 'px';

        }else if(!res.success && cartstore.coupon_ID == null){
            restore_coupon();
            cartstore.discount = 0
            hasCoupon.value = false;
            document.body.style.overflow = '' ;
            document.documentElement.style.overflow = '';
            document.querySelector('.shoppingcart-main').style.paddingRight = 120 + 'px';

 

        }
    })
}

function couponopen(){
    hasCoupon.value = true;
    document.querySelector('.shoppingcart-main').style.overflow = 'hidden' ;
    document.querySelector('.shoppingcart-main').style.paddingRight = 120 + scrollbarWidth + 'px';
    document.documentElement.style.overflow = 'hidden';
    
}
function restore_coupon(){
        const storedUser = localStorage.getItem('user');
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/modifycoupon.php`;
        if(!storedUser) return;
        const userData = JSON.parse(storedUser); 
        const { pointscard_ID } = userData;

        return fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json'
            },
            credentials: 'include',
            body: JSON.stringify({
                pointscard_ID
            })
        }
        ).then( res => res.json())}; 

function cart_use_coupon(){
        const storedUser = localStorage.getItem('user');
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/checkdiscount.php`;
        if(!storedUser) return;
        const userData = JSON.parse(storedUser); 
        const { pointscard_ID } = userData;

        return fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json'
            },
            credentials: 'include',
            body: JSON.stringify({
                pointscard_ID,
                coupons_ID: cartstore.coupon_ID
            })
        }
        ).then( res => res.json())
};
function couponuse(){
    cart_use_coupon().then( res =>{
        if(res.success && res.data.coupons_ID == cartstore.coupon_ID){
            cartstore.discount = res.data.discount
            hasCoupon.value = false;
            document.body.style.overflow = '' ;
            document.documentElement.style.overflow = '';
            document.querySelector('.shoppingcart-main').style.paddingRight = 120 + 'px';

        }else if(!res.success && cartstore.coupon_ID == null){
            cartstore.discount = 0
            hasCoupon.value = false;
            document.body.style.overflow = '' ;
            document.documentElement.style.overflow = '';
            document.querySelector('.shoppingcart-main').style.paddingRight = 120 + 'px';

        }
    })
}

// #region 暫時不用的信用卡格式
// // 信用卡填完跳轉下一格 ---------------------------
// const cardNum = ref([]);
// const cardMonth = ref(null);
// const cardYear = ref(null);
// const cardCode = ref(null);
// const cardName = ref(null);

// /* 這個寫法要寫好幾個function來進行跳轉
// function nextInput( e, index ){
//     //檢查內容是否為數字

//     //檢查內容的長度
//     if(e.target.value.length === 4 ){    
//         //檢查是否為最後一格
//         if(index < 3){
//             cardNum.value[ index + 1 ].focus();
//         }else{
//             cardMonth.value.focus();
//         }
//     }
// }
// */

// //用一個 function 統一處理跳轉
// function goNext( e, maxlength, nextOne ){

//     // 先過濾輸入的內容是否為數字，但是排除姓名欄
//     // 因為在目前情況下，剛好有限制長度的欄位皆為數字，所以一定會輸入限制長度，而長度必大於0，所以可以用這件事來進行排除
//     if( maxlength > 0 ){
//         //e.target.value = e.target.value.replace(/[^\d]/g, '');
//         e.target.value = e.target.value.replace(/\D/g, '');
//         // replace() 是字串的方法，因為 input 的內容會被視為字串傳回來，所以可以使用字串的方法
//         // replace(舊值, 取代舊值的新值)

//         /*
//         /[^\d]/g 正則表達式
//         /  / ：中間寫規則
//         g    ：修飾符，代表規則的備註 / 設定 => g代表全部都要找出來，如果沒有 g，找到一個之後就會停止
//         \d   ：代表 數字 (0-9)。
//         \D   ：代表 非數字 (0-9)。
//         [^  ]：代表 除了... 以外。
//         ^    ：代表 開頭為...
//         */
//     }

//     if(e.target.value.length === maxlength){
//         //nextOne.value.focus();  因為nextOne在目前的情況，有可能是 陣列裡的某一個 或 單獨的 Ref，所以這樣寫會有 bug，跑不動

//         // step1 先確認 nextOne 真的存在 (避免最後一格傳 null 進來報錯)
//         if(nextOne){
//             // step2 判斷它是「陣列裡的某一個」還是「單獨的 Ref」
//             // nextOne.focus 代表 檢查有沒有這個功能 => 回傳函式本身 (True) 或 undefined (False)。
//             // nextOne.focus() 代表 現在立刻執行這個功能！ 
//             const target = nextOne.focus ? nextOne : nextOne.value;
//             // step3 程式保護，避免報錯，網頁會死掉
//             // ? : 萬一沒有就停在這邊，不要繼續執行（ JavaScript (ES2020)的語法糖 ）
//             target?.focus?.();
//         }
//     }
// };


// function goBack(e, previousOne){
//     if(e.target.value.length === 0){

//         // step1 先確認 nextOne 真的存在 (避免最後一格傳 null 進來報錯)
//         if(previousOne){

//             // step2 判斷它是「陣列裡的某一個」還是「單獨的 Ref」
//             const target = previousOne.focus ? previousOne : previousOne.value;
//             // step3 程式保護，避免報錯，網頁會死掉
//             target?.focus?.();
//         }
//     }
// }
//#endregion

// 付款方式 ----------------------------------------------
const paymentInfo = ref('creditCard');


// 運送資訊 ----------------------------------------------
const deliveryInfo = ref('homeDelivery');


// 電話號碼檢核 ----------------------------------------------
function checkNumber(e){
    const val = e.target.value
        .replace(/[^0-9+\-]/g, '')   // step1. 過濾非數字、+、-
        .replace(/(?!^)\+/g, '')     // step2. 過濾非開頭 (?!^) 的 +
        .replace(/-{2,}/g, '-');     // step3. 過濾連續出現2次或以上 -{2,} 的 -

    e.target.value = val;
};


// 地址下拉選單  ----------------------------------------------
const selectCity = ref('');
const selectDist = ref('');

// 依照城市選區域
const currentDist = computed(() => {
    //如果使用者還沒選城市，就不用先跑一遍
    //if(!selectCity){return []};
    if( !selectCity.value ) return [];
    //找出對應的 dist
    const city = addrstore.addrTaiwan.find((thiscity) => thiscity.name_zh === selectCity.value);
    //return city;  這樣回傳的是一整個物件，不是 districts
    return city ? city.districts : [];
});


//如果城市改變，區域就清空
watch ( selectCity, () => {
  selectDist.value = '';
});

//需要存進orderList的資料  ----------------------------------------------
const receiptName = ref('');
const receiptPhone = ref('');
const receiptAddr = ref('');         //只記錄使用者填寫的部分，完整的地址需要組合下拉選單
const receiptRemark = ref('');
const saveAddr = ref(false);         // 是否儲存為常用地址



// 完整地址需要組合
const finalAddr = computed(()=>{
    // pinia 通常會自動解包，所以不用.value
    if(cartstore.selectCountry !=='taiwan'){
        // 國外：國家＋地址 
        return `${cartstore.selectCountry}-${receiptAddr.value||''}`;  
        // 因為${}是JS，所以如果是 undefined 或 null 會被同步呈現出來，所以要用||''來取代。
        // {{}} 是 vue，就不會有${}的問題，所以不用寫   
    }else{
        // 台灣：國家＋城市＋區域＋地址
        const city = selectCity.value || '';
        const dist = selectDist.value || '';
        const detail = receiptAddr.value || '';
        return `${city}${dist}${detail}`;
    }
})


// 確認訂單 ---------------------------
// 訂單寫入資料庫、寫入後將資料傳給綠界、沒問題後在前往訂單完成頁

const errorMsg = ref({});   // 用來存 php 回傳的錯誤訊息

// 設定環境變數
const apiBase = import.meta.env.VITE_API_BASE;
const apiURL = `${apiBase}/createOrder.php`;

async function goOrder(){
    //step0 如果有前次錯誤訊息，先清空
    errorMsg.value = {};

    // step1 再次檢查購物車有沒有商品（防護用，因為如果購物車沒商品，應該無法點選按鈕執行goOrder）
    if(cartstore.cartList.length === 0){
        return alert(t('shoppingcart.cartEmpty'));      //return 是為了讓程式停在這，不要繼續往下跑，才不會報錯或是送出一張空的訂單
    }

    // step2 把資料打包
    const orderData = {
        //查詢用ID
        member_ID : cartstore.memberID,
        coupons_ID : cartstore.coupon_ID || null,   //預設，還沒抓

        //運費+運送方式
        country : cartstore.selectCountry,   //後端會用國家重新查一次運費
        shipping : deliveryInfo.value,

        //購物車內容
        cartItem : cartstore.cartList.map( item => ({
            product_ID : item.product_ID,
            quantity : item.qty,
        })),

        //收件資訊
        phone : receiptPhone.value,
        address_en : finalAddr.value,
        name_en : receiptName.value,
        remark : receiptRemark.value,

        //付款方式
        payment : paymentInfo.value,

        //前端計算的總額（只是為了參考，實際還是會用後端查詢到的資訊來計算）
        frontend_total : cartstore.finalPrice,
    };


    // step3 送給後端（發送請求）
    try{
        console.log('正在發送訂單...', orderData);   //console.log 可以同時傳入多個參數，用,隔開即可

        // 使用 POST 發送
        const response = await axios.post(
            apiURL, //API 路徑
            //'http://localhost/Formosoul/public/php/createOrder.php',
            orderData
        );

        // 判斷回傳狀態

        if(response.data.status === 'success'){
            //訂單成立
            //alert(`訂單建立成功！\n編號：${response.data.orderID}\n金額：NT$ ${response.data.real_total}\n即將前往付款頁面...`);

            // 清空購物車
            cartstore.clearCart();
            


            //測試環境避開綠界付款
            const isDev = import.meta.env.DEV;       // vite 內建變數： 開發環境 (npm run dev) = true   正式環境 (npm run build) = false
            const skipEcpay = false;       //測試環境想測金流，改成false，跳過金流 改成true

            if(isDev && skipEcpay){
                // 跳轉訂單成功畫面
                router.push({
                    name: 'OrderSuccess',
                });
            }else{

                // 拿訂單編號，跳轉綠界
                const payURL = `${apiBase}/ecpay.php?order_number=${response.data.orderID}`; 
                window.location.href = payURL;
            }


        
        }else{
            alert('訂單建立失敗：' + response.data.message);
        }

        //之後可以補上驗證失敗的欄位提示
        
    }catch(error){
        console.error('連線錯誤:', error);
        alert('系統發生錯誤，請檢查網路或聯絡管理員');
    }    
}



// 語系切換  ----------------------------------------------
    const langList = {
        'en-US': 'en',
        'zh-TW': 'zh'
    };

    const lang = computed( () => {
        return langList[locale.value] || 'en';
    });



</script>

<template>
    <section class="check-dock">
        <!-- 內容 -->
        <section class="my-cart-dock">

            <OrderList 
                :showCart="false"
                :showTitle="false"
                :showTotal="false"
            ></OrderList>
            
        </section>
        <!-- 付款 -->
        <section class="payment-total dp-flex">
            <div class="check-payment">
                <h5>{{$t('shoppingcart.paymentShipping')}}</h5>
                <hr>
                <p>{{$t('shoppingcart.country')}}</p>
                <nav class="nav-payment-total">
                    <font-awesome-icon class="nav-icon" icon="fa-solid fa-angle-down" /> 
                    <select class="nav-list fw200" v-model="cartstore.selectCountry"> <!-- 因為 option 的 value 讀到的為字串，所以要 .number 變成數字，加法才不會出問題-->
                        <option class="list-option" value="taiwan">{{$t('shoppingcart.taiwan')}}</option>
                        <option class="list-option" value="japan">{{$t('shoppingcart.japan')}}</option>
                        <option class="list-option" value="singapore">{{$t('shoppingcart.singapore')}}</option>
                        <option class="list-option" value="netherlands">{{$t('shoppingcart.netherlands')}}</option>
                        <option class="list-option" value="ireland">{{$t('shoppingcart.ireland')}}</option>
                    </select>
                </nav>
                <p>{{$t('shoppingcart.delivery')}}</p>
                <nav class="nav-payment-total">
                    <font-awesome-icon class="nav-icon" icon="fa-solid fa-angle-down" /> 
                    <select class="nav-list fw200" v-model="deliveryInfo">
                        <option class="list-option" value="homeDelivery">{{$t('shoppingcart.homeDelivery')}}</option>
                    </select>
                </nav>
                <p>{{$t('shoppingcart.payment')}}</p>
                <nav class="nav-payment-total">
                    <font-awesome-icon class="nav-icon" icon="fa-solid fa-angle-down" /> 
                    <select class="nav-list fw200" v-model="paymentInfo">
                        <option class="list-option" value="creditCard">{{$t('shoppingcart.card')}}</option>
                        <option class="list-option" value="applePay">{{$t('shoppingcart.applePAY')}}</option>
                    </select>
                </nav>
            </div>
            <div class="check-total">
                <h5>{{$t('shoppingcart.totalDetails')}}</h5>
                <hr>

                <div class="check-total-dock">
                    <div class="check-price dp-flex">
                        <p>{{$t('shoppingcart.price')}}：</p>
                        <p>NT$ {{cartstore.totalPrice}}</p>
                    </div>
                    <div class="check-shippingfee dp-flex">
                        <p>{{$t('shoppingcart.shippingFee')}}：</p>
                        <p>NT$ {{cartstore.shippingFee}}</p>
                    </div>
                    <div class="check-discount dp-flex">
                        <p>{{$t('shoppingcart.discount')}}：</p>
                        <p>－ NT$ {{cartstore.discount}}</p>
                    </div>
                    <hr>
                    <div class="check-total-payment dp-flex">
                        <h5>{{$t('shoppingcart.total')}}：</h5>
                        <h5>NT$ {{cartstore.finalPrice}}</h5>
                    </div>
                </div>
                <BasicButton class="btn-blue-fill btn-fix-width btn-coupon" @click="couponopen">
                    {{$t('shoppingcart.btn-selectCoupon')}}
                </BasicButton>
                <Teleport v-if="hasCoupon" to="body">

                    <div class="coupon-overlay dp-flex"  @click.self="couponclose">
                        <div class="coupon-dock">
                            <font-awesome-icon class="coupon-close" icon="fa-solid fa-xmark" @click="couponclose"/>
                            <Couponcontaion isrows></Couponcontaion>
                            <p class="coupon-tips">{{$t('shoppingcart.couponTips')}}</p>
                            <div class="btn-coupon-use  dp-flex ">
                                <BasicButton class="btn-gray-fill" @click="couponclose">
                                    {{$t('shoppingcart.couponNouse')}}
                                </BasicButton>
                                <BasicButton class="btn-yellow-fill" @click="couponuse">
                                    {{$t('shoppingcart.couponuse')}}
                                </BasicButton>
                            </div>
                        </div>
                    </div>
                </Teleport>

            </div>
        </section>

        
        <!-- 表單 -->
        <form action="" method="POST" @submit.prevent="goOrder">
            <!-- @submit.prevent="goOrder" 阻止預設行為並且執行goOrder -->
            <!-- 信用卡資料 -->
            <section v-if="false" class="creditcard-info">
            <!-- <section v-if="paymentInfo === 'creditCard'" class="creditcard-info"> -->
                <h5>{{$t('shoppingcart.creditCard')}}<span class="fw200"> （ VISA / MASTER / JCB ）</span></h5>
                <hr>  
                <div class="card-dock dp-flex">
                    <div class="card-left dp-flex-col">

                        <div class="card-num">
                            <p>{{$t('shoppingcart.cardNumber')}}</p>
                            <!-- 改用 v-for 產生輸入框，方便接下來的輸入框跳轉 -->
                            <span v-for="(num, index) in 4">
                                <input class="input-text" type="text" maxlength="4" required
                                        ref="cardNum" 
                                        @input="goNext($event, 4, ( index < 3 ? cardNum[index + 1] : cardMonth))"
                                        @keydown.delete="goBack($event, ( index > 0 ? cardNum[index - 1] : cardNum[0]))"
                                        >  <!-- maxlength 限制只能填4個
                                                $event 因為還要傳遞 index 值，所以不能省略-->
                                <span v-if="index < 3">－</span>
                            </span>
                        </div>

                        <div class="card-date-type dp-flex">
                            <div class="card-date">
                                <p>{{$t('shoppingcart.expirationDate')}}</p>

                                <input ref="cardMonth" class="input-text" type="text" placeholder="mm" maxlength="2" required
                                        @input="goNext($event, 2, cardYear)"
                                        @keydown.delete="goBack($event, cardNum[3])">
                                ／<input ref="cardYear" class="input-text" type="text" placeholder="yy" maxlength="2" required
                                        @input="goNext($event, 2, cardCode)"
                                        @keydown.delete="goBack($event, cardMonth)">
                            </div>
    
                            <div class="card-type">
                                <font-awesome-icon class="icon-card" icon="fa-brands fa-cc-visa" />
                                <font-awesome-icon class="icon-card" icon="fa-brands fa-cc-mastercard" />
                                <font-awesome-icon class="icon-card" icon="fa-brands fa-cc-jcb" />
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="card-right dp-flex-col">
                        <div class="card-code dp-flex">
                            <p>{{$t('shoppingcart.securityCode')}}</p>
                            <input ref="cardCode" class="input-text" type="text" maxlength="3" required
                                    @input="goNext($event, 3, cardName)"
                                    @keydown.delete="goBack($event, cardYear)">
                        </div>
                        <div class="card-name">
                            <p>{{$t('shoppingcart.authorized')}}</p>
                            <input ref="cardName" class="input-text" type="text" required
                                    @input="goNext($event, 0, )"
                                    @keydown.delete="goBack($event, cardCode)">
                        </div>
                        
                    </div>

                </div>

            </section> 

            <!-- 收件資料 -->
            <section class="received-info">
                <h5>{{$t('shoppingcart.recipientInformation')}}</h5>
                <hr>
                <div class="received-info-dock">

                    <div class="received-name-phone dp-flex">
                        <div class="received-name">
                            <p>{{$t('shoppingcart.name')}}</p>
                            <input v-model="receiptName" class="input-text" type="text" required>
                        </div>
                        <div class="received-phone">
                            <div v-if="cartstore.selectCountry !== 'taiwan'">
                                <p>{{$t('shoppingcart.phoneNumber')}}<span>{{$t('shoppingcart.phoneEx')}}</span></p>
                                <input v-model="receiptPhone" class="input-text" type="text" @input="checkNumber" maxlength="12"required> <!--因為資料庫欄位設定12碼，所以這邊也先鎖12碼，避免報錯-->
                            </div>
                            <div v-else>
                                <p>{{$t('shoppingcart.phoneNumber')}}</p>
                                <input v-model="receiptPhone" class="input-text" type="text" @input="checkNumber"  placeholder="0912-345678" maxlength="11" required>
                            </div>
                        </div>
                    </div>
                    <div class="received-address">
                        <div v-if="cartstore.selectCountry !== 'taiwan'">
                            <p>{{$t('shoppingcart.address')}}</p>
                            <input v-model="receiptAddr" class="input-text" type="text" required>
                        </div>

                        <div v-else class="country-taiwan ">
                            <p>{{$t('shoppingcart.address')}}</p>
                            <div class="nav-addr-dock dp-flex">
                                <nav class="nav-addr">
                                    <font-awesome-icon class="nav-icon" icon="fa-solid fa-angle-down" /> 
                                    <select class="nav-list fw200" v-model="selectCity" required>
                                        <option class="list-option" disabled value="">{{$t('shoppingcart.city')}}</option>
                                        <option class="list-option" v-for="city in addrstore.addrTaiwan" :key="city.name_zh" :value="city.name_zh">{{ city[`name_${lang}`]}}</option>
                                    </select>
                                </nav>
                                <nav class="nav-addr">
                                    <font-awesome-icon class="nav-icon" icon="fa-solid fa-angle-down" /> 
                                    <select class="nav-list fw200" v-model="selectDist" required>
                                        <option class="list-option"  disabled value="">{{$t('shoppingcart.dist')}}</option>
                                        <option class="list-option"  v-for="dist in currentDist" :key="dist.name_zh" :value="dist.name_zh">{{ dist[`name_${lang}`] }}</option>
                                    </select>
                                </nav>
                            </div>
                            
                            <input v-model="receiptAddr" class="input-text" type="text" required>
                        </div>
                    </div>

                    <label class="checkbox-dock dp-flex">
                        <input v-model="saveAddr" class="input-checkbox" type="checkbox" name="" id="">
                        <span>{{$t('shoppingcart.record')}}</span>
                    </label>

                    <textarea
                    v-model="receiptRemark" 
                    class="input-text input-textarea " 
                    :placeholder="$t('shoppingcart.remark')" 
                    type="textarea"
                    rows="3"
                    ></textarea>
    
                </div>
    
    
            </section>

            <!-- order -->
            <div>  <!-- 這邊用@click的話，會直接執行，表單的sumbit的檢查必填功能會被跳過，所以要改寫在表單的submit功能的預設事件後執行想執行的動作 -->
                <BasicButton class="btn-yellow-fill btn-fix-width btn-order" type="submit">
                    {{$t('shoppingcart.order')}}
                </BasicButton>
            </div>
        </form>
    
    </section>    
    
</template>

<style scoped lang="scss">

    // 訂單明細
    .my-cart-dock{
        box-shadow: 
            inset 0 2px 6px rgba(255,255,255,0.05),
            0 0px 10px rgba(0,0,0,0.5);
        backdrop-filter: blur(70px);  
        border-radius: 10px;    
    }

    //背景設定
    .check-payment, .check-total, .creditcard-info, .received-info{
        box-shadow: 
        inset 0 2px 6px rgba(255,255,255,0.05),
        0 0px 10px rgba(0,0,0,0.5);
        backdrop-filter: blur(70px);  
        border-radius: 10px;    
        margin-top: 40px;
        padding: 40px;
    }

    //標題設定
    .check-payment>h5, .check-total>h5, .creditcard-info>h5, .received-info>h5{
        text-align: center;
    }
    
    //payment&total
    .payment-total{gap: 40px;}
    .check-payment{flex-grow: 2;}
    .check-total{flex-grow: 1;}


    // payment 選單--------------------------------
    .check-payment>p{
        padding: 20px 20px 12px;    
    }

    .nav-payment-total{
        // 新增自訂義箭頭
        position: relative;
        padding: 0 20px;
    }

    .nav-list{
        color: $color-fsWhite;
        background-color: transparent;
        font-size: 20px;
        font-family: 'Ubuntu','Roboto', 'Noto Sans TC', sans-serif;
        text-align: center;
        width: 100%;
        height: 40px;
        cursor: pointer;

        border: none;
        box-shadow: 0 0 5px rgba(255, 255, 255, 0.5) ;
        border-radius: 10px;

        // 移除預設下拉箭頭
        appearance: none;

        & .list-option{
            background-color: $color-fsTitle;
            color: $color-fsWhite;
        }
    }

    .nav-list:focus {
        outline: none;
        border: 0.5px solid $color-fsGold;
    }

    .nav-icon{
        position: absolute;
        right: 28px;
        top:0;
        bottom: 0;
        margin: auto 0;

        //箭頭會被穿透，不會擋住 select 下拉點擊
        pointer-events: none;
    }


    //total
    .check-total{
        position: relative;
    }
    .check-price, .check-discount, .check-shippingfee, .check-total-payment{
        justify-content: space-between;
        padding: 0 20px;
        height: 52px;
        align-items: center;
    }

    .check-total-payment{
        color: $color-fsGold;
    }

    .btn-coupon{
        position: absolute;
        bottom: 40px;
        left: 0;
        right: 0;
        margin: auto;   
    }

    .coupon-overlay{
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 100;
        backdrop-filter: blur(1.5px); 
        justify-content: center;
        align-items: center;
    }

    .coupon-dock{
        position: relative;
        width: 50vw;
        //height: 80vh;
        background-color: $color-fsBlue50;
        border-radius: 10px;
        outline: 10px solid $color-fsBlue50;
        border: 1px solid $color-fsTitle;
        box-shadow: 0 0 50px;
        // padding: 20px;
        padding: 36px 20px 20px 20px;

        & .coupon-close{
            color: $color-fsTitle;
            font-size: 20px;
            position: absolute;
            right: 12px;
            top: 12px;
            cursor: pointer;
        }

        & .coupon-tips{
            text-align: center;
            color: $color-fsRed;
        }

        & .btn-coupon-use{
            justify-content: space-around;
            margin: 20px auto 0;
        }
    }


    //creditcard
    .creditcard-info>h5>span {
        font-size: 1.6rem;
    }

    .card-dock{
        padding: 50px 20px 30px;
        gap: 20px;
        flex-shrink: 0;
        justify-content: space-around;
    }

    .card-left, .card-right{
        background-color: $color-fsContent;
        min-width: 360px;
        width: 40%;
        aspect-ratio: 3/1.8;
        border-radius: 20px;
        padding: 28px;   
        justify-content: space-around;
    }

    .card-left input, .card-right input{
        width: 60px;
        padding: 4px 4px;
        text-align: center;
        height: 32px;
        font-size: 1.6rem;
        background-color: transparent;
        border: none;
    }

    .card-num{
        border-bottom: 0.5px solid $color-fsWhite;
        margin: 8px 0 16px;
        text-align: center;
        & >span{
            span{
                margin: 0 4px;
            }
        }
    }

    .card-date-type{
        justify-content: space-between;
        align-items: end;
        margin: 8px 0;
    }

    .icon-card{
        font-size: 2.8rem;
    }

    .card-date, .card-name{
        border-bottom: 0.5px solid $color-fsWhite;
    }
    
    .card-code{
        justify-content: space-between;
        margin: 36px 0 16px;
    }

    .card-code input{
        background-color: $color-fsWhite;
        border: 1px solid $color-fsCaption;
        width: 60%;
        //min-width: 150px;
    }
    
    .card-name input{
        width: 100%;
    }


    //received

    .received-info-dock{
        padding: 0 20px;
    }
    .received-name-phone{
        gap: 20px;
        padding-top: 20px;
    }

    .received-name, .received-phone{
        flex-basis: 0;
        flex-grow: 1;
    }

    .received-phone{
        & span{
            font-size: 12px;
            font-weight: 200;
            margin-left: 8px;
        }
    }

    .received-address{
        padding-top: 20px;

        & .nav-addr-dock{
            gap: 20px;
            margin-bottom: 10px;
        }

        & .nav-addr{
            width: 50%;
            position: relative;
            
        }
    }

    .received-address>input:last-of-type{
        flex-grow: 1;
    }

    .checkbox-dock{
        align-items: center;
        padding-top: 20px;
    }
    
    .input-checkbox{
        line-height: 160%;
        font-size: 1.2rem;
    }

    .input-textarea{
        height: auto;
        margin-top: 40px;
    }

    //order
    .btn-order{
        display: block;
        margin: 100px auto 0;
    }

    // RWD------------------------------------- 
    @media screen and (max-width: 1366px) {
        .card-left, .card-right{
            padding: 20px;   
        }
    }

</style>
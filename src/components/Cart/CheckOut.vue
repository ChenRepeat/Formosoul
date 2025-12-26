<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import BasicButton from '../../components/BasicButton.vue';
import { useRouter } from 'vue-router';
import OrderList from '../OrderList.vue';
import { ref } from 'vue'

const router = useRouter();

function goOrder(){
    router.push('/shoppingcart/ordersuccess');
}

// 信用卡填完跳轉下一格
const cardNum = ref([]);
const cardMonth = ref(null);
const cardYear = ref(null);
const cardCode = ref(null);
const cardName = ref(null);

/*
//這個寫法要寫好幾個function來進行跳轉
function nextInput( e, index ){
    //檢查內容是否為數字

    //檢查內容的長度
    if(e.target.value.length === 4 ){    
        //檢查是否為最後一格
        if(index < 3){
            cardNum.value[ index + 1 ].focus();
        }else{
            cardMonth.value.focus();
        }
    }
}
*/

//用一個 function 統一處理跳轉
function goNext( e, maxlength, nextOne ){

    // 先過濾輸入的內容是否為數字，但是排除姓名欄
    // 因為在目前情況下，剛好有限制長度的欄位皆為數字，所以一定會輸入限制長度，而長度必大於0，所以可以用這件事來進行排除
    if( maxlength > 0 ){
        //e.target.value = e.target.value.replace(/[^\d]/g, '');
        e.target.value = e.target.value.replace(/\D/g, '');
        // replace() 是字串的方法，因為 input 的內容會被視為字串傳回來，所以可以使用字串的方法
        // replace(舊值, 取代舊值的新值)

        /*
        /[^\d]/g 正則表達式
        /  / ：中間寫規則
        g    ：修飾符，代表規則的備註 / 設定 => g代表全部都要找出來，如果沒有 g，找到一個之後就會停止
        \d   ：代表 數字 (0-9)。
        \D   ：代表 非數字 (0-9)。
        [^  ]：代表 除了... 以外。
        ^    ：代表 開頭為...
        */
    }

    if(e.target.value.length === maxlength){
        //nextOne.value.focus();  因為nextOne在目前的情況，有可能是 陣列裡的某一個 或 單獨的 Ref，所以這樣寫會有 bug，跑不動

        // step1 先確認 nextOne 真的存在 (避免最後一格傳 null 進來報錯)
        if(nextOne){
            // step2 判斷它是「陣列裡的某一個」還是「單獨的 Ref」
            // nextOne.focus 代表 檢查有沒有這個功能 => 回傳函式本身 (True) 或 undefined (False)。
            // nextOne.focus() 代表 現在立刻執行這個功能！ 
            const target = nextOne.focus ? nextOne : nextOne.value;
            // step3 程式保護，避免報錯，網頁會死掉
            // ? : 萬一沒有就停在這邊，不要繼續執行（ JavaScript (ES2020)的語法糖 ）
            target?.focus?.();
        }
    }
};


function goBack(e, previousOne){
    if(e.target.value.length === 0){

        // step1 先確認 nextOne 真的存在 (避免最後一格傳 null 進來報錯)
        if(previousOne){

            // step2 判斷它是「陣列裡的某一個」還是「單獨的 Ref」
            const target = previousOne.focus ? previousOne : previousOne.value;
            // step3 程式保護，避免報錯，網頁會死掉
            target?.focus?.();
        }
    }
}


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
                    <select class="nav-list fw200">
                        <option class="list-option">{{$t('shoppingcart.taiwan')}}</option>
                        <option class="list-option">{{$t('shoppingcart.japan')}}</option>
                        <option class="list-option">{{$t('shoppingcart.singapore')}}</option>
                        <option class="list-option">{{$t('shoppingcart.netherlands')}}</option>
                        <option class="list-option">{{$t('shoppingcart.ireland')}}</option>
                    </select>
                </nav>
                <p>{{$t('shoppingcart.delivery')}}</p>
                <nav class="nav-payment-total">
                    <font-awesome-icon class="nav-icon" icon="fa-solid fa-angle-down" /> 
                    <select class="nav-list fw200">
                        <option class="list-option">{{$t('shoppingcart.homeDelivery')}}</option>
                    </select>
                </nav>
                <p>{{$t('shoppingcart.payment')}}</p>
                <nav class="nav-payment-total">
                    <font-awesome-icon class="nav-icon" icon="fa-solid fa-angle-down" /> 
                    <select class="nav-list fw200">
                        <option class="list-option">{{$t('shoppingcart.card')}}</option>
                        <option class="list-option">{{$t('shoppingcart.applePAY')}}</option>
                    </select>
                </nav>
            </div>
            <div class="check-total">
                <h5>{{$t('shoppingcart.totalDetails')}}</h5>
                <hr>

                <div class="check-total-dock">
                    <div class="check-price dp-flex">
                        <p>{{$t('shoppingcart.price')}}：</p>
                        <p>NT$ 380</p>
                    </div>
                    <div class="check-discount dp-flex">
                        <p>{{$t('shoppingcart.discount')}}：</p>
                        <p>－ NT$ 60</p>
                    </div>
                    <div class="check-shippingfee dp-flex">
                        <p>{{$t('shoppingcart.shippingFee')}}：</p>
                        <p>NT$ 80</p>
                    </div>
                    <hr>
                    <div class="check-total-payment dp-flex">
                        <h5>{{$t('shoppingcart.total')}}：</h5>
                        <h5>NT$ 400</h5>
                    </div>
                </div>
                <BasicButton class="btn-blue-fill btn-fix-width btn-coupon">
                    {{$t('shoppingcart.btn-selectCoupon')}}
                </BasicButton>

            </div>
        </section>

        
        <!-- 表單 -->
        <form action="" method="POST" @submit.prevent="goOrder">
            <!-- @submit.prevent="goOrder" 阻止預設行為並且執行goOrder -->
            
            <!-- 信用卡資料 -->
            <section class="creditcard-info">
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

                            <!-- <input class="input-text" type="text" required>
                            －<input class="input-text" type="text" required>
                            －<input class="input-text" type="text" required>
                            －<input class="input-text" type="text" required> -->
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
                            <input class="input-text" type="text" required>
                        </div>
                        <div class="received-phone">
                            <p>{{$t('shoppingcart.phoneNumber')}}</p>
                            <input class="input-text" type="text" required>
                        </div>
                    </div>
                    <div class="received-address dp-flex">
                        <div>
                            <p>{{$t('shoppingcart.address')}}</p>
                            <input class="input-text" placeholder="Country" type="text" required>
                        </div>
                        <div>
                            <input class="input-text" type="text" placeholder="City" required>
                        </div>
                        <input class="input-text" type="text" required>
                    </div>

                    <label class="checkbox-dock dp-flex">
                        <input class="input-checkbox" type="checkbox" name="" id="">
                        <span>{{$t('shoppingcart.record')}}</span>
                    </label>

                    <textarea 
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

    .received-address{
        align-items: end;
        gap: 20px;
        padding-top: 20px;
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
    @media screen and (max-width: 1200px) {
        .card-left, .card-right{
            padding: 20px;   
        }
    }

</style>
<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import BasicButton from '../../components/BasicButton.vue';
import { useRouter } from 'vue-router';
import OrderList from '../OrderList.vue';

const router = useRouter();

function goOrder(){
    router.push('/shoppingcart/ordersuccess');
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
                        <option class="list-option" selested>{{$t('shoppingcart.taiwan')}}</option>
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
                        <option class="list-option" selested>{{$t('shoppingcart.homeDelivery')}}</option>
                    </select>
                </nav>
                <p>{{$t('shoppingcart.payment')}}</p>
                <nav class="nav-payment-total">
                    <font-awesome-icon class="nav-icon" icon="fa-solid fa-angle-down" /> 
                    <select class="nav-list fw200">
                        <option class="list-option" selested>{{$t('shoppingcart.card')}}</option>
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
                    <div class="card-left">

                        <div class="card-num">
                            <p>{{$t('shoppingcart.cardNumber')}}</p>
                            <input class="input-text" type="text" required>
                            －<input class="input-text" type="text" required>
                            －<input class="input-text" type="text" required>
                            －<input class="input-text" type="text" required>
                        </div>

                        <div class="card-date-type dp-flex">
                            <div class="card-date">
                                <p>{{$t('shoppingcart.expirationDate')}}</p>
                                <input class="input-text" type="text" placeholder="mm" required>
                                ／<input class="input-text" type="text" placeholder="yy" required>
                            </div>
    
                            <div class="card-type">
                                <font-awesome-icon class="icon-card" icon="fa-brands fa-cc-visa" />
                                <font-awesome-icon class="icon-card" icon="fa-brands fa-cc-mastercard" />
                                <font-awesome-icon class="icon-card" icon="fa-brands fa-cc-jcb" />
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="card-right">
                        <div class="card-code dp-flex">
                            <p>{{$t('shoppingcart.securityCode')}}</p>
                            <input class="input-text" type="text" required>
                        </div>
                        <div class="card-name">
                            <p>{{$t('shoppingcart.authorized')}}</p>
                            <input class="input-text" type="text" required>
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
        padding: 20px 20px 0;
        gap: 20px;
        flex-shrink: 0;
        justify-content: space-around;
    }

    .card-left, .card-right{
        background-color: $color-fsContent;
        width: 360px;
        aspect-ratio: 3/1.8;
        border-radius: 20px;
        padding: 20px;   
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
        width: 150px;
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

</style>
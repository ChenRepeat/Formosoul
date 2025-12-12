<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import BasicButton from '../../components/BasicButton.vue';
import { RouterLink } from 'vue-router';

</script>

<template>
    <section class="check-dock">
        <!-- 內容 -->
        <section class="my-cart-dock">
            <h5>Order Details</h5>
            <div class="orderdetail-total dp-flex-col">
                <div class="total-price dp-flex fw200">
                    <h6>Price：</h6>
                    <h6>NT$ 380</h6>
                </div>

                <div class="total-qty dp-flex fw200">
                    <h6>Quantity：</h6>
                    <h6>2</h6>
                    <h6>item(s)</h6>
                </div>
            </div>
            
            <div class="orderdetail-body dp-flex">
                <div class="item-image">
                    <img src="../../../public/Shop/2.png" alt="">
                </div>
                <h6 class="item-name">Bamboo Helicopter</h6>
                <div class="item-qty dp-flex">
                    <h6>1</h6>
                    <h6>item(s)</h6>
                </div>
                <h6 class="item-price">NT$ 190</h6>
                
                
            </div>
            <hr class="item-bar">

            <div class="orderdetail-body dp-flex">
                <div class="item-image">
                    <img src="../../../public/Shop/2.png" alt="">
                </div>
                <h6 class="item-name">Bamboo Helicopter</h6>
                <div class="item-qty dp-flex">
                    <h6>1</h6>
                    <h6>item(s)</h6>
                </div>
                <h6 class="item-price">NT$ 190</h6>
                
                
            </div>
            <hr class="item-bar">

            <p class="warning-text fw200">Import duties are charged once the parcel reaches its destination country. These charges must be paid by the recipient of the parcel. We have no control over these charges and we can’t tell you what the cost would be, as customs policies and import duties vary widely from country to country. It might be a good idea to contact your local customs office for more information on the current charges.</p>
  
        </section>
        <!-- 付款 -->
        <section class="payment-total dp-flex">
            <div class="check-payment">
                <h5>Payment & Shipping</h5>
                <hr>
                <p>Country</p>
                <nav class="nav-payment-total">
                    <font-awesome-icon class="nav-icon" icon="fa-solid fa-angle-down" /> 
                    <select class="nav-list fw200">
                        <option class="list-option" selested>Taiwan</option>
                        <option class="list-option">Japan</option>
                        <option class="list-option">Singapore</option>
                        <option class="list-option">The Netherlands</option>
                        <option class="list-option">Ireland</option>
                    </select>
                </nav>
                <p>Delivery</p>
                <nav class="nav-payment-total">
                    <font-awesome-icon class="nav-icon" icon="fa-solid fa-angle-down" /> 
                    <select class="nav-list fw200">
                        <option class="list-option" selested>Home Delivery</option>
                    </select>
                </nav>
                <p>Payment</p>
                <nav class="nav-payment-total">
                    <font-awesome-icon class="nav-icon" icon="fa-solid fa-angle-down" /> 
                    <select class="nav-list fw200">
                        <option class="list-option" selested>Credit Card</option>
                        <option class="list-option">Apple PAY</option>

                    </select>
                </nav>
            </div>
            <div class="check-total">
                <h5>Total Details</h5>
                <hr>

                <BasicButton class="btn-blue-fill btn-fix-width">
                    Select COUPON
                </BasicButton>
            </div>
        </section>
        <!-- 收件資料 -->
        <section class="creditcard-info">
            <h5>Credit Card Information <span class="fw200"> （ VISA / MASTER / JCB ）</span></h5>
            <hr>
            
            
        </section>
          
        <!-- 收件資料 -->
        <section class="received-info">
            <h5>Recipient Information</h5>
            <hr>


        </section>
    
        <!-- order -->
        <div class="btn-order">  
            <BasicButton class="btn-yellow-fill btn-fix-width">
                ORDER
            </BasicButton>
        </div>
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
        margin-top: 40px;
        padding: 60px 40px 80px;
        text-align: center;
    }

    .my-cart-dock>h5{
        margin-bottom: 60px;
    }

    .orderdetail-total{
        border: 0.5px solid $color-fsWhite;
        margin-bottom: 20px;
    }

    .total-price, .total-qty {
        justify-content: center;
        margin: 8px 0;
    }

    .total-price>h6:last-of-type{
        color: $color-fsRed;
    }

    .total-qty>h6:nth-of-type(2){
        margin-right: 8px;
    }





    //body
    .orderdetail-body{
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
        text-align: center;
        margin-right:8px;
    }

    .warning-text{
        margin-top: 60px;
        color: $color-fsCaption;
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

    //payment
    .check-payment>p{
        padding: 20px 20px 12px;    
    }
    



    // payment 選單--------------------------------
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

        // 讓文字在空白處置中
        padding-right: 18px;
    }

    .nav-list:focus {
        outline: none;
        border: 0.5px solid $color-fsGold300;

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



    //creditcard
    .creditcard-info>h5>span {
        font-size: 1.6rem;
    }

    //received



    //order
    .btn-order{
        margin-top: 100px;
    }

</style>
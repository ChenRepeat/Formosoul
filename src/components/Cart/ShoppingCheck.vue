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

                <div class="check-total-dock">
                    <div class="check-price dp-flex">
                        <p>Price：</p>
                        <p>NT$ 380</p>
                    </div>
                    <div class="check-discount dp-flex">
                        <p>Discount：</p>
                        <p>－ NT$ 60</p>
                    </div>
                    <div class="check-shippingfee dp-flex">
                        <p>Shipping Fee：</p>
                        <p>NT$ 80</p>
                    </div>
                    <hr>
                    <div class="check-total-payment dp-flex">
                        <h5>Total：</h5>
                        <h5>NT$ 400</h5>
                    </div>
                </div>
                <BasicButton class="btn-blue-fill btn-fix-width btn-coupon">
                    Select COUPON
                </BasicButton>

            </div>
        </section>

        
        <!-- 表單 -->
        <form action="" method="POST">
            
            <!-- 信用卡資料 -->
            <section class="creditcard-info">
                <h5>Credit Card<span class="fw200"> （ VISA / MASTER / JCB ）</span></h5>
                <hr>  
                <div class="card-dock dp-flex">
                    <div class="card-left">

                        <div class="card-num">
                            <p>Card Number</p>
                            <input class="input-text" type="text">
                            －<input class="input-text" type="text">
                            －<input class="input-text" type="text">
                            －<input class="input-text" type="text">
                        </div>

                        <div class="card-date-type dp-flex">
                            <div class="card-date">
                                <p>Expiration Date</p>
                                <input class="input-text" type="text" placeholder="mm">
                                ／<input class="input-text" type="text" placeholder="yy">
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
                            <p>Security code</p>
                            <input class="input-text" type="text">
                        </div>
                        <div class="card-name">
                            <p>AUTHORIZED SIGNATURE</p>
                            <input class="input-text" type="text">
                        </div>

                        
                    </div>

                </div>

            </section>

            <!-- 收件資料 -->
            <section class="received-info">
                <h5>Recipient Information</h5>
                <hr>
                <div class="received-info-dock">

                    <div class="received-name-phone dp-flex">
                        <div class="received-name">
                            <p>Name</p>
                            <input class="input-text" type="text" required>
                        </div>
                        <div class="received-phone">
                            <p>Phone Number</p>
                            <input class="input-text" type="text" required>
                        </div>
                    </div>
                    <div class="received-address dp-flex">
                        <div>
                            <p>Address</p>
                            <input class="input-text" placeholder="Country" type="text" required>
                        </div>
                        <div>
                            <input class="input-text" type="text" placeholder="City" required>
                        </div>
                        <input class="input-text" type="text" required>
                    </div>

                    <label class="checkbox-dock dp-flex">
                        <input class="input-checkbox" type="checkbox" name="" id="">
                        <span>Remember delivery address.</span>
                    </label>

                    <textarea 
                    class="input-text input-textarea " 
                    placeholder="Please leave a note here for any special requests regarding your purchase." 
                    type="textarea"
                    rows="3"
                    ></textarea>
    
                </div>
    
    
            </section>

            <!-- order -->
            <div>  
                <BasicButton 
                class="btn-yellow-fill btn-fix-width btn-order" 
                type="submit"
                >
                    ORDER
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
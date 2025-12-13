<template>
    <div class="coupon-list ">

        
        <div class="coupon dp-flex ">
            <div class="coupon-left coupon-click left-used">
                <p class="fw600">MAGIC FUN</p>
            </div>
            <div class="coupon-center center-used">
                <div>
                <h4 class="coupon-content content-used">$100 OFF</h4>
                <h4>COUPON</h4>
                <p class="coupon-valid">VAILD UNTIL DEC 25, 2026</p>
                </div>
            </div>
            
            <div class="coupon-right">
                <div><img src="../../../assets/LOGO_blackColor_coupon.svg" alt=""></div>
            </div>
        </div>

        <div class="coupon dp-flex">
            <div class="coupon-left coupon-click">
                <p class="fw600">MAGIC FUN</p>
            </div>
            <div class="coupon-center">
                <div>
                <h4 class="coupon-content">50% OFF</h4>
                <h4>COUPON</h4>
                <p class="coupon-valid">VAILD UNTIL DEC 25, 2026</p>
                </div>
            </div>
            
            <div class="coupon-right">
                <div><img src="../../../assets/LOGO_blackColor_coupon.svg" alt=""></div>
            </div>
        </div>

        <div class="coupon dp-flex">
            <div class="coupon-left">
                <p class="fw600">MAGIC FUN</p>
            </div>
            <div class="coupon-center">
                <div>
                <h4 class="coupon-content">50% OFF</h4>
                <h4>COUPON</h4>
                <p class="coupon-valid">VAILD UNTIL DEC 25, 2026</p>
                </div>
            </div>
            
            <div class="coupon-right">
                <div><img src="../../../assets/LOGO_blackColor_coupon.svg" alt=""></div>
            </div>
        </div>
    </div>
</template>

<script setup>
    
</script>

<style scoped lang="scss">
    .coupon-list{
        padding: 80px 120px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        row-gap: 32px;
        column-gap: 80px;
    }


    .coupon {
        min-width: 400px;
        width: 100%;
        height: 200px;
        border-radius: 10px;
        margin: auto;
        filter: drop-shadow(0 3px 5px rgba(0, 0, 0, 0.5));
        color: $color-fsTitle;
    }

    .coupon > div {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .coupon-left {
        border-radius: 10px 0 0 10px;   //左上 右上 右下 左下
        width: 20%;
        background-image: radial-gradient(
            circle at 0% 50%,
            transparent 25px,
            $color-fsGold 26px     
        );           //從圓心開始的25px都是透明，26到邊緣都是$color-fsGold
    }
    .coupon-left p {
        transform: rotate(-90deg);
        white-space: nowrap;
        font-size: 1.4rem;
    }

    .coupon-center {
        width: 50%;
        text-align: center;
        background-color: $color-fsGold;
        border-left: 1px dashed rgba(255, 255, 255, 0.7);
    }

    .coupon-content{
        background: $color-fsWhite;
        color: $color-fsGold;
        padding: 0 12px;
        white-space: nowrap;    //空格不斷行
    }

    .coupon-valid {
        font-size: 1rem;
        letter-spacing: 2px;
    }

    .coupon-right {
        border-radius: 0 10px 10px 0;   //左上 右上 右下 左下
        width: 30%;
        background-image: radial-gradient(
            circle at 100% 50%,
            transparent 25px,
            #fff 26px     
        );           //從圓心開始的25px都是透明，26到邊緣都是#fff
    }
    .coupon-right div {
        transform: rotate(-90deg);
    }
    
    //hover
    .coupon:hover{
        filter: drop-shadow(0 9px 10px rgba(0, 0, 0, 0.5));
        transform: translateY(-15px);
    }
    
    //click 當下
    .coupon-click{
        transform-origin: right bottom;
        transform: rotate(-10deg);
        border-right: 1px dashed rgba(255, 255, 255, 0.7);
    }
    
    //used＆過期
    .left-used{
        background-image: radial-gradient(
            circle at 0% 50%,
            transparent 25px,
            $color-fsCaption 26px     
        );           //從圓心開始的25px都是透明，26到邊緣都是$color-fsGold
        color: $color-fsContent;
    }

    .center-used{
        color: $color-fsContent;
        background-color: $color-fsCaption;
    }

    .content-used{
        color: $color-fsContent;
    }

    
</style>
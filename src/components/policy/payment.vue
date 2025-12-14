<template>
    <div class="wrapper">
        <div class="chinesedelivery">
            <p>▌台灣地區</p>
            <hr>
            
            <p>|線上刷卡|</p>
            <p>- 接受之信用卡:VISA、MasterCard、JCB。</p>
            <hr>

            <p>|線上刷卡分期合作之銀行|</p>
            <p>- 合作刷卡分期之銀行:上海、日盛、台北富邦、台新、台灣中小企銀、台灣新光、永豐、玉山、合作金庫、安泰、國泰世華、一銀、渣打、華南、華泰、陽信、萬泰(凱基)、彰化、遠東、聯邦商業銀行。</p>
            <hr class="big">
            
            <p>▌國際地區</p>
            <hr>

            <p>|線上刷卡|</p>
            <p>- 接受之信用卡:VISA、MasterCard、JCB、銀聯卡。</p>
        </div>
        <div class="engdelivery">
            <p>▌ Taiwan Payment</p>
            <hr>
            
            <p>|Credit Card Payments|</p>
            <p>- Accepted credit cards: VISA, MasterCard, and JCB.</p>
            <hr>

            <p>|Credit card installment plan with cooperated banks|</p>
            <p>- Cooperated banks: The Shanghai Commercial & Savings Bank, JihSun Bank, Taipei Fubon Bank, Tashin International Bank, Taiwan Business Bank, Shin Kong Bank, Bank SinoPac, E. Sun Bank, Taiwan Cooperative Bank, Entie Commercial Bank, Cathay United Bank, First Bank, Standard Chartered Bank, Hua Nan Bank, Hwatai Bank, Sunny Bank, KGI Bank, Chang Hwa Bank, Far Eastern International Bank, Union Bank of Taiwan.</p>
            <hr class="big">
            
            <p>▌Oversea Payment</p>
            <hr>

            <p>|Credit Card Payment|</p>
            <p>- Accepted Credit Card: VISA, MasterCard, JCB, UnionPay.</p>
        </div>
    </div>
</template>

<script setup>
    
</script>

<style lang="scss" scoped>
    .wrapper{
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 40px 100px 40px;
    }
    .chinesedelivery{
        padding-bottom: 60px;
    }
    .chinesedelivery > hr,
    .engdelivery > hr{
        border-color: transparent;

    }

    .big{
        margin: 24px;
    }
</style>
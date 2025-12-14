<template>
    <div class="wrapper">
        <div class="chinesedelivery">
            <p>▌運費計算方式</p>
            <hr>
            
            <p>|台灣地區|(包含離島)：</p>
            <p>- 以宅配通寄送，一律NT$80元。</p>
            <p>- 商品金額(含稅金)滿NT$2,000元免運費。</p>
            <p>- 不提供宅急便包裹收送服務區域：</p>
            <p>澎湖地區：望安鄉，七美鄉，虎井島，桶盤島，大倉嶼，員貝嶼，鳥嶼，花嶼，吉貝嶼</p>
            <p>金門地區：烏坵，列嶼，大膽、二膽。</p>
            <p>馬祖地區（連江縣）：南竿鄉，北竿鄉，莒光鄉，東引鄉。</p>
            <p>其他：蘭嶼，小琉球，綠島等外島及郵政信箱。</p>
            <hr>

            <p>|國際地區|</p>
            <p>- 中國地區僅提供公司戶地址寄送。</p>
            <p>- 國際配送由DPEX空運寄送。</p>
            <p>- 國際購買尚未提供滿額免運之服務。</p>
            <p>- 國際運費由購買商品之總重量計算，各地區皆不同。(於結帳畫面系統會自動計算運費)。</p>
        </div>
        <div class="engdelivery">
            <p>▌ Shipping Costs</p>
            <hr>
            
            <p>|Taiwan| (Included its offshore islands) </p>
            <p>- Delivered by Taiwan Pelican Express with NT$80 flat rate for order under NT$2000. </p>
            <p>- Free shipping for order over NT$2000. </p>
            <hr>

            <p>|International|</p>
            <p>- Air delivered by DPEX.</p>
            <p>- No free shipping for oversea.</p>
            <p>- The cost of oversea shipping is based upon the weight of the items you order. (shipping fee will be calculated automatically on the checkout page)</p>
            
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
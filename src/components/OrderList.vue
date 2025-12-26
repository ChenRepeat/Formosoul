<script setup>
import { ref } from "vue";

//商品陣列
const orderitems =ref([
    {
    product_ID: 'PE2025100010',
    type: 'Personalized',
    price: '80',
    stock: '150',
    status: 'Listed',
    createdate: '20251001',
    update: '20251203',
    name_en: 'Black Spell',
    name_zh: '符咒 (黑)',
    images: ['/tjd103/Shop/26.jpg'],
    description_en: 'Guards against fire and ensures kitchen safety.',
    description_zh: '鎮火災、保廚房平安。',
    story_en: 'Black corresponds to water; masters fire through water.',
    story_zh: '黑色屬水，以水克火，是家庭巫師必備。',
    use_en: 'Paste on walls near the kitchen or heat sources.',
    use_zh: '貼於廚房或火源附近的牆面上。',
    isLike: false
  },
  {
    product_ID: 'VO2025110002',
    type: 'Vouchers',
    price: '350',
    stock: '100',
    status: 'Listed',
    createdate: '20251101',
    update: '20251205',
    name_en: 'Pingxi Sky Lantern Blessing Voucher',
    name_zh: '平溪天燈祈福券',
    images: ['/tjd103/Shop/35.jpg'],
    description_en: 'Redeemable for one handmade extra-large sky lantern and a cinnabar brush for writing magical vows.',
    description_zh: '兌換一盞手作特大天燈，含硃砂筆一枝，用於書寫魔法誓詞。',
    story_en: 'Sky lanterns serve as messengers to the celestial realm. In the world of Formosoul, these lanterns are used to send graduation wishes to the stars.',
    story_zh: '天燈是通往天界與神靈溝通的信使。在Formosoul，天燈用於將畢業願望送往星辰。',
    use_en: 'Redeem at designated shops near Pingxi Station. Write down your wish and ignite the lantern under a professor\'s guidance. Observe its flight path to divine your fortune.',
    use_zh: '在平溪車站指定店鋪兌換，寫下願望後在教授指導下點火釋放。觀察上升路徑可預知運勢。',
    isLike: false
  },
  {
    product_ID: 'AC2025090004',
    type: 'Accessories',
    price: '320',
    stock: '80',
    status: 'Listed',
    createdate: '20250901',
    update: '20251210',
    name_en: 'Tiger God Embroidered Keychain',
    name_zh: '虎爺繡花鑰匙圈',
    images: ['/tjd103/Shop/32.jpg'],
    description_en: 'A hand-embroidered yellow tiger head, blending a plump, cute shape with a sense of divine majesty.',
    description_zh: '手工刺繡的黃色虎頭，圓滾滾造型中帶有神聖威嚴感。',
    story_en: 'Lord Tiger is a guardian deity in Taiwanese temples, protecting children and travelers. This item is blessed to \'bite wealth\' and avoid danger.',
    story_zh: '虎爺是台灣廟宇守護神，保護孩童與旅人。此飾品具備「咬錢」與「避險」的加持。',
    use_en: 'Hang on keys or bags. Pat the tiger\'s head three times when entering dangerous areas to gain temporary light-footedness and luck protection.',
    use_zh: '掛在鑰匙或背包。進出危險區域時輕拍虎頭三次，能獲得短暫的腳步輕盈與保護。',
    isLike: false
  },
]);    

// 接收父層相關設定
const props =

defineProps({
  showCart: {
    type: Boolean, // 資料類型
    default: true, // 預設值
  },
  showCheck: {
    type: Boolean, // 資料類型
    default: true, // 預設值
  },
  showTitle: {
    type: Boolean, // 資料類型
    default: true, // 預設值
  },
  showTotal: {
    type: Boolean, // 資料類型
    default: true, // 預設值
  }

})

// 商品數量增減.  ＊改用 pinia 管理
const qty = ref(1);

function qtyMinus(){
    if( qty.value > 1 ){
        qty.value--;
    }
};

function qtyPlus(product_ID){
    if( qty.value < orderitems.value.product_ID.stock ){
        qty.value++;
    }
};




// 商品刪除

</script>

<template>
    <section class="order-dock">

        <!-- 標題組件 -->
        <h5 class="order-title">{{$t('shoppingcart.orderDetail')}}</h5>

    
        <!-- check組件 -->
        <div 
            v-if="showCheck"
            class="orderdetail-total dp-flex-col"
            >
            <div class="total-price dp-flex fw200">
                <h6>{{$t('shoppingcart.price')}}：</h6>
                <h6>NT$ 380</h6>
            </div>
            <div class="total-qty dp-flex fw200">
                <h6>{{$t('shoppingcart.quantity')}}：</h6>
                <h6>{{orderitems.length}}</h6>
                <h6>{{$t('shoppingcart.items')}}</h6>
            </div>
        </div>


        <!-- 表頭組件 -->
        <div 
            v-if="showTitle"
            class="orderlist-head dp-flex fw200"
            >
            <div class="itemhead-image"></div>
            <h6 class="itemhead-name">{{$t('shoppingcart.productName')}}</h6>
            <h6 v-if="showCart" class="itemhead-qty">{{$t('shoppingcart.quantity')}}</h6>
            <h6 v-if="showCheck" class="itemhead-qty-check">{{$t('shoppingcart.quantity')}}</h6>
            <h6 v-if="showCart" class="itemhead-price">{{$t('shoppingcart.price')}}</h6>
            <h6 v-if="showCheck" class="itemhead-price-check">{{$t('shoppingcart.price')}}</h6>
            <div v-if="showCart" class="itemhead-icon-delete"></div>   
        </div>
        <hr class="item-bar-head">


        <!-- 一般內容組件 ＊有 item-bar -->
        <div 
            v-for="item in orderitems"
            :key="item.product_ID" 
            >
            <div class="orderlist-body dp-flex">

                <div class="item-image">
                    <img :src="item.images[0]" alt="">
                </div>
                <h6 class="item-name">{{item.name_en}}</h6>
                <div v-if="showCart" class="item-qty dp-flex">
                    <font-awesome-icon class="item-qty-icon" icon="fa-regular fa-square-minus"/>
                    <h6 class="no-i18n-anim">1</h6>
                    <font-awesome-icon class="item-qty-icon fw200" icon="fa-regular fa-square-plus"/>
                </div>
                <div v-if="showCheck" class="item-qty-check dp-flex">
                    <h6>1</h6>
                    <h6>{{$t('shoppingcart.items')}}</h6>
                </div>
                <h6 v-if="showCart" class="item-price no-i18n-anim">NT$ 190</h6>
                <h6 v-if="showCheck" class="item-price-check">NT$ 190</h6>
                <font-awesome-icon v-if="showCart" class="item-icon-delete" icon="fa-regular fa-trash-can" />
            
            </div>
            <hr class="item-bar">

        </div>

        <!-- 總計組件 -->

        <hr class="item-bar-total">

        <div 
            v-if="showTotal"
            class="item-total dp-flex">
            <h5>{{$t('shoppingcart.total')}}</h5>
            <div class="item-dollar dp-flex">
                <h5 class="no-i18n-anim">NT$ </h5>
                <h3 class="no-i18n-anim">190</h3>
            </div>
        </div>

        <!-- 海外購物警語 -->
        <p 
            v-if="showCheck"
            class="warning-text fw200"
            >
            {{$t('shoppingcart.waringtext')}}</p>


    </section>

</template>

<style scoped lang="scss">

.order-dock{ 
    margin-top: 40px;
    padding: 60px 40px 80px;
    text-align: center;
}
//標題組件 ------------------------------------------
.order-title{
    margin-bottom: 60px;
}


//bar設定 ------------------------------------------
.item-bar-head{
    margin-bottom: 20px;
}

.item-bar{
    margin: 20px 0;
    border: none;
    height: 0.5px;
    background-color: $color-fsContent;
}

.item-bar-total{
    margin: 20px 0;
    border: none;
    height: 1px;
    background-color: $color-fsWhite;
}


//check 組件 ------------------------------------------
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


//表頭組件 ------------------------------------------
.orderlist-head{
    padding: 0 20px;
    align-items: center;
}

.itemhead-image{width: 100px; flex-grow: 0;}
.itemhead-name{flex-basis: 0; flex-grow: 4;}
.itemhead-qty{flex-basis: 0; flex-grow: 3;}
.itemhead-qty-check{flex-basis: 0; flex-grow: 3;}
.itemhead-price{flex-basis: 0; flex-grow: 2;}
.itemhead-price-check{flex-basis: 0; flex-grow: 2;}
.itemhead-icon-delete{flex-basis: 0; flex-grow: 1;}


//內容組件 ------------------------------------------

.orderlist-body{
    padding: 0 20px;
    align-items: center;
}

.item-image{width: 100px; height: 100px; flex-grow: 0;}
.item-name{flex-basis: 0; flex-grow: 4;}
.item-qty{flex-basis: 0; flex-grow: 3;}
.item-qty-check{flex-basis: 0; flex-grow: 3;}
.item-price{flex-basis: 0; flex-grow: 2;}
.item-price-check{flex-basis: 0; flex-grow: 2;}
.item-icon-delete{flex-basis: 0; flex-grow: 1;}

.item-image{
    & >img{
        width: 100%;
        height: 100%;
        border-radius: 10px;
    }
}

.item-qty{
    justify-content: center;
    align-items: center;

    & >h6{
        border-radius: 10px;
        border: 0.5px solid $color-fsWhite;
        width: 70px;
        text-align: center;
        margin: 0 20px;
    }
}

.item-qty-check{
    justify-content: center;
    align-items: center;

    & >h6{
        text-align: center;
        margin-right:8px;
    }
}

.item-qty-icon, .item-icon-delete{
    font-size: 2rem;
    cursor: pointer;
}


//總計組件 ------------------------------------------
.item-total{
    justify-content: space-between;
    align-items: center;
}

.item-dollar{
    align-items: center;
}

.item-dollar{
    & >h3{
        color: $color-fsRed;
        margin-left: 20px;
    }
}

//海外購物警語 ------------------------------------------
.warning-text{
    margin-top: 60px;
    color: $color-fsCaption;
}
    

</style>
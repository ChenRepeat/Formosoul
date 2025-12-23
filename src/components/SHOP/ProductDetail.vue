<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useRouter } from "vue-router";     //使用路由功能
import BasicButton from '../BasicButton.vue';
import { ref } from 'vue';


// 按鈕 ------------------------------------
// 宣告常數來接收 useRouter() ，方便後續使用
const router = useRouter();

// 設定路由功能
function goCart(){
    router.push('/shoppingcart')
};
function goProductList(){
    router.push('/shop/productlist')
};


// 商品資料動態載入------------------------------------
// 之後要從資料庫抓跟整理成這個格式
const productData = ref({
    product_ID: 'FO2025110001',
    type: 'Folktoys',
    price: '80',
    stock: '25',
    status: 'Listed',
    createdate: '20251101',
    update: '20251115',
    name_en: 'Spinning Top',
    name_zh: '陀螺',
    images: [
        '/tjd103/Shop/1.png', 
        '/tjd103/Shop/2-1.png', 
        '/tjd103/Shop/2-2.png', 
        '/tjd103/Shop/2-3.png', 
        '/tjd103/Shop/2-4.png'
    ],
    description_en: 'A wooden conical toy that spins when thrown with a wound-up string.',
    description_zh: '木製錐形玩具，繞上細繩後甩出旋轉。',
    story_en: 'A famous craft from Daxi, Taiwan; it symbolizes the power of stability and grounding.',
    story_zh: '台灣大溪等地著名的工藝，象徵重心的穩定。',
    use_en: 'Wrap the string, hurl it toward the ground while pulling back to initiate a steady spin.',
    use_zh: '纏繞繩子後向地甩出並收回繩索，使其平穩旋轉。', 
    isLike: false
  });



const currentBigPic = ref(productData.value.images[0]);


// Tab 開關 ------------------------------------
//const tabInfo = ref(story);  story 對程式來說是變數，所以需要宣告程式才會認識
const tabInfo = ref('story');


// like 切換 ------------------------------------
//const isLike = ref(false);
function likeHeart(){
    //isLike = !isLike;   因為是const，所以這樣寫無法改變值
    productData.value.isLike = !productData.value.isLike;
}


// 數量切換 ------------------------------------
const productQty =ref(1);

function qtyAdd(){
    if( productQty.value < productData.value.stock ){      //之後庫存數量要從資料庫查詢
        productQty.value++;
    }
}

function qtyMinus(){
    if( productQty.value > 1 ){
        productQty.value--;
    }
}


</script>

<template>
    <!-- 麵包屑 -->
    
    <h6 class="page-guide">
        <RouterLink class="page-guide-text" to="/shop">All Product </RouterLink>
        <font-awesome-icon icon="fa-solid fa-angle-right" />
        {{ productData.type }}
        <font-awesome-icon icon="fa-solid fa-angle-right" />
        {{ productData.name_en }}
    </h6>

        
    
    <div class="detail-dock">

        <!-- 主要內容 -->
        <section class="detail-main dp-flex">
    
            <div class="detail-pic dp-flex">
                <ul class="detail-pic-small dp-flex-col">
                    <li v-for="image in productData.images"
                        :key="image"    
                        @click="currentBigPic = image">

                        <img :src="image" alt="" :class="{currentpic: currentBigPic === image}">
                    </li>



                    <!-- 把 li 變成動態載入 -->
                    <!-- <li><img src="../../../public/Shop/2.png" alt=""></li>
                    <li><img src="../../../public/Shop/2-2.png" alt=""></li>
                    <li><img src="../../../public/Shop/2-1.png" alt=""></li>
                    <li><img src="../../../public/Shop/2-3.png" alt=""></li>
                    <li><img src="../../../public/Shop/2-4.png" alt=""></li> -->
                </ul>
                <div class="detail-pic-big">
                    <img :src="currentBigPic" alt="">

                    <!-- 把 大圖 變成動態載入 -->
                    <!-- <img src="../../../public/Shop/2.png" alt=""> -->
                    <div class="detail-pic-icon-dock" @click="likeHeart">
                        <font-awesome-icon v-if="productData.isLike" class="detail-pic-icon" icon="fa-solid fa-heart" />
                        <font-awesome-icon v-else class="detail-pic-icon" icon="fa-regular fa-heart"/>
                    </div>
                </div>
            </div> 
    
            <div class="detail-text dp-flex-col">
                <div>
                    <p class="fw200 no-i18n-anim">{{ productData.product_ID }}</p>
                    <h5>{{ productData.name_en }}</h5>
                    <h4 class="no-i18n-anim">NT$ {{ productData.price }}</h4>
                    <p class="fw200">{{ productData.description_en }}</p>
                    <div class="share-icon">
                        <font-awesome-icon icon="fa-brands fa-square-facebook" />
                        <font-awesome-icon icon="fa-brands fa-instagram" />
                    </div>
                </div>
                
                <div>

                    <div class="qty dp-flex">
                        <font-awesome-icon class="qty-icon" icon="fa-regular fa-square-minus" @click="qtyMinus"/>
                        <h6 class="no-i18n-anim">{{ productQty }}</h6>
                        <font-awesome-icon class="qty-icon fw200" icon="fa-regular fa-square-plus" @click="qtyAdd"/>
                    </div>
    
                    <div class="btn-shopping dp-flex-col">
                        <BasicButton 
                        class="btn-yellow-fill btn-fix-width"
                        @click="goCart()">    
                            Buy Now
                            <font-awesome-icon icon="fa-solid fa-bag-shopping" />
                        </BasicButton>
        
                        <BasicButton class="btn-gray-fill btn-fix-width">
                            Add to Cart
                            <font-awesome-icon icon="fa-solid fa-cart-plus" />
                        </BasicButton>
                    </div>

                </div>

                
    
            </div>
        </section>
    
        <section class="detail-secondary">
            <ul class="detail-tab dp-flex">
                <li :class="{show: tabInfo === 'story'}" @click="tabInfo = 'story'"><span>{{$t('productdetail.story')}}</span></li>
                <li :class="{show: tabInfo === 'howtoplay'}" @click="tabInfo = 'howtoplay'"><span>{{$t('productdetail.howtouse')}}</span></li>
                <li :class="{show: tabInfo === 'shipping'}" @click="tabInfo = 'shipping'"><span>{{$t('productdetail.shipping')}}</span></li>
                <li :class="{show: tabInfo === 'payment'}" @click="tabInfo = 'payment'"><span>{{$t('productdetail.payment')}}</span></li>
            </ul>
    
            <hr>

            <p v-if="tabInfo === 'story'" class="fw200 story">
                {{ productData.story_en }}
            </p>

            <p v-if="tabInfo === 'howtoplay'" class="fw200 howtoplay">
                {{ productData.use_en }}
            </p>

            <p v-if="tabInfo === 'shipping'" class="fw200 shipping">
                {{$t('productdetail.shippingTitle')}} <br>
                <br>
                {{$t('productdetail.shippingContent')}}
            </p>
    
            <p v-if="tabInfo === 'payment'" class="fw200 payment">
                {{$t('productdetail.paymentTitle1')}} <br>
                <br>
                {{$t('productdetail.paymentContent1')}} <br>
                <br><br>
                {{$t('productdetail.paymentTitle2')}} <br>
                <br>
                {{$t('productdetail.paymentContent2')}}
            </p>    
    
        </section>
    </div>

    <BasicButton 
        class="btn-white" 
        @click="goProductList()">    
        Back to Shop
    </BasicButton>



</template>

<style scoped lang="scss">

// 麵包屑
.page-guide{
  padding-bottom: 60px;
}

.page-guide-text{
    color: $color-fsWhite;
    text-decoration: none;
    border-bottom: 1px solid $color-fsWhite;
}

.detail-dock{
    color: $color-fsWhite;

    box-shadow: 
    inset 0 2px 6px rgba(255,255,255,0.05),
    0 0px 10px rgba(0,0,0,0.5);
    backdrop-filter: blur(70px);  
    border-radius: 10px;    

    padding: 60px 60px 80px;
}

.detail-main{
    gap: 60px;
}

//商品照

.detail-pic{
    gap: 20px;
    padding-bottom: 60px;
}

.detail-pic-small{
    gap: 20px;
}
.detail-pic-small>li{
    width: 100px;
    height: 100px;  
    border-radius: 10px;  
    cursor: pointer;
}

.currentpic{
    border: 2px solid $color-fsGold;
    border-radius: 10px;  
    box-shadow: 0 0 15px ;
}

.detail-pic-small>li>img{
    width: 100%;
    height: 100%;
    border-radius: 10px; 
    object-fit: cover;
}

.detail-pic-big{
    width: 100%;
    height: 100%;
    border-radius: 10px; 
    position: relative;
}

.detail-pic-big>img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px; 
}

.detail-pic-icon-dock{
    position: absolute;
    top: 20px;
    right: 20px;
    cursor: pointer;
}

.detail-pic-icon{
    font-size: 2rem;
    color: $color-fsRed;
}


//商品主要文字
.detail-text{
    min-width: 320px;
    flex-grow: 1;
    padding-bottom: 60px;
    justify-content: space-between;

    & h4{
        color: $color-fsRed;
        padding-top: 20px;
    }

    & .share-icon{
        font-size: 2.8rem;
        display: inline-block;
        cursor: pointer;
    }

}


.detail-text p:first-child{
    background-color: $color-fsContent;
    display: inline-block;
    padding: 0 8px;
    border-radius: 10px;
    color: $color-fsTitle;
}

.detail-text div p:nth-of-type(2){
    padding: 40px 0;
}


.qty{
    padding-top: 60px;
    justify-content: center;
    align-items: center;

    & h6{
        border-radius: 10px;
        border: 0.5px solid $color-fsWhite;
        width: 160px;
        text-align: center;
        margin: 0 20px;
    }  

    & .qty-icon{
        font-size: 2rem;
        cursor: pointer;
    }
}


.btn-shopping{
    align-items: center;

    & .btn-fix-width{
        margin-top: 16px;
    }
}


//商品說明

.detail-tab{
    & li{
        font-size: 1.6rem;
        line-height: 200%;
        flex-basis: 0;
        flex-grow: 1;
        text-align: center;
        border-bottom: 2px solid transparent;
        cursor: pointer;

        &:hover{
            border-bottom: 2px solid $color-fsWhite;
        }

        //tab bar 切換
        &.show{
            border-bottom: 2px solid $color-fsWhite;
        }
    }   
}


.detail-secondary{
    & hr{
        height: 0.5px;
        background-color: $color-fsWhite;
        border: none; 
    }

    & p{
        padding-top: 60px;
    }
}




// 回shop btn
.btn-white{
    margin: 60px auto 0;
    display: block;
}

// RWD------------------------------------- 
@media screen and (max-width: 1200px) {
  .detail-dock{ 
    padding: 60px 40px 80px;
  }

  .detail-main{
    gap: 40px;
  }
}

</style>
<template>
    <div class="detail" @click="activeIndex = 2">
        <h3>Orders Detail</h3>
        <div class="detailbar">
            <p>Orders Information</p>
            <!-- {{ $route.params.id }} -->
        </div>
        <div v-if="orderlang" class="orders-information notice">
            <p>Order Number： <span>{{ orderlang.order_number }}</span></p>
            <p>Order Date： {{ orderlang.orderdate }}</p>
            <p>Order Status： {{ orderlang.statuscode }}</p>
            <p>Recipient's Name： {{ orderlang.name_en }}</p>
            <p>Delivery method： {{ orderlang.shippingcode }}</p>
            <p>pieces： {{ orderlang.total_quantity }}</p>
            <p>address： {{ orderlang.address_en }}</p>
            <p>Remark： {{ orderlang.remark }}</p>
            <p>* Notice *<br>To request a return, please email our customer service within the 7-day cooling-off period. For further information, please refer to our Return and Exchange Policy.</p>
        </div>
        <div class="detailbar">
            <p>Payment Information</p>
        </div>
        <div v-if="orderlang" class="orders-information">
            <p>{{$t('shoppingcart.payment')}}： {{ orderlang.payment }}</p>
        </div>
        <div class="detailbar">
            <p>Products Information</p>
        </div>
        <div v-for="product in productlist"  class="orders-product">
            <img :src="product.image" :alt="1">
            <p>{{ product[`name_${lang}`]}}</p>
            <p> {{ product.quantity }} {{$t('shoppingcart.items')}}</p>
            <p>NT$ {{ product.price }}</p>
        </div>
        <div v-if="totallist" class="total">
            <span><p>{{$t('shoppingcart.price')}}：</p><p>NT$ {{ totallist.totalPrice }}</p></span>
            <span><p>{{$t('shoppingcart.discount')}}：</p><p>NT$ {{ totallist.discount }}</p></span>
            <span><p>{{$t('shoppingcart.shippingFee')}}：</p><p>NT$ {{ totallist.fee }}</p></span>
            <span><p>{{$t('shoppingcart.total')}}：</p><p>NT$ {{ totallist.total }}</p></span>
        </div>

        <div class="back-to-member">
            <router-link to="/member/orderslist"><basic-button class="btn-yellow-fill"><h6><font-awesome-icon icon="fa-solid fa-angle-left" /> Back to Query orders</h6></basic-button></router-link>
        </div>
    </div>
</template>
<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import BasicButton from '@/components/BasicButton.vue';
import { useMemberStore } from '@/stores/member';
import { useI18n } from 'vue-i18n';
    const order = ref(null);
    const totallist = ref(null);
    const memberStore = useMemberStore();
    const route = useRoute();
    const productlist = ref(null);
    const { locale } = useI18n();  
    const langList = {
    'en-US': 'en',
    'zh-TW': 'zh'
    };

    const lang = computed( () => {
        return langList[locale.value] || 'en';
    });
    // if(route.params && route.params.id){
    //     

    // }
    
    function orderinformation(){
        const storedUser = localStorage.getItem('user');
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/getmemberorderinformation.php`;
        if(!storedUser) return;
        const userData = JSON.parse(storedUser); 
        const order_number = route.params.id;
        const { member_ID } = userData;

        return fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json'
            },
            // credentials: 'include',
            body: JSON.stringify({
                member_ID, 
                order_number
            })
        }
        ).then( res => res.json()
        ).then( information_response => {
            // localStorage.setItem(order_information, JSON.stringify(information_response));
            const countArray = information_response.count || [];
            const realArray = information_response.data || [];
            const totalQuantity = countArray.reduce((sum, item) => sum + item.quantity, 0);
            const totalPrice = countArray.reduce((sum, item) => sum + item.price * item.quantity, 0);
            const discountAmount = realArray.discount;
            // console.log(totalQuantity);
            // console.log(totalPrice);
            // console.log(countArray);
            realArray.total_quantity = totalQuantity;
            countArray.totalPrice = totalPrice;
            countArray.discount = discountAmount;

            order.value = realArray;
            totallist.value = countArray;

            // if(order.value.payment === 'Credit Card'){
            //     order.value.payment = 'Credit Card (Pay in Full)－VISA/ MASTER/ JCB';
            // }
            // 修改成英文後 需改變判斷式
            if(order.value.shipping === '宅配'){
                const shippingfee = 80;
                countArray.fee = shippingfee;
            }else{
                const shippingfee = 60;
                countArray.fee = shippingfee; 
            }
        }
        ).then( () => {
            const subtotal = computed(() => {
                const { discount, fee, totalPrice } = totallist.value;
                return  fee + totalPrice - discount; 
            });
            totallist.value.total = subtotal.value;
        }).then( () => {
            if(totallist.value.discount > 0){
                totallist.value.discount = `-${totallist.value.discount}`;
            }else{
                totallist.value.discount = 0;
            }
        })
    }
    const orderlang = computed(() => {
        if (!order.value) return null;
        const statusMapByLang = {
            en: { 0: 'Shipped', 1: 'Not Shipped', 2: 'Completed', 3: "Paid", 4: "Payment Failed", 5: "Pending Payment" },
            zh: { 0: '已出貨', 1: '未出貨', 2: '已完成', 3: "已付款", 4: "付款失敗", 5: "等待付款" }
        };
        const shippingMapByLang = {
            en: { '宅配': 'Delivery', '超商取貨': 'Pickup' },
            zh: { '宅配': '宅配', '超商取貨': '超商取貨' }
        };
        const paymentMapByLang = {
            en: { 'Credit Card': 'Credit Card (Pay in Full)－VISA/ MASTER/ JCB', 'Apple Pay': 'Apple Pay' },
            zh: { 'Credit Card': '信用卡（全額支付）－VISA/ MASTER/ JCB', 'Apple Pay': 'Apple Pay' }
        };

        const currentLang = lang.value;
        const data = order.value;
        return {
            ...data,
            statuscode: statusMapByLang[currentLang][data.status] || 'unknown',
            shippingcode: shippingMapByLang[currentLang][data.shipping] || 'unknown',
            payment: paymentMapByLang[currentLang][data.payment] || 'unknown',
        };
    });
    function order_product(){
        const storedUser = localStorage.getItem('user');
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/getorderproduct.php`;
        if(!storedUser) return;
        const userData = JSON.parse(storedUser); 
        const order_number = route.params.id;
        const { member_ID } = userData;
        return fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json'
            },
            credentials: 'include',
            body: JSON.stringify({
                member_ID, 
                order_number
            })
        }
        ).then( res => res.json()
        ).then( product_res => {
            const productArray = product_res.data || [];
            productlist.value = productArray.map((product, index) => {
                const productInfo = productArray[index] || {};
                const productid = productInfo.product_ID || 0;
                const productprice = productInfo.price || 0;
                const productpieces = productInfo.quantity || 0;
                const product_total = productprice * productpieces;
                const imglist = productInfo.image || {};
                let  finalpath = '';
                if(imglist.includes('|')){
                    const splitimg = imglist.split('|');
                    finalpath = splitimg[0];
                }else{
                    finalpath = imglist;
                }

                const imgageurl = `${import.meta.env.BASE_URL}${finalpath}`;



                return{
                    ...product,
                    product_ID: productid,
                    price: product_total,
                    image: imgageurl
                }
            })
            // console.log(productArray);
        })
    };

    onMounted(() => {
        orderinformation();
        order_product();
    });



</script>

<style lang="scss" scoped>
    .detail{
        margin: 0 auto;
        max-width: 1000px;

        & >h3{
            text-align: center;
            margin: 100px 0 100px 0;
        }

        & .notice{
            & span{
                color: $color-fsRed;
                font-weight: 600;
            }

            & p:last-of-type{
                color: $color-fsBlue;
            }
        }
    }

    .orders-information{
        & >p{
            text-align: start;
        }
    } 

    .detailbar{
        border-radius: 8px;
        background-color: $color-fsBlue50;
        height: 40px;    
        margin: 16px 0 16px;
        padding: 0 20px;
        & p{
            line-height: 40px;
            font-weight: 600;
        }
    }

    .orders-information{
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 16px;
        padding: 0 32px;    
    }


    .orders-product{
        // display: flex;
        // justify-content: space-between;
        display: grid;
        grid-template-columns: 1fr 1fr 1.5fr 0.5fr;
        margin: 0 32px;
        padding: 32px;
        border-bottom: 1px solid $color-fsCaption;
    }

    .orders-product > img{
        width: 100px;   
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid $color-fsCaption;
    }

    .orders-product  > p{
        display: flex;
        justify-content: center;
        flex-direction: column;
        text-align: center;
        color: $color-fsTitle;  
    }


    .total{
        width: 30%;
        display: flex;
        flex-direction: column;
        margin: 20px 32px 20px auto;
        gap: 16px;

        & span{
            display: flex;
            justify-content: space-between;
            font-weight: 600;

            & p:last-of-type{
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        & span:last-of-type{

            & p{
                font-size: 2.8rem;
            }
            & p:last-of-type{
                color: $color-fsRed;
            }
        }
    }


    

    .back-to-member{
        margin: 100px 32px;
        display: flex;
        justify-content: center;
    }
    .back-to-member > .btn-yellow-fill > a{
        color: $color-fsTitle;
        text-decoration: none;
    }

    @media screen and (max-width: 1300px) {
        .detail{
            max-width: 900px;

        }
    }
</style>
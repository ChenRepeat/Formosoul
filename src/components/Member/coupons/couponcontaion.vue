<template>
    <div v-if="nocoupon" class="nocoupon"><h3>{{ nocoupon }}</h3></div>
    <div v-else class="coupon-list" :class="{ 'isrows' : isrows }">  
        <!-- <div class="coupon dp-flex ">
            <div class="coupon-left coupon-click left-used">
                <p class="fw600">MAGIC FUN</p>
            </div>
            <div class="coupon-center center-used">
                <div >
                    <h4 class="coupon-content content-used">50%</h4>
                    <h4>COUPON</h4>
                    <p class="coupon-valid">VAILD UNTIL DEC 25, 2026</p>
                </div>
            </div>

            <div class="coupon-right">
                <div><img src="../../../assets/LOGO_blackColor_coupon.svg" alt=""></div>
            </div>
        </div>  -->

         <!-- <div class="coupon dp-flex">
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
        </div> -->
<!-- 以這個為主 -->
        <!-- 要寫一單只能用一個 -->
        <div v-for="coupon in sortedCoupons" :key="coupon.id" class="coupon dp-flex"  @click="handleCouponClick(coupon)">
            <div
            class="coupon-left"
            :class="{
                    'tear-animation': coupon.status === 2 || coupon.isTearing, 
                    'coupon-left': coupon.status >= 2 || coupon.isTearing, 
                    'coupon-click': coupon.status < 3 || coupon.isTearing, // 撕掉
                    'left-used': coupon.status == 0    // 最終是0的時候會灰色
                }"
            >
                <p class="fw600">MAGIC FUN</p>
            </div>
            <div class="coupon-center"
                :class="{ 
                    'tear-animation': coupon.status === 2 || coupon.isTearing,
                    'center-used': coupon.status == 1 || coupon.status == 0,
                }"
            >
                <div>
                <h4 class="coupon-content"
                :class="{ 
                    'tear-animation': coupon.status === 2 || coupon.isTearing, 
                    'content-used': coupon.status == 1 || coupon.status == 0,
                }"  
                >${{ coupon.discount }}</h4>
                <h4>COUPON</h4>
                <p class="coupon-valid">VAILD UNTIL {{ coupon.enddate }}</p>
                <p class="coupon-valid">Usage threshold Spend ${{ coupon.threshold }}</p>
                </div>
            </div>
            
            <div class="coupon-right">
                <div><img src="../../../assets/LOGO_blackColor_coupon.svg" alt=""></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useCartStore } from '@/stores/cart';
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
    const props = defineProps({
        isrows: {
            type: Boolean,
            default: false,
        },
    });
    const nocoupon = ref('');
    const emit = defineEmits(['no-coupon-found', 'coupon-updated']);
    const get_coupon_information = ref(null);
    const route = useRoute();
    const cartStore = useCartStore();
    const sortedCoupons = computed(() => {
        if (!get_coupon_information.value || get_coupon_information.value.length == 0) {
            return null;
        }
        return [...get_coupon_information.value].sort((a, b) => b.status - a.status);
    });
    
    function get_coupon(){
        const storedUser = localStorage.getItem('user');
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/getmembercoupon.php`;
        if(!storedUser) return;
        const userData = JSON.parse(storedUser); 
        const { member_ID, pointscard_ID } = userData;

        return fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json'
            },
            credentials: 'include',
            body: JSON.stringify({
                member_ID, 
                pointscard_ID
            })
        }
        ).then( res => res.json()
        ).then( coupon_information => {
            const coupon_Array = coupon_information.data || [];
            if(!coupon_information.havecoupon){
                nocoupon.value = coupon_information.message;
                emit('no-coupon-found', coupon_information.havecoupon);
                return;
            }
            get_coupon_information.value = coupon_Array.map((coupon, index) => {
                const couponInfo = coupon_Array[index] || {};
                const discount = parseInt(couponInfo.discount) || 0;
                const threshold = parseInt(couponInfo.threshold) || 0;
                const status = parseInt(couponInfo.status) || 0;
                const enddate = couponInfo.end_date || 'N/A';
                emit('coupon-updated', get_coupon_information.value);
                return{
                    ...coupon,
                    discount,
                    threshold,
                    enddate,
                    status
                }
            })
        })
    };
    function restore_coupon(){
        const storedUser = localStorage.getItem('user');
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/modifycoupon.php`;
        if(!storedUser) return;
        const userData = JSON.parse(storedUser); 
        const { pointscard_ID } = userData;

        return fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json'
            },
            credentials: 'include',
            body: JSON.stringify({
                pointscard_ID
            })
        }
        ).then( res => res.json())};
        // 在更新依次狀態   
    // change_coupon 跟 handleCouponClick 是在結帳頁的時候才用的功能

    function change_coupon(coupon){
        const storedUser = localStorage.getItem('user');
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/changecouponstatus.php`;
        if(!storedUser) return;
        const userData = JSON.parse(storedUser); 
        const { member_ID, pointscard_ID } = userData;

        return fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json'
            },
            credentials: 'include',
            body: JSON.stringify({
                member_ID, 
                pointscard_ID,
                coupons_ID: coupon.coupons_ID,
                coupon_status: coupon.user_coupon_status
            })
        }
        ).then( res => res.json())};
    function handleCouponClick(coupon) {
        if (route.path.includes('/member/coupons')) {
            return;
        }
        // if (coupon.status !== 2) return;
        
        // console.log('狀態',coupon.status);
        
        const cartcoupon = cartStore.coupon_ID
        // 切換中，防止重複點擊
        if (coupon.isTearing) return;
        coupon.isTearing = true;
        if(cartStore.coupon_ID == null && coupon.status == 3){
            change_coupon(coupon).then(result => {
            if (result.success) {
                    get_coupon();
                    const storecouponID = result.data.coupons_ID
                    cartStore.coupon_ID  = storecouponID
                    coupon.status = 2; 
                    setTimeout(() => {
                        coupon.status = result.data.user_coupon_status;
                        coupon.isTearing = false;
                    }, 300);
                }else {
                    coupon.isTearing = false;
                }
            });        
        }else if(cartcoupon != coupon.coupons_ID && coupon.status == 3){
            change_coupon(coupon).then(result => {
            if (result.success) {
                    get_coupon();
                    const storecouponID = result.data.coupons_ID
                    cartStore.coupon_ID  = storecouponID
                    // console.log('變成1');
                    coupon.status = 2; 
                    setTimeout(() => {
                        coupon.status = result.data.user_coupon_status;
                        coupon.isTearing = false;
                    }, 300);
                }else {
                    coupon.isTearing = false;
                }   
            });      
        }else{
            coupon.isTearing = false;
        }


    };
        // 如果想點第二次就取消選取，可以在這裡寫邏輯
        // if (cartStore.coupon_ID === coupon.coupons_ID) {
        //     return; 
        // }
        // const previousCoupon = coupon.find(c => c.coupons_ID === cartStore.coupon_ID);
        //     if (previousCoupon) {
        //         previousCoupon.status = 2; 
        //     }
    onMounted(() => {
        get_coupon();
        if (route.path.includes('/member/coupons')) {
            restore_coupon().then( r => {
                get_coupon();
            });
            cartStore.coupon_ID = null;
            cartStore.discount = 0;
            return;
        }
    
    });
</script>

<style scoped lang="scss">
    h3{
        margin-top: 100px;
        text-align: center;
        margin-bottom: 100px;

    }
    .nocoupon{
        width: 100%;
        margin: 0 auto;
    }
    .coupon-list{
        max-width: 1200px;
        margin: 0 auto;
        padding: 100px 0px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        row-gap: 32px;
        column-gap: 80px;
        
        &.isrows{
            grid-template-columns: 1fr;
            grid-template-rows: 1fr;
            row-gap: 48px;
            max-height: 500px;
            // max-width: 600px;
        // 控制當內容的高度「超過」容器設定的高度時會讓瀏覽器自動判斷是否要顯示「垂直捲軸」。 
            overflow-y: auto;
            padding: 40px 0;
        }
    }

        .isrows::-webkit-scrollbar {
            width: 8px;
        }
        .isrows::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 10px;
        }
    .coupon {
        min-width: 400px;
        width: 70%;
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
        text-align: center;
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
    .tear-animation {
        transition: all 0.3s ease;
    }
        
    @media screen and (max-width: 1300px) {
        .coupon-list{
            padding: 100px 48px;
        }
        
    }
</style>
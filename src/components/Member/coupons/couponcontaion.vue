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
<!-- 以這個為主 -->
 <!-- 完全撕掉才變灰色 -->
        <!-- 要寫一單只能用一個 -->
        <div v-for="coupon in sortedCoupons" :key="coupon.id" class="coupon dp-flex"  @click="handleCouponClick(coupon)">
            <div
            :class="{
                'tear-animation': coupon.status === 0 || coupon.isTearing,
                'coupon-left': coupon.status === 1 && !coupon.isTearing, 
                'coupon-left coupon-click left-used': coupon.status === 0 || coupon.isTearing,

            }"  
            >
                <p class="fw600">MAGIC FUN</p>
            </div>
            <div class="coupon-center"
                :class="{ 
                    'center-used': coupon.status === 0 || coupon.isTearing,

                }"  
            
            >
                <div>
                <h4 class="coupon-content"
                :class="{ 
                    'content-used': coupon.status === 0 || coupon.isTearing,
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
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
    const props = defineProps({
        isrows: {
            type: Boolean,
            default: false,
        },
    })
    const nocoupon = ref('');
    const emit = defineEmits(['no-coupon-found', 'coupon-updated']);
    const get_coupon_information = ref(null);
    const route = useRoute();

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
                coupons_ID: coupon.coupons_ID
            })
        }
        ).then( res => res.json())};

    function handleCouponClick(coupon) {
        // if (route.path.includes('/member/coupons')) {
        //     return;
        // }
        

        if (coupon.status === 0) return;    
        change_coupon(coupon).then(result => {
            if (result.success) {
                coupon.isTearing = true;
                setTimeout(() => {
                    coupon.status = 0;
                    coupon.isTearing = false;
                }, 1000);           
            }
        });

    }

    onMounted(() => {
        get_coupon();

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
        padding: 100px 120px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        row-gap: 32px;
        column-gap: 80px;
        
        &.isrows{
            grid-template-columns: 1fr;
            grid-template-rows: 1fr;
            row-gap: 48px;
            max-height: 500px;
        // 控制當內容的高度「超過」容器設定的高度時會讓瀏覽器自動判斷是否要顯示「垂直捲軸」。 
            overflow-y: auto;
            padding: 80px 100px;
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
        transition: all 0.5s ease;
    }

</style>
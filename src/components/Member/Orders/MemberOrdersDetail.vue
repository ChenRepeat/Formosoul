<template>
        <div v-for="order in calorderpage" :key="order.order_id" class="orders-contain">
            <p>{{ order.order_number }}</p> 
            <p>{{ order.orderdate }}</p>
            <p>{{ order.total }}</p>
            <p>{{ order.payment }}</p>
            <p>{{ order.statuscode }}</p>
            <p>{{ order.shippingcode }}</p>

            <p>
                
                <router-link :to="`/member/orderslist/orderscontain/${order.order_number}`">
                <basic-button 
                    class="btn-yellow-fill" 
                    @click="handleCheckOrder(order.order_number)"
                >
                    <p>Check</p>
                </basic-button>
                </router-link>
            </p>
        </div>

</template>

<script setup>
    import { computed, onMounted, ref } from 'vue';
    import BasicButton from '@/components/BasicButton.vue';
    import { useRouter } from 'vue-router';
    import { useMemberStore } from '@/stores/member';
    import { useI18n } from 'vue-i18n';
    // 接收父組件傳來的當前頁碼
    const props = defineProps({
        currentPage: {
            type: Number,
            default: 1
        }
    });
    const { locale } = useI18n();  
    const langList = {
    'en-US': 'en',
    'zh-TW': 'zh'
    };

    const lang = computed( () => {
        return langList[locale.value] || 'en';
    });
    const memberStore = useMemberStore();
    const router = useRouter();
    const orders = ref([]);
    // 也可以 order-status 然後回傳 true or false
    const emit = defineEmits(['no-order-found', 'order-updated'])
    const get_order = () => {
        const storedUser = localStorage.getItem('user');
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/getmemberorder.php`;
        if(!storedUser) return;
        const userData = JSON.parse(storedUser);
        const { member_ID } = userData;
        
        return fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json'
            },
            body: JSON.stringify({
                member_ID
            })
        }
        ).then( res => res.json()
        ).then( order_response => {
            const countArray = order_response.coupon || [];
            const realArray = order_response.data || []; 
            // localStorage.setItem('data', JSON.stringify(realArray));
            if(!order_response.success || realArray.length === 0){
                emit('no-order-found', order_response.success);
                return;
            }
            orders.value = realArray.map((order, index) => {
                    const couponInfo = countArray[index] || {};
                    const subtotalInfo = realArray[index] || {};

                    const count = parseInt(subtotalInfo.subtotal)|| 0;
                    const discount = parseInt(couponInfo.discount) || 0;
                    const shippingFee = (couponInfo.shipping === '宅配') ? 80 : 60;

                    const finalTotal =  count + shippingFee - discount; 
                    emit('order-updated', order_response.success);
                    return {
                        ...order, 
                        total: finalTotal,
                        
                    };
                });

        });
    };

    onMounted(() => {
        get_order();      
    })

    const handleCheckOrder = (orderNumber) => {
        memberStore.setOrderNumber(orderNumber);
        router.push(`/member/orderslist/orderscontain/${orderNumber}`);
    }
    

    const itemsPerPage = 5;

    const calorderpage = computed(() => {
        // console.log(lang.value);
        const start = (props.currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const pagedOrders = orders.value.slice(start, end);
        return pagedOrders.map(order => {
        const statusMapByLang = {
            en: { 0: 'Shipped', 1: 'Not Shipped', 2: 'Completed', 3: "Paid", 4: "Payment Failed", 5: "Pending Payment" },
            zh: { 0: '已出貨', 1: '未出貨', 2: '已完成', 3: "已付款", 4: "付款失敗", 5: "等待付款" }
        };
        const shippingMapByLang = {
            en: { '宅配': 'Delivery', '超商取貨': 'Pickup' },
            zh: { '宅配': '宅配', '超商取貨': '超商取貨' }
        };
        const paymentMapByLang = {
            en: { 'Credit Card': 'Credit Card', 'Apple Pay': 'Apple Pay' },
            zh: { 'Credit Card': '信用卡', 'Apple Pay': 'Apple Pay' }
        };
        const currentLang = lang.value;
        // console.log(statusMapByLang[currentLang][order.status]);
        return {
            ...order,
            statuscode: statusMapByLang[currentLang][order.status] || 'unknown',
            shippingcode: shippingMapByLang[currentLang][order.shipping] || 'unknown',
            payment: paymentMapByLang[currentLang][order.payment] || 'unknown',
        };
    });
    });
    const totalPages = computed(() =>{
        return Math.ceil(orders.value.length / itemsPerPage);
    });
    // 用 expose 把數據暴露給父組件
    defineExpose({
        orders,
        totalPages,
    });
</script>

<style scoped lang="scss">
    .orders-contain{
        width: 100%;
        display: flex;
        justify-content: space-between;
        margin: 0 auto;
        padding: 10px 0;
        border-bottom: 1px solid #e0e0e0;
        flex-shrink: 1;
    }
    .orders-contain > p{
        width: 160px;
        display: inline-flex;
        justify-content: center;
        align-items: center;

    }
    
    .btn-yellow-fill > a{
        display: flex;
        width: 100%;
        height: 100%;
        color: $color-fsTitle;
        text-decoration: none;
    }
    .btn-yellow-fill > p{
        // width: 100%;
        // height: 100%;
    }

</style>
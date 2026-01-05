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
                    const shippingMapByLang = {
                        en: {
                            '宅配': 'delivery',
                            '超商取貨': 'pickup'
                        },
                        zh: {
                            '宅配': '宅配',
                            '超商取貨': '超商取貨'
                        }
                    };
                    const currentLang = lang.value ?? 'zh'; // ⭐ 關鍵
                    console.log(langList.value);
                    const shippingMap = shippingMapByLang[langList.value] ?? {};
                    // 物件映射
                    const statusMap = {
                        0: 'Shipped',
                        1: 'Not Shipped',
                        2: 'Completed',
                        3: "Paid",
                        4: "Payment Failed",
                        5: "Pending Payment"
                    };
                    const finalTotal =  count + shippingFee - discount; 
                    emit('order-updated', order_response.success);
                    return {
                        ...order, 
                        total: finalTotal,
                        statuscode: statusMap[order.status] || 'unknown',
                        shippingcode: order.shipping || 'unknown'
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
        const start = (props.currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        return orders.value.slice(start, end);
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
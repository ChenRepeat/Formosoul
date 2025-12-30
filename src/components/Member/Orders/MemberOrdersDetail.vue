<template>
        <div v-for="order in calorderpage" :key="order.order_id" class="orders-contain">
            <p>{{ order.order_number }}</p> 
            <p>{{ order.date }}</p>
            <!-- <p>{{ order.price }}</p> -->
            <p>{{ order.total }}</p>
            <p>{{ order.payment }}</p>
            <p>{{ order.status }}</p>
            <p>{{ order.shipping }}</p>
            <!-- 換成p -->
            <p>
                
                <!-- <router-link :to="{ path: '/member/orderslist/orderscontain', query: { id: order.order_number } }"> -->
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
    // 接收父組件傳來的當前頁碼
    const props = defineProps({
        currentPage: {
            type: Number,
            default: 1
        }
    });

    console.log(props);
    const memberStore = useMemberStore();
    const router = useRouter();
    const orders = ref([]);
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
            // 使用 map 將兩個陣列的資料合併
            orders.value = realArray.map((order, index) => {
                    const couponInfo = countArray[index] || {};
                    const subtotalInfo = realArray[index] || {};
                    // 1. 取得正確的原始金額欄位 (修正 subtotal -> total)
                    const count = parseInt(subtotalInfo.subtotal)|| 0;
                    const discount = parseInt(couponInfo.discount) || 0;
                    
                    // 2. 運費判斷
                    const shippingFee = (couponInfo.shipping === '宅配') ? 80 : 60;

                    const finalTotal =  count + shippingFee - discount; 

                    return {
                        ...order, // 自動帶入 order_number, date, status 等
                        total: finalTotal, // 這裡會正確顯示 4467 而不是 -31
                    };
                });
            // orders.value = combinedOrders;
            console.log(orders.value);
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
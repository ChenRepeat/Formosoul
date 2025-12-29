<template>
        <div v-for="order in calorderpage" :key="order.id" class="orders-contain">
            <p>{{ order.order_number }}</p> 
            <p>{{ order.date }}</p>
            <p>{{ order.prices }}</p>
            <p>{{ order.payment }}</p>
            <p>{{ order.status }}</p>
            <p>{{ order.shipping }}</p>
            <!-- 換成p -->
            <p>
                <router-link to="/member/orderslist/orderscontain"><basic-button class="btn-yellow-fill"><p>Check</p></basic-button></router-link>
            </p>
        </div>

</template>

<script setup>
    import { computed, onMounted, ref } from 'vue';
    import BasicButton from '@/components/BasicButton.vue';
    // 接收父組件傳來的當前頁碼
    const props = defineProps({
        currentPage: {
            type: Number,
            default: 1
        }
    });
    
    const ordertest = ref([]);
    
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
        ).then( order_data => {
            const formattedData = Array.isArray(order_data) ? order_data : (order_data ? [order_data] : []);
            localStorage.setItem('data', JSON.stringify(formattedData));
            ordertest.value = formattedData || [];
        });
    };


    onMounted(() => {
        get_order().then( () => {
            console.log(ordertest.value);

        });
        
    })
    
    const orders = ref([
        {
            number: 'OD20250001',
            date: '2025-01-01',
            prices: '$1,000',
            payment: 'Credit card',
            status: 'Paid',
            shipping: 'Home delivery',

        },
        {
            number: 'OD20250002',
            date: '2025-01-02',
            prices: '$2,500',
            payment: 'APPLE PAY',
            status: 'Paid',
            shipping: 'Home delivery',

        },
        {
            number: 'OD20250003',
            date: '2025-01-03',
            prices: '$800',
            payment: 'APPLE PAY',
            status: 'Pending',
            shipping: 'Home delivery',
        },
        {
            number: 'OD20250003',
            date: '2025-01-03',
            prices: '$800',
            payment: 'APPLE PAY',
            status: 'Pending',
            shipping: 'Home delivery',
        },
        {
            number: 'OD20250003',
            date: '2025-01-03',
            prices: '$800',
            payment: 'APPLE PAY',
            status: 'Pending',
            shipping: 'Home delivery',
        },
        {
            number: 'OD20250003',
            date: '2025-01-03',
            prices: '$800',
            payment: 'APPLE PAY',
            status: 'Pending',
            shipping: 'Home delivery',
        },
        {
            number: 'OD20250003',
            date: '2025-01-03',
            prices: '$800',
            payment: 'APPLE PAY',
            status: 'Pending',
            shipping: 'Home delivery',
        },
        {
            number: 'OD20250003',
            date: '2025-01-03',
            prices: '$800',
            payment: 'APPLE PAY',
            status: 'Pending',
            shipping: 'Home delivery',
        },
        {
            number: 'OD20250003',
            date: '2025-01-03',
            prices: '$800',
            payment: 'APPLE PAY',
            status: 'Pending',
            shipping: 'Home delivery',
        },
        {
            number: 'OD20250003',
            date: '2025-01-03',
            prices: '$800',
            payment: 'APPLE PAY',
            status: 'Pending',
            shipping: 'Home delivery',
        },
        {
            number: 'OD20250003',
            date: '2025-01-03',
            prices: '$80d0',
            payment: 'APPLE PAY',
            status: 'Pending',
            shipping: 'Home delivery',
        },
        {
            number: 'OD20250003',
            date: '2025-01-03',
            prices: '$8d00',
            payment: 'APPLE PAY',
            status: 'Pending',
            shipping: 'Home delivery',
        },
    ]);

    const itemsPerPage = 5;

    const calorderpage = computed(() => {
        const start = (props.currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        return ordertest.value.slice(start, end);
    });
    
    const totalPages = computed(() =>{
        return Math.ceil(ordertest.value.length / itemsPerPage);
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
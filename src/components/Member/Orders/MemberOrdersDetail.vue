<template>
        <div v-for="(order, index) in calorderpage" :key="index" class="orders-contain">
            <span>{{ order.number }}</span>
            <span>{{ order.date }}</span>
            <span>{{ order.prices }}</span>
            <span>{{ order.payment }}</span>
            <span>{{ order.status }}</span>
            <span>{{ order.shipping }}</span>
            <span>
                <button>Check</button>
            </span>
        </div>
</template>

<script setup>
    import { computed, ref } from 'vue';
    // 接收父組件傳來的當前頁碼
    const props = defineProps({
        currentPage: {
            type: Number,
            default: 1
        }
    });
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
    .orders-contain > span{
        width: 160px;
        display: flex;
        justify-content: center;
        
    }
</style>
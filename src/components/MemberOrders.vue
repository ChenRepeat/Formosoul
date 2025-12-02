<template>
    <div class="member-orders">
        <h2>Query Orders</h2>
    </div>
    <div class="orders">
        <div class="title-orders">
            <span>Number</span>
            <span>Date</span>
            <span>Prices</span>
            <span>Payment</span>
            <span>Status</span>
            <span>Shipping</span>
            <span>Detail</span>
        </div>
        <hr class="title-orders-line">
        <MemberOrdersDetail ref="detailComponent" :currentPage="currentPage" />
        <div class="orderspage-btn">
            
            <button @click="prevPage"><font-awesome-icon icon="fa-solid fa-angle-left" /></button>
            <button
                v-for="page in totalPages"
                :key="page"
                @click="goToPage(page)"
                :class="{ active: currentPage == page}"
            >
                {{ page }}
            </button>
            <!-- <button @click="goToPage(1)">1</button>
            <button @click="goToPage(2)">2</button>
            <button @click="goToPage(3)">3</button> -->
            <button @click="nextPage"><font-awesome-icon icon="fa-solid fa-angle-right" /></button>
        </div>
    </div>
</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { computed, ref } from 'vue';
import MemberOrdersDetail from './MemberOrdersDetail.vue';

const currentPage = ref(1);
// 獲得每頁幾筆的資訊
const itemsPerPage = 5;

const detailComponent = ref(null);

const totalPages = computed(() =>{
    return detailComponent.value?.totalPages || 0;
});
const prevPage = () => {
    if(currentPage.value > 1){
        currentPage.value = currentPage.value - 1;
    }
};

const nextPage = () => {
    if(currentPage.value < totalPages.value){
        currentPage.value = currentPage.value + 1; 
    }else{
        currentPage.value = totalPages.value; 
    }
};

const goToPage = (pageNumber) => {
    currentPage.value = pageNumber;
};

</script>

<style scoped>
    .member-orders{
        margin: 0 auto;
        width: 1200px;
    }
    h2{ 
        display: flex;
        font-size: 52px;
        align-items: center;
        color: #041416;
        justify-content: center;
    }
    .orders{
        margin: 0 auto;
        width: 1200px;  
    }
    .title-orders{
        width: 100%;
        display: flex;
        justify-content: space-between;
        margin: 0 auto;
        font-weight: bold;
    }
    .title-orders  > span{
        width: 160px;
        display: flex;
        justify-content: center;
    }
    .title-orders-line{
        height: 2px;
        background-color: #041416;
    }
    .orderspage-btn{
        padding-top: 16px;
        display: flex;
        justify-content: center;
        gap: 16px;
    }
    .orderspage-btn > button{
        font-size: 16px;
        background-color: #ffcc46;
        width: 24px;
        height: 24px;
        border: 0;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
</style>
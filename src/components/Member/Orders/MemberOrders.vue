<template>
    <template v-if="!hasChildRoute">
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
                    
                    <button class="btn-yellow-fill" @click="prevPage"><font-awesome-icon icon="fa-solid fa-angle-left" /></button>
                    <button class="btn-yellow-fill"
                        v-for="page in totalPages"
                        :key="page"
                        @click="goToPage(page)"
                        :class="{ active: currentPage == page}"
                    >
                        {{ page }}
                    </button>
                    <button class="btn-yellow-fill" @click="nextPage"><font-awesome-icon icon="fa-solid fa-angle-right" /></button>
                </div>
            </div>
    </template>
    <RouterView></RouterView>
</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { computed, ref } from 'vue';
import MemberOrdersDetail from './MemberOrdersDetail.vue';
import { useRoute } from 'vue-router';

const currentPage = ref(1);
// 獲得每頁幾筆的資訊
const itemsPerPage = 5;

const detailComponent = ref(null);

const route = useRoute();

const hasChildRoute = computed(() => {
    // route.matched => 返回所有匹配的路由陣列 | includes('path的名稱')
    return route.matched.some(r => r.path.includes('orderscontain'));
})

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

<style scoped lang="scss">
    .member-orders{
        margin: 0 auto;
        width: 1200px;
    }
    h2{
        text-align: center;
        color: #041416;

    }
    .orders{
        margin: 0 auto;
        width: 1200px;
        padding-top: 16px;
        padding-bottom: 16px; 
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
        background-color: transparent;
        transition:  0.2s ease;

    }
    .orderspage-btn > button:hover{
        background-color: #ffcc46;
        color: white; 
    }
    .orderspage-btn > button.active {
        background-color: #ffcc46;
        color: white;
    }
</style>
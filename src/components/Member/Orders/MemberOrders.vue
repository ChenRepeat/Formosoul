<template>
    <template v-if="!hasChildRoute">
            <div class="member-orders">
                <h3>Query Orders</h3>
            </div>
            <div class="orders">
                <div class="title-orders">
                    <p>Number</p>
                    <p>Date</p>
                    <p>Prices</p>
                    <p>Payment</p>
                    <p>Status</p>
                    <p>Shipping</p>
                    <p>Detail</p>
                </div>
                <hr class="title-orders-line">
                
                <MemberOrdersDetail ref="detailComponent" :currentPage="currentPage" />
                <div class="orderspage-btn">
                    
                    <span class="list-page noborder" @click="prevPage"><font-awesome-icon icon="fa-solid fa-angle-left" /></span>
                    <span class="list-page"
                        v-for="page in totalPages"
                        :key="page"
                        @click="goToPage(page)"
                        :class="{ active: currentPage == page}"
                    >
                        {{ page }}
                    </span>
                    <span class="list-page noborder" @click="nextPage"><font-awesome-icon icon="fa-solid fa-angle-right" /></span>
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
    h3{
        text-align: center;
        color: $color-fsTitle;
        margin: 100px 0 100px 0;

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
    .title-orders  > p{
        width: 160px;
        display: flex;
        justify-content: center;
    }
    .title-orders-line{
        height: 2px;
        background-color: $color-fsTitle;
    }
    .orderspage-btn{
        margin-top: 100px;
        margin-bottom: 100px;
        font-size: 16px;
        line-height: 160%;
        text-align: center;
        display: flex;
        justify-content: center;

    }
    .list-page{
        border-radius: 3px;
        border: 1px solid $color-fsTitle;
        display: inline-block;
        width: 24px;
        margin: 10px;
        cursor: pointer;
        text-align: center;
    }

    .list-icon{
        cursor: pointer; 
    }

    .list-page.click, .list-page:hover{
        background-color: $color-fsTitle;
        color: $color-fsWhite;
    }

    .noborder{
        border: 0;
    }
</style>
<template>
    <div class="no-orders dp-flex-col" v-if="!hasOrders">
        <h3>No Orders</h3>
        <router-link to="/shop"><h4>Buy Something Now ~ </h4></router-link>
    </div>
    <template v-else-if="!hasChildRoute">
            <div class="member-orders">
                <h3>{{$t('member.myOrders')}}</h3>
            </div>
            <div class="orders">
                <div class="title-orders">
                    <p>{{$t('shoppingcart.orderNO')}}</p>
                    <p>{{$t('shoppingcart.orderDate')}}</p>
                    <p>{{$t('shoppingcart.price')}}</p>
                    <p>{{$t('shoppingcart.payment')}}</p>
                    <p>{{$t('shoppingcart.orderStatus')}}</p>
                    <p>{{$t('shoppingcart.shipping')}}</p>
                    <p>{{$t('shoppingcart.orderDetailTab')}}</p>
                </div>
                <hr class="title-orders-line">
                
                <MemberOrdersDetail @no-order-found="setOrderStatus" ref="detailComponent" :currentPage="currentPage" />
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
const hasOrders = ref(true);
const setOrderStatus = (status) =>{
    hasOrders.value = status
}
const route = useRoute();

const hasChildRoute = computed(() => {
    // route.matched => 返回所有匹配的路由陣列 | includes('path的名稱')  
    return route.matched.some(r => 
        r.path.includes('orderscontain')
    );
})

const totalPages = computed(() =>{
    // ?. 就是當前面找不到 totalpages時 return 0 避免報錯
    return detailComponent.value ?. totalPages || 0;
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
        margin: 100px 0;

    }
    .no-orders {
        margin: 100px 0 100px 0;
        h3{margin: 0 0;}
        h4{text-align: center;}
        a{  
            transition: all 0.3s ease;
            &:link{
            color: $color-fsTitle;
            }
            &:visited {
            color: $color-fsTitle;
            }
            &:hover {
            color:  $color-fsGold300;
            text-decoration: underline;
            }
            &:active {
            color: $color-fsGold;
            }
        }
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
    @media screen and (max-width: 1300px) {
        .member-orders{
            width: 900px;

        }
        .orders{
            width: 900px;

        }
    }
</style>
<template>
    <div class="collections">
        <h3>My collections</h3>
        <ProductCard :images="calcollectionspage" :currentPage="currentPage"></ProductCard>
        <div class="pagebtn">
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

<script setup>
import BasicButton from '@/components/BasicButton.vue';
import ProductCard from '@/components/ProductCard.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { computed, ref } from 'vue';

    const myimg = ref([
        { id: 1, img: 'https://images.unsplash.com/photo-1682687220742-aba13b6e50ba?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 2, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 3, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 4, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 5, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 6, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 7, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 8, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 9, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 10, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 11, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 12, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 13, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 14, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 15, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 16, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 17, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 18, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 19, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 20, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 21, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 22, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 23, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 24, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 25, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 26, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 27, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 28, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 29, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
        { id: 30, img: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=600' },
    ]);

    // 設定現在在一頁(預設是第一頁)
    const currentPage = ref(1);
    // 取每一頁需要幾張卡片
    const itemsPerPage = 9;
    // 用 start 跟 end 去 slice 每一頁該有幾張卡片
    const calcollectionspage = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        return myimg.value.slice(start, end);
    })
    // 總頁數 ceil是除完後無條件 +1
    const totalPages = computed(() => {
        return Math.ceil(myimg.value.length / itemsPerPage);
    });
    // 下面兩個為上一頁跟下一頁 邏輯就是用條件式去判斷 currentPage 是要 +1 還是 -1 判斷的條件一個是不能超過第一頁另一個是不能大於總頁數
    const prevPage = () => {
        if(currentPage.value > 1){
            currentPage.value = currentPage.value - 1;
        }
    };

    const nextPage = () => {
        if(currentPage.value < totalPages.value){
            currentPage.value = currentPage.value + 1;
        }
    };
    // 中間頁碼 先判斷是否有超出頁數 沒有的話就直接讓 currentPage = pageNumber 就是讓 currentPage == 你點擊到的頁數(傳出來的參數)
    const goToPage = (pageNumber) => {
        if(pageNumber >= 1 && pageNumber <= totalPages.value){
            currentPage.value = pageNumber;
            // console.log(pageNumber);
        }
    };
</script>

<style scoped lang="scss">
    .collections{
        max-width: 1200px;
        margin: 0 auto;
    }

    h3{
        text-align: center;
        margin: 100px 0 100px 0;
    }

    .product-case{
        display: grid;
        grid-template-columns: auto auto auto;
        justify-content: center;
        column-gap: 126px;
        row-gap: 24px;
    }

    .pagebtn{
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
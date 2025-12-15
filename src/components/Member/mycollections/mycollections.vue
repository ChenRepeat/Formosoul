<template>
    <div class="collections">
        <h3>My collections</h3>
        <section class="list-all">
            <ProductCard :images="calcollectionspage" :currentPage="currentPage"></ProductCard>
        </section>
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
        { id: 1, img: '../../public/Shop/1.png' },
        { id: 2, img: '../../public/Shop/2.png' },
        { id: 3, img: '../../public/Shop/3.png' },
        { id: 4, img: '../../public/Shop/4.png' },
        { id: 5, img: '../../public/Shop/5.png' },
        { id: 6, img: '../../public/Shop/6.png' },
        { id: 7, img: '../../public/Shop/1.png' },
        { id: 8, img: '../../public/Shop/2.png' },
        { id: 9, img: '../../public/Shop/3.png' },
        { id: 10, img: '../../public/Shop/4.png' },
        { id: 11, img: '../../public/Shop/5.png' },
        { id: 12, img: '../../public/Shop/6.png' },
        { id: 13, img: '../../public/Shop/1.png' },
        { id: 14, img: '../../public/Shop/2.png' },
        { id: 15, img: '../../public/Shop/3.png' },
        { id: 16, img: '../../public/Shop/4.png' },
        { id: 17, img: '../../public/Shop/5.png' },
        { id: 18, img: '../../public/Shop/6.png' },
        { id: 19, img: '../../public/Shop/1.png' },
        { id: 20, img: '../../public/Shop/2.png' },
        { id: 21, img: '../../public/Shop/3.png' },
        { id: 22, img: '../../public/Shop/4.png' },
        { id: 23, img: '../../public/Shop/5.png' },
        { id: 24, img: '../../public/Shop/6.png' },
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
    .list-all{
  max-width: 1200px;
  margin: 0 auto;
}
</style>
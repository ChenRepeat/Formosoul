<template>
    <div v-if="nocollection" class="nocoupon"><h3>{{$t('member.nocollection')}}</h3></div>
    <div v-else class="collections">
        <h3>{{$t('member.myCollect')}}</h3>
        <section class="list-all">
            <TestProductCard :products="calcollectionspage" :currentPage="currentPage" withwhite></TestProductCard>
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
import TestProductCard from '@/components/TestProductCard.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { computed, onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
  const { locale } = useI18n();  
  const langList = {
  'en-US': 'en',
  'zh-TW': 'zh'
  };

  const lang = computed( () => {
      return langList[locale.value] || 'en';
  });
const collectionlist =  ref([]);
const nocollection = ref('');

function getcollectionlist(){
          const storedUser = localStorage.getItem('user');
          const apiBase = import.meta.env.VITE_API_BASE;
          const API_URL = `${apiBase}/getmembercollectionlist.php`;
          if(!storedUser) return;
          const userData = JSON.parse(storedUser); 
          const { member_ID } = userData;
          
          return fetch(API_URL, {
              method: 'POST',
              headers: {
                  'Content-Type' : 'application/json'
              },
              credentials: 'include',
              body: JSON.stringify({
                  member_ID, 
              })
          }
          ).then( res => res.json()
          ).then(collection_list => {
          const collection_Array = collection_list.data || [];
          collectionlist.value = collection_Array.map((item, index) => {
            const collectionInfo = collection_Array[index] || {};
            let processedImages = collectionInfo.image;
            if (typeof processedImages === 'string') {
                processedImages = processedImages.split('|');
            }
            if (Array.isArray(processedImages)) {
                processedImages = processedImages.map(path => {
                    return path.startsWith('Shop/') ? path : `Shop/${path}`;
                });
            }
            return {
                ...item,
                image: processedImages,
                isLike: Number(item.collect_status) == 1 
            };
        });
        
    });
};

    // 設定現在在一頁(預設是第一頁)
    const currentPage = ref(1);
    // 取每一頁需要幾張卡片
    const itemsPerPage = 9;
    // 用 start 跟 end 去 slice 每一頁該有幾張卡片
    const calcollectionspage = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        return collectionlist.value.slice(start, end);
    })
    // 總頁數 ceil是除完後無條件 +1
    const totalPages = computed(() => {
        return Math.ceil(collectionlist.value.length / itemsPerPage);
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

onMounted(async () => {
    await getcollectionlist();
    if(collectionlist.value.length == 0){
        nocollection.value = 'No Collection'
    }
});

watch(
    collectionlist, 
    (newList) => {
        const hasUnliked = newList.some(item => item.isLike === false);
        if (hasUnliked) {
            collectionlist.value = collectionlist.value.filter(item => item.isLike !== false);
            if (calcollectionspage.value.length === 0 && currentPage.value > 1) {
                currentPage.value--;
            }
        }
    },
    // 深層監聽 才能監聽到裡面的islike
    { deep: true }
);

// .some()	只要有一個符合，就是 true	布林值 (true/false)
// .every()	必須全部都符合，才是 true	布林值 (true/false)
// .find()	找第一個符合的項目	回傳該項目物件 (找不到則 undefined)
// .filter()	找所有符合的項目	回傳新陣列
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
    .list-page.active{
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
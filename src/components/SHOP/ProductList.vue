<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import TestProductCard from '@/components/TestProductCard.vue';
import { useRouter } from "vue-router";     //使用路由功能

// 宣告常數來接收 useRouter() ，方便後續使用
const router = useRouter();

// 設定路由功能
function goProductDetail(){
    router.push('/shop/productdetail')
};

</script>

<template>
    <!-- 麵包屑 -->
    <h6 class="page-guide">
        All Product
        <font-awesome-icon icon="fa-solid fa-angle-right" />
        New Arrivals
    </h6>


    <!-- 上方區塊 -->
    <section class="nav-top dp-flex">
        <div class="search">
            <form class="search-content dp-flex" action="" method="GET">
                <input type="text" class="search-text" placeholder="Search">
                <button type="submit" class="btn-search-submit">
                    <font-awesome-icon icon="fa-solid fa-magnifying-glass" />
                </button>
            </form>
        </div> 

        <nav class="nav-top-right">
            <font-awesome-icon class="nav-icon" icon="fa-solid fa-angle-down" /> 
            <select class="nav-list fw200">
                <option class="list-option" selested>New Arrivals</option>
                <option class="list-option">Price：Low to High</option>
                <option class="list-option">Price：High to Low</option>
            </select>
        </nav>
    </section> 

    <section>
        <ul class="list-category dp-flex">
            <li class="list-category-group">
                <div class="list-dock">
                    <div class="list-liquidGlass">
                        <font-awesome-icon class="list-faIcon" icon="fa-solid fa-gamepad" />
                    </div>
                </div>
                <p class="list-name"> Traditional Toys</p>
            </li>

            <li class="list-category-group">
                <div class="list-dock">
                    <div class="list-liquidGlass">
                        <font-awesome-icon class="list-faIcon" icon="fa-solid fa-box-open" />
                    </div>
                </div>
                <p class="list-name">Custom Product</p>
            </li>

            <li class="list-category-group">
                <div class="list-dock">
                    <div class="list-liquidGlass">
                        <font-awesome-icon class="list-faIcon" icon="fa-solid fa-ring" />
                    </div>
                </div>
                <p class="list-name">Accessories</p>
            </li>

            <li class="list-category-group">
                <div class="list-dock">
                    <div class="list-liquidGlass">
                    <font-awesome-icon class="list-faIcon" icon="fa-solid fa-ticket" />
                    </div>
                </div>
                <p class="list-name">Voucher</p>
            </li>
        </ul>
    </section>

<!-- 中間商品列表 -->
    <section class="list-all">
        <TestProductCard 
        @click="goProductDetail()"/>
    </section>

<!-- 下方頁碼 -->
    <nav class="nav-down fw200">
        <font-awesome-icon class="list-icon" icon="fa-solid fa-angle-left" />
        <span class="list-page click">1</span>
        <span class="list-page">2</span>
        <span class="list-page">3</span>
        <font-awesome-icon class="list-icon" icon="fa-solid fa-angle-right" />
    </nav>


</template>

<style scoped lang="scss">

// 麵包屑
.page-guide{
  padding-bottom: 60px;
}


// 上方搜尋框＋選單--------------------------------

.nav-top{
  font-size: 16px;
  justify-content:space-between;
}


// <搜尋框>--------------------------------
.search{
    width: 320px;
    height: 40px;
    border-radius: 20px;
    padding: 0 16px;

    background-color: $color-fsWhite;
}

.search:focus-within{
    //裡面包的input倍focus時，外層可已產生focus的效果
    box-shadow: 0 0 25px rgba(255, 255, 255, 0.8) ;   
}

.search-content{
    justify-content: space-between;
    height: 100%;
}


.search-text{
    flex-basis: 0;      //因為 input-text 有預設寬度，所以要這樣寫，才能讓 flex-grow 接管寬度
    flex-grow: 1;
    font-size: 1.6rem;
    color: $color-fsTitle;
    font-family: 'Roboto', 'Noto Sans TC', sans-serif;

    //取消預設值
    border: none;  
    background-color: transparent;
}

.search-text:focus{
    outline: 0;    //取消預設值
}

.btn-search-submit{
    cursor: pointer;
    font-size: 2rem;
    color: $color-fsTitle;

    //取消預設值
    border: none;  
    background-color: transparent;
}


// <選單>--------------------------------
.nav-top-right{
    // 新增自訂義箭頭
    position: relative;
}

.nav-list{
    color: $color-fsWhite;
    background-color: transparent;
    font-size: 20px;
    font-family: 'Ubuntu','Roboto', 'Noto Sans TC', sans-serif;
    text-align: center;
    width: 320px;
    height: 40px;
    cursor: pointer;

    border: none;
    border-bottom: 0.5px solid $color-fsWhite;

    // 移除預設下拉箭頭
    appearance: none;

    // 讓文字在空白處置中
    padding-right: 28px;
}

.nav-list:focus {
  outline: none;
  border-bottom: 0.5px solid transparent;
  box-shadow: 0 0 5px rgba(255, 255, 255, 0.5) ;
  border-radius: 10px;
}

.nav-icon{
    position: absolute;
    right: 8px;
    top:0;
    bottom: 0;
    margin: auto 0;

    //箭頭會被穿透，不會擋住 select 下拉點擊
    pointer-events: none;
}













// 上方商品分類------------------------------------

.list-category{
  justify-content: center;
  padding: 60px 0;
} 

.list-category-group{
  margin: 40px;
  cursor: pointer;
}

.list-dock{
  width: 120px;
  height: 120px;
  border-radius: 50%;
  backdrop-filter: blur(5px);
  box-shadow: inset 0 0 120px rgba(255, 255, 255, 0.2);
}

.list-liquidGlass{
  width: 120px;
  height: 120px;
  border-radius: 50%;
  backdrop-filter: blur(15px);
  box-shadow: 0 0 8px rgba(255, 255, 255, 0.5) ;

  position: relative;
}

.list-category-group .list-faIcon{
  font-size: 48px;

  color: $color-fsWhite;
  
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.list-name{
  margin-top: 20px;
  text-align: center;
}

.list-category-group:hover .list-dock{
  transform: scale(1.1);
}



//中間商品列表--------------------------------

.list-all{
  max-width: 1200px;
  margin: 0 auto;
}


// 下方頁碼----------------------------------

.nav-down {
  padding: 120px 0 0;
  
  font-size: 16px;
  line-height: 160%;
  margin: 0 auto;
  text-align: center;
}

.list-page{
  border-radius: 3px;
  border: 1px solid $color-fsWhite;
  display: inline-block;
  width: 25.6px;
  margin: 10px;
  cursor: pointer;
}

.list-icon{
   cursor: pointer; 
}

.list-page.click, .list-page:hover{
  background-color: $color-fsWhite;
  color: $color-fsTitle;
}

</style>
<script setup>
  import { ref } from 'vue'; // 引入 ref 來創建響應式變數
  import siteLogo from '@/assets/logo_white_S.png'; 
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

  // 創建一個響應式變數來追蹤選單的狀態（開/關）
  const isMenuOpen = ref(false); 

  // 切換選單狀態的函式
  function toggleMenu() {
      isMenuOpen.value = !isMenuOpen.value;
  }
</script>

<template>
  <div class="header-outer-case dp-flex">
    <img :src="siteLogo" alt="SiteLogo">
    <div class="header-link dp-flex">
      <div class="header-lang-trigger dp-flex">
        <h6 class="trigger-lang">EN</h6>
        <h6 class="trigger-lang">CN</h6>
      </div>
      <div class="header-icons-list dp-flex">
        <font-awesome-icon icon="fa-solid fa-bag-shopping" class="header-icon"/>
        <font-awesome-icon icon="fa-regular fa-circle-user" class="header-icon"/>
          <div class="transition hamburger-btn dp-flex-col" 
             @click="toggleMenu"
             :class="{ 'active': isMenuOpen }"> 
          <div class="bar bar1 transition"></div>
          <div class="bar bar2 transition"></div>
          <div class="bar bar3 transition"></div>
        </div>
        <font-awesome-icon icon="fa-solid fa-grip-vertical" class="header-icon"/>
      </div>
      <ul class="burger-list" :class="{ 'active': isMenuOpen }">
         <li><a href="#">首頁</a></li>
         <li><a href="#">關於</a></li>
         <li><a href="#">聯繫</a></li>
      </ul>
    </div>
  </div>
</template>

<style scoped lang="scss">
  .header-outer-case{
    padding: 0 40px 0 60px;
    background-color: #041426;
    height: 100px;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    width: 100%;
  }
  img{
    object-fit: none;
  }
  .trigger-lang{
    color: $color-fsWhite;
  }
  .header-lang-trigger{
    width: 84px;
    height: 40px;
    border: 1px solid $color-fsWhite;
    padding: 0 10px;
    justify-content: space-around;
    align-items: center;
    border-radius: 20px;
  }
  .header-link{
    border-radius: 36px;
    padding: 12px 40px;
    border: 1px solid wheat;
    height: 64px;
    align-items: center;
    gap: 16px;
  }
  .header-icons-list{
    gap: 16px;
  }
  .header-icon{
    color: $color-fsWhite;
    font-size: 32px;
    align-self: center;
  }


  .burger-list {
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    position: absolute;
  }

  .burger-list.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
  }
  .bar{

    background-color: $color-fsWhite;
    border-radius: 8px;

    }
  .hamburger-btn{
    justify-content: center;
    gap: 8px;
    align-items: end;
  }
  .bar1{
    width: 16px;
    height: 2px;
  }
  .bar2{
    width: 12px;
    height: 1px;
  }
  .bar3{
    width: 24px;
    height: 3px;
  }
  .transition{
    transition: 1.2s;
  }
  .active .bar1{transform: rotate(-45deg) translate(-8px, 5px);width: 24px;height: 3px;}
  .active .bar2{width: 0;height: 0;}
  .active .bar3{transform: rotate(45deg) translate(-9px, -5px);}
</style>
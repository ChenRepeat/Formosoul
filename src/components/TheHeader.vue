<script setup>
  import { ref, onMounted } from 'vue';
  import siteLogo from '@/assets/logo_white_S.png'; 
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import $ from 'jquery'; // 需要導入 jQuery
// 引入 jQuery UI
import 'jquery-ui-dist/jquery-ui'
import 'jquery-ui-dist/jquery-ui.css'
  const isMenuOpen = ref(false); 
  const userIcon = ref(null); // 添加 ref 來引用元素

  function toggleMenu() {
      isMenuOpen.value = !isMenuOpen.value;
  }

  onMounted(() => {
    // 在組件掛載後初始化 draggable
    if (userIcon.value) {
      $(userIcon.value.$el).draggable({ // 修正拼寫: dragable -> draggable
        containment: 'window', // 修正: 'parent' 可能不適用,改為 'window'
        cursor: 'move'
      });
    }
  });
</script>

<template>
  <div class="header-outer-case dp-flex">
    <a href="/"><img :src="siteLogo" alt="SiteLogo"></a>
    <div class="header-link dp-flex">
      <div class="header-lang-trigger dp-flex">
        <h6 class="trigger-lang">EN</h6>
        <h6 class="trigger-lang">CN</h6>
      </div>
      <div class="header-icons-list dp-flex">
        <font-awesome-icon icon="fa-solid fa-bag-shopping" class="header-icon"/>
        <font-awesome-icon 
          icon="fa-regular fa-circle-user" 
          class="header-icon draggable-icon"
          ref="userIcon"
        />
        <div class="transition hamburger-btn dp-flex-col" 
             @click="toggleMenu"
             :class="{ 'active': isMenuOpen }"> 
          <div class="bar bar1 transition"></div>
          <div class="bar bar2 transition"></div>
          <div class="bar bar3 transition"></div>
        </div>
        <font-awesome-icon icon="fa-solid fa-grip-vertical" class="header-icon" />
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
    top: 0;
    left: 0;
    z-index: 1000;
  }
  img{
    object-fit: none;
  }
  .trigger-lang{
    color: $color-fsWhite;
    margin: 0;
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
    position: relative;
  }
  .header-icons-list{
    gap: 16px;
  }
  .header-icon{
    color: $color-fsWhite;
    font-size: 32px;
    align-self: center;
    cursor: pointer;
  }
  
  .draggable-icon {
    cursor: move !important;
    position: relative;
    z-index: 1001;
  }

  .burger-list {
    list-style: none;
    padding: 20px;
    margin: 0;
    background-color: #041426;
    border: 1px solid wheat;
    border-radius: 12px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    position: absolute;
    top: 80px;
    right: 0;
    min-width: 150px;
    
    li {
      margin: 10px 0;
      
      a {
        color: $color-fsWhite;
        text-decoration: none;
        display: block;
        padding: 8px 12px;
        border-radius: 4px;
        transition: background-color 0.3s;
        
        &:hover {
          background-color: rgba(255, 255, 255, 0.1);
        }
      }
    }
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
    cursor: pointer;
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
    transition: 0.3s ease;
  }
  
  .active .bar1{
    transform: rotate(-45deg) translate(-8px, 5px);
    width: 24px;
    height: 3px;
  }
  .active .bar2{
    width: 0;
    height: 0;
    opacity: 0;
  }
  .active .bar3{
    transform: rotate(45deg) translate(-9px, -5px);
  }
</style>
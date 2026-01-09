<script setup>
import { useRouter, useRoute } from "vue-router";
import { computed } from "vue";
import AdminHeader from "@/components/Admin/AdminHeader.vue";
import Cookies from 'js-cookie'
import { useAuthStore } from '@/stores/autoStore'

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

const activeMenu = computed(() => {
  if (route.meta.activeMenu) {
    return route.meta.activeMenu
  }
  return route.path
})

// 登出邏輯 (跟 Header 一樣)
const handleLogout = () => {
  Cookies.remove('token')
  localStorage.removeItem('user')
  localStorage.removeItem('loginData')
  authStore.token = ''
  authStore.user = null
  
  router.push('/')
}
</script>

<template>
  <el-header>
    <AdminHeader />
  </el-header>
  <el-container class="layout-container">

    <el-container>
      <el-aside width="200px" class="aside-container">
        <div><h6>後台管理系統</h6></div>
        <el-menu router :default-active="activeMenu">
          <el-menu-item index="/admin/member-management">會員管理</el-menu-item>
          <el-menu-item index="/admin/product-management">商品管理</el-menu-item>
          <el-menu-item index="/admin/order-management">訂單管理</el-menu-item>
          <el-menu-item index="/admin/coupon-management">優惠券管理</el-menu-item>
          <el-menu-item index="/admin/news-management">消息管理</el-menu-item>
          <el-menu-item index="/admin/annual-event-management">年度盛事管理</el-menu-item>
        </el-menu>
        
        <div class="el-menu-item logout-btn" @click="handleLogout">
          登出
        </div>
      </el-aside>
      <el-main>        
        <router-view></router-view>
      </el-main>
    </el-container>
  </el-container>
</template>

<style scoped>
/* CSS 完全不用動，因為我們保留了 class="logout-btn" */
.layout-container {
  height:calc(100vh - 60px);
  max-width: 1200px;
  margin: 0 auto;
}
.el-header {
  color: black;
  text-align: center;
  line-height: 60px;
  width: 100%;
  padding: 0;
}
.el-aside {
  background-color: #FFF;
  text-align: center;
}
.aside-container{
  height: 100% ;
  display: flex;
  flex-direction: column;
  margin: 0 10px;
}
.el-menu{
  padding: 10px;
  border-right: none;
}
.el-main{
  padding: 20px 20px 0 20px;
}
.el-menu-item {
  margin: 10px 0;
  border-radius: 10px;
  background-color: #F0F7FF;
  justify-content: center;
  color: #333; 
  box-sizing: border-box;
  width: 180px;
  height: 48px;
}
.el-menu-item:hover{
  background-color: #0A3D70;
  color: #fff;
}
.el-menu-item.is-active {
  background-color: #0A3D70;
  color: #fff; 
}
.aside-container h6{
  font-size: 2rem;
  line-height: 160%;
  margin-top: 20px;
  margin-bottom: 10px;
}
.logout-btn {
  margin: 40px 10px 10px 10px;
  border-radius: 10px;
  background-color: #F0F7FF;
  text-align: center;
  width: 180px;
  height: 48px;
  display: flex;
  justify-content: center; 
  align-items: center;      
  cursor: pointer;
  color: #333;
  text-decoration: none;
}
.logout-btn:hover {
  background-color: #0A3D70;
  color: #fff;
}
</style>
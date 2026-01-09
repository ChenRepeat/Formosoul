<template>
  <el-header class="admin-header">
    <div class="header-left">
      <router-link :to="{ name: 'MemberManagement' }">
        <img src="/Admin/admin-logo.svg" alt="Logo" class="logo" />
      </router-link>
    </div>

    <div class="header-right">
      <el-tooltip content="去前台" placement="bottom">
        <router-link to="/">
          <el-icon class="action-icon" size="20"><HomeFilled /></el-icon>
        </router-link>
      </el-tooltip>

      <el-dropdown trigger="click">
        <div class="user-profile">
          <el-avatar :size="32" :icon="UserFilled" />
          <span class="username">管理員</span>
          <el-icon><CaretBottom /></el-icon>
        </div>
        
        <template #dropdown>
          <el-dropdown-menu>
            <el-dropdown-item divided @click="handleLogout">登出</el-dropdown-item>
          </el-dropdown-menu>
        </template>
      </el-dropdown>
    </div>
  </el-header>
</template>

<script setup>
import { HomeFilled, CaretBottom, UserFilled } from '@element-plus/icons-vue'
import { useRouter } from 'vue-router'
import Cookies from 'js-cookie'
import { useAuthStore } from '@/stores/autoStore'

const router = useRouter()
const authStore = useAuthStore()

// 登出邏輯
const handleLogout = () => {
  // 1. 清除 Cookie 中的 Token
  Cookies.remove('token')
  
  // 2. 清除 localStorage 中的使用者資料 (user, loginData...)
  localStorage.removeItem('user')
  localStorage.removeItem('loginData')
  
  // 3. 清除 Pinia Store 狀態
  authStore.token = ''
  authStore.user = null
  
  // 4. 導回前台首頁
  router.push('/')
}
</script>

<style scoped>
/* 你的樣式保持原本的即可 */
.admin-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 60px; 
  padding: 0 20px 0 20px !important;
  box-sizing: border-box;
}
.header-left {
  display: flex;
  align-items: center;
  gap: 10px;
}
.logo {
  width: 120px; 
}
.header-right {
  display: flex;
  align-items: center;
  gap: 20px; 
}
.action-icon {
  cursor: pointer;
  color: #606266;
  transition: color 0.3s;
  display: flex;
  align-items: center;
}
.user-profile {
  display: flex;
  align-items: center;
  cursor: pointer;
  gap: 8px;
  color: #606266;
}
</style>
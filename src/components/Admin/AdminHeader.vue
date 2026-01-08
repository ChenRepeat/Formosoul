<template>
  <el-header class="admin-header">
    <div class="header-left">
      <router-link :to="{ name: 'MemberManagement' }">
        <img src="/public/Admin/admin-logo.svg" alt="Logo" class="logo" />
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
          <span class="username">管理員A</span>
          <el-icon><CaretBottom /></el-icon>
        </div>
        
        <template #dropdown>
          <el-dropdown-menu>
            <!-- <el-dropdown-item></el-dropdown-item> -->
            <el-dropdown-item divided>登出</el-dropdown-item>
          </el-dropdown-menu>
        </template>
      </el-dropdown>
    </div>
  </el-header>
</template>

<script setup>
// 記得引入需要的 Icon
import { HomeFilled, Bell, CaretBottom, UserFilled } from '@element-plus/icons-vue'
</script>

<style scoped>

.admin-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 60px; 
  padding: 0 20px 0 20px !important;
  box-sizing: border-box;
}

/* 左側 Logo */
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
<template>
  <el-header class="admin-header">
    <div class="header-left">
      <router-link :to="{ name: 'MemberManagement' }">
        <img src="/public/Admin/admin-logo.svg" alt="Logo" class="logo" />
      </router-link>
    </div>

    <div class="header-right">
      <el-tooltip content="前往前台" placement="bottom">
        <a href="/tjd103/" target="_blank">
          <el-icon class="action-icon" size="20"><HomeFilled /></el-icon>
        </a>
      </el-tooltip>

      <el-badge :value="3" class="action-item">
        <el-icon class="action-icon" size="20"><Bell /></el-icon>
      </el-badge>

      <el-dropdown trigger="click">
        <div class="user-profile">
          <el-avatar :size="32" :icon="UserFilled" />
          <span class="username">管理員A</span>
          <el-icon><CaretBottom /></el-icon>
        </div>
        
        <template #dropdown>
          <el-dropdown-menu>
            <el-dropdown-item>個人設定</el-dropdown-item>
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
  background-color: #fff;
  border-bottom: 1px solid #dcdfe6; 
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 60px; 
  border: 1px solid green;
  /* 👇 關鍵設定：上下0，左右20px，並強制生效 */
  padding: 0 40px 0 20px !important;

  /* 👇 保險起見加上這行，確保寬度不會因為加了 padding 而爆掉變寬 */
  box-sizing: border-box;
}

/* 左側 Logo */
.header-left {
  display: flex;
  align-items: center;
  gap: 10px;
}

.logo {
  width: 120px; /* 依你的 Logo 調整 */
}

/* 右側功能區 */
.header-right {
  display: flex;
  align-items: center;
  gap: 20px; /* 元件之間的間距 */
}

/* Icon 滑鼠互動效果 */
.action-icon {
  cursor: pointer;
  color: #606266;
  transition: color 0.3s;
}

.action-icon:hover {
  color: #409eff; /* Element Plus 主色 */
}

/* 用戶區塊 */
.user-profile {
  display: flex;
  align-items: center;
  cursor: pointer;
  gap: 8px;
  color: #606266;
}

/* 修正 Badge 被切到的問題 */
.action-item :deep(.el-badge__content) {
  top: 15px;   
  right: 5px;
  transform: translateY(-50%) translateX(100%); 
}
</style>
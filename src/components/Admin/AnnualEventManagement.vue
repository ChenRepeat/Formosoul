<script setup>
  import { ref, computed, onMounted } from 'vue'
  import ListLayout from './ListLayout.vue'
  import { useRouter } from 'vue-router'
  import { ArrowDown } from '@element-plus/icons-vue'
  import { ElMessage } from 'element-plus'

  const currentPage = ref(1)
  const pageSize = ref(10)
  const total = ref(0)
  const router = useRouter()

  // 搜尋與篩選變數
  const eventSearch = ref('')
  const filterStatus = ref('全部')
  
  const eventData = ref([])

  // ★★★ 核心篩選邏輯 ★★★
  const filteredData = computed(() => {
    let data = [...eventData.value]; // 淺拷貝避免影響原始資料

    // 1. 關鍵字搜尋 (標題 或 內容)
    if (eventSearch.value) {
      const keyword = eventSearch.value.toLowerCase();
      data = data.filter(item =>
        (item.title_zh && item.title_zh.toLowerCase().includes(keyword)) ||
        (item.content_zh && item.content_zh.toLowerCase().includes(keyword))
      );
    }

    // 2. 狀態篩選
    if (filterStatus.value !== '全部') {
      data = data.filter(item => getStatusText(item.status) === filterStatus.value);
    }

    return data;
  })

  // 分頁邏輯
  const pagedData = computed(() => {
    total.value = filteredData.value.length;
    const start = (currentPage.value - 1) * pageSize.value;
    const end = currentPage.value * pageSize.value;
    return filteredData.value.slice(start, end);
  })

  // 取得資料
  const getEvents = async () => {
    const apiBase = import.meta.env.VITE_API_BASE;
    // ★ 修改：指向 Event 相關 API
    const API_URL = `${apiBase}/getAnnualEvents.php`; 

    try {
      const response = await fetch(API_URL);
      const data = await response.json();
      eventData.value = data;
    } catch (error) {
      console.error(error);
      ElMessage.error('無法取得年度盛事資料');
    }
  }

  // Helper: 狀態數字轉中文
  const getStatusText = (status) => {
    return Number(status) === 1 ? '顯示中' : '草稿';
  }

  // Handler: 狀態下拉選單
  const handleStatusCommand = (command) => {
    filterStatus.value = command;
    currentPage.value = 1;
  }

  // 跳轉新增頁面
  const addEvent = () => {
    router.push({ name: 'AnnualEventAdd' }) 
  }

  // ★★★ 刪除功能 (使用原生 confirm) ★★★
  const handleDelete = async (id) => {
    if (!id) return;

    // ★ 修改：提示文字改為"活動"
    const isConfirmed = window.confirm(`確定要永久刪除編號 "${id}" 的活動嗎？此動作無法復原。`);
    
    if (!isConfirmed) return;

    try {
      const apiBase = import.meta.env.VITE_API_BASE;
      // ★ 修改：指向 Event 刪除 API
      const API_URL = `${apiBase}/deleteEvents.php`; 

      const response = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
      });

      const data = await response.json();

      if (data.success) {
        ElMessage.success('刪除成功');
        // 重新取得列表
        getEvents(); 
      } else {
        ElMessage.error(data.message || '刪除失敗');
      }
    } catch (error) {
      console.error(error);
      ElMessage.error('系統錯誤，無法刪除');
    }
  }

  onMounted(() => {
    getEvents();
  });
</script>

<template>
  <ListLayout>

    <template #title>
      <h6>年度盛事列表</h6>
    </template>

    <template #filters>
      <div class="filter-group">
        
        <el-input
          class="custom-search-input"
          type="text"
          v-model="eventSearch"
          placeholder="搜尋活動標題 / 內容"
          style="width: 250px;">
        </el-input>

        <el-dropdown trigger="click" @command="handleStatusCommand">
          <div class="capsule-btn">
            <span class="label">狀態: {{ filterStatus }}</span>
            <el-icon class="el-icon--right"><ArrowDown /></el-icon>
          </div>
          <template #dropdown>
            <el-dropdown-menu>
              <el-dropdown-item command="全部">全部</el-dropdown-item>
              <el-dropdown-item command="顯示中">顯示中</el-dropdown-item>
              <el-dropdown-item command="草稿">草稿</el-dropdown-item>
            </el-dropdown-menu>
          </template>
        </el-dropdown>

      </div>
    </template>

    <template #controls>
      <el-button type="primary" @click="addEvent" class="add-btn" round>
        新增活動
      </el-button>
    </template>

    <el-table :data="pagedData" stripe style="position: absolute; width: 100%; height: 100%;">
      
      <el-table-column label="活動標題" prop="title_zh" min-width="150px"></el-table-column>
      <el-table-column label="活動大綱" prop="content_summary_zh" min-width="300px" show-overflow-tooltip></el-table-column>
      <el-table-column label="活動日期" prop="launchdate" width="180px" align="center">
      </el-table-column>

      <el-table-column width="100px" align="center">
        <template #default="scope">
          <div class="action-icons">
            <router-link :to="{name:'AnnualEventEdit', params:{id: scope.row.annalevent_ID}}">
              <font-awesome-icon
                :icon="['fas', 'pen-to-square']"
                class="icon-btn edit-icon"
              />
            </router-link>
            
            <font-awesome-icon
                :icon="['fas', 'trash-can']"
                class="icon-btn delete-icon"
                @click="handleDelete(scope.row.eventID)"
              />
          </div>
        </template>
      </el-table-column>

    </el-table>

    <template #footer>
      <div class="pagination-text">
        <p>本頁有 {{ pagedData.length }} 筆 第 {{ currentPage }} 頁 / 共 {{ Math.ceil(total / pageSize) || 1 }} 頁</p>
      </div>
      <el-pagination
        v-model:current-page="currentPage"
        :total="total"
        :page-size="pageSize"
        layout="prev, pager, next"
        background
        class="pagination-btn"
      />
    </template>

  </ListLayout>
</template>

<style lang="scss" scoped>
/* 樣式直接沿用，保持一致性 */

.filter-group {
  display: flex;
  gap: 12px;
  align-items: center;
}

:deep(.custom-search-input .el-input__wrapper){
  border-radius: 50px;
  background-color: #F0F7FF;
}

.capsule-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #F0F7FF;
  border-radius: 50px;
  padding: 0 16px;
  color: #606266;
  cursor: pointer;
  font-size: 14px;
  height: 32px;
  transition: all 0.3s;
  user-select: none;
  border: 1px solid transparent;
  white-space: nowrap;

  &:hover {
    border-color: #409eff;
    color: #409eff;
    background-color: #e1f0ff;
  }
}

.el-icon--right {
  margin-left: 6px;
}

.add-btn {
  border-color: #F0F7FF;
  background-color: #F0F7FF;
  font-weight: normal;
  color: black;
  width: 140px;
  
  &:hover {
    border-color: #409eff;
    background-color: #F0F7FF;
    color: #409eff;
  }
}

/* 操作區塊 icon 樣式 */
.action-icons {
  display: flex;
  justify-content: center;
  gap: 15px; /* 圖示間距 */
}

.icon-btn {
  font-size: 18px; 
  transition: transform 0.2s;
  
  &:hover {
    transform: scale(1.1);
  }
}

.edit-icon {
  color: #0A3D70;
}

.delete-icon {
  color: #F56C6C; /* 類似 Element Danger 紅色 */
  cursor: pointer;
}

/* 覆蓋 element-plus 的背景色 */
:deep(.el-table__row--striped td.el-table__cell) {
  background-color: #F0F7FF !important;
}

:deep(.el-table .el-table__cell) {
  padding: 8px 0; /* 保持與訂單列表一致 */
}

.pagination-text {
  color: #606266;
  margin: 0;
  white-space: nowrap;
}

.pagination-btn {
  margin-top: 16px;
}
.text-truncate {
  /* 1. 不換行 */
  white-space: nowrap; 
  /* 2. 超出部分隱藏 */
  overflow: hidden; 
  /* 3. 顯示刪節號 (...) */
  text-overflow: ellipsis; 
  
  /* ★ 關鍵：必須給定一個最大寬度，否則表格會被撐開 */
  max-width: 300px; /* 數字可依你的版面調整 */
  display: block; /* 在某些表格結構下需要加上這行確保寬度生效 */
}
</style>
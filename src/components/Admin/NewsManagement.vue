<script setup>
  import { ref, computed, onMounted, watch } from 'vue'
  import ListLayout from './ListLayout.vue'
  import { useRouter } from 'vue-router'
  import { ArrowDown } from '@element-plus/icons-vue'
  import { ElMessage } from 'element-plus' 

  const currentPage = ref(1)
  const pageSize = ref(10)
  const router = useRouter()

  // 搜尋與篩選變數
  const newsSearch = ref('')
  const filterStatus = ref('全部')
  
  const newsData = ref([])

  // ★★★ 核心篩選邏輯 ★★★
  const filteredData = computed(() => {
    let data = newsData.value; 

    // 1. 關鍵字搜尋 (標題 或 內容)
    if (newsSearch.value) {
      const keyword = newsSearch.value.toLowerCase().trim();
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
    const start = (currentPage.value - 1) * pageSize.value;
    const end = currentPage.value * pageSize.value;
    return filteredData.value.slice(start, end);
  })

  watch([newsSearch, filterStatus], () => {
    currentPage.value = 1;
  })

  // 取得資料
  const getNews = async () => {
    const apiBase = import.meta.env.VITE_API_BASE;
    const API_URL = `${apiBase}/getNews.php`;

    try {
      const response = await fetch(API_URL);
      const data = await response.json();
      newsData.value = data;
    } catch (error) {
      console.error(error);
      ElMessage.error('無法取得最新消息資料');
    }
  }

  // Helper: 狀態數字轉中文
  const getStatusText = (status) => {
    return Number(status) === 1 ? '顯示中' : '草稿';
  }

  // Handler: 狀態下拉選單
  const handleStatusCommand = (command) => {
    filterStatus.value = command;
  }

  // 跳轉新增頁面
  const addNews = () => {
    router.push({ name: 'NewsAdd' })
  }

  // ★★★ 刪除功能 (使用原生 confirm) ★★★
  const handleDelete = async (id) => {
    // 1. 先確認 ID 存在
    if (!id) return;

    // 2. 彈出原生確認視窗
    const isConfirmed = window.confirm(`確定要永久刪除編號 "${id}" 的消息嗎？此動作無法復原。`);
    
    // 3. 如果使用者按「取消」，則直接結束
    if (!isConfirmed) return;

    // 4. 使用者按「確定」，執行刪除 API
    try {
      const apiBase = import.meta.env.VITE_API_BASE;
      const API_URL = `${apiBase}/deleteNews.php`;

      const response = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
      });

      const data = await response.json();

      if (data.success) {
        ElMessage.success('刪除成功');
        // 刪除成功後，重新取得列表資料
        getNews(); 
      } else {
        ElMessage.error(data.message || '刪除失敗');
      }
    } catch (error) {
      console.error(error);
      ElMessage.error('系統錯誤，無法刪除');
    }
  }

  onMounted(() => {
    getNews();
  });
</script>

<template>
  <ListLayout>

    <template #title>
      <h6>最新消息列表</h6>
    </template>

    <template #filters>
      <div class="filter-group">
        
        <el-input
          class="custom-search-input"
          type="text"
          v-model="newsSearch"
          placeholder="搜尋標題 / 內容關鍵字"
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
      <el-button type="primary" @click="addNews" class="add-btn" round>
        新增消息
      </el-button>
    </template>

    <el-table :data="pagedData" stripe style="position: absolute; width: 100%; height: 100%;">
      
      <el-table-column label="標題" prop="title_zh" min-width="200px"></el-table-column>
      
      <el-table-column label="上稿日期" prop="createdate" width="180px" align="center">
      </el-table-column>

      <el-table-column label="狀態" prop="status" width="120px" align="center">
        <template #default="scope">
          <span>
            {{ getStatusText(scope.row.status) }}
          </span>
        </template>
      </el-table-column>

      <el-table-column width="100px" align="center">
        <template #default="scope">
          <div class="action-icons">
            <router-link :to="{name:'NewsEdit', params:{id: scope.row.newsID}}">
              <font-awesome-icon
                :icon="['fas', 'pen-to-square']"
                class="icon-btn edit-icon"
              />
            </router-link>
              <font-awesome-icon
                :icon="['fas', 'trash-can']"
                class="icon-btn delete-icon"
                @click="handleDelete(scope.row.newsID)"
              />
          </div>
        </template>
      </el-table-column>

    </el-table>

    <template #footer>
      <div class="pagination-text">
        <p>本頁有 {{ pagedData.length }} 筆 第 {{ currentPage }} 頁 / 共 {{ Math.ceil(filteredData.length / pageSize) || 1 }} 頁</p>
      </div>
      <el-pagination
        v-model:current-page="currentPage"
        :total="filteredData.length"
        :page-size="pageSize"
        layout="prev, pager, next"
        background
        class="pagination-btn"
      />
    </template>

  </ListLayout>
</template>

<style lang="scss" scoped>
/* 樣式直接沿用 CouponManagement */

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

/* 刪除按鈕的外層 wrapper (重要：確保有點擊手勢) */
.delete-btn-wrapper {
  cursor: pointer;
  display: inline-flex;
  align-items: center;
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
  color: #F56C6C; 
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
</style>
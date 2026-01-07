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

    // 2. 狀態篩選 (如果未來有開放篩選功能可保留)
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
    // 指向正確的讀取 API
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

  // 跳轉新增頁面
  const addEvent = () => {
    router.push({ name: 'AnnualEventAdd' }) 
  }

  // ★★★ 刪除功能 (修正版) ★★★
  const handleDelete = async (id) => {
    // 1. 先確認 ID 存在
    if (!id) {
        console.error("刪除失敗：ID 為 undefined，請檢查 template 中的欄位名稱");
        return;
    }

    // 2. 彈出確認視窗
    const isConfirmed = window.confirm(`確定要永久刪除編號 "${id}" 的年度盛事嗎？此動作無法復原。`);
    
    // 3. 取消則結束
    if (!isConfirmed) return;

    // 4. 執行 API
    try {
      const apiBase = import.meta.env.VITE_API_BASE;
      // ★ 修正：API 檔名要對應 deleteAnnualEvent.php
      const API_URL = `${apiBase}/deleteAnnualEvent.php`; 

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
      </div>
    </template>

    <template #controls>
      <el-button type="primary" @click="addEvent" class="add-btn" round>
        新增活動
      </el-button>
    </template>

    <el-table :data="pagedData" stripe style="position: absolute; width: 100%; height: 100%;">
      
      <el-table-column label="活動標題" prop="title_zh" min-width="150px"></el-table-column>
      
      <el-table-column label="活動大綱" prop="content_summary_zh" min-width="300px" show-overflow-tooltip>
      </el-table-column>
      
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
                @click="handleDelete(scope.row.annalevent_ID)"
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
/* 樣式保持不變 */

.filter-group {
  display: flex;
  gap: 12px;
  align-items: center;
}

:deep(.custom-search-input .el-input__wrapper){
  border-radius: 50px;
  background-color: #F0F7FF;
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

.action-icons {
  display: flex;
  justify-content: center;
  gap: 15px;
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

:deep(.el-table__row--striped td.el-table__cell) {
  background-color: #F0F7FF !important;
}

:deep(.el-table .el-table__cell) {
  padding: 8px 0;
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
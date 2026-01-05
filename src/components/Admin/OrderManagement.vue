<script setup>
  import { ref, computed, onMounted } from 'vue'
  import ListLayout from './ListLayout.vue'
  import { ArrowDown } from '@element-plus/icons-vue'
  import { ElMessage } from 'element-plus'

  const currentPage = ref(1)
  const pageSize = ref(10)
  const total = ref(0)

  // 搜尋與篩選狀態
  const memberSearch = ref('')
  const filterStatus = ref('全部') // 用來綁定 dropdown 顯示文字
  const orderData = ref([])

  // 分頁計算
  const pagedData = computed(() => {
    // 這裡只做分頁切片，若需前端搜尋功能可在此加入 .filter
    return orderData.value.slice((currentPage.value - 1) * pageSize.value , currentPage.value * pageSize.value)
  })

  // 取得訂單列表
  const getOrders = async () => {
    const apiBase = import.meta.env.VITE_API_BASE;
    const API_URL = `${apiBase}/getOrders.php`;

    try {
      const response = await fetch(API_URL);
      const data = await response.json();
      orderData.value = data;
      total.value = data.length;
    } catch (error) {
      console.error(error);
      ElMessage.error('無法取得訂單資料');
    }
  }

  // 切換訂單狀態篩選
  const handleCommand = (command) => {
    filterStatus.value = command;
    // TODO: 這裡可以呼叫過濾 function (記得要把中文轉回數字去比對，或請後端支援)
  }
  
  // ★★★ 新增 helper 1: 數字轉中文 ★★★
  const getStatusText = (status) => {
    const s = Number(status);
    const map = {
      0: '未付款',
      1: '已付款',
      2: '已出貨',
      3: '已完成',
      4: '已取消'
    };
    return map[s] || '未知';
  }

  onMounted (() => {
    getOrders();
  });
</script>

<template>
  <ListLayout>

    <template #title>
      <h6>訂單列表</h6>
    </template>

    <template #filters>
      <div class="filter-group">
        <el-input 
          class="custom-search-input" 
          type="text" 
          v-model="memberSearch" 
          placeholder="搜尋訂單編號/會員姓名" 
          style="width: 300px;"
        />
        
        <el-dropdown trigger="click" @command="handleCommand">
          <div class="capsule-btn">
            <span class="label">訂單狀態: {{ filterStatus }}</span>
            <el-icon class="el-icon--right"><ArrowDown /></el-icon>
          </div>

          <template #dropdown>
            <el-dropdown-menu>
              <el-dropdown-item command="全部">全部</el-dropdown-item>
              <el-dropdown-item command="已付款">已付款</el-dropdown-item>
              <el-dropdown-item command="未付款">未付款</el-dropdown-item>
              <el-dropdown-item command="已取消">已取消</el-dropdown-item>
            </el-dropdown-menu>
          </template>
        </el-dropdown>
      </div>
    </template>

    <el-table :data="pagedData" stripe style="position: absolute; width: 100%; height: 100%;">
      <el-table-column label="訂單編號" width="120px" prop="order_ID"></el-table-column>
      <el-table-column label="會員姓名" prop="name_en" min-width="150px"></el-table-column>
      <el-table-column label="付款方式" prop="payment" width="120px"></el-table-column>
      <el-table-column label="運送方式" prop="shipping" width="120px"></el-table-column>
      
      <el-table-column label="訂單狀態" prop="status" width="120px">
        <template #default="scope">
          <span>
            {{ getStatusText(scope.row.status) }}
          </span>
        </template>
      </el-table-column>

      <el-table-column label="建立時間" prop="date" width="180px"></el-table-column>
      
      <el-table-column label="明細" width="80px" align="center">
        <template #default="{ row }">
          <router-link :to="{name:'OrderDetails', params:{id: row.order_ID}}">
            <font-awesome-icon :icon="['fas', 'magnifying-glass']" class="search-icon" />
          </router-link>
        </template>
      </el-table-column>
    </el-table>

    <template #footer>
      <div class="pagination-text">
        <p>本頁有 {{ pagedData.length }} 筆 第 {{ currentPage }} 頁 / 共 {{ Math.ceil(total / pageSize) }} 頁</p>
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
/* 搜尋框與篩選器容器 */
.filter-group {
  display: flex;
  gap: 16px; /* 搜尋框跟下拉選單的間距 */
  align-items: center;
}

/* 搜尋框樣式 */
:deep(.custom-search-input .el-input__wrapper){
  border-radius: 50px;
  background-color: #F0F7FF;
}

/* 下拉選單按鈕樣式 */
.capsule-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #F0F7FF; 
  border-radius: 50px;
  padding: 0 20px;
  color: #606266;
  cursor: pointer;
  font-size: 14px;
  height: 32px; /* 與 element-plus input 預設高度一致 */
  transition: all 0.3s;
  user-select: none;
  border: 1px solid transparent;

  &:hover {
    border-color: #409eff;
    color: #409eff;
    background-color: #e1f0ff;
  }
}

.el-icon--right {
  margin-left: 8px;
}

/* 圖標顏色 */
.search-icon{
  font-size: 18px;
  color: #0A3D70;
  transition: transform 0.2s;
  
  &:hover {
    transform: scale(1.1);
    color: #409eff;
  }
}

/* 表格背景色覆蓋 */
:deep(.el-table__row--striped td.el-table__cell) {
  background-color: #F0F7FF !important;
}

/* 修改內建行高 */
:deep(.el-table .el-table__cell) {
  padding: 8px 0;
}

/* 分頁文字 */
.pagination-text {
  color: #606266;
  margin: 0;
  white-space: nowrap;
}

/* 分頁按鈕間距 */
.pagination-btn{
  margin-top: 16px;
}
</style>
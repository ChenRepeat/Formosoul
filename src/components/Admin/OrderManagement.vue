<script setup>
  import { ref, computed, onMounted, watch } from 'vue'
  import ListLayout from './ListLayout.vue'
  import { ArrowDown } from '@element-plus/icons-vue'
  import { ElMessage } from 'element-plus'

  const currentPage = ref(1)
  const pageSize = ref(10)

  const memberSearch = ref('')
  const filterStatus = ref('全部') 
  const orderData = ref([])

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

  const filteredData = computed(() => {
    let results = orderData.value;

    if (filterStatus.value !== '全部') {
      results = results.filter(item => {
        return getStatusText(item.status) === filterStatus.value;
      });
    }

    if (memberSearch.value) {
      const keyword = memberSearch.value.toLowerCase().trim();
      results = results.filter(item => {
        const idMatch = item.order_ID ? String(item.order_ID).toLowerCase().includes(keyword) : false;
        const nameMatch = item.name_en ? item.name_en.toLowerCase().includes(keyword) : false;
        return idMatch || nameMatch;
      });
    }

    return results;
  })

  const pagedData = computed(() => {
    return filteredData.value.slice((currentPage.value - 1) * pageSize.value , currentPage.value * pageSize.value)
  })

  watch([memberSearch, filterStatus], () => {
    currentPage.value = 1;
  })

  const getOrders = async () => {
    const apiBase = import.meta.env.VITE_API_BASE;
    const API_URL = `${apiBase}/getOrders.php`;

    try {
      const response = await fetch(API_URL);
      const data = await response.json();
      orderData.value = data;
    } catch (error) {
      console.error(error);
      ElMessage.error('無法取得訂單資料');
    }
  }

  const handleCommand = (command) => {
    filterStatus.value = command;
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
              <el-dropdown-item command="已出貨">已出貨</el-dropdown-item>
              <el-dropdown-item command="已完成">已完成</el-dropdown-item>
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
        <p>本頁有 {{ pagedData.length }} 筆 第 {{ currentPage }} 頁 / 共 {{ Math.ceil(filteredData.length / pageSize) }} 頁</p>
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
.filter-group {
  display: flex;
  gap: 16px;
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
  padding: 0 20px;
  color: #606266;
  cursor: pointer;
  font-size: 14px;
  height: 32px; 
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

.search-icon{
  font-size: 18px;
  color: #0A3D70;
  transition: transform 0.2s;
  
  &:hover {
    transform: scale(1.1);
    color: #409eff;
  }
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

.pagination-btn{
  margin-top: 16px;
}
</style>
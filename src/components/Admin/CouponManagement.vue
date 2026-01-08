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
  const couponSearch = ref('')
  const filterStatus = ref('全部') 
  const filterType = ref('全部')   
   
  const couponData = ref([])

  // ★★★ 核心篩選邏輯 ★★★
  const filteredData = computed(() => {
    let data = couponData.value;

    // 1. 關鍵字搜尋 (編號 或 名稱)
    if (couponSearch.value) {
      const keyword = couponSearch.value.toLowerCase().trim();
      data = data.filter(item => {
        const nameMatch = item.name ? item.name.toLowerCase().includes(keyword) : false;
        const idMatch = item.coupons_ID ? String(item.coupons_ID).toLowerCase().includes(keyword) : false;
        return nameMatch || idMatch;
      });
    }

    // 2. 狀態篩選
    if (filterStatus.value !== '全部') {
      data = data.filter(item => getStatusText(item.status) === filterStatus.value);
    }

    // 3. 類型篩選 (依照 discount 數值判斷)
    if (filterType.value !== '全部') {
      // 這裡改用 getDiscountCategory 來判斷該筆資料屬於哪一類，再跟選單比對
      data = data.filter(item => getDiscountCategory(item.discount) === filterType.value);
    }

    return data;
  })

  // 分頁邏輯
  const pagedData = computed(() => {
    const start = (currentPage.value - 1) * pageSize.value;
    const end = currentPage.value * pageSize.value;
    return filteredData.value.slice(start, end);
  })

  watch([couponSearch, filterStatus], () => {
    currentPage.value = 1;
  })

  // 取得資料
  const getCoupons = async () => {
    const apiBase = import.meta.env.VITE_API_BASE;
    const API_URL = `${apiBase}/getCoupons.php`; 

    try {
      const response = await fetch(API_URL);
      const data = await response.json();
      couponData.value = data;
    } catch (error) {
      console.error(error);
      ElMessage.error('無法取得優惠券資料');
    }
  }

  // Helper: 狀態數字轉中文
  const getStatusText = (status) => {
    return Number(status) === 1 ? '啟用中' : '草稿'; 
  }

  // ★★★ 新增 Helper: 根據 discount 數值回傳分類名稱 (用於篩選比對) ★★★
  // const getDiscountCategory = (val) => {
  //   const discount = Number(val);
  //   if (discount === 0) {
  //     return '免運';
  //   } else if (discount > 0 && discount < 100) {
  //     return '折扣百分比';
  //   } else if (discount >= 100) {
  //     return '定額折抵';
  //   }
  //   return '其他';
  // }

  // Handler: 狀態下拉選單
  const handleStatusCommand = (command) => {
    filterStatus.value = command;
  }

  // Handler: 類型下拉選單
  const handleTypeCommand = (command) => {
    filterType.value = command;
  }

  const addCoupon = () => {
    router.push({ name: 'CouponAdd' })
  }

  onMounted(() => {
    getCoupons();
  });
</script>

<template>
  <ListLayout>

    <template #title>
      <h6>優惠券列表</h6>
    </template>

    <template #filters>
      <div class="filter-group">
        
        <el-input 
          class="custom-search-input" 
          type="text" 
          v-model="couponSearch" 
          placeholder="搜尋名稱/編號" 
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
              <el-dropdown-item command="啟用中">啟用中</el-dropdown-item>
              <el-dropdown-item command="草稿">草稿</el-dropdown-item>
            </el-dropdown-menu>
          </template>
        </el-dropdown>

        </div>
    </template>

    <template #controls>
      <el-button type="primary" @click="addCoupon" class="add-btn" round>
        新增優惠券
      </el-button>
    </template>

    <el-table :data="pagedData" stripe style="position: absolute; width: 100%; height: 100%;">
      
      <el-table-column label="優惠券編號" prop="coupons_ID" width="120px"></el-table-column>
      
      <el-table-column label="名稱" prop="name" min-width="200px"></el-table-column>
      
   <el-table-column label="折扣" prop="discount" width="160px">
        <template #default="scope">
          <span>{{ scope.row.discount }}</span>          
          </template>
      </el-table-column>

      <el-table-column label="使用門檻" prop="threshold" width="100px">
        <template #default="scope">
          <span v-if="scope.row.threshold > 0">
            NT${{ scope.row.threshold }}
          </span>
          <span v-else>
            無門檻
          </span>
        </template>
      </el-table-column>

      <el-table-column label="有效期限" width="200px">
        <template #default="scope">
          {{ scope.row.startdate }} ~ {{ scope.row.enddate }}
        </template>
      </el-table-column>

      <el-table-column label="狀態" prop="status" width="80px">
        <template #default="scope">
          <span>
            {{ getStatusText(scope.row.status) }}
          </span>
        </template>
      </el-table-column>

      <el-table-column width="60px" align="center">
        <template #default="scope">
          <router-link :to="{name:'CouponEdit', params:{id: scope.row.coupons_ID}}">
            <font-awesome-icon 
              :icon="['fas', 'pen-to-square']" 
              class="edit-icon" 
              style="cursor: pointer;"
            />
          </router-link>
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
/* 搜尋框與篩選器容器 */
.filter-group {
  display: flex;
  gap: 12px; /* 間距 */
  align-items: center;
}

/* 搜尋框樣式 */
:deep(.custom-search-input .el-input__wrapper){
  border-radius: 50px;
  background-color: #F0F7FF;
}

/* 下拉選單按鈕樣式 (膠囊) */
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
  height: 32px; /* 與 element-plus input 預設高度一致 */
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

.edit-icon {
  font-size: 20px;
  color: #0A3D70;
}

/* 覆蓋 element-plus 的背景色 */
:deep(.el-table__row--striped td.el-table__cell) {
  background-color: #F0F7FF !important; 
}

/* 修改內建行高 */
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
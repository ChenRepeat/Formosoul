<script setup>
  import { ref, computed, onMounted } from 'vue'
  import ListLayout from './ListLayout.vue'
  import { useRouter } from 'vue-router'
  import { ElMessage } from 'element-plus'

  const router = useRouter()

  const currentPage = ref(1)
  const pageSize = ref(10)
  const total = ref(0)

  // 搜尋關鍵字
  const productSearch = ref('')
  const productData = ref([])

  // 分頁計算 (如果有做前端搜尋過濾，建議要在這裡加入 filter 邏輯)
  const pagedData = computed(() => {
    return productData.value.slice((currentPage.value - 1) * pageSize.value , currentPage.value * pageSize.value)
  })

  // 取得商品列表
  const getProductData = async () => {
    const apiBase = import.meta.env.VITE_API_BASE;
    const API_URL = `${apiBase}/getProductData.php`;

    try {
      const response = await fetch(API_URL);
      const data = await response.json();
      productData.value = data;
      total.value = data.length;
    } catch (error) {
      console.error(error);
      ElMessage.error('無法取得商品資料');
    }
  }

  // 圖片路徑處理
  const getImageUrl = (filename) => {
    const imgBase = import.meta.env.VITE_PRODUCT_IMG_BASE; 
    return `${imgBase}${filename}`;
  }

  // 跳轉新增頁面
  const addProduct = () => {
    router.push({ name: 'ProductAdd' })
  }

  // 刪除功能
  const handleDelete = async (id) => {
    const isConfirmed = confirm(`確定要永久刪除編號="${id}"商品嗎？此動作無法復原。`);
    
    if (!isConfirmed) return;

    try {
      const apiBase = import.meta.env.VITE_API_BASE;
      const API_URL = `${apiBase}/deleteProduct.php`;

      const response = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
      });

      const data = await response.json();

      if (data.success) {
        ElMessage.success('刪除成功');
        // 刪除後重新抓取資料
        getProductData();
      } else {
        ElMessage.error(data.message || '刪除失敗');
      }
    } catch (error) {
      console.error(error);
      ElMessage.error('系統錯誤，無法刪除');
    }
  }

  onMounted (() => {
    getProductData();
  });
</script>

<template>
  <ListLayout>

    <template #title>
      <h6>商品列表</h6>
    </template>

    <template #filters>
      <el-input 
        class="custom-search-input" 
        type="text" 
        v-model="productSearch" 
        placeholder="搜尋商品名稱/編號" 
        style="width: 400px;"
      />
    </template>

    <template #controls>
      <el-button type="primary" @click="addProduct" class="add-btn" round>
        新增商品
      </el-button>
    </template>

    <el-table :data="pagedData" stripe style="position: absolute; width: 100%; height: 100%;">
      
      <el-table-column label="圖片" width="60px">
        <template #default="scope">
          <div v-if="scope.row.main_image" style="display: flex; align-items: center; justify-content: center;">
            <img 
              :src="getImageUrl(scope.row.main_image)" 
              alt="主圖"
              style="width: 24px; height: 24px;  object-fit: cover;"
            />
          </div>
          <div v-else>
            <span style="color: #ccc; font-size: 12px;">無</span>
          </div>
        </template>
      </el-table-column>

      <el-table-column label="編號" prop="product_ID" width="130px"></el-table-column>
      <el-table-column label="商品名稱" prop="name_zh" min-width="180px"></el-table-column>
      <el-table-column label="分類" prop="type_zh" width="100px"></el-table-column>
      <el-table-column label="價格" prop="price" width="100px">
        <template #default="scope">
           $ {{ scope.row.price }}
        </template>
      </el-table-column>
      <el-table-column label="庫存" prop="stock" width="90px"></el-table-column>
      
      <el-table-column label="狀態" prop="product_status" width="90px">
        <template #default="scope">
          {{ console.log('Row Data:', scope.row) }}
          <span v-if="scope.row.product_status == 1">
            上架中
          </span>
          <span v-else style="color: gray;">
            已下架
          </span>
        </template>
      </el-table-column>

      <el-table-column width="50px">
        <template #default="scope">
          <router-link :to="{name:'ProductEdit', params: { id: scope.row.product_ID }}">
            <font-awesome-icon :icon="['fas', 'pen-to-square']" class="edit-icon" />
          </router-link>
        </template>
      </el-table-column>

      <el-table-column width="50px">
        <template #default="scope">
          <font-awesome-icon 
            :icon="['fas', 'trash-can']" 
            class="delete-icon" 
            @click="handleDelete(scope.row.product_ID)"
            style="cursor: pointer;" 
          />
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
/* 搜尋框樣式 */
:deep(.custom-search-input .el-input__wrapper){
  border-radius: 50px;
  background-color: #F0F7FF;
}

/* 新增按鈕樣式 (含 Hover) */
.add-btn{
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

/* 圖標顏色 */
.edit-icon{
  font-size: 20px;
  color: #0A3D70;
}
.delete-icon{
  font-size: 20px;
  color: #F56C6C; /* 刪除用紅色比較直覺 */
  transition: opacity 0.2s;
  
  &:hover {
    opacity: 0.7;
  }
}

/* 表格背景色覆蓋 */
:deep(.el-table__row--striped td.el-table__cell) {
  background-color: #F0F7FF !important;
}

/* 修改內建行高 */
:deep(.el-table .el-table__cell) {
  padding: 0;
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
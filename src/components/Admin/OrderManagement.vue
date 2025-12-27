<script setup>
  import { ref, computed, onMounted } from 'vue'
  import ListLayout from './ListLayout.vue'
  import { ArrowDown } from '@element-plus/icons-vue'

  const currentPage = ref(1)
  const pageSize = ref(10)
  const total = ref(0)

  const memberSearch = ref('')
  const orderData = ref([])

  const pagedData = computed( () => {
    return orderData.value.slice((currentPage.value - 1) * pageSize.value , currentPage.value * pageSize.value)
  })

  const getOrders = async () => {
    const apiBase = import.meta.env.VITE_API_BASE;
    const API_URL = `${apiBase}/getOrders.php`;


    const response = await fetch(API_URL);
    const data = await response.json();
    orderData.value = data;

    total.value = data.length;
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

  <template #controls>
    <el-input class="custom-search-input" type="text" v-model="memberSearch" placeholder="搜尋訂單編號/會員姓名" style="width: 400px;"></el-input>
    <el-dropdown trigger="click">
    <div class="capsule-btn">
      <span class="label">訂單狀態: 全部</span>
      <el-icon class="el-icon--right"><ArrowDown /></el-icon>
    </div>

    <template #dropdown>
      <el-dropdown-menu>
        <el-dropdown-item>全部</el-dropdown-item>
        <el-dropdown-item>已付款</el-dropdown-item>
        <el-dropdown-item>未付款</el-dropdown-item>
      </el-dropdown-menu>
    </template>
  </el-dropdown>
  </template>

  <el-table :data="pagedData" stripe>
      <el-table-column label="訂單編號" width="100px" prop="order_ID"></el-table-column>
      <el-table-column label="會員姓名" prop="name_en"></el-table-column>
      <el-table-column label="付款方式" prop="payment"></el-table-column>
      <el-table-column label="運送方式" prop="shipping"></el-table-column>
      <el-table-column label="訂單狀態" prop="status"></el-table-column>
      <el-table-column label="建立時間" prop="date"></el-table-column>
      <el-table-column label="訂單明細">
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
        layout="prev, pager, next"
        background
        class="pagination-btn"
      />
    </template>

  </ListLayout>

</template>
<style lang="scss" scoped>

:deep(.custom-search-input .el-input__wrapper){
  border-radius:50px;
  background-color: #F0F7FF;
}
.add-btn{
  background-color: #F0F7FF;
  width: 140px;
}
.search-icon{
  font-size: 20px;
  color: #0A3D70;
}
/* 覆蓋element-plus的背景色 */
:deep(.el-table__row--striped td.el-table__cell) {
  background-color: #F0F7FF !important; /* 換成淺藍色 */
}
/* 修改內建行高 */
:deep(.el-table .el-table__cell) {
  padding: 4px 0;
}

.pagination-text {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  bottom: -50px;
  
  font-size: 14px;
  color: #606266;
  margin: 0;
  white-space: nowrap;
}

.capsule-btn {
  display: flex;            /* 讓文字跟箭頭並排 */
  align-items: center;      /* 垂直置中 */
  justify-content: center;
  
  background-color: #F0F7FF; /* 與左邊 input 同樣的淺藍色背景 */
  border-radius: 50px;       /* 膠囊圓角 */
  padding: 8px 20px;         /* 內距：讓字不要貼邊，看起來像按鈕 */
  
  color: #606266;            /* 文字顏色 */
  cursor: pointer;           /* 滑鼠移上去變手指 */
  font-size: 14px;
  border: 1px solid transparent; /* 預留邊框位置，若需要邊框可改成 #dcdfe6 */
  
  /* 避免文字換行 */
  white-space: nowrap; 

  /* 為了跟左邊的 input 高度對齊 (視情況調整) */
  height: 32px; 
  box-sizing: border-box; 
  
  transition: all 0.3s;
}

/* 滑鼠移上去的效果 (選擇性) */
.capsule-btn:hover {
  background-color: #e1f0ff;
}

/* 確保箭頭跟文字有一點距離 */
.el-icon--right {
  margin-left: 8px;
}
</style>

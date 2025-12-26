<script setup>
  import { ref, computed, onMounted } from 'vue'
  import ListLayout from './ListLayout.vue'

  const currentPage = ref(1)
  const pageSize = ref(10)
  const total = ref(0)

  const memberSearch = ref('')
  const orderData = ref([])

  const pagedData = computed( () => {
    return orderData.value.slice((currentPage.value - 1) * pageSize.value , currentPage.value * pageSize.value)
  })

  const getOrderData = async () => {
    const apiBase = import.meta.env.VITE_API_BASE;
    const API_URL = `${apiBase}/getOrderData.php`;


    const response = await fetch(API_URL);
    const data = await response.json();
    orderData.value = data;

    total.value = data.length;
  }





  
  onMounted (() => {
    getOrderData();
  });
</script>
<template>
  <ListLayout>

  <template #title>
    <h6>訂單列表</h6>
  </template>

  <template #controls>
    <el-input class="custom-search-input" type="text" v-model="memberSearch" placeholder="搜尋訂單編號/會員姓名" style="width: 400px;"></el-input>
    <el-dropdown trigger="click">訂單狀態:全部</el-dropdown>
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
          <router-link :to="{name:'OrderDetails', params:{id: row_order_ID}}">
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
</style>

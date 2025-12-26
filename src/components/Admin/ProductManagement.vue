<script setup>
  import { ref, computed, onMounted } from 'vue'
  import ListLayout from './ListLayout.vue'

  const currentPage = ref(1)
  const pageSize = ref(10)
  const total = ref(0)

  const memberSearch = ref('')
  const memberData = ref([])

  const pagedData = computed( () => {
    return memberData.value.slice((currentPage.value - 1) * pageSize.value , currentPage.value * pageSize.value)
  })

  const getMemberData = async () => {
    const apiBase = import.meta.env.VITE_API_BASE;
    const API_URL = `${apiBase}/getMemberData.php`;


    const response = await fetch(API_URL);
    const data = await response.json();
    memberData.value = data;

    total.value = data.length;
  }





  
  onMounted (() => {
    getMemberData();
  });
</script>
<template>
  <ListLayout>

  <template #title>
    <h6>商品列表</h6>
  </template>

  <template #controls>
    <el-input class="custom-search-input" type="text" v-model="memberSearch" placeholder="搜尋商品名稱/編號" style="width: 400px;"></el-input>
    <router-link :to="{name:'ProductAdd'}">
      <el-button class="add-btn" round>新增商品</el-button>
    </router-link>
  </template>

  <el-table :data="pagedData" stripe>
      <el-table-column label="商品主圖" width="100px"></el-table-column>
      <el-table-column label="商品編號" prop=""></el-table-column>
      <el-table-column label="商品名稱" prop=""></el-table-column>
      <el-table-column label="分類" prop=""></el-table-column>
      <el-table-column label="價格" prop=""></el-table-column>
      <el-table-column label="庫存" prop=""></el-table-column>
      <el-table-column label="狀態" prop="">
        <template #default="scope">
          <span v-if="scope.row.status === 1">
            上架中
          </span>
          <span v-else style="color:gray;">
            已下架
          </span>
        </template>
      </el-table-column>
      <el-table-column width="50">
        <font-awesome-icon :icon="['fas', 'pen-to-square']" class="edit-icon" />
      </el-table-column>
      <el-table-column width="50">
        <font-awesome-icon :icon="['fas', 'trash-can']" class="delete-icon" />
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
.edit-icon{
  font-size: 20px;
  color: #0A3D70;
}
.delete-icon{
  font-size: 20px;
  color: red;
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

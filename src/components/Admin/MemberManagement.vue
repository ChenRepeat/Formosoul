<script setup>
  import { ref, computed, onMounted } from 'vue'

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
    console.log(response);
    const data = await response.json();
    console.log(data);
    memberData.value = data;

    total.value = data.length;
  }
  
  onMounted (() => {
    getMemberData();
  });
</script>
<template>
  <div class="page-container">
    <div class="pagination-layout">
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
    </div>
    <h6>會員列表</h6>
    <p>檢視目前系統的所有會員資料。</p>
    <div class="search-add-container">
      <el-input class="custom-search-input" type="text" v-model="memberSearch" placeholder="搜尋會員姓名/Email" style="width: 400px;"></el-input>
      <el-button class="add-btn" round>新增會員</el-button>
    </div>
    <el-table :data="pagedData" stripe>
      <el-table-column label="會員編號" prop="member_ID"  width="90px"></el-table-column>
      <el-table-column label="姓名" prop="name" width="180px"></el-table-column>
      <el-table-column label="Email" prop="email"></el-table-column>
      <el-table-column label="集點卡" prop="pointscard" width="70px"></el-table-column>
      <el-table-column label="註冊日期" width="110px">
        <template #default="scope">
          {{ scope.row.createdate?.split(' ')[0] }}
        </template>
      </el-table-column>
      <el-table-column label="權限" width="90px" prop="role">
        <template #default="scope">
          <span v-if="scope.row.role === 1">
            管理者
          </span>
          <span v-else>
            一般會員
          </span>
        </template>
      </el-table-column>
      <el-table-column label="會員狀態" width="90px" prop="status">
        <template #default="scope">
          <span v-if="scope.row.status === 1">
            啟用
          </span>
          <span v-else style="color:red;">
            停用
          </span>
        </template>
      </el-table-column>
      <el-table-column width="50px">
        <font-awesome-icon :icon="['fas', 'pen-to-square']" class="edit-icon" />
      </el-table-column>
    </el-table>
  </div>
</template>
<style lang="scss" scoped>
.page-container{
  position: relative; 
  min-height: calc(100vh - 100px); /* 撐到底部 */
  padding-bottom: 60px;
}
.el-main p{
  color: #B0B0B0;
  margin-bottom: 8px;
}
.search-add-container{
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
}
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
/* 覆蓋element-plus的背景色 */
:deep(.el-table__row--striped td.el-table__cell) {
  background-color: #F0F7FF !important; /* 換成淺藍色 */
}
/* 修改內建行高 */
:deep(.el-table .el-table__cell) {
  padding: 4px 0;
}

.pagination-layout {
  position: absolute;
  bottom: 0; 
  left: 0; 
  width: 100%; 
  height: 50px;  

  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding: 0 10px; 
}

.pagination-text {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  
  font-size: 14px;
  color: #606266;
  margin: 0;
  white-space: nowrap;
}
</style>

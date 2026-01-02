<template>
  <div class="page-container">
    
    <div class="header-layout">
      <div class="header-section">
        <slot name="title"></slot>
      </div>

      <div class="search-add-container">
        <slot name="controls"></slot>
      </div>
    </div>

    <div class="table-section">
      <slot></slot>
    </div>

    <div class="pagination-layout">
      <slot name="footer"></slot>
    </div>
    
  </div>
</template>

<style lang="scss" scoped>

.page-container {
  position: relative;
  min-height: calc(100vh - 80px);
  overflow: hidden;
  display: flex;          /* 啟用 Flex 排版 */
  flex-direction: column; /* 設定為「由上到下」排列 */
}

.header-layout {
  flex-shrink: 0; /* 防止上方區塊被壓縮 */
}


:deep(h6) {
  margin: 0;
  font-size: 2rem;
}

:deep(p) {
  color: #B0B0B0;
  margin-bottom: 8px;
}

.search-add-container {
  display: flex;
  justify-content: space-between;
}

/* 中間表格區塊樣式 */
.table-section {
  flex-grow: 1;    /* 關鍵 2：讓這個區塊自動填滿所有剩餘空間 */
  overflow: hidden; /* 隱藏溢出，交給內部的 el-table 處理捲軸 */
  background-color: #fff; /* 可以加個背景色 */
  border-radius: 8px; /* 圓角 */
  border: solid 1px pink;
  /* ▼▼▼ 加入這行！非常重要 ▼▼▼ */
  position: relative;
  margin-top: 12px;
   min-height: 0; /* ← 加入這行 */
}

/* 下方分頁區塊樣式 */
.pagination-layout {
  display: flex;
  justify-content: center;
}

:deep(.el-table__row) {
  height: 30px !important; 
}
</style>
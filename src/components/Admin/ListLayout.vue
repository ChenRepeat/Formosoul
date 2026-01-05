<template>
  <div class="page-container">
    
    <div class="header-layout">
      
      <div class="header-row-title">
        <slot name="title"></slot>
      </div>

      <div class="header-row-toolbar">
        <div class="toolbar-left">
          <slot name="filters"></slot>
        </div>

        <div class="toolbar-right">
          <slot name="controls"></slot>
        </div>
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
  display: flex;
  flex-direction: column;
  padding-bottom: 24px;
}

/* --- Header 排版修改 --- */
.header-layout {
  flex-shrink: 0;
  display: flex;
  flex-direction: column; /* 垂直排列：標題在上，工具列在下 */
  gap: 16px;              /* 標題與工具列的間距 */
  margin-bottom: 16px;    /* Header 與表格的間距 */
}

/* 第一行：標題樣式 */
.header-row-title :deep(h6) {
  margin: 0;
  font-size: 1.75rem;
  color: #1a1a1a;
  line-height: 1.2;
}
/* 隱藏原本可能存在的 p 標籤 margin，或是你已經刪掉了 */
.header-row-title :deep(p) {
  margin: 0;
}

/* 第二行：工具列樣式 */
.header-row-toolbar {
  display: flex;
  justify-content: space-between; /* 關鍵：左右推開 */
  align-items: center;            /* 垂直置中 */
  flex-wrap: wrap;                /* 防止小螢幕破版 */
  gap: 16px;                      /* 當螢幕太小變成兩行時的間距 */
  min-height: 32px;               /* 確保就算沒內容也有基本高度 */
}

/* 左側篩選區塊的內部排版 */
.toolbar-left {
  display: flex;
  align-items: center;
  gap: 12px; /* 搜尋框跟篩選框之間的距離 */
}

/* 右側按鈕區塊的內部排版 */
.toolbar-right {
  display: flex;
  align-items: center;
  gap: 12px; /* 按鈕之間的距離 */
  margin-left: auto; /* 確保一定靠右 (雙重保險) */
}
/* ------------------------ */

/* 中間表格區塊樣式 (維持不變) */
.table-section {
  flex-grow: 1;
  overflow: hidden; 
  background-color: #fff; 
  border-radius: 8px; 
  position: relative;
  min-height: 0;
}

/* 下方分頁區塊樣式 (維持不變) */
.pagination-layout {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 16px;
  flex-shrink: 0;
}

:deep(.el-table__row) {
  height: 35px !important;
}
</style>
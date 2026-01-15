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
  padding-bottom: 12px; 
  min-height: calc(100vh - 80px);
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

/* --- Header 排版 --- */
.header-layout {
  flex-shrink: 0;
  display: flex;
  flex-direction: column; 
  gap: 10px;               
  margin-bottom: 10px;    
}

/* 第一行：標題樣式 */
.header-row-title :deep(h6) {
  margin: 0;
  font-size: 1.5rem;
  color: #1a1a1a;
  line-height: 1.2;
}
.header-row-title :deep(p) {
  margin: 0;
}

.header-row-toolbar {
  display: flex;
  justify-content: space-between; 
  align-items: center;            
  flex-wrap: wrap;                
  gap: 8px;
  min-height: 32px;               
}

.toolbar-left {
  display: flex;
  align-items: center;
  gap: 8px;
}

.toolbar-right {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-left: auto; 
}

/* --- 表格區塊 --- */
.table-section {
  flex-grow: 1;
  overflow: hidden; 
  background-color: #fff; 
  border-radius: 8px; 
  position: relative;
  min-height: 0;
}

/* --- 分頁區塊 --- */
.pagination-layout {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 8px; 
  flex-shrink: 0;
}

/*Element Plus 表格行高 */
:deep(.el-table__row) {
  height: 35px !important;
}
:deep(.el-table .cell) {
  line-height: 20px !important; /* 修正文字垂直置中 */
  padding-top: 0px;
  padding-bottom: 0px;
}

@media screen and (max-height: 900px) {
  .page-container {
     padding-bottom: 4px;
  }

  .header-layout {
    gap: 4px;
    margin-bottom: 6px; 
  }

  .header-row-title :deep(h6) {
    font-size: 1.25rem; 
  }

  .header-row-toolbar {
    min-height: 28px;
  }
  
  .pagination-layout {
    margin-top: 4px;
  }
}
</style>
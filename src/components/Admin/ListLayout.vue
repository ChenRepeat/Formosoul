<template>
  <div class="page-container">
    
    <div class="header-section">
      <slot name="title"></slot>
    </div>

    <div class="search-add-container">
      <slot name="controls"></slot>
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
  min-height: calc(100vh - 100px);
}


:deep(p) {
  color: #B0B0B0;
  margin-bottom: 8px;
}

.search-add-container {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
  margin-bottom: 20px;
}

.pagination-layout {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  bottom:0;
}
</style>
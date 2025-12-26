<template>
  <div class="order-view-container">
    
    <div class="page-header">
      <div class="header-left">
        <h6 class="title">訂單查看</h6>
        <span class="order-id">#{{ formData.orderId }}</span>
        <el-tag :type="getStatusType(formData.status)" effect="dark" class="status-tag">
          {{ formData.status }}
        </el-tag>
      </div>
      <el-button @click="goBack" class="add-btn" round>返回列表</el-button>
    </div>

    <div class="content-card">
      
      <div class="section-block info-section">
        <el-row :gutter="40">
          <el-col :span="8">
            <div class="info-item">
              <p>會員姓名</p>
              <div class="info-value">{{ formData.memberName }}</div>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="info-item">
              <p>付款方式</p>
              <div class="info-value">{{ formData.paymentMethod }}</div>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="info-item">
              <p>建立時間</p>
              <div class="info-value">{{ formData.createTime }}</div>
            </div>
          </el-col>
        </el-row>
      </div>

      <el-divider />

      <el-form 
        :model="formData" 
        label-position="top"
        class="order-form"
      >
        <div class="section-block form-section">
          <div class="section-header">
            <h6>收件人資料</h6>
            <p class="sub-text">出貨前可修改收件人資料</p>
          </div>

          <el-row :gutter="24">
            <el-col :span="12">
              <el-form-item label="收件人姓名 (中文)">
                <el-input v-model="formData.recipientNameZh" :suffix-icon="Edit" placeholder="請輸入中文姓名" />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="Recipient Name (English)">
                <el-input v-model="formData.recipientNameEn" :suffix-icon="Edit" placeholder="Enter name" />
              </el-form-item>
            </el-col>

            <el-col :span="12">
              <el-form-item label="聯絡電話">
                <el-input v-model="formData.phone" :suffix-icon="Edit" />
              </el-form-item>
            </el-col>
            
            <el-col :span="24">
              <el-form-item label="收件人地址 (中文)">
                <el-input v-model="formData.addressZh" :suffix-icon="Edit" />
              </el-form-item>
            </el-col>
            
            <el-col :span="24">
              <el-form-item label="Recipient Address (English)">
                <el-input 
                  v-model="formData.addressEn" 
                  :suffix-icon="Edit" 
                  type="textarea" 
                  :rows="2"
                  autosize
                />
              </el-form-item>
            </el-col>
          </el-row>
        </div>

        <div class="section-block cancel-section">
          <el-form-item label-width="0">
             <el-checkbox v-model="formData.isCancel" label="申請取消此訂單" size="large" border class="cancel-checkbox"/>
          </el-form-item>
          
          <transition name="el-zoom-in-top">
            <div v-if="formData.isCancel" class="cancel-reason-box">
              <el-form-item label="請輸入取消原因與備註">
                <el-input 
                  v-model="formData.cancelReason" 
                  type="textarea" 
                  placeholder="例如：訂錯商品、更換付款方式..." 
                />
              </el-form-item>
            </div>
          </transition>
        </div>
      </el-form>
    </div>

    <div class="content-card items-section">
      <p class="section-title mb-4">訂單明細</p>
      
      <div class="items-table-header">
        <div class="col-image">圖片</div>
        <div class="col-name">Product Name</div>
        <div class="col-qty">Quantity</div>
        <div class="col-price">Prices</div>
      </div>

      <div class="items-list">
        <div v-for="item in orderItems" :key="item.id" class="item-row">
          <div class="col-name item-info">
            <img :src="item.image" class="product-img" alt="product" />
            <div class="info-text">
              <div class="p-name">{{ item.name }}</div>
            </div>
          </div>
          <div class="col-qty item-qty">x {{ item.qty }}</div>
          <div class="col-price item-price">NT$ {{ item.price }}</div>
        </div>
      </div>
      
      <div class="order-summary">
        <div class="summary-row total">
          <span>訂單總金額</span>
          <span class="amount">NT$ 1,998</span>
        </div>
      </div>
    </div>

    <div class="footer-actions">
      <el-button class="btn-back" @click="goBack">取消並返回</el-button>
      <el-button type="primary" color="#003060" class="btn-save" @click="saveChanges">儲存變更</el-button>
    </div>

  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { Edit } from '@element-plus/icons-vue' 
import { useRouter, useRoute } from 'vue-router'

const route = useRoute()
const router = useRouter()

const currentOrderId = route.params.id

const OrderData = reactive({
  orderId: '',
  memberName: '',
  recipientNameZh: '',
  recipientNameEn: '',
  phone: '',
  addressZh: '',
  addressEn: '',
  paymentMethod: '',
  status: '',
  createTime: '',
  updateTime: '',
  isCancel: false, 
  cancelReason: ''
})

const orderItems = ref([])

const saveChanges = () => {
  console.log('儲存變更:', formData)
  alert('變更已儲存')
}

const goBack = () => {
  router.push('/admin/order-management') 
}

// 根據狀態回傳 Tag 顏色
const getStatusType = (status) => {
  if(status === '未付款') return 'danger'
  if(status === '已完成') return 'success'
  return 'info'
}
</script>

<style scoped>
.order-view-container {
  max-width: 900px;
  margin: 0 auto;
}

/* 標題區 */
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}
.header-left {
  display: flex;
  align-items: center;
  gap: 12px;
}
.title {
  margin: 0;
  color: #1a1a1a;
}
.order-id {
  color: #666;
}
.status-tag {
  padding: 0 12px;
}

/* 通用卡片樣式 */
.content-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.05);
  padding: 30px;
  margin-bottom: 24px;
}

/* 區塊標題 */
.section-title {
  color: #333;
  margin: 0 0 16px 0;
  border-left: 4px solid #003060;
  padding-left: 12px;
  line-height: 1.2;
}

.section-header {
  margin-bottom: 20px;
}
.sub-text {
  color: #888;
  margin: 4px 0 0 0;
}

.mb-4 { margin-bottom: 16px; }

/* --- 唯讀資訊區 (Info Section) --- */
.info-item {
  margin-bottom: 10px;
  font-size: 16px;
}
.info-item label {
  display: block;
  color: #888;
  margin-bottom: 4px;
}
.info-item .info-value {
  color: #333;
}

/* --- 表單優化 --- */
.order-form :deep(.el-form-item__label) {
  color: #444;
  line-height: 1.5; /* 讓 label 稍微高一點 */
  padding-bottom: 6px; /* label 與 input 的距離 */
}

/* 輸入框樣式微調 */
.order-form :deep(.el-input__wrapper),
.order-form :deep(.el-textarea__inner) {
  box-shadow: 0 0 0 1px #dcdfe6 inset;
  padding: 8px 12px;
}
.order-form :deep(.el-input__wrapper:hover) {
  box-shadow: 0 0 0 1px #003060 inset;
}

/* 取消訂單區塊 */
.cancel-section {
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px dashed #eee;
}
.cancel-checkbox {
  color: #e6a23c; /* 警告色 */
}
.cancel-checkbox :deep(.el-checkbox__inner) {
  border-color: #e6a23c;
}
.cancel-checkbox :deep(.el-checkbox__input.is-checked .el-checkbox__inner) {
  background-color: #e6a23c;
  border-color: #e6a23c;
}

/* --- 訂單明細列表 --- */
.items-table-header {
  display: flex;
  background-color: #F5F7FA;
  padding: 12px 20px;
  color: #606266;
  border-radius: 4px;
}
.item-row {
  display: flex;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #f0f0f0;
}
.item-row:last-child {
  border-bottom: none;
}

.col-name { flex: 3; }
.col-qty { flex: 1; text-align: center; }
.col-price { flex: 1; text-align: right; font-weight: 600; color: #333; }

.item-info {
  display: flex;
  align-items: center;
  gap: 16px;
}
.product-img {
  width: 60px;
  height: 60px;
  border-radius: 6px;
  object-fit: cover;
  border: 1px solid #eee;
}
.p-name { color: #333; margin-bottom: 4px; }
.p-spec { color: #999; }

/* 總結區 */
.order-summary {
  display: flex;
  justify-content: flex-end;
  padding-top: 20px;
  border-top: 1px solid #f0f0f0;
  margin-top: 10px;
}
.summary-row.total {
  color: #003060;
}
.summary-row .amount {
  margin-left: 20px;
}

/* 底部按鈕 */
.footer-actions {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 20px;
  padding-bottom: 60px;
}
.btn-save {
  padding: 12px 40px;
}
.add-btn{
  background-color: #F0F7FF;
  width: 140px;
}
</style>
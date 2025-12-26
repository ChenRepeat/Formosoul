<template>
  <div class="order-view-container">
    
    <div class="page-header">
      <div>
        <h6>訂單查看</h6>
        <p class="sub-title">(出貨前可以修改收件人資料與取消訂單)</p>
      </div>
      <el-button round @click="goBack" class="back-btn">返回商品列表</el-button>
    </div>

    <el-form 
      :model="formData" 
      label-width="140px" 
      label-position="left"
      class="order-form"
    >
      
      <el-form-item label="訂單編號">
        <el-input v-model="formData.orderId" disabled class="read-only-input" />
      </el-form-item>

      <el-form-item label="會員姓名">
        <el-input v-model="formData.memberName" disabled class="read-only-input" />
      </el-form-item>

      <el-row :gutter="20">
        <el-col :span="12">
          <el-form-item label="收件人姓名(中文)">
            <el-input v-model="formData.recipientNameZh" :suffix-icon="Edit" placeholder="請輸入" />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Recipient Name(EN)" label-width="160px">
            <el-input v-model="formData.recipientNameEn" :suffix-icon="Edit" placeholder="Enter name" />
          </el-form-item>
        </el-col>
      </el-row>

      <el-form-item label="聯絡電話">
        <el-input v-model="formData.phone" :suffix-icon="Edit" style="width: 50%;" />
      </el-form-item>

      <el-row :gutter="20">
        <el-col :span="12">
          <el-form-item label="收件地址(中文)">
            <el-input v-model="formData.addressZh" :suffix-icon="Edit" />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Recipient Address(EN)" label-width="180px">
            <el-input v-model="formData.addressEn" :suffix-icon="Edit" />
          </el-form-item>
        </el-col>
      </el-row>

      <el-form-item label="付款方式">
        <el-input v-model="formData.paymentMethod" disabled class="read-only-input" />
      </el-form-item>

      <el-form-item label="訂單狀態">
        <el-input v-model="formData.status" disabled class="read-only-input" />
      </el-form-item>

      <el-form-item label="建立時間">
        <el-input v-model="formData.createTime" disabled class="read-only-input" />
      </el-form-item>

      <el-form-item label="更新時間">
        <el-input v-model="formData.updateTime" disabled class="read-only-input" />
      </el-form-item>

      <div class="cancel-section">
        <el-form-item label-width="0">
          <el-checkbox v-model="formData.isCancel" label="是否取消訂單(出貨前)" size="large" />
        </el-form-item>
        
        <el-form-item label="備註(取消原因)">
          <el-input v-model="formData.cancelReason" placeholder="請輸入取消原因" />
        </el-form-item>
      </div>

    </el-form>

    <div class="order-items-section">
      <div class="items-header">訂單明細</div>
      
      <div class="items-table-header">
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
              <div class="p-spec">{{ item.spec }}</div>
            </div>
          </div>
          <div class="col-qty item-qty">Quantity: {{ item.qty }} item</div>
          <div class="col-price item-price">NT$ {{ item.price }}</div>
        </div>
      </div>
    </div>

    <div class="footer-actions">
      <router-link to="/orders">
        <el-button class="btn-back">返回列表</el-button>
      </router-link>
      <el-button type="primary" color="#003060" @click="saveChanges">儲存變更</el-button>
    </div>

  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { Edit } from '@element-plus/icons-vue' // 引入編輯圖示

// 模擬表單資料
const formData = reactive({
  orderId: '2025-000123',
  memberName: '王小明',
  recipientNameZh: '王小明',
  recipientNameEn: 'Xiaoming Wang',
  phone: '0912-345-678',
  addressZh: '台北市中正區魔法路 123 號 7 樓',
  addressEn: '7F., No. 123, Magic Rd., Zhongzheng Dist., Taipei City',
  paymentMethod: '信用卡',
  status: '未付款',
  createTime: '2025-05-21 14:32',
  updateTime: '系統自動產生',
  isCancel: false, // 是否取消訂單
  cancelReason: ''
})

// 模擬訂單商品列表
const orderItems = ref([
  {
    id: 1,
    name: 'Product Name',
    spec: 'Product Specifications',
    qty: 1,
    price: 999,
    image: 'https://placehold.co/100x100?text=Product' // 替換成你的商品圖
  },
  {
    id: 2,
    name: 'Product Name',
    spec: 'Product Specifications',
    qty: 1,
    price: 999,
    image: 'https://placehold.co/100x100?text=Product' // 替換成你的商品圖
  }
])

const saveChanges = () => {
  console.log('儲存變更:', formData)
  // 這裡呼叫後端 API
  alert('變更已儲存')
}
</script>

<style scoped>
.order-view-container {
  max-width: 1000px;
  margin: 0 auto;
  background-color: #fff;
  font-family: sans-serif;
}

/* 標題區 */
.page-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}
.page-header h6 {
  margin-bottom: 8px;
  color: #333;
}
.sub-title {
  color: #999;
}

/* 表單樣式 */
.read-only-input :deep(.el-input__inner) {
  background-color: #F0F5FA; /* 設計圖的淺藍底色 */
  color: #666;
}
.read-only-input :deep(.el-input__wrapper) {
  background-color: #F0F5FA;
  box-shadow: none; /* 移除邊框 */
}

/* 取消訂單區 */
.cancel-section {
  margin-top: 30px;
  margin-bottom: 40px;
}

/* --- 訂單明細列表樣式 --- */
.order-items-section {
  margin-top: 40px;
}
.items-header {
  font-size: 16px;
  color: #666;
  margin-bottom: 10px;
}

/* 列表 Header */
.items-table-header {
  display: flex;
  background-color: #F0F5FA; /* 淺藍色背景 */
  padding: 15px 20px;
  font-weight: bold;
  color: #003060;
  font-size: 18px;
  border-radius: 4px;
}

/* 列表內容 Row */
.item-row {
  display: flex;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #eee; /* 分隔線 */
}

/* 欄位寬度分配 */
.col-name {
  flex: 2; /* 佔據最多空間 */
}
.col-qty {
  flex: 1;
  text-align: center;
}
.col-price {
  flex: 1;
  text-align: right;
}

/* 商品資訊細節 */
.item-info {
  display: flex;
  align-items: center;
  gap: 20px;
}
.product-img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 4px;
}
.p-name {
  font-size: 18px;
  font-weight: bold;
  color: #003060;
  margin-bottom: 5px;
}
.p-spec {
  font-size: 14px;
  color: #666;
}
.item-qty {
  font-size: 16px;
  color: #666;
}
.item-price {
  font-size: 18px;
  color: #666;
}

/* 底部按鈕 */
.footer-actions {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 40px;
}
.back-btn{
  background-color: #F0F7FF;
  margin-top: 30px;
}

.order-form :deep(.el-form-item__label) {
  font-size: 16px;
}
/* 修改整張表單內所有輸入框的 placeholder */
.order-form :deep(.el-input__inner::placeholder),
.order-form :deep(.el-textarea__inner::placeholder) {
  font-size: 16px;
}
</style>
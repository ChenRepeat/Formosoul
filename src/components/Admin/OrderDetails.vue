<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { ArrowLeft } from '@element-plus/icons-vue' 
import { ElMessage } from 'element-plus'
import ListLayout from './ListLayout.vue' 

const route = useRoute()
const router = useRouter()

const currentOrderNumber = route.params.id 
const imgBase = import.meta.env.VITE_PRODUCT_IMG_BASE;

const loading = ref(true) 
// 儲存資料庫原始狀態
const originalStatus = ref(null)

const statusOptions = [
  { value: 0, label: '已出貨' },
  { value: 1, label: '未出貨' },
  { value: 2, label: '已完成' },
  { value: 3, label: '已付款' },
  { value: 4, label: '付款失敗/取消' },
  { value: 5, label: '等待付款' },
]

const OrderData = ref({
  orderId: '',
  memberName: '',
  recipientName: '',
  phone: '',
  address: '',
  paymentMethod: '',
  status: 1, 
  createTime: '',
  updateTime: '',
  isCancel: false, 
  cancelReason: ''
})

const orderItems = ref([])

//判斷 originalStatus (原始狀態)
const isLocked = computed(() => {
  const s = Number(originalStatus.value);
  return s === 0 || s === 2;
})

const goBack = () => {
  router.push('/admin/order-management') 
}

const getStatusType = (status) => {
  const s = Number(status); 
  if (s === 0) return 'warning'; 
  if (s === 1) return 'success'; 
  if (s === 2) return 'primary'; 
  if (s === 3) return 'success'; 
  if (s === 4) return 'info';    
  if (s === 5) return 'danger';  
  return 'info'; 
}

const getStatusText = (status) => {
  if (status === '' || status === undefined || status === null) return '';
  const s = Number(status);
  const found = statusOptions.find(opt => opt.value === s);
  return found ? found.label : '未知狀態';
}

const getOrderDetail = async () => {
  if (!currentOrderNumber) return;
  
  try {
    const apiBase = import.meta.env.VITE_API_BASE;
    const response = await fetch(`${apiBase}/getOrderDetail.php?id=${currentOrderNumber}`);
    const data = await response.json();

    if (data.error) {
        ElMessage.error(data.error);
        return;
    }

    if (data && data.info) {
        OrderData.value = {
          orderId: data.info.order_number, 
          memberName: data.info.name_en || data.info.member_ID, 
          recipientName: data.info.name_en, 
          phone: data.info.phone,
          address: data.info.address_en,
          paymentMethod: data.info.payment,
          status: Number(data.info.status), 
          createTime: data.info.date,
          updateTime: new Date().toISOString().split('T')[0],
          isCancel: Number(data.info.status) === 4, 
          cancelReason: data.info.remark
        }
        // 存原始狀態，作為鎖定依據
        originalStatus.value = Number(data.info.status);
    }

    if (data && data.items) {
        orderItems.value = data.items;
    }

  } catch (error) {
    console.error('取得訂單資料失敗:', error);
    ElMessage.error('無法取得訂單資料');
  } finally {
    loading.value = false; 
  }
}

const saveChanges = async () => {
    loading.value = true;
    
    const payload = {
        orderId: OrderData.value.orderId, 
        recipientName: OrderData.value.recipientName,
        phone: OrderData.value.phone,
        address: OrderData.value.address,
        status: OrderData.value.status, 
        isCancel: OrderData.value.isCancel,
        cancelReason: OrderData.value.cancelReason 
    };

    try {
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/updateOrderDetail.php`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (data.success) {
            ElMessage.success('訂單更新成功！');
            await getOrderDetail();
        } else {
            ElMessage.error(data.message || '更新失敗');
        }

    } catch (error) {
        console.error('更新錯誤:', error);
        ElMessage.error('系統發生錯誤，無法更新');
    } finally {
        loading.value = false;
    }
}

watch(() => OrderData.value.status, (newStatus) => {
    const s = Number(newStatus);
    if (s === 4) {
        OrderData.value.isCancel = true;
    } else {
        OrderData.value.isCancel = false;
    }
});

watch(() => OrderData.value.isCancel, (isCancel) => {
    if (isCancel) {
        OrderData.value.status = 4;
    } else {
        if (Number(OrderData.value.status) === 4) {
            OrderData.value.status = 1;
        }
    }
});

onMounted(() => {
  getOrderDetail();
});
</script>

<template>
  <ListLayout v-loading="loading">
    
    <template #title>
      <div class="header-title-flex">
        <h6>訂單查看</h6>
        <template v-if="OrderData.orderId">
            <span class="order-id">#{{ OrderData.orderId }}</span>
            <el-tag 
            :type="getStatusType(OrderData.status)" 
            effect="dark" 
            round 
            size="small" 
            class="status-tag"
            >
            {{ getStatusText(OrderData.status) }}
            </el-tag>
        </template>
      </div>
    </template>

    <template #controls>
      <el-button @click="goBack" class="back-btn" round>
        <el-icon><ArrowLeft /></el-icon>返回列表
      </el-button>
    </template>

    <div class="scroll-container">
      <div class="order-view-container" v-if="!loading">
        
        <div class="content-card">
          <h5 class="card-title">基本資訊</h5>
          
          <div class="info-group">
             <el-row :gutter="24">
               <el-col :span="6">
                 <div class="info-item">
                   <label>會員姓名</label>
                   <div class="info-value">{{ OrderData.memberName }}</div>
                 </div>
               </el-col>
               
               <el-col :span="6">
                 <div class="info-item">
                   <label>付款方式</label>
                   <div class="info-value">{{ OrderData.paymentMethod }}</div>
                 </div>
               </el-col>

               <el-col :span="6">
                 <div class="info-item">
                   <label>訂單狀態</label>
                   <el-select 
                     v-model="OrderData.status" 
                     placeholder="狀態" 
                     :disabled="isLocked"
                     style="width: 100%"
                   >
                     <el-option
                       v-for="item in statusOptions"
                       :key="item.value"
                       :label="item.label"
                       :value="item.value"
                     />
                   </el-select>
                 </div>
               </el-col>

               <el-col :span="6">
                 <div class="info-item">
                   <label>建立時間</label>
                   <div class="info-value">{{ OrderData.createTime }}</div>
                 </div>
               </el-col>
             </el-row>
          </div>

          <el-divider border-style="dashed" />

          <el-form :model="OrderData" label-position="top" class="order-form">
            <h5 class="card-title" style="margin-top: 10px;">
                收件人資料 <span class="sub-text">(出貨前可修改)</span>
            </h5>
            
            <el-row :gutter="24">
                <el-col :span="12">
                  <el-form-item label="收件人">
                    <el-input 
                        v-model="OrderData.recipientName" 
                        placeholder="Enter name" 
                        :disabled="isLocked"
                    />
                  </el-form-item>
                </el-col>

                <el-col :span="12">
                  <el-form-item label="聯絡電話">
                    <el-input v-model="OrderData.phone" :disabled="isLocked"/>
                  </el-form-item>
                </el-col>
                
                <el-col :span="24">
                  <el-form-item label="收件人地址">
                    <el-input 
                      v-model="OrderData.address"  
                      type="textarea" 
                      :rows="2"
                      autosize
                      :disabled="isLocked"
                    />
                  </el-form-item>
                </el-col>
            </el-row>

            <div class="cancel-section">
              <el-form-item label-width="0">
                 <el-checkbox 
                    v-model="OrderData.isCancel" 
                    label="取消此訂單" 
                    size="large" 
                    border 
                    class="cancel-checkbox"
                    :disabled="isLocked"
                  />
              </el-form-item>
              
              <transition name="el-zoom-in-top">
                <div v-if="OrderData.isCancel" class="cancel-reason-box">
                  <el-form-item label="請輸入取消原因與備註">
                    <el-input 
                      v-model="OrderData.cancelReason" 
                      type="textarea" 
                      :rows="3"
                      placeholder="例如：訂錯商品、更換付款方式..." 
                      :disabled="isLocked"
                    />
                  </el-form-item>
                </div>
              </transition>
            </div>
          </el-form>
        </div>

        <div class="content-card">
           <h5 class="card-title">訂單明細</h5>
           
           <div class="items-table-header">
             <div class="col-name">商品名稱 (Product)</div>
             <div class="col-qty">數量 (Qty)</div>
             <div class="col-price">金額 (Price)</div>
           </div>

           <div class="items-list">
             <div v-for="item in orderItems" :key="item.order_detail_ID" class="item-row">
               <div class="col-name item-info">
                 <img :src="`${imgBase}${item.url}`" class="product-img" alt="product" />
                 <div class="info-text">
                   <div class="p-name-en">{{ item.name_en }}</div>
                   </div>
               </div>
               <div class="col-qty item-qty">x {{ item.quantity }}</div>
               <div class="col-price item-price">NT$ {{ item.price }}</div>
             </div>
           </div>
        </div>

        <div class="footer-actions">
           <el-button @click="goBack" size="large" class="cancel-btn">返回</el-button>
           
           <el-button 
             type="primary" 
             color="#003060" 
             @click="saveChanges" 
             size="large" 
             :loading="loading"
             :disabled="isLocked"
           >
             {{ isLocked ? '訂單已鎖定' : '儲存變更' }}
           </el-button>
        </div>

      </div>
    </div>
  </ListLayout>
</template>

<style scoped>
.scroll-container {
  height: calc(100vh - 250px);
  overflow-y: auto;
  padding: 20px 20px 20px 0;
}

.order-view-container {
  max-width: 1000px;
  margin: 0 auto;
}

.header-title-flex {
  display: flex;
  align-items: center;
  gap: 12px;
}
.order-id {
  color: #909399;
  font-size: 16px;
  font-weight: normal;
}
.status-tag {
  font-weight: bold;
}

.back-btn {
  border-color: #F0F7FF;
  background-color: #F0F7FF;
  font-weight: normal;
  color: black;
  width: 140px;
}
.back-btn:hover {
  border-color: #409eff;
  background-color: #F0F7FF;
  color: #409eff;
}

.content-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.05);
  margin-bottom: 24px;
  padding: 24px; 
}

.card-title {
  margin: 0 0 20px 0;
  font-size: 16px;
  color: #333;
  border-left: 4px solid #003060;
  padding-left: 10px;
  display: flex;
  align-items: center;
}

.sub-text {
  font-size: 13px;
  color: #909399;
  margin-left: 8px;
  font-weight: normal;
}

.info-item {
  margin-bottom: 10px;
}
.info-item label {
  display: block;
  color: #606266;
  font-size: 14px;
  margin-bottom: 4px;
}
.info-item .info-value {
  color: #333;
  font-size: 16px;
  font-weight: 500;
  background-color: #f5f7fa; 
  padding: 8px 12px;
  border-radius: 4px;
  min-height: 23px; 
}

.order-form :deep(.el-form-item__label) {
  color: #444;
  font-weight: 500;
}
.bg-gray :deep(.el-input__wrapper) {
  background-color: #f5f7fa !important;
  box-shadow: 0 0 0 1px #e4e7ed inset !important;
}

.cancel-section {
  margin-top: 20px;
  padding-top: 20px;
  border-top: 1px dashed #eee;
}
.cancel-checkbox {
  color: #F56C6C;
}
.cancel-checkbox :deep(.el-checkbox__input.is-checked .el-checkbox__inner) {
  background-color: #F56C6C;
  border-color: #F56C6C;
}
.cancel-checkbox :deep(.el-checkbox__label) {
  color: #F56C6C;
  font-weight: bold;
}

.items-table-header {
  display: flex;
  background-color: #F8F9FA;
  padding: 12px 20px;
  color: #606266;
  font-size: 13px;
  font-weight: 500;
  border-radius: 4px;
  margin-bottom: 10px;
}
.item-row {
  display: flex;
  align-items: center;
  padding: 16px 20px;
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
  background-color: #fff;
}
.p-name-en { 
  color: #333; 
  font-weight: 500;
}

.footer-actions {
  display: flex;
  justify-content: center;
  gap: 16px;
  margin-top: 40px;
  padding-top: 20px;
  border-top: 1px solid #eee;
}
.cancel-btn {
  width: 120px;
}
</style>
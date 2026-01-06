<template>
  <ListLayout>
    
    <template #title>
      <h6>編輯折價券</h6>
    </template>

    <template #controls>
      <el-button @click="goBack" class="back-btn" round>
        <el-icon><ArrowLeft /></el-icon>返回列表
      </el-button>
    </template>

    <div class="scroll-container">
      <div class="form-container">
        <el-form 
          v-loading="pageLoading"
          :model="editCouponForm" 
          ref="formRef"
          label-position="top"
          class="custom-form"
        >
          
          <div class="content-card">       
            <el-row :gutter="24">
              <el-col :span="12">
                <el-form-item label="Coupon Name">
                  <el-input v-model="editCouponForm.name" placeholder="Enter coupon name" />
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-form-item label="折價券編號">
                  <el-input v-model="editCouponForm.code" disabled class="bg-gray"/>
                </el-form-item>
              </el-col>

              <el-col :span="12">
                <el-form-item label="折扣" required>
                    <el-input-number 
                      v-model="editCouponForm.discount" 
                      :min="0" 
                      :controls="false"
                      style="width: 100%;"
                      placeholder="請輸入折扣金額"
                    />
                  <!-- <div class="input-group">
                    <el-select v-model="editCouponForm.discountType" style="width: 140px; margin-right: 10px;">
                      <el-option label="定額折抵" value="amount" />
                      <el-option label="百分比折扣" value="percent" />
                      <el-option label="免運" value="shipping" />
                    </el-select>

                    <el-input-number 
                      v-model="editCouponForm.discount" 
                      :min="0" 
                      :controls="false"
                      style="flex: 1;"
                      :disabled="editCouponForm.discountType === 'shipping'"
                      :placeholder="getPlaceholder"
                    />
                  </div> -->
                </el-form-item>
              </el-col>

              <el-col :span="12">
                <el-form-item label="使用門檻" required>
                  <div class="input-group">
                    <el-select v-model="editCouponForm.thresholdType" style="width: 140px; margin-right: 10px;">
                      <el-option label="滿金額可用" value="min_spend" />
                      <el-option label="無門檻" value="none" />
                    </el-select>
                    <el-input-number 
                      v-model="editCouponForm.threshold" 
                      :min="0" 
                      :controls="false"
                      style="flex: 1;"
                      placeholder="請輸入門檻金額"
                      :disabled="editCouponForm.thresholdType === 'none'"
                    />
                  </div>
                </el-form-item>
              </el-col>

              <el-col :span="12">
                <el-form-item label="有效期限" required>
                  <el-date-picker
                    v-model="editCouponForm.dates"
                    type="daterange"
                    range-separator="至"
                    start-placeholder="開始日期"
                    end-placeholder="結束日期"
                    value-format="YYYY-MM-DD"
                    :default-time="defaultTime"
                    style="width: 100%"
                    unlink-panels
                  />
                </el-form-item>
              </el-col>

              <el-col :span="12">
                <el-form-item label="發行張數" required>
                  <el-input-number 
                    v-model="editCouponForm.count" 
                    :min="1" 
                    style="width: 100%" 
                    :controls="false"
                    placeholder="請輸入數量"
                  />
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-form-item label="狀態" required>
                  <el-radio-group v-model="editCouponForm.status">
                    <el-radio :label="1" border>
                      <div class="radio-content">
                        <div class="status-dot active"></div>可使用
                      </div>
                    </el-radio>
                    <el-radio :label="0" border>
                      <div class="radio-content">
                        <div class="status-dot inactive"></div>草稿
                      </div>
                    </el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-col>

            </el-row>
          </div>

          <div class="footer-actions">
            <el-button @click="goBack" size="large" class="cancel-btn">取消</el-button>
            <el-button 
              type="primary" 
              color="#003060" 
              @click="submitForm" 
              size="large" 
              :loading="submitLoading"
            >
              儲存修改
            </el-button>
          </div>

        </el-form>
      </div>
    </div>
  </ListLayout>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router' // 引入 useRoute 取得參數
import { ArrowLeft } from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'

const router = useRouter()
const route = useRoute()
const submitLoading = ref(false)
const pageLoading = ref(false)

// 取得網址上的 ID (例如 /admin/coupon-edit/5 => id 為 5)
const couponId = route.params.id;

const defaultTime = [
  new Date(2000, 1, 1, 0, 0, 0),
  new Date(2000, 2, 1, 23, 59, 59),
]

const editCouponForm = reactive({
  name: '',
  code: '', 
  discountType: 'amount', 
  discount: undefined,    
  thresholdType: 'min_spend',
  threshold: undefined,   
  dates: [],              
  count: undefined,       
  status: 1,              
})

const goBack = () => {
  router.push('/admin/coupon-management') 
}

// ★★★ 1. 取得詳細資料並回填表單 ★★★
const getCouponDetail = async () => {
  if (!couponId) return;
  pageLoading.value = true;
  
  const apiBase = import.meta.env.VITE_API_BASE;
  const API_URL = `${apiBase}/getCoupons.php?id=${couponId}`; 

  try {
    const response = await fetch(API_URL);
    const data = await response.json();

    // 如果後端回傳的是物件，進行資料回填
    if (data) {
        // 基本資料
        editCouponForm.name = data.name;
        editCouponForm.code = data.coupons_ID; // 顯示 ID
        editCouponForm.count = Number(data.count);
        editCouponForm.status = Number(data.status);
        
        // 日期回填
        if (data.startdate && data.enddate) {
            // 直接塞字串
            editCouponForm.dates = [data.startdate, data.enddate];
        } else {
            editCouponForm.dates = [];
        }


        // ★ 關鍵：判斷折扣類型 (反推邏輯)
        const d = Number(data.discount);
        if (d === 0) {
            editCouponForm.discountType = 'shipping';
            editCouponForm.discount = 0;
        } else if (d > 0 && d < 100) {
            editCouponForm.discountType = 'percent';
            editCouponForm.discount = d;
        } else {
            editCouponForm.discountType = 'amount';
            editCouponForm.discount = d;
        }

        // ★ 關鍵：判斷門檻類型
        const t = Number(data.threshold);
        if (t === 0) {
            editCouponForm.thresholdType = 'none';
            editCouponForm.threshold = 0;
        } else {
            editCouponForm.thresholdType = 'min_spend';
            editCouponForm.threshold = t;
        }
    }
  } catch (error) {
    console.error(error);
    ElMessage.error('無法讀取折價券資料');
  } finally {
    pageLoading.value = false;
  }
}

// ★★★ 2. 送出修改 ★★★
const submitForm = async () => {
  submitLoading.value = true;
  
  const isDiscountValid = editCouponForm.discountType === 'shipping' || (editCouponForm.discount !== undefined && editCouponForm.discount !== null && editCouponForm.discount > 0);

  if(!editCouponForm.name || !isDiscountValid || !editCouponForm.dates || editCouponForm.dates.length < 2) {
      ElMessage.warning('請填寫必填欄位 (名稱、折扣、有效期限)');
      submitLoading.value = false;
      return;
  }

  const apiBase = import.meta.env.VITE_API_BASE;
  const API_URL = `${apiBase}/editCoupon.php`;

  const fd = new FormData();
  
  // ★ 重要：帶入 ID 讓後端知道要更新哪一筆
  fd.append('id', couponId);

  fd.append('name', editCouponForm.name); 
  fd.append('discount', editCouponForm.discount);
  
  if (editCouponForm.thresholdType === 'none') {
    fd.append('threshold', 0);
  } else {
    fd.append('threshold', editCouponForm.threshold || 0);
  }

  fd.append('startdate', editCouponForm.dates[0]);
  fd.append('enddate', editCouponForm.dates[1]);
  fd.append('count', editCouponForm.count || 0);
  fd.append('status', editCouponForm.status);
  
  // 記得要傳 type (如果有加欄位的話)
  fd.append('type', editCouponForm.discountType);

  try {
    const response = await fetch(API_URL, {
      method: 'POST', // 或 PUT，看你後端怎麼寫
      body: fd        
    });

    const data = await response.json();

    if (data.success) { 
      ElMessage.success('修改成功！');
      router.push('/admin/coupon-management');
    } else {
      ElMessage.error('修改失敗：' + (data.message || '未知錯誤'));
    }

  } catch (error) {
    console.error('Network error:', error);
    ElMessage.error('系統發生錯誤，無法連線到伺服器');
  } finally {
    submitLoading.value = false;
  }
}

const getPlaceholder = computed(() => {
  if (editCouponForm.discountType === 'percent') return '請輸入 % 數 (如 10)';
  if (editCouponForm.discountType === 'amount') return '請輸入金額 (如 100)';
  return '免運優惠';
})

onMounted(() => {
    getCouponDetail();
});
</script>

<style scoped>
/* 樣式與 CouponAdd 相同 */
.scroll-container {
  height: calc(100vh - 250px); 
  overflow-y: auto;
  padding: 20px 20px 20px 0;
}

.form-container {
  max-width: 900px; 
  margin: 0 auto;
}

.content-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.05);
  margin-bottom: 24px;
}

.input-group {
  display: flex;
  width: 100%;
}

.radio-content {
  display: flex;
  align-items: center;
  gap: 8px;
}
.status-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
}
.status-dot.active { background-color: #67C23A; }
.status-dot.inactive { background-color: #909399; }

.bg-gray :deep(.el-input__wrapper) {
  background-color: #f5f7fa !important;
  box-shadow: 0 0 0 1px #e4e7ed inset !important;
}

.footer-actions {
  display: flex;
  justify-content: center;
  gap: 16px;
  margin-top: 40px;
  padding-top: 20px;
  border-top: 1px solid #eee;
}

.back-btn {
  border-color: #F0F7FF;
  background-color: #F0F7FF;
  font-weight: normal;
  color: black;
  width: 140px;
  
    &:hover {
    border-color: #409eff;
    background-color: #F0F7FF;
    color: #409eff;
  }
}

.cancel-btn {
  width: 120px;
}

:deep(.el-form-item__label) {
  font-weight: 500;
  color: #606266;
}
</style>
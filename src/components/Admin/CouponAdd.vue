<template>
  <ListLayout>
    
    <template #title>
      <h6>新增折價券</h6>
    </template>

    <template #controls>
      <el-button @click="goBack" class="back-btn" round>
        <el-icon><ArrowLeft /></el-icon>返回列表
      </el-button>
    </template>

    <div class="scroll-container">
      <div class="form-container">
        <el-form 
          :model="addCouponForm" 
          ref="formRef"
          label-position="top"
          class="custom-form"
        >
          
          <div class="content-card">       
            <el-row :gutter="24">
              <el-col :span="12">
                <el-form-item label="Coupon Name">
                  <el-input v-model="addCouponForm.name" placeholder="Enter coupon name" />
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-form-item label="折價券編號">
                  <el-input v-model="addCouponForm.code" disabled placeholder="系統自動產生" class="bg-gray"/>
                </el-form-item>
              </el-col>

              <el-col :span="12">
                <el-form-item label="折扣" required>
                    <el-input-number 
                      v-model="addCouponForm.discount" 
                      :min="0" 
                      :controls="false"
                      style="width: 100%;"
                      placeholder="請輸入折扣金額"
                    />
                  <!-- <div class="input-group">
                    <el-select v-model="addCouponForm.discountType" style="width: 140px; margin-right: 10px;">
                      <el-option label="定額折抵" value="amount" />
                      <el-option label="百分比折扣" value="percent" />
                      <el-option label="免運" value="shipping" />
                    </el-select>

                    <el-input-number 
                      v-model="addCouponForm.discount" 
                      :min="0" 
                      :controls="false"
                      style="flex: 1;"
                      :disabled="addCouponForm.discountType === 'shipping'"
                      :placeholder="getPlaceholder"
                    />
                  </div> -->
                </el-form-item>
              </el-col>

              <el-col :span="12">
                <el-form-item label="使用門檻" required>
                  <div class="input-group">
                    <el-select v-model="addCouponForm.thresholdType" style="width: 140px; margin-right: 10px;">
                      <el-option label="滿金額可用" value="min_spend" />
                      <el-option label="無門檻" value="none" />
                    </el-select>
                    <el-input-number 
                      v-model="addCouponForm.threshold" 
                      :min="0" 
                      :controls="false"
                      style="flex: 1;"
                      placeholder="請輸入門檻金額"
                      :disabled="addCouponForm.thresholdType === 'none'"
                    />
                  </div>
                </el-form-item>
              </el-col>

              <el-col :span="12">
                <el-form-item label="有效期限" required>
                  <el-date-picker
                    v-model="addCouponForm.dates"
                    type="daterange"
                    range-separator="至"
                    start-placeholder="開始日期"
                    end-placeholder="結束日期"
                    value-format="YYYY-MM-DD HH:mm:ss"
                    :default-time="defaultTime"
                    style="width: 100%"
                    unlink-panels
                  />
                </el-form-item>
              </el-col>

              <el-col :span="12">
                <el-form-item label="發行張數" required>
                  <el-input-number 
                    v-model="addCouponForm.count" 
                    :min="1" 
                    style="width: 100%" 
                    :controls="false"
                    placeholder="請輸入數量"
                  />
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-form-item label="初始狀態" required>
                  <el-radio-group v-model="addCouponForm.status">
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
              :loading="loading"
            >
              新增折價券
            </el-button>
          </div>

        </el-form>
      </div>
    </div>
  </ListLayout>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { ArrowLeft } from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'

const router = useRouter()
const loading = ref(false)

// 設定日期選擇器的預設時間 (起日 00:00:00, 迄日 23:59:59)
const defaultTime = [
  new Date(2000, 1, 1, 0, 0, 0),
  new Date(2000, 2, 1, 23, 59, 59),
]

// 表單資料模型
const addCouponForm = reactive({
  name: '',
  code: '', // 預設空，後端自動生成或顯示用
  discountType: 'amount', // UI 控制用 (後端可能沒此欄位，需自行轉邏輯)
  discount: undefined,    // 對應 DB: discount
  thresholdType: 'min_spend', // UI 控制用
  threshold: undefined,   // 對應 DB: threshold
  dates: [],              // 暫存日期陣列 [start, end]
  count: undefined,       // 對應 DB: count
  status: 1,              // 對應 DB: status (預設 1)
})

const goBack = () => {
  router.push('/admin/coupon-management') 
}

const submitForm = async () => {
  loading.value = true;
  
  // 邏輯：如果是「免運」類型，或者「折扣金額」有值(包含大於0)，就算通過
  const isDiscountValid = addCouponForm.discountType === 'shipping' || (addCouponForm.discount !== undefined && addCouponForm.discount !== null && addCouponForm.discount > 0);

  // 驗證檢查
  if(!addCouponForm.name || !isDiscountValid || !addCouponForm.dates || addCouponForm.dates.length < 2) {
      ElMessage.warning('請填寫必填欄位 (名稱、折扣、有效期限)');
      loading.value = false;
      return;
  }

  const apiBase = import.meta.env.VITE_API_BASE;
  const API_URL = `${apiBase}/addCoupon.php`;

  const fd = new FormData();
  
  fd.append('name', addCouponForm.name); 

  // 2. 處理折扣: 直接傳數值
  fd.append('discount', addCouponForm.discount);
  
  // 3. 處理門檻: 若選無門檻則傳 0
  if (addCouponForm.thresholdType === 'none') {
    fd.append('threshold', 0);
  } else {
    fd.append('threshold', addCouponForm.threshold || 0);
  }

  // 4. 處理日期: 拆解 dates 陣列
  fd.append('startdate', addCouponForm.dates[0]);
  fd.append('enddate', addCouponForm.dates[1]);

  // 5. 其他欄位
  fd.append('count', addCouponForm.count || 0);
  fd.append('status', addCouponForm.status);
  
  // 如果有 requirement 欄位需求，可在此補上
  // fd.append('requirement', 'none'); 

  try {
    const response = await fetch(API_URL, {
      method: 'POST',
      body: fd        
    });

    const data = await response.json();

    if (data.success) { // 假設後端回傳格式 { success: true }
      ElMessage.success('折價券新增成功！');
      router.push('/admin/coupon-management');
    } else {
      ElMessage.error('新增失敗：' + (data.message || '未知錯誤'));
    }

  } catch (error) {
    console.error('Network error:', error);
    ElMessage.error('系統發生錯誤，無法連線到伺服器');
  } finally {
    loading.value = false;
  }
}

const getPlaceholder = computed(() => {
  if (addCouponForm.discountType === 'percent') return '請輸入 % 數 (如 10)';
  if (addCouponForm.discountType === 'amount') return '請輸入金額 (如 100)';
  return '免運優惠';
})
</script>

<style scoped>
.scroll-container {
  height: calc(100vh - 250px); /* 調整高度以適應視窗 */
  overflow-y: auto;
  padding: 20px 20px 20px 0;
}

.form-container {
  max-width: 900px; /* 寬度稍微縮小讓畫面集中 */
  margin: 0 auto;
}

/* 卡片與內容樣式 */
.content-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.05);
  margin-bottom: 24px;
}

/* 輸入框組合樣式 (Select + Input) */
.input-group {
  display: flex;
  width: 100%;
}

/* 狀態 Radio 樣式 */
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

/* 背景灰 (Disabled Input) */
.bg-gray :deep(.el-input__wrapper) {
  background-color: #f5f7fa !important;
  box-shadow: 0 0 0 1px #e4e7ed inset !important;
}

/* 底部按鈕區 */
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

/* 覆蓋 Element Plus 預設樣式微調 */
:deep(.el-form-item__label) {
  font-weight: 500;
  color: #606266;
}
</style>
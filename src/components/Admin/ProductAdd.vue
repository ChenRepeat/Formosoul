<template>
  <ListLayout>
    
    <template #title>
      <h6>新增商品</h6>
    </template>

    <template #controls>
      <el-button @click="goBack" class="back-btn" round>
        <el-icon><ArrowLeft /></el-icon>返回列表
      </el-button>
    </template>

    <div class="scroll-container">
      <div class="product-add-container">
        <el-form 
          :model="addProductForm" 
          ref="formRef"
          label-position="top"
          class="product-form"
        >
          
          <div class="content-card">
            <h5 class="card-title">基本資訊</h5>
            <el-row :gutter="24">
              <el-col :span="12">
                <el-form-item label="商品名稱 (中文)" required>
                  <el-input v-model="addProductForm.nameZh" placeholder="請輸入中文名稱" />
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="Product Name (English)" required>
                  <el-input v-model="addProductForm.nameEn" placeholder="Enter product name" />
                </el-form-item>
              </el-col>

              <el-col :span="12">
                <el-form-item label="商品價格" required>
                  <el-input-number 
                    v-model="addProductForm.price" 
                    :min="0" 
                    style="width: 100%" 
                    :controls="false"
                    placeholder="請輸入金額"
                  />
                </el-form-item>
              </el-col>

              <el-col :span="12">
                <el-form-item label="庫存數量" required>
                  <el-input-number 
                    v-model="addProductForm.stock" 
                    :min="0" 
                    style="width: 100%" 
                    :controls="false"
                    placeholder="請輸入數量"
                  />
                </el-form-item>
              </el-col>

              <el-col :span="12">
                <el-form-item label="Product Type (English)" required>
                  <el-select v-model="addProductForm.typeEn" placeholder="Please select a type" style="width: 100%">
                    <el-option label="Folktoys" value="Folktoys" />
                    <el-option label="Personalized" value="Personalized" />
                    <el-option label="Accessories" value="Accessories" />
                    <el-option label="Voucher" value="Voucher" />
                  </el-select>
                </el-form-item>
              </el-col>

              <el-col :span="12">
                <el-form-item label="商品分類 (中文)" required>
                  <el-select v-model="addProductForm.typeZh" placeholder="請選擇分類" style="width: 100%" disabled>
                    <el-option label="童玩" value="童玩" />
                    <el-option label="客製化商品" value="客製化商品" />
                    <el-option label="飾品" value="飾品" />
                    <el-option label="體驗券" value="體驗券" />
                  </el-select>
                </el-form-item>
              </el-col>

              <el-col :span="12">
                <el-form-item label="上下架狀態" required>
                  <el-radio-group v-model="addProductForm.status">
                    <el-radio :label="1" border>
                      <div class="radio-content">
                        <div class="status-dot active"></div>上架
                      </div>
                    </el-radio>
                    <el-radio :label="0" border>
                      <div class="radio-content">
                        <div class="status-dot inactive"></div>下架
                      </div>
                    </el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-col>
              
              <el-col :span="12">
                  <div class="system-info-group">
                    <el-form-item label="商品編號">
                       <el-input 
                         placeholder="由系統生成" 
                         disabled 
                         class="bg-gray"
                       />
                    </el-form-item>
                    <el-form-item label="建立日期">
                        <el-input v-model="displayDate" disabled placeholder="自動生成" class="bg-gray"/>
                    </el-form-item>
                  </div>
              </el-col>
            </el-row>
          </div>

          <div class="content-card">
            <h5 class="card-title">圖片</h5>
            <el-row :gutter="40">
              <el-col :span="10">
                <div class="upload-block">
                  <div class="field-header">
                    <span class="label">商品主圖 (封面)</span>
                    <span class="hint">建議 1200x1200px, JPG/PNG</span>
                  </div>
                  <el-upload
                    class="main-uploader"
                    drag
                    action="#"
                    :auto-upload="false"
                    :limit="1"
                    :show-file-list="true"
                    v-model:file-list="mainImage"
                    list-type="picture"
                  >
                    <div class="upload-content">
                      <el-icon class="el-icon--upload"><upload-filled /></el-icon>
                      <div class="el-upload__text">
                        拖曳圖片至此或 <em>點擊上傳</em>
                      </div>
                    </div>
                  </el-upload>
                </div>
              </el-col>

              <el-col :span="14">
                <div class="upload-block">
                  <div class="field-header">
                    <span class="label">商品更多視角 (最多 4 張)</span>
                    <span class="hint">建議 800x800px, 可拖曳排序</span>
                  </div>
                  <el-upload
                    v-model:file-list="subImages"
                    action="#"
                    list-type="picture-card"
                    :auto-upload="false"
                    :limit="4"
                    multiple
                    class="sub-uploader"
                  >
                    <el-icon><Plus /></el-icon>
                  </el-upload>
                </div>
              </el-col>
            </el-row>
          </div>

          <div class="content-card">
            <h5 class="card-title">介紹</h5>
            
            <div class="desc-group">
              <el-row :gutter="24">
                <el-col :span="12">
                  <el-form-item label="商品描述">
                    <el-input v-model="addProductForm.descriptionZh" type="textarea" :rows="4" placeholder="請輸入商品詳細介紹..." />
                  </el-form-item>
                </el-col>
                <el-col :span="12">
                  <el-form-item label="English Description">
                    <el-input v-model="addProductForm.descriptionEn" type="textarea" :rows="4" placeholder="Enter product description..." />
                  </el-form-item>
                </el-col>
              </el-row>
            </div>

            <el-divider border-style="dashed" />

            <div class="desc-group">
              <el-row :gutter="24">
                <el-col :span="12">
                  <el-form-item label="商品故事及延伸文化">
                    <el-input v-model="addProductForm.storyZh" type="textarea" :rows="4" placeholder="請輸入設計理念或背景故事..." />
                  </el-form-item>
                </el-col>
                <el-col :span="12">
                  <el-form-item label="English Story">
                    <el-input v-model="addProductForm.storyEn" type="textarea" :rows="4" placeholder="Enter design story..." />
                  </el-form-item>
                </el-col>
              </el-row>
            </div>

            <el-divider border-style="dashed" />

            <div class="desc-group">
              <el-row :gutter="24">
                <el-col :span="12">
                  <el-form-item label="商品如何玩或使用">
                    <el-input v-model="addProductForm.useZh" type="textarea" :rows="3" placeholder="請輸入使用或遊玩方式..." />
                  </el-form-item>
                </el-col>
                <el-col :span="12">
                  <el-form-item label="English Instructions">
                    <el-input v-model="addProductForm.useEn" type="textarea" :rows="3" placeholder="Enter usage instructions..." />
                  </el-form-item>
                </el-col>
              </el-row>
            </div>
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
              儲存新增
            </el-button>
          </div>

        </el-form>
      </div>
    </div>
  </ListLayout>
</template>

<script setup>
import { reactive, ref, watch, computed } from 'vue'
import { useRouter } from 'vue-router'
import { Plus, UploadFilled, ArrowLeft } from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'

const router = useRouter()
const loading = ref(false)

// ★ 修改點 2: 顯示當前日期 (僅供顯示用)
const displayDate = computed(() => {
    const now = new Date();
    return now.toISOString().split('T')[0]; // 格式: YYYY-MM-DD
});

// 表單資料模型
const addProductForm = reactive({
  nameZh: '',
  nameEn: '',
  // id: '', // ★ 移除 id 欄位
  typeZh: '',
  typeEn:'',
  price: undefined,
  stock: undefined,
  // createDate: '', // ★ 移除 createDate，後端 SQL 會自己 NOW()
  status: 0, 
  descriptionZh: '',
  descriptionEn: '',
  storyZh: '',
  storyEn: '',
  useZh: '',
  useEn: '',
})

// 定義分類對應表 (Key: 英文, Value: 中文)
const typeMapping = {
  'Folktoys': '童玩',
  'Personalized': '客製化商品',
  'Accessories': '飾品',
  'Voucher': '體驗券'
}

// 監聽英文分類變動，自動填入中文
watch(() => addProductForm.typeEn, (newValue) => {
  if (newValue && typeMapping[newValue]) {
    addProductForm.typeZh = typeMapping[newValue]
  }
})

// 圖片檔案列表
const mainImage = ref([])
const subImages = ref([])

const goBack = () => {
  router.push('/admin/product-management') 
}

const submitForm = async () => {
  loading.value = true;
  
  // ★ 修改點 3: 驗證邏輯移除 id
  if(!addProductForm.nameZh || !addProductForm.price || !addProductForm.typeEn) {
      ElMessage.warning('請填寫必填欄位 (名稱、分類、價格)');
      loading.value = false;
      return;
  }

  const apiBase = import.meta.env.VITE_API_BASE;
  const API_URL = `${apiBase}/addProduct.php`;

  const fd = new FormData();

  for (const [key, value] of Object.entries(addProductForm)) {
    fd.append(key, value === undefined || value === null ? '' : value);
  }

  if (mainImage.value.length > 0) {
    fd.append('mainImage', mainImage.value[0].raw);
  }

  subImages.value.forEach((file) => {
    fd.append('subImages[]', file.raw);
  });

  try {
    const response = await fetch(API_URL, {
      method: 'POST',
      body: fd        
    });

    const data = await response.json();

    if (data.success) {
      // ★ 修改點 4: 成功訊息顯示後端回傳的 ID
      ElMessage.success(`商品新增成功！編號：${data.id}`);
      router.push('/admin/product-management');
    } else {
      ElMessage.error('新增失敗：' + data.message);
    }

  } catch (error) {
    console.error('Network error:', error);
    ElMessage.error('系統發生錯誤，無法連線到伺服器');
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
/* 樣式保持原樣，只有 .bg-gray 有用到 */
.scroll-container {
  height: calc(100vh - 250px);
  overflow-y: auto;
  padding: 20px 20px 20px 0;
}

.product-add-container {
  max-width: 1000px;
  margin: 0 auto;
}

/* 卡片與內容樣式 */
.content-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.05);
  margin-bottom: 24px;
}

.card-title {
  margin: 0 0 20px 0;
  font-size: 16px;
  color: #333;
  border-left: 4px solid #003060;
  padding-left: 10px;
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

/* 系統資訊群組 (ID, Date) */
.system-info-group {
    display: flex;
    gap: 20px;
}
.system-info-group .el-form-item {
    flex: 1;
}

/* 背景灰 (Disabled Input) */
.bg-gray :deep(.el-input__wrapper) {
  background-color: #f5f7fa !important;
  box-shadow: 0 0 0 1px #e4e7ed inset !important;
}

/* 圖片上傳區優化 */
.upload-block {
    display: flex;
    flex-direction: column;
}
.field-header {
    margin-bottom: 12px;
    display: flex;
    flex-direction: column;
}
.field-header .label {
    color: #444;
    margin-bottom: 4px;
    font-weight: 500;
}
.field-header .hint {
    color: #999;
    font-size: 13px;
}

.main-uploader :deep(.el-upload-dragger) {
    height: 220px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #f8f9fa;
    border: 2px dashed #dcdfe6;
    transition: all 0.3s;
}
.main-uploader :deep(.el-upload-dragger:hover) {
    border-color: #003060;
    background-color: #f0f7ff;
}
.el-icon--upload {
    color: #003060;
    margin-bottom: 10px;
}

/* 副圖 Grid */
.sub-uploader :deep(.el-upload-list--picture-card .el-upload-list__item),
.sub-uploader :deep(.el-upload--picture-card) {
    width: 100px;
    height: 100px;
    margin: 0 10px 10px 0;
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
</style>
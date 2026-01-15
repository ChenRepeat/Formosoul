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
                    ref="mainUploaderRef"
                    class="main-uploader"
                    drag
                    action="#"
                    :auto-upload="false"
                    :limit="1"
                    :show-file-list="false" 
                    :on-exceed="handleMainExceed"
                    :on-change="handleMainFileChange"
                    accept="image/*"
                  >
                    <div v-if="mainPreview" class="preview-container">
                      <img :src="mainPreview" class="preview-img" />
                      <div class="overlay">
                        <span>更換圖片</span>
                      </div>
                    </div>

                    <div v-else class="upload-placeholder">
                      <el-icon class="upload-icon"><Plus /></el-icon>
                      <div class="upload-text">拖曳圖片至此或 點擊上傳</div>
                    </div>
                  </el-upload>
                </div>
              </el-col>

              <el-col :span="14">
                <div class="upload-block">
                  <div class="field-header">
                    <span class="label">商品更多視角 (最多 4 張)</span>
                    <span class="hint">建議 800x800px (拖曳可直接取代舊圖)</span>
                  </div>
                  <el-upload
                    ref="subUploaderRef"
                    v-model:file-list="subImages"
                    action="#"
                    list-type="picture-card"
                    :auto-upload="false"
                    :limit="4"
                    multiple
                    drag
                    class="sub-uploader"
                    :on-exceed="handleSubExceed"
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
import { Plus, ArrowLeft } from '@element-plus/icons-vue'
import { ElMessage, genFileId } from 'element-plus'

const router = useRouter()
const loading = ref(false)
const mainUploaderRef = ref(null)
const subUploaderRef = ref(null) // ★ 新增副圖 ref

// 主圖相關變數
const mainPreview = ref(null)
const mainFile = ref(null)

const displayDate = computed(() => {
    const now = new Date();
    return now.toISOString().split('T')[0]; 
});

const addProductForm = reactive({
  nameZh: '',
  nameEn: '',
  typeZh: '',
  typeEn:'',
  price: undefined,
  stock: undefined,
  status: 0, 
  descriptionZh: '',
  descriptionEn: '',
  storyZh: '',
  storyEn: '',
  useZh: '',
  useEn: '',
})

const typeMapping = {
  'Folktoys': '童玩',
  'Personalized': '客製化商品',
  'Accessories': '飾品',
  'Voucher': '體驗券'
}

watch(() => addProductForm.typeEn, (newValue) => {
  if (newValue && typeMapping[newValue]) {
    addProductForm.typeZh = typeMapping[newValue]
  }
})

// 副圖列表
const subImages = ref([])

// ★ 主圖超出限制處理 (覆蓋舊圖)
const handleMainExceed = (files) => {
  mainUploaderRef.value.clearFiles()
  const file = files[0]
  file.uid = genFileId()
  mainUploaderRef.value.handleStart(file)
}

// ★ 副圖超出限制處理 (覆蓋所有舊圖)
const handleSubExceed = (files) => {
  // 當數量超過 limit 或直接拖曳多張時，清空舊的，換上新的
  subUploaderRef.value.clearFiles()
  
  // 取得新拖曳進來的檔案 (最多取前4張)
  const newFiles = Array.from(files).slice(0, 4)
  
  newFiles.forEach((file) => {
    file.uid = genFileId()
    subUploaderRef.value.handleStart(file)
  })
}

// 主圖檔案變更處理
const handleMainFileChange = (uploadFile) => {
  const file = uploadFile.raw
  if (!file) return

  if (!file.type.startsWith('image/')) {
    ElMessage.warning('請上傳圖片格式')
    mainUploaderRef.value.clearFiles()
    return
  }
  if (file.size > 2 * 1024 * 1024) {
    ElMessage.warning('圖片大小請勿超過 2MB')
    mainUploaderRef.value.clearFiles()
    return
  }

  mainFile.value = file
  mainPreview.value = URL.createObjectURL(file)
}

const goBack = () => {
  router.push('/admin/product-management') 
}

const submitForm = async () => {
  loading.value = true;
  
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

  if (mainFile.value) {
    fd.append('mainImage', mainFile.value);
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
/* 佈局容器 */
.scroll-container {
  height: calc(100vh - 250px);
  overflow-y: auto;
  padding: 20px 20px 20px 0;
}

.product-add-container {
  max-width: 1000px;
  margin: 0 auto;
}

/* 卡片與標題 */
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
}

/* Form Styles */
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

.system-info-group {
    display: flex;
    gap: 20px;
}
.system-info-group .el-form-item {
    flex: 1;
}

.bg-gray :deep(.el-input__wrapper) {
  background-color: #f5f7fa !important;
  box-shadow: 0 0 0 1px #e4e7ed inset !important;
}

/* === 圖片上傳區樣式優化 === */
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

/* --- 主圖上傳器 (大方框) --- */
.main-uploader {
    width: 100%;
    display: block;
}
.main-uploader :deep(.el-upload) {
    width: 100%;
    display: block;
}
.main-uploader :deep(.el-upload-dragger) {
    width: 100%;
    height: 350px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #fafafa;
    border: 1px dashed #dcdfe6;
    border-radius: 6px;
    transition: all 0.3s;
    padding: 0;
}
.main-uploader :deep(.el-upload-dragger:hover) {
    border-color: #409eff;
    background-color: #f0f7ff;
}

.upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #909399;
    height: 100%;
    width: 100%;
}
.upload-icon {
    font-size: 48px;
    color: #dcdfe6;
    margin-bottom: 10px;
}
.upload-text {
    font-size: 14px;
}

.preview-container {
    width: 100%;
    height: 100%;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}
.preview-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}
.overlay {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex; justify-content: center; align-items: center;
    opacity: 0; transition: opacity 0.3s;
    color: white; font-size: 16px;
    z-index: 10;
}
.main-uploader:hover .overlay { opacity: 1; }


/* --- 副圖上傳器 (Grid) --- */
.sub-uploader :deep(.el-upload-list--picture-card .el-upload-list__item),
.sub-uploader :deep(.el-upload--picture-card) {
    width: 100px;
    height: 100px;
    margin: 0 10px 10px 0;
}
.sub-uploader :deep(.el-upload-dragger) {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    border: none; 
    background: transparent;
}

/* ★ 關鍵樣式：強制顯示 picture-card 的上傳框 (即使滿了也不隱藏) */
/* 這樣使用者才能繼續拖曳新圖片進去觸發 handleSubExceed */
.sub-uploader :deep(.el-upload--picture-card) {
    display: inline-flex !important;
}

/* 底部按鈕 */
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
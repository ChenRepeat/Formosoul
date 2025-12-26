<template>
  <div class="product-add-container">
    
    <div class="page-header">
      <div class="header-content">
        <h6 class="title">商品新增</h6>
        <p class="sub-title">建立新的商品資料，儲存後預設為下架狀態。</p>
      </div>
      <el-button @click="goBack" class="add-btn" round>返回列表</el-button>
    </div>

    <el-form 
      :model="addProductForm" 
      label-position="top"
      class="product-form"
    >
      
      <div class="content-card">
        
        <el-row :gutter="24">
          <el-col :span="12">
            <el-form-item label="商品名稱 (中文)" required>
              <el-input v-model="addProductForm.nameZh" placeholder="請輸入中文名稱" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Product Name (English)">
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
                placeholder="0"
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
                placeholder="0"
              />
            </el-form-item>
          </el-col>

          <el-col :span="12">
            <el-form-item label="商品分類" required>
              <el-select v-model="addProductForm.type" placeholder="請選擇分類" style="width: 100%">
                <el-option label="Folktoys" value="Folktoys" />
                <el-option label="Personalized" value="Personalized" />
                <el-option label="Accessories" value="Accessories" />
                <el-option label="Voucher" value="Voucher" />
              </el-select>
            </el-form-item>
          </el-col>

          <el-col :span="12">
            <el-form-item label="初始上下架狀態">
              <el-radio-group v-model="addProductForm.status">
                <el-radio-button :label="1">立即上架</el-radio-button>
                <el-radio-button :label="0">暫時下架</el-radio-button>
              </el-radio-group>
            </el-form-item>
          </el-col>
          
          <el-col :span="12">
             <div class="system-info-group">
                <el-form-item label="系統編號">
                   <el-input v-model="addProductForm.id" disabled placeholder="自動生成" />
                </el-form-item>
                <el-form-item label="建立日期">
                   <el-input v-model="addProductForm.createDate" disabled placeholder="自動生成" />
                </el-form-item>
             </div>
          </el-col>
        </el-row>
      </div>

      <div class="content-card">
        
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
        <el-button @click="goBack" size="large">取消新增</el-button>
        <el-button type="primary" color="#003060" @click="submitForm" size="large" :loading="loading">
          新增商品
        </el-button>
      </div>

    </el-form>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { Plus, UploadFilled } from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'

const router = useRouter()
const loading = ref(false)

// 表單資料模型
const addProductForm = reactive({
  nameZh: '',
  nameEn: '',
  id: '', 
  type: '',
  price: undefined, // 改為 undefined 讓 input-number 顯示為空
  stock: undefined,
  createDate: '', 
  status: 0, // 預設下架 (對應 radio-button)
  descriptionZh: '',
  descriptionEn: '',
  storyZh: '',
  storyEn: '',
  useZh: '',
  useEn: ''
})

// 圖片檔案列表
const mainImage = ref([])
const subImages = ref([])

const goBack = () => {
  router.push('/admin/product-management') 
}

const submitForm = async () => {
  loading.value = true;
  
  // 簡單驗證
  if(!addProductForm.nameZh || !addProductForm.price) {
      ElMessage.warning('請填寫必填欄位 (名稱、價格)');
      loading.value = false;
      return;
  }

  const apiBase = import.meta.env.VITE_API_BASE;
  const API_URL = `${apiBase}/addProduct.php`;

  const fd = new FormData();

  for (const [key, value] of Object.entries(addProductForm)) {
    // 處理 undefined 或 null 轉空字串
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
      ElMessage.success('商品新增成功！');
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
.product-add-container {
  max-width: 1000px;
  margin: 0 auto 80px; /* 底部留白給按鈕 */
}

/* 標題區 */
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 24px;
}
.title {
  color: #1a1a1a;
  margin: 0 0 8px 0;
}
.sub-title {
  color: #666;
  margin: 0;
}

/* 通用卡片樣式 */
.content-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.05);
  padding: 30px;
  margin-bottom: 24px;
}


/* 系統資訊群組 (ID, Date) */
.system-info-group {
    display: flex;
    gap: 20px;
}
.system-info-group .el-form-item {
    flex: 1;
}

/* 表單優化 */
.product-form :deep(.el-form-item__label) {
  color: #444;
  padding-bottom: 6px;
}
.product-form :deep(.el-input__wrapper),
.product-form :deep(.el-textarea__inner) {
  box-shadow: 0 0 0 1px #dcdfe6 inset;
  padding: 8px 12px;
}
/* 唯讀欄位樣式 */
.product-form :deep(.el-input.is-disabled .el-input__wrapper) {
    background-color: #f5f7fa;
    box-shadow: 0 0 0 1px #e4e7ed inset;
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
}
.field-header .hint {
    color: #999;
}

.main-uploader :deep(.el-upload-dragger) {
    height: 220px; /* 降低高度 */
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

/* 底部按鈕 */
.footer-actions {
  display: flex;
  justify-content: center;
  gap: 16px;
  margin-top: 40px;
  padding-top: 20px;
  border-top: 1px solid #eee;
}

.add-btn{
  background-color: #F0F7FF;
  width: 140px;
}
</style>
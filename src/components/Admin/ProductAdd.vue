<template>
  <div class="product-add-container">
    <div class="header-section">
      <div class="header-left">
        <h6>商品新增</h6>
        <p class="sub-title">請輸入商品基本資料與圖片，儲存後將新增一筆商品，可再於商品列表中調整上下架狀態。</p>
      </div>
      <el-button round @click="goBack" class="back-btn">返回商品列表</el-button>
    </div>

    <el-form 
      :model="addProductForm" 
      label-width="120px" 
      label-position="left"
      class="product-form"
    >
      
      <el-row :gutter="20">
        <el-col :span="12">
          <el-form-item label="商品名稱" label-width="120px">
            <el-input v-model="addProductForm.nameZh" placeholder="請輸入中文名稱" />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Product Name" label-width="170px">
            <el-input v-model="addProductForm.nameEn" placeholder="Enter EN product name" />
          </el-form-item>
        </el-col>
      </el-row>

      <el-form-item label="商品編號">
        <el-input v-model="addProductForm.id" disabled class="read-only-input" placeholder="系統自動產生" style="width:333px;"/>
      </el-form-item>

      <el-form-item label="分類">
        <el-select v-model="addProductForm.type" placeholder="請選擇分類" style="width:333px;">
          <el-option label="Folktoys" value="Folktoys" />
          <el-option label="Personalized" value="Personalized" />
          <el-option label="Accessories" value="Accessories" />
          <el-option label="Voucher" value="Voucher" />
        </el-select>
      </el-form-item>

      <el-form-item label="價格">
        <el-input v-model="addProductForm.price" placeholder="0" style="width:333px;">
          <template #prefix>NT$</template>
        </el-input>
      </el-form-item>

      <el-form-item label="庫存">
        <el-input v-model="addProductForm.stock" placeholder="0" style="width:333px;"/>
      </el-form-item>

      <el-form-item label="建立日期">
        <el-input v-model="addProductForm.createDate" disabled class="read-only-input" placeholder="系統自動產生" style="width:333px;"/>
      </el-form-item>

      <el-form-item label="初始狀態">
        <el-radio-group v-model="addProductForm.status">
          <el-radio :label="1">上架</el-radio>
          <el-radio :label="0">下架</el-radio>
        </el-radio-group>
      </el-form-item>

      <div class="image-section">
        
        <div class="main-image-upload">
          <div class="field-label"><p>商品主圖</p></div>
          <el-upload
            class="upload-demo main-uploader"
            drag
            action="#"
            :auto-upload="false"
            :limit="1"
            v-model:file-list="mainImage"
          >
            <el-icon class="el-icon--upload"><plus /></el-icon>
            <div class="el-upload__text">
              點擊或拖曳圖片至此
              <div class="sub-text">
                <span>建議尺寸1200*1200 px，檔案大小 ≤ 1MB</span> 
                <br> 
                <span>格式：JPG / PNG</span>
              </div>
            </div>
          </el-upload>
        </div>

        <div class="sub-images-upload">
          <div class="field-label">
            <p>商品小圖（可拖曳排序,最多 4 張）</p>
            <p class="hint">建議尺寸800*800 px 最多4張 檔案大小 ≤ 500KB 格式：JPG / PNG</p>
          </div>
          <el-upload
            v-model:file-list="subImages"
            action="#"
            list-type="picture-card"
            drag
            :auto-upload="false"
            :limit="4"
          >
            <el-icon><Plus /></el-icon>
          </el-upload>
        </div>
      </div>

      <div class="description-section">
        <el-row :gutter="20" class="desc-row">
          <el-col :span="12">
            <div class="desc-label">商品描述（中文）</div>
            <el-input
              v-model="addProductForm.descriptionZh"
              type="textarea"
              :rows="5"
            />
          </el-col>
          <el-col :span="12">
            <div class="desc-label">Product Description（EN）</div>
            <el-input
              v-model="addProductForm.descriptionEn"
              type="textarea"
              :rows="5"
            />
          </el-col>
        </el-row>

        <el-row :gutter="20" class="desc-row">
          <el-col :span="12">
            <div class="desc-label">商品故事及延伸文化（中文）</div>
            <el-input
              v-model="addProductForm.storyZh"
              type="textarea"
              :rows="5"
            />
          </el-col>
          <el-col :span="12">
            <div class="desc-label">Product Story & Cultural Background (EN)</div>
            <el-input
              v-model="addProductForm.storyEn"
              type="textarea"
              :rows="5"
            />
          </el-col>
        </el-row>

        <el-row :gutter="20" class="desc-row">
          <el-col :span="12">
            <div class="desc-label">如何玩或使用（中文）</div>
            <el-input
              v-model="addProductForm.useZh"
              type="textarea"
              :rows="5"
            />
          </el-col>
          <el-col :span="12">
            <div class="desc-label">How to Play or Use (EN)</div>
            <el-input
              v-model="addProductForm.useEn"
              type="textarea"
              :rows="5"
            />
          </el-col>
        </el-row>
      </div>

      <div class="footer-actions">
        <el-button @click="goBack">取消</el-button>
        <el-button type="primary" color="#003060" @click="submitForm">儲存新增</el-button>
      </div>

    </el-form>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { Plus } from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'

const router = useRouter()

// 表單資料模型
const addProductForm = reactive({
  nameZh: '',
  nameEn: '',
  // id: '', // 系統自動產生
  type: '',
  price: '',
  stock: '',
  // createDate: '', // 系統自動產生
  status: 'off',
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
  const apiBase = import.meta.env.VITE_API_BASE;
  const API_URL = `${apiBase}/addProduct.php`;

  const fd = new FormData();

  for (const [key, value] of Object.entries(addProductForm)) {
    fd.append(key, value);
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
      console.error('Error details:', data);
    }

  } catch (error) {
    // 網路錯誤或 JSON 解析錯誤
    console.error('Network or Parsing error:', error);
    ElMessage.error('系統發生錯誤，無法連線到伺服器');
  }
}
</script>

<style scoped>
.product-add-container {
  max-width: 1200px;
  margin: 0 auto 10px;
  background-color: #fff;
}

/* 標題區 */
.header-section {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
}
.header-section h6 {
  margin-bottom: 8px;
}
.sub-title {
  color: #999;
}

/* 表單通用調整 */
.product-form :deep(.el-input__wrapper) {
  /* 讓輸入框稍微圓潤一點，符合設計圖 */
  border-radius: 4px; 
}
.read-only-input :deep(.el-input__inner) {
  /* 模擬設計圖中唯讀欄位的淡藍/灰色背景 */
  background-color: #F0F5FA;
  color: #999;
}
.read-only-input :deep(.el-input__wrapper) {
  background-color: #F0F5FA;
  box-shadow: none !important; /* 移除外框線 */
}

/* 圖片區塊 */
.image-section {
  margin-top: 40px;
  border-top: 1px solid #eee; /* 分隔線可選 */
  padding-top: 20px;
}

/* 主圖上傳 */
.main-image-upload {
  display: flex;
  align-items: flex-start;
  margin-bottom: 40px;
}
.field-label {
  width: 120px; 
  color: #606266;
  padding-right: 12px;
}
.main-uploader {
  flex: 1;
}
.main-uploader :deep(.el-upload-dragger) {
  width: 100%;
  height: 400px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  border: 2px dashed #dcdfe6;
  background-color: #fff;
}
.el-icon--upload {
  color: #999;
  margin-bottom: 10px;
}
.sub-text {
  color: #999;
  margin-top: 8px;
}
.sub-images-upload {
  margin-bottom: 40px;
}
.sub-images-upload .field-label {
  margin-bottom: 10px;
  width: 100%; 
}
.sub-images-upload .hint {
  color: #999;
}


.description-section {
  margin-top: 20px;
}
.desc-row {
  margin-bottom: 30px;
}
.desc-label {
  color: #333;
  margin-bottom: 10px;
}

.footer-actions {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 40px;
  padding-top: 20px;
  border-top: 1px solid #eee;
}

.back-btn{
  background-color: #F0F7FF;
  margin-top: 30px;
}

/* 隱藏預覽按鈕 */
.sub-images-upload :deep(.el-upload-list__item-preview) {
  display: none !important;
}

/* 移除垃圾桶自帶邊距 */
.sub-images-upload :deep(.el-upload-list__item-delete) {
  margin: 0 !important;
}

.product-form{
  font-size: 16px;
}
.product-form :deep(.el-form-item__label) {
  font-size: 16px;
}
/* 修改整張表單內所有輸入框的 placeholder */
.product-form :deep(.el-input__inner::placeholder),
.product-form :deep(.el-textarea__inner::placeholder) {
  font-size: 16px;
}

/* 修正：當 picture-card 搭配 drag 使用時，強制讓感應區填滿 */
.sub-images-upload :deep(.el-upload--picture-card .el-upload-dragger) {
  width: 100%;
  height: 100%;

  /* 內容物（+號）置中 */
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
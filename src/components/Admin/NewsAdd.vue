<template>
  <ListLayout>
    
    <template #title>
      <h6>新增消息</h6>
    </template>

    <template #controls>
      <el-button @click="goBack" class="back-btn" round>
        <el-icon><ArrowLeft /></el-icon>返回列表
      </el-button>
    </template>

    <div class="scroll-container">
      <div class="form-container">
        <el-form 
          :model="addNewsForm" 
          ref="formRef"
          label-position="top"
          class="custom-form"
        >
          
          <div class="content-card">       
            <el-row :gutter="40">
              <el-col :span="14">
              <el-form-item label="標題(中文)" required>
                <el-input v-model="addNewsForm.title_zh" placeholder="請輸入中文標題" />
              </el-form-item>
                
              <el-form-item label="Title(EN)" required>
                  <el-input v-model="addNewsForm.title_en" placeholder="Enter English title" />
                </el-form-item>

                <el-form-item label="上稿日期(可排程)" required>
                  <el-date-picker
                    v-model="addNewsForm.createdate"
                    type="date"
                    placeholder="選擇日期"
                    value-format="YYYY-MM-DD"
                    style="width: 100%"
                  />
                </el-form-item>

                <el-form-item label="狀態" required>
                  <el-radio-group v-model="addNewsForm.status">
                    <el-radio :label="1" border>
                      <div class="radio-content">
                        <div class="status-dot active"></div>顯示中
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

              <el-col :span="10">
                <el-form-item label="封面照">
                  <el-upload
                    ref="uploadRef"
                    class="news-uploader"
                    drag
                    action="#"
                    :auto-upload="false"
                    :show-file-list="false"
                    :limit="1"
                    :on-exceed="handleExceed"
                    :on-change="handleFileChange"
                    accept="image/*"
                  >
                    <div v-if="previewImage" class="preview-container">
                      <img :src="previewImage" class="preview-img" />
                      <div class="overlay">
                        <span>更換圖片</span>
                      </div>
                    </div>

                    <div v-else class="upload-placeholder">
                      <el-icon class="upload-icon"><Plus /></el-icon>
                      <div class="upload-text">點擊或拖曳圖片至此</div>
                      <div class="upload-hint">建議尺寸 1200*1200 px<br>檔案大小 ≤ 1MB</div>
                    </div>
                  </el-upload>
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-form-item label="整篇文章內容(中文)" required>
                  <el-input 
                    v-model="addNewsForm.content" 
                    type="textarea" 
                    :rows="6" 
                    placeholder="請輸入文章內容..."
                  />
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-form-item label="Article Content(EN)" required>
                  <el-input 
                    v-model="addNewsForm.content_en" 
                    type="textarea" 
                    :rows="6" 
                    placeholder="Enter article content..."
                  />
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
              新增消息
            </el-button>
          </div>

        </el-form>
      </div>
    </div>
  </ListLayout>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { ArrowLeft, Plus } from '@element-plus/icons-vue'
import { ElMessage, genFileId } from 'element-plus' // ★ 引入 genFileId
import ListLayout from './ListLayout.vue'

const router = useRouter()
const loading = ref(false)
const uploadRef = ref(null) // ★ 綁定 el-upload
const previewImage = ref(null) // 圖片預覽網址
const selectedFile = ref(null) // 實際要上傳的檔案物件

// 表單資料模型
const addNewsForm = reactive({
  title_zh: '',
  title_en: '',
  createdate: '',
  status: 1, 
  content: '',
  content_en: ''
})

// 返回列表
const goBack = () => {
  router.push({ name: 'NewsManagement' })
}

// ★ 新增：處理覆蓋圖片 (當拖曳第二張圖時，自動替換第一張)
const handleExceed = (files) => {
  uploadRef.value.clearFiles()
  const file = files[0]
  file.uid = genFileId()
  uploadRef.value.handleStart(file)
}

// ★ 修改：處理圖片選擇與預覽 (el-upload 的 on-change 事件)
const handleFileChange = (uploadFile) => {
  const file = uploadFile.raw
  if (!file) return

  // 驗證檔案大小 (例如限制 1MB)
  if (file.size > 1024 * 1024) {
    ElMessage.warning('圖片檔案大小不能超過 1MB')
    uploadRef.value.clearFiles() // 清除不合規的檔案
    return
  }

  // 驗證格式 (簡單驗證)
  if (!file.type.startsWith('image/')) {
    ElMessage.warning('請上傳圖片格式')
    uploadRef.value.clearFiles()
    return
  }

  selectedFile.value = file
  
  // 建立預覽網址
  previewImage.value = URL.createObjectURL(file)
}

// 送出表單
const submitForm = async () => {
  loading.value = true
  
  // 驗證必填欄位
  if(!addNewsForm.title_zh || !addNewsForm.createdate) {
      ElMessage.warning('請填寫標題與上稿日期')
      loading.value = false
      return
  }

  const apiBase = import.meta.env.VITE_API_BASE
  const API_URL = `${apiBase}/addNews.php` 

  // 使用 FormData 傳送 (包含文字與檔案)
  const fd = new FormData()
  
  fd.append('title_zh', addNewsForm.title_zh)
  fd.append('title_en', addNewsForm.title_en)
  fd.append('createdate', addNewsForm.createdate)
  fd.append('status', addNewsForm.status)
  fd.append('content_zh', addNewsForm.content)
  fd.append('content_en', addNewsForm.content_en)
  
  // 如果有選擇圖片，才加入圖片欄位
  if (selectedFile.value) {
    fd.append('pic', selectedFile.value) // 對應 PHP $_FILES['pic'] 或 'image'，看你後端寫什麼
  }

  try {
    const response = await fetch(API_URL, {
      method: 'POST',
      body: fd        
    })

    const data = await response.json()

    if (data.success) {
      ElMessage.success('消息新增成功！')
      router.push({ name: 'NewsManagement' })
    } else {
      ElMessage.error('新增失敗：' + (data.message || '未知錯誤'))
    }

  } catch (error) {
    console.error('Network error:', error)
    ElMessage.error('系統發生錯誤，無法連線到伺服器')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.scroll-container {
  height: calc(100vh - 250px);
  overflow-y: auto;
  padding: 20px 20px 20px 0;
}

.form-container {
  max-width: 1000px;
  margin: 0 auto;
}

.content-card {
  background: #fff;
  border-radius: 8px;
  padding: 10px;
  margin-bottom: 24px;
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

/* === ★ 修改：el-upload 樣式調整 === */
/* 強制設定 upload 拖曳區塊的高度與樣式，以符合原本設計 */
.news-uploader :deep(.el-upload),
.news-uploader :deep(.el-upload-dragger) {
  width: 100%;
  height: 350px; /* 固定高度 */
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #fafafa;
  border-radius: 6px;
  transition: border-color 0.3s;
}

/* 當有圖片時，去除內建 padding，讓圖片滿版 */
.news-uploader :deep(.el-upload-dragger) {
  padding: 0;
  border: 1px dashed #dcdfe6;
}

.news-uploader :deep(.el-upload-dragger:hover) {
  border-color: #409eff;
  background-color: #f0f7ff;
}

.upload-placeholder {
  text-align: center;
  color: #909399;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
}

.upload-icon {
  font-size: 48px;
  color: #dcdfe6;
  margin-bottom: 10px;
}

.upload-text {
  font-size: 14px;
  margin-bottom: 5px;
}

.upload-hint {
  font-size: 12px;
  color: #c0c4cc;
  line-height: 1.5;
}

.preview-container {
  width: 100%;
  height: 100%;
  position: relative;
  display: flex;       /* 確保圖片居中 */
  justify-content: center;
  align-items: center;
}

.preview-img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
  display: block;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  opacity: 0;
  transition: opacity 0.3s;
  color: white;
  font-size: 16px;
  z-index: 10;
}

/* 這裡要改用 .news-uploader:hover 來觸發 */
.news-uploader:hover .overlay {
  opacity: 1;
}

:deep(.el-form-item__label) {
  font-weight: 500;
  color: #606266;
}
</style>
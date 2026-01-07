<template>
  <ListLayout>
    
    <template #title>
      <h6>編輯消息</h6>
    </template>

    <template #controls>
      <el-button @click="goBack" class="back-btn" round>
        <el-icon><ArrowLeft /></el-icon>返回列表
      </el-button>
    </template>

    <div class="scroll-container" v-loading="pageLoading">
      <div class="form-container">
        <el-form 
          :model="editNewsForm" 
          ref="formRef"
          label-position="top"
          class="custom-form"
        >
          
          <div class="content-card">        
            <el-row :gutter="40">
              <el-col :span="14">
                <el-form-item label="標題(中文)" required>
                  <el-input v-model="editNewsForm.title_zh" placeholder="請輸入中文標題" />
                </el-form-item>
                
                <el-form-item label="Title(EN)" required>
                  <el-input v-model="editNewsForm.title_en" placeholder="Enter English title" />
                </el-form-item>

                <el-form-item label="上稿日期(可排程)" required>
                  <el-date-picker
                    v-model="editNewsForm.createdate"
                    type="date"
                    placeholder="選擇日期"
                    value-format="YYYY-MM-DD"
                    style="width: 100%"
                  />
                </el-form-item>

                <el-form-item label="狀態" required>
                  <el-radio-group v-model="editNewsForm.status">
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
                  <div class="upload-box" @click="triggerFileInput">
                    <input 
                      type="file" 
                      ref="fileInputRef" 
                      class="hidden-input" 
                      accept="image/*"
                      @change="handleImageChange"
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
                  </div>
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-form-item label="整篇文章內容(中文)" required>
                  <el-input 
                    v-model="editNewsForm.content_zh" 
                    type="textarea" 
                    :rows="6" 
                    placeholder="請輸入文章內容..."
                  />
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-form-item label="Article Content(EN)" required>
                  <el-input 
                    v-model="editNewsForm.content_en" 
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
              :loading="submitting"
            >
              更新消息
            </el-button>
          </div>

        </el-form>
      </div>
    </div>
  </ListLayout>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router' // 引入 useRoute
import { ArrowLeft, Plus } from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'
import ListLayout from './ListLayout.vue'

const router = useRouter()
const route = useRoute() // 取得當前路由資訊
const newsID = route.params.id // 從網址取得 ID

const pageLoading = ref(true) // 頁面載入中
const submitting = ref(false) // 送出按鈕 loading
const fileInputRef = ref(null)
const previewImage = ref(null) 
const selectedFile = ref(null) 

// 環境變數
const apiBase = import.meta.env.VITE_API_BASE
const imgBase = import.meta.env.VITE_IMG_BASE
const imgBaseUrl = imgBase

// 表單資料模型
const editNewsForm = reactive({
  title_zh: '',
  title_en: '',
  createdate: '',
  status: 1, 
  content_zh: '',
  content_en: ''
})

// 返回列表
const goBack = () => {
  router.push({ name: 'NewsManagement' })
}

// 觸發檔案選擇
const triggerFileInput = () => {
  fileInputRef.value.click()
}

// 處理圖片選擇
const handleImageChange = (event) => {
  const file = event.target.files[0]
  if (!file) return

  if (file.size > 1024 * 1024) {
    ElMessage.warning('圖片檔案大小不能超過 1MB')
    return
  }

  selectedFile.value = file
  // 建立本地預覽網址 (取代原本的後端圖片顯示)
  previewImage.value = URL.createObjectURL(file)
}

// ★ 取得單筆資料
const getNewsDetail = async () => {
  if (!newsID) {
    ElMessage.error('無效的消息 ID')
    goBack()
    return
  }

  try {
    // 這裡假設你有一個 getNewsDetail.php 或者用 getNews.php?id=xxx
    const API_URL = `${apiBase}/getNews.php?id=${newsID}`
    const response = await fetch(API_URL)
    const data = await response.json()

    // 填入表單
    editNewsForm.title_zh = data.title_zh
    editNewsForm.title_en = data.title_en
    editNewsForm.createdate = data.createdate
    editNewsForm.status = Number(data.status) // 確保是數字
    editNewsForm.content_zh = data.content_zh
    editNewsForm.content_en = data.content_en
    
    // 處理圖片預覽
    if (data.pic) {
      // 假設資料庫存的是 'News/abc.jpg'，前端要組合成完整網址
      // 請依實際檔案結構調整 imgBaseUrl
      previewImage.value = `${imgBaseUrl}/${data.pic}`
    }

  } catch (error) {
    console.error(error)
    ElMessage.error('無法讀取消息資料')
  } finally {
    pageLoading.value = false
  }
}

// ★ 送出更新
const submitForm = async () => {
  submitting.value = true
  
  if(!editNewsForm.title_zh || !editNewsForm.createdate) {
      ElMessage.warning('請填寫標題與上稿日期')
      submitting.value = false
      return
  }

  const API_URL = `${apiBase}/editNews.php` 

  const fd = new FormData()
  
  // 必填：ID
  fd.append('newsID', newsID)
  
  fd.append('title_zh', editNewsForm.title_zh)
  fd.append('title_en', editNewsForm.title_en)
  fd.append('createdate', editNewsForm.createdate)
  fd.append('status', editNewsForm.status)
  fd.append('content_zh', editNewsForm.content_zh)
  fd.append('content_en', editNewsForm.content_en)
  
  // 只有當使用者有選新圖片時，才傳送 pic
  if (selectedFile.value) {
    fd.append('pic', selectedFile.value)
  }

  try {
    const response = await fetch(API_URL, {
      method: 'POST', // 雖然是編輯，但因為有檔案上傳，通常還是用 POST
      body: fd        
    })

    const data = await response.json()

    if (data.success) {
      ElMessage.success('更新成功！')
      router.push({ name: 'NewsManagement' })
    } else {
      ElMessage.error('更新失敗：' + (data.message || '未知錯誤'))
    }

  } catch (error) {
    console.error('Network error:', error)
    ElMessage.error('系統發生錯誤')
  } finally {
    submitting.value = false
  }
}

// 進入頁面時載入資料
onMounted(() => {
  getNewsDetail()
})
</script>

<style scoped>
/* 樣式與 NewsAdd.vue 完全相同 */
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

.upload-box {
  width: 100%;
  height: 350px;
  border: 1px dashed #dcdfe6;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: border-color 0.3s;
  background-color: #fafafa;
  display: flex;
  justify-content: center;
  align-items: center;

  &:hover {
    border-color: #409eff;
  }
}

.hidden-input {
  display: none;
}

.upload-placeholder {
  text-align: center;
  color: #909399;
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
}

.preview-img {
  width: 100%;
  height: 100%;
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
}

.upload-box:hover .overlay {
  opacity: 1;
}

:deep(.el-form-item__label) {
  font-weight: 500;
  color: #606266;
}
</style>
<template>
  <ListLayout>
    
    <template #title>
      <h6>編輯年度盛事</h6>
    </template>

    <template #controls>
      <el-button @click="goBack" class="back-btn" round>
        <el-icon><ArrowLeft /></el-icon>返回列表
      </el-button>
    </template>

    <div class="scroll-container" v-loading="pageLoading">
      <div class="form-container">
        <el-form 
          :model="editEventForm" 
          ref="formRef"
          label-position="top"
          class="custom-form"
        >
          
          <div class="content-card">        
            <el-row :gutter="40">
              <el-col :span="14">
                <el-form-item label="活動標題(中文)" required>
                  <el-input v-model="editEventForm.title_zh" placeholder="請輸入中文標題" />
                </el-form-item>
                
                <el-form-item label="Event Title(EN)" required>
                  <el-input v-model="editEventForm.title_en" placeholder="Enter English title" />
                </el-form-item>

                <el-form-item label="活動日期" required>
                  <el-date-picker
                    v-model="editEventForm.event_date"
                    type="date"
                    placeholder="選擇活動日期"
                    value-format="YYYY-MM-DD"
                    style="width: 100%"
                  />
                </el-form-item>

                <el-form-item label="影片連結 (Video URL)">
                  <el-input 
                    v-model="editEventForm.video_url" 
                    placeholder="請輸入 YouTube 或 Vimeo 連結" 
                    clearable
                  >
                    <template #prefix>
                      <el-icon><VideoPlay /></el-icon>
                    </template>
                  </el-input>
                </el-form-item>

                <el-form-item label="狀態" required>
                  <el-radio-group v-model="editEventForm.status">
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
                <el-form-item label="活動封面照">
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
                      <div class="upload-hint">建議尺寸 1200*800 px<br>檔案大小 ≤ 1MB</div>
                    </div>
                  </div>
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-form-item label="活動大綱(中文)" required>
                  <el-input 
                    v-model="editEventForm.description_zh" 
                    type="textarea" 
                    :rows="6" 
                    placeholder="請輸入活動大綱..."
                  />
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-form-item label="Event Description(EN)" required>
                  <el-input 
                    v-model="editEventForm.description_en" 
                    type="textarea" 
                    :rows="6" 
                    placeholder="Enter event description..."
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
              更新活動
            </el-button>
          </div>

        </el-form>
      </div>
    </div>
  </ListLayout>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { ArrowLeft, Plus, VideoPlay } from '@element-plus/icons-vue' // 引入 VideoPlay
import { ElMessage } from 'element-plus'
import ListLayout from './ListLayout.vue'

const router = useRouter()
const route = useRoute()
const eventID = route.params.id // 取得活動 ID

const pageLoading = ref(true)
const submitting = ref(false)
const fileInputRef = ref(null)
const previewImage = ref(null) 
const selectedFile = ref(null) 

// 環境變數
const apiBase = import.meta.env.VITE_API_BASE
// ★ 如果你的活動圖片放在不同資料夾，建議在 .env 新增 VITE_EVENT_IMG_BASE
// 這裡暫時沿用邏輯，若無設定則需手動調整路徑
const imgBase = import.meta.env.VITE_EVENT_IMG_BASE || import.meta.env.VITE_NEWS_IMG_BASE 

// 表單資料模型
const editEventForm = reactive({
  title_zh: '',
  title_en: '',
  event_date: '',
  video_url: '', // ★ 新增影片連結
  status: 1, 
  description_zh: '',
  description_en: ''
})

// 返回列表
const goBack = () => {
  // ★ 記得確認你的 Router 名稱是否為 AnnualEventsManagement
  router.push({ name: 'AnnualEventManagement' })
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
  previewImage.value = URL.createObjectURL(file)
}

// ★ 取得單筆資料
const getEventDetail = async () => {
  if (!eventID) {
    ElMessage.error('無效的活動 ID')
    goBack()
    return
  }

  try {
    // 呼叫 getAnnualEvents.php?id=xxx
    const API_URL = `${apiBase}/getAnnualEvents.php?id=${eventID}`
    const response = await fetch(API_URL)
    const data = await response.json()

    // 填入表單 (請確保欄位名稱與資料庫一致)
    editEventForm.title_zh = data.title_zh
    editEventForm.title_en = data.title_en
    editEventForm.event_date = data.event_date // 或 data.createdate，視資料庫而定
    editEventForm.video_url = data.video_url   // ★ 讀取影片連結
    editEventForm.status = Number(data.status)
    editEventForm.description_zh = data.description_zh
    editEventForm.description_en = data.description_en
    
    // 處理圖片預覽
    if (data.pic) {
      // 組合圖片網址
      previewImage.value = `${imgBase}/${data.pic}`
    }

  } catch (error) {
    console.error(error)
    ElMessage.error('無法讀取活動資料')
  } finally {
    pageLoading.value = false
  }
}

// ★ 送出更新
const submitForm = async () => {
  submitting.value = true
  
  if(!editEventForm.title_zh || !editEventForm.event_date) {
      ElMessage.warning('請填寫標題與活動日期')
      submitting.value = false
      return
  }

  // ★ 呼叫 editAnnualEvents.php
  const API_URL = `${apiBase}/editAnnualEvents.php` 

  const fd = new FormData()
  
  // 必填：ID (注意後端接收的是 eventID 還是 id)
  fd.append('eventID', eventID)
  
  fd.append('title_zh', editEventForm.title_zh)
  fd.append('title_en', editEventForm.title_en)
  fd.append('event_date', editEventForm.event_date)
  fd.append('video_url', editEventForm.video_url) // ★ 傳送影片連結
  fd.append('status', editEventForm.status)
  fd.append('description_zh', editEventForm.description_zh)
  fd.append('description_en', editEventForm.description_en)
  
  // 只有當使用者有選新圖片時，才傳送 pic
  if (selectedFile.value) {
    fd.append('pic', selectedFile.value)
  }

  try {
    const response = await fetch(API_URL, {
      method: 'POST',
      body: fd        
    })

    const data = await response.json()

    if (data.success) {
      ElMessage.success('年度盛事更新成功！')
      router.push({ name: 'AnnualEventsManagement' })
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
  getEventDetail()
})
</script>

<style scoped>
/* 樣式沿用 editNews.vue */
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
}
.back-btn:hover {
  border-color: #409eff;
  background-color: #F0F7FF;
  color: #409eff;
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
}
.upload-box:hover {
  border-color: #409eff;
}

.hidden-input { display: none; }
.upload-placeholder { text-align: center; color: #909399; }
.upload-icon { font-size: 48px; color: #dcdfe6; margin-bottom: 10px; }
.upload-text { font-size: 14px; margin-bottom: 5px; }
.upload-hint { font-size: 12px; color: #c0c4cc; line-height: 1.5; }

.preview-container { width: 100%; height: 100%; position: relative; }
.preview-img { width: 100%; height: 100%; object-fit: contain; display: block; }

.overlay {
  position: absolute;
  top: 0; left: 0; width: 100%; height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex; justify-content: center; align-items: center;
  opacity: 0; transition: opacity 0.3s;
  color: white; font-size: 16px;
}
.upload-box:hover .overlay { opacity: 1; }

:deep(.el-form-item__label) {
  font-weight: 500;
  color: #606266;
}
</style>
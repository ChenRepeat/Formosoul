<template>
  <ListLayout>
    <template #title><h6>編輯年度盛事</h6></template>
    <template #controls>
      <el-button @click="goBack" class="back-btn" round>
        <el-icon><ArrowLeft /></el-icon>返回列表
      </el-button>
    </template>

    <div class="scroll-container" v-loading="pageLoading">
      <div class="form-container">
        <el-form :model="editEventForm" ref="formRef" label-position="top" class="custom-form">
          
          <div class="content-card">       
            <el-row :gutter="40">
              
              <el-col :span="14">
                <el-form-item label="活動標題(中文)" required>
                  <el-input v-model="editEventForm.title_zh" placeholder="請輸入中文標題" />
                </el-form-item>
                
                <el-form-item label="Event Title(EN)">
                  <el-input v-model="editEventForm.title_en" placeholder="Enter English title" />
                </el-form-item>

                <el-row :gutter="20">
                  <el-col :span="12">
                    <el-form-item label="上架/活動日期 (Launch Date)" required>
                      <el-date-picker
                        v-model="editEventForm.launchdate"
                        type="date"
                        placeholder="選擇日期"
                        value-format="YYYY-MM-DD"
                        style="width: 100%"
                      />
                    </el-form-item>
                  </el-col>
                </el-row>

                <el-form-item label="影片連結 (Video URL)">
                  <el-input v-model="editEventForm.video" placeholder="請輸入影片連結" clearable>
                    <template #prefix><el-icon><VideoPlay /></el-icon></template>
                  </el-input>
                </el-form-item>

                <el-form-item label="狀態" required>
                  <el-radio-group v-model="editEventForm.status">
                    <el-radio :label="1" border>顯示中</el-radio>
                    <el-radio :label="0" border>草稿</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-col>

              <el-col :span="10">
                <el-form-item label="活動封面照">
                  <el-upload
                    ref="uploadRef"
                    class="event-uploader"
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
                <el-divider content-position="left">內容簡介 (Summary)</el-divider>
              </el-col>
              <el-col :span="12">
                <el-form-item label="簡介(中文)">
                  <el-input v-model="editEventForm.content_summary_zh" type="textarea" :rows="3" />
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="Summary(EN)">
                  <el-input v-model="editEventForm.content_summary_en" type="textarea" :rows="3" />
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-divider content-position="left">內容 (Full Content)</el-divider>
              </el-col>
              <el-col :span="24">
                <el-form-item label="內容(中文)">
                  <el-input v-model="editEventForm.content_zh" type="textarea" :rows="6" />
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Content(EN)">
                  <el-input v-model="editEventForm.content_en" type="textarea" :rows="6" />
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
import { ArrowLeft, Plus, VideoPlay } from '@element-plus/icons-vue'
import { ElMessage, genFileId } from 'element-plus' // ★ 引入 genFileId
import ListLayout from './ListLayout.vue'

const router = useRouter()
const route = useRoute()
const eventID = route.params.id

const pageLoading = ref(true)
const submitting = ref(false)
const uploadRef = ref(null) // ★ 改用 el-upload ref
const previewImage = ref(null)
const selectedFile = ref(null)

const apiBase = import.meta.env.VITE_API_BASE
const imgBaseUrl = import.meta.env.VITE_IMG_BASE || apiBase.replace('/api', '') 

const editEventForm = reactive({
  title_zh: '',
  title_en: '',
  launchdate: '',
  video: '',
  status: 0, 
  content_summary_zh: '', 
  content_summary_en: '',
  content_zh: '',
  content_en: ''
})

const goBack = () => router.push({ name: 'AnnualEventManagement' })

// ★ 新增：處理拖曳覆蓋圖片
const handleExceed = (files) => {
  uploadRef.value.clearFiles()
  const file = files[0]
  file.uid = genFileId()
  uploadRef.value.handleStart(file)
}

// ★ 修改：統一處理圖片變更 (選取/拖曳)
const handleFileChange = (uploadFile) => {
  const file = uploadFile.raw
  if (!file) return

  if (file.size > 1024 * 1024) {
    ElMessage.warning('圖片檔案大小不能超過 1MB')
    uploadRef.value.clearFiles()
    return
  }
  
  if (!file.type.startsWith('image/')) {
    ElMessage.warning('請上傳圖片格式')
    uploadRef.value.clearFiles()
    return
  }

  selectedFile.value = file
  previewImage.value = URL.createObjectURL(file)
}

// 讀取單筆資料
const getEventDetail = async () => {
  if (!eventID) {
    ElMessage.error('無效的活動 ID')
    goBack()
    return
  }

  try {
    const API_URL = `${apiBase}/getAnnualEvents.php?id=${eventID}`
    const response = await fetch(API_URL)
    const data = await response.json()

    if (data.error) {
        throw new Error(data.error)
    }

    editEventForm.title_zh = data.title_zh
    editEventForm.title_en = data.title_en
    editEventForm.launchdate = data.launchdate
    editEventForm.video = data.video
    editEventForm.status = Number(data.status)
    editEventForm.content_summary_zh = data.content_summary_zh
    editEventForm.content_summary_en = data.content_summary_en
    editEventForm.content_zh = data.content_zh
    editEventForm.content_en = data.content_en

    // ★ 處理舊圖片預覽
    if (data.pic) {
      previewImage.value = `${imgBaseUrl}/${data.pic}`
    }

  } catch (error) {
    console.error(error)
    ElMessage.error('讀取資料失敗')
  } finally {
    pageLoading.value = false
  }
}

// 送出更新
const submitForm = async () => {
  submitting.value = true
  
  if(!editEventForm.title_zh || !editEventForm.launchdate) {
      ElMessage.warning('請填寫標題與活動日期')
      submitting.value = false
      return
  }

  const API_URL = `${apiBase}/editAnnualEvent.php` 

  const fd = new FormData()
  
  fd.append('annalevent_ID', eventID)
  fd.append('title_zh', editEventForm.title_zh)
  fd.append('title_en', editEventForm.title_en)
  fd.append('launchdate', editEventForm.launchdate)
  fd.append('video', editEventForm.video)           
  fd.append('status', editEventForm.status)
  fd.append('content_summary_zh', editEventForm.content_summary_zh)
  fd.append('content_summary_en', editEventForm.content_summary_en)
  fd.append('content_zh', editEventForm.content_zh)
  fd.append('content_en', editEventForm.content_en)
  
  if (selectedFile.value) {
    fd.append('pic', selectedFile.value)
  }

  try {
    const response = await fetch(API_URL, { method: 'POST', body: fd })
    const data = await response.json()

    if (data.success) {
      ElMessage.success('更新成功！')
      router.push({ name: 'AnnualEventManagement' })
    } else {
      ElMessage.error('更新失敗：' + (data.message || '未知錯誤'))
    }
  } catch (error) {
    console.error(error)
    ElMessage.error('系統發生錯誤')
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
    getEventDetail()
})
</script>

<style scoped>
/* 樣式保持一致 */
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

/* === ★★★ 圖片上傳區塊樣式 (統一使用 el-upload 架構) ★★★ === */

.event-uploader {
  width: 100%;
  display: block;
}

.event-uploader :deep(.el-upload) {
  width: 100%;
  display: block; 
}

.event-uploader :deep(.el-upload-dragger) {
  width: 100%;       
  height: 350px;     
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #fafafa;
  border-radius: 6px;
  transition: border-color 0.3s;
  padding: 0;        
  border: 1px dashed #dcdfe6;
}

.event-uploader :deep(.el-upload-dragger:hover) {
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

.event-uploader:hover .overlay {
  opacity: 1;
}

:deep(.el-form-item__label) {
  font-weight: 500;
  color: #606266;
}
</style>
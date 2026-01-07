<template>
  <ListLayout>
    <template #title><h6>新增年度盛事</h6></template>
    <template #controls>
      <el-button @click="goBack" class="back-btn" round>
        <el-icon><ArrowLeft /></el-icon>返回列表
      </el-button>
    </template>

    <div class="scroll-container">
      <div class="form-container">
        <el-form :model="addEventForm" ref="formRef" label-position="top" class="custom-form">
          
          <div class="content-card">       
            <el-row :gutter="40">
              
              <el-col :span="14">
                <el-form-item label="活動標題(中文)" required>
                  <el-input v-model="addEventForm.title_zh" placeholder="請輸入中文標題" />
                </el-form-item>
                
                <el-form-item label="Event Title(EN)">
                  <el-input v-model="addEventForm.title_en" placeholder="Enter English title" />
                </el-form-item>

                <el-row :gutter="20">
                  <el-col :span="12">
                    <el-form-item label="上架/活動日期 (Launch Date)" required>
                      <el-date-picker
                        v-model="addEventForm.launchdate"
                        type="date"
                        placeholder="選擇日期"
                        value-format="YYYY-MM-DD"
                        style="width: 100%"
                      />
                    </el-form-item>
                  </el-col>
                </el-row>

                <el-form-item label="影片連結 (Video URL)">
                  <el-input v-model="addEventForm.video" placeholder="請輸入影片連結" clearable>
                    <template #prefix><el-icon><VideoPlay /></el-icon></template>
                  </el-input>
                </el-form-item>

                <el-form-item label="狀態" required>
                  <el-radio-group v-model="addEventForm.status">
                    <el-radio :label="1" border>顯示中</el-radio>
                    <el-radio :label="0" border>草稿</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-col>

              <el-col :span="10">
                <el-form-item label="活動封面照">
                  <div class="upload-box" @click="triggerFileInput">
                    <input type="file" ref="fileInputRef" class="hidden-input" accept="image/*" @change="handleImageChange">
                    <div v-if="previewImage" class="preview-container">
                      <img :src="previewImage" class="preview-img" />
                      <div class="overlay"><span>更換圖片</span></div>
                    </div>
                    <div v-else class="upload-placeholder">
                      <el-icon class="upload-icon"><Plus /></el-icon>
                      <div class="upload-text">上傳圖片</div>
                    </div>
                  </div>
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-divider content-position="left">內容簡介 (Summary)</el-divider>
              </el-col>
              <el-col :span="12">
                <el-form-item label="簡介(中文)">
                  <el-input v-model="addEventForm.content_summary_zh" type="textarea" :rows="3" />
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="Summary(EN)">
                  <el-input v-model="addEventForm.content_summary_en" type="textarea" :rows="3" />
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-divider content-position="left">內容 (Full Content)</el-divider>
              </el-col>
              <el-col :span="24">
                <el-form-item label="內容(中文)">
                  <el-input v-model="addEventForm.content_zh" type="textarea" :rows="6" />
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Content(EN)">
                  <el-input v-model="addEventForm.content_en" type="textarea" :rows="6" />
                </el-form-item>
              </el-col>

            </el-row>
          </div>

          <div class="footer-actions">
            <el-button @click="goBack" class="cancel-btn">取消</el-button>
            <el-button type="primary" color="#003060" @click="submitForm" :loading="loading">新增活動</el-button>
          </div>

        </el-form>
      </div>
    </div>
  </ListLayout>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { ArrowLeft, Plus, VideoPlay } from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'
import ListLayout from './ListLayout.vue'

const router = useRouter()
const loading = ref(false)
const fileInputRef = ref(null)
const previewImage = ref(null)
const selectedFile = ref(null)

// ★ 這裡的欄位必須對應 PHP $_POST 想要接收的資料
const addEventForm = reactive({
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

const goBack = () => router.push({ name: 'AnnualEventsManagement' })
const triggerFileInput = () => fileInputRef.value.click()

const handleImageChange = (event) => {
  const file = event.target.files[0]
  if (file && file.size <= 1024 * 1024) {
    selectedFile.value = file
    previewImage.value = URL.createObjectURL(file)
  } else {
    ElMessage.warning('圖片過大或未選擇')
  }
}

const submitForm = async () => {
  loading.value = true
  
  // 簡單驗證
  if(!addEventForm.title_zh || !addEventForm.createdate) {
      ElMessage.warning('請填寫標題與建立日期')
      loading.value = false
      return
  }

  const apiBase = import.meta.env.VITE_API_BASE
  const API_URL = `${apiBase}/addAnnualEvents.php` 

  const fd = new FormData()
  
  // ★ 將所有欄位 Append 進 FormData
  fd.append('title_zh', addEventForm.title_zh)
  fd.append('title_en', addEventForm.title_en)
  fd.append('launchdate', addEventForm.launchdate)
  fd.append('video', addEventForm.video)           
  fd.append('status', addEventForm.status)
  fd.append('content_summary_zh', addEventForm.content_summary_zh)
  fd.append('content_summary_en', addEventForm.content_summary_en)
  fd.append('content_zh', addEventForm.content_zh)
  fd.append('content_en', addEventForm.content_en)
  
  if (selectedFile.value) {
    fd.append('pic', selectedFile.value)
  }

  try {
    const response = await fetch(API_URL, { method: 'POST', body: fd })
    const data = await response.json()

    if (data.success) {
      ElMessage.success('新增成功！')
      router.push({ name: 'AnnualEventsManagement' })
    } else {
      ElMessage.error('新增失敗：' + (data.message || '未知錯誤'))
    }
  } catch (error) {
    console.error(error)
    ElMessage.error('系統發生錯誤')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* 樣式保持原樣即可，或依需求微調 */
.scroll-container { height: calc(100vh - 250px); overflow-y: auto; padding: 20px 20px 20px 0; }
.form-container { max-width: 1000px; margin: 0 auto; }
.content-card { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 24px; }
.upload-box { width: 100%; height: 250px; border: 1px dashed #dcdfe6; display: flex; justify-content: center; align-items: center; cursor: pointer; background: #fafafa; }
.upload-box:hover { border-color: #409eff; }
.preview-img { width: 100%; height: 100%; object-fit: contain; }
.hidden-input { display: none; }
.footer-actions { display: flex; justify-content: center; gap: 16px; margin-top: 40px; }
</style>
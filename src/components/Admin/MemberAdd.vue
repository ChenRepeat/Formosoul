<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { ArrowLeft, Plus, Close } from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'
import ListLayout from './ListLayout.vue'

const router = useRouter()
const loading = ref(false)
const formRef = ref(null)
const fileInputRef = ref(null) 

const addMemberForm = reactive({
  id: '', 
  name: '',
  email: '',
  password: '',
  status: 1 
})

const avatarPreview = ref(null) 
const selectedFile = ref(null)  

const generatePassword = () => {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
  let pwd = ''
  for (let i = 0; i < 8; i++) {
    pwd += chars.charAt(Math.floor(Math.random() * chars.length))
  }
  addMemberForm.password = pwd
}

const triggerFileInput = () => {
  fileInputRef.value.click()
}

const handleImageChange = (event) => {
  const file = event.target.files[0]
  if (!file) return

  if (file.size > 1024 * 1024) {
    ElMessage.warning('圖片檔案大小不能超過 1MB')
    return
  }

  selectedFile.value = file
  avatarPreview.value = URL.createObjectURL(file)
}

const goBack = () => {
  router.push('/admin/member-management') 
}

const submitForm = async () => {
  loading.value = true
  
  if (!addMemberForm.name || !addMemberForm.email || !addMemberForm.password) {
    ElMessage.warning('請填寫完整資訊 (姓名、Email、密碼)')
    loading.value = false
    return
  }

  const apiBase = import.meta.env.VITE_API_BASE
  const API_URL = `${apiBase}/addMember.php` 

  const fd = new FormData()
  fd.append('name', addMemberForm.name)
  fd.append('email', addMemberForm.email)
  fd.append('password', addMemberForm.password)
  fd.append('status', addMemberForm.status)

  if (selectedFile.value) {
    fd.append('avatar', selectedFile.value) 
  }

  try {
    const response = await fetch(API_URL, {
      method: 'POST',
      body: fd
    })

    const data = await response.json()

    if (data.success) {
    console.log('準備顯示訊息:', data.message)
      ElMessage.success(data.message)
      goBack()
    } else {
      ElMessage.error(data.message || '新增失敗')
    }
  } catch (error) {
    console.error('API Error:', error)
    ElMessage.error('系統發生錯誤，無法連線到伺服器')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <ListLayout>
    
    <template #title>
      <h6>新增會員</h6>
    </template>

    <template #controls>
      <el-button @click="goBack" class="back-btn" round>
        <el-icon><ArrowLeft /></el-icon>返回列表
      </el-button>
    </template>

    <div class="scroll-container">
      <div class="member-add-container">
        <el-form 
          :model="addMemberForm" 
          ref="formRef"
          label-position="left"
          label-width="100px"
          class="member-form"
        >
          <div class="content-card">
            <el-row :gutter="40">
              <el-col :span="16" :xs="24">
                
                <el-form-item label="會員編號">
                  <el-input 
                    v-model="addMemberForm.id" 
                    disabled 
                    placeholder="系統自動產生" 
                    class="bg-gray"
                  />
                </el-form-item>

                <el-form-item label="姓名" required>
                  <el-input 
                    v-model="addMemberForm.name" 
                    placeholder="請輸入會員姓名" 
                  />
                </el-form-item>

                <el-form-item label="Email" required>
                  <el-input 
                    v-model="addMemberForm.email" 
                    placeholder="example@mail.com" 
                  />
                </el-form-item>

                <el-form-item label="設定密碼" required class="password-item">
                  <div class="password-group">
                    <el-input 
                      v-model="addMemberForm.password" 
                      placeholder="********" 
                      type="text"
                      show-password
                    />
                    <el-button @click="generatePassword" class="gen-btn">產生密碼</el-button>
                  </div>
                  <span class="field-hint">系統自動產生 8 碼含大小寫英文及數字之亂數密碼</span>
                </el-form-item>

                <el-form-item label="會員狀態">
                  <el-radio-group v-model="addMemberForm.status">
                    <el-radio :label="1" border>
                      <div class="radio-content">
                        <div class="status-dot active"></div>啟用
                      </div>
                    </el-radio>
                    <el-radio :label="0" border>
                      <div class="radio-content">
                        <div class="status-dot inactive"></div>停權
                      </div>
                    </el-radio>
                  </el-radio-group>
                </el-form-item>

              </el-col>

              <el-col :span="8" :xs="24">
                <div class="custom-label">會員頭貼</div>
                
                <el-form-item label-width="0"> <div class="upload-box" @click="triggerFileInput">
                    <input 
                      type="file" 
                      ref="fileInputRef" 
                      class="hidden-input" 
                      accept="image/*"
                      @change="handleImageChange"
                    >
                    
                    <div v-if="avatarPreview" class="preview-container">
                      <img :src="avatarPreview" class="preview-img" />
                      <div class="overlay">
                        <span>更換圖片</span>
                      </div>
                    </div>

                    <div v-else class="upload-placeholder">
                      <el-icon class="upload-icon"><Plus /></el-icon>
                      <div class="upload-text">上傳頭貼</div>
                      <div class="upload-hint">建議尺寸 500*500 px<br>檔案大小 ≤ 1MB</div>
                    </div>
                  </div>
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
              儲存新增
            </el-button>
          </div>
        </el-form>
      </div>
    </div>
  </ListLayout>
</template>

<style lang="scss" scoped>
.scroll-container {
  height: auto;     
  overflow-y: visible;
  padding-right: 0; 
}

.member-add-container {
  max-width: 1000px;
  padding-bottom: 40px;
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

.content-card {
  background: #fff;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 24px;
}

.member-form :deep(.el-form-item__label) {
  font-weight: 500;
  color: #333;
}

/* ★★★ 新增：自定義標籤樣式 ★★★ */
.custom-label {
  font-size: 14px;
  color: #333; /* 與 Element Plus label 顏色一致 */
  font-weight: 500;
  margin-bottom: 10px; /* 讓標題跟圖片有點距離 */
  line-height: 1.5;
}

.password-group {
  display: flex;
  width: 100%;
  gap: 10px;
  .el-input { flex: 1; }
  .gen-btn {
    background: #E5E5E5;
    color: #333;
    border: none;
    &:hover { background: #d4d4d4; }
  }
}

.field-hint {
  color: #999;
  margin-top: 4px;
  display: block;
}

.radio-content {
  display: flex;
  align-items: center;
  gap: 6px;
}
.status-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  &.active { background-color: #67C23A; }
  &.inactive { background-color: #909399; }
}

.footer-actions {
  display: flex;
  justify-content: center; 
  gap: 16px;
  margin-top: 20px;
  border-top: 1px solid #eee;
  padding-top: 20px;
}
.cancel-btn {
  width: 100px;
}

/* 圖片上傳區塊樣式 */
.upload-box {
  width: 100%;
  height: 250px;
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
</style>
<template>
  <div class="member-add-container">
    
    <div class="page-header">
      <div class="header-content">
        <h6 class="title">新增會員</h6>
        <p class="sub-title">建立一位新會員帳號，亂數產生密碼並寄信給會員登入修改。</p>
      </div>
      
      <div class="header-actions">
        <el-button @click="goBack" class="back-btn" round>
          <el-icon class="el-icon--left"><ArrowLeft /></el-icon>返回列表
        </el-button>
      </div>
    </div>

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
            <div class="avatar-upload-section">
              <div class="upload-label">
                <span>=</span>
                <el-icon class="close-icon" @click="clearImage" v-if="avatarList.length"><Close /></el-icon>
              </div>        
              <el-upload
                class="avatar-uploader"
                action="#"
                :auto-upload="false"
                :limit="1"
                :show-file-list="false"
                :on-change="handleImageChange"
                drag
              >
                <img v-if="avatarPreview" :src="avatarPreview" class="avatar-preview" />
                <div v-if="!avatarPreview" class="upload-placeholder">
                  <el-icon class="placeholder-icon"><Picture /></el-icon>
                </div>
              </el-upload>
              <div class="avatar-text">會員頭貼</div>
            </div>
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
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { Picture, Close, ArrowLeft } from '@element-plus/icons-vue' 
import { ElMessage } from 'element-plus'
// 移除 ListLayout 引入
// import ListLayout from './ListLayout.vue' 

const router = useRouter()
const loading = ref(false)
const formRef = ref(null)

const addMemberForm = reactive({
  id: '', 
  name: '',
  email: '',
  password: '',
  status: 1 
})

const avatarList = ref([])
const avatarPreview = ref('')

const generatePassword = () => {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
  let pwd = ''
  for (let i = 0; i < 8; i++) {
    pwd += chars.charAt(Math.floor(Math.random() * chars.length))
  }
  addMemberForm.password = pwd
}

const handleImageChange = (uploadFile) => {
  avatarList.value = [uploadFile]
  avatarPreview.value = URL.createObjectURL(uploadFile.raw)
}

const clearImage = () => {
  avatarList.value = []
  avatarPreview.value = ''
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

  if (avatarList.value.length > 0) {
    fd.append('avatar', avatarList.value[0].raw)
  }

  try {
    const response = await fetch(API_URL, {
      method: 'POST',
      body: fd
    })

    const data = await response.json()

    if (data.success) {
      ElMessage.success('會員新增成功！')
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

<style lang="scss" scoped>
/* 主要容器 */
.member-add-container {
  max-width: 1000px;
  margin: 0 auto;
  padding-bottom: 40px;
}

/* 1. 新增 Header 區塊樣式 */
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end; /* 讓按鈕對齊文字底部 */
  margin-bottom: 24px;
}

.title {
  color: #1a1a1a;
  margin: 0 0 8px 0;
}

.sub-title {
  font-size: 14px;
  color: #666;
  margin: 0;
  line-height: 1.5;
}

.back-btn {
  background-color: #F0F7FF;
  border-color: #dcdfe6;
  color: #606266;
  &:hover {
    border-color: #c6e2ff;
    background-color: #ecf5ff;
    color: #409eff;
  }
}

/* 以下維持原樣 */
.content-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.05);
  padding: 40px;
  margin-bottom: 24px;
}

.member-form :deep(.el-form-item__label) {
  font-weight: 500;
  color: #333;
}

.member-form :deep(.el-input__wrapper) {
  box-shadow: 0 0 0 1px #dcdfe6 inset;
  padding: 8px 12px;
  &.is-disabled {
    background-color: #F5F7FA;
  }
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

.password-item :deep(.el-form-item__content) {
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
.member-form :deep(.el-radio.is-bordered.is-checked) {
  border-color: #003060;
  .el-radio__inner {
    border-color: #003060;
    background: #003060;
  }
  .el-radio__label { color: #003060; }
}

.avatar-upload-section {
  border: 1px solid #eee;
  border-radius: 8px;
  padding: 10px;
  text-align: center;
  position: relative;
  background: #fff;
}

.upload-label {
  display: flex;
  justify-content: space-between;
  padding: 0 10px 10px;
  color: #ccc;
  .close-icon {
    cursor: pointer;
    &:hover { color: #333; }
  }
}

.avatar-uploader :deep(.el-upload-dragger) {
  width: 100%;
  height: 200px; 
  display: flex;
  justify-content: center;
  align-items: center;
  border: 2px dashed #e0e0e0;
  background: #fafafa;
  border-radius: 4px;
  padding: 0;
  overflow: hidden;
  &:hover { border-color: #003060; }
}

.placeholder-icon {
  color: #ddd;
}

.avatar-preview {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-text {
  margin-top: 10px;
  color: #333;
}

.footer-actions {
  display: flex;
  justify-content: center; 
  gap: 16px;
  margin-top: 20px;
}
.cancel-btn {
  width: 100px;
}
</style>
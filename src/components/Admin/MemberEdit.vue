<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { ArrowLeft } from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'
import ListLayout from './ListLayout.vue'

const router = useRouter()
const route = useRoute()
const memberID = route.params.id

const loading = ref(false) // 頁面載入
const submitting = ref(false) // 送出按鈕

// 表單資料
const editMemberForm = reactive({
  id: '', 
  name_zh: '',
  name_en: '',
  email: '',
  role: 0,        // 0: 一般會員, 1: 管理員
  status: 1,      // 1: 啟用, 0: 停權
  suspend_reason: '' // 停權原因
})

const goBack = () => {
  router.push('/admin/member-management') 
}

// 取得單筆會員資料
const getMemberDetail = async () => {
  loading.value = true
  try {
    const apiBase = import.meta.env.VITE_API_BASE
    // 假設你的讀取 API 為 getMember.php?id=xxx
    const API_URL = `${apiBase}/getMember.php?id=${memberID}`
    const response = await fetch(API_URL)
    const data = await response.json()

    if (data.error) throw new Error(data.error)

    // 填入表單
    editMemberForm.id = data.memberID // 假設後端回傳 memberID
    editMemberForm.name_zh = data.name_zh
    editMemberForm.name_en = data.name_en
    editMemberForm.email = data.email
    editMemberForm.role = Number(data.role)
    editMemberForm.status = Number(data.status)
    editMemberForm.suspend_reason = data.suspend_reason || ''

  } catch (error) {
    console.error(error)
    ElMessage.error('無法讀取會員資料')
    goBack()
  } finally {
    loading.value = false
  }
}

// 送出更新
const submitForm = async () => {
  submitting.value = true
  
  const apiBase = import.meta.env.VITE_API_BASE
  // 假設你的更新 API 為 editMember.php
  const API_URL = `${apiBase}/editMember.php` 

  // 使用 JSON 傳送資料 (因為只有文字欄位，不含圖片，用 JSON 比較單純)
  // 如果你的後端習慣接 FormData，也可以改用 FormData
  const payload = {
    id: memberID,
    role: editMemberForm.role,
    status: editMemberForm.status,
    suspend_reason: editMemberForm.suspend_reason
  }

  try {
    const response = await fetch(API_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })

    const data = await response.json()

    if (data.success) {
      ElMessage.success('會員資料更新成功！')
      goBack()
    } else {
      ElMessage.error(data.message || '更新失敗')
    }
  } catch (error) {
    console.error('API Error:', error)
    ElMessage.error('系統發生錯誤')
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
  getMemberDetail()
})
</script>

<template>
  <ListLayout>
    
    <template #title>
      <h6>編輯會員</h6>
    </template>

    <template #controls>
      <el-button @click="goBack" class="back-btn" round>
        <el-icon><ArrowLeft /></el-icon>返回列表
      </el-button>
    </template>

    <div class="scroll-container" v-loading="loading">
      <div class="member-edit-container">
        <el-form 
          :model="editMemberForm" 
          label-position="left"
          label-width="120px"
          class="member-form"
        >
          <div class="content-card">
            
            <el-form-item label="會員編號">
              <el-input v-model="editMemberForm.id" disabled class="bg-gray" />
            </el-form-item>

            <el-row :gutter="20">
              <el-col :span="12">
                <el-form-item label="姓名(中文)">
                  <el-input v-model="editMemberForm.name_zh" disabled class="bg-gray" />
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="Name(EN)">
                  <el-input v-model="editMemberForm.name_en" disabled class="bg-gray" />
                </el-form-item>
              </el-col>
            </el-row>

            <el-form-item label="Email">
              <el-input v-model="editMemberForm.email" disabled class="bg-gray" />
            </el-form-item>

            <el-divider content-position="left">權限與狀態設定</el-divider>

            <el-form-item label="權限">
              <el-radio-group v-model="editMemberForm.role">
                <el-radio :label="1" border>
                  <div class="radio-content">
                     <span class="role-badge admin">管理員</span>
                  </div>
                </el-radio>
                <el-radio :label="0" border>一般會員</el-radio>
              </el-radio-group>
            </el-form-item>

            <el-form-item label="會員狀態">
              <el-radio-group v-model="editMemberForm.status">
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
              <span class="field-hint">停權後：會員將無法登入前台網站，無法再累積集點；既有訂單與集點記錄仍會保留在系統中。</span>
            </el-form-item>

            <el-form-item label="停權原因 (備註)">
              <el-input 
                v-model="editMemberForm.suspend_reason" 
                type="textarea" 
                :rows="3"
                placeholder="範例：違反社群規範，暫時停權觀察中……"
                :disabled="editMemberForm.status === 1" 
              />
              </el-form-item>

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
              儲存變更
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

.member-edit-container {
  max-width: 800px; /* 編輯頁面寬度稍微窄一點比較好看 */
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
  padding: 30px;
  margin-bottom: 24px;
}

.member-form :deep(.el-form-item__label) {
  font-weight: 500;
  color: #333;
}

/* 唯讀欄位樣式 */
.bg-gray :deep(.el-input__wrapper) {
  background-color: #F5F7FA;
  box-shadow: none !important;
  border: 1px solid #E4E7ED;
}

.field-hint {
  color: #909399;
  font-size: 13px;
  margin-top: 8px;
  display: block;
  line-height: 1.5;
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

.role-badge.admin {
  color: #003060;
  font-weight: bold;
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
</style>
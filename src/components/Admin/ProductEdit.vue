<template>
  <div class="product-add-container">
    
    <div class="page-header">
      <div class="header-content">
        <h6 class="title">商品編輯</h6>
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
            <el-form-item label="Product Name (English)" required>
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
            <el-form-item label="Product Type" required>
              <el-select v-model="addProductForm.typeEn" placeholder="Please select a type" style="width: 100%">
                <el-option label="Folktoys" value="Folktoys" />
                <el-option label="Personalized" value="Personalized" />
                <el-option label="Accessories" value="Accessories" />
                <el-option label="Voucher" value="Voucher" />
              </el-select>
            </el-form-item>
          </el-col>

          <el-col :span="12">
            <el-form-item label="商品分類" required>
              <el-select v-model="addProductForm.typeZh" placeholder="請選擇分類" style="width: 100%">
                <el-option label="童玩" value="童玩" />
                <el-option label="客製化商品" value="客製化商品" />
                <el-option label="飾品" value="飾品" />
                <el-option label="體驗券" value="體驗券" />
              </el-select>
            </el-form-item>
          </el-col>

          <el-col :span="12">
            <el-form-item label="上下架狀態" required>
              <el-radio-group v-model="addProductForm.status">
                <el-radio-button :label="1">上架</el-radio-button>
                <el-radio-button :label="0">下架</el-radio-button>
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
        <el-button @click="goBack" size="large">取消編輯</el-button>
        <el-button type="primary" color="#003060" @click="submitForm" size="large" :loading="loading">
          更新商品
        </el-button>
      </div>

    </el-form>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRouter,useRoute } from 'vue-router'
import { Plus, UploadFilled } from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'

const router = useRouter()
const route = useRoute()
const loading = ref(false)
const productID = route.params.id

// 表單資料
const addProductForm = reactive({
  nameZh: '',
  nameEn: '',
  id: '', 
  typeEn: '',
  typeZh: '',
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

  // const fetchProductDetails = async (id) => {
  //   const apiBase = import.meta.env.VITE_API_BASE
  //   const apiURL = `${apiBase}/getProductData.php?id=${id}`
  //   try{
  //     loading.value = true;
  //     const response = await fetch(apiURL)
  //     const data = await response.json()
  //     console.log('抓回來的資料:',data)

  //     if(data && data.length >0){
  //       addProductForm.nameZh = data.name_zh;
  //       addProductForm.nameEn = data.name_en;
  //       addProductForm.id = data.product_ID;
  //       addProductForm.typeEn = data.type_en;
  //       addProductForm.typeZh = data.type_zh;
  //       addProductForm.createDate = data.createdate;
  //       addProductForm.descriptionZh = data.description_zh;
  //       addProductForm.descriptionEn = data.description_en;
  //       addProductForm.storyZh = data.story_zh;
  //       addProductForm.storyEn = data.story_en;
  //       addProductForm.useZh = data.use_zh;
  //       addProductForm.useEn = data.use_en;
  //     }
  //     if (data.main_image) {
        
  //       // 把網址包裝成 Element Plus 看得懂的格式
  //       mainImage.value = [
  //         {
  //           name: 'image.png',  // 顯示用，可任意取名
  //           url: data.main_image // 這裡才是重點！要把後端的網址填進來
  //         }
  //       ];
        
  //     }

  //   }catch (error) {
  //   console.error(error);
  //   } finally {
  //     loading.value = false;
  //   }
  // }

const fetchProductDetails = async (id) => {
  const apiBase = import.meta.env.VITE_API_BASE
  const imgBase = import.meta.env.VITE_IMG_BASE;
  const apiURL = `${apiBase}/getProductData.php?id=${id}`

  try {
    loading.value = true
    const response = await fetch(apiURL)
    const data = await response.json()


    if (data && data.product_ID) {
      
      console.log('抓回來的商品資料:', data)

      addProductForm.nameZh = data.name_zh
      addProductForm.nameEn = data.name_en
      addProductForm.id = data.product_ID
      addProductForm.typeEn = data.type_en
      addProductForm.typeZh = data.type_zh
      addProductForm.createDate = data.create_at
      addProductForm.descriptionZh = data.details[0].description_zh
      addProductForm.descriptionEn = data.details[0].description_en
      addProductForm.storyZh = data.details[0].story_zh
      addProductForm.storyEn = data.details[0].story_en
      addProductForm.useZh = data.details[0].use_zh
      addProductForm.useEn = data.details[0].use_en
      // 小心數字與狀態 (轉型確保型態為數字)
      addProductForm.price = Number(data.price)
      addProductForm.stock = Number(data.stock)
      addProductForm.status = Number(data.product_status)

// 1. 主圖
if (data.main_image) {
        mainImage.value = [{
          name: 'current_main.png',
          // 【修正點 2】必須加上 imgBase 路徑，不然圖片預覽會破圖
          url: `${imgBase}${data.main_image}` 
        }];
      }

      // 2. 副圖
      // 【修正點 3】PHP 回傳的是 ['img1.jpg', 'img2.jpg'] 字串陣列
      // 不能用 img.url，因為 img 本身就是字串
      if (data.sub_images && Array.isArray(data.sub_images) && data.sub_images.length > 0) {
        subImages.value = data.sub_images.map((filename, index) => ({
          name: `sub_${index}.png`,
          url: `${imgBase}${filename}`, // 組合完整路徑
          rawName: filename // (選填) 之後送出表單可能用到
        }));
      }
    } else {
      console.error('API 回傳狀態不正確或查無資料')
    }
  } catch (error) {
    console.error('發生錯誤:', error)
  } finally {
    loading.value = false
  }
}

const submitForm = async () => {
  loading.value = true;
  
  if(!addProductForm.nameZh || !addProductForm.price) {
      ElMessage.warning('請填寫必填欄位 (名稱、價格)');
      loading.value = false;
      return;
  }

  const apiBase = import.meta.env.VITE_API_BASE;
  const API_URL = `${apiBase}/editProduct.php`;

  // 準備 FormData
  const fd = new FormData();
  
  // 【關鍵】一定要傳 ID 給 PHP，不然它不知道要更新哪一筆
  fd.append('product_ID', addProductForm.id);

  // 將表單文字資料塞入
  for (const [key, value] of Object.entries(addProductForm)) {
    // 避免傳 undefined 變成字串 "undefined"
    fd.append(key, value === undefined || value === null ? '' : value);
  }

  // 圖片上傳
  
  // (A) 主圖：只有當使用者選了"新圖片" (有 raw 屬性) 才傳送
  // 如果是舊圖 (只有 url 屬性)，代表使用者沒改圖，就不傳，PHP 會保留原圖
  if (mainImage.value.length > 0 && mainImage.value[0].raw) {
    fd.append('mainImage', mainImage.value[0].raw);
  }

  // (B) 副圖：只傳送"新增加"的圖片
  subImages.value.forEach((file) => {
    if (file.raw) {
       fd.append('subImages[]', file.raw);
    }
  });

  try {
    const response = await fetch(API_URL, {
      method: 'POST',
      body: fd        
    });
    
    const data = await response.json();

    if (data.success) {
      ElMessage.success('商品更新成功！');
      // 更新成功後，跳轉回列表頁
      router.push('/admin/product-management');
    } else {
      ElMessage.error('更新失敗：' + (data.message || '未知錯誤'));
    }

  } catch (error) {
    console.error('Network error:', error);
    ElMessage.error('系統發生錯誤，無法連線到伺服器');
  } finally {
    loading.value = false;
  }
}

  onMounted (() =>{
    if (productID) {
    fetchProductDetails(productID)
  }
  })
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
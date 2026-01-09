<script setup>
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import BasicButton from '@/components/BasicButton.vue';

const router = useRouter();
const { t } = useI18n();

const goBack = () => {
  if (window.history.length > 1) {
    router.go(-1);
  } else {
    router.push('/'); // 沒紀錄就回首頁
  }
}
const goHome = () => {
    router.push('/');
}
</script>

<template>
  <div class="not-found-layout">
    <div class="not-found">
      <h5>
          {{ t('notfound.notFoundMsg') }}
      </h5>
      <div class="btn-layout">
        <BasicButton @click="goBack()" class="hover-btn">
          {{ t('notfound.btnBack') }}
        </BasicButton>
        <BasicButton @click="goHome()" class="hover-btn">
          {{ t('notfound.btnHome') }}
        </BasicButton>
      </div>
    </div>
  </div>
</template>
<style scoped>
.not-found-layout{
  width: 1200px;
  margin: 0 auto;
}
.not-found {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 50px;
  color: white;
  min-height: 100vh;
}
.not-found h5{
  color: white;
  margin-bottom: 40px;
}
.btn-layout{
  display: flex;
  justify-content: center;
  gap: 20px;
}
/* 1. 設定過渡動畫，讓變化滑順 */
.hover-btn {
  transition: all 0.3s ease-in-out;
}

/* 2. 設定滑鼠懸停時的狀態 */
.hover-btn:hover {
  /* 往上浮動 5px */
  transform: translateY(-5px); 
  
  /* 增加一點亮度和陰影，製造立體感 */
  filter: brightness(1.1); 
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3); 
}

/* 3. (選用) 按下去時稍微縮小，更有按壓感 */
.hover-btn:active {
  transform: translateY(-2px);
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}
</style>
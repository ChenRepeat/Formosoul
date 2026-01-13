import { createI18n } from 'vue-i18n';
import zh from './zh-TW.json';
import en from './en-US.json';
import jp from './jp.json'

const i18n = createI18n({
  legacy: false,
  locale: sessionStorage.getItem('user-lang') || 'en-US', // 預設語系
  fallbackLocale: 'zh-TW', // 找不到翻譯時的備用語系, 0113針對日文缺失問題改成用中文顯示
  messages: {
    'zh-TW': zh,
    'en-US': en,
    'ja-JP': jp
  }
});

export default i18n;
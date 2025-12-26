import { createI18n } from 'vue-i18n';
import zh from './zh-TW.json';
import en from './en-US.json';

const i18n = createI18n({
  legacy: false,
  locale: sessionStorage.getItem('user-lang') || 'en-US', // 預設語系
  fallbackLocale: 'en-US', // 找不到翻譯時的備用語系
  messages: {
    'zh-TW': zh,
    'en-US': en
  }
});

export default i18n;
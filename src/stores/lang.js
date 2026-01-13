import { defineStore } from 'pinia';
import i18n from '@/i18n'; 
export const useLangStore = defineStore('lang', {
  state: () => ({
    locale: sessionStorage.getItem('user-lang') || 'en-US'
  }),
  getters: {
    dbSuffix: (state) => {
      const map = {
        'en-US': 'en',
        'zh-TW': 'zh',
        'ja-JP': 'ja'
      };
      return map[state.locale] || state.locale.split('-')[0];
    }
  },
  actions: {
    setLanguage(lang) {
      this.locale = lang;
      i18n.global.locale.value = lang;
      sessionStorage.setItem('user-lang', lang);
      document.querySelector('html').setAttribute('lang', lang);
    },
    initLanguage() {
      i18n.global.locale.value = this.locale;
      document.querySelector('html').setAttribute('lang', this.locale);
    }
  }
});
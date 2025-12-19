import { defineStore } from 'pinia';
import i18n from '@/i18n'; 
export const useLangStore = defineStore('lang', {
  state: () => ({
    locale: localStorage.getItem('user-lang') || 'en-US'
  }),
  actions: {
    setLanguage(lang) {
      this.locale = lang;
      i18n.global.locale.value = lang;
      localStorage.setItem('user-lang', lang);
      document.querySelector('html').setAttribute('lang', lang);
    }
  }
});
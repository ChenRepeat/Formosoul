import { defineStore } from 'pinia';
import i18n from '@/i18n'; 
export const useLangStore = defineStore('lang', {
  state: () => ({
    locale: sessionStorage.getItem('user-lang') || 'en-US'
  }),
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
import { defineStore } from "pinia";
import { computed, ref } from "vue";

export const useAuthStore = defineStore('auth', () => {
    // 認證狀態
    const user = ref(null);
    const token = ref(localStorage.getItem('token') || null);
    // 彈窗狀態
    const isLoginModalOpen = ref(false);
    // 計算屬性 雙重否定會讓兩個value值 除了字串外都會是false
    const isLoggedIn = computed(() => !!token.value && !!user.value);

    // 認證方法
    const setUser = (userData) => {
        user.value = userData;
    };

    const setToken = (newToken) => {
        token.value = newToken;
        localStorage.setItem('token', newToken);
    };

    const logout = () =>{
        user.value = null;
        token.value = null;
        localStorage.removeItem('token');
    };
    
    // 彈窗的方式
    const openLoginModal = () => {
        isLoginModalOpen.value = true;
        document.body.style.overflow = 'hidden';  //鎖定背景 - 將網頁最外層設為不可滾動
    };

    const closeLoginModal = () => {
        isLoginModalOpen.value = false;
        document.body.style.overflow = '' ;

    };

    return{
        // 新增
        user,
        token,
        isLoggedIn,
        setUser,
        setToken,
        isLoginModalOpen,
        openLoginModal,
        closeLoginModal,
    }
});
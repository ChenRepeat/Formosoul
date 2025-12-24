import { defineStore } from "pinia";
import { computed, ref} from "vue";
import Cookies from 'js-cookie';
// 之後可能有封裝好的 axios
// import axios from 'axios';
export const useAuthStore = defineStore('auth', () => {
    // 認證狀態
    const user = ref(JSON.parse(localStorage.getItem('user')) || null);

    const token = ref(Cookies.get('token') || null);

    // 載入狀態避免畫面閃爍
    const isLoading = ref(false);
    // 彈窗狀態
    const isLoginModalOpen = ref(false);
    const memberView = ref('coreselection');
    const loginView = ref('loginpage');
    const informationView = ref('informationmembercard');
    // 計算屬性 雙重否定會讓兩個value值 除了字串外都會是false
    const isLoggedIn = computed(() => !!token.value);

    // 認證方法
    const setUser = (userData) => {
        user.value = userData;
        localStorage.setItem('user', JSON.stringify(userData));
    };

    const setToken = (newToken) => {
        token.value = newToken;
        // expires: 1 代表 1 天
        Cookies.set('token', newToken, { expires: 1 }); 
    };
    const logout = () =>{
        user.value = null;
        token.value = null;
        Cookies.remove('token');
        localStorage.removeItem('user');
    };
    const setmemberView = (viewName) => {
        memberView.value = viewName;
    };
    const setloginView = (viewName) =>{
        loginView.value = viewName;
    };

    const setinformationView = (viewName) => {
        informationView.value = viewName;
    }

    const fetchUser = async () => {

        isLoading.value = true;
        try{
            // 模擬 API 請求
            // const res = await axios.get('/api/user/profile', {
            //    headers: { Authorization: `Bearer ${token.value}` }
            // });
            // user.value = res.data;  
            // await new Promise(r => setTimeout(r, 500));
            // user.value = { id: 1, name: "test", role: "admin"};
        } catch(error){
            console.error("Token 失效或網路錯誤", error);
            logout();
        }finally{
            isLoading.value = false;
        }
    }





    
    // 彈窗的方式
    const openLoginModal = () => {
        isLoginModalOpen.value = true;
        document.body.style.overflow = 'hidden';  //鎖定背景 - 將網頁最外層設為不可滾動

    };

    const closeLoginModal = () => {
        isLoginModalOpen.value = false;
        document.body.style.overflow = '' ;
        if(memberView.value = 'cardcontain'){
            setTimeout(() => {
                memberView.value = 'coreselection';
            }, 500);
        }
    };

    return{
        // 新增
        user,
        token,
        isLoggedIn,
        isLoading,
        setUser,
        setToken,
        logout,
        fetchUser,
        isLoginModalOpen,
        openLoginModal,
        closeLoginModal,
        memberView,
        setmemberView,
        loginView,
        setloginView,
        informationView,
        setinformationView,
    }
});
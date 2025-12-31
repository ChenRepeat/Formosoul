import { defineStore } from "pinia";
import { computed, ref} from "vue";
import Cookies from 'js-cookie';
import { useMemberStore } from "./member";
import { useRouter } from "vue-router";
// 之後可能有封裝好的 axios
// import axios from 'axios';
export const useAuthStore = defineStore('auth', () => {
    // 認證狀態
    const user = ref(JSON.parse(localStorage.getItem('user')) || null);

    const token = ref(localStorage.getItem('token') || Cookies.get('token') || null);
    const router = useRouter();
    // 載入狀態避免畫面閃爍
    const isLoading = ref(true);
    // 彈窗狀態
    const isLoginModalOpen = ref(false);
    const memberView = ref('coreselection');
    const loginView = ref('loginpage');
    const informationView = ref('informationmembercard');
    
    const isLoggedIn = computed(() => !!token.value);
    const memberStore = useMemberStore();
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

    localStorage.removeItem('token');

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
        memberStore.memberData.isEditing = false;

        if(memberView.value != 'coreselection'){
            setTimeout(() => {
                memberView.value = 'coreselection';
            }, 500);
        }

        if(loginView.value != 'loginpage'){
            setTimeout(() => {
                memberView.value = 'loginpage';
            }, 500);
        }

        if(informationView.value != 'informationmembercard'){
            setTimeout(() => {
                memberView.value = 'informationmembercard';
            }, 500);
        }
    };

    // === 新增：專門處理 LINE 登入的 Action ===
    const loginWithLine = (base64Data) => {
        try {
            // 1. 解碼
            const jsonString = atob(base64Data);
            const userData = JSON.parse(jsonString);
            
            // 2. 存入狀態 (呼叫你原本寫好的 setUser)
            setUser(userData);
            
            // 3. 設定 Token (呼叫你原本寫好的 setToken)
            // 因為我們沒有真的 JWT，用這串 base64 當作 token 來代替 isLoggedIn 的檢查
            setToken(base64Data); 
            
            // 4. 關閉彈窗
            closeLoginModal();
            
            console.log("LINE 登入成功，使用者：", userData);
            return true;
        } catch (error) {
            console.error("LINE Login 解析失敗", error);
            return false;
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
        loginWithLine,
    }
});
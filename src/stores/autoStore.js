import { defineStore } from "pinia";
import { computed, ref} from "vue";
import Cookies from 'js-cookie';
import { useMemberStore } from "./member";
import { useRouter } from "vue-router";
import { useCartStore } from "./cart";
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
    const cartstore = useCartStore();
    // 認證方法
    const setUser = (userData) => {
        user.value = userData;
        localStorage.setItem('user', JSON.stringify(userData));
        // 購物車同步取得會員ID
        cartstore.getMemberID();
        
        // setTimeout(() => {
        //     cartstore.getMemberID();
        // }, 500) 
        // if ( userData && userData.member_ID ) {
        //     cartstore.memberID = userData.member_ID; 
        // }

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
        Cookies.remove('user_name');
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

    // 額外處理關閉彈窗的紀錄
    function wandcore_member_popup(member_ID, currentCoreID){
        const new_storedUser = {
            ...userData,
            wandcore_ID: currentCoreID
        };
        localStorage.setItem('user', JSON.stringify(new_storedUser));
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/save_wandcore.php`;
        return fetch(API_URL, {
                method: 'POST', 
                headers: {
                    'Content-Type' : 'application/json'
                },
                body: JSON.stringify({
                    member_ID,
                    wandcore_ID: currentCoreID
                })
            }
            ).then( res => res.json())
    };

    const storedUser = localStorage.getItem('user');
    const userData = JSON.parse(storedUser);
    const storeCore = sessionStorage.getItem('guest');
    const coreData = JSON.parse(storeCore);
    // 彈窗的方式
    const openLoginModal = () => {
        isLoginModalOpen.value = true;
        document.body.style.overflow = 'hidden';  //鎖定背景 - 將網頁最外層設為不可滾動
    };

    const closeLoginModal = () => {
        isLoginModalOpen.value = false;
        document.body.style.overflow = '' ;
        memberStore.memberData.isEditing = false;
        localStorage.removeItem('game_progress');
        if(memberView.value == 'coreselection' && storedUser && userData.member_ID){
            if(userData.wandcore_ID == null){
                wandcore_member_popup(userData.member_ID, coreData.core);
                sessionStorage.removeItem('guest');
            }
        }else{
            console.log('沒存到');
        }
        if(memberView.value != 'coreselection'){
            setTimeout(() => {
                memberView.value = 'coreselection';
            }, 1500);
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
        wandcore_member_popup,
    }
});
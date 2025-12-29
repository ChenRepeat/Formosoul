import { defineStore } from "pinia";
import { ref } from "vue";

export const useMemberStore = defineStore('member', () => {
    const publicPath = import.meta.env.BASE_URL;
    // const imgURL = ref(`${publicPath}member/googleicon.png`);
    const imgURL = ref('');


    const memberData = ref({
        name: 'Your Name',
        wandcore: 'Select Your WandCore',
        number: 'Your Number',
        date: 'Enrollment Date',
        isEditing: false, 
        tempName: '' 
    });

    const loadMemberData = async () => {
        const storedUser = localStorage.getItem('user');
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/getMemberinformation.php`;
        if(!storedUser) return;
        // 解構賦值也能讓解析出來的變數重新命名
        const { name: loginName , member_ID} = JSON.parse(storedUser);

        try{
            const response = await fetch(API_URL, {
                method: 'POST',
                headers:{
                    'Content-Type': 'application/json; charset=utf-8'
                },
                // 這裡要對應 PHP 的接收格式
                body: JSON.stringify({name: loginName, member_ID})
            });
            const result = await response.json();
            if(result.success){
                const dbData = result.data;
                memberData.value.tempName = dbData.name;
                memberData.value.number = dbData.member_ID;
                memberData.value.date =  dbData.createdate;
                memberData.value.wandcore = dbData.name_en || 'Select Your WandCore';
                imgURL.value = dbData.headshot
            }else{
                console.error(result.message);
            }
        }catch(error){
            console.error("Fetch 發生錯誤:", error);
        }
    };

    const data_uptime = async() => {
        const storedUser = localStorage.getItem('user');
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/checkoncelogin.php`;
        if(!storedUser) return;
        const { name, member_ID } = JSON.parse(storedUser);
        try{
            const response = await fetch(API_URL, {
            method: 'POST',
            headers:{
                'Content-Type': 'application/json; charset=utf-8'
            },
            body: JSON.stringify({name, member_ID})
            });
        const result = await response.json();
            if(result.success){
                
            }else{
                console.log('沒改到');

            }
        }catch(error){
            console.error('沒改到');
        }
    };

    // 之後把會員資料丟到這邊 比較好讓其他遊戲進行管理

    const updateName = (newName) => {
        memberData.value.name = newName;
    };

    const updatePhoto = (newBase64) => {
        imgURL.value = newBase64;
    };

    return {
        imgURL,
        memberData,
        updateName,
        updatePhoto,
        data_uptime,
        loadMemberData,
    };
});
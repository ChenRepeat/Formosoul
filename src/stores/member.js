import { defineStore } from "pinia";
import { ref } from "vue";

export const useMemberStore = defineStore('member', () => {
    const publicPath = import.meta.env.BASE_URL;
    // const imgURL = ref(`${publicPath}member/googleicon.png`);
    const imgURL = ref('');
    
    const orders = ref(null); 
    const setOrderNumber = (orderNumber) => {
        orders.value = orderNumber;
    };
    const memberData = ref({
        name: 'Your Name',
        wandcore: 'Select Your WandCore',
        number: 'Your Number',
        date: 'Enrollment Date',
        isEditing: false, 
        tempName: '',
        pointscard_ID:'',
        charmImg:''
    });
    const gameData = ref({
        bue: { count: 0, pass: 0 },
        charm: { img: null },
        dice: { count: 0, pass: 0 },
        motor: { count: 0, score: 0, pass: 0 },
        ring: { count: 0, score: 0, pass: 0 },
        shrimp: { count: 0, score: 0, pass: 0 },
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
                credentials: 'include',
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
                memberData.value.pointscard_ID = dbData.pointscard_ID || 'Select Your WandCore';
                imgURL.value = dbData.headshot || '';
                memberData.value.charmImg = dbData.charmgame_img1 || '';
                gameData.value.bue = { 
                    count: dbData.buegame_count, 
                    pass: dbData.buegame_pass 
                };
                gameData.value.charm = {
                    img: dbData.charmgame_img1, 
                };
                gameData.value.dice = {
                    count: dbData.dicegame_count, 
                    pass: dbData.dicegame_pass 
                };
                gameData.value.motor = {
                    count: dbData.motorcyclegame_count, 
                    pass: dbData.motorcyclegame_pass 
                };
                gameData.value.ring = {
                    count: dbData.ringgame_count, 
                    pass: dbData.ringgame_pass,
                    score: dbData.ringgame_score,
                };
                gameData.value.shrimp = {
                    count: dbData.shrimpgame_count, 
                    pass: dbData.shrimpgame_pass,
                    score: dbData.shrimpgame_score,
                };
                console.log(dbData);
                
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

    //  遊戲資料存檔
    const saveGameResult = async (gameType, gamePayload) => {
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/saveGameData.php`;
        if (!memberData.value.pointscard_ID) {
            console.error("錯誤：找不到集點卡 ID，無法存檔 (請確認是否已登入)");
            return;
        }
        try {
            const payload = {
                pointscard_ID: memberData.value.pointscard_ID,
                member_ID: memberData.value.number,
                gameType: gameType,
                score: gamePayload.score || 0,
                pass: gamePayload.pass || 0,
                img: gamePayload.img || ''
            };
            const response = await fetch(API_URL, {
                method: 'POST',
                headers:{'Content-Type': 'application/json; charset=utf-8'},
                body: JSON.stringify(payload)
            });
            const result = await response.json();
            if(result.success){
                console.log(`[${gameType}] 存檔成功:`, result.message);
                if(gameData.value[gameType]){
                    const currentData = gameData.value[gameType];
                    currentData.pass = Math.max(currentData.pass, payload.pass);
                    if (payload.score) {
                        currentData.score = Math.max(currentData.score, payload.score);
                    }
                    currentData.count = (currentData.count || 0) + 1;
                }
            } else {
                console.error('後端存檔失敗:', result.message);
            }
        } catch (error) {
            console.error('API 連線錯誤:', error);
        }
    };

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
        orders,
        setOrderNumber,
        saveGameResult,
        gameData,
    };
});
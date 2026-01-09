import { defineStore } from "pinia";
import { computed, ref } from "vue";
import axios from 'axios';
import { useI18n } from "vue-i18n";


export const useMemberStore = defineStore('member', () => {
    const { locale } = useI18n();

    // 語系切換對照
    const langList = {
        'en-US': 'En',
        'zh-TW': 'Zh'
    };
    const lang = computed( () => {
        return langList[locale.value] || 'En';
    });
    const apiBase = import.meta.env.VITE_API_BASE;
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
        charmImg:'',
        role:0,
    });
    const pointsStatus = ref({
        dice: 0, 
        shrimp: 0, 
        ring: 0, 
        bue: 0, 
        mot: 0, 
        member_wandcore: 0
    });
    const gameData = ref({
        bue: { count: 0, pass: 0 },
        charm: { img: null },
        dice: { count: 0, pass: 0 },
        wand: { count:0, pass: 0},
        motor: { count: 0, score: 0, pass: 0 },
        ring: { count: 0, score: 0, pass: 0 },
        shrimp: { count: 0, score: 0, pass: 0 },
    });

    // 獲取優惠券
    function rewards_coupon(){
        const storedUser = localStorage.getItem('user');
        const apiBase = import.meta.env.VITE_API_BASE;
        const API_URL = `${apiBase}/game_coupon.php`;
        if(!storedUser) return;
        const { pointscard_ID } = JSON.parse(storedUser);

        return fetch( API_URL, {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json'
            },
            body: JSON.stringify({
                pointscard_ID
            })
        }).then( res => res.json())
    };
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
                const wandcoreKey = computed(() => {
                    const langKey = `name${lang.value}`
                    return dbData[langKey] || 'Select Your WandCore';
                })
                memberData.value.tempName = dbData.name;
                memberData.value.number = dbData.member_ID;
                memberData.value.date =  dbData.createdate;
                memberData.value.wandcore = wandcoreKey;
                memberData.value.pointscard_ID = dbData.pointscard_ID || 'Select Your WandCore';
                memberData.value.role = dbData.role;
                imgURL.value = dbData.headshot || '';

                if (Number(dbData.motorcyclegame_pass) >= 1) pointsStatus.value.mot = 1;
                if (Number(dbData.shrimpgame_pass) >= 1) pointsStatus.value.shrimp = 1;
                if (Number(dbData.dicegame_pass) >= 1) pointsStatus.value.dice = 1;
                if (Number(dbData.buegame_pass) >= 1) pointsStatus.value.bue = 1;
                if (Number(dbData.ringgame_pass) >= 1) pointsStatus.value.ring = 1;
                if (Number(dbData.wandcore_pass) >= 1) pointsStatus.value.member_wandcore = 1;
                pointsStatus.value = {
                    mot: dbData.motorcyclegame_pass,
                    shrimp: dbData.shrimpgame_pass,
                    dice: dbData.dicegame_pass,
                    bue: dbData.buegame_pass,
                    ring: dbData.ringgame_pass,
                    member_wandcore: wandcoreKey.value || '',
                };
                // console.log(pointsStatus.value.member_wandcore);
                // console.log("資料加載成功", pointsStatus.value);

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
                gameData.value.wand = {
                    count: dbData.wandcore_count, 
                    pass: dbData.wandcore_pass 
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
                // console.log(dbData);
                
            }else{
                console.error(result.message);
            }
        }catch(error){
            console.error("Fetch 發生錯誤:", error);
        }
    };

    const stampOnepoint = async (columnName) => {
        if(!memberData.value.number){
            console.error('找不到會員，請先登入!')
            return
        }
        const API_URL = `${apiBase}/stampOnePoint.php`;
        
        try{
            const response = await axios.post(API_URL, {
                member_ID : memberData.value.number,
                column : columnName
            });

            if(response.data.success){
                pointsStatus.value[columnName] = 1;
                
                console.log(columnName)
                await fetchPointsStatus();
            } else {
                console.error('蓋章失敗', response.data.message);
            }
        } catch(error){
            console.error("API 連線錯誤:", error);
        }
    }

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
            if(!result.success){
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
        const gameTypeMapping = {
        'wandcore': 'wand',
        'mot': 'motor',
        'ring': 'ring',
        'shrimp': 'shrimp',
        'dice': 'dice',
        'bue': 'bue'
        };
        const gameDataKey = gameTypeMapping[gameType] || gameType;

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

                if(gameData.value[gameDataKey]){
                    const currentData = gameData.value[gameDataKey];
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

    const fetchPointsStatus = async () => {
    const storedUser = localStorage.getItem('user');
    if (!storedUser) {
        console.log('[Store] 未登入，跳過載入集點卡');
        return;
    }

    const API_URL = `${apiBase}/getPointsCard.php`;
    const { member_ID } = JSON.parse(storedUser);

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ member_ID })
        });

        const result = await response.json();
        console.log('原始集點卡資料狀態:', pointsStatus.value);
        if (result.success && result.data) {
            // 更新 store 的 pointsStatus
            // console.log('TESTTTTT', result.data.member_wandcore);
            pointsStatus.value = {
                mot: result.data.mot || 0,
                shrimp: result.data.shrimp || 0,
                dice: result.data.dice || 0,
                bue: result.data.bue || 0,
                ring: result.data.ring || 0,
                member_wandcore: Number(result.data.member_wandcore) || 0
            };

            // console.log('[Store] 集點卡狀態已更新:', pointsStatus.value);
        } else {
            console.error('[Store] 載入集點卡失敗:', result.message);
        }
    } catch (error) {
        console.error('[Store] API 連線錯誤:', error);
    }
};
    return {
        imgURL,
        memberData,
        updateName,
        updatePhoto,
        data_uptime,
        loadMemberData,
        fetchPointsStatus,
        orders,
        setOrderNumber,
        saveGameResult,
        gameData,
        pointsStatus,
        stampOnepoint,
        rewards_coupon,
    };
});
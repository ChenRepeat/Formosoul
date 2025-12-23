<script setup>
import { useAuthStore } from '@/stores/autoStore';
import BasicButton from '../BasicButton.vue';
import { ref } from 'vue';


// 頁面串聯 - 前往註冊頁面
const authStore = useAuthStore();

function goToEnroll(){
    authStore.setmemberView('login');
    authStore.setloginView('enrollment');
};


// 頁面串聯 - 重新感應杖心
const emit = defineEmits(['restart-coregame']);

function goToSensing(){
    emit('restart-coregame');      //按下按鈕時，發送事件給父組件

};

// 魔杖杖心

const wandCores = ref([
    {
        pic: 'Home/core/0.jpg',
        nameEn: 'Fossil Os Draconis',
        nameZh: '龍骨',
        sourceEn: 'Fossilized bone of ancient mammals.',
        sourceZh: '古哺乳動物的骨骼化石。',
        propertyEn: 'Ancient Power, Eternal Stability.',
        propertyZh: '遠古力量，不朽的穩定。',
        effectEn: 'Anchors the Mind, Resists Mental Attack, Suited for Deep Concentration.',
        effectZh: '鎮心安神，抵抗精神攻擊，適合極度專注。',
    },
    {
        pic: 'Home/core/1.jpg',
        nameEn: 'Succinum / Amber',
        nameZh: '琥珀',
        sourceEn: 'Fossilized resin of Pinaceae plants.',
        sourceZh: '松柏科植物的樹脂化石。',
        propertyEn: 'Eternity, Time Confinement, Radiance.',
        propertyZh: '永恆，時光封存，光芒。',
        effectEn: 'Enhances Chronomancy, used for Memory Restoration.',
        effectZh: '增強時光系魔法，用於記憶修復。',
    },
    {
        pic: 'Home/core/2.jpg',
        nameEn: 'Lignum Aquilariae',
        nameZh: '沉香',
        sourceEn: 'Resin-infused wood from infected Aquilaria.',
        sourceZh: '受感染的瑞香科油脂木材。',
        propertyEn: 'Spiritual Uplift, Noble Purification, Rebirth.',
        propertyZh: '靈性提升，高貴淨化，浴火重生。',
        effectEn: 'Aids Meditation & Prophecy, Enhances Spiritual Connection.',
        effectZh: '冥想與預言輔助，增強與靈界的連結。',
    },
    {
        pic: 'Home/core/3.jpg',
        nameEn: 'Faeces Vespertilionis',
        nameZh: '夜明砂',
        sourceEn: 'Dried feces of bats.',
        sourceZh: '蝙蝠的乾燥糞便。',
        propertyEn: 'Nocturnal Sight, Subtle Perception.',
        propertyZh: '夜的視覺，隱秘的感知。',
        effectEn: 'Invisibility Spells, Detection Magic, Enhances Night Casting.',
        effectZh: '隱身咒、偵測魔法，增強夜間施法。',
    },
    {
        pic: 'Home/core/4.jpg',
        nameEn: 'Cornu Cervi Pantotrichum',
        nameZh: '鹿茸',
        sourceEn: 'Young antler of Sika or Red Deer.',
        sourceZh: '梅花鹿或馬鹿的幼角。',
        propertyEn: 'Extreme Vitality, Rapid Regeneration.',
        propertyZh: '極致生命力，快速再生。',
        effectEn: 'Rapid Mana/Stamina Recovery, Provides Sustained Casting Power.',
        effectZh: '快速恢復魔力與精神力，提供續航力。',
    },
    {
        pic: 'Home/core/5.jpg',
        nameEn: 'Placenta Hominis',
        nameZh: '紫河車',
        sourceEn: 'Dried human placenta.',
        sourceZh: '健康人的乾燥胎盤。',
        propertyEn: 'Origin of Life, Primal Mana.',
        propertyZh: '生命的起始，原始的魔力。',
        effectEn: 'Powerful Creative and Healing Magic, Difficult to Control.',
        effectZh: '強大的創造性和治療性魔力。',
    },
    {
        pic: 'Home/core/6.jpg',
        nameEn: 'Cinnabar',
        nameZh: '硃砂',
        sourceEn: 'Mercury sulfide mineral.',
        sourceZh: '硫化汞礦物。',
        propertyEn: 'Exorcism, Transmutation, Toxic Nature.',
        propertyZh: '鎮邪，轉化，帶有劇毒。',
        effectEn: 'Converts Negative Energy into Protective Force.',
        effectZh: '將負面能量轉化為保護力量。',
    },
    {
        pic: 'Home/core/7.jpg',
        nameEn: 'Calculus Macacae',
        nameZh: '猴棗',
        sourceEn: "Bezoar stone from the monkey's gallbladder.",
        sourceZh: '猴子體內的膽結石。',
        propertyEn: 'Wild Wisdom, Anti-Toxicity.',
        propertyZh: '野性的智慧，解毒能力。',
        effectEn: 'Specializes in Counter-Curses and Dark Magic Reversal.',
        effectZh: '專精於解咒和反擊黑暗魔法。',
    },
    {
        pic: 'Home/core/8.jpg',
        nameEn: 'Calculus Bovis',
        nameZh: '牛黃',
        sourceEn: "Bezoar stone from the bovine's gallbladder.",
        sourceZh: '牛的膽結石。',
        propertyEn: 'Extreme Clarity, Sacred Tranquility.',
        propertyZh: '極致清醒，神聖的鎮定。',
        effectEn: 'For Complex Emergency Spells, Instantaneous Defense Activation.',
        effectZh: '適用於複雜緊急咒語或瞬間啟動防禦結界。',
    },
    {
        pic: 'Home/core/9.jpg',
        nameEn: 'Sanguis Draconis',
        nameZh: '血竭',
        sourceEn: 'Red resin exuded from the fruit of Arecaceae plants.',
        sourceZh: '棕櫚科植物果實滲出的紅色樹脂。',
        propertyEn: 'Hemostasis, Fire Affinity, Life Force Transfer.',
        propertyZh: '止血，火焰，生命力傳輸。',
        effectEn: 'Strong Offensive Fire Spells, Instantly Repairs Minor Backlash.',
        effectZh: '強大的攻擊性火焰咒，瞬間修復反噬。',
    },
    {
        pic: 'Home/core/10.jpg',
        nameEn: 'Calculus Equi',
        nameZh: '馬寶',
        sourceEn: 'Bezoar stone found in the horse body.',
        sourceZh: '馬體內的結石。',
        propertyEn: 'Speed, Resilient Spirit.',
        propertyZh: '速度，堅毅的靈魂。',
        effectEn: 'Greatly Accelerates Casting Speed, Suitable for Rapid Succession.',
        effectZh: '極大加速施法速度，適合連續快速施法。',
    },
    {
        pic: 'Home/core/11.jpg',
        nameEn: 'Radix Ginseng',
        nameZh: '人參',
        sourceEn: 'Dried root of Araliaceae plants.',
        sourceZh: '五加科植物的乾燥根。',
        propertyEn: 'King of Herbs, Extreme Energy.',
        propertyZh: '百草之王，極致的能量。',
        effectEn: 'Grants the Caster near-limitless Mana Reserves.',
        effectZh: '賦予使用者近乎無限的魔力儲備。',
    },
    {
        pic: 'Home/core/12.jpg',
        nameEn: 'Rhizoma Gastrodiae',
        nameZh: '天麻',
        sourceEn: 'Tuber of Orchidaceae plants.',
        sourceZh: '蘭科植物的塊莖。',
        propertyEn: 'Calm Sky, Wind Flow.',
        propertyZh: '平靜的天空，風的流動。',
        effectEn: 'Enhances the Stability and Precision of Air/Weather Magic.',
        effectZh: '增強風系或天氣系魔法的穩定性和精準度。',
    },
    {
        pic: 'Home/core/13.jpg',
        nameEn: 'Cordyceps',
        nameZh: '冬蟲夏草',
        sourceEn: 'Complex of fungus and moth larva.',
        sourceZh: '蝙蝠蛾幼蟲被真菌寄生形成的複合體。',
        propertyEn: 'Life-Death Transition, Yin-Yang Harmony.',
        propertyZh: '生死轉換，陰陽調和。',
        effectEn: 'Balances Magic Attributes, Suitable for Cross-Attribute Spells.',
        effectZh: '平衡不同魔力屬性，適合跨屬性咒語。',
    },
    {
        pic: 'Home/core/14.jpg',
        nameEn: 'Radix Angelicae Sinensis',
        nameZh: '當歸',
        sourceEn: 'Root of Umbelliferae plants.',
        sourceZh: '繖形科植物的根。',
        propertyEn: 'Life Force Transfer, Restoration & Healing.',
        propertyZh: '生命力傳輸，恢復與療癒。',
        effectEn: 'Nourishes Life Force, Excellent for Rapid Repair and Healing Spells.',
        effectZh: '補血活血，適合快速修復和療癒類的魔法。',
    },
    {
        pic: 'Home/core/15.jpg',
        nameEn: 'Cortex Cinnamomi',
        nameZh: '肉桂',
        sourceEn: 'Bark of Lauraceae plants.',
        sourceZh: '樟科植物的樹皮。',
        propertyEn: 'Warmth, Passion, Yang Mana.',
        propertyZh: '溫暖，熱情，陽性魔力。',
        effectEn: 'Specializes in Fire Magic, Warmth Charms, or Morale Boosting.',
        effectZh: '專精於火焰系、溫暖咒或鼓舞士氣的魔法。',
    },
    {
        pic: 'Home/core/16.jpg',
        nameEn: 'Dragon Vine',
        nameZh: '穿山龍',
        sourceEn: 'Rhizome of Dioscoreaceae plants.',
        sourceZh: '薯蕷科植物的根莖。',
        propertyEn: 'Latent Energy, Curving Flow.',
        propertyZh: '潛藏的能量，彎曲的流動。',
        effectEn: 'Tracking Spells, Space Distortion, Locating Hidden Paths.',
        effectZh: '追蹤咒、扭曲空間或尋找隱藏路徑。',
    },
    {
        pic: 'Home/core/17.jpg',
        nameEn: 'Ganoderma Lucidum',
        nameZh: '靈芝',
        sourceEn: 'Fruiting body of Ganoderma fungus.',
        sourceZh: '靈芝科真菌的子實體。',
        propertyEn: 'Immortality, Mental Resilience.',
        propertyZh: '長生不老，心靈的韌性。',
        effectEn: 'Suitable for Healing and Protection Spells, Resists Negative Emotion.',
        effectZh: '適合治療與保護系魔法，抵抗負面情緒。',
    },
    {
        pic: 'Home/core/18.jpg',
        nameEn: 'Radix Polygalae',
        nameZh: '遠志',
        sourceEn: 'Root of Polygalaceae plants.',
        sourceZh: '遠志科植物的根。',
        propertyEn: 'Willpower, Ambition, Focus.',
        propertyZh: '意志力，抱負，專注。',
        effectEn: 'Aids in Memorizing Complex Spell Structures and Focus.',
        effectZh: '有助於記憶複雜咒語結構和提升專注力。',
    },
    {
        pic: 'Home/core/19.jpg',
        nameEn: 'Poria Cocos',
        nameZh: '茯苓',
        sourceEn: 'Fungus mass parasitic on pine tree roots.',
        sourceZh: '寄生在松樹根上的真菌塊。',
        propertyEn: 'Earth Stability, Solid Resolve.',
        propertyZh: '土的穩重，堅實的意志。',
        effectEn: "Helps Clear Negative Emotions, Stabilizes the Caster's Mind.",
        effectZh: '協助使用者排除負面情緒干擾，穩定心神。',
    },
    {
        pic: 'Home/core/20.jpg',
        nameEn: 'Semen Ziziphi Spinosae',
        nameZh: '酸棗仁',
        sourceEn: 'Mature seeds of the Sour Jujube.',
        sourceZh: '酸棗的成熟種子。',
        propertyEn: 'Tranquility, Nocturnal Peace, Dreams.',
        propertyZh: '沉靜，夜的安寧，夢境。',
        effectEn: 'Specialized in Sleep Charms, Dream Magic, and Pacifying Anger.',
        effectZh: '專精於睡眠咒、夢境魔法和平息憤怒。',
    },
    {
        pic: 'Home/core/21.jpg',
        nameEn: 'Rhizoma Acori Tatarinowii',
        nameZh: '石菖蒲',
        sourceEn: 'Rhizome of Acoraceae plants.',
        sourceZh: '菖蒲科植物的根莖。',
        propertyEn: 'Clears Heart, Wisdom Awakening.',
        propertyZh: '清心開竅，智慧覺醒。',
        effectEn: 'Greatly Boosts Memory/Reaction, Aids in Resisting Mental Control.',
        effectZh: '極大增強記憶力、反應力，並協助抵抗精神控制。',
    },
    {
        pic: 'Home/core/22.jpg',
        nameEn: 'Plastrum Testudinis',
        nameZh: '龜甲',
        sourceEn: 'Plastron or carapace of the turtle.',
        sourceZh: '龜的腹甲或背甲。',
        propertyEn: 'Longevity, Prophecy, Defense.',
        propertyZh: '長壽，預言，防禦。',
        effectEn: 'Specializes in Divination, Future Sight, and Fortifying Wards.',
        effectZh: '專精於占卜學、未來預測和強化防禦結界。',
    },
    {
        pic: 'Home/core/23.jpg',
        nameEn: 'Succus Bambusae',
        nameZh: '竹瀝',
        sourceEn: 'Sap exuded from bamboo when heated.',
        sourceZh: '竹子被加熱後流出的液汁。',
        propertyEn: 'Empty Wisdom, Refreshing Mana.',
        propertyZh: '空的智慧，清新的魔力。',
        effectEn: 'Grants Extremely Fast Casting Speed and Light Spell Trajectories.',
        effectZh: '賦予魔杖極快的施法速度和輕盈軌跡。',
    },
    {
        pic: 'Home/core/24.jpg',
        nameEn: 'Flos Lonicerae',
        nameZh: '金銀花',
        sourceEn: 'Flower buds of Honeysuckle.',
        sourceZh: '忍冬的花蕾。',
        propertyEn: 'Purification, Exorcism, Detoxification.',
        propertyZh: '淨化，驅邪，解毒。',
        effectEn: 'Specializes in Purification Charms and Dispelling Malicious Magic.',
        effectZh: '專精於淨化咒和驅散惡意魔法。',
    },
    {
        pic: 'Home/core/25.jpg',
        nameEn: 'Folium Artemisiae Argyi',
        nameZh: '艾葉',
        sourceEn: 'Leaves of Artemisia argyi.',
        sourceZh: '菊科植物的葉子。',
        propertyEn: 'Amulet Power, Warmth, Warding.',
        propertyZh: '辟邪，溫暖，結界。',
        effectEn: 'Suitable for Warding Spells, Dispelling Cold/Negative Dark Magic.',
        effectZh: '適合用於結界術，驅散冰冷、負面的黑暗魔法。',
    },
    {
        pic: 'Home/core/26.jpg',
        nameEn: 'Realgar',
        nameZh: '雄黃',
        sourceEn: 'Arsenic sulfide mineral.',
        sourceZh: '硫砷化物礦物。',
        propertyEn: 'Extreme Exorcism, Anti-Toxicity.',
        propertyZh: '極致驅邪，解除中毒。',
        effectEn: 'Possesses Powerful Exorcism and Anti-Toxicity Properties.',
        effectZh: '具有強大的驅魔和解除中毒屬性。',
    },
    {
        pic: 'Home/core/27.jpg',
        nameEn: 'Concha Margaritiferae',
        nameZh: '珍珠母',
        sourceEn: 'Inner shell layer of pearl mussels.',
        sourceZh: '珍珠蚌的內殼層。',
        propertyEn: 'Oceanic Wisdom, Gentle Protection.',
        propertyZh: '海洋的智慧，柔和保護。',
        effectEn: 'Provides Strong Defensive Magic and Enchantment Spell Boost.',
        effectZh: '提供強大的防禦魔法和魅惑咒加成。',
    },
    {
        pic: 'Home/core/28.jpg',
        nameEn: 'Fructus Lycii',
        nameZh: '枸杞子',
        sourceEn: 'Mature fruit of Solanaceae plants.',
        sourceZh: '茄科植物的成熟果實。',
        propertyEn: 'Radiance, Essence, Insight.',
        propertyZh: '光明，精氣，洞察力。',
        effectEn: 'Grants the Caster Clear Insight and Sustained Vitality.',
        effectZh: '賦予魔杖使用者清晰的洞察力和持久的精力。',
    },
    {
        pic: 'Home/core/29.jpg',
        nameEn: 'Fructus Gardeniae',
        nameZh: '梔子',
        sourceEn: 'Fruit of Rubiaceae plants.',
        sourceZh: '茜草科植物的果實。',
        propertyEn: 'Fire Draining, Confusion Elimination.',
        propertyZh: '瀉火，消除混亂。',
        effectEn: 'Suitable for Calming the Mind, Eliminating Confusion and Illusions.',
        effectZh: '適合用於冷靜心神、消除混亂和驅散幻術。',
    },
    {
        pic: 'Home/core/30.jpg',
        nameEn: 'Cornu Saigae Tataricae',
        nameZh: '羚羊角',
        sourceEn: 'Horn of the Saiga Antelope.',
        sourceZh: '賽加羚羊的角。',
        propertyEn: 'Speed, Clarity, Lightning Fast.',
        propertyZh: '速度，清醒，閃電般迅速。',
        effectEn: 'Greatly Increases Casting Speed and Reaction Ability.',
        effectZh: '極大增強施法速度和反應能力。',
    },
    {
        pic: 'Home/core/31.jpg',
        nameEn: 'Squama Manis',
        nameZh: '穿山甲片',
        sourceEn: 'Scales of the Pangolin.',
        sourceZh: '穿山甲的鱗甲。',
        propertyEn: 'Penetration, Concealment, Unpredictability.',
        propertyZh: '穿透，隱藏，難以預料。',
        effectEn: 'Specialized in Penetrative and Concealed Offensive Spells.',
        effectZh: '專精於穿透性、隱藏性的攻擊咒語。',
    },
    {
        pic: 'Home/core/32.jpg',
        nameEn: 'Hippocampus',
        nameZh: '海馬',
        sourceEn: 'Dried body of Syngnathidae animals.',
        sourceZh: '海龍科動物的乾燥屍體。',
        propertyEn: 'Oceanic Riding, Confidence.',
        propertyZh: '海洋的騎行，自信。',
        effectEn: "Suitable for Water/Tidal Magic, Fortifies Caster's Confidence.",
        effectZh: '適合水系或潮汐魔法，強化施法者的自信。',
    },
    {
        pic: 'Home/core/33.jpg',
        nameEn: 'Radix Polygoni Multiflori',
        nameZh: '何首烏',
        sourceEn: 'Root of Polygonaceae plants.',
        sourceZh: '蓼科植物的根。',
        propertyEn: 'Transfiguration, Longevity, Disguise.',
        propertyZh: '轉變，長壽，易容。',
        effectEn: 'Specialized in Transfiguration, Illusion, and Longevity Potions.',
        effectZh: '專精於易容換貌、幻術和增長壽命的魔藥學。',
    }
]);

//魔杖杖心索引跑亂數

//const coreSelect = ref(27);   //確定可以抓到資料
//讓杖心的索引值不要寫死
//const coreSelect = computed(()=>{});   //不用computed，因為不用根據其他響應式數據進行計算/執行動作

// Math.random()  
// 產生 [0, 1) 之間的數，包含 0，但「不包含」1 
//const coreSelect = ref(Math.random() * 34);  // 產生 0 ~ 34 的亂數，但不包含34

// 把亂數變成整數
//const coreSelect = ref(Math.trunc(Math.random() * 34));  // 把小數直接截掉

//所以如果杖心的數量會變動，改成用陣列長度來取值，不寫死
//因為要能讀到 wandCores ，所以需要把宣告的位置放到 wandCores 後
//因為 wandCores 為陣列，用 ref 時，要用 .value 來讀

const coreSelect = ref(Math.trunc(Math.random() * wandCores.value.length));   


</script>
<template>

    <main class="coregame-dock dp-flex-col">
        <h5>Power Forged!</h5>
        <div class="coregame-top dp-flex">
            <div>
                <div class="core-pic dp-flex">
                    <img :src="wandCores[coreSelect].pic" alt="">
                    <!-- <img :src="{{wandCores[coreSelect].pic}}" alt="">    因為是網址，不能寫{{ }}  -->
                    <!-- <img src="../../../public/Home/game/Cornu Cervi Pantotrichum.png" alt=""> -->
                </div>
            </div>
            <div class="core-text">

                <!-- <h6>{{wandCores.nameEn}}</h6>   因為wandCores是陣列，所以這樣無法直接取值，要加入索引 -->
                <h6>{{wandCores[coreSelect].nameEn}}</h6>
                <p>Source ：{{wandCores[coreSelect].sourceEn}}</p>
                <p>Property ：{{wandCores[coreSelect].propertyEn}}</p>
                <p>Effect：{{wandCores[coreSelect].effectEn}}</p>
                
                <!-- <h6>Cornu Cervi Pantotrichum</h6>
                <p>Source ：Young antler of Sika or Red Deer.</p>
                <p>Property ：Extreme Vitality, Rapid Regeneration.</p>
                <p>Effect：Anchors the Mind, Resists Mental Attack, Suited for Deep Concentration.</p> -->
                
            </div>
        </div>

        <div class="coregame-down">
            <div class="coregame-record">

                <p>Your Formosoul bond is sealed. <br>
                    The core is already etched onto your Student Card.
                    Finalize enrollment now, before the energy dissipates.
                </p>

                <div class="btn-step dp-flex">
                    <BasicButton class="btn-gray-fill" @click="goToSensing">Restart the Sensing Ritual</BasicButton>
                    <BasicButton class="btn-yellow-fill" @click="goToEnroll">Enroll ＆ Claim Student Card!</BasicButton>
                </div>

            </div>
        </div>
    </main>
    

</template>

<style scoped lang="scss">

    .coregame-dock{  
        gap: 40px;
        & h5{
            text-align: center;
        }
    }



//上方區塊------------------------------------
    .coregame-top{
        justify-content: center;
        align-items: center;
        gap: 60px;
        padding: 50px 60px;
        box-shadow: inset 0 0 10px rgba(0,0,0, 0.3);
        border-radius: 10px;
    }

    .coregame-top img{
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center left;
        filter: drop-shadow(0 0 15px $color-fsWhite);   //因為要沿著圖加陰影，不能用box-shadow
    }

    .core-pic{
        height: 150px;
        width: 150px;
        & img{
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
    }
    
    .core-text{
        & h6{
            margin: 0 auto 20px;
        }
        & p{
            font-weight: 200;
        }
    }


    // 下方區塊------------------------------------

    .coregame-record{
        & P{
            font-size: 1.2rem;
            animation: 
            float-swing 10s linear infinite;
        }
    }

    @keyframes float-swing {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        20%, 60% {
            transform: translateY(-3px) rotate(-0.5deg);
        }
        40%, 80% {
            transform: translateY(-3px) rotate(0.5deg);
        }
    }


    //無法重玩
    // .btn-yellow-fill{
    //     display: block;
    //     margin: 20px auto 0;
    // }

    //可重玩
    .btn-step{
        justify-content: center;
        gap: 40px;
        margin: 20px auto 0;
    }



</style>
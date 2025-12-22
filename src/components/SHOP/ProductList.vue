<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useRouter } from "vue-router";     //使用路由功能
import TestProductCard from '@/components/TestProductCard.vue';
import { ref } from 'vue';

// 宣告常數來接收 useRouter() ，方便後續使用

// 設定路由功能




// 商品  ＊傳送給子組件
const products = ref([
  {
    product_ID: 'FO2025110001',
    type: 'Folktoys',
    price: '80',
    stock: '25',
    status: 'Listed',
    createdate: '20251101',
    update: '20251115',
    name_en: 'Spinning Top',
    name_zh: '陀螺',
    description_en: 'A wooden conical toy that spins when thrown with a wound-up string.',
    description_zh: '木製錐形玩具，繞上細繩後甩出旋轉。',
    story_en: 'A famous craft from Daxi, Taiwan; it symbolizes the power of stability and grounding.',
    story_zh: '台灣大溪等地著名的工藝，象徵重心的穩定。',
    use_en: 'Wrap the string, hurl it toward the ground while pulling back to initiate a steady spin.',
    use_zh: '纏繞繩子後向地甩出並收回繩索，使其平穩旋轉。',
    main_pic: 'Shop/1.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110002',
    type: 'Folktoys',
    price: '50',
    stock: '30',
    status: 'Listed',
    createdate: '20251101',
    update: '20251205',
    name_en: 'Bamboo Helicopter',
    name_zh: '竹蜻蜓',
    description_en: 'Bamboo blades and a central axis that take flight with a simple rub of the fingers.',
    description_zh: '竹製葉片與旋轉軸，利用手指搓動即可飛翔。',
    story_en: 'Originating in antiquity, it symbolizes the fusion of handmade ingenuity and physical laws.',
    story_zh: '源於古代，象徵手作巧思與物理學的結合。',
    use_en: 'Rub the rod rapidly between your palms and release to let it spiral upward.',
    use_zh: '雙手搓動桿子並迅速放開，使其向上旋轉飛行。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110003',
    type: 'Folktoys',
    price: '120',
    stock: '15',
    status: 'Listed',
    createdate: '20251101',
    update: '20251122',
    name_en: 'Pellet Drum',
    name_zh: '波浪鼓',
    description_en: 'A double-sided drum with beads on strings that strike the drumheads when rotated.',
    description_zh: '兩側繫有小球的雙面小鼓，轉動手把發聲。',
    story_en: 'The hallmark of early street peddlers; its sound is believed to ward off evil and bring luck.',
    story_zh: '早期走街貨郎的標誌，聲響具備驅邪祈福之意。',
    use_en: 'Twist the handle rapidly back and forth so the pellets strike the drum to produce a rhythmic beat.',
    use_zh: '快速來回轉動手柄，使小球擊打鼓面發出咚咚聲。',
    main_pic: 'Shop/3.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110004',
    type: 'Folktoys',
    price: '180',
    stock: '20',
    status: 'Listed',
    createdate: '20251101',
    update: '20251218',
    name_en: 'Slingshot',
    name_zh: '彈弓',
    description_en: 'A Y-shaped wooden fork with rubber bands for launching pebbles or marbles.',
    description_zh: 'Y形木叉配上橡皮筋，發射小石子或彈珠。',
    story_en: "An essential tool for mountain life, used to train a hunter's sharp intuition.",
    story_zh: '山林生活的必備工具，訓練獵人的敏銳直覺。',
    use_en: 'Hold the fork in one hand, pull the band with the other, aim, and release.',
    use_zh: '一手握叉、一手拉動橡皮筋，對準目標後放開。',
    main_pic: 'Shop/4.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110005',
    type: 'Folktoys',
    price: '40',
    stock: '50',
    status: 'Listed',
    createdate: '20251101',
    update: '20251108',
    name_en: 'Shuttlecock',
    name_zh: '毽子',
    description_en: 'Crafted from feathers and rubber discs, designed to be struck by the feet.',
    description_zh: '羽毛與橡膠墊片製成，利用腳部擊打。',
    story_en: 'Rooted in ancient martial arts training, a symbol of vitality and physical prowess.',
    story_zh: '源於古代練武，充滿活力與強健體魄的象徵。',
    use_en: 'Keep the shuttlecock airborne by repeatedly striking it with your inner foot or knees.',
    use_zh: '使用腳內側或膝蓋持續擊打毽子使其不落地。',
    main_pic: 'Shop/5.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110006',
    type: 'Folktoys',
    price: '90',
    stock: '12',
    status: 'Listed',
    createdate: '20251101',
    update: '20251212',
    name_en: 'Dough Figurine',
    name_zh: '捏麵人',
    description_en: 'Vibrant glutinous rice dough sculpted into intricate figures of people or animals.',
    description_zh: '色彩鮮豔的糯米團，捏塑成人物或動物。',
    story_en: 'A renowned temple fair craft that blends artistic expression with edibility.',
    story_zh: '廟會著名的民俗工藝，將藝術與可食性結合。',
    use_en: 'Knead, pinch, and carve different colors of dough into your desired shapes.',
    use_zh: '揉、捏、掐、刻，將不同顏色的麵糰塑造成型。',
    main_pic: 'Shop/6.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110007',
    type: 'Folktoys',
    price: '20',
    stock: '100',
    status: 'Listed',
    createdate: '20251101',
    update: '20251130',
    name_en: 'Battle Cards',
    name_zh: '尪仔標',
    description_en: 'Circular notched paper cards printed with images of heroes or deities.',
    description_zh: '圓形帶齒邊的厚紙卡，印有英雄或神祇。',
    story_en: "Known as early 'social currency' for children, carrying the weight of folk legends.",
    story_zh: '早期孩童的「社交貨幣」，承載民間傳說故事。',
    use_en: 'Flick your card against your opponent\'s to flip it over or knock it out of bounds.',
    use_zh: '用自己的卡片彈擊對方的卡片，試圖翻轉或擊出場外。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110008',
    type: 'Folktoys',
    price: '150',
    stock: '18',
    status: 'Listed',
    createdate: '20251101',
    update: '20251202',
    name_en: 'Kendama',
    name_zh: '劍玉',
    description_en: 'A balance toy consisting of a cross-shaped wooden handle and a tethered red ball.',
    description_zh: '十字形木柄與繫繩紅球組成的平衡玩具。',
    story_en: "The ultimate test of hand-eye coordination, used to train a wizard's focus.",
    story_zh: '考驗手眼協調的極致，訓練魔法師的專注力。',
    use_en: 'Use a wrist flick to toss the ball and catch it precisely in the wooden cups or spike.',
    use_zh: '利用手腕巧勁將紅球甩起，使其精準落在木柄凹槽。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110009',
    type: 'Folktoys',
    price: '100',
    stock: '22',
    status: 'Listed',
    createdate: '20251101',
    update: '20251111',
    name_en: 'Clapper',
    name_zh: '響板',
    description_en: 'Two bamboo or wooden boards that produce a crisp rhythm when struck together.',
    description_zh: '兩片竹板或木板，擊打產生清脆節奏。',
    story_en: 'The rhythmic soul of traditional opera and storytelling, symbolizing harmony.',
    story_zh: '傳統戲曲與唸歌的節奏靈魂，象徵韻律與和諧。',
    use_en: 'Hold in both hands to strike alternately or shake rapidly with one hand for rhythm.',
    use_zh: '雙手持板交替擊打或單手快速搖動，產生節奏感。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110010',
    type: 'Folktoys',
    price: '200',
    stock: '10',
    status: 'Listed',
    createdate: '20251101',
    update: '20251221',
    name_en: 'Rubber Band Gun',
    name_zh: '橡皮筋槍',
    description_en: 'A wooden pistol model that utilizes elasticity to launch rubber bands.',
    description_zh: '木製手槍模型，利用彈性發射橡皮筋。',
    story_en: 'A classic memory of Taiwanese craft classes, symbolizing precision and ranged projection.',
    story_zh: '台灣工藝課的經典回憶，象徵精準與遠程投射。',
    use_en: 'Hook the band to the muzzle and trigger, then pull the trigger to fire at your target.',
    use_zh: '將橡皮筋勾在前端與扳機，扣下扳機將其射向目標。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110011',
    type: 'Folktoys',
    price: '250',
    stock: '8',
    status: 'Listed',
    createdate: '20251101',
    update: '20251125',
    name_en: 'Stilts',
    name_zh: '踩高蹺',
    description_en: 'Long wooden poles strapped to the feet to increase the walker\'s height.',
    description_zh: '綁在腳上的長木桿，增加行走高度。',
    story_en: 'Originating from field patrols or temple performances, symbolizing elevated vision.',
    story_zh: '源於農業社會巡田或廟會表演，象徵視野的提升。',
    use_en: 'Step onto the footrests and hold the tops of the poles to walk while maintaining balance.',
    use_zh: '雙腳踩在木板支撐點，手握桿頂維持平衡行走。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110012',
    type: 'Folktoys',
    price: '120',
    stock: '15',
    status: 'Listed',
    createdate: '20251101',
    update: '20251214',
    name_en: 'Kite',
    name_zh: '風箏',
    description_en: 'A frame covered in paper or cloth designed to rise into the air via wind power.',
    description_zh: '骨架蒙上紙或布，利用風力升空的裝置。',
    story_en: 'A symbol of freedom and a medium for sending wishes to the heavens.',
    story_zh: '象徵自由與將願望傳達給上蒼的媒介。',
    use_en: 'Run against the wind while slowly releasing the line to let the airflow lift it high.',
    use_zh: '逆風奔跑並逐漸放線，利用氣流將其升至高空。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110013',
    type: 'Folktoys',
    price: '70',
    stock: '35',
    status: 'Listed',
    createdate: '20251101',
    update: '20251104',
    name_en: 'Flying Disc',
    name_zh: '飛盤',
    description_en: 'A circular disc that generates lift through rotation to glide through the air.',
    description_zh: '圓型盤狀物，利用旋轉產生升力飛行。',
    story_en: 'A popular modern sport, symbolizing dynamic capture and spatial awareness.',
    story_zh: '現代公園常見運動，象徵動態捕捉與空間感知。',
    use_en: 'Flick your wrist outward to spin the disc, allowing it to glide smoothly.',
    use_zh: '手腕向外甩出並帶動盤身旋轉，使其滑翔。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110014',
    type: 'Folktoys',
    price: '30',
    stock: '60',
    status: 'Listed',
    createdate: '20251101',
    update: '20251209',
    name_en: 'Paper Pinwheel',
    name_zh: '紙風車',
    description_en: 'Paper blades that spin rapidly when caught by the wind.',
    description_zh: '紙製葉片，遇風會快速轉動。',
    story_en: 'Symbolizes turning luck and eternal life; often used in blessing rituals.',
    story_zh: '象徵好運轉動、生生不息，常用於祭典祈福。',
    use_en: 'Hold it while running or face the wind to let the blades catch the breeze and spin.',
    use_zh: '手持奔跑或對著風向，讓葉片受力旋轉。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110015',
    type: 'Folktoys',
    price: '110',
    stock: '20',
    status: 'Listed',
    createdate: '20251101',
    update: '20251119',
    name_en: 'Rolling Iron Hoop',
    name_zh: '滾鐵圈',
    description_en: 'An iron ring pushed by a hook to keep it rolling along the ground.',
    description_zh: '用鐵鉤推動鐵環，使其在地上滾動。',
    story_en: 'An endurance race for rural children, testing balance and stamina.',
    story_zh: '早期鄉村孩童的耐力比賽，考驗平衡與續航。',
    use_en: 'Use the hook to push the base of the hoop, running to maintain its momentum.',
    use_zh: '利用鐵鉤推動環身底部，一邊奔跑一邊維持轉動。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'FO2025110016',
    type: 'Folktoys',
    price: '25',
    stock: '80',
    status: 'Listed',
    createdate: '20251101',
    update: '20251220',
    name_en: 'Beanbag',
    name_zh: '沙包',
    description_en: 'Small cloth bags filled with red beans or sand.',
    description_zh: '裝滿紅豆或沙子的小布包。',
    story_en: 'A test of finger dexterity and a collective memory for many generations of girls.',
    story_zh: '考驗手指靈活度，多為早期女孩子的集體回憶。',
    use_en: 'Toss one bag upward and try to scoop up others from the ground before it falls.',
    use_zh: '向上拋起一包，在落下前抓取地上的其餘沙包。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'PE2025100001',
    type: 'Personalized',
    price: '500',
    stock: '30',
    status: 'Listed',
    createdate: '20251001',
    update: '20251108',
    name_en: 'Seal',
    name_zh: '印章',
    description_en: 'A hand-carved name seal.',
    description_zh: '手工雕刻的姓名章。',
    story_en: 'Represents the formal power of contracts, identity, and laws.',
    story_zh: '代表契約、身分與法規的正式力量。',
    use_en: 'Dip in cinnabar ink and press onto documents or codices.',
    use_zh: '蘸上硃砂印泥，在文件或法典上用印。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'PE2025100002',
    type: 'Personalized',
    price: '100',
    stock: '200',
    status: 'Listed',
    createdate: '20251001',
    update: '20251212',
    name_en: 'Peace Charm',
    name_zh: '平安符',
    description_en: 'A basic protective amulet to ensure a smooth life.',
    description_zh: '基礎護身符，保佑生活順遂。',
    story_en: 'Taiwan\'s most common faith; an invisible shield from the gods.',
    story_zh: '台灣最普遍的信仰，象徵神明的隱形盾牌。',
    use_en: 'Carry it with you or keep it in your daily bag.',
    use_zh: '隨身攜帶或放在隨身包內。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'PE2025100003',
    type: 'Personalized',
    price: '150',
    stock: '100',
    status: 'Listed',
    createdate: '20251001',
    update: '20251125',
    name_en: 'Safety Charm',
    name_zh: '安全平安符',
    description_en: 'Enhances physical defense, specifically against accidents.',
    description_zh: '強化物理防禦，特別針對意外。',
    story_en: 'Often hung on vehicles to pray for safe travels.',
    story_zh: '多掛於交通工具上，祈求行路平安。',
    use_en: 'Hang it on your broomstick or vehicle\'s front.',
    use_zh: '掛於魔法掃帚或代步工具前端。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'PE2025100004',
    type: 'Personalized',
    price: '200',
    stock: '80',
    status: 'Listed',
    createdate: '20251001',
    update: '20251205',
    name_en: 'Lucky Charm',
    name_zh: '幸運平安符',
    description_en: 'Increases blessings and attracts positive energy.',
    description_zh: '增加福報，吸引正向能量。',
    story_en: 'Used to improve one\'s aura and attract helpful mentors.',
    story_zh: '用於改善磁場，讓使用者常遇貴人。',
    use_en: 'Place in a wealth corner or wear it daily.',
    use_zh: '放置於財位或日常佩戴。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'PE2025100005',
    type: 'Personalized',
    price: '150',
    stock: '120',
    status: 'Listed',
    createdate: '20251001',
    update: '20251119',
    name_en: 'Study Charm',
    name_zh: '學業平安符',
    description_en: 'Boosts wisdom and exam luck with Wenchang\'s blessing.',
    description_zh: '提升智慧與考運，文昌加持。',
    story_en: 'A seasonal bestseller; a specific \'buff\' for academy tests.',
    story_zh: '考季熱賣品，專為學院測驗設計的 Buff。',
    use_en: 'Place on the top-left of your desk while studying or testing.',
    use_zh: '閱讀或考試時放在書桌左上角。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'PE2025100006',
    type: 'Personalized',
    price: '180',
    stock: '90',
    status: 'Listed',
    createdate: '20251001',
    update: '20251218',
    name_en: 'Health Charm',
    name_zh: '健康平安符',
    description_en: 'Strengthens the aura to prevent evil spirits and illness.',
    description_zh: '增強氣場，預防病邪入侵。',
    story_en: 'Blessed by Baosheng Dadi; ideal for frail wizards.',
    story_zh: '保生大帝加持，適合體弱的巫師。',
    use_en: 'Wear it on your chest, close to your heartbeat.',
    use_zh: '佩戴於胸前，貼近心跳處。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'PE2025100007',
    type: 'Personalized',
    price: '300',
    stock: '40',
    status: 'Listed',
    createdate: '20251001',
    update: '20251130',
    name_en: 'Birth Charm',
    name_zh: '順產平安符',
    description_en: 'Guards mother and child for a smooth delivery.',
    description_zh: '守護母子，保佑生產順利。',
    story_en: 'Symbolizes life\'s continuation and Zhusheng Niangniang\'s mercy.',
    story_zh: '象徵生命的傳承與註生娘娘的慈悲。',
    use_en: 'Place by the bedside or carry it for protection.',
    use_zh: '放置於床頭 or 隨身攜帶以求守護。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'PE2025100008',
    type: 'Personalized',
    price: '60',
    stock: '300',
    status: 'Listed',
    createdate: '20251001',
    update: '20251221',
    name_en: 'Yellow Spell',
    name_zh: '符咒 (黃)',
    description_en: 'Banishes evil and purifies the environment.',
    description_zh: '鎮煞、驅邪，淨化環境。',
    story_en: 'Basic magic used to clear shadowy miasma from the air.',
    story_zh: '基礎魔法，用於去除空氣中的暗影晦氣。',
    use_en: 'Burn and mix with water for sprinkling, or paste on doors.',
    use_zh: '焚化於水中淨灑或貼於門窗。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'PE2025100009',
    type: 'Personalized',
    price: '80',
    stock: '150',
    status: 'Listed',
    createdate: '20251001',
    update: '20251114',
    name_en: 'Green Spell',
    name_zh: '符咒 (綠)',
    description_en: 'Suppresses, balances, and prevents clashing energies.',
    description_zh: '鎮壓、平衡、防沖。',
    story_en: 'Handles attribute conflicts and stabilizes berserk energy fields.',
    story_zh: '專門處理屬性衝突，穩定狂暴的能量場。',
    use_en: 'Place within ritual circles experiencing unstable fluctuations.',
    use_zh: '放置於能量波動不穩的儀式陣中。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'PE2025100010',
    type: 'Personalized',
    price: '80',
    stock: '150',
    status: 'Listed',
    createdate: '20251001',
    update: '20251203',
    name_en: 'Black Spell',
    name_zh: '符咒 (黑)',
    description_en: 'Guards against fire and ensures kitchen safety.',
    description_zh: '鎮火災、保廚房平安。',
    story_en: 'Black corresponds to water; masters fire through water.',
    story_zh: '黑色屬水，以水克火，是家庭巫師必備。',
    use_en: 'Paste on walls near the kitchen or heat sources.',
    use_zh: '貼於廚房或火源附近的牆面上。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'PE2025100011',
    type: 'Personalized',
    price: '120',
    stock: '60',
    status: 'Listed',
    createdate: '20251001',
    update: '20251121',
    name_en: 'White Spell',
    name_zh: '符咒 (白)',
    description_en: 'Resolves legal issues; represents sanctity and purity.',
    description_zh: '官司解套，代表聖潔與純淨。',
    story_en: 'Handles disputes; symbolizes the manifestation of justice.',
    story_zh: '處理紛爭，象徵正義與真相的顯現。',
    use_en: 'Carry it during mediations or courtroom debates.',
    use_zh: '在進行調解或法庭辯論時攜帶。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'PE2025100012',
    type: 'Personalized',
    price: '50',
    stock: '500',
    status: 'Listed',
    createdate: '20251001',
    update: '20251210',
    name_en: 'Red Spell',
    name_zh: '符咒 (紅)',
    description_en: 'Represents festivity and attracts wealth.',
    description_zh: '代表喜氣、招財。',
    story_en: 'Similar to Spring Couplets; used to boost luck during festivals.',
    story_zh: '類似春聯，用於節慶增強幸運魔法。',
    use_en: 'Paste at entrances during festivals or put in red envelopes.',
    use_zh: '節慶時貼於門口或放入紅包中。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'AC2025090001',
    type: 'Accessories',
    price: '250',
    stock: '150',
    status: 'Listed',
    createdate: '20250901',
    update: '20251114',
    name_en: 'House Enamel Pin',
    name_zh: '學院金屬徽章',
    description_en: 'Exquisite enamel pins engraved with divine beasts such as the Taiwan Blue Magpie, Formosan Black Bear, or Clouded Leopard.',
    description_zh: '精緻的琺瑯金屬針章，刻有台灣藍鵲、黑熊或雲豹等神獸。',
    story_en: 'Represents the honor of the student\'s house. Different divine beast totems symbolize unique personality traits and magical talents.',
    story_zh: '代表學員所屬的學院榮譽。不同的神獸圖騰象徵不同的性格特質與魔法天賦。',
    use_en: 'Wear it on the collar of your wizard robe or backpack. During specific house events, it glows faintly when sensing the presence of fellow housemates.',
    use_zh: '佩戴於巫師袍領口或背包上。在特定活動中，會因感應同儕氣息而發出微光。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'AC2025090002',
    type: 'Accessories',
    price: '450',
    stock: '45',
    status: 'Listed',
    createdate: '20250901',
    update: '20251203',
    name_en: 'Lightning-Struck Wood Sword Pendant',
    name_zh: '雷劈木迷你劍吊飾',
    description_en: 'A miniature sword carved from peach wood struck by celestial lightning, featuring authentic scorch marks.',
    description_zh: '由被天雷劈中的桃木削製而成的微型寶劍，帶有焦灼痕跡。',
    story_en: 'In Taiwanese folklore, lightning-struck wood is a powerful exorcism material. This sword can automatically detect surrounding negative energy.',
    story_zh: '台灣民間信仰中，雷劈木被視為具極強驅魔能力的材料。能自動偵測周遭負面能量。',
    use_en: 'Attach to your phone or keys. The wooden sword will grow warm to alert the wearer when negative aura or evil spirits are nearby.',
    use_zh: '掛在手機或鑰匙上。當附近有晦氣或邪靈接近時，木劍會微微發熱以提醒佩戴者。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'AC2025090003',
    type: 'Accessories',
    price: '600',
    stock: '25',
    status: 'Listed',
    createdate: '20250901',
    update: '20251120',
    name_en: 'Dragonfly Glass Bead Necklace',
    name_zh: '蜻蜓珠魔法項鍊',
    description_en: 'A necklace featuring colorful glass beads with unique patterns, each possessing its own distinct texture.',
    description_zh: '鑲嵌不同圖案的彩色琉璃珠項鍊，每顆珠子都有獨特紋路。',
    story_en: 'Originating from Paiwan craftsmanship. Each bead holds an ancient soul; for example, Peacock beads represent love, while Warrior beads represent courage.',
    story_zh: '源自排灣族工藝。每一顆珠子都擁有古老靈魂，如孔雀珠代表愛情，勇士珠代表勇氣。',
    use_en: 'Wear around the neck. Touch the corresponding bead while casting spells to enhance the success rate of that specific magical attribute.',
    use_zh: '佩戴於頸部。施法時觸摸對應意義的珠子，可增強該屬性魔法的成功率。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'AC2025090004',
    type: 'Accessories',
    price: '320',
    stock: '80',
    status: 'Listed',
    createdate: '20250901',
    update: '20251210',
    name_en: 'Tiger God Embroidered Keychain',
    name_zh: '虎爺繡花鑰匙圈',
    description_en: 'A hand-embroidered yellow tiger head, blending a plump, cute shape with a sense of divine majesty.',
    description_zh: '手工刺繡的黃色虎頭，圓滾滾造型中帶有神聖威嚴感。',
    story_en: 'Lord Tiger is a guardian deity in Taiwanese temples, protecting children and travelers. This item is blessed to \'bite wealth\' and avoid danger.',
    story_zh: '虎爺是台灣廟宇守護神，保護孩童與旅人。此飾品具備「咬錢」與「避險」的加持。',
    use_en: 'Hang on keys or bags. Pat the tiger\'s head three times when entering dangerous areas to gain temporary light-footedness and luck protection.',
    use_zh: '掛在鑰匙或背包。進出危險區域時輕拍虎頭三次，能獲得短暫的腳步輕盈與保護。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'AC2025090005',
    type: 'Accessories',
    price: '550',
    stock: '60',
    status: 'Listed',
    createdate: '20250901',
    update: '20251129',
    name_en: 'Five Elements Balance Pin',
    name_zh: '五行能量胸章',
    description_en: 'A circular base inlaid with five-colored crystals representing Metal, Wood, Water, Fire, and Earth.',
    description_zh: '圓形底座上鑲嵌金、木、水、火、土五色晶石。',
    story_en: 'Combines Eastern Five Elements principles to ensure stable mana flow. Crucial in locations where elemental energy is chaotic.',
    story_zh: '結合東方五行原理，確保巫師體內魔力流動穩定。在元素混亂的地點特別重要。',
    use_en: 'Pin to the chest of your robe. When feeling magical imbalance, rotate the center clockwise to help harmonize your internal energy.',
    use_zh: '固定於外袍胸前。感到魔力失衡時，順時針旋轉胸章中心，可幫助理順體內氣息。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'VO2025110001',
    type: 'Vouchers',
    price: '1200',
    stock: '50',
    status: 'Listed',
    createdate: '20251101',
    update: '20251112',
    name_en: 'Yanshui Firework Brave\'s Voucher',
    name_zh: '鹽水蜂炮勇者體驗券',
    description_en: 'Includes a full set of fire-resistant wizard\'s raiment, a safety helmet, and eligibility to enter the firework display area.',
    description_zh: '包含全套防火巫師服、安全帽，以及進場觀賞蜂炮的資格。',
    story_en: 'The Yanshui Beehive Fireworks is Taiwan\'s most powerful exorcism rite. Thousands of fireworks symbolize the sweeping away of plagues; for wizards, it is the ultimate trial of courage.',
    story_zh: '鹽水蜂炮是台灣最具震撼力的驅邪儀式。萬炮齊發象徵掃除瘟疫，對巫師而言是最高等級的膽量試煉。',
    use_en: 'Redeem your gear at the Tainan Yanshui Office and suit up before entering the array. Maintaining your composure amidst the barrage will significantly boost your defense.',
    use_zh: '在台南鹽水辦事處兌換裝備後，穿戴整齊進入炮陣。在炮火中保持冷靜能大幅提升防禦力。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'VO2025110002',
    type: 'Vouchers',
    price: '350',
    stock: '100',
    status: 'Listed',
    createdate: '20251101',
    update: '20251205',
    name_en: 'Pingxi Sky Lantern Blessing Voucher',
    name_zh: '平溪天燈祈福券',
    description_en: 'Redeemable for one handmade extra-large sky lantern and a cinnabar brush for writing magical vows.',
    description_zh: '兌換一盞手作特大天燈，含硃砂筆一枝，用於書寫魔法誓詞。',
    story_en: 'Sky lanterns serve as messengers to the celestial realm. In the world of Formosoul, these lanterns are used to send graduation wishes to the stars.',
    story_zh: '天燈是通往天界與神靈溝通的信使。在Formosoul，天燈用於將畢業願望送往星辰。',
    use_en: 'Redeem at designated shops near Pingxi Station. Write down your wish and ignite the lantern under a professor\'s guidance. Observe its flight path to divine your fortune.',
    use_zh: '在平溪車站指定店鋪兌換，寫下願望後在教授指導下點火釋放。觀察上升路徑可預知運勢。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'VO2025110003',
    type: 'Vouchers',
    price: '800',
    stock: '30',
    status: 'Listed',
    createdate: '20251101',
    update: '20251128',
    name_en: 'Temple Banquet Culture Meal Voucher',
    name_zh: '廟口辦桌文化餐券',
    description_en: 'Grants access to the \'Liushui-xi\' (traditional roadside banquet) to taste local cuisine charged with spiritual energy.',
    description_zh: '可參與廟會盛宴「流水席」，品嚐最具靈力的在地料理。',
    story_en: '\'Bando\' (banqueting) is the essence of Taiwanese magical socializing. Feasting under the witness of gods allows a wizard to restore vast amounts of mana and spirit.',
    story_zh: '辦桌是台灣魔法社交的精髓。在眾神見證下的饗宴，能讓巫師恢復大量的魔力與精神力。',
    use_en: 'Proceed to the active temple square and present your voucher to the \'Zong-pu-shi\' (Head Chef). After the meal, you may pray to nearby deities for inspiration.',
    use_zh: '前往活動廟口，憑券向總舖師兌換席位。用餐後可向附近的神明祈求靈感。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'VO2025110004',
    type: 'Vouchers',
    price: '1500',
    stock: '20',
    status: 'Listed',
    createdate: '20251101',
    update: '20251215',
    name_en: 'Mazu Pilgrimage Lodging Voucher',
    name_zh: '媽祖隨香住宿券',
    description_en: 'Redeemable for a serene room in specific \'Pilgrim Buildings,\' including a divine-blessed vegetarian breakfast.',
    description_zh: '兌換特定「香客大樓」的靜謐房型，含神力加持的素食早餐。',
    story_en: 'Following the Mazu Pilgrimage is the longest magical trek in Taiwan. These buildings provide deep physical and mental recovery after the long journey.',
    story_zh: '跟隨大甲媽祖進香是全台灣最長的魔法長征。香客大樓能提供在長途跋涉後的深度身心修復。',
    use_en: 'Present this voucher at designated temples along the pilgrimage route. Participate in the morning drum ceremony after check-in to receive spiritual purification.',
    use_zh: '在進香沿線的指定廟宇出示此券。入住後於晨間參與廟宇擊鼓儀式，可獲得身心淨化。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  },
  {
    product_ID: 'VO2025110005',
    type: 'Vouchers',
    price: '150',
    stock: '200',
    status: 'Listed',
    createdate: '20251101',
    update: '20251221',
    name_en: 'Mystic Forest Shuttle Pass',
    name_zh: '魔法林道接駁車券',
    description_en: 'Access to the academy\'s exclusive shuttle to high-spiritual energy locations such as Alishan or Taroko.',
    description_zh: '可搭乘學院專屬接駁車前往阿里山或太魯閣等高靈氣地點。',
    story_en: 'Taiwan\'s high mountains and canyons hide ancient forest spirits. This pass is designed for students who are not yet proficient in long-range flight.',
    story_zh: '台灣的高山峽谷藏有古老的森林精靈。此車票專為不擅長長距離飛行的學員設計。',
    use_en: 'Present the pass at major transit hubs and board the shuttle marked with the academy\'s totem. The vehicle is enchanted with anti-motion-sickness magic for a comfortable journey.',
    use_zh: '在各縣市交通轉運站出示，登上漆有學院圖騰的接駁車。車內配有抗暈魔法，確保旅途舒適。',
    main_pic: 'Shop/2.png',
    pic1: 'Shop/2-1.png',
    pic2: 'Shop/2-2.png',
    pic3: 'Shop/2-3.png',
    pic4: 'Shop/2-4.png',
    isLike: false
  }
]);

</script>

<template>
    <!-- 麵包屑 -->
    <h6 class="page-guide">
        All Product
        <font-awesome-icon icon="fa-solid fa-angle-right" />
        New Arrivals
    </h6>


    <!-- 上方區塊 -->
    <section class="nav-top dp-flex">
        <div class="search">
            <form class="search-content dp-flex" action="" method="GET">
                <input type="text" class="search-text" placeholder="Search">
                <button type="submit" class="btn-search-submit">
                    <font-awesome-icon icon="fa-solid fa-magnifying-glass" />
                </button>
            </form>
        </div> 

        <nav class="nav-top-right">
            <font-awesome-icon class="nav-icon" icon="fa-solid fa-angle-down" /> 
            <select class="nav-list fw200">
                <option class="list-option" selested>New Arrivals</option>
                <option class="list-option">Price：Low to High</option>
                <option class="list-option">Price：High to Low</option>
            </select>
        </nav>
    </section> 

    <section>
        <ul class="list-category dp-flex">
            <li class="list-category-group">
                <div class="list-dock">
                    <div class="list-liquidGlass">
                        <font-awesome-icon class="list-faIcon" icon="fa-solid fa-gamepad" />
                    </div>
                </div>
                <p class="list-name">Folktoys</p>
            </li>

            <li class="list-category-group">
                <div class="list-dock">
                    <div class="list-liquidGlass">
                        <font-awesome-icon class="list-faIcon" icon="fa-solid fa-box-open" />
                    </div>
                </div>
                <p class="list-name">Personalized</p>
            </li>

            <li class="list-category-group">
                <div class="list-dock">
                    <div class="list-liquidGlass">
                        <font-awesome-icon class="list-faIcon" icon="fa-solid fa-ring" />
                    </div>
                </div>
                <p class="list-name">Accessories</p>
            </li>

            <li class="list-category-group">
                <div class="list-dock">
                    <div class="list-liquidGlass">
                    <font-awesome-icon class="list-faIcon" icon="fa-solid fa-ticket" />
                    </div>
                </div>
                <p class="list-name">Voucher</p>
            </li>
        </ul>
    </section>

<!-- 中間商品列表 -->
    <section class="list-all">
        <TestProductCard :products="products"/>  <!-- product 傳遞變數 products 的值，子組件接收時要用 product 來接收 -->
    </section>

<!-- 下方頁碼 -->
    <nav class="nav-down fw200">
        <font-awesome-icon class="list-icon" icon="fa-solid fa-angle-left" />
        <span class="list-page click">1</span>
        <span class="list-page">2</span>
        <span class="list-page">3</span>
        <font-awesome-icon class="list-icon" icon="fa-solid fa-angle-right" />
    </nav>


</template>

<style scoped lang="scss">

// 麵包屑
.page-guide{
  padding-bottom: 60px;
}


// 上方搜尋框＋選單--------------------------------

.nav-top{
  font-size: 16px;
  justify-content:space-between;
}


// <搜尋框>--------------------------------
.search{
    width: 320px;
    height: 40px;
    border-radius: 20px;
    padding: 0 16px;

    background-color: $color-fsWhite;
}

.search:focus-within{
    //裡面包的input倍focus時，外層可已產生focus的效果
    box-shadow: 0 0 25px rgba(255, 255, 255, 0.8) ;   
}

.search-content{
    justify-content: space-between;
    height: 100%;
}


.search-text{
    flex-basis: 0;      //因為 input-text 有預設寬度，所以要這樣寫，才能讓 flex-grow 接管寬度
    flex-grow: 1;
    font-size: 1.6rem;
    color: $color-fsTitle;
    font-family: 'Roboto', 'Noto Sans TC', sans-serif;

    //取消預設值
    border: none;  
    background-color: transparent;
}

.search-text:focus{
    outline: 0;    //取消預設值
}

.btn-search-submit{
    cursor: pointer;
    font-size: 2rem;
    color: $color-fsTitle;

    //取消預設值
    border: none;  
    background-color: transparent;
}


// <選單>--------------------------------
.nav-top-right{
    // 新增自訂義箭頭
    position: relative;
}

.nav-list{
    color: $color-fsWhite;
    background-color: transparent;
    font-size: 20px;
    font-family: 'Ubuntu','Roboto', 'Noto Sans TC', sans-serif;
    text-align: center;
    width: 320px;
    height: 40px;
    cursor: pointer;

    border: none;
    border-bottom: 0.5px solid $color-fsWhite;

    // 移除預設下拉箭頭
    appearance: none;

    // 讓文字在空白處置中
    padding-right: 28px;
}

.nav-list:focus {
  outline: none;
  border-bottom: 0.5px solid transparent;
  box-shadow: 0 0 5px rgba(255, 255, 255, 0.5) ;
  border-radius: 10px;
}

.nav-icon{
    position: absolute;
    right: 8px;
    top:0;
    bottom: 0;
    margin: auto 0;

    //箭頭會被穿透，不會擋住 select 下拉點擊
    pointer-events: none;
}



// 上方商品分類------------------------------------

.list-category{
  justify-content: center;
  padding: 60px 0;
} 

.list-category-group{
  margin: 40px;
  cursor: pointer;
}

.list-dock{
  width: 120px;
  height: 120px;
  border-radius: 50%;
  backdrop-filter: blur(5px);
  box-shadow: inset 0 0 120px rgba(255, 255, 255, 0.2);
}

.list-liquidGlass{
  width: 120px;
  height: 120px;
  border-radius: 50%;
  backdrop-filter: blur(15px);
  box-shadow: 0 0 8px rgba(255, 255, 255, 0.5) ;

  position: relative;
}

.list-category-group .list-faIcon{
  font-size: 48px;

  color: $color-fsWhite;
  
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.list-name{
  margin-top: 20px;
  text-align: center;
}

.list-category-group:hover .list-dock{
  transform: scale(1.1);
}



//中間商品列表--------------------------------

.list-all{
  max-width: 1200px;
  margin: 0 auto;
}


// 下方頁碼----------------------------------

.nav-down {
  padding: 120px 0 0;
  
  font-size: 16px;
  line-height: 160%;
  margin: 0 auto;
  text-align: center;
}

.list-page{
  border-radius: 3px;
  border: 1px solid $color-fsWhite;
  display: inline-block;
  width: 25.6px;
  margin: 10px;
  cursor: pointer;
}

.list-icon{
   cursor: pointer; 
}

.list-page.click, .list-page:hover{
  background-color: $color-fsWhite;
  color: $color-fsTitle;
}

</style>
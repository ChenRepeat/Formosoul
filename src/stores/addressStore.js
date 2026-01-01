import { defineStore } from 'pinia';


export const useAddressStore = defineStore('address', () => {

    // 建立地址清單

    const addrTaiwan = [
      {
        "name_zh": "基隆市",
        "name_en": "Keelung City",
        "districts": [
          { "name_zh": "仁愛區", "name_en": "Ren’ai Dist.", "zip": "200" },
          { "name_zh": "信義區", "name_en": "Xinyi Dist.", "zip": "201" },
          { "name_zh": "中正區", "name_en": "Zhongzheng Dist.", "zip": "202" },
          { "name_zh": "中山區", "name_en": "Zhongshan Dist.", "zip": "203" },
          { "name_zh": "安樂區", "name_en": "Anle Dist.", "zip": "204" },
          { "name_zh": "暖暖區", "name_en": "Nuannuan Dist.", "zip": "205" },
          { "name_zh": "七堵區", "name_en": "Qidu Dist.", "zip": "206" }
        ]
      },
      {
        "name_zh": "臺北市",
        "name_en": "Taipei City",
        "districts": [
          { "name_zh": "中正區", "name_en": "Zhongzheng Dist.", "zip": "100" },
          { "name_zh": "大同區", "name_en": "Datong Dist.", "zip": "103" },
          { "name_zh": "中山區", "name_en": "Zhongshan Dist.", "zip": "104" },
          { "name_zh": "松山區", "name_en": "Songshan Dist.", "zip": "105" },
          { "name_zh": "大安區", "name_en": "Da’an Dist.", "zip": "106" },
          { "name_zh": "萬華區", "name_en": "Wanhua Dist.", "zip": "108" },
          { "name_zh": "信義區", "name_en": "Xinyi Dist.", "zip": "110" },
          { "name_zh": "士林區", "name_en": "Shilin Dist.", "zip": "111" },
          { "name_zh": "北投區", "name_en": "Beitou Dist.", "zip": "112" },
          { "name_zh": "內湖區", "name_en": "Neihu Dist.", "zip": "114" },
          { "name_zh": "南港區", "name_en": "Nangang Dist.", "zip": "115" },
          { "name_zh": "文山區", "name_en": "Wenshan Dist.", "zip": "116" }
        ]
      },
      {
        "name_zh": "新北市",
        "name_en": "New Taipei City",
        "districts": [
          { "name_zh": "萬里區", "name_en": "Wanli Dist.", "zip": "207" },
          { "name_zh": "金山區", "name_en": "Jinshan Dist.", "zip": "208" },
          { "name_zh": "板橋區", "name_en": "Banqiao Dist.", "zip": "220" },
          { "name_zh": "汐止區", "name_en": "Xizhi Dist.", "zip": "221" },
          { "name_zh": "深坑區", "name_en": "Shenkeng Dist.", "zip": "222" },
          { "name_zh": "石碇區", "name_en": "Shiding Dist.", "zip": "223" },
          { "name_zh": "瑞芳區", "name_en": "Ruifang Dist.", "zip": "224" },
          { "name_zh": "平溪區", "name_en": "Pingxi Dist.", "zip": "226" },
          { "name_zh": "雙溪區", "name_en": "Shuangxi Dist.", "zip": "227" },
          { "name_zh": "貢寮區", "name_en": "Gongliao Dist.", "zip": "228" },
          { "name_zh": "新店區", "name_en": "Xindian Dist.", "zip": "231" },
          { "name_zh": "坪林區", "name_en": "Pinglin Dist.", "zip": "232" },
          { "name_zh": "烏來區", "name_en": "Wulai Dist.", "zip": "233" },
          { "name_zh": "永和區", "name_en": "Yonghe Dist.", "zip": "234" },
          { "name_zh": "中和區", "name_en": "Zhonghe Dist.", "zip": "235" },
          { "name_zh": "土城區", "name_en": "Tucheng Dist.", "zip": "236" },
          { "name_zh": "三峽區", "name_en": "Sanxia Dist.", "zip": "237" },
          { "name_zh": "樹林區", "name_en": "Shulin Dist.", "zip": "238" },
          { "name_zh": "鶯歌區", "name_en": "Yingge Dist.", "zip": "239" },
          { "name_zh": "三重區", "name_en": "Sanchong Dist.", "zip": "241" },
          { "name_zh": "新莊區", "name_en": "Xinzhuang Dist.", "zip": "242" },
          { "name_zh": "泰山區", "name_en": "Taishan Dist.", "zip": "243" },
          { "name_zh": "林口區", "name_en": "Linkou Dist.", "zip": "244" },
          { "name_zh": "蘆洲區", "name_en": "Luzhou Dist.", "zip": "247" },
          { "name_zh": "五股區", "name_en": "Wugu Dist.", "zip": "248" },
          { "name_zh": "八里區", "name_en": "Bali Dist.", "zip": "249" },
          { "name_zh": "淡水區", "name_en": "Tamsui Dist.", "zip": "251" },
          { "name_zh": "三芝區", "name_en": "Sanzhi Dist.", "zip": "252" },
          { "name_zh": "石門區", "name_en": "Shimen Dist.", "zip": "253" }
        ]
      },
      {
        "name_zh": "桃園市",
        "name_en": "Taoyuan City",
        "districts": [
          { "name_zh": "中壢區", "name_en": "Zhongli Dist.", "zip": "320" },
          { "name_zh": "平鎮區", "name_en": "Pingzhen Dist.", "zip": "324" },
          { "name_zh": "龍潭區", "name_en": "Longtan Dist.", "zip": "325" },
          { "name_zh": "楊梅區", "name_en": "Yangmei Dist.", "zip": "326" },
          { "name_zh": "新屋區", "name_en": "Xinwu Dist.", "zip": "327" },
          { "name_zh": "觀音區", "name_en": "Guanyin Dist.", "zip": "328" },
          { "name_zh": "桃園區", "name_en": "Taoyuan Dist.", "zip": "330" },
          { "name_zh": "龜山區", "name_en": "Guishan Dist.", "zip": "333" },
          { "name_zh": "八德區", "name_en": "Bade Dist.", "zip": "334" },
          { "name_zh": "大溪區", "name_en": "Daxi Dist.", "zip": "335" },
          { "name_zh": "復興區", "name_en": "Fuxing Dist.", "zip": "336" },
          { "name_zh": "大園區", "name_en": "Dayuan Dist.", "zip": "337" },
          { "name_zh": "蘆竹區", "name_en": "Luzhu Dist.", "zip": "338" }
        ]
      },
      {
        "name_zh": "新竹市",
        "name_en": "Hsinchu City",
        "districts": [
          { "name_zh": "東區", "name_en": "East Dist.", "zip": "300" },
          { "name_zh": "北區", "name_en": "North Dist.", "zip": "300" },
          { "name_zh": "香山區", "name_en": "Xiangshan Dist.", "zip": "300" }
        ]
      },
      {
        "name_zh": "新竹縣",
        "name_en": "Hsinchu County",
        "districts": [
          { "name_zh": "竹北市", "name_en": "Zhubei City", "zip": "302" },
          { "name_zh": "湖口鄉", "name_en": "Hukou Township", "zip": "303" },
          { "name_zh": "新豐鄉", "name_en": "Xinfeng Township", "zip": "304" },
          { "name_zh": "新埔鎮", "name_en": "Xinpu Township", "zip": "305" },
          { "name_zh": "關西鎮", "name_en": "Guanxi Township", "zip": "306" },
          { "name_zh": "芎林鄉", "name_en": "Qionglin Township", "zip": "307" },
          { "name_zh": "寶山鄉", "name_en": "Baoshan Township", "zip": "308" },
          { "name_zh": "竹東鎮", "name_en": "Zhudong Township", "zip": "310" },
          { "name_zh": "五峰鄉", "name_en": "Wufeng Township", "zip": "311" },
          { "name_zh": "橫山鄉", "name_en": "Hengshan Township", "zip": "312" },
          { "name_zh": "尖石鄉", "name_en": "Jianshi Township", "zip": "313" },
          { "name_zh": "北埔鄉", "name_en": "Beipu Township", "zip": "314" },
          { "name_zh": "峨眉鄉", "name_en": "Emei Township", "zip": "315" }
        ]
      },
      {
        "name_zh": "苗栗縣",
        "name_en": "Miaoli County",
        "districts": [
          { "name_zh": "竹南鎮", "name_en": "Zhunan Township", "zip": "350" },
          { "name_zh": "頭份市", "name_en": "Toufen City", "zip": "351" },
          { "name_zh": "三灣鄉", "name_en": "Sanwan Township", "zip": "352" },
          { "name_zh": "南庄鄉", "name_en": "Nanzhuang Township", "zip": "353" },
          { "name_zh": "獅潭鄉", "name_en": "Shitan Township", "zip": "354" },
          { "name_zh": "後龍鎮", "name_en": "Houlong Township", "zip": "356" },
          { "name_zh": "通霄鎮", "name_en": "Tongxiao Township", "zip": "357" },
          { "name_zh": "苑裡鎮", "name_en": "Yuanli Township", "zip": "358" },
          { "name_zh": "苗栗市", "name_en": "Miaoli City", "zip": "360" },
          { "name_zh": "造橋鄉", "name_en": "Zaoqiao Township", "zip": "361" },
          { "name_zh": "頭屋鄉", "name_en": "Touwu Township", "zip": "362" },
          { "name_zh": "公館鄉", "name_en": "Gongguan Township", "zip": "363" },
          { "name_zh": "大湖鄉", "name_en": "Dahu Township", "zip": "364" },
          { "name_zh": "泰安鄉", "name_en": "Tai’an Township", "zip": "365" },
          { "name_zh": "銅鑼鄉", "name_en": "Tongluo Township", "zip": "366" },
          { "name_zh": "三義鄉", "name_en": "Sanyi Township", "zip": "367" },
          { "name_zh": "西湖鄉", "name_en": "Xihu Township", "zip": "368" },
          { "name_zh": "卓蘭鎮", "name_en": "Zhuolan Township", "zip": "369" }
        ]
      },
      {
        "name_zh": "臺中市",
        "name_en": "Taichung City",
        "districts": [
          { "name_zh": "中區", "name_en": "Central Dist.", "zip": "400" },
          { "name_zh": "東區", "name_en": "East Dist.", "zip": "401" },
          { "name_zh": "南區", "name_en": "South Dist.", "zip": "402" },
          { "name_zh": "西區", "name_en": "West Dist.", "zip": "403" },
          { "name_zh": "北區", "name_en": "North Dist.", "zip": "404" },
          { "name_zh": "北屯區", "name_en": "Beitun Dist.", "zip": "406" },
          { "name_zh": "西屯區", "name_en": "Xitun Dist.", "zip": "407" },
          { "name_zh": "南屯區", "name_en": "Nantun Dist.", "zip": "408" },
          { "name_zh": "太平區", "name_en": "Taiping Dist.", "zip": "411" },
          { "name_zh": "大里區", "name_en": "Dali Dist.", "zip": "412" },
          { "name_zh": "霧峰區", "name_en": "Wufeng Dist.", "zip": "413" },
          { "name_zh": "烏日區", "name_en": "Wuri Dist.", "zip": "414" },
          { "name_zh": "豐原區", "name_en": "Fengyuan Dist.", "zip": "420" },
          { "name_zh": "后里區", "name_en": "Houli Dist.", "zip": "421" },
          { "name_zh": "石岡區", "name_en": "Shigang Dist.", "zip": "422" },
          { "name_zh": "東勢區", "name_en": "Dongshi Dist.", "zip": "423" },
          { "name_zh": "和平區", "name_en": "Heping Dist.", "zip": "424" },
          { "name_zh": "新社區", "name_en": "Xinshe Dist.", "zip": "426" },
          { "name_zh": "潭子區", "name_en": "Tanzi Dist.", "zip": "427" },
          { "name_zh": "大雅區", "name_en": "Daya Dist.", "zip": "428" },
          { "name_zh": "神岡區", "name_en": "Shengang Dist.", "zip": "429" },
          { "name_zh": "大肚區", "name_en": "Dadu Dist.", "zip": "432" },
          { "name_zh": "沙鹿區", "name_en": "Shalu Dist.", "zip": "433" },
          { "name_zh": "龍井區", "name_en": "Longjing Dist.", "zip": "434" },
          { "name_zh": "梧棲區", "name_en": "Wuqi Dist.", "zip": "435" },
          { "name_zh": "清水區", "name_en": "Qingshui Dist.", "zip": "436" },
          { "name_zh": "大甲區", "name_en": "Dajia Dist.", "zip": "437" },
          { "name_zh": "外埔區", "name_en": "Waipu Dist.", "zip": "438" },
          { "name_zh": "大安區", "name_en": "Da’an Dist.", "zip": "439" }
        ]
      },
      {
        "name_zh": "彰化縣",
        "name_en": "Changhua County",
        "districts": [
          { "name_zh": "彰化市", "name_en": "Changhua City", "zip": "500" },
          { "name_zh": "芬園鄉", "name_en": "Fenyuan Township", "zip": "502" },
          { "name_zh": "花壇鄉", "name_en": "Huatan Township", "zip": "503" },
          { "name_zh": "秀水鄉", "name_en": "Xiushui Township", "zip": "504" },
          { "name_zh": "鹿港鎮", "name_en": "Lukang Township", "zip": "505" },
          { "name_zh": "福興鄉", "name_en": "Fuxing Township", "zip": "506" },
          { "name_zh": "線西鄉", "name_en": "Xianxi Township", "zip": "507" },
          { "name_zh": "和美鎮", "name_en": "Hemei Township", "zip": "508" },
          { "name_zh": "伸港鄉", "name_en": "Shengang Township", "zip": "509" },
          { "name_zh": "員林市", "name_en": "Yuanlin City", "zip": "510" },
          { "name_zh": "社頭鄉", "name_en": "Shetou Township", "zip": "511" },
          { "name_zh": "永靖鄉", "name_en": "Yongjing Township", "zip": "512" },
          { "name_zh": "埔心鄉", "name_en": "Puxin Township", "zip": "513" },
          { "name_zh": "溪湖鎮", "name_en": "Xihu Township", "zip": "514" },
          { "name_zh": "大村鄉", "name_en": "Dacun Township", "zip": "515" },
          { "name_zh": "埔鹽鄉", "name_en": "Puyan Township", "zip": "516" },
          { "name_zh": "田中鎮", "name_en": "Tianzhong Township", "zip": "520" },
          { "name_zh": "北斗鎮", "name_en": "Beidou Township", "zip": "521" },
          { "name_zh": "田尾鄉", "name_en": "Tianwei Township", "zip": "522" },
          { "name_zh": "埤頭鄉", "name_en": "Pitou Township", "zip": "523" },
          { "name_zh": "溪州鄉", "name_en": "Xizhou Township", "zip": "524" },
          { "name_zh": "竹塘鄉", "name_en": "Zhutang Township", "zip": "525" },
          { "name_zh": "二林鎮", "name_en": "Erlin Township", "zip": "526" },
          { "name_zh": "大城鄉", "name_en": "Dacheng Township", "zip": "527" },
          { "name_zh": "芳苑鄉", "name_en": "Fangyuan Township", "zip": "528" },
          { "name_zh": "二水鄉", "name_en": "Ershui Township", "zip": "530" }
        ]
      },
      {
        "name_zh": "南投縣",
        "name_en": "Nantou County",
        "districts": [
          { "name_zh": "南投市", "name_en": "Nantou City", "zip": "540" },
          { "name_zh": "中寮鄉", "name_en": "Zhongliao Township", "zip": "541" },
          { "name_zh": "草屯鎮", "name_en": "Caotun Township", "zip": "542" },
          { "name_zh": "國姓鄉", "name_en": "Guoxing Township", "zip": "544" },
          { "name_zh": "埔里鎮", "name_en": "Puli Township", "zip": "545" },
          { "name_zh": "仁愛鄉", "name_en": "Ren’ai Township", "zip": "546" },
          { "name_zh": "名間鄉", "name_en": "Mingjian Township", "zip": "551" },
          { "name_zh": "集集鎮", "name_en": "Jiji Township", "zip": "552" },
          { "name_zh": "水里鄉", "name_en": "Shuili Township", "zip": "553" },
          { "name_zh": "魚池鄉", "name_en": "Yuchi Township", "zip": "555" },
          { "name_zh": "信義鄉", "name_en": "Xinyi Township", "zip": "556" },
          { "name_zh": "竹山鎮", "name_en": "Zhushan Township", "zip": "557" },
          { "name_zh": "鹿谷鄉", "name_en": "Lugu Township", "zip": "558" }
        ]
      },
      {
        "name_zh": "雲林縣",
        "name_en": "Yunlin County",
        "districts": [
          { "name_zh": "斗南鎮", "name_en": "Dounan Township", "zip": "630" },
          { "name_zh": "大埤鄉", "name_en": "Dapi Township", "zip": "631" },
          { "name_zh": "虎尾鎮", "name_en": "Huwei Township", "zip": "632" },
          { "name_zh": "土庫鎮", "name_en": "Tuku Township", "zip": "633" },
          { "name_zh": "褒忠鄉", "name_en": "Baozhong Township", "zip": "634" },
          { "name_zh": "東勢鄉", "name_en": "Dongshi Township", "zip": "635" },
          { "name_zh": "臺西鄉", "name_en": "Taixi Township", "zip": "636" },
          { "name_zh": "崙背鄉", "name_en": "Lunbei Township", "zip": "637" },
          { "name_zh": "麥寮鄉", "name_en": "Mailiao Township", "zip": "638" },
          { "name_zh": "斗六市", "name_en": "Douliu City", "zip": "640" },
          { "name_zh": "林內鄉", "name_en": "Linnei Township", "zip": "643" },
          { "name_zh": "古坑鄉", "name_en": "Gukeng Township", "zip": "646" },
          { "name_zh": "莿桐鄉", "name_en": "Citong Township", "zip": "647" },
          { "name_zh": "西螺鎮", "name_en": "Xiluo Township", "zip": "648" },
          { "name_zh": "二崙鄉", "name_en": "Erlun Township", "zip": "649" },
          { "name_zh": "北港鎮", "name_en": "Beigang Township", "zip": "651" },
          { "name_zh": "水林鄉", "name_en": "Shuilin Township", "zip": "652" },
          { "name_zh": "口湖鄉", "name_en": "Kouhu Township", "zip": "653" },
          { "name_zh": "四湖鄉", "name_en": "Sihu Township", "zip": "654" },
          { "name_zh": "元長鄉", "name_en": "Yuanchang Township", "zip": "655" }
        ]
      },
      {
        "name_zh": "嘉義市",
        "name_en": "Chiayi City",
        "districts": [
          { "name_zh": "東區", "name_en": "East Dist.", "zip": "600" },
          { "name_zh": "西區", "name_en": "West Dist.", "zip": "600" }
        ]
      },
      {
        "name_zh": "嘉義縣",
        "name_en": "Chiayi County",
        "districts": [
          { "name_zh": "番路鄉", "name_en": "Fanlu Township", "zip": "602" },
          { "name_zh": "梅山鄉", "name_en": "Meishan Township", "zip": "603" },
          { "name_zh": "竹崎鄉", "name_en": "Zhuqi Township", "zip": "604" },
          { "name_zh": "阿里山鄉", "name_en": "Alishan Township", "zip": "605" },
          { "name_zh": "中埔鄉", "name_en": "Zhongpu Township", "zip": "606" },
          { "name_zh": "大埔鄉", "name_en": "Dapu Township", "zip": "607" },
          { "name_zh": "水上鄉", "name_en": "Shuishang Township", "zip": "608" },
          { "name_zh": "鹿草鄉", "name_en": "Lucao Township", "zip": "611" },
          { "name_zh": "太保市", "name_en": "Taibao City", "zip": "612" },
          { "name_zh": "朴子市", "name_en": "Puzi City", "zip": "613" },
          { "name_zh": "東石鄉", "name_en": "Dongshi Township", "zip": "614" },
          { "name_zh": "六腳鄉", "name_en": "Liujiao Township", "zip": "615" },
          { "name_zh": "新港鄉", "name_en": "Xingang Township", "zip": "616" },
          { "name_zh": "民雄鄉", "name_en": "Minxiong Township", "zip": "621" },
          { "name_zh": "大林鎮", "name_en": "Dalin Township", "zip": "622" },
          { "name_zh": "溪口鄉", "name_en": "Xikou Township", "zip": "623" },
          { "name_zh": "義竹鄉", "name_en": "Yizhu Township", "zip": "624" },
          { "name_zh": "布袋鎮", "name_en": "Budai Township", "zip": "625" }
        ]
      },
      {
        "name_zh": "臺南市",
        "name_en": "Tainan City",
        "districts": [
          { "name_zh": "中西區", "name_en": "West Central Dist.", "zip": "700" },
          { "name_zh": "東區", "name_en": "East Dist.", "zip": "701" },
          { "name_zh": "南區", "name_en": "South Dist.", "zip": "702" },
          { "name_zh": "北區", "name_en": "North Dist.", "zip": "704" },
          { "name_zh": "安平區", "name_en": "Anping Dist.", "zip": "708" },
          { "name_zh": "安南區", "name_en": "Annan Dist.", "zip": "709" },
          { "name_zh": "永康區", "name_en": "Yongkang Dist.", "zip": "710" },
          { "name_zh": "歸仁區", "name_en": "Guiren Dist.", "zip": "711" },
          { "name_zh": "新化區", "name_en": "Xinhua Dist.", "zip": "712" },
          { "name_zh": "左鎮區", "name_en": "Zuozhen Dist.", "zip": "713" },
          { "name_zh": "玉井區", "name_en": "Yujing Dist.", "zip": "714" },
          { "name_zh": "楠西區", "name_en": "Nanxi Dist.", "zip": "715" },
          { "name_zh": "南化區", "name_en": "Nanhua Dist.", "zip": "716" },
          { "name_zh": "仁德區", "name_en": "Rende Dist.", "zip": "717" },
          { "name_zh": "關廟區", "name_en": "Guanmiao Dist.", "zip": "718" },
          { "name_zh": "龍崎區", "name_en": "Longqi Dist.", "zip": "719" },
          { "name_zh": "官田區", "name_en": "Guantian Dist.", "zip": "720" },
          { "name_zh": "麻豆區", "name_en": "Madou Dist.", "zip": "721" },
          { "name_zh": "佳里區", "name_en": "Jiali Dist.", "zip": "722" },
          { "name_zh": "西港區", "name_en": "Xigang Dist.", "zip": "723" },
          { "name_zh": "七股區", "name_en": "Qigu Dist.", "zip": "724" },
          { "name_zh": "將軍區", "name_en": "Jiangjun Dist.", "zip": "725" },
          { "name_zh": "學甲區", "name_en": "Xuejia Dist.", "zip": "726" },
          { "name_zh": "北門區", "name_en": "Beimen Dist.", "zip": "727" },
          { "name_zh": "新營區", "name_en": "Xinying Dist.", "zip": "730" },
          { "name_zh": "後壁區", "name_en": "Houbi Dist.", "zip": "731" },
          { "name_zh": "白河區", "name_en": "Baihe Dist.", "zip": "732" },
          { "name_zh": "東山區", "name_en": "Dongshan Dist.", "zip": "733" },
          { "name_zh": "六甲區", "name_en": "Liujia Dist.", "zip": "734" },
          { "name_zh": "下營區", "name_en": "Xiaying Dist.", "zip": "735" },
          { "name_zh": "柳營區", "name_en": "Liuying Dist.", "zip": "736" },
          { "name_zh": "鹽水區", "name_en": "Yanshui Dist.", "zip": "737" },
          { "name_zh": "善化區", "name_en": "Shanhua Dist.", "zip": "741" },
          { "name_zh": "大內區", "name_en": "Danei Dist.", "zip": "742" },
          { "name_zh": "山上區", "name_en": "Shanshang Dist.", "zip": "743" },
          { "name_zh": "新市區", "name_en": "Xinshi Dist.", "zip": "744" },
          { "name_zh": "安定區", "name_en": "Anding Dist.", "zip": "745" }
        ]
      },
      {
        "name_zh": "高雄市",
        "name_en": "Kaohsiung City",
        "districts": [
          { "name_zh": "新興區", "name_en": "Xinxing Dist.", "zip": "800" },
          { "name_zh": "前金區", "name_en": "Qianjin Dist.", "zip": "801" },
          { "name_zh": "苓雅區", "name_en": "Lingya Dist.", "zip": "802" },
          { "name_zh": "鹽埕區", "name_en": "Yancheng Dist.", "zip": "803" },
          { "name_zh": "鼓山區", "name_en": "Gushan Dist.", "zip": "804" },
          { "name_zh": "旗津區", "name_en": "Qijin Dist.", "zip": "805" },
          { "name_zh": "前鎮區", "name_en": "Qianzhen Dist.", "zip": "806" },
          { "name_zh": "三民區", "name_en": "Sanmin Dist.", "zip": "807" },
          { "name_zh": "楠梓區", "name_en": "Nanzi Dist.", "zip": "811" },
          { "name_zh": "小港區", "name_en": "Xiaogang Dist.", "zip": "812" },
          { "name_zh": "左營區", "name_en": "Zuoying Dist.", "zip": "813" },
          { "name_zh": "仁武區", "name_en": "Renwu Dist.", "zip": "814" },
          { "name_zh": "大社區", "name_en": "Dashe Dist.", "zip": "815" },
          { "name_zh": "岡山區", "name_en": "Gangshan Dist.", "zip": "820" },
          { "name_zh": "路竹區", "name_en": "Luzhu Dist.", "zip": "821" },
          { "name_zh": "阿蓮區", "name_en": "Alian Dist.", "zip": "822" },
          { "name_zh": "田寮區", "name_en": "Tianliao Dist.", "zip": "823" },
          { "name_zh": "燕巢區", "name_en": "Yanchao Dist.", "zip": "824" },
          { "name_zh": "橋頭區", "name_en": "Qiaotou Dist.", "zip": "825" },
          { "name_zh": "梓官區", "name_en": "Ziguan Dist.", "zip": "826" },
          { "name_zh": "彌陀區", "name_en": "Mituo Dist.", "zip": "827" },
          { "name_zh": "永安區", "name_en": "Yong’an Dist.", "zip": "828" },
          { "name_zh": "湖內區", "name_en": "Hunei Dist.", "zip": "829" },
          { "name_zh": "鳳山區", "name_en": "Fengshan Dist.", "zip": "830" },
          { "name_zh": "大寮區", "name_en": "Daliao Dist.", "zip": "831" },
          { "name_zh": "林園區", "name_en": "Linyuan Dist.", "zip": "832" },
          { "name_zh": "鳥松區", "name_en": "Niaosong Dist.", "zip": "833" },
          { "name_zh": "大樹區", "name_en": "Dashu Dist.", "zip": "840" },
          { "name_zh": "旗山區", "name_en": "Qishan Dist.", "zip": "842" },
          { "name_zh": "美濃區", "name_en": "Meinong Dist.", "zip": "843" },
          { "name_zh": "六龜區", "name_en": "Liugui Dist.", "zip": "844" },
          { "name_zh": "內門區", "name_en": "Neimen Dist.", "zip": "845" },
          { "name_zh": "杉林區", "name_en": "Shanlin Dist.", "zip": "846" },
          { "name_zh": "甲仙區", "name_en": "Jiaxian Dist.", "zip": "847" },
          { "name_zh": "桃源區", "name_en": "Taoyuan Dist.", "zip": "848" },
          { "name_zh": "那瑪夏區", "name_en": "Namaxia Dist.", "zip": "849" },
          { "name_zh": "茂林區", "name_en": "Maolin Dist.", "zip": "851" },
          { "name_zh": "茄萣區", "name_en": "Qieding Dist.", "zip": "852" }
        ]
      },
      {
        "name_zh": "屏東縣",
        "name_en": "Pingtung County",
        "districts": [
          { "name_zh": "屏東市", "name_en": "Pingtung City", "zip": "900" },
          { "name_zh": "三地門鄉", "name_en": "Sandimen Township", "zip": "901" },
          { "name_zh": "霧臺鄉", "name_en": "Wutai Township", "zip": "902" },
          { "name_zh": "瑪家鄉", "name_en": "Majia Township", "zip": "903" },
          { "name_zh": "九如鄉", "name_en": "Jiuru Township", "zip": "904" },
          { "name_zh": "里港鄉", "name_en": "Ligang Township", "zip": "905" },
          { "name_zh": "高樹鄉", "name_en": "Gaoshu Township", "zip": "906" },
          { "name_zh": "鹽埔鄉", "name_en": "Yanpu Township", "zip": "907" },
          { "name_zh": "長治鄉", "name_en": "Changzhi Township", "zip": "908" },
          { "name_zh": "麟洛鄉", "name_en": "Linluo Township", "zip": "909" },
          { "name_zh": "竹田鄉", "name_en": "Zhutian Township", "zip": "911" },
          { "name_zh": "內埔鄉", "name_en": "Neipu Township", "zip": "912" },
          { "name_zh": "萬丹鄉", "name_en": "Wandan Township", "zip": "913" },
          { "name_zh": "潮州鎮", "name_en": "Chaozhou Township", "zip": "920" },
          { "name_zh": "泰武鄉", "name_en": "Taiwu Township", "zip": "921" },
          { "name_zh": "來義鄉", "name_en": "Laiyi Township", "zip": "922" },
          { "name_zh": "萬巒鄉", "name_en": "Wanluan Township", "zip": "923" },
          { "name_zh": "崁頂鄉", "name_en": "Kanding Township", "zip": "924" },
          { "name_zh": "新埤鄉", "name_en": "Xinpi Township", "zip": "925" },
          { "name_zh": "南州鄉", "name_en": "Nanzhou Township", "zip": "926" },
          { "name_zh": "林邊鄉", "name_en": "Linbian Township", "zip": "927" },
          { "name_zh": "東港鎮", "name_en": "Donggang Township", "zip": "928" },
          { "name_zh": "琉球鄉", "name_en": "Liuqiu Township", "zip": "929" },
          { "name_zh": "佳冬鄉", "name_en": "Jiadong Township", "zip": "931" },
          { "name_zh": "新園鄉", "name_en": "Xinyuan Township", "zip": "932" },
          { "name_zh": "枋寮鄉", "name_en": "Fangliao Township", "zip": "940" },
          { "name_zh": "枋山鄉", "name_en": "Fangshan Township", "zip": "941" },
          { "name_zh": "春日鄉", "name_en": "Chunri Township", "zip": "942" },
          { "name_zh": "獅子鄉", "name_en": "Shizi Township", "zip": "943" },
          { "name_zh": "車城鄉", "name_en": "Checheng Township", "zip": "944" },
          { "name_zh": "牡丹鄉", "name_en": "Mudan Township", "zip": "945" },
          { "name_zh": "恆春鎮", "name_en": "Hengchun Township", "zip": "946" },
          { "name_zh": "滿州鄉", "name_en": "Manzhou Township", "zip": "947" }
        ]
      },
      {
        "name_zh": "宜蘭縣",
        "name_en": "Yilan County",
        "districts": [
          { "name_zh": "宜蘭市", "name_en": "Yilan City", "zip": "260" },
          { "name_zh": "頭城鎮", "name_en": "Toucheng Township", "zip": "261" },
          { "name_zh": "礁溪鄉", "name_en": "Jiaoxi Township", "zip": "262" },
          { "name_zh": "壯圍鄉", "name_en": "Zhuangwei Township", "zip": "263" },
          { "name_zh": "員山鄉", "name_en": "Yuanshan Township", "zip": "264" },
          { "name_zh": "羅東鎮", "name_en": "Luodong Township", "zip": "265" },
          { "name_zh": "三星鄉", "name_en": "Sanxing Township", "zip": "266" },
          { "name_zh": "大同鄉", "name_en": "Datong Township", "zip": "267" },
          { "name_zh": "五結鄉", "name_en": "Wujie Township", "zip": "268" },
          { "name_zh": "冬山鄉", "name_en": "Dongshan Township", "zip": "269" },
          { "name_zh": "蘇澳鎮", "name_en": "Su’ao Township", "zip": "270" },
          { "name_zh": "南澳鄉", "name_en": "Nan’ao Township", "zip": "272" }
        ]
      },
      {
        "name_zh": "花蓮縣",
        "name_en": "Hualien County",
        "districts": [
          { "name_zh": "花蓮市", "name_en": "Hualien City", "zip": "970" },
          { "name_zh": "新城鄉", "name_en": "Xincheng Township", "zip": "971" },
          { "name_zh": "秀林鄉", "name_en": "Xiulin Township", "zip": "972" },
          { "name_zh": "吉安鄉", "name_en": "Ji’an Township", "zip": "973" },
          { "name_zh": "壽豐鄉", "name_en": "Shoufeng Township", "zip": "974" },
          { "name_zh": "鳳林鎮", "name_en": "Fenglin Township", "zip": "975" },
          { "name_zh": "光復鄉", "name_en": "Guangfu Township", "zip": "976" },
          { "name_zh": "豐濱鄉", "name_en": "Fengbin Township", "zip": "977" },
          { "name_zh": "瑞穗鄉", "name_en": "Ruisui Township", "zip": "978" },
          { "name_zh": "萬榮鄉", "name_en": "Wanrong Township", "zip": "979" },
          { "name_zh": "玉里鎮", "name_en": "Yuli Township", "zip": "981" },
          { "name_zh": "卓溪鄉", "name_en": "Zhuoxi Township", "zip": "982" },
          { "name_zh": "富里鄉", "name_en": "Fuli Township", "zip": "983" }
        ]
      },
      {
        "name_zh": "臺東縣",
        "name_en": "Taitung County",
        "districts": [
          { "name_zh": "臺東市", "name_en": "Taitung City", "zip": "950" },
          { "name_zh": "綠島鄉", "name_en": "Ludao Township", "zip": "951" },
          { "name_zh": "蘭嶼鄉", "name_en": "Lanyu Township", "zip": "952" },
          { "name_zh": "延平鄉", "name_en": "Yanping Township", "zip": "953" },
          { "name_zh": "卑南鄉", "name_en": "Beinan Township", "zip": "954" },
          { "name_zh": "鹿野鄉", "name_en": "Luye Township", "zip": "955" },
          { "name_zh": "關山鎮", "name_en": "Guanshan Township", "zip": "956" },
          { "name_zh": "海端鄉", "name_en": "Haiduan Township", "zip": "957" },
          { "name_zh": "池上鄉", "name_en": "Chishang Township", "zip": "958" },
          { "name_zh": "東河鄉", "name_en": "Donghe Township", "zip": "959" },
          { "name_zh": "成功鎮", "name_en": "Chenggong Township", "zip": "961" },
          { "name_zh": "長濱鄉", "name_en": "Changbin Township", "zip": "962" },
          { "name_zh": "太麻里鄉", "name_en": "Taimali Township", "zip": "963" },
          { "name_zh": "金峰鄉", "name_en": "Jinfeng Township", "zip": "964" },
          { "name_zh": "大武鄉", "name_en": "Dawu Township", "zip": "965" },
          { "name_zh": "達仁鄉", "name_en": "Daren Township", "zip": "966" }
        ]
      },
      {
        "name_zh": "澎湖縣",
        "name_en": "Penghu County",
        "districts": [
          { "name_zh": "馬公市", "name_en": "Magong City", "zip": "880" },
          { "name_zh": "西嶼鄉", "name_en": "Xiyu Township", "zip": "881" },
          { "name_zh": "望安鄉", "name_en": "Wang’an Township", "zip": "882" },
          { "name_zh": "七美鄉", "name_en": "Qimei Township", "zip": "883" },
          { "name_zh": "白沙鄉", "name_en": "Baisha Township", "zip": "884" },
          { "name_zh": "湖西鄉", "name_en": "Huxi Township", "zip": "885" }
        ]
      },
      {
        "name_zh": "金門縣",
        "name_en": "Kinmen County",
        "districts": [
          { "name_zh": "金沙鎮", "name_en": "Jinsha Township", "zip": "890" },
          { "name_zh": "金湖鎮", "name_en": "Jinhu Township", "zip": "891" },
          { "name_zh": "金寧鄉", "name_en": "Jinning Township", "zip": "892" },
          { "name_zh": "金城鎮", "name_en": "Jincheng Township", "zip": "893" },
          { "name_zh": "烈嶼鄉", "name_en": "Lieyu Township", "zip": "894" },
          { "name_zh": "烏坵鄉", "name_en": "Wuqiu Township", "zip": "896" }
        ]
      },
      {
        "name_zh": "連江縣",
        "name_en": "Lienchiang County",
        "districts": [
          { "name_zh": "南竿鄉", "name_en": "Nangan Township", "zip": "209" },
          { "name_zh": "北竿鄉", "name_en": "Beigan Township", "zip": "210" },
          { "name_zh": "莒光鄉", "name_en": "Juguang Township", "zip": "211" },
          { "name_zh": "東引鄉", "name_en": "Dongyin Township", "zip": "212" }
        ]
      }
    ];



    return{
        addrTaiwan,
    };
    

}) 
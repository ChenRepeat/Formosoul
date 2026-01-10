# Formosoul - 台灣文化魔法學院
|<img src="src\assets\LOGO_blackColor.svg" width="auto" alt="Lexcy 首頁" />|
> **Project Formosoul** > 一個結合「魔法學院」沉浸式體驗，面向外國人的台灣文化介紹網站。  
> — A magical nexus weaving through diverse civilizations.

---

## 技術堆疊 (Tech Stack)

本專案採用 Vue.js 生態系進行開發，並整合多種函式庫。

* **Core Framework**
  * **Vue.js 3**

* **Backend & Database**
  * **PHP**: 伺服器端邏輯處理、API 開發
  * **MySQL**: 關聯式資料庫系統,負責儲存會員、商品、訂單及年度盛事等動態資料

* **Routing & State Management**
  * **Vue Router**: 官方路由管理
  * **Pinia**: 核心狀態管理管理

* **Styling**
  * **SCSS (Sass)**: CSS 預處理器
  * **BEM Naming Convention**: CSS 命名規範

* **UI Components**
  * **Element-Plus**: UI 組件庫

* **Animation & Interaction**
  * **GSAP**: 核心動畫控制
  * **Lenis**: 平滑捲動 (Smooth Scrolling)
  * **Page-Flip**: 書籍翻頁效果 (用於 Classes 頁面)
  * **Three.js**: 3D 效果實驗
  * **Swiper**: 輪播組件

* **Networking & Services**
  * **Axios**: Promise 基礎 HTTP 客戶端,用於與 PHP 後端進行 API 串接
  * **EmailJS**: 用於前端直接發送電子郵件功能
  * **Leaflet**: 互動式地圖呈現 (用於夜市地圖位置)
  * **Js-cookie**: 輕量級 Cookie 管理,用於登入狀態記錄

* **Internationalization & Auth**
  * **Vue-i18n**: 多國語言支援系統 (支援中、英)
  * **Vue3-google-login**: 第三方 Google 帳號登入整合

* **Icons**
  * **FontAwesome**: SVG Core, Brands, Regular, Solid
  * **SVG Components**: 自訂 SVG 圖標組件

* **Tools**
  * **Vite**: 前端開發與打包工具
  * **Sass-embedded**:  Sass 編譯

---

## 視覺設計規範 (Visual Design Guidelines)

### 1. 色彩規範 (Color System)

#### 品牌主色 (Primary Colors)
| 色彩名稱 | 色碼 (Hex) | 變數名稱 (SCSS) | 說明 |
| :--- | :--- | :--- | :--- |
| **海洋藍 Blue** | `#00529F` | `$color-fsBlue` | 品牌標準主色 |
| **太陽金 Gold** | `#FF9010` | `$color-fsGold` | 品牌標準輔助色 |
| **森林綠 Green** | `#4F7A26` | `$color-fsGreen` | 品牌次要色 |
| **Formosoul 紅** | `#CC202B` | `$color-fsRed` | 品牌次要色 |

#### 階層色與功能色 (UI Colors)
| 應用項目 | 色碼 (Hex) | 變數名稱 (SCSS) | 說明 |
| :--- | :--- | :--- | :--- |
| **各級標題** | `#041426` | `$color-fsTitle` | Blue 950 |
| **內文資訊** | `#5D5D5D` | `$color-fsContent` | Black 600 |
| **輔助資訊** | `#B0B0B0` | `$color-fsCaption` | Black 300 |
| **彈窗背景** | `#F6F6F6` | `$color-fsPopup` | Black 50 |
| **純白** | `#FFFFFF` | `$color-fsWhite` | |

#### 色階延伸 (Extended Palette)
* **Gold:** `$color-fsGold700` (`#BB4702`), `$color-fsGold300` (`#ffcc46`), `$color-fsGold50` (`#FFFBEA`)
* **Blue:** `$color-fsBlue900` (`#0A3D70`), `$color-fsBlue300` (`#93c8fd`), `$color-fsBlue50` (`#F0F7FF`)

---

### 2. 字級規範 (Typography System)

#### 字體家族 (Font Family)
* **全域設定:** `'Ubuntu'`, `'Roboto'`, `'Noto Sans TC'`, sans-serif
* **內文專用:** `'Roboto'`, `'Noto Sans TC'`, sans-serif

#### 基礎排版設定
* **根字級 (Root size):** `62.5%` (1rem = 10px)
* **字距 (Letter Spacing):** 全域 `0.05em`
* **行高規則:** 標題 (H1-H6) `160%` / 內文 (p) `180%`

#### 字級階層表 (Type Scale)
| 級別 | 尺寸 (rem/px) | 適用範圍 |
| :--- | :--- | :--- |
| **H1** | 6.0rem (60px) | 特殊大標題 |
| **H2** | 5.2rem (52px) | 頁面主標題 |
| **H3** | 4.0rem (40px) | 彈窗主標題 |
| **H4** | 3.2rem (32px) | 中型標題 |
| **H5** | 2.8rem (28px) | 次要標題 |
| **H6** | 2.0rem (20px) | 小型標題 |
| **p** | 1.6rem (16px) | 標準內文資訊 |

---

### 3. 排版與組件規範 (Layout & Components)

#### 字體樣式 Class
* **對齊方式:** `.ta-justify` (中文段落分散對齊)
* **字體粗細:**
    * `.fw200`: Light (200)
    * `Default`: Regular (400)
    * `.fw600`: Medium/Bold (600)

#### 佈局工具 (Flexbox)
* `.dp-flex`: `display: flex; flex-direction: row;`
* `.dp-flex-col`: `display: flex; flex-direction: column;`
* `.dp-none`: `display: none;`

#### UI 特效與元件
* **毛玻璃背景 (`.bg-frostedGlass`):**
    * `backdrop-filter: blur(70px)`
    * `box-shadow`: 內陰影 `inset 0 2px 6px rgba(255,255,255,0.05)` 與外陰影
    * `border-radius`: `10px`
* **標準輸入框 (`.input-text`):**
    * 尺寸: `height: 44px`, `font-size: 2rem`
    * Focus 狀態: 邊框色改為 `$color-fsGold` 並增加外陰影

## 開發規範 (Coding Conventions)

為了維護程式碼的一致性與可讀性，請團隊成員嚴格遵守以下命名與開發原則。

### 1. 通用原則 (General)
* **PascalCase (大駝峰)**：Vue 組件名稱 (e.g., `MyComponent.vue`)。
* **camelCase (小駝峰)**：變數、方法 (e.g., `myFunction`)。
* **kebab-case (烤肉串)**：CSS Class、HTML ID、自定義事件 (e.g., `my-class`)。
* **描述性命名**：多字詞描述，避免縮寫。
* **布林值**：以 `is` 或 `has` 開頭 (e.g., `isActive`, `hasError`)。

### 2. JavaScript / Vue Script
* **變數 / Props**：`camelCase` (e.g., `userName`, `userId`)
* **Computed (有回傳值)**：`camelCase` (e.g., `fullName`)
* **函式 / 方法**：`camelCase`，並以**動詞**開頭 (e.g., `fetchUserData()`, `handleSubmit()`)
* **常數 (Constants)**：`SNAKE_CASE` (e.g., `MAX_USERS`, `API_BASE_URL`)

### 3. HTML / CSS (BEM Modified)
* **Class 命名結構**：`kebab-case`
    * 遵循 BEM 變體：`block__element--modifier`
    * 範例：`user-profile__name--active` (區塊__元素--狀態)
* **ID**：`kebab-case` (e.g., `main-content`)
* **Scoped CSS**：`kebab-case` (e.g., `.user-profile { ... }`)

### 4. 組件命名 (Component Naming)
* **規則**：均以**複合字 (Multi-word)** 表示，避免與 HTML 原生標籤衝突。
* **基礎組件 (Base)**：以 `Base` 前綴 (e.g., `BaseButton.vue`, `BaseInput.vue`)。
* **功能組件 (Feature)**：以功能描述命名 (e.g., `UserProfile.vue`, `NewsCard.vue`)。
* **模板使用**：在 HTML Template 中使用 `kebab-case`。
    * 範例：`<base-button></base-button>`

---

## 開發日誌 (Changelog)
> **Legend:**
> - `[+]` New Feature (新增)
> - `[M]` Modify (修改/優化)
> - `[v]` Confirm (確認)
> - `[!]` Major Decision (重大修改決策)
### Phase 3: 收尾與優化 (Optimization & Finalization)

* **26.01.09**
    * `[+]` 系統開發：實作會員收藏功能，支援點擊後重新渲染狀態。
    * `[+]` 多國語言：全站 i18n 內容校對，包含關於頁面與會員列表修正。
    * `[M]` 介面優化：修正年度盛事 (AnnualEvent) 的內距 (Padding) 與點選範圍。
    * `[M]` 邏輯優化：調整入學小遊戲速度、登入後自動關閉登入視窗邏輯。
    * `[M]` 後台管理：修正後台新聞與年度盛事圖片拖曳上傳、修正訂單資料位移。

* **26.01.08**
    * `[+]` 系統功能：新增登入註冊驗證碼、實作分享商品功能。
    * `[+]` 多國語言：首頁、商品詳細頁、會員中心 i18n 翻譯補全。
    * `[+]` 後台管理：新增會員搜尋功能、修改後台商品 ID 形式。
    * `[M]` 遊戲互動：修正魔藥小遊戲 (Potion Game) 邏輯、加入成功過關動畫。
    * `[M]` 視覺優化：壓縮全站圖片、微調 CSS 效能、修正 Header 點擊 Bug。

* **26.01.07**
    * `[+]` 系統串接：完成綠界金流串接與回傳網址配置。
    * `[+]` 系統功能：第三方登入整合 (LINE、Google)，並修正登入取消邏輯。
    * `[+]` 互動開發：新增天氣動畫效果、地圖與列表頁面修復。
    * `[+]` 後台管理：年度盛事列表編輯功能 (新增、刪除、活動管理)。
    * `[M]` 性能優化：修正訪客登出後清除資料邏輯、優化擲骰子音效。

* **26.01.06**
    * `[+]` 後台管理：新增優惠券管理功能、修正訂單與商品列表排序。
    * `[M]` 多國語言：入學小遊戲、年度盛事 i18n 邏輯整合。
    * `[M]` 邏輯修正：優化入學小遊戲選單切換、修正前往會員中心路徑。

* **26.01.05**
    * `[+]` 系統開發：實作訂單清單 (OrderList) 打包、金流 API 參數設定。
    * `[+]` 內容擴充：新增全站版權 (Copyright) i18n 翻譯。
    * `[M]` 遊戲優化：修正釣蝦與套圈圈遊戲判定、整合集點卡動畫與邏輯。
    * `[M]` 後台管理：後台商品分類修正、上架狀態切換。

* **26.01.04**
    * `[+]` 系統串接：購物車取得會員 ID 邏輯、登入時同步取得 PHP 訂單資料。
    * `[+]` 內容新增：新聞資訊串接 MySQL 資料庫。
    * `[+]` 內容新增：Classes 索引列 i18n、新聞資訊串接資料庫。
    * `[M]` 視覺修正：調整魔杖芯 (Wandcore) 資料庫渲染、修正大頭貼顯示。
    * `[M]` 佈局優化：調整年度盛事影片形狀、Classes 窄版寬度適配。

* **26.01.03**
    * `[+]` 系統開發：實作年度盛事動態資料串接 (Dynamic Data)。
    * `[+]` 視覺優化：新增天氣 API 與對應動畫效果。
    * `[M]` 佈局優化：調整索引標籤 RWD、修正 Index Bar 文字顏色與邏輯。

* **26.01.02**
    * `[+]` 系統開發：Google 登入 API 整合、後台商品編輯與刪除功能。
    * `[M]` 佈局優化：夜市地點 Google Sheet 整合、i18n 切換支援。
    * `[M]` 視覺修正：修正 Google & LINE 登入圖標大小、Loading 動效優化。

* **26.01.01**
    * `[+]` HAPPY NEW YEAR !! 專案正式版細節調整。
    * `[+]` 系統功能：新增運費計算、最終結帳金額計算、信用卡與地址類型欄位。
    * `[+]` 多國語言：抽籤、存檔系統、Classes 內容 i18n 翻譯切換。

* **25.12.31**
    * `[+]` 系統開發：串接 OrderList 與 Cart.js、實作購買數量上限提示。
    * `[+]` 互動更新：新增抽籤存檔、自行車遊戲存檔、點數卡 i18n。
    * `[M]` 邏輯優化：修正 LINE 第三方登入與後台編輯商品權限、修正會員頭貼更新。

* **25.12.30**
    * `[+]` 系統開發：實作查詢訂單完成功能、Cart.js 基礎架構、煙火特效 (Fireworks)。
    * `[M]` 邏輯優化：將抽籤邏輯從 Emit/Props 遷移至 Pinia (Store) 管理。
    * `[M]` RWD 修正：調整 1200px 斷點佈局、Header Icon 購物車數量標記 (Qty)。

* **25.12.29**
    * `[+]` 系統開發：實作串接 LINE 第三方登入、新增魔杖蓋章互動效果。
    * `[M]` 遊戲優化：修復機車遊戲結果判定與 RWD、修正魔杖蓋章動畫 Bug。
    * `[M]` 後台管理：編輯商品列表邏輯優化、調整伺服器環境變數。

* **25.12.28**
    * `[+]` 系統開發：新增修改密碼邏輯、Pinia 整合 ProductList。
    * `[+]` 內容新增：新增魔藥小遊戲 (Potion Game) 素材與邏輯。
    * `[M]` 視覺優化：重構 Loading 動畫元件、修正跳轉時 RWD 圖標遺失。

* **25.12.27**
    * `[+]` 系統開發：實作信用卡資料驗證與自動跳轉、新增 Pinia 管理 product.js。
    * `[M]` 視覺優化：修正 Classes 翻頁與 RWD Logo 顯示邏輯。

* **25.12.26**
    * `[+]` 系統開發：實作後台商品/訂單列表、會員權限門禁、註冊加入名字驗證。
    * `[+]` 內容新增：新增套圈圈遊戲 (Ring Toss) 雛形與 i18n、新增台灣地址下拉選擇。
    * `[M]` 邏輯優化：登入後自動寫入 Cookie 紀錄。

* **25.12.25**
    * `[+]` 系統開發：實作註冊後端初版、新增商品分頁與動態顯示邏輯。
    * `[M]` 效能優化：修正 PHP 環境變數路徑、刪除多餘測試照片。

* **25.12.24**
    * `[+]` 內容新增：新增生存守則英雄頁面與夜市篇章中文內容。
    * `[M]` 邏輯修正：修正 Dice Game 手部動畫、修正錯誤訊息彈窗樣式。

* **25.12.23**
    * `[+]` 系統開發：新增環境變數檔案、實作全站中英切換邏輯。
    * `[M]` 邏輯優化：重構商品詳細頁圖片切換與 RWD 調整。

* **25.12.22**
    * `[+]` 內容新增：Classes i18n 內容、新增魔杖芯 (WandCores) 資料數組。
    * `[M]` 視覺優化：新增機車遊戲原型、調整夜市地圖 RWD。

* **25.12.21**
    * `[+]` 系統開發：實作收藏功能點擊切換與防止冒泡邏輯。
    * `[M]` 視覺特效：新增過場動畫、修正商品陣列加入 isLike 欄位。

* **25.12.20**
    * `[+]` 系統開發：實作重新開始按鈕邏輯 (Restart Btn)、確認遊戲存檔資料邏輯。
    * `[M]` 邏輯優化：修正遊戲骰子手勢與位置判定。

* **25.12.19**
    * `[+]` 組件開發：實作點擊漢堡選單外部自動關閉功能、Header 語言切換測試。

* **25.12.18**
    * `[+]` 系統開發：購物車步驟切換組件拆分、商品詳細頁加入數量加減功能。
    * `[M]` 視覺優化：新增首頁小遊戲發光入口、修正年度盛事內容區域高度偏移。

* **25.12.17**
    * `[+]` 內容開發：About 頁面海浪特效與島嶼浮動效果優化。
    * `[+]` 組件開發：商品詳細頁 Tab 切換與立即購買點擊邏輯。
    * `[M]` 邏輯修正：修復會員資料與收藏頁面 Bug、修正 SVG 模糊問題。

* **25.12.16**
    * `[+]` 專案部署：上傳至伺服器，修正頁面與圖片路徑 (Deploy Fixes)。
    * `[+]` 功能新增：入學遊戲連結、About 頁面、年度盛事詳細頁。
    * `[+]` 環境配置：加入 Swiper 套件。
    * `[M]` 樣式微調：全站頁面細節調整。
    * `[M]` 邏輯修正：漢堡選單 (Hamburger Menu) 開關邏輯優化。

* **25.12.15**
    * `[+]` 互動開發：生存守則 (拖曳、骰子遊戲邏輯)、Classes (抽籤、擲茭)。
    * `[+]` 系統開發：後台頁面與路由配置。
    * `[+]` 內容新增：生存守則素材、入學小遊戲。
    * `[M]` 版面優化：最新消息排版、`<p>` 段落對齊、Classes 細節。
    * `[M]` 頁面調整：收藏頁面、生存守則資訊。

* **25.12.14**
    * `[+]` 會員功能：登入狀態判斷、登出按鈕。
    * `[+]` 遊戲功能：骰子遊戲實作。
    * `[M]` 邏輯修正：Classes 互動 (魔藥、符咒)、翻頁邏輯。
    * `[M]` 樣式修正：首頁動畫、會員中心元素、註冊頁 UI。

### Phase 2: 核心功能開發 (Core Features)
* **25.12.13**
    * `[+]` 組件開發：波浪特效 (Wave Effect) 組件化。
    * `[+]` 內容新增：Classes (占卜學)。
    * `[M]` UI 更新：Logo 格式優化 (PNG -> SVG)、優惠券樣式確認。

* **25.12.12**
    * `[+]` 樣式新增：玻璃擬態 (Glassmorphism) CSS Class。
    * `[+]` 內容新增：Classes (魔法史、魔藥學、符咒學)、生存守則遊戲素材、Policy 頁面。
    * `[+]` 購物流程：新增確認頁 (Checkout)、支付頁 (Payment)。
    * `[+]` 路由邏輯：動態 Header (根據路由切換 Logo/背景色)。
    * `[+]` 組件庫：SVG 組件化、定義 Input 樣式。
    * `[M]` 佈局修正：整合 Dark/Light Header，改善首頁配置與消息卡片。

* **25.12.11**
    * `[+]` 內容新增：Classes (稀有動物、魔法史)、夜市相關圖片。
    * `[+]` 功能新增：商品頁返回按鈕、會員中心卡片/按鈕。
    * `[M]` 邏輯優化：Professor 卡片滑鼠互動、Header Menu 開關、首頁判定。
    * `[M]` 樣式簡化：Button Style 優化。

* **25.12.10**
    * `[+]` 系統功能：登入/註冊功能實作。
    * `[+]` 內容新增：Classes 目錄、商品圖。
    * `[M]` 版面修正：夜市地圖、段落對齊。

* **25.12.09**
    * `[+]` 互動功能：滑鼠位置偵測 (Mouse Tracking)。
    * `[+]` 商場功能：搜尋與下拉選單。
    * `[+]` 樣式擴充：新增按鈕 Variant。
    * `[M]` 修正：生存守則頁面、商品頁路由。

* **25.12.08**
    * `[+]` 內容新增：Classes (機車文化、稀有動物)、生存守則 (夜市)、商品照片。
    * `[M]` 架構調整：Logo Header 拆分，實作路由顏色判斷。

* **25.12.07**
    * `[+]` 互動更新：Classes 翻頁邏輯、開頭動畫。
    * `[M]` 修正：Member Linebar 顯示問題。

* **25.12.06**
    * `[+]` 環境建置：引入 GSAP、Element-Plus、Lenis。
    * `[+]` 組件開發：SVG 組件化、Dark Mode Header、Professor 輪播。
    * `[+]` 功能開發：Classes 索引頁與跳頁功能、首頁動畫。

* **25.12.05**
    * `[v]` 功能試做：Header 玻璃效果。
    * `[+]` 頁面開發：商品頁卡片、會員頁功能。
    * `[M]` 修正：App.vue 佈局與 index.html 設定。

* **25.12.04**
    * `[v]` 組件定稿：生存守則物件、Professor 卡片。
    * `[!]` 技術決策：**Class 書本特效確定採用 Page-Flip 製作** (捨棄 Three.js)。
    * `[+]` 環境配置：Three.js, Page-Flip, NPM 指令。
    * `[+]` 樣式設定：字體粗細、FontAwesome 紀錄。

* **25.12.02**
    * `[v]` 組件定稿：新聞卡片、Footer、按鈕組件 (含文件)。
    * `[+]` 環境配置：FontAwesome。
    * `[+]` 頁面整理：Member 組件。
    * `[M]` Debug：Policy 背景問題。

* **25.12.01**
    * `[v]` 規範確立：SCSS 架構、全域變數 (Variables)。
    * **正式進入開發階段 (Development Start)**

### Phase 1: 前期規劃與設計 (Planning & Design)
* **25.11.30**：建立 Survival Guide、Member 頁面、Footer 原型、加入 Logo。
* **25.11.29**：建立專案路由 (Vue Router)、試做卡片組件。
* **25.11.28**：**Vue 專案初始化**。新增指令、確立 Git 流程與開發規則。
* **25.11.11 - 27**：素材搜集、Mockup 定稿 (v)、Canvas 動畫試做。
* **25.11.06**：專案啟動。確立主題 (Theme)、網站架構 (Structure)、Logo 設計。

---
*Last Updated: 2026.01.10*

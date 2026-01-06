# Formosoul - 台灣文化魔法學院

> **Project Formosoul** > 一個結合「魔法學院」沉浸式體驗，面向外國人的台灣文化介紹網站。  
> — A magical nexus weaving through diverse civilizations.

---

## 🛠️ 技術堆疊 (Tech Stack)

本專案採用 Vue.js 生態系進行開發，並整合多種高階互動函式庫。

* **Core Framework:** Vue.js 3
* **Styling:** SCSS (Sass), BEM Naming Convention
* **UI Components:** Element-Plus
* **Animation & Interaction:**
    * **GSAP**: 核心動畫控制
    * **Lenis**: 平滑捲動 (Smooth Scrolling)
    * **Page-Flip**: 書籍翻頁效果 (用於 Classes 頁面)
    * **Three.js**: 3D 效果實驗
    * **Swiper**: 輪播組件
* **Icons:** FontAwesome, SVG Components
* **Tools:** Vite, Git

---

## 🎨 視覺設計規範 (Visual Design Guidelines)

本專案視覺系統以「魔法學院」為核心，結合台灣在地色彩元素。

### 1. 色彩規範 (Color System)

#### 品牌主色 (Primary Colors)
| 色彩名稱 | 色碼 (Hex) | 變數建議 | 說明 |
| :--- | :--- | :--- | :--- |
| **海洋藍 Blue** | `#00529F` | `color-primary-blue` | 品牌標準主色 |
| Blue 950 | `#041426` | `color-blue-950` | 極深藍 (標題文字用) |
| Blue 900 | `#0a3d70` | `color-blue-900` | 深藍 |
| Blue 600 | `#0a8aed` | `color-blue-600` | 亮藍 |
| Blue 50 | `#f0f7ff` | `color-blue-50` | 極淺藍 (背景底色) |
| **太陽金 Gold** | `#FF9810` | `color-primary-gold` | 品牌標準輔助色 |
| Gold 950 | `#2B0E00` | `color-gold-950` | 極深褐金 |
| Gold 700 | `#bb4702` | `color-gold-700` | 深橘金 |
| Gold 300 | `#ffcc46` | `color-gold-300` | 亮金 (文字連結用) |
| Gold 50 | `#FFFBEA` | `color-gold-50` | 極淺金 |

#### 次要色 (Secondary Colors)
| 色彩名稱 | 色碼 (Hex) | 變數建議 |
| :--- | :--- | :--- |
| **森林綠 Green** | `#617E46` | `color-secondary-green` |
| **Formosoul 紅** | `#CC202B` | `color-secondary-red` |

#### 無彩色系 (Neutrals / Grayscale)
| 名稱 | 色碼 (Hex) | 說明 |
| :--- | :--- | :--- |
| Black_LOGO | `#000000` | 純黑 |
| Black | `#262626` | 接近純黑 |
| Black 900 | `#3d3d3d` | 深灰 |
| Black 600 | `#5d5d5d` | 中深灰 (標準內文用色) |
| Black 400 | `#7F7F7F` | 中灰 |
| Black 300 | `#b0b0b0` | 淺灰 (輔助資訊用色) |
| Black 200 | `#d1d1d1` | 極淺灰 |
| Black 50 | `#f6f6f6` | 灰白背景 |

### 2. 字級規範 (Typography System)

#### 字體家族 (Font Family)
* **繁體中文 (Traditional Chinese):** Noto Sans TC
* **英文 (English):** Roboto, Ubuntu
* **字重 (Weights):** Light (300), Regular (400), Medium (500), Bold (700)

#### 字體顏色應用 (Font Color Usage)
| 應用項目 | 色碼 (Hex) | 對應色系 |
| :--- | :--- | :--- |
| **各級標題** | `#041426` | Blue 950 |
| **內文資訊** | `#5d5d5d` | Black 600 |
| **輔助資訊** | `#b0b0b0` | Black 300 |
| **文字連結** | `#ffcc46` | Gold 300 |
| **收藏、金額** | `#B00020` | Red (Custom) |

#### 字級階層表 (Type Scale)
* **Letter Spacing:** 全域 5%
* **行高規則:** H1-H6 (160%), Desktop Body (200%), Mobile Body (160%)

| 級別 | 字級 (px) | 字重 | 適用裝置 | 說明 |
| :--- | :--- | :--- | :--- | :--- |
| **H1** | 60 | Medium | 通用 | 特殊大標題 |
| **H2** | 52 | Medium | Desktop | 頁面主標題 |
| **H3** | 40 | Medium | Desktop | 彈窗主標題 |
| **H4** | 32 | Medium | Mobile | 彈窗大標題 |
| **H5** | 28 | Regular | Desktop | 彈窗次標題、頁面次標題 |
| **H6** | 20 | Medium | Mobile | 彈窗次標題 |
| **Subtitle 2**| 20 | Light | Desktop | 彈窗內文 |
| **Body 1** | 16 | Medium/Regular | Desktop | 內文資訊、按鈕 |
| **Body 2** | 14 | Medium/Regular | Mobile | 內文資訊、彈窗內文、按鈕 |
| **Caption 1** | 12 | Regular | Desktop | 輔助資訊 |
| **Caption 2** | 12 | Regular | Mobile | 輔助資訊 |

---

## 📏 開發規範 (Coding Conventions)

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
*Last Updated: 2025.12.16*
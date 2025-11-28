# Formosoul

## 通用原則

　　PascalCase：組件名（e.g., MyComponent）。  
　　camelCase：變數/方法（e.g., myFunction 。  
　　kebab-case：class/事件（e.g., my-class）。  
　　多字詞描述，避免縮寫；布林以 is/has 開頭。  

---

## JS 相關命名

　　變數/Props: camelCase（e.g., userName, userId）。  
　　常數: SNAKE_CASE（e.g., MAX_USERS）。  
　　函式/方法: camelCase，以動詞開頭（e.g., fetchUserData()）。  
　　Events: kebab-case（e.g., 'update-user'）。  
　　Computed等有傳回值的函式: camelCase（e.g., fullName）。  

---

## HTML/CSS 相關命名

　　Class: kebab-case，vue次級組件名稱(雙底線)class原本要取的名字(雙減號)狀態/動作  
　　（e.g., user-profile__name--active）。  
　　ID: kebab-case（e.g., main-content）。  
　　Scoped CSS: kebab-case（e.g., .user-profile { }）。  

---

## 組件命名（均以複合字表示）

　　初級組件（基礎）：大駝峰，以 Base 前綴（e.g., BasicButton.vue）。  
　　次級組件（複合）：大駝峰，以功能描述（e.g., UserProfile.vue）。  
　　模板中使用 kebab-case（e.g., <base-button></base-button>）。  

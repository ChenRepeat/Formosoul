import './assets/style.scss'
import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

/* 1. 引入 FontAwesome 核心 */
import { library } from '@fortawesome/fontawesome-svg-core'

/* 2. 引入 FontAwesome Vue 組件 */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* 3. 引入你想使用的特定圖示 */
// 注意：為了效能，建議只引入有用到的圖示，而不是全部
// import { faUserSecret, faHouse } from '@fortawesome/free-solid-svg-icons'
// import { faTwitter } from '@fortawesome/free-brands-svg-icons'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { fab } from '@fortawesome/free-brands-svg-icons'
import { far } from '@fortawesome/free-regular-svg-icons'

import i18n from './i18n'; // 引用i18N 切換語系

/* 4. 將圖示加入 Library */
library.add(fas,fab,far)

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(i18n)

app.component('font-awesome-icon', FontAwesomeIcon)

app.mount('#app')

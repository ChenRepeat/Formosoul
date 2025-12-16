import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'


// ELEMENT-PLUS 引入插件
//在 <template> 裡寫 <el-xxx>，Vite 就會自動幫你引入該元件和對應的 CSS 樣式。
import AutoImport from "unplugin-auto-import/vite";
import Components from "unplugin-vue-components/vite";
import { ElementPlusResolver } from "unplugin-vue-components/resolvers";

// https://vite.dev/config/
export default defineConfig({
  base: '/tjd103/',
  plugins: [
    vue(),
    vueDevTools(),
    // 設定自動引入
    AutoImport({
      resolvers: [ElementPlusResolver()],
    }),
    Components({
      resolvers: [ElementPlusResolver()],
    }),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },
css: {
    preprocessorOptions: {
      scss: {
        // **移除 path.resolve(__dirname, ...) **
        // 直接使用相对于项目根目录的路径。
        // Vite/Sass 的解析器通常能更好地处理这种相对路径。
        additionalData: `@import "/src/assets/_variables.scss";`,
      }
    }
  },
})

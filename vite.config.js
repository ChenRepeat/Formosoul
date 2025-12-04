import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'


// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
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

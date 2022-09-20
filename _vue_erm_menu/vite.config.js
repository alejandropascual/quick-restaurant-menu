import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import {resolve} from "path";

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  build: {
    //outDir: './../dashboard',
    //assetsDir: 'wp-content/plugins/simple-restaurant-reservations/dashboard',
    sourceMap: 'inline',
    minify: false,
    //chunkSizeWarningLimit: 5000
  },
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src')
    }
  },
})

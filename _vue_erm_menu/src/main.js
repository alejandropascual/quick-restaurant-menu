import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import axios from "axios"
window.axios = axios

//import _ld from 'lodash'

createApp(App).mount('#app')

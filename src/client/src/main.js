import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'

/** outros **/
import './assets/css/_reset.scss'
import './assets/css/bootstrap-grid.min.css'
import './assets/lib/icon-app/style.css'
import './assets/css/tiptap.scss'
import './assets/css/main.scss'

const app = createApp(App)
app.use(createPinia())
app.use(router)
app.mount('#app')



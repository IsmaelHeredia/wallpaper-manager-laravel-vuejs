import './bootstrap';

import { createApp } from 'vue'
import App from "./layouts/app.vue";
import router from './router'
import vuetify from "./vuetify";
import { createPinia } from 'pinia';

import './assets/main.css';

import VueSidebarMenu from 'vue-sidebar-menu'
import 'vue-sidebar-menu/dist/vue-sidebar-menu.css'

import './fontawesome'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import Toast, { POSITION } from 'vue-toastification';
import 'vue-toastification/dist/index.css';

createApp(App)
  .use(router)
  .use(vuetify)
  .use(VueSidebarMenu)
  .component('font-awesome-icon', FontAwesomeIcon)
  .use(createPinia())
  .use(Toast, {
    position: POSITION.TOP_RIGHT,
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    icon: true,
    rtl: false,
  })
  .mount('#app')
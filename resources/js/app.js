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

createApp(App)
  .use(router)
  .use(vuetify)
  .use(VueSidebarMenu)
  .component('font-awesome-icon', FontAwesomeIcon)
  .use(createPinia())
  .mount('#app')
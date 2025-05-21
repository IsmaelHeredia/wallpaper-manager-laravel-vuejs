import { createRouter, createWebHistory } from 'vue-router'
import Layout from '@/layouts/AuthLayout.vue';
import LoginView from '@/views/login/Index.vue';
import LayoutDash from '@/layouts/DashboardLayout.vue';
import DashboardView from '@/views/dashboard/Index.vue';
import WallpapersView from '@/views/dashboard/wallpapers/Index.vue';
import SaveWallpaperView from '@/views/dashboard/wallpapers/Save.vue';
import StatisticsView from '@/views/dashboard/statistics/Index.vue';
import AccountView from '@/views/dashboard/account/Index.vue';
import UsuarioService from '@/services/UsuarioService';
import config from '@/config';

const routes = [
  {
    path: '/',
    component: Layout,
    children: [
      {
        path: '',
        name: 'Login',
        component: LoginView,
        meta: { guestOnly: true },
      }
    ]
  },
  {
    path: '/dashboard',
    component: LayoutDash,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'Dashboard',
        component: DashboardView
      },
      {
        path: 'wallpapers',
        name: 'Wallpapers',
        component: WallpapersView
      },
      {
        path: 'wallpapers/new',
        name: 'NewWallpaper',
        component: SaveWallpaperView
      },
      {
        path: 'wallpapers/:id/save',
        name: 'SaveWallpaper',
        component: SaveWallpaperView
      },
      {
        path: 'statistics',
        name: 'Statistics',
        component: StatisticsView
      },
      {
        path: 'account',
        name: 'Account',
        component: AccountView
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {

  const token = sessionStorage.getItem(config.SESSION_NAME);

  if (to.meta.requiresAuth) {

    if (!token) {
      return next('/');
    }

    try {
      await UsuarioService.validar()
      return next();
    } catch (error) {
      sessionStorage.removeItem(config.SESSION_NAME);
      return next('/');
    }
  }

  if (to.meta.guestOnly && token) {
    try {
      await UsuarioService.validar()
      return next('/dashboard');
    } catch {
      sessionStorage.removeItem(config.SESSION_NAME);
    }
  }

  return next();

});

export default router

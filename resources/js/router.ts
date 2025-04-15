import { createRouter, createWebHistory } from 'vue-router'
import Layout from '@/layouts/AuthLayout.vue';
import LoginView from '@/views/login/Index.vue';
import LayoutDash from '@/layouts/DashboardLayout.vue';
import DashboardView from '@/views/dashboard/Index.vue';
import WallpapersView from '@/views/dashboard/wallpapers/Index.vue';
import SaveWallpaperView from '@/views/dashboard/wallpapers/Save.vue';
import StatisticsView from '@/views/dashboard/statistics/Index.vue';
import AccountView from '@/views/dashboard/account/Index.vue';

const routes = [
  {
    path: '/',
    component: Layout,
    children: [
      {
        path: '',
        name: 'Login',
        component: LoginView
      }
    ]
  },
  {
    path: '/dashboard',
    component: LayoutDash,
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
        path: 'wallpapers/save',
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
  routes
})

export default router

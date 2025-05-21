<template>
  <sidebar-menu v-model:collapsed="collapsed" :menu="menu" :theme="selectedTheme" :show-one-child="true"
    @update:collapsed="onToggleCollapse" @item-click="onItemClick" />
  <div v-if="isOnMobile && !collapsed" class="sidebar-overlay" @click="collapsed = true" />
  <div id="navbarVue" :class="[{ collapsed: collapsed }, { onmobile: isOnMobile }]">
    <router-view />
  </div>

  <v-btn color="primary" class="floating-btn btn-theme" @click="changeTheme()" icon>
    <v-icon :color="theme.global.current.value.colors.onBackground">
      {{ store.mode === 2 ? 'mdi-white-balance-sunny' : 'mdi-weather-night' }}
    </v-icon>
  </v-btn>

  <v-btn color="primary" class="floating-btn btn-info" icon @click="dialog = true">
    <v-icon>mdi-information</v-icon>
  </v-btn>

  <v-btn :to="{ name: 'Account' }" color="primary" class="floating-btn btn-account" icon>
    <v-tooltip location="top">
      <template #activator="{ props }">
        <v-btn v-bind="props" :to="{ name: 'Account' }" color="primary" class="floating-btn btn-account" icon>
          <v-avatar size="40">
            <v-img :src="`/storage/photos/${avatar}`" :alt="username" class="avatar-img" />
          </v-avatar>
        </v-btn>
      </template>
      <span>{{ username }}</span>
    </v-tooltip>
  </v-btn>

  <v-btn :to="{ name: 'Login' }" color="primary" class="floating-btn btn-logout" @click="logout()" icon>
    <v-icon>mdi-logout</v-icon>
  </v-btn>

  <v-dialog v-model="dialog" max-width="400">
    <v-card class="pa-4 text-center">
      <v-card-title>
        <h2>About</h2>
      </v-card-title>
      <v-card-text>
        <h4>Nombre: Wallpapers Manager</h4>
        <h4>Versión: 1.0</h4>
        <h4>Autor: Ismael Heredia</h4>
      </v-card-text>
      <v-card-actions class="justify-center">
        <v-btn color="primary" @click="dialog = false">Cerrar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

</template>

<script setup>
import { useTheme } from "vuetify";
import { themes } from "../stores/themes";
import { ref, onMounted, h } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

import { useRoute } from "vue-router";

import UsuarioService from '@/services/UsuarioService';
import { showToast } from '@/utils/toast';

import config from '@/config';

const route = useRoute();

const store = themes();
const theme = useTheme();
const collapsed = ref(false);
const isOnMobile = ref(false);
const dialog = ref(false);

const username = ref('');
const avatar = ref('');

const changeTheme = () => {
  store.toogle();
  let themeName = store.themeName;
  theme.global.name.value = themeName;

  if (themeName === "light") {
    selectedTheme.value = "white-theme";
  } else {
    selectedTheme.value = "";
  }
};

const selectedTheme = ref("");

const separator = h("hr", {
  style: {
    borderColor: "rgba(0, 0, 0, 0.1)",
    margin: "20px",
  },
});

const faIcon = (props) => {
  return {
    element: h("div", [h(FontAwesomeIcon, { size: "lg", ...props })]),
  };
};

const menu = ref([
  {
    href: "/dashboard",
    title: "Inicio",
    icon: faIcon({ icon: "fa-solid fa-house" }),
    isActive: () => route.path === "/dashboard"
  },
  {
    href: "/dashboard/wallpapers",
    title: "Wallpapers",
    icon: faIcon({ icon: "fa-solid fa-image" }),
    isActive: () => route.path.startsWith("/dashboard/wallpapers")
  },
  {
    href: "/dashboard/statistics",
    title: "Estadisticas",
    icon: faIcon({ icon: "fa-solid fa-chart-bar" }),
    isActive: () => route.path.startsWith("/dashboard/statistics")
  }
]);

const onToggleCollapse = () => {
};

const onItemClick = (event, item) => {
};

const onResize = () => {
  isOnMobile.value = window.innerWidth <= 767;
  collapsed.value = isOnMobile.value;
};

onMounted(async () => {
  onResize();
  window.addEventListener("resize", onResize);
  let response = await UsuarioService.validar();
  let datos = response.data;
  username.value = datos.name;
  avatar.value = datos.photo;
});

const logout = () => {

  sessionStorage.setItem(config.SESSION_NAME, '');

  showToast({
    message: 'La sesión fue cerrada',
    type: 'success',
    timeout: 2000,
    redirectTo: '/'
  });

};

</script>

<style scoped></style>
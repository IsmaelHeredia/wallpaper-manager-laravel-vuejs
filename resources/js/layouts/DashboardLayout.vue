<template>
  <sidebar-menu v-model:collapsed="collapsed" :menu="menu" :theme="selectedTheme" :show-one-child="true"
    @update:collapsed="onToggleCollapse" @item-click="onItemClick" />
  <div v-if="isOnMobile && !collapsed" class="sidebar-overlay" @click="collapsed = true" />
  <div id="navbarVue" :class="[{ collapsed: collapsed }, { onmobile: isOnMobile }]">
    <router-view />
  </div>

  <v-btn color="primary" class="theme-toggle btn-theme" @click="changeTheme()" icon>
    <v-icon :color="theme.global.current.value.colors.onBackground">
      {{ store.mode === 2 ? 'mdi-white-balance-sunny' : 'mdi-weather-night' }}
    </v-icon>
  </v-btn>

  <v-btn color="primary" class="floating-btn btn-info" icon @click="dialog = true">
    <v-icon>mdi-information</v-icon>
  </v-btn>

  <v-btn :to="{ name: 'Account' }" color="primary" class="floating-btn btn-account" icon>
    <v-avatar size="40">
      <img src="http://localhost:8000/storage/photos/saitama.png" alt="Perfil" class="avatar-img" />
    </v-avatar>
  </v-btn>

  <v-btn :to="{ name: 'Login' }" color="primary" class="floating-btn btn-logout" icon>
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

const route = useRoute();

const store = themes();
const theme = useTheme();
const collapsed = ref(false);
const isOnMobile = ref(false);
const dialog = ref(false);

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

onMounted(() => {
  onResize();
  window.addEventListener("resize", onResize);
});
</script>

<style scoped></style>
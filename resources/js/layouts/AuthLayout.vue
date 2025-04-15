<script setup lang="ts">
import { useTheme } from "vuetify";
import { VSonner } from "vuetify-sonner";
import "vuetify-sonner/style.css";
import { themes } from "../stores/themes";

const store = themes();
const theme = useTheme();

const changeTheme = () => {
  store.toogle();
  theme.global.name.value = store.themeName;
};
</script>

<template>
  <v-app :style="{ backgroundColor: theme.global.current.value.colors.background }">
    <VSonner />
    <v-container>
      <router-view />
    </v-container>

    <div class="botones-theme">
      <v-btn @click="changeTheme()" icon>
        <v-icon :color="theme.global.current.value.colors.onBackground">
          {{ store.mode === 2 ? 'mdi-white-balance-sunny' : 'mdi-weather-night' }}
        </v-icon>
      </v-btn>
    </div>
  </v-app>
</template>

<style scoped lang="scss">
html, body, #app {
  height: 100vh;
  margin: 0;
  padding: 0;
}
</style>

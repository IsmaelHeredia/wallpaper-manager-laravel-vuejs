<template>
  <div class="ingreso">
    <v-form ref="formIngreso" @submit.prevent="validarIngreso">
      <v-card elevation="24">
        <v-card-title class="headline black text-center card-title" primary-title>
          Ingreso
        </v-card-title>
        <v-card-text class="pa-5">
          <v-text-field label="Email" v-model="email" :rules="emailRules"></v-text-field>
          <v-text-field type="password" label="Clave" v-model="clave" :rules="claveRules"></v-text-field>
        </v-card-text>
        <v-card-actions class="pa-5 center-div">
          <v-btn variant="elevated" type="submit" color="primary" class="boton-pequeño" :disabled="isLoading">
            <v-icon>mdi-login</v-icon>
            Ingresar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
  </div>
</template>

<script>
import UsuarioService from '@/services/UsuarioService';
import { showToast } from '@/utils/toast';

import config from '@/config';

export default {
  data() {
    return {
      email: '',
      clave: '',
      isLoading: false,
      emailRules: [
        v => !!v || 'El email es requerido'
      ],
      claveRules: [
        v => !!v || 'La clave es requerida'
      ],
    };
  },
  methods: {
    async validarIngreso() {

      if (!this.$refs.formIngreso.validate()) return;

      this.isLoading = true;

      try {

        const response = await UsuarioService.login({
          email: this.email,
          password: this.clave
        });

        const token = response.data.access_token;

        sessionStorage.setItem(config.SESSION_NAME, token);

        showToast({
          message: 'Ingreso exitoso',
          type: 'success',
          timeout: 2000,
          redirectTo: '/dashboard'
        });

      } catch (error) {

        showToast({
          message: 'Credenciales incorrectas',
          type: 'error',
          timeout: 2000
        });

      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>

<style scoped>
.ingreso {
  max-width: 400px;
  margin: 40px auto;
}

.center-div {
  display: flex;
  justify-content: center;
  width: 100%;
}

.boton-pequeño {
  min-width: 120px;
}
</style>

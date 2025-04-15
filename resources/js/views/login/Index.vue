<template>
  <div class="ingreso">
    <v-form ref="formIngreso" @submit.prevent="validarIngreso">
      <v-card elevation="24">
        <v-card-title class="headline black text-center card-title" primary-title>
          Ingreso
        </v-card-title>
        <v-card-text class="pa-5">
          <v-text-field label="Usuario" v-model="nombre" :rules="nombreRules"></v-text-field>
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
export default {
  data() {
    return {
      nombre: '',
      clave: '',
      isLoading: false,
      nombreRules: [
        v => !!v || 'El usuario es requerido',
        v => (v && v.length >= 3) || 'El usuario debe tener al menos 3 caracteres',
      ],
      claveRules: [
        v => !!v || 'La clave es requerida',
        v => (v && v.length >= 6) || 'La clave debe tener al menos 6 caracteres',
      ],
    };
  },
  methods: {
    async validarIngreso() {
      if (this.$refs.formIngreso.validate()) {
        this.isLoading = true;
        // ...
        this.isLoading = false;
      }
    },
  },
};
</script>

<style scoped>
</style>
<template>
  
    <div class="form-wallpaper">
      <v-form ref="formGuardar" @submit.prevent="validarGuardar">
        <v-card elevation="24">
          <v-card-title class="headline black text-center card-title" primary-title>
            Gestion de wallpaper
          </v-card-title>
          <v-card-text class="pa-5">
  
            <v-text-field label="Nombre" v-model="nombre" :rules="nombreRules"></v-text-field>
  
            <v-row dense>
              <v-col cols="12" md="6" sm="6">
                <v-file-input label="Seleccione una imagen" v-model="imagen" outlined dense @change="onFileChange"
                  accept="image/png, image/jpeg, image/bmp" :rules="imagenRules"></v-file-input>
              </v-col>
              <v-col cols="12" md="6" sm="6">
                <v-img class="imagen-preview" :src="imagePreview" v-if="imagePreview != null" />
              </v-col>
            </v-row>
      
            <v-row dense class="form-input-calificacion">
              <div class="text-h6 text-calificacion">Calificación</div>
              <v-rating v-model="calificacion" :rules="calificacionRules" class="rating-form custom-rating" hover
                :length="5" :size="32" active-color="primary" />
            </v-row>
    
            <v-autocomplete clearable chips label="Estaciones" v-model="generos" :items="generosLista" item-title="nombre"
              item-value="id" multiple :rules="generosRules"></v-autocomplete>
  
              <v-autocomplete clearable chips label="Horarios" v-model="generos" :items="generosLista" item-title="nombre"
              item-value="id" multiple :rules="generosRules"></v-autocomplete>

          </v-card-text>
          <v-card-actions class="pa-5 center-div">
            <v-btn variant="elevated" type="submit" color="primary" class="boton-largo" :disabled=isLoading><v-icon
                class="icono-boton">mdi-floppy</v-icon>
              Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </div>
  </template>
  
  <script lang="ts">
  
  import { toast } from 'vuetify-sonner'
    
  import { isProxy, toRaw } from 'vue';
  
  import { generateToast } from "@/utils/functions";
  import router from "@/router";
  
  export default {
    data: () => ({
      session_name: import.meta.env.VITE_SESSION_NAME,
      api_url_imagenes: import.meta.env.VITE_API_URL_IMAGES,
      nombre: '',
      nombreRules: [
        (value: any) => {
          if (value) return true
          return 'El nombre es obligatorio'
        },
      ],
      imagen: null as any,
      imagenRules: [
        (value: File[] | undefined) => {
          if (value && value.length > 0) return true
          return 'La imagen es obligatoria'
        },
      ],
      imagePreview: null as any,
      calificacion: 3,
      calificacionRules: [
        (value: any) => {
          if (value) return true
          return 'La calificación es obligatoria'
        },
      ],
      isLoading: false
    }),
    created: async function () {
  
      const params_id = this.$route.params.id;
    
      if (typeof params_id !== "undefined") {
  
        this.isLoading = true;
  
      }
  
    },
    methods: {
      async volver() {
        window.history.back();
      },
      async validarGuardar() {
  
        const { valid } = await (this.$refs.formGuardar as any).validate();
  
        if (valid) {  
        }
  
      },
      onFileChange(file: any) {
        if (!file) {
          return;
        }
        this.imagePreview = URL.createObjectURL(this.imagen);
      }
    },
  }
  </script>
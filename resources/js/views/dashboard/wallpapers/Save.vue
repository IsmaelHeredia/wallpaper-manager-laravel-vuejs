<template>
  <div class="form-wallpaper">
    <v-form ref="formGuardar" @submit.prevent="validarGuardar">
      <v-card elevation="24">
        <v-card-title class="headline black text-center card-title" primary-title>
          Gestion de wallpaper
        </v-card-title>

        <v-card-text class="pa-5">
          <v-text-field label="Nombre" v-model="nombre" :rules="nombreRules" />

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

          <v-row dense>
            <v-col cols="12" sm="6">
              <v-autocomplete clearable chips label="Estaciones" v-model="estaciones" :items="estacionesLista"
                item-title="nombre" item-value="id" multiple />
            </v-col>
            <v-col cols="12" sm="6">
              <v-autocomplete clearable chips label="Horarios" v-model="horarios" :items="horariosLista"
                item-title="nombre" item-value="id" multiple />
            </v-col>
          </v-row>

          <v-checkbox v-model="esFavorita" label="Es favorita" color="primary" />

        </v-card-text>

        <v-card-actions class="center-div">
          <v-btn variant="elevated" type="submit" color="primary" class="boton-largo" :disabled="isLoading">
            <v-icon class="icono-boton">mdi-floppy</v-icon>
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
  </div>
</template>

<script lang="ts">
import { toast } from 'vuetify-sonner';
import { isProxy, toRaw } from 'vue';
import EstacionService from '@/services/EstacionService';
import HorarioService from '@/services/HorarioService';
import WallpaperService from '@/services/WallpaperService';
import { showToast } from '@/utils/toast';

export default {
  data: () => ({
    nombre: '',
    imagen: null as any,
    imagePreview: null as any,
    calificacion: 3,
    estaciones: [],
    horarios: [],
    estacionesLista: [],
    horariosLista: [],
    esFavorita: false,
    isLoading: false,
    esEdicion: false,

    nombreRules: [(v: any) => !!v || 'El nombre es obligatorio'],
    calificacionRules: [(v: any) => !!v || 'La calificación es obligatoria'],
  }),

  computed: {
    imagenRules(): ((value: any) => true | string)[] {
      return [
        (value: File | null) => {
          if (this.esEdicion && this.imagePreview && !this.imagen) {
            return true;
          }

          if (value instanceof File || (value && typeof value === 'object')) {
            return true;
          }

          return 'La imagen es obligatoria';
        },
      ];
    },
  },

  async created() {

    await this.cargarEstaciones();
    await this.cargarHorarios();

    const id = Number(this.$route.params.id);

    if (id) {

      this.isLoading = true;

      this.esEdicion = true;

      const data = await WallpaperService.obtener(id);

      this.nombre = data.nombre;
      this.calificacion = data.calificacion;
      this.estaciones = data.estaciones.map((e: any) => e.id);
      this.horarios = data.horarios.map((h: any) => h.id);

      const url_imagen = `/storage/wallpapers/${data.imagen}`;

      fetch(url_imagen)
        .then((res) => res.blob())
        .then((myBlob) => {
          const file = new File([myBlob], data.imagen, { type: myBlob.type });
          this.imagen = file;
          this.imagePreview = URL.createObjectURL(this.imagen);
        });

      this.esFavorita = data.es_favorita;

      this.isLoading = false;
    }
  },

  methods: {
    async cargarEstaciones() {
      const data = await EstacionService.listar();
      this.estacionesLista = data;
    },

    async cargarHorarios() {
      const data = await HorarioService.listar();
      this.horariosLista = data;
    },

    onFileChange(file: any) {
      if (file) {
        this.imagePreview = URL.createObjectURL(this.imagen);
      }
    },

    async validarGuardar() {
      const { valid } = await (this.$refs.formGuardar as any).validate();

      if (!valid) return;

      this.guardarWallpaper();
    },

    async guardarWallpaper() {

      this.isLoading = true;

      const formData = new FormData();

      formData.append('nombre', this.nombre);
      formData.append('calificacion', this.calificacion.toString());

      toRaw(this.estaciones).forEach((id: number) => {
        formData.append('estaciones_ids[]', id.toString());
      });

      toRaw(this.horarios).forEach((id: number) => {
        formData.append('horarios_ids[]', id.toString());
      });

      formData.append('es_favorita', this.esFavorita ? '1' : '0');

      if (this.imagen && this.imagen instanceof File) {
        formData.append('imagen', this.imagen);
      }

      const id = Number(this.$route.params.id);

      try {

        if (id) {

          await WallpaperService.actualizar(id, formData);

          showToast({
            message: 'Wallpaper actualizado correctamente',
            type: 'success',
            timeout: 2000,
            redirectTo: '/dashboard/wallpapers'
          });

        } else {

          await WallpaperService.crear(formData)

          showToast({
            message: 'Wallpaper creado correctamente',
            type: 'success',
            timeout: 2000,
            redirectTo: '/dashboard/wallpapers'
          });

        }

      } catch (error) {

        showToast({
          message: 'Ocurrió un error al guardar el wallpaper',
          type: 'error',
          timeout: 2000,
        });

      } finally {
        this.isLoading = false;
      }
    }
  },
}
</script>

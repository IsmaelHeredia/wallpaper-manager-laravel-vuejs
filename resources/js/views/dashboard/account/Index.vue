<template>
    <div class="cuenta">
        <v-form ref="formCuenta" @submit.prevent="validarCuenta">
            <v-card elevation="24">
                <v-card-title class="headline black text-center card-title" primary-title>
                    Actualizar datos de cuenta
                </v-card-title>
                <v-card-text class="pa-5">

                    <v-text-field label="Email" v-model="email" :rules="emailRules" />

                    <v-row dense>
                        <v-col cols="12" md="6" sm="6">
                            <v-file-input label="Seleccione una imagen" v-model="imagen" outlined dense
                                @change="onFileChange" accept="image/png, image/jpeg, image/bmp" />
                        </v-col>
                        <v-col cols="12" md="6" sm="6">
                            <v-img class="imagen-preview" :src="imagePreview" v-if="imagePreview != null" />
                        </v-col>
                    </v-row>

                    <v-text-field type="password" label="Clave actual" v-model="clave" :rules="claveRules" />

                    <v-text-field type="password" label="Nueva clave" v-model="nueva_clave" />

                </v-card-text>
                <v-card-actions class="pa-5 center-div">
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
import UsuarioService from '@/services/UsuarioService';
import { showToast } from '@/utils/toast';
import config from '@/config';

export default {
    data: () => ({
        usuario: '',
        email: '',
        clave: '',
        nueva_clave: '',
        imagen: null as File | null,
        imagePreview: null as string | null,
        isLoading: false,

        emailRules: [(v: any) => !!v || 'El email es obligatorio'],
        claveRules: [(v: any) => !!v || 'La clave es obligatoria'],
        nuevaclaveRules: [(v: any) => !!v || 'La clave actual es obligatoria'],
    }),

    async created() {

        let response = await UsuarioService.validar();
        let datos = response.data;

        if (datos.photo) {

            const url_imagen = `/storage/photos/${datos.photo}`;

            fetch(url_imagen)
                .then((res) => res.blob())
                .then((myBlob) => {
                    const file = new File([myBlob], datos.photo, { type: myBlob.type });
                    this.imagen = file;
                    this.imagePreview = URL.createObjectURL(this.imagen);
                });
        }

        this.email = datos.email;

    },

    methods: {
        onFileChange(file: File) {
            if (file) {
                this.imagePreview = URL.createObjectURL(file);
            }
        },

        async validarCuenta() {
            const { valid } = await (this.$refs.formCuenta as any).validate();

            if (!valid) return;

            await this.actualizarCuenta();
        },

        async actualizarCuenta() {

            this.isLoading = true;

            const formData = new FormData();

            formData.append('email', this.email);
            formData.append('currentPassword', this.clave);

            if (this.nueva_clave) {
                formData.append('password', this.nueva_clave);
            }

            if (this.imagen && this.imagen instanceof File) {
                formData.append('photo', this.imagen);
            }

            try {

                await UsuarioService.actualizarCuenta(formData);

                sessionStorage.setItem(config.SESSION_NAME, '');

                showToast({
                    message: 'Cuenta actualizada correctamente',
                    type: 'success',
                    timeout: 2000,
                    redirectTo: '/'
                });

            } catch (error) {

                showToast({
                    message: 'Error al actualizar la cuenta',
                    type: 'error',
                    timeout: 2000,
                });

            } finally {
                this.isLoading = false;
            }
        },
    },
}
</script>
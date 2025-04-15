<template>
    <v-btn :to="{ name: 'SaveWallpaper' }" variant="elevated" type="submit" color="primary"
        class="boton-nuevo"><v-icon>mdi-plus</v-icon></v-btn>

    <v-row class="filtros">
        <v-col cols="4">
            <v-text-field v-model="buscarNombre" label="Ingrese nombre" class="filtro-nombre" />
        </v-col>
        <v-col cols="2">
            <v-autocomplete clearable chips label="Estaciones" v-model="estaciones" :items="estacionesLista"
                item-title="nombre" item-value="id" multiple>
                <template v-slot:chip="{ props, item, index }">
                    <v-chip v-if="estaciones.length <= 3" v-bind="props" small>
                        {{ item.title }}
                    </v-chip>
                    <span v-if="index === 1 && estaciones.length > 3">
                        + 3 estaciones seleccionados
                    </span>
                </template>
            </v-autocomplete>
        </v-col>
        <v-col cols="2">
            <v-select label="Horario" :items="horarios" item-title='nombre' item-value='id'
                v-model="horario_id"></v-select>
        </v-col>
        <v-col cols="4" style="display: inline-block;">
            <v-btn variant="elevated" type="submit" color="primary"
                class="boton-filtrar"><v-icon>mdi-magnify</v-icon></v-btn>
            <v-btn variant="elevated" type="submit" color="primary"
                class="boton-filtrar"><v-icon>mdi-close</v-icon></v-btn>
        </v-col>
    </v-row>

    <div class="paginas-wallpapers">
        <div class="left-wallpapers">
            Página 1 / 1
        </div>
        <div class="right-wallpapers">
            <v-btn-toggle>
                <v-btn :disabled="actual == 1">
                    <v-icon>mdi-arrow-collapse-left</v-icon>
                </v-btn>

                <v-btn :disabled="actual == 1">
                    <v-icon>mdi-arrow-left</v-icon>
                </v-btn>

                <v-btn :disabled="actual == paginas">
                    <v-icon>mdi-arrow-right</v-icon>
                </v-btn>

                <v-btn :disabled="actual == paginas">
                    <v-icon>mdi-arrow-collapse-right</v-icon>
                </v-btn>
            </v-btn-toggle>
        </div>
    </div>

</template>

<script lang="ts">

import { toast } from 'vuetify-sonner';

import { generateToast } from "@/utils/functions";

import { isProxy, toRaw } from 'vue';

export default {

    data: () => ({
        buscarNombre: "",
        total: 0,
        paginas: 0,
        actual: 0,
        anterior: 0,
        siguiente: 0,
        session_name: import.meta.env.VITE_SESSION_NAME,
        images_url: import.meta.env.VITE_API_URL_IMAGES,
        isLoading: false
    }),
    created: async function () {
    },
    methods: {
    },
}
</script>

<style scoped></style>
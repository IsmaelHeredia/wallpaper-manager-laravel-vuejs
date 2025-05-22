<template>
    <v-row class="filtros">
        <v-col cols="4">
            <v-text-field v-model="buscarNombre" label="Ingrese nombre" class="filtro-nombre" />
        </v-col>
        <v-col cols="3">
            <v-autocomplete clearable chips label="Estaciones" v-model="estaciones" :items="estacionesLista"
                item-title="nombre" item-value="id" multiple>
                <template v-slot:chip="{ props, item, index }">
                    <v-chip v-if="estaciones.length <= 1" v-bind="props" small>
                        {{ item.title }}
                    </v-chip>
                    <span v-if="index === 1 && estaciones.length > 1">
                        + 1
                    </span>
                </template>
            </v-autocomplete>
        </v-col>
        <v-col cols="3">
            <v-autocomplete clearable chips label="Horarios" v-model="horarios" :items="horariosLista"
                item-title="nombre" item-value="id" multiple>
                <template v-slot:chip="{ props, item, index }">
                    <v-chip v-if="horarios.length <= 1" v-bind="props" small>
                        {{ item.title }}
                    </v-chip>
                    <span v-if="index === 1 && horarios.length > 1">
                        + 1
                    </span>
                </template>
            </v-autocomplete>
        </v-col>
        <v-col cols="2" style="display: inline-block;">
            <v-btn variant="elevated" type="submit" color="primary" class="boton-filtrar" rounded="xl" @click="filtrar">
                <v-icon>mdi-magnify</v-icon>
            </v-btn>
        </v-col>
    </v-row>

    <div class="listado-home">
        <v-container fluid grid-list-md>
            <div v-if="wallpapers.length">
                <v-row class="pa-2">
                    <v-col v-for="(wallpaper) in wallpapers" :key="wallpaper.id" class="d-flex child-flex" cols="4">
                        <div @click="cargarWallpaper(wallpaper.id)" style="cursor: pointer; width: 100%;">
                            <v-img class="align-end flash imagen-portada"
                                :src="`/storage/wallpapers/${wallpaper.imagen}`" contain aspect-ratio="1.6"
                                height="100%" max-height="500" v-tooltip:top="wallpaper.nombre"
                                :to="{ name: 'SaveWallpaper', params: { id: wallpaper.id } }" />
                        </div>
                    </v-col>
                </v-row>
            </div>
            <div v-else>
                <div class="text-h6 text-md-h5 text-lg-h4 text-center">No se encontraron registros</div>
            </div>
        </v-container>
    </div>

    <div class="paginas-wallpapers">
        <div class="left-wallpapers">
            Página {{ store.paginacion.pagina }} / {{ totalPaginas }}
        </div>
        <div class="right-wallpapers">
            <v-btn-toggle>
                <v-btn :disabled="store.paginacion.pagina == 1" @click="cargarPrimeraPagina">
                    <v-icon>mdi-arrow-collapse-left</v-icon>
                </v-btn>
                <v-btn :disabled="store.paginacion.pagina == 1" @click="cargarPaginaAnterior">
                    <v-icon>mdi-arrow-left</v-icon>
                </v-btn>
                <v-btn :disabled="store.paginacion.pagina == totalPaginas" @click="cargarPaginaSiguiente">
                    <v-icon>mdi-arrow-right</v-icon>
                </v-btn>
                <v-btn :disabled="store.paginacion.pagina == totalPaginas" @click="cargarUltimaPagina">
                    <v-icon>mdi-arrow-collapse-right</v-icon>
                </v-btn> </v-btn-toggle>
        </div>
    </div>

</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useWallpaperStore } from '@/stores/wallpaper';
import EstacionService from '@/services/EstacionService';
import HorarioService from '@/services/HorarioService';
import WallpaperService from '@/services/WallpaperService';

import { useRouter } from 'vue-router';
const router = useRouter();

import { toRaw } from 'vue';

const store = useWallpaperStore();

const buscarNombre = ref(store.filtros.nombre);
const estaciones = ref(store.filtros.estaciones);
const horarios = ref(store.filtros.horarios);

const estacionesLista = ref([]);
const horariosLista = ref([]);
const wallpapers = ref([]);
const totalPaginas = ref(1);

onMounted(async () => {
    cargarDesdeStore();
})

function cargarDesdeStore() {
    buscarNombre.value = store.filtros.nombre;
    estaciones.value = store.filtros.estaciones;
    horarios.value = store.filtros.horarios;
    cargarEstaciones();
    cargarHorarios();
    obtenerWallpapers();
}

async function cargarEstaciones() {
    let data = await EstacionService.listar();
    estacionesLista.value = data;
}

async function cargarHorarios() {
    let data = await HorarioService.listar();
    horariosLista.value = data;
}

async function obtenerWallpapers() {

    let params = {
        nombre: store.filtros.nombre,
        estaciones_ids: toRaw(store.filtros.estaciones),
        horarios_ids: toRaw(store.filtros.horarios),
        page: store.paginacion.pagina,
    };

    let data = await WallpaperService.buscar(params);
    wallpapers.value = data.data;
    totalPaginas.value = data.last_page;
}

function filtrar() {
    store.setFiltros({
        nombre: buscarNombre.value,
        estaciones: estaciones.value,
        horarios: horarios.value,
    });
    obtenerWallpapers();
}

function cargarPaginaAnterior() {
    if (store.paginacion.pagina > 1) {
        let pagina = store.paginacion.pagina - 1;
        store.setPagina({ pagina });
        obtenerWallpapers();
    }
}

function cargarPaginaSiguiente() {
    if (store.paginacion.pagina < totalPaginas.value) {
        let pagina = store.paginacion.pagina + 1;
        store.setPagina({ pagina });
        obtenerWallpapers();
    }
}

function cargarPrimeraPagina() {
    let pagina = 1;
    store.setPagina({ pagina });
    obtenerWallpapers();
}

function cargarUltimaPagina() {
    let pagina = totalPaginas.value;
    store.setPagina({ pagina });
    obtenerWallpapers();
}

function cargarWallpaper(id) {
    router.push({ name: 'SaveWallpaper', params: { id } });
}
</script>

<style scoped></style>
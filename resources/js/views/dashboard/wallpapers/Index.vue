<template>

    <v-btn :to="{ name: 'NewWallpaper' }" variant="elevated" type="submit" color="primary" class="boton-nuevo"
        rounded="xl"><v-icon>mdi-plus</v-icon></v-btn>

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

    <v-dialog v-model="dialogEliminar" max-width="600">
        <v-card>
            <v-card-title class="text-h6 text-center">Confirmación de eliminación</v-card-title>
            <v-card-text class="text-center">¿ Desea borrar el wallpaper "{{ confirmarWallpaperBorrar?.nombre
                }}" ?</v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="grey" variant="text" @click="dialogEliminar = false">Cancelar</v-btn>
                <v-btn color="red" variant="elevated" @click="eliminarWallpaper">Eliminar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <div class="listado-wallpapers">
        <v-table>
            <thead>
                <tr>
                    <th class="text-center">
                        Nombre
                    </th>
                    <th class="text-center">
                        Imagen
                    </th>
                    <th class="text-center">
                        Calificación
                    </th>
                    <th class="text-center">
                        Horarios
                    </th>
                    <th class="text-center">
                        Estaciones
                    </th>
                    <th class="text-center">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="wallpaper in wallpapers" :key="wallpaper.id">
                    <td>{{ wallpaper.nombre }}</td>
                    <td>
                        <img class="imagen" :src="`/storage/wallpapers/${wallpaper.imagen}`" />
                    </td>
                    <td>
                        <v-rating hover readonly :length="5" :size="32" :model-value="wallpaper.calificacion"
                            active-color="primary" class="custom-rating" />
                    </td>
                    <td>
                        <v-chip v-for="horario_chip in wallpaper.horarios" :key="horario_chip.id" class="chip-horario">
                            {{ horario_chip.nombre }}
                        </v-chip>
                    </td>
                    <td>
                        <v-chip v-for="estacion_chip in wallpaper.estaciones" :key="estacion_chip.id"
                            class="chip-estacion">
                            {{ estacion_chip.nombre }}
                        </v-chip>
                    </td>
                    <td>
                        <div class="acciones-wrapper">
                            <v-btn class="boton-icono-tabla" density="compact" size="x-large"
                                :to="{ name: 'SaveWallpaper', params: { id: wallpaper.id } }">
                            <v-icon>mdi-pencil</v-icon>
                            </v-btn>
                            <v-btn class="boton-icono-tabla" density="compact" size="x-large"
                                @click="confirmarEliminar(wallpaper)">
                            <v-icon>mdi-delete</v-icon>
                            </v-btn>
                        </div>
                    </td>
                </tr>
            </tbody>
        </v-table>
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
                </v-btn>
            </v-btn-toggle>
        </div>
    </div>

</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useWallpaperStore } from '@/stores/wallpaper';
import EstacionService from '@/services/EstacionService';
import HorarioService from '@/services/HorarioService';
import WallpaperService from '@/services/WallpaperService';

import { toRaw } from 'vue';

import { showToast } from '@/utils/toast';

const store = useWallpaperStore();

const buscarNombre = ref(store.filtros.nombre);
const estaciones = ref(store.filtros.estaciones);
const horarios = ref(store.filtros.horarios);

const estacionesLista = ref([]);
const horariosLista = ref([]);
const wallpapers = ref([]);
const totalPaginas = ref(1);

const dialogEliminar = ref(false);
const confirmarWallpaperBorrar = ref(null);

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
    let data = await WallpaperService.buscar({
        nombre: store.filtros.nombre,
        estaciones_ids: toRaw(store.filtros.estaciones),
        horarios_ids: toRaw(store.filtros.horarios),
        page: store.paginacion.pagina,
    });
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

function confirmarEliminar(wallpaper) {
    confirmarWallpaperBorrar.value = wallpaper;
    dialogEliminar.value = true;
}

async function eliminarWallpaper() {
    try {

        await WallpaperService.eliminar(confirmarWallpaperBorrar.value.id);

        dialogEliminar.value = false;

        showToast({
            message: 'El wallpaper fue eliminado',
            type: 'success',
            timeout: 2000
        });

        obtenerWallpapers();

    } catch (error) {
        console.error("Error al eliminar:", error);
    }
}

</script>

<style scoped></style>
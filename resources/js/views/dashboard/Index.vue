<template>

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
            <v-btn variant="elevated" type="submit" color="primary" class="boton-filtrar"
                ><v-icon>mdi-magnify</v-icon></v-btn>
            <v-btn variant="elevated" type="submit" color="primary" class="boton-filtrar"
                ><v-icon>mdi-close</v-icon></v-btn>
        </v-col>
    </v-row>

    <div class="listado-registros">
        <v-container fluid grid-list-md>
            <div v-if="wallpapers.length">
                <v-row class="pa-2">
                    <v-col v-for="(wallpaper) in wallpapers" :key="wallpaper.id" class="d-flex child-flex" cols="4">
                        <v-img class="align-end flash imagen-portada"
                            :src="`http://localhost:8000/storage/wallpapers/${wallpaper.imagen}`" contain aspect-ratio="1.6"
                            height="100%" max-height="500" v-tooltip:top="wallpaper.nombre" />
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

<script setup>
import { ref } from 'vue';

const wallpapers = ref(
    Array.from({ length: 30 }, (_, index) => ({
        id: index + 1,
        nombre: `Wallpaper ${index + 1}`,
        imagen: "solar.jpg",
    }))
);

const buscarNombre = ref('');
const estaciones = ref([]);

const estacionesLista = ref([
    { id: 1, nombre: 'Verano' },
    { id: 2, nombre: 'Otoño' },
    { id: 3, nombre: 'Invierno' },
    { id: 4, nombre: 'Primavera' }
]);

const horario_id = ref(null);
const horarios = ref([
    { id: 1, nombre: 'Amanecer' },
    { id: 2, nombre: 'Mañana' },
    { id: 3, nombre: 'Tarde' },
    { id: 4, nombre: 'Noche' }
]);

</script>

<style scoped>
</style>

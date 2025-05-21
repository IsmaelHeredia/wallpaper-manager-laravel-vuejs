<template>
    <div class="graficos">
        <v-row>
            <v-col cols="12" md="6">
                <v-card>
                    <v-card-title>Wallpapers por Estación</v-card-title>
                    <v-card-text>
                        <DoughnutChart :data="chartDataEstaciones" />
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" md="6">
                <v-card>
                    <v-card-title>Wallpapers por Horario</v-card-title>
                    <v-card-text>
                        <DoughnutChart :data="chartDataHorarios" />
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { ChartData } from 'chart.js';
import DoughnutChart from '@/components/DoughnutChart.vue';
import WallpaperService from '@/services/WallpaperService';

type DoughnutChartData = ChartData<'doughnut', number[], string>;

const chartDataEstaciones = ref<DoughnutChartData | null>(null);
const chartDataHorarios = ref<DoughnutChartData | null>(null);

onMounted(async () => {
    const datosGraficos = await WallpaperService.generarReporte();

    chartDataEstaciones.value = {
        labels: datosGraficos.por_estacion.map((e: any) => e.nombre),
        datasets: [
            {
                label: 'Wallpapers',
                data: datosGraficos.por_estacion.map((e: any) => e.cantidad),
                backgroundColor: ['#3f51b5', '#e91e63', '#4caf50', '#ff9800', '#00bcd4'],
            },
        ],
    };

    chartDataHorarios.value = {
        labels: datosGraficos.por_horario.map((h: any) => h.nombre),
        datasets: [
            {
                label: 'Wallpapers',
                data: datosGraficos.por_horario.map((h: any) => h.cantidad),
                backgroundColor: ['#673ab7', '#009688', '#f44336', '#8bc34a', '#ffc107'],
            },
        ],
    };
});
</script>
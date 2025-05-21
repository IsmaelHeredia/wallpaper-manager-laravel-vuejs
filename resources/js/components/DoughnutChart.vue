<template>
    <Doughnut v-if="data" :data="data" :options="chartOptions" />
</template>

<script setup>
import { Doughnut } from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement)

defineProps(['data'])

const chartOptions = {
    responsive: true,
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                font: {
                    color: '#cccccc',
                    size: 13,         
                },
            },
        },
    },
};
</script>
<template>
    <div class="chart-container">
        <canvas :id="chartId" :width="width" :height="height"></canvas>
    </div>
</template>

<script setup lang="ts">
import { CategoryScale, Chart as ChartJS, Filler, Legend, LinearScale, LineController, LineElement, PointElement, Title, Tooltip } from 'chart.js';
import { onMounted, onUnmounted, ref, watch } from 'vue';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, LineController, Title, Tooltip, Legend, Filler);

interface Props {
    chartId: string;
    width?: number;
    height?: number;
    labels: string[];
    data: number[];
    title?: string;
    color?: string;
}

const props = withDefaults(defineProps<Props>(), {
    width: 400,
    height: 200,
    color: '#3B82F6',
});

const chartInstance = ref<ChartJS | null>(null);

const createChart = () => {
    const ctx = document.getElementById(props.chartId) as HTMLCanvasElement;
    if (!ctx) return;

    chartInstance.value = new ChartJS(ctx, {
        type: 'line',
        data: {
            labels: props.labels,
            datasets: [
                {
                    label: props.title || 'Dados',
                    data: props.data,
                    borderColor: props.color,
                    backgroundColor: props.color + '20',
                    tension: 0.1,
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: !!props.title,
                },
                title: {
                    display: !!props.title,
                    text: props.title,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                    },
                },
            },
        },
    });
};

const updateChart = () => {
    if (chartInstance.value) {
        chartInstance.value.data.labels = props.labels;
        chartInstance.value.data.datasets[0].data = props.data;
        chartInstance.value.update();
    }
};

onMounted(() => {
    createChart();
});

onUnmounted(() => {
    if (chartInstance.value) {
        chartInstance.value.destroy();
    }
});

watch(
    () => [props.labels, props.data],
    () => {
        updateChart();
    },
    { deep: true },
);
</script>

<style scoped>
.chart-container {
    position: relative;
    width: 100%;
    height: 200px;
}
</style>

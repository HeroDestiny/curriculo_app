<template>
    <div class="chart-container">
        <canvas :id="chartId" :width="width" :height="height"></canvas>
    </div>
</template>

<script setup lang="ts">
import { BarController, BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, Title, Tooltip } from 'chart.js';
import { onMounted, onUnmounted, ref, watch } from 'vue';

ChartJS.register(CategoryScale, LinearScale, BarElement, BarController, Title, Tooltip, Legend);

interface Props {
    chartId: string;
    width?: number;
    height?: number;
    labels: string[];
    data: number[];
    title?: string;
    color?: string;
    horizontal?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    width: 400,
    height: 250,
    color: '#10B981',
    horizontal: false,
});

const chartInstance = ref<ChartJS | null>(null);

const createChart = () => {
    const ctx = document.getElementById(props.chartId) as HTMLCanvasElement;
    if (!ctx) return;

    chartInstance.value = new ChartJS(ctx, {
        type: props.horizontal ? 'bar' : 'bar',
        data: {
            labels: props.labels,
            datasets: [
                {
                    label: props.title || 'Dados',
                    data: props.data,
                    backgroundColor: props.color,
                    borderColor: props.color,
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: props.horizontal ? 'y' : 'x',
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
                [props.horizontal ? 'x' : 'y']: {
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
    height: 250px;
}
</style>

<template>
    <div class="chart-container">
        <canvas :id="chartId" :width="width" :height="height"></canvas>
    </div>
</template>

<script setup lang="ts">
import { ArcElement, Chart as ChartJS, Legend, PieController, Title, Tooltip } from 'chart.js';
import { onMounted, onUnmounted, ref, watch } from 'vue';

ChartJS.register(ArcElement, PieController, Tooltip, Legend, Title);

interface ChartData {
    label: string;
    value: number;
}

interface Props {
    chartId: string;
    width?: number;
    height?: number;
    data: ChartData[] | Record<string, number>;
    title?: string;
    colors?: string[];
}

const props = withDefaults(defineProps<Props>(), {
    width: 300,
    height: 300,
    colors: () => [
        '#3B82F6', // blue
        '#EF4444', // red
        '#10B981', // green
        '#F59E0B', // yellow
        '#8B5CF6', // purple
        '#F97316', // orange
        '#6B7280', // gray
        '#EC4899', // pink
    ],
});

const chartInstance = ref<ChartJS | null>(null);

const processData = () => {
    if (Array.isArray(props.data)) {
        return {
            labels: props.data.map((item) => item.label),
            values: props.data.map((item) => item.value),
        };
    } else {
        return {
            labels: Object.keys(props.data),
            values: Object.values(props.data),
        };
    }
};

const createChart = () => {
    const ctx = document.getElementById(props.chartId) as HTMLCanvasElement;
    if (!ctx) return;

    const { labels, values } = processData();

    chartInstance.value = new ChartJS(ctx, {
        type: 'pie',
        data: {
            labels,
            datasets: [
                {
                    data: values,
                    backgroundColor: props.colors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                    },
                },
                title: {
                    display: !!props.title,
                    text: props.title,
                    position: 'top',
                },
            },
        },
    });
};

const updateChart = () => {
    if (chartInstance.value) {
        const { labels, values } = processData();
        chartInstance.value.data.labels = labels;
        chartInstance.value.data.datasets[0].data = values;
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
    () => props.data,
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
    height: 300px;
}
</style>

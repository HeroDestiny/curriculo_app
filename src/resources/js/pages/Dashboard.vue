<script setup lang="ts">
import BarChart from '@/components/Charts/BarChart.vue';
import LineChart from '@/components/Charts/LineChart.vue';
import PieChart from '@/components/Charts/PieChart.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { BarChart3, Clock, FileText, LineChart as LineChartIcon, PieChart as PieChartIcon, ShieldX, TrendingUp } from 'lucide-vue-next';

interface ChartData {
    curriculos_por_dia: {
        labels: string[];
        data: number[];
    };
    curriculos_por_escolaridade: Array<{
        label: string;
        value: number;
    }>;
    curriculos_por_cargo: Array<{
        label: string;
        value: number;
    }>;
}

interface Props {
    stats: {
        total_curriculos: number;
        novos_esta_semana: number;
        aguardando_analise: number;
    };
    charts: ChartData;
}

const { stats, charts } = defineProps<Props>();
const page = usePage();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Alerta de erro se usuário tentou acessar área restrita -->
        <div v-if="(page.props as any).flash?.error" class="mb-4 rounded-md border border-red-200 bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <ShieldX class="h-5 w-5 text-red-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-800">{{ (page.props as any).flash?.error }}</p>
                </div>
            </div>
        </div>

        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Links de Ação Rápida -->
            <!-- <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-sidebar-border/70 bg-gradient-to-r from-gray-50 to-gray-100 p-4 dark:border-sidebar-border dark:from-gray-800/50 dark:to-gray-900/50">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-blue-100 p-2 dark:bg-blue-900/30">
                        <Users class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900 dark:text-gray-100">Ações Rápidas</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Acesse as principais funcionalidades</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <Link
                        href="/curriculos"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700"
                    >
                        <FileText class="h-4 w-4" />
                        Ver Currículos
                    </Link>
                    <Link
                        href="/"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                    >
                        <Home class="h-4 w-4" />
                        Página Inicial
                    </Link>
                </div>
            </div> -->

            <!-- Cards de estatísticas -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-gradient-to-br from-blue-50 to-blue-100 p-6 dark:border-sidebar-border dark:from-blue-900/20 dark:to-blue-800/20"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total de Currículos</p>
                            <p class="mt-1 text-2xl font-bold text-blue-900 dark:text-blue-100">{{ stats.total_curriculos }}</p>
                        </div>
                        <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-800/50">
                            <FileText class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        </div>
                    </div>
                </div>

                <div
                    class="relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-gradient-to-br from-green-50 to-green-100 p-6 dark:border-sidebar-border dark:from-green-900/20 dark:to-green-800/20"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Novos Esta Semana</p>
                            <p class="mt-1 text-2xl font-bold text-green-900 dark:text-green-100">{{ stats.novos_esta_semana }}</p>
                        </div>
                        <div class="rounded-full bg-green-100 p-3 dark:bg-green-800/50">
                            <TrendingUp class="h-6 w-6 text-green-600 dark:text-green-400" />
                        </div>
                    </div>
                </div>

                <div
                    class="relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-gradient-to-br from-purple-50 to-purple-100 p-6 dark:border-sidebar-border dark:from-purple-900/20 dark:to-purple-800/20"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Aguardando Análise</p>
                            <p class="mt-1 text-2xl font-bold text-purple-900 dark:text-purple-100">{{ stats.aguardando_analise }}</p>
                        </div>
                        <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-800/50">
                            <Clock class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Área principal com gráficos -->
            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Gráfico de linha - Currículos por dia -->
                <div class="rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border">
                    <div class="mb-4 flex items-center gap-2">
                        <div class="rounded-lg bg-blue-100 p-2 dark:bg-blue-900/30">
                            <LineChartIcon class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Currículos Recebidos</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Últimos 7 dias</p>
                        </div>
                    </div>
                    <LineChart
                        chart-id="curriculos-por-dia"
                        :labels="charts.curriculos_por_dia.labels"
                        :data="charts.curriculos_por_dia.data"
                        color="#3B82F6"
                    />
                </div>

                <!-- Gráfico de pizza - Currículos por escolaridade -->
                <div class="rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border">
                    <div class="mb-4 flex items-center gap-2">
                        <div class="rounded-lg bg-green-100 p-2 dark:bg-green-900/30">
                            <PieChartIcon class="h-5 w-5 text-green-600 dark:text-green-400" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Por Escolaridade</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Distribuição por nível educacional</p>
                        </div>
                    </div>
                    <PieChart
                        chart-id="curriculos-escolaridade"
                        :data="charts.curriculos_por_escolaridade"
                        :colors="['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#F97316']"
                    />
                </div>

                <!-- Gráfico de barras - Currículos por cargo desejado -->
                <div class="rounded-xl border border-sidebar-border/70 p-6 lg:col-span-2 dark:border-sidebar-border">
                    <div class="mb-4 flex items-center gap-2">
                        <div class="rounded-lg bg-purple-100 p-2 dark:bg-purple-900/30">
                            <BarChart3 class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Top 10 Cargos Desejados</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Cargos mais procurados pelos candidatos</p>
                        </div>
                    </div>
                    <div class="mx-auto max-w-4xl">
                        <BarChart
                            chart-id="curriculos-por-cargo"
                            :labels="charts.curriculos_por_cargo.map((item) => item.label)"
                            :data="charts.curriculos_por_cargo.map((item) => item.value)"
                            color="#8B5CF6"
                            :horizontal="true"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

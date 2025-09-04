<template>
    <AppShell>
        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <Heading title="Currículos Recebidos" />
                    <p class="mt-1 text-sm text-gray-600">Visualize e gerencie os currículos enviados pelos candidatos</p>
                </div>

                <div class="overflow-hidden bg-white shadow sm:rounded-md">
                    <ul class="divide-y divide-gray-200">
                        <li v-for="curriculo in curriculos.data" :key="curriculo.id" class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-1">
                                            <p class="truncate text-lg font-medium text-gray-900">
                                                {{ curriculo.nome }}
                                            </p>
                                            <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                <span>{{ curriculo.email }}</span>
                                                <span>•</span>
                                                <span>{{ curriculo.telefone }}</span>
                                            </div>
                                            <div class="mt-1 text-sm text-gray-500"><strong>Cargo:</strong> {{ curriculo.cargo_desejado }}</div>
                                            <div class="mt-1 text-sm text-gray-500">
                                                <strong>Escolaridade:</strong> {{ curriculo.escolaridade_formatada }}
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">
                                                {{ formatarData(curriculo.created_at) }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ formatarHora(curriculo.created_at) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="ml-6 flex items-center space-x-2">
                                    <Button variant="outline" size="sm" @click="visualizar(curriculo)"> Visualizar </Button>

                                    <Button variant="outline" size="sm" @click="downloadArquivo(curriculo)"> Download </Button>
                                </div>
                            </div>

                            <div v-if="curriculo.observacoes" class="mt-3 rounded bg-gray-50 p-3 text-sm text-gray-600">
                                <strong>Observações:</strong><br />
                                {{ curriculo.observacoes }}
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Paginação -->
                <div v-if="curriculos.links.length > 3" class="mt-6 flex justify-center">
                    <nav class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <template v-for="(link, index) in curriculos.links" :key="index">
                            <component
                                :is="link.url ? 'inertia-link' : 'span'"
                                :href="link.url"
                                class="relative inline-flex items-center border px-4 py-2 text-sm font-medium"
                                :class="{
                                    'border-indigo-500 bg-indigo-50 text-indigo-600': link.active,
                                    'border-gray-300 bg-white text-gray-500 hover:bg-gray-50': !link.active && link.url,
                                    'cursor-not-allowed border-gray-300 bg-gray-100 text-gray-400': !link.url,
                                    'rounded-l-md': index === 0,
                                    'rounded-r-md': index === curriculos.links.length - 1,
                                }"
                                v-html="link.label"
                            />
                        </template>
                    </nav>
                </div>

                <div v-if="curriculos.data.length === 0" class="py-12 text-center">
                    <p class="text-gray-500">Nenhum currículo foi enviado ainda.</p>
                </div>
            </div>
        </div>
    </AppShell>
</template>

<script setup lang="ts">
import AppShell from '@/components/AppShell.vue';
import Heading from '@/components/Heading.vue';
import Button from '@/components/ui/button/Button.vue';
import { router } from '@inertiajs/vue3';

interface Curriculo {
    id: number;
    nome: string;
    email: string;
    telefone: string;
    cargo_desejado: string;
    escolaridade: string;
    escolaridade_formatada: string;
    observacoes?: string;
    arquivo_original_name: string;
    created_at: string;
}

interface PaginatedCurriculos {
    data: Curriculo[];
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

interface Props {
    curriculos: PaginatedCurriculos;
}

const props = defineProps<Props>();

const formatarData = (dataString: string) => {
    const data = new Date(dataString);
    return data.toLocaleDateString('pt-BR');
};

const formatarHora = (dataString: string) => {
    const data = new Date(dataString);
    return data.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
};

const visualizar = (curriculo: Curriculo) => {
    router.visit(`/curriculos/${curriculo.id}`);
};

const downloadArquivo = (curriculo: Curriculo) => {
    window.open(`/curriculos/${curriculo.id}/download`, '_blank');
};
</script>

<template>
    <Head title="Currículos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="mb-4">
                <h1 class="text-2xl font-bold tracking-tight">Currículos Recebidos</h1>
                <p class="text-muted-foreground">Visualize e gerencie os currículos enviados pelos candidatos</p>
            </div>

            <!-- Barra de pesquisa -->
            <div class="mb-6">
                <div class="relative max-w-sm">
                    <Search class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input v-model="searchQuery" placeholder="Pesquisar por nome, cargo ou email..." class="pl-10" @input="handleSearch" />
                </div>
                <div v-if="searchQuery" class="mt-2 text-sm text-muted-foreground">
                    {{ curriculos.data.length }} resultado(s) encontrado(s) para "{{ searchQuery }}"
                </div>
            </div>

            <div v-if="curriculos.data.length > 0" class="overflow-hidden rounded-xl border border-sidebar-border/70 bg-background shadow-sm">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[200px]">Nome</TableHead>
                            <TableHead>Cargo Desejado</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Data de Envio</TableHead>
                            <TableHead class="text-right">Ações</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="curriculo in curriculos.data" :key="curriculo.id" class="hover:bg-muted/50">
                            <TableCell class="font-medium">
                                {{ curriculo.nome }}
                            </TableCell>
                            <TableCell>
                                {{ curriculo.cargo_desejado }}
                            </TableCell>
                            <TableCell>
                                <a :href="`mailto:${curriculo.email}`" class="text-primary hover:underline">
                                    {{ curriculo.email }}
                                </a>
                            </TableCell>
                            <TableCell>
                                <div>
                                    <div class="text-sm">{{ formatarData(curriculo.created_at) }}</div>
                                    <div class="text-xs text-muted-foreground">{{ formatarHora(curriculo.created_at) }}</div>
                                </div>
                            </TableCell>
                            <TableCell class="text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <CurriculoDetailDialog :curriculo="curriculo" />
                                    <Button variant="outline" size="sm" @click="downloadArquivo(curriculo)"> Download </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <!-- Componente de paginação -->
            <div v-if="curriculos.data.length > 0 && curriculos.links.length > 3" class="flex items-center justify-center space-x-2 py-6">
                <!-- Botão Anterior -->
                <Button variant="outline" size="sm" :disabled="!hasPrevious" @click="goToPrevious" class="px-3 py-2"> Anterior </Button>

                <!-- Números das páginas -->
                <template v-for="(link, index) in paginationLinks" :key="index">
                    <Button
                        v-if="link.type === 'page'"
                        :variant="link.active ? 'default' : 'outline'"
                        size="sm"
                        :disabled="!link.url"
                        @click="goToPage(link.url)"
                        class="min-w-[40px] px-3 py-2"
                    >
                        {{ link.label }}
                    </Button>
                    <span v-else-if="link.type === 'ellipsis'" class="px-2 text-muted-foreground"> ... </span>
                </template>

                <!-- Botão Próximo -->
                <Button variant="outline" size="sm" :disabled="!hasNext" @click="goToNext" class="px-3 py-2"> Próximo </Button>
            </div>

            <div
                v-else-if="curriculos.data.length === 0"
                class="flex h-64 items-center justify-center rounded-xl border border-dashed border-sidebar-border/70"
            >
                <div v-if="searchQuery" class="text-center">
                    <Search class="mx-auto h-12 w-12 text-muted-foreground" />
                    <h3 class="mt-2 text-sm font-medium">Nenhum resultado encontrado</h3>
                    <p class="mt-1 text-sm text-muted-foreground">Tente pesquisar por outro termo ou limpe o filtro.</p>
                </div>
                <div v-else class="text-center">
                    <FileText class="mx-auto h-12 w-12 text-muted-foreground" />
                    <h3 class="mt-2 text-sm font-medium">Nenhum currículo encontrado</h3>
                    <p class="mt-1 text-sm text-muted-foreground">Nenhum currículo foi enviado ainda.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as curriculosIndex } from '@/routes/curriculos';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { FileText, Search } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import CurriculoDetailDialog from './partials/CurriculoDetailDialog.vue';

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
    search?: string;
}

const { curriculos, search = '' } = defineProps<Props>();

// Estado da pesquisa
const searchQuery = ref(search);

// Debounce da pesquisa
let searchTimeout: ReturnType<typeof setTimeout>;

// Função para lidar com a pesquisa
const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.visit(curriculosIndex().url, {
            data: { search: searchQuery.value || undefined },
            preserveState: true,
            preserveScroll: true,
        });
    }, 500);
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Currículos',
        href: curriculosIndex().url,
    },
];

const formatarData = (dataString: string) => {
    const data = new Date(dataString);
    return data.toLocaleDateString('pt-BR');
};

const formatarHora = (dataString: string) => {
    const data = new Date(dataString);
    return data.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
};

const downloadArquivo = (curriculo: Curriculo) => {
    window.open(`/curriculos/${curriculo.id}/download`, '_blank');
};

// Funções de paginação
const paginationLinks = computed(() => {
    return curriculos.links
        .filter(
            (link) =>
                !link.label.includes('Previous') &&
                !link.label.includes('Next') &&
                !link.label.includes('&laquo;') &&
                !link.label.includes('&raquo;'),
        )
        .map((link) => {
            if (link.label === '...') {
                return { type: 'ellipsis', label: link.label, url: link.url, active: link.active };
            }
            return { type: 'page', label: link.label, url: link.url, active: link.active };
        });
});

const hasPrevious = computed(() => {
    const prevLink = curriculos.links.find((link) => link.label.includes('Previous') || link.label.includes('&laquo;'));
    return prevLink?.url !== null;
});

const hasNext = computed(() => {
    const nextLink = curriculos.links.find((link) => link.label.includes('Next') || link.label.includes('&raquo;'));
    return nextLink?.url !== null;
});

const goToPage = (url: string | null) => {
    if (url) {
        router.visit(url, {
            preserveState: true,
            preserveScroll: true,
        });
    }
};

const goToPrevious = () => {
    const prevLink = curriculos.links.find((link) => link.label.includes('Previous') || link.label.includes('&laquo;'));
    if (prevLink?.url) {
        goToPage(prevLink.url);
    }
};

const goToNext = () => {
    const nextLink = curriculos.links.find((link) => link.label.includes('Next') || link.label.includes('&raquo;'));
    if (nextLink?.url) {
        goToPage(nextLink.url);
    }
};
</script>

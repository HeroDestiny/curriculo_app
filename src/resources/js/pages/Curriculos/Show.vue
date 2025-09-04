<template>
    <AppShell>
        <div class="py-8">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <Heading title="Detalhes do Currículo" />
                    <p class="mt-1 text-sm text-gray-600">Informações completas do candidato</p>
                </div>

                <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ curriculo.nome }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Enviado em {{ formatarDataCompleta(curriculo.created_at) }}</p>
                    </div>

                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nome Completo</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    {{ curriculo.nome }}
                                </dd>
                            </div>

                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">E-mail</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    <a :href="`mailto:${curriculo.email}`" class="text-indigo-600 hover:text-indigo-500">
                                        {{ curriculo.email }}
                                    </a>
                                </dd>
                            </div>

                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Telefone</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    <a :href="`tel:${curriculo.telefone}`" class="text-indigo-600 hover:text-indigo-500">
                                        {{ curriculo.telefone }}
                                    </a>
                                </dd>
                            </div>

                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Cargo Desejado</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    {{ curriculo.cargo_desejado }}
                                </dd>
                            </div>

                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Escolaridade</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    {{ curriculo.escolaridade_formatada }}
                                </dd>
                            </div>

                            <div v-if="curriculo.observacoes" class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Observações</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    <div class="whitespace-pre-wrap">{{ curriculo.observacoes }}</div>
                                </dd>
                            </div>

                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Currículo Anexado</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span class="ml-2">{{ curriculo.arquivo_original_name }}</span>
                                        </div>

                                        <Button variant="outline" size="sm" @click="downloadArquivo"> Download </Button>
                                    </div>
                                </dd>
                            </div>

                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Data de Envio</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    {{ formatarDataCompleta(curriculo.created_at) }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="mt-8 flex justify-between">
                    <Button variant="outline" @click="voltarLista"> ← Voltar à Lista </Button>

                    <div class="space-x-4">
                        <Button variant="outline" @click="downloadArquivo"> Download do Currículo </Button>

                        <Button @click="entrarContato" class="bg-indigo-600 hover:bg-indigo-700"> Entrar em Contato </Button>
                    </div>
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

interface Props {
    curriculo: Curriculo;
}

const props = defineProps<Props>();

const formatarDataCompleta = (dataString: string) => {
    const data = new Date(dataString);
    return data.toLocaleString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const voltarLista = () => {
    router.visit('/curriculos');
};

const downloadArquivo = () => {
    window.open(`/curriculos/${props.curriculo.id}/download`, '_blank');
};

const entrarContato = () => {
    const subject = encodeURIComponent(`Oportunidade de emprego - ${props.curriculo.cargo_desejado}`);
    const body = encodeURIComponent(
        `Olá ${props.curriculo.nome},\n\nEntramos em contato sobre seu currículo enviado para a vaga de ${props.curriculo.cargo_desejado}.\n\n`,
    );
    window.open(`mailto:${props.curriculo.email}?subject=${subject}&body=${body}`);
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl">
            <div class="mb-8 text-center">
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Envie seu Currículo</h1>
                <p class="text-gray-600">Preencha o formulário abaixo para enviar seu currículo</p>
            </div>

            <div class="rounded-lg bg-white p-8 shadow-lg">
                <form @submit.prevent="submitForm" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Nome -->
                        <div class="md:col-span-2">
                            <Label for="nome" class="text-sm font-medium text-gray-700">Nome Completo *</Label>
                            <Input id="nome" v-model="form.nome" type="text" class="mt-1" :class="{ 'border-red-500': form.errors.nome }" required />
                            <InputError v-if="form.errors.nome" :message="form.errors.nome" />
                        </div>

                        <!-- Email -->
                        <div>
                            <Label for="email" class="text-sm font-medium text-gray-700">E-mail *</Label>
                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="mt-1"
                                :class="{ 'border-red-500': form.errors.email }"
                                required
                            />
                            <InputError v-if="form.errors.email" :message="form.errors.email" />
                        </div>

                        <!-- Telefone -->
                        <div>
                            <Label for="telefone" class="text-sm font-medium text-gray-700">Telefone *</Label>
                            <Input
                                id="telefone"
                                v-model="form.telefone"
                                type="tel"
                                class="mt-1"
                                :class="{ 'border-red-500': form.errors.telefone }"
                                placeholder="(11) 99999-9999"
                                required
                            />
                            <InputError v-if="form.errors.telefone" :message="form.errors.telefone" />
                        </div>

                        <!-- Cargo Desejado -->
                        <div class="md:col-span-2">
                            <Label for="cargo_desejado" class="text-sm font-medium text-gray-700">Cargo Desejado *</Label>
                            <Input
                                id="cargo_desejado"
                                v-model="form.cargo_desejado"
                                type="text"
                                class="mt-1"
                                :class="{ 'border-red-500': form.errors.cargo_desejado }"
                                required
                            />
                            <InputError v-if="form.errors.cargo_desejado" :message="form.errors.cargo_desejado" />
                        </div>

                        <!-- Escolaridade -->
                        <div class="md:col-span-2">
                            <Label for="escolaridade" class="text-sm font-medium text-gray-700">Escolaridade *</Label>
                            <select
                                id="escolaridade"
                                v-model="form.escolaridade"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                :class="{ 'border-red-500': form.errors.escolaridade }"
                                required
                            >
                                <option value="">Selecione sua escolaridade</option>
                                <option v-for="(label, value) in escolaridades" :key="value" :value="value">
                                    {{ label }}
                                </option>
                            </select>
                            <InputError v-if="form.errors.escolaridade" :message="form.errors.escolaridade" />
                        </div>

                        <!-- Arquivo -->
                        <div class="md:col-span-2">
                            <Label for="arquivo" class="text-sm font-medium text-gray-700">Currículo (PDF, DOC ou DOCX) *</Label>
                            <input
                                id="arquivo"
                                ref="fileInput"
                                type="file"
                                accept=".pdf,.doc,.docx"
                                @change="handleFileChange"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100"
                                :class="{ 'border-red-500': form.errors.arquivo }"
                                required
                            />
                            <p class="mt-1 text-sm text-gray-500">Máximo 5MB</p>
                            <InputError v-if="form.errors.arquivo" :message="form.errors.arquivo" />
                        </div>

                        <!-- Observações -->
                        <div class="md:col-span-2">
                            <Label for="observacoes" class="text-sm font-medium text-gray-700">Observações</Label>
                            <textarea
                                id="observacoes"
                                v-model="form.observacoes"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                :class="{ 'border-red-500': form.errors.observacoes }"
                                placeholder="Conte-nos um pouco mais sobre você..."
                            ></textarea>
                            <InputError v-if="form.errors.observacoes" :message="form.errors.observacoes" />
                        </div>
                    </div>

                    <!-- Data e hora de envio (apenas exibição) -->
                    <div class="mt-6 rounded-md bg-gray-50 p-4">
                        <p class="text-sm text-gray-600"><strong>Data e hora do envio:</strong> {{ formatarDataHora(new Date()) }}</p>
                    </div>

                    <!-- Botões -->
                    <div class="mt-8 flex items-center justify-between">
                        <Button type="button" variant="outline" @click="voltarPaginaAnterior"> Voltar </Button>

                        <div class="flex space-x-4">
                            <Button type="button" variant="outline" @click="resetForm"> Limpar Formulário </Button>
                            <Button type="submit" :disabled="form.processing" class="bg-indigo-600 hover:bg-indigo-700">
                                <span v-if="form.processing">Enviando...</span>
                                <span v-else>Enviar Currículo</span>
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Props {
    escolaridades: Record<string, string>;
}

const { escolaridades } = defineProps<Props>();

const fileInput = ref<HTMLInputElement>();

const form = useForm({
    nome: '',
    email: '',
    telefone: '',
    cargo_desejado: '',
    escolaridade: '',
    observacoes: '',
    arquivo: null as File | null,
});

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.arquivo = target.files[0];
    }
};

const submitForm = () => {
    form.post('/curriculos', {
        forceFormData: true,
    });
};

const resetForm = () => {
    form.reset();
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const voltarPaginaAnterior = () => {
    window.history.back();
};

const formatarDataHora = (data: Date) => {
    return data.toLocaleString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

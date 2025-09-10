<template>
    <div class="min-h-screen bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl">
            <div class="mb-8 text-center">
                <div class="mb-4 flex items-center justify-center">
                    <Rocket class="mr-2 h-8 w-8 text-indigo-600" />
                    <h1 class="text-3xl font-bold text-gray-900">Envie seu Currículo</h1>
                </div>
                <p class="text-lg text-gray-600">Dê o próximo passo na sua carreira conosco!</p>
                <p class="mt-2 text-sm text-gray-500">Preencha o formulário abaixo com suas informações e anexe seu currículo atualizado</p>
            </div>

            <div class="rounded-lg bg-white p-8 shadow-lg">
                <form @submit.prevent="submitForm" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Nome -->
                        <div class="md:col-span-2">
                            <Label for="nome" class="text-sm font-medium text-gray-700">Nome Completo *</Label>
                            <Input
                                id="nome"
                                v-model="form.nome"
                                type="text"
                                class="mt-1"
                                placeholder="Ex: João Silva dos Santos"
                                :class="{ 'border-red-500': form.errors.nome }"
                                required
                            />
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
                                placeholder="seu.email@exemplo.com"
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
                                @input="handlePhoneInput"
                                maxlength="15"
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
                                placeholder="Ex: Desenvolvedor Full Stack, Analista de Marketing, Gerente de Vendas"
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
                            <p class="mt-1 text-sm text-gray-500">
                                <span class="mb-1 flex items-center">
                                    <FileText class="mr-1 h-4 w-4" />
                                    Formatos aceitos: PDF, DOC, DOCX • Tamanho máximo: 5MB
                                </span>
                                <span class="flex items-center">
                                    <Lightbulb class="mr-1 h-4 w-4" />
                                    Dica: Certifique-se de que seu currículo esteja atualizado com suas experiências mais recentes
                                </span>
                            </p>
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
                                placeholder="Conte-nos sobre sua experiência, habilidades especiais, disponibilidade ou qualquer informação adicional que considere importante..."
                            ></textarea>
                            <p class="mt-1 text-sm text-gray-500">Opcional - Use este espaço para destacar pontos importantes do seu perfil</p>
                            <InputError v-if="form.errors.observacoes" :message="form.errors.observacoes" />
                        </div>
                    </div>

                    <!-- Data e hora de envio (apenas exibição) -->
                    <div class="mt-6 rounded-md border border-indigo-200 bg-indigo-50 p-4">
                        <div class="flex items-center">
                            <Calendar class="mr-3 h-5 w-5 text-indigo-600" />
                            <div>
                                <p class="text-sm font-medium text-indigo-900">Data e hora do envio:</p>
                                <p class="text-sm text-indigo-700">{{ formatarDataHora(currentDateTime) }}</p>
                                <p class="mt-1 text-xs text-indigo-600">Seus dados serão tratados com segurança e confidencialidade</p>
                            </div>
                        </div>
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
import { Calendar, FileText, Lightbulb, Rocket } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';

interface Props {
    escolaridades: Record<string, string>;
}

const { escolaridades } = defineProps<Props>();

const fileInput = ref<HTMLInputElement>();
const currentDateTime = ref(new Date());
let timeInterval: ReturnType<typeof setInterval> | null = null;

// Atualiza a data/hora a cada minuto
onMounted(() => {
    timeInterval = setInterval(() => {
        currentDateTime.value = new Date();
    }, 1000); // Atualiza a cada segundo
});

onUnmounted(() => {
    if (timeInterval) {
        clearInterval(timeInterval);
    }
});

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

// Função para aplicar máscara no telefone
const handlePhoneInput = (event: Event) => {
    const target = event.target as HTMLInputElement;
    let value = target.value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos

    // Limita a 11 dígitos no máximo
    if (value.length > 11) {
        value = value.slice(0, 11);
    }

    // Aplica a máscara baseada no tamanho do número
    if (value.length <= 2) {
        // Apenas DDD: "84" -> "(84"
        value = value.replace(/^(\d{1,2})/, '($1');
    } else if (value.length <= 6) {
        // DDD + início do número: "84999" -> "(84) 999"
        value = value.replace(/^(\d{2})(\d{1,4})/, '($1) $2');
    } else if (value.length <= 10) {
        // Telefone fixo: 10 dígitos -> (XX) XXXX-XXXX
        value = value.replace(/^(\d{2})(\d{4})(\d{1,4})/, '($1) $2-$3');
    } else {
        // Celular: 11 dígitos -> (XX) XXXXX-XXXX
        value = value.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    }

    form.telefone = value;
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

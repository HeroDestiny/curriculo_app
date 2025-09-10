<template>
    <div class="min-h-screen bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-md">
            <div class="text-center">
                <ShieldX class="mx-auto mb-4 h-16 w-16 text-red-500" />
                <h1 class="mb-4 text-3xl font-bold text-gray-900">Acesso Restrito</h1>
                <p class="mb-6 text-lg text-gray-600">
                    Você não possui permissão para acessar esta área. Apenas administradores podem visualizar e gerenciar currículos.
                </p>
                <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4">
                    <div class="flex items-center">
                        <Info class="mr-2 h-5 w-5 text-blue-600" />
                        <p class="text-sm text-blue-700">
                            Se você é um administrador, entre em contato com o suporte técnico para verificar suas permissões.
                        </p>
                    </div>
                </div>
                <div class="flex flex-col space-y-3">
                    <Link
                        href="/dashboard"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700"
                    >
                        <Home class="h-4 w-4" />
                        Voltar ao Dashboard
                    </Link>
                    <Link
                        href="/curriculos/enviar"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                    >
                        <FileText class="h-4 w-4" />
                        Formulário de Currículos
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { FileText, Home, Info, ShieldX } from 'lucide-vue-next';
</script>

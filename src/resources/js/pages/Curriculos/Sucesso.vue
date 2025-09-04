<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <div class="rounded-lg bg-white p-8 text-center shadow-lg">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h1 class="mb-2 text-2xl font-bold text-gray-900">Currículo Enviado com Sucesso!</h1>

                <p class="mb-6 text-gray-600">
                    Seu currículo foi enviado com sucesso. Em breve entraremos em contato caso haja uma vaga compatível com seu perfil.
                </p>

                <div class="space-y-4">
                    <Button @click="enviarOutro" class="w-full bg-indigo-600 hover:bg-indigo-700"> Enviar Outro Currículo </Button>

                    <Button variant="outline" @click="voltarInicio" class="w-full"> Voltar ao Início </Button>
                </div>

                <div class="mt-6 rounded-md bg-gray-50 p-4">
                    <p class="text-sm text-gray-500">Para dúvidas ou mais informações, entre em contato conosco.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { router } from '@inertiajs/vue3';

const enviarOutro = () => {
    router.visit('/curriculos/enviar');
};

const voltarInicio = () => {
    router.visit('/');
};
</script>

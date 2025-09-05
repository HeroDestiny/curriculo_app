<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, FileText, Send, Shield, Star, Target, UserCheck, Zap } from 'lucide-vue-next';
</script>

<template>
    <Head title="Portal de Currículos - Encontre sua próxima oportunidade">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <!-- Hero Section -->
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900">
        <!-- Navigation -->
        <nav class="relative z-10 w-full px-4 py-6">
            <div class="mx-auto flex max-w-7xl items-center justify-between">
                <!-- Logo/Brand -->
                <div class="flex items-center space-x-2">
                    <div class="rounded-lg bg-indigo-600 p-2 text-white">
                        <FileText class="h-6 w-6" />
                    </div>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Portal de Currículos</span>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-4">
                    <Link
                        href="/curriculos/enviar"
                        class="flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-2 font-medium text-white shadow-lg transition-colors duration-200 hover:bg-indigo-700 hover:shadow-xl"
                    >
                        <Send class="h-4 w-4" />
                        Enviar Currículo
                    </Link>

                    <template v-if="$page.props.auth.user">
                        <Link
                            :href="dashboard()"
                            class="flex items-center gap-2 rounded-lg border border-gray-300 px-6 py-2 font-medium text-gray-700 transition-colors duration-200 hover:border-gray-400 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700"
                        >
                            <Target class="h-4 w-4" />
                            Dashboard
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="px-4 py-2 font-medium text-gray-700 transition-colors duration-200 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400"
                        >
                            Entrar
                        </Link>
                        <Link
                            :href="register()"
                            class="rounded-lg border border-indigo-600 px-6 py-2 font-medium text-indigo-600 transition-colors duration-200 hover:bg-indigo-600 hover:text-white"
                        >
                            Registrar
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Hero Content -->
        <div class="flex flex-1 items-center justify-center px-4 py-12">
            <div class="mx-auto max-w-7xl">
                <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
                    <!-- Left Content -->
                    <div class="text-center lg:text-left">
                        <h1 class="mb-6 text-4xl leading-tight font-bold text-gray-900 lg:text-6xl dark:text-white">
                            Sua próxima
                            <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent"> oportunidade </span>
                            começa aqui
                        </h1>

                        <p class="mb-8 text-xl leading-relaxed text-gray-600 dark:text-gray-300">
                            Conectamos talentos com oportunidades. Envie seu currículo e deixe que as empresas encontrem você.
                        </p>

                        <div class="flex flex-col justify-center gap-4 sm:flex-row lg:justify-start">
                            <Link
                                href="/curriculos/enviar"
                                class="flex transform items-center justify-center gap-2 rounded-xl bg-indigo-600 px-8 py-4 text-lg font-semibold text-white shadow-lg transition-all duration-200 hover:-translate-y-1 hover:bg-indigo-700 hover:shadow-xl"
                            >
                                <Send class="h-5 w-5" />
                                Enviar Meu Currículo
                            </Link>

                            <template v-if="!$page.props.auth.user">
                                <Link
                                    :href="register()"
                                    class="flex items-center justify-center gap-2 rounded-xl border-2 border-indigo-600 px-8 py-4 text-lg font-semibold text-indigo-600 transition-all duration-200 hover:bg-indigo-600 hover:text-white"
                                >
                                    <UserCheck class="h-5 w-5" />
                                    Sou Recrutador
                                </Link>
                            </template>
                        </div>

                        <!-- Stats -->
                        <div class="mt-12 grid grid-cols-3 gap-8 border-t border-gray-200 pt-8 dark:border-gray-700">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-indigo-600">1000+</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Currículos</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-indigo-600">500+</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Empresas</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-indigo-600">95%</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Satisfação</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content - Illustration -->
                    <div class="relative">
                        <div class="relative">
                            <!-- Background decoration -->
                            <div
                                class="absolute inset-0 rotate-6 transform rounded-3xl bg-gradient-to-r from-indigo-400 to-purple-400 opacity-20"
                            ></div>
                            <div
                                class="absolute inset-0 -rotate-6 transform rounded-3xl bg-gradient-to-r from-purple-400 to-pink-400 opacity-20"
                            ></div>

                            <!-- Main content -->
                            <div class="relative rounded-3xl bg-white p-8 shadow-2xl dark:bg-gray-800">
                                <!-- Fake browser header -->
                                <div class="mb-6 flex items-center space-x-2 border-b border-gray-200 pb-4 dark:border-gray-700">
                                    <div class="h-3 w-3 rounded-full bg-red-400"></div>
                                    <div class="h-3 w-3 rounded-full bg-yellow-400"></div>
                                    <div class="h-3 w-3 rounded-full bg-green-400"></div>
                                    <div class="ml-4 text-sm text-gray-500 dark:text-gray-400">portal-curriculos.com</div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <div class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nome Completo</div>
                                        <div class="flex h-10 items-center gap-2 rounded-lg bg-gray-50 px-3 dark:bg-gray-700">
                                            <UserCheck class="h-4 w-4 text-gray-400" />
                                            <span class="text-gray-400">João Silva Santos</span>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Email</div>
                                        <div class="flex h-10 items-center gap-2 rounded-lg bg-gray-50 px-3 dark:bg-gray-700">
                                            <Send class="h-4 w-4 text-gray-400" />
                                            <span class="text-gray-400">joao@email.com</span>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Cargo Desejado</div>
                                        <div class="flex h-10 items-center gap-2 rounded-lg bg-gray-50 px-3 dark:bg-gray-700">
                                            <Target class="h-4 w-4 text-gray-400" />
                                            <span class="text-gray-400">Desenvolvedor Full Stack</span>
                                        </div>
                                    </div>

                                    <div class="pt-4">
                                        <div
                                            class="flex items-center justify-center gap-2 rounded-lg bg-indigo-100 px-4 py-3 text-center font-medium text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300"
                                        >
                                            <Shield class="h-4 w-4" />
                                            Currículo enviado com sucesso!
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-white py-20 dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-4">
                <div class="mb-16 text-center">
                    <h2 class="mb-4 text-3xl font-bold text-gray-900 lg:text-4xl dark:text-white">Por que escolher nosso portal?</h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300">A plataforma mais eficiente para conectar talentos e oportunidades</p>
                </div>

                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <!-- Feature 1 -->
                    <div class="group text-center">
                        <div
                            class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-100 transition-transform duration-200 group-hover:scale-110 dark:bg-indigo-900"
                        >
                            <Zap class="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <h3 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Rápido e Simples</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Envie seu currículo em poucos cliques. Interface intuitiva e processo otimizado.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="group text-center">
                        <div
                            class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-100 transition-transform duration-200 group-hover:scale-110 dark:bg-indigo-900"
                        >
                            <Target class="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <h3 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Matches Precisos</h3>
                        <p class="text-gray-600 dark:text-gray-300">Nossa IA conecta você com as vagas que mais combinam com seu perfil.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group text-center">
                        <div
                            class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-100 transition-transform duration-200 group-hover:scale-110 dark:bg-indigo-900"
                        >
                            <Shield class="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <h3 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">100% Seguro</h3>
                        <p class="text-gray-600 dark:text-gray-300">Seus dados são protegidos com as mais altas tecnologias de segurança.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 py-20">
            <div class="mx-auto max-w-4xl px-4 text-center">
                <h2 class="mb-6 text-3xl font-bold text-white lg:text-4xl">Pronto para dar o próximo passo?</h2>
                <p class="mb-8 text-xl text-indigo-100">Junte-se a milhares de profissionais que já encontraram sua oportunidade ideal</p>
                <Link
                    href="/curriculos/enviar"
                    class="inline-block transform rounded-xl bg-white px-8 py-4 text-lg font-semibold text-indigo-600 shadow-lg transition-colors duration-200 hover:-translate-y-1 hover:bg-gray-50 hover:shadow-xl"
                >
                    <div class="flex items-center gap-2">
                        <Star class="h-5 w-5" />
                        Começar Agora - É Grátis!
                        <ArrowRight class="h-5 w-5" />
                    </div>
                </Link>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 py-12 text-white">
            <div class="mx-auto max-w-7xl px-4">
                <div class="text-center">
                    <div class="mb-4 flex items-center justify-center space-x-2">
                        <div class="rounded-lg bg-indigo-600 p-2 text-white">
                            <FileText class="h-6 w-6" />
                        </div>
                        <span class="text-xl font-bold">Portal de Currículos</span>
                    </div>
                    <p class="mb-6 text-gray-400">Conectando talentos com oportunidades desde 2025</p>
                    <div class="flex justify-center space-x-6 text-sm text-gray-400">
                        <a href="#" class="transition-colors hover:text-white">Sobre</a>
                        <a href="#" class="transition-colors hover:text-white">Contato</a>
                        <a href="#" class="transition-colors hover:text-white">Privacidade</a>
                        <a href="#" class="transition-colors hover:text-white">Termos</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button variant="outline" size="sm"> Visualizar </Button>
        </DialogTrigger>
        <DialogContent class="max-h-[90vh] max-w-4xl overflow-y-auto">
            <DialogHeader>
                <DialogTitle>{{ curriculo.nome }}</DialogTitle>
                <DialogDescription> Enviado em {{ formatarDataCompleta(curriculo.created_at) }} </DialogDescription>
            </DialogHeader>

            <div class="space-y-6 py-4">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <h4 class="text-sm font-medium text-muted-foreground">Nome Completo</h4>
                        <p class="text-sm">{{ curriculo.nome }}</p>
                    </div>

                    <div class="space-y-2">
                        <h4 class="text-sm font-medium text-muted-foreground">E-mail</h4>
                        <a :href="`mailto:${curriculo.email}`" class="text-sm text-primary hover:underline">
                            {{ curriculo.email }}
                        </a>
                    </div>

                    <div class="space-y-2">
                        <h4 class="text-sm font-medium text-muted-foreground">Telefone</h4>
                        <a :href="`tel:${curriculo.telefone}`" class="text-sm text-primary hover:underline">
                            {{ curriculo.telefone }}
                        </a>
                    </div>

                    <div class="space-y-2">
                        <h4 class="text-sm font-medium text-muted-foreground">Cargo Desejado</h4>
                        <p class="text-sm">{{ curriculo.cargo_desejado }}</p>
                    </div>

                    <div class="space-y-2">
                        <h4 class="text-sm font-medium text-muted-foreground">Escolaridade</h4>
                        <p class="text-sm">{{ curriculo.escolaridade_formatada }}</p>
                    </div>

                    <div class="space-y-2">
                        <h4 class="text-sm font-medium text-muted-foreground">Data de Envio</h4>
                        <p class="text-sm">{{ formatarDataCompleta(curriculo.created_at) }}</p>
                    </div>
                </div>

                <div v-if="curriculo.observacoes" class="space-y-2">
                    <h4 class="text-sm font-medium text-muted-foreground">Observações</h4>
                    <div class="rounded-lg bg-muted/50 p-3 text-sm whitespace-pre-wrap">{{ curriculo.observacoes }}</div>
                </div>

                <div class="space-y-2">
                    <h4 class="text-sm font-medium text-muted-foreground">Currículo Anexado</h4>
                    <div class="flex items-center space-x-3 rounded-lg bg-muted/50 p-3">
                        <FileText class="h-5 w-5 text-muted-foreground" />
                        <span class="flex-1 text-sm">{{ curriculo.arquivo_original_name }}</span>
                        <Button variant="outline" size="sm" @click="downloadArquivo"> Download </Button>
                    </div>
                </div>
            </div>

            <DialogFooter class="gap-3">
                <Button variant="outline" @click="downloadArquivo"> Download do Currículo </Button>
                <Button @click="entrarContato" class="bg-primary hover:bg-primary/90"> Entrar em Contato </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { FileText } from 'lucide-vue-next';

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

const { curriculo } = defineProps<Props>();

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

const downloadArquivo = () => {
    window.open(`/curriculos/${curriculo.id}/download`, '_blank');
};

const entrarContato = () => {
    const subject = encodeURIComponent(`Oportunidade de emprego - ${curriculo.cargo_desejado}`);
    const body = encodeURIComponent(
        `Olá ${curriculo.nome},\n\nEntramos em contato sobre seu currículo enviado para a vaga de ${curriculo.cargo_desejado}.\n\n`,
    );
    window.open(`mailto:${curriculo.email}?subject=${subject}&body=${body}`);
};
</script>

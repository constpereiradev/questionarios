<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="row p-3">
            <form @submit.prevent="submitQuestionario">

                <div v-if="!inputTitulo" class="row mb-5 align-items-center d-flex">
                    <div class="col-sm-10 fs-1 fw-bold"> {{ novoQuestionario.nome }}</div>
                    <div class="col-sm-2">
                        <button type="button" @click="adicionarTitulo()" class="btn fs-1">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>
                </div>
                <div v-if="inputTitulo" class="row mb-5 align-items-center d-flex">
                    <div class="col-sm-8 fs-1 fw-bold">
                        <input v-model="novoQuestionario.nome" type="text" class="form-control" required>
                    </div>
                    <div class="col-sm-4">
                        <button type="button" @click="adicionarTitulo()" class="btn fs-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                class="bi bi-floppy" viewBox="0 0 16 16">
                                <path d="M11 2H9v3h2z" />
                                <path
                                    d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div v-if="novoQuestionario.perguntas.length">
                    <div class="row">
                        <div class="col-sm-12">
                            <div v-for="(pergunta, index) in novoQuestionario.perguntas" :key="pergunta.id"
                                class="row mb-3">
                                <div class="col-sm-12">
                                    <div class="row mb-3">
                                        <div class="col-sm-10 fs-3"> {{ index + 1 }}. {{ pergunta.nome }}</div>
                                    </div>

                                    <div v-if="pergunta.tipo == 'texto'" class="row">
                                        <div class="col-sm-12">
                                            <textarea disabled class="form-control" cols="30" rows="5"></textarea>
                                        </div>
                                    </div>

                                    <div v-if="pergunta.tipo == 'multipla_escolha'" class="row">
                                        <div class="col-sm-12">
                                            <div class="row mb-2 align-items-center d-flex"
                                                v-for="(opcao, index) in pergunta.opcoes" :key="index">
                                                <div class="col-sm-12 fs-5">
                                                    <input type="radio" :id="`opcao_pergunta_${index}`"
                                                        :name="`opcao_pergunta_${pergunta.id}`" :value="opcao">
                                                    {{ opcao }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="inputNovaPergunta" class="justify-content-center d-flex mt-5">
                    <div class="col-sm-12 row mb-5 border p-3">
                        <div class="row">
                            <div class="col-sm-8">
                                <input v-model="novaPergunta.nome" type="text" class="form-control"
                                    :disabled="!novoQuestionario.nome" placeholder="Insira sua pergunta">
                            </div>
                            <div class="col-sm-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <select @change="verificarTipoPergunta" v-model="novaPergunta.tipo"
                                            class="form-select">
                                            <option value="texto">Texto</option>
                                            <option value="multipla_escolha">Múltipla escolha</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="inputMultiplaEscolha" class="row mb-5">
                            <div class="col-sm-12">
                                <div class="row align-items-center d-flex" v-for="(opcao, index) in novaPergunta.opcoes"
                                    :key="index">
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="novaPergunta.opcoes[index]"
                                            placeholder="Insira uma opção">
                                    </div>
                                    <div class="col-sm-1 justify-content-center d-flex">
                                        <button type="button" @click="adicionarOpcao" class="btn fs-4 text-secondary"><i
                                                class="bi bi-plus-circle"></i></button>
                                    </div>
                                    <div class="col-sm-1 justify-content-center d-flex">
                                        <button type="button" @click="removerOpcao(index)" class="btn fs-4 text-secondary"><i
                                                class="bi bi-dash-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 justify-content-end d-flex">
                                <div class="row">
                                    <div class="col-sm-6 justify-content-end d-flex">
                                        <button type="button" class="btn btn-outline-secondary">Cancelar</button>
                                    </div>
                                    <div class="col-sm-6 justify-content-end d-flex">
                                        <button type="button" @click.prevent="adicionarPergunta"
                                            class="btn btn-success">Salvar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-12 justify-content-center d-flex">
                        <button type="button" @click="verificarNovaPergunta()" class="btn btn-success fw-medium">
                            <i class="bi bi-plus-circle"></i> Adicionar pergunta
                        </button>
                    </div>
                </div>

                <div v-if="!inputNovaPergunta && novoQuestionario.perguntas.length" class="row">
                    <div class="col-sm-12 justify-content-end d-flex">
                        <button type="submit" class="btn btn-outline-success">Salvar</button>
                    </div>
                </div>

            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { defineComponent } from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from "axios";

export default defineComponent({
    components: {
        AuthenticatedLayout,
    },
    data() {
        return {
            questionarios: [],
            mostrarModalNovoQuestionario: false,
            novoQuestionario: {
                nome: 'Sem título',
                perguntas: []
            },
            novaPergunta: {
                nome: '',
                tipo: 'texto',
                opcoes: [''],
                opcao: '',
            },
            inputTitulo: false,
            inputNovaPergunta: false,
            tiposPergunta: ['Texto', 'Múltipla escolha', 'Numérico'],
            inputMultiplaEscolha: false,
        };
    },
    methods: {
        submitQuestionario() {
            axios.post(route('questionario.registrar'), { novoQuestionario: this.novoQuestionario })
                .then(() => {
                   this.$inertia.get(route('questionario.index'))
                })
                .catch(err => console.error('Erro ao salvar questionário:', err));
        },
        adicionarPergunta() {
            if (this.novaPergunta.nome) {
                // Cria uma nova instância de opcoes para cada pergunta
                const novaPergunta = {
                    id: Date.now(),
                    nome: this.novaPergunta.nome,
                    tipo: this.novaPergunta.tipo,
                    opcoes: [...this.novaPergunta.opcoes], // Clona as opções
                };
                this.novoQuestionario.perguntas.push(novaPergunta);

                // Limpa o formulário para a próxima pergunta
                this.novaPergunta.nome = '';
                this.novaPergunta.tipo = 'texto';
                this.novaPergunta.opcoes = [''];
                this.inputNovaPergunta = false;
                this.inputMultiplaEscolha = false;
            }
        },
        deletarPergunta(index) {
            this.novoQuestionario.perguntas.splice(index, 1);
        },
        resetarFormulario() {
            this.novoQuestionario = { nome: '', perguntas: [] };
            this.novaPergunta = { nome: '', tipo: 'texto', opcoes: [''] };
        },
        adicionarTitulo() {
            this.inputTitulo = !this.inputTitulo;
        },
        verificarNovaPergunta() {
            this.inputNovaPergunta = !this.inputNovaPergunta;
        },
        verificarTipoPergunta() {
            this.inputMultiplaEscolha = this.novaPergunta.tipo === 'multipla_escolha';
        },
        adicionarOpcao() {
            this.novaPergunta.opcoes.push('');
        },
        removerOpcao(index) {
            if (this.novaPergunta.opcoes.length > 1) {
                this.novaPergunta.opcoes.splice(index, 1);
            }
        }
    }
});

</script>

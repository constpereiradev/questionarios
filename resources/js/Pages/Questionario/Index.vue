<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="row p-3">
            <div class="col-sm-6 fs-1 fw-medium">Questionários</div>
            <div class="col-sm-6 fw-medium justify-content-end d-flex">
                <button @click="abrirModal" class="btn-padrao">+ Novo questionário</button>
            </div>
        </div>

        <div v-for="questionario in questionarios" class="row p-3 m-3 border rounded bg-white">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6 fw-medium fs-3"> {{ questionario.nome }}</div>
                    <div class="col-sm-6 justify-content-end d-flex">
                        <div class="row">
                            <div class="col-sm-12">
                                <button @click="verQuestionario(questionario.id)" class="btn btn-secondary fs-6">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 fs-6 text-secondary"> {{ questionario.perguntas.length }} Perguntas</div>
                </div>
            </div>

        </div>

        <!-- Modal -->
        <div v-if="mostrarModal" class="modal" id="novoQuestionario">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Novo questionário</h1>
                        <button @click="fecharModal" type="button" class="btn-close" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="submitQuestionario">
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <label for="nomeQuestionario">Nome do questionário</label>
                                    <input v-model="novoQuestionario.nome" type="text" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-10">
                                    <label for="perguntaNome">Pergunta</label>
                                    <input v-model="novaPergunta.nome" type="text" class="form-control"
                                        :disabled="!novoQuestionario.nome">
                                </div>
                                <div class="col-sm-2">
                                    <button @click.prevent="adicionarPergunta" :disabled="!novoQuestionario.nome"
                                        class="btn btn-secondary mt-4"><i class="bi bi-plus-lg"></i></button>
                                </div>
                            </div>

                            <div v-if="novoQuestionario.perguntas.length">
                                <div class="row mt-5">
                                    <div class="col-sm-12 mt-5">
                                        <div v-for="(pergunta, index) in novoQuestionario.perguntas" :key="pergunta.id"
                                            class="row mb-3">
                                            <div class="col-sm-12">
                                                <div class="row mb-3">
                                                    <div class="col-sm-10 fs-3 fw-bold">{{ pergunta.nome }}</div>
                                                    <div class="col-sm-2">
                                                        <button @click.prevent="deletarPergunta(index)"
                                                            class="btn btn-danger ms-2"><i
                                                                class="bi bi-trash-fill"></i></button>
                                                    </div>
                                                </div>

                                                <!-- Tipo da pergunta -->
                                                <div class="row mb-3">
                                                    <div class="col-sm-12">
                                                        <label for="tipo_pergunta">Tipo</label>
                                                        <select v-model="pergunta.tipo" class="form-select">
                                                            <option value="texto">Texto</option>
                                                            <option value="numerica">Numérica</option>
                                                            <option value="sim_nao">Sim/Não</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button @click="fecharModal" type="button" class="btn-padrao-cancelar">Cancelar</button>
                            <button type="submit" class="btn-padrao-sucesso">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { defineComponent } from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from "axios";

export default defineComponent({
    components: {
        AuthenticatedLayout
    },
    mounted() {
        this.apiQuestionarios();
    },
    data() {
        return {
            questionarios: [],
            mostrarModal: false,
            novoQuestionario: {
                nome: '',
                perguntas: []
            },
            novaPergunta: {
                nome: '',
                tipo: 'texto',
            }
        };
    },
    methods: {
        apiQuestionarios() {
            axios.post(route('questionario.api'))
                .then((response) => {
                    this.questionarios = response.data.questionarios;
                })

        },
        abrirModal() {
            this.mostrarModal = true;
        },
        fecharModal() {
            this.mostrarModal = false;
            this.resetarFormulario();
        },
        submitQuestionario() {
            axios.post(route('questionario.registrar'), { novoQuestionario: this.novoQuestionario })
                .then(() => {
                    console.log(this.novoQuestionario);
                    window.location.reload();
                })
                .catch(err => console.error('Erro ao salvar questionário:', err));
        },
        adicionarPergunta() {
            if (this.novaPergunta.nome) {
                const novaPergunta = {
                    id: Date.now(),
                    nome: this.novaPergunta.nome,
                    tipo: this.novaPergunta.tipo,
                };
                this.novoQuestionario.perguntas.push(novaPergunta);
                this.novaPergunta.nome = '';
            }
        },
        deletarPergunta(index) {
            this.novoQuestionario.perguntas.splice(index, 1);
        },
        resetarFormulario() {
            this.novoQuestionario = { nome: '', perguntas: [] };
            this.novaPergunta = { nome: '', tipo: 'texto' };
        },
        verQuestionario(id){
            this.$inertia.get(route('questionario.ver', { id }));
        }
    }
});
</script>

<style scoped>
.modal {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    width: 400px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.close {
    cursor: pointer;
    float: right;
    font-size: 1.5rem;
}
</style>

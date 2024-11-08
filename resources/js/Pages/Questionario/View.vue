<template>

    <Head title="Questionário" />

    <AuthenticatedLayout>
        <div class="row p-3 bg-white p-3 mb-3">
            <div class="col-sm-6 fs-1 fw-medium"> {{ questionario.nome }}
               
            </div>
            <div class="col-sm-6  justify-content-end d-flex fs-1 fw-medium">
                <span class="p-2 rounded fw-medium text-white"
                    :class="{ 'bg-success': questionario.status == 'Ativo', 'bg-danger-subtle': questionario.status == 'Inativo' }">
                    {{ questionario.status }}
                </span>
            </div>
        </div>

        <div class="row p-3">
            <div class="col-sm-6 fs-1 fw-normal"> Métricas do questionário
            </div>
            <div class="col-sm-1 fs-1 fw-normal"> <button @click="copiarLink(questionario)"
                    class="btn fs-1">
                    <i v-if="!this.copiado" class="bi bi-clipboard"></i>
                    <i v-else class="bi bi-clipboard-check"></i>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 p-3 m-3  fs-4">

                <div class="row">
                    <div class="col-sm-6">
                        <div class="row mb">
                            <div class="col-sm-12">Total de respostas</div>
                        </div>
                        <div class="row mb">
                            <div class="col-sm-12 fs-1 fw-bold">
                                {{ qtdRespostas }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row mb">
                            <div class="col-sm-12">Link</div>
                        </div>
                        <div class="row fw-bold">
                            <div class="col-sm-12">http://127.0.0.1:8000/questionario/{{ questionario.token }}</div>
                        </div>
                    </div>


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
    props: ['questionario', 'qtdRespostas'],
    mounted() {

    },
    data() {
        return {
            copiado: false,
        };
    },
    methods: {
        copiarLink(questionario) {
            const link = `http://127.0.0.1:8000/questionario/${questionario.token}`;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(link)
                    .then(() => {
                        console.log('Link copiado!')
                        this.copiado = true;
                    }).catch(err => console.error('Erro ao copiar: ', err));
            } else {
                console.error('Clipboard API não disponível.');
            }
        },
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

<template>

    <Head title="Questionário" />

    <div class="row p-5 m-5">

        <div class="row mb-5 align-items-center d-flex">
            <div class="col-sm-12 fs-1 fw-bold"> {{ questionario.nome }}</div>
        </div>

        <div class="row">
            <div class="col-sm-12 ">
                <div v-for="(pergunta, index) in questionario.perguntas" :key="pergunta.id" class="row mb-3">
                    <div class="col-sm-12 ">
                        <div class="row mb-3">
                            <div class="col-sm-12 fs-3"> {{ index + 1 }}. {{ pergunta.nome }}</div>
                        </div>

                        <div v-if="pergunta.tipo == 'texto'" class="row">
                            <div class="col-sm-12">
                                <textarea class="form-control" cols="30" rows="5"></textarea>
                            </div>
                        </div>

                        <div v-if="pergunta.tipo == 'multipla_escolha'" class="row">
                            <div class="col-sm-12">
                                <div class="row mb-2 align-items-center d-flex"
                                    v-for="(opcao, index) in pergunta.opcoes" :key="index">
                                    <div class="col-sm-12 fs-5">
                                        <input type="radio" :id="`opcao_pergunta_${index}`"
                                            :name="`opcao_pergunta_${pergunta.id}`" :value="opcao">
                                            {{ opcao.opcao }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 justify-content-center d-flex">
                <button @click="responderQuestionario(pergunta)" style="width: 200px;" type="submit" class="fw-medium btn btn-success">Concluído</button>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-sm-12 justify-content-center d-flex">
                <div class="row">
                    <div class="col-sm-12">
                        <br>
                        <span class="text-secondary justify-content-center d-flex">
                            <a target="_blank" rel="noopener noreferrer" ref="https://github.com/constpereiradev">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="grey"
                                    class="bi bi-github" viewBox="0 0 16 16">
                                    <path
                                        d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8" />
                                </svg>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import { defineComponent } from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from "axios";

export default defineComponent({
    components: {
        AuthenticatedLayout
    },
    props: ['questionario'],
    mounted() {

    },
    data() {
        return {
        };
    },
    methods: {
        responderQuestionario(pergunta) {
            alert(pergunta)
            axios.post(route('pergunta.registrar'), {
                pergunta: pergunta
            })
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

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="row p-3">
            <div class="col-sm-6 fs-1 fw-medium">Questionários</div>
            <div class="col-sm-6 fw-medium justify-content-end d-flex">
                <button @click="abrirModal" class="btn-padrao">+ Novo questionário</button>
            </div>
        </div>

        <div class="row p-3 m-3 border rounded bg-white">
            <div class="col-sm-12">
                <div class="row mb-3">
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
                <div v-for="pergunta in questionario.perguntas" class="row mb-2">
                    <div class="col-sm-6 fs-6 text-secondary"> {{ pergunta.nome }} </div>
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
    props: ['questionario'],
    mounted() {

    },
    data() {
        return {

        };
    },
    methods: {

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

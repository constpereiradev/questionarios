<template>

    <Head title="Dashboard" />

    <div class="row p-3 bg-white p-3 mb-3">
        <div class="col-sm-6 fs-1 fw-medium"> {{ questionario.nome }}
        </div>
    </div>

    <div class="row p-3 m-3 border rounded bg-white text-black">
    <div class="col-sm-12">
        <div v-for="(pergunta, index) in questionario.perguntas" class="row mb-4">

            <div class="col-sm-12 p-3 d-flex justify-content-center align-items-center fs-1 mb-2">
                <div class="d-flex w-75">
                    <div class="text-start me-2">{{ index + 1 }}.</div> 
                    <div class="text-start">{{ pergunta.nome }}</div> 
                </div>
            </div>

            <div v-if="pergunta.tipo == 'numerica'" class="row">
                <div class="col-sm-12 d-flex justify-content-center">
                    <div class="d-flex justify-content-around w-75">
                        <button v-for="numero in numeros" 
                                @click="registrarResposta(pergunta.id, numero)" 
                                class="btn btn-outline-primary me-2 fs-5">
                            {{ numero }}
                        </button>
                    </div>
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
            numeros: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10']
        };
    },
    methods: {
        registrarResposta(pergunta_id, resposta) {

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

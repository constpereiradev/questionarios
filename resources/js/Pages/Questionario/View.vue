<template>

    <Head title="Questionário" />

    <AuthenticatedLayout>
        <div class="row p-3 bg-white p-3 mb-3">
            <div class="col-sm-6 fs-1 fw-medium"> {{ questionario.nome }}</div>
            <div class="col-sm-6 justify-content-end d-flex fs-1 fw-medium">
                <span class="p-2 rounded fw-medium text-white"
                    :class="{ 'bg-success': questionario.status == 'Ativo', 'bg-danger-subtle': questionario.status == 'Inativo' }">
                    {{ questionario.status }}
                </span>
            </div>
        </div>

        <div class="row p-3">
            <div class="col-sm-6 fs-1 fw-normal"> Métricas do questionário
            </div>
            <div class="col-sm-1 fs-1 fw-normal">
                <button @click="copiarLink(questionario)" class="btn fs-1">
                    <i v-if="!this.copiado" class="bi bi-clipboard"></i>
                    <i v-else class="bi bi-clipboard-check"></i>
                </button>
            </div>
            <div class="col-sm-1 fs-1 fw-normal">
                <button @click="baixarQRCode" class="btn fs-1">
                    <i class="bi bi-qr-code"></i>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 p-3 m-3 fs-4">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row mb">
                            <div class="col-sm-12">Total de respostas</div>
                        </div>
                        <div class="row mb">
                            <div class="col-sm-12 fs-1 fw-bold">{{ qtdRespostas }}</div>
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

        <div v-if="qtdRespostas !== 0" class="row">
            <div class="col-sm-12">
                <div class="row mb-3 m-3 p-3 card bg-white shadow-sm" v-for="(grafico, index) in graficos" :key="index">

                    <div class="col-sm-12">
                        <div class="row p-3">
                            <div class="col-sm-12 fs-4 fw-medium">
                                {{ grafico.original.grafico.series[0].name }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <apexchart v-if="grafico.original.grafico" :key="index" type="bar" height="300"
                                    :options="grafico.original.grafico.chartOptions"
                                    :series="grafico.original.grafico.series"></apexchart>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        
        <div class="invisible">
            <QRCodeVue3 :width="200" :height="200" 
            :value="`http://127.0.0.1:8000/questionario/${questionario.token}`"
            :dotsOptions="{ type: 'dots', color: '#000000' }"
            :backgroundOptions="{ color: '#ffffff' }"
            :cornersSquareOptions="{ type: 'square', color: '#000000' }"
            :cornersDotOptions="{ type: 'square', color: '#000000' }"
            fileExt="png" :download="true"
            myclass="my-qur" imgclass="img-qr" downloadButton="my-button"
            :downloadOptions="{ name: 'questionario_qr', extension: 'png' }" />
        </div>
        

    </AuthenticatedLayout>
</template>

<script>
import { defineComponent } from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VueApexCharts from "vue3-apexcharts";
import QRCodeVue3 from "qrcode-vue3";

export default defineComponent({
    components: {
        AuthenticatedLayout,
        apexchart: VueApexCharts,
        QRCodeVue3,
    },
    props: ['questionario', 'qtdRespostas', 'graficos'],
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
        baixarQRCode() {
            const qrCodeDownloadButton = document.querySelector(".my-button");
            qrCodeDownloadButton.click(); 
        },
    }
});
</script>

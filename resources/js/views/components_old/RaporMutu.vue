<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <canvas id="nilai_komponen" style="min-height: 250px; height: 250px;"></canvas>
                        </div>
                        <div class="col-4">
                            <b-progress height="2rem" class="mt-1" :max="100" show-value>
                                <b-progress-bar :label="tercapai.input" :value="90" variant="success"></b-progress-bar>
                                <b-progress-bar :label="tak_tercapai.input" :value="10" variant="secondary">
                                </b-progress-bar>
                            </b-progress>
                            <b-progress height="2rem" class="mt-1" :max="100" show-value>
                                <b-progress-bar :label="tercapai.input" :value="50" variant="warning"></b-progress-bar>
                                <b-progress-bar :label="tak_tercapai.input" :value="50" variant="secondary">
                                </b-progress-bar>
                            </b-progress>
                            <b-progress height="2rem" class="mt-1" :max="100" show-value>
                                <b-progress-bar :label="tercapai.input" :value="50" variant="danger"></b-progress-bar>
                                <b-progress-bar :label="tak_tercapai.input" :value="50" variant="secondary">
                                </b-progress-bar>
                            </b-progress>
                            <b-progress height="2rem" class="mt-1" :max="100" show-value>
                                <b-progress-bar :label="tercapai.input" :value="50" variant="indigo"></b-progress-bar>
                                <b-progress-bar :label="tak_tercapai.input" :value="50" variant="secondary">
                                </b-progress-bar>
                            </b-progress>
                            <b-progress height="2rem" class="mt-1" :max="100" show-value>
                                <b-progress-bar :label="tercapai.input" :value="50" variant="fuchsia"></b-progress-bar>
                                <b-progress-bar :label="tak_tercapai.input" :value="50" variant="secondary">
                                </b-progress-bar>
                            </b-progress>
                            <b-progress height="2rem" class="mt-1" :max="100" show-value>
                                <b-progress-bar :label="tercapai.input" :value="50" variant="primary"></b-progress-bar>
                                <b-progress-bar :label="tak_tercapai.input" :value="50" variant="secondary">
                                </b-progress-bar>
                            </b-progress>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            tercapai: {
                input: 'asd 10',
                value: 10,
            }
        },
    }
}
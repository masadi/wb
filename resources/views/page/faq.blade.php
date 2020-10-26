@extends('layouts.app')
@section('title', 'Frequently Asked Questions')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingSatu">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSatu"
                                    aria-expanded="true" aria-controls="collapseSatu">
                                    Nomor Helpdesk Aplikasi Penjaminan Mutu SMK
                                </button>
                            </h5>
                        </div>

                        <div id="collapseSatu" class="collapse show" aria-labelledby="headingSatu"
                            data-parent="#accordion">
                            <div class="card-body">
                                <ol style="padding-left: 20px;">
                                    <li>Deni Warsa Setiawan : 0818 624 330</li>
                                    <li>Ahmad Aripin : 0812 2999 7730</li>
                                    <li>Wahyudin : 0815 6441 864</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingDua">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseDua"
                                    aria-expanded="false" aria-controls="collapseDua">
                                    Panduan Penggunaan Aplikasi
                                </button>
                            </h5>
                        </div>
                        <div id="collapseDua" class="collapse" aria-labelledby="headingDua" data-parent="#accordion">
                            <div class="card-body">
                                Sedang dalam pengembangan
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingTiga">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse"
                                    data-target="#collapseTiga" aria-expanded="false" aria-controls="collapseTiga">
                                    Pedoman Penjaminan Mutu SMK
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTiga" class="collapse" aria-labelledby="headingTiga"
                            data-parent="#accordion">
                            <div class="card-body">
                                Sedang dalam pengembangan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
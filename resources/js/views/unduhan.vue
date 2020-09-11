<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Pusat Unduhan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Pusat Unduhan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-download mr-1"></i>
                                    Pusat Unduhan
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="small-box text-center mr-2">
                                        <img src="/vendor/img/pdf.png" width="100">
                                        <div class="inner">Panduan Penggunaan Aplikasi</div>
                                        <div class="small-box-footer bg-primary">
                                            <a href="/downloads/Panduan APM SMK 2020 (Sekolah).pdf" target="_blank">Unduh <i class="fas fa-download"></i></a>
                                        </div>
                                    </div>
                                    <div class="small-box text-center mr-2">
                                        <img src="/vendor/img/pdf.png" width="100">
                                        <div class="inner">Pedoman Penjaminan Mutu SMK</div>
                                        <div class="small-box-footer bg-primary">
                                            Unduh <i class="fas fa-download"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
export default {
}
</script>

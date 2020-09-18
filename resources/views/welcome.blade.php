@extends('layouts.app')
@section('title', 'Beranda')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div id="apmIndikator" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#apmIndikator" data-slide-to="0" class="active"></li>
                        <li data-target="#apmIndikator" data-slide-to="1"></li>
                        <li data-target="#apmIndikator" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="d-block w-100"
                                src="{{asset('vendor/img/apm-1.png')}}"
                                alt="Alur Aplikasi Penjaminan Mutu">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100"
                                src="{{asset('vendor/img/apm-2.png')}}"
                                alt="Alur Aplikasi Akses Sekolah">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100"
                                src="{{asset('vendor/img/apm-3.png')}}"
                                alt="Alur Aplikasi Akses Tim Penjamin Mutu">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100"
                                src="{{asset('vendor/img/apm-4.png')}}"
                                alt="Alur Aplikasi Akses Direktorat">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100"
                                src="{{asset('vendor/img/apm-5.jpg')}}"
                                alt="Domain Aplikasi Penjaminan Mutu">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#apmIndikator" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#apmIndikator" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
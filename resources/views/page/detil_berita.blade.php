@extends('layouts.app')
@section('title', $post->judul)
@section('content')
<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <h2>{{$post->judul}}</h2>
                <ul class="list-inline list-unstyled">
                    <li class="list-inline-item"><span><i class="fas fa-user"></i> {{$post->user->name}} </span></li>
                    <li class="list-inline-item">|</li>
                    <li class="list-inline-item"><span><i class="fas fa-calendar"></i> {{Helper::TanggalIndo($post->created_at)}} </span></li>
                    <li class="list-inline-item">|</li>
                    <li class="list-inline-item"><span><i class="fas fa-tag"></i> {{$post->get_kategori()}} </span></li>
                </ul>
                <hr>
                {!!$post->isi_berita!!}
            </div>
        </div>
    </div>
    <div class="col-4">
        @include('layouts.sidebar')
    </div>
</div>
@endsection
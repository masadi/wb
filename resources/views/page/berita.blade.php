@extends('layouts.app')
@section('title', 'Berita')
@section('content')
<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <ul class="list-unstyled">
                    @forelse ($berita as $post)
                    <li class="media">
                        <a href="{{route('detil_berita', ['slug' => $post->slug])}}">
                            <img src="/vendor/img/logo.png" alt="{{$post->judul}}" class="img-responsive mr-3" width="200" height="200">
                        </a>
                        <div class="media-body">
                            <a href="{{route('detil_berita', ['slug' => $post->slug])}}"><h3 class="media-heading">{{$post->judul}}</h3></a>
                            <ul class="list-inline list-unstyled">
                                <li class="list-inline-item"><span><i class="fas fa-user"></i> {{$post->user->name}} </span></li>
                                <li class="list-inline-item">|</li>
                                <li class="list-inline-item"><span><i class="fas fa-calendar"></i> {{Helper::TanggalIndo($post->created_at)}} </span></li>
                                <li class="list-inline-item">|</li>
                                <li class="list-inline-item"><span><i class="fas fa-tag"></i> {{$post->get_kategori()}} </span></li>
                            </ul>
                            {{strip_tags(Str::limit($post->isi_berita, 180))}} <a href="{{route('detil_berita', ['slug' => $post->slug])}}" title="{{$post->judul}}">Selengkapnya &raquo;</a>
                        </div>
                    </li>
                    <hr>
                    @empty
                        <p class="text-center">Tidak ada data untuk ditampilkan</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-4">
        @include('layouts.sidebar')
    </div>
</div>
@endsection
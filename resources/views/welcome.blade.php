@extends('layouts.app')
@section('title', 'Beranda')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @for($i=0;$i<=10;$i++)
                <p>Homepage</p>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection
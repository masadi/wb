@extends('layouts.cetak')
@section('content')
<div class="container-fluid">
    <ol class="componen">
        @foreach($all_komponen as $komponen)
        <li>Komponen {{$komponen->nama}}
            <ol class="aspek">
                @foreach ($komponen->aspek as $aspek)
                <li>Aspek {{$aspek->nama}}
                    <ol class="instrumen">
                        @foreach ($aspek->instrumen as $instrumen)
                        <li>
                            <p>Petunjuk Pengisian: <br> {{$instrumen->petunjuk_pengisian}}</p>
                            <p>Pertanyaan: <br> {{$instrumen->pertanyaan}}</p>
                            <p>Pilihan Jawaban <br>
                                <ol class="jawaban">
                                    @foreach ($instrumen->subs as $sub)
                                    <li>{{$sub->pertanyaan}}</li>
                                    @endforeach
                                </ol>
                            </p>
                        </li>
                        @endforeach
                    </ol>
                </li>
                @endforeach
            </ol>
        </li>
        @endforeach
    </ol>
</div>
@endsection
@extends('layouts.cetak')
@section('content')
<htmlpagefooter name="page-footer">
	<table width="100%">
        <tr>
            <td width="33%">
                <span style="font-weight: bold; font-style: italic; font-size:7pt;">{DATE j-m-Y}</span>
            </td>
            <td width="33%" align="center" style="font-weight: bold; font-style: italic; font-size:7pt;">
                {PAGENO}/{nbpg}
            </td>
            <td width="33%" style="text-align: right;font-style: italic; font-size:7pt;">
                Dicetak dari Aplikasi APM SMK V.1.0.0
            </td>
        </tr>
    </table>
</htmlpagefooter>
<div class="container-fluid">
    <h3>Isian Instrumen Sekolah {{$laporan->sekolah->nama}}</h3>
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
                                    <?php
                                    $check = '';
                                    $class = '';
                                    if($instrumen->jawaban){
                                        if($instrumen->jawaban->nilai == $sub->urut){
                                            $check = '√';
                                            $class = 'text-bold';
                                        }
                                    }
                                    ?>
                                    <li class="{{$class}}">{{$sub->pertanyaan}} {{$check}}</li>
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
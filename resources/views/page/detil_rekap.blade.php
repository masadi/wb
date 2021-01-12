@extends('layouts.modal')

@section('title', $title)
@section('content')
<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th>No</th>
			<th>Nama Sekolah</th>
			<th>NPSN</th>
			<th>Sektor CoE</th>
			<th>Kabupaten/Kota</th>
			<th>Provinsi</th>
		</tr>
	</thead>
	<tbody>
	@forelse ($data_sekolah as $sekolah)
		<tr>
			<td class="text-center">{{$loop->iteration}}</td>
			<td>{{$sekolah->nama}}</td>
			<td class="text-center">{{$sekolah->npsn}}</td>
			<td>{{($sekolah->sekolah_sasaran) ? ($sekolah->sekolah_sasaran->sektor) ? $sekolah->sekolah_sasaran->sektor->nama : '-' : '-'}}</td>
			<td>{{$sekolah->kabupaten}}</td>
			<td>{{$sekolah->provinsi}}</td>
		</tr>
	@empty
		<tr>
			<td colspan="6" class="text-center">Tidak ada data untuk ditampilkan</td>
		</tr>
	@endforelse
	</tbody>
</table>
@endsection
@section('footer')
	<a class="btn btn-default" href="javascript:void(0)" data-dismiss="modal">Tutup</a>
@endsection
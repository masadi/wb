<table class="table">
    <tr>
        <td>Nama Sekolah</td>
        <td>: {{$sekolah->nama}}</td>
    </tr>
    <tr>
        <td>NPSN</td>
        <td>: {{$sekolah->npsn}}</td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>: {{$sekolah->alamat}}</td>
    </tr>
    <tr>
        <td>Kepala Sekolah</td>
        <td>: {{$sekolah->nama_kepsek}}</td>
    </tr>
</table>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="rekapitulasi-tab" data-toggle="tab" href="#rekapitulasi" role="tab" aria-controls="rekapitulasi" aria-selected="true">Rekapitulasi</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="rapor-mutu-tab" data-toggle="tab" href="#rapor-mutu" role="tab" aria-controls="rapor-mutu" aria-selected="false">Rapor Mutu</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="rekapitulasi" role="tabpanel" aria-labelledby="rekapitulasi-tab">
        <table class="table">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>10</th>
                    <th>11</th>
                    <th>12</th>
                    <th>13</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Jumlah Siswa</td>
                    <td class="kelas_10">-</td>
                    <td class="kelas_11">-</td>
                    <td class="kelas_12">-</td>
                    <td class="kelas_13">-</td>
                    <td class="jumlah_siswa">-</td>
                </tr>
            </tbody>
        </table>
        <table class="table">
            <thead>
                <tr>
                    <th>PTK</th>
                    <th>Guru</th>
                    <th>Tendik</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Jumlah</td>
                    <td class="ptk">-</td>
                    <td class="tendik">-</td>
                </tr>
            </tbody>
        </table>
        <table class="table">
            <thead>
                <tr>
                    <th>Kompetensi Keahlian</th>
                    <th>Jumlah PD</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>-</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade" id="rapor-mutu" role="tabpanel" aria-labelledby="rapor-mutu-tab">
        @if($sekolah->smk_coe)
            @if($user && $user->isAbleTo(['referensi-read', 'report-read']))
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header card-warna-{{strtolower($all_komponen[0]->nama)}}">
                            <h3 class="card-title">{{$all_komponen[0]->nama}}</h3>
                            <div class="card-tools">
                                <span class="avg">{{number_format($all_komponen[0]->all_nilai_komponen->avg('total_nilai'),2)}}</span>
                                <span class="bintang">{!! Helper::bintang_icon(number_format($all_komponen[0]->all_nilai_komponen->avg('total_nilai'),2), 'warning') !!}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($all_komponen[0]->aspek as $aspek)
                                <div class="col-lg-4 col-md-12">
                                    <div class="position-relative p-3 mb-3 card-warna-{{strtolower($all_komponen[0]->nama)}} text-center" style="height:125px">
                                        {{$aspek->nama}} <br>
                                        <div class="{{strtolower(Helper::clean($aspek->nama))}}">{{number_format($aspek->all_nilai_aspek->avg('total_nilai'),2)}}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 mb-4">
                    <div id="btn_toggle" style="display: none;">
                        <button class="show_satu button btn btn-warning btn-block" style="display: none;" data-query="nasional">Tampilkan Data Nasional</button>
                        <button class="show_dua button btn btn-danger btn-block" data-query="provinsi">Tampilkan Data <span class="nama_provinsi"></span></button>
                        <div style="width:100%; height:100%">
                            <canvas id="scatterChart" class="mb-3" style="display: none;"></canvas>
                        </div>
                    </div>
                    <canvas id="marksChart" style="max-width: 512px; margin: auto"></canvas>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header card-warna-{{strtolower($all_komponen[1]->nama)}}">
                            <h3 class="card-title">{{$all_komponen[1]->nama}}</h3>
                            <div class="card-tools">
                                <span class="avg">{{number_format($all_komponen[1]->all_nilai_komponen->avg('total_nilai'),2)}}</span>
                                <span class="bintang">{!! Helper::bintang_icon(number_format($all_komponen[1]->all_nilai_komponen->avg('total_nilai'),2), 'warning') !!}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($all_komponen[1]->aspek as $aspek)
                                <div class="col-lg-4 col-md-12">
                                    <div class="position-relative p-3 mb-3 card-warna-{{strtolower($all_komponen[1]->nama)}} text-center" style="height:125px">
                                        {{$aspek->nama}} <br>
                                        <div class="{{strtolower(Helper::clean($aspek->nama))}}">{{number_format($aspek->all_nilai_aspek->avg('total_nilai'),2)}}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header card-warna-{{strtolower($all_komponen[2]->nama)}}">
                            <h3 class="card-title">{{$all_komponen[2]->nama}}</h3>
                            <div class="card-tools">
                                <span class="avg">{{number_format($all_komponen[2]->all_nilai_komponen->avg('total_nilai'),2)}}</span>
                                <span class="bintang">{!! Helper::bintang_icon(number_format($all_komponen[2]->all_nilai_komponen->avg('total_nilai'),2), 'warning') !!}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($all_komponen[2]->aspek as $aspek)
                                <div class="col-lg-6 col-md-12">
                                    <div class="position-relative p-3 mb-3 card-warna-{{strtolower($all_komponen[2]->nama)}} text-center" style="height:125px">
                                        {{$aspek->nama}} <br>
                                        <div class="{{strtolower(Helper::clean($aspek->nama))}}">{{number_format($aspek->all_nilai_aspek->avg('total_nilai'),2)}}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header card-warna-{{strtolower($all_komponen[3]->nama)}}">
                            <h3 class="card-title">{{$all_komponen[3]->nama}}</h3>
                            <div class="card-tools">
                                <span class="avg">{{number_format($all_komponen[3]->all_nilai_komponen->avg('total_nilai'),2)}}</span>
                                <span class="bintang">{!! Helper::bintang_icon(number_format($all_komponen[3]->all_nilai_komponen->avg('total_nilai'),2), 'warning') !!}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($all_komponen[3]->aspek as $aspek)
                                <div class="col-12">
                                    <div class="position-relative p-3 mb-3 card-warna-{{strtolower($all_komponen[3]->nama)}} text-center" style="height:125px">
                                        {{$aspek->nama}} <br>
                                        <div class="{{strtolower(Helper::clean($aspek->nama))}}">{{number_format($aspek->all_nilai_aspek->avg('total_nilai'),2)}}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header card-warna-{{strtolower($all_komponen[4]->nama)}}">
                            <h3 class="card-title">{{$all_komponen[4]->nama}}</h3>
                            <div class="card-tools">
                                <span class="avg">{{number_format($all_komponen[4]->all_nilai_komponen->avg('total_nilai'),2)}}</span>
                                <span class="bintang">{!! Helper::bintang_icon(number_format($all_komponen[4]->all_nilai_komponen->avg('total_nilai'),2), 'warning') !!}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($all_komponen[4]->aspek as $aspek)
                                <div class="col-lg-6 col-md-12">
                                    <div class="position-relative p-3 mb-3 card-warna-{{strtolower($all_komponen[4]->nama)}} text-center" style="height:125px">
                                        {{$aspek->nama}} <br>
                                        <div class="{{strtolower(Helper::clean($aspek->nama))}}">{{number_format($aspek->all_nilai_aspek->avg('total_nilai'),2)}}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title">Kinerja (Performance)</h3>
                            <div class="card-tools">
                                <?php
                                $rerata_kinerja = [];
                                foreach($komponen_kinerja as $kinerja){
                                    $rerata_kinerja[] = number_format($kinerja->all_nilai_komponen->avg('total_nilai'),2);
                                }
                                ?>
                                <span class="avg_kinerja">{{number_format(array_sum($rerata_kinerja) / count($rerata_kinerja),2)}}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($komponen_kinerja as $kinerja)
                                <div class="col-lg-4 col-md-12">
                                    <div class="position-relative p-3 mb-3 card-warna-{{strtolower($kinerja->nama)}} text-center" style="height:125px">
                                        {{$kinerja->nama}} <br>
                                        <div class="kinerja-{{strtolower(Helper::clean($kinerja->nama))}}">{{number_format($kinerja->all_nilai_komponen->avg('total_nilai'),2)}}</div>
                                        <span class="bintang-kinerja">{!! Helper::bintang_icon(number_format($kinerja->all_nilai_komponen->avg('total_nilai'),2), 'warning') !!}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title">Dampak (Impact)</h3>
                            <div class="card-tools">
                                <?php
                                $rerata_dampak = [];
                                foreach($komponen_dampak as $dampak){
                                    $rerata_dampak[] = number_format($dampak->all_nilai_komponen->avg('total_nilai'),2);
                                }
                                ?>
                                <span class="avg_dampak">{{number_format(array_sum($rerata_dampak) / count($rerata_dampak),2)}}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($komponen_dampak as $dampak)
                                <div class="col-lg-6 col-md-12">
                                    <div class="position-relative p-3 mb-3 card-warna-{{strtolower($dampak->nama)}} text-center" style="height:125px">
                                        {{$dampak->nama}} <br>
                                        <div class="dampak-{{strtolower(Helper::clean($dampak->nama))}}">{{number_format($dampak->all_nilai_komponen->avg('total_nilai'),2)}}</div>
                                        <span class="bintang-dampak">{!! Helper::bintang_icon(number_format($dampak->all_nilai_komponen->avg('total_nilai'),2), 'warning') !!}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 mb-4">
                    <div id="btn_toggle" style="display: none;">
                        <button class="show_satu button btn btn-warning btn-block" style="display: none;" data-query="nasional">Tampilkan Data Nasional</button>
                        <button class="show_dua button btn btn-danger btn-block" data-query="provinsi">Tampilkan Data <span class="nama_provinsi"></span></button>
                        <div style="width:100%; height:100%">
                            <canvas id="scatterChart" class="mb-3" style="display: none;"></canvas>
                        </div>
                    </div>
                    <canvas id="marksChart" style="max-width: 512px; margin: auto"></canvas>
                </div>
            </div>
            @endif
        @else
        <p class="text-center mt-3">{{$sekolah->nama}} belum ditetapkan menjadi SMK CoE</p>
        @endif
    </div>
</div>
<style>
    .card-warna-{{strtolower($all_komponen[0]->nama)}} {
        background:#d9434e !important;
        color:white;
    }
    .card-warna-{{strtolower($all_komponen[1]->nama)}} {
        background:#1fac4d !important;
        color:white;
    }
    .card-warna-{{strtolower($all_komponen[2]->nama)}} {
        background:#48cfc1 !important;
        color:white;
    }
    .card-warna-{{strtolower($all_komponen[3]->nama)}} {
        background:#9398ec !important;
        color:white;
    }
    .card-warna-{{strtolower($all_komponen[4]->nama)}} {
        background:#d27b25 !important;
        color:white;
    }
  </style>
<script>
var params = {
    provinsi_id : '{{$sekolah->provinsi_id}}',
    sekolah_id: '{{$sekolah->sekolah_id}}',
}
@if($sekolah->smk_coe)
getRaporMutu(params);
@endif
function getRaporMutu(params){
    $.ajax({
		url: '{{route('api.rapor_sekolah')}}',
		type: 'post',
		/*data: {
            provinsi_id : $('#provinsi_id').val().trim(),
            sekolah_id: ini,
        },*/
        data: params,
		success: function(response){
            $( ".show_satu" ).toggle();
            $( ".show_dua" ).toggle();
            $('.nama_provinsi').html(response.nama_wilayah);
            if(response.sekolah){
                $('#rekap_coe').hide();
                $('#rekap_sekolah').show();
                $('#scatterChart').show();
                $('#btn_toggle').show();
                $('.nama_sekolah').html(response.sekolah.nama);
                $('.alamat_sekolah').html(response.sekolah.alamat);
                $('.telp').html(response.sekolah.no_telp);
                $('.fax').html(response.sekolah.no_fax);
                $('.laman').html(response.sekolah.website);
                $('.kepsek').html(response.sekolah.nama_kepsek);
                $('.proli').html('');
                $('.kelas_10').html(response.sekolah.kelas_10_count);
                $('.kelas_11').html(response.sekolah.kelas_11_count);
                $('.kelas_12').html(response.sekolah.kelas_12_count);
                $('.kelas_13').html(response.sekolah.kelas_13_count);
                $('.jumlah_siswa').html(response.sekolah.anggota_rombel_count);
                $('.ptk').html(response.sekolah.guru_count);
                $('.tendik').html(response.sekolah.tendik_count);
                $('.avg_kinerja').html(response.rerata_komponen_kinerja);
                $('.avg_dampak').html(response.rerata_komponen_dampak);
                $.each(response.group_komponen.all_kinerja.nama, function (i, item) {
                    var a = $('h1').hasClass('kinerja-'+item);
                    $('h1.kinerja-'+item).html(response.group_komponen.all_kinerja.nilai[i]);
                })
                $.each(response.group_komponen.all_dampak.nama, function (i, item) {
                    var a = $('h1').hasClass('dampak-'+item);
                    $('h1.dampak-'+item).html(response.group_komponen.all_dampak.nilai[i]);
                })
                $.each($('.bintang-kinerja'), function(i, val) {
                    $(this).html(response.group_komponen.all_kinerja.bintang[i])
                })
                $.each($('.bintang-dampak'), function(i, val) {
                    $(this).html(response.group_komponen.all_dampak.bintang[i])
                })
                $.each($('.avg'), function(i, val) {
                    $(this).html(response.nilai_komponen_kotak[i].nilai)
                })
                $.each($('.bintang'), function(i, val) {
                    $(this).html(response.nilai_komponen_kotak[i].bintang)
                })
                $.each(response.nilai_komponen_kotak, function (i, item) {
                    $.each(item.nilai_aspek, function (key, val) {
                        var a = $('h1').hasClass(key);
                        $('h1.'+key).html(val);
                    })
                })
                tampilChart(response)
            } else {
                $('#rekap_sekolah').hide();
                $('#scatterChart').hide();
                $('#rekap_coe').show();
            }
		}
	});
}
$( ".button" ).click(function() {
    var data = $(this).data('query');
    var params;
    if(data === 'nasional'){
        params = {
            all_provinsi: 1,
            provinsi_id : '{{$sekolah->provinsi_id}}',
            sekolah_id: '{{$sekolah->sekolah_id}}',
        }
    } else {
        params = {
            all_provinsi: 0,
            provinsi_id : '{{$sekolah->provinsi_id}}',
            sekolah_id: '{{$sekolah->sekolah_id}}', 
        }
    }
    getRaporMutu(params);
});
var radarChart;
var scatterChart;
function tampilChart(data){
    if(data.counting){
        $.each($('td.rekap'), function(i, val) {
            $(this).html(data.counting[i])
        });
    }
    if(radarChart){
        radarChart.destroy();
    }
    var marksCanvas = document.getElementById("marksChart");
    var marksData = {
        labels: data.nama_komponen,
        datasets: [{
            label: "Tahun 2020",
            backgroundColor: "transparent",
            data: data.nilai_komponen,
            borderColor: "rgba(200,0,0,0.6)",
            fill: false,
            radius: 6,
            pointRadius: 6,
            pointBorderWidth: 3,
            pointBackgroundColor: "orange",
            pointBorderColor: "rgba(200,0,0,0.6)",
            pointHoverRadius: 10,
        }]
    };
    radarChart = new Chart(marksCanvas, {
        type: 'radar',
        data: marksData,
        options: {
            legend: {
                position: 'top',
            },
            title: {
                display: false,
                text: 'Chart.js Outcome Graph'
            },
            scale: {
                ticks: {
                    beginAtZero: true,
                    min: 0,
                    max: 100,
                }
            },
            tooltips:{
                enabled:true,
            },
        }
    });
    if(data.group_komponen){
        function totalNilai(total, num) {
            return total + num;
        }
        var responseApi = data;
        var dataLainnya = responseApi.all_sekolah;
        var smkLainnya = [];
        $.each(dataLainnya, function (k, v) {
            var set_nilai_kinerja = [];
            $.each(v.nilai_kinerja, function(a,b){
                set_nilai_kinerja.push(parseFloat(b.total_nilai).toFixed(2));
            })
            var set_nilai_dampak = [];
            $.each(v.nilai_dampak, function(c,d){
                set_nilai_dampak.push(parseFloat(d.total_nilai).toFixed(2));
            })
            var total_nilai_kinerja = parseFloat(set_nilai_kinerja.reduce(totalNilai, 0)).toFixed(2);// / set_nilai_kinerja.length;
            var total_nilai_dampak = parseFloat(set_nilai_dampak.reduce(totalNilai, 0)).toFixed(2);// / set_nilai_dampak.length;
            smkLainnya.push({
                x: total_nilai_kinerja - 50,
                y: total_nilai_dampak - 50,
            });
        })
        var color = Chart.helpers.color;
        var scatterChartData = {
            datasets: [{
                label: responseApi.sekolah.nama,
                borderColor: window.chartColors.red,
                backgroundColor: color(window.chartColors.red).alpha(0.2).rgbString(),
                data: [{
                    x: (data.group_komponen.all_kinerja.nilai_scatter > 0) ? data.group_komponen.all_kinerja.nilai_scatter - 50 : 0,
                    y: (data.group_komponen.all_dampak.nilai_scatter > 0) ? data.group_komponen.all_dampak.nilai_scatter - 50 : 0
                }]
            }, {
                label: 'SMK Lainnya',
                borderColor: window.chartColors.blue,
                backgroundColor: color(window.chartColors.blue).alpha(0.2).rgbString(),
                data: smkLainnya,
            }]
        };
        if(scatterChart){
            scatterChart.destroy();
        }
        var ctx = document.getElementById('scatterChart').getContext('2d');
        scatterChart = new Chart(ctx, {
            type: 'scatter',
            data: scatterChartData,
            options: {
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            var nilai_kinerja = tooltipItem.xLabel;//Math.round(tooltipItem.yLabel * 100) / 100;
                            var nilai_dampak = tooltipItem.yLabel;//Math.round(tooltipItem.xLabel * 100) / 100;
                            var label_nama_sekolah = responseApi.all_sekolah[tooltipItem.index];
                            if(tooltipItem.index === 0){
                                label = responseApi.sekolah.nama;
                            } else {
                                if(label_nama_sekolah){
                                    label = label_nama_sekolah.nama+' - '+label_nama_sekolah.kabupaten+' - '+label_nama_sekolah.provinsi;
                                } 
                            }
                            if (label) {
                                label += ': ';
                            } 
                            
                            nilai_dampak = (nilai_dampak) ? nilai_dampak + 50 : 0;
                            nilai_kinerja = (nilai_kinerja) ? nilai_kinerja + 50 : 0;
                            label += 'Kinerja ('+nilai_kinerja+') | Dampak ('+nilai_dampak+')';
                            return label;
                        }
                    },
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            min: -50,
                            max: 50,
                            //stepSize: 5,
                            callback: v => v == 0 ? 'Dampak Rendah' : ''
                        },
						type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
						display: true,
						position: 'bottom',
                        id: 'x-axis-1',
                        gridLines: {
                            drawTicks: false,
                            drawOnChartArea:true,
                            color: 'rgba(0, 0, 0, 0)',
                            zeroLineColor: 'rgba(0,0,0,0.50)',
						},
					}, {
                        ticks: {
                            min: -50,
                            max: 50,
                            //stepSize: 5,
                            callback: v => v == 0 ? 'Dampak Tinggi' : ''
                        },
						type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
						display: true,
						position: 'top',
						reverse: true,
						id: 'x-axis-2',

						// grid line settings
						gridLines: {
                            drawTicks: false,
                            drawOnChartArea:true,
                            color: 'rgba(0, 0, 0, 0)',
                            zeroLineColor: 'rgba(0,0,0,0.50)',
						},
                    }],
                    yAxes: [{
                        ticks: {
                            min: -50,
                            max: 50,
                            //stepSize: 5,
                            callback: v => v == 0 ? 'Kinerja Rendah' : ''
                        },
						type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
						display: true,
						position: 'left',
                        id: 'y-axis-1',
                        gridLines: {
                            drawTicks: false,
                            drawOnChartArea:true,
                            color: 'rgba(0, 0, 0, 0)',
                            zeroLineColor: 'rgba(0,0,0,0.50)',
						},
					}, {
                        ticks: {
                            min: -50,
                            max: 50,
                            //stepSize: 5,
                            callback: v => v == 0 ? 'Kinerja Tinggi' : ''
                        },
						type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
						display: true,
						position: 'right',
						reverse: true,
						id: 'y-axis-2',
                        gridLines: {
                            drawTicks: false,
                            drawOnChartArea:true,
                            color: 'rgba(0, 0, 0, 0)',
                            zeroLineColor: 'rgba(0,0,0,0.50)',
						},
					}],
                },
                title: {
                    display: true,
                    text: 'Pembandingan Kinerja dan Impact',
                },
            }
        });
        if(data.output){
            scatterChart.update();
        }
    }
}
</script>
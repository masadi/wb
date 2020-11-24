@extends('layouts.app_rapor')
@section('title', 'Rekapitulasi Rapor Mutu')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title text-center float-none"><strong>REKAPITULASI RAPOR MUTU SEKOLAH</strong></h3>
            </div>
            <div class="card-body">
                <form id="form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Filter Provinsi</label>
                                <select class="form-control select2" id="provinsi_id" style="width: 100%;">
                                    <option value="">Semua Provinsi</option>
                                    @foreach($all_wilayah as $wilayah)
                                    <option value="{{$wilayah->kode_wilayah}}">{{$wilayah->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Filter Kabupaten/Kota</label>
                                <select class="form-control select2" id="kabupaten_id" style="width: 100%;">
                                    <option value="">Semua Kab/Kota</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="4" class="text-center">Nasional</th>
                                </tr>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Tingkat Kinerja</th>
                                    <th class="text-center">Predikat</th>
                                    <th class="text-center">Jml Sekolah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td class="text-center">91-100</td>
                                    <td class="text-center">Sangat Baik</td>
                                    <td class="text-center sangat_baik_nasional">0</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td class="text-center">76-90</td>
                                    <td class="text-center">Baik</td>
                                    <td class="text-center baik_nasional">0</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td class="text-center">61-75</td>
                                    <td class="text-center">Cukup Baik</td>
                                    <td class="text-center cukup_baik_nasional">0</td>
                                </tr>
                                <tr>
                                    <td class="text-center">4</td>
                                    <td class="text-center">46-60</td>
                                    <td class="text-center">Kurang Baik</td>
                                    <td class="text-center kurang_baik_nasional">0</td>
                                </tr>
                                <tr>
                                    <td class="text-center">5</td>
                                    <td class="text-center">0-45</td>
                                    <td class="text-center">Tidak Baik</td>
                                    <td class="text-center tidak_baik_nasional">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="4" class="text-center">Provinsi</th>
                                </tr>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Tingkat Kinerja</th>
                                    <th class="text-center">Predikat</th>
                                    <th class="text-center">Jml Sekolah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td class="text-center">91-100</td>
                                    <td class="text-center">Sangat Baik</td>
                                    <td class="text-center sangat_baik_provinsi">0</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td class="text-center">76-90</td>
                                    <td class="text-center">Baik</td>
                                    <td class="text-center baik_provinsi">0</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td class="text-center">61-75</td>
                                    <td class="text-center">Cukup Baik</td>
                                    <td class="text-center cukup_baik_provinsi">0</td>
                                </tr>
                                <tr>
                                    <td class="text-center">4</td>
                                    <td class="text-center">46-60</td>
                                    <td class="text-center">Kurang Baik</td>
                                    <td class="text-center kurang_baik_provinsi">0</td>
                                </tr>
                                <tr>
                                    <td class="text-center">5</td>
                                    <td class="text-center">0-45</td>
                                    <td class="text-center">Tidak Baik</td>
                                    <td class="text-center tidak_baik_provinsi">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="4" class="text-center">Kab/Kota</th>
                                </tr>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Tingkat Kinerja</th>
                                    <th class="text-center">Predikat</th>
                                    <th class="text-center">Jml Sekolah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td class="text-center">91-100</td>
                                    <td class="text-center">Sangat Baik</td>
                                    <td class="text-center sangat_baik_kabupaten">0</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td class="text-center">76-90</td>
                                    <td class="text-center">Baik</td>
                                    <td class="text-center baik_kabupaten">0</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td class="text-center">61-75</td>
                                    <td class="text-center">Cukup Baik</td>
                                    <td class="text-center cukup_baik_kabupaten">0</td>
                                </tr>
                                <tr>
                                    <td class="text-center">4</td>
                                    <td class="text-center">46-60</td>
                                    <td class="text-center">Kurang Baik</td>
                                    <td class="text-center kurang_baik_kabupaten">0</td>
                                </tr>
                                <tr>
                                    <td class="text-center">5</td>
                                    <td class="text-center">0-45</td>
                                    <td class="text-center">Tidak Baik</td>
                                    <td class="text-center tidak_baik_kabupaten">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="chartNasional" class="chartdiv"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js_file')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<!-- Resources -->
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<!-- Styles -->
<style>
    .chartdiv {
        width: 100%;
        height: 500px;
    }
</style>
@endsection
@section('js')
<script>
$('.select2').select2();
function setData(response){
    $('.sangat_baik_nasional').html(response.data.rekap_nasional.sangat_baik)
    $('.baik_nasional').html(response.data.rekap_nasional.baik)
    $('.cukup_baik_nasional').html(response.data.rekap_nasional.cukup_baik)
    $('.kurang_baik_nasional').html(response.data.rekap_nasional.kurang_baik)
    $('.tidak_baik_nasional').html(response.data.rekap_nasional.tidak_baik)

    $('.sangat_baik_provinsi').html(response.data.rekap_provinsi.sangat_baik)
    $('.baik_provinsi').html(response.data.rekap_provinsi.baik)
    $('.cukup_baik_provinsi').html(response.data.rekap_provinsi.cukup_baik)
    $('.kurang_baik_provinsi').html(response.data.rekap_provinsi.kurang_baik)
    $('.tidak_baik_provinsi').html(response.data.rekap_provinsi.tidak_baik)

    $('.sangat_baik_kabupaten').html(response.data.rekap_kabupaten.sangat_baik)
    $('.baik_kabupaten').html(response.data.rekap_kabupaten.baik)
    $('.cukup_baik_kabupaten').html(response.data.rekap_kabupaten.cukup_baik)
    $('.kurang_baik_kabupaten').html(response.data.rekap_kabupaten.kurang_baik)
    $('.tidak_baik_kabupaten').html(response.data.rekap_kabupaten.tidak_baik)
}
var chartNasional;
$.get( "{{route('api.rekapitulasi.wilayah')}}", function( response ) {
    console.log(response);
    setData(response)
    tampilChart(response)
    //tampilChart(data)
});
$('#provinsi_id').change(function(){
    var ini = $(this).val();
    if(ini){
        $.ajax({
            url: '{{route('api.filter_wilayah')}}',
            type: 'post',
            data: {
                id_level_wilayah: 1,
                kode_wilayah: ini.trim(),
            },
            success: function(response){
                $('#kabupaten_id').html('<option value="">Semua Kab/Kota</option>');
                $.each(response.output.result, function (i, item) {
                    $('#kabupaten_id').append($('<option>', { 
                        value: item.value,
                        text : item.text
                    }));
                });
                $.ajax({
                    url: '{{route('api.rekapitulasi.wilayah')}}',
                    type: 'post',
                    data: {
                        provinsi_id: ini.trim(),
                    },
                    success: function(response){
                        console.log(response);
                        /*$('.sangat_baik_nasional').html(response.data.rekap_nasional.sangat_baik)
                        $('.baik_nasional').html(response.data.rekap_nasional.baik)
                        $('.cukup_baik_nasional').html(response.data.rekap_nasional.cukup_baik)
                        $('.kurang_baik_nasional').html(response.data.rekap_nasional.kurang_baik)
                        $('.tidak_baik_nasional').html(response.data.rekap_nasional.tidak_baik)

                        $('.sangat_baik_provinsi').html(response.data.rekap_provinsi.sangat_baik)
                        $('.baik_provinsi').html(response.data.rekap_provinsi.baik)
                        $('.cukup_baik_provinsi').html(response.data.rekap_provinsi.cukup_baik)
                        $('.kurang_baik_provinsi').html(response.data.rekap_provinsi.kurang_baik)
                        $('.tidak_baik_provinsi').html(response.data.rekap_provinsi.tidak_baik)

                        $('.sangat_baik_kabupaten').html(response.data.rekap_kabupaten.sangat_baik)
                        $('.baik_kabupaten').html(response.data.rekap_kabupaten.baik)
                        $('.cukup_baik_kabupaten').html(response.data.rekap_kabupaten.cukup_baik)
                        $('.kurang_baik_kabupaten').html(response.data.rekap_kabupaten.kurang_baik)
                        $('.tidak_baik_kabupaten').html(response.data.rekap_kabupaten.tidak_baik)*/
                        setData(response)
                        tampilChart(response)
                    }
                });
            }
        })
    } else {
        $.get( "{{route('api.rekapitulasi.wilayah')}}", function( response ) {
            console.log(response);
            setData(response)
            tampilChart(response)
            //tampilChart(data)
        });
    }
});
$('#kabupaten_id').change(function(){
    var ini = $(this).val();
    $.ajax({
        url: '{{route('api.rekapitulasi.wilayah')}}',
        type: 'post',
        data: {
            provinsi_id: $('#provinsi_id').val().trim(),
            kabupaten_id: ini.trim(),
        },
        success: function(response){
            setData(response)
            tampilChart(response)
        }
    });
});
function tampilChart(data){
    am4core.ready(function() {
    
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end
    //am4core.options.autoDispose = true;
    // Create chart instance

    chartNasional = am4core.create("chartNasional", am4charts.XYChart3D);
    console.log(data);
    chartNasional.data = data.data.chart;
    /*chartNasional.data = [{
        "komponen": "Sangat Baik",
        "nasional": 0,
        "provinsi": 0,
        "kabupaten": 0,
    }, {
        "komponen": "Baik",
        "nasional": 0,
        "provinsi": 0,
        "kabupaten": 0,
    }, {
        "komponen": "Cukup Baik",
        "nasional": 0,
        "provinsi": 0,
        "kabupaten": 0,
    }, {
        "komponen": "Kurang",
        "nasional": 0,
        "provinsi": 0,
        "kabupaten": 0,
    }, {
        "komponen": "Tidak Baik",
        "nasional": 0,
        "provinsi": 0,
        "kabupaten": 0,
    }];*/
    // Create axes
    var categoryAxisNasional = chartNasional.xAxes.push(new am4charts.CategoryAxis());
    categoryAxisNasional.dataFields.category = "komponen";
    categoryAxisNasional.renderer.grid.template.location = 0;
    categoryAxisNasional.renderer.minGridDistance = 30;
    
    var valueAxisNasional = chartNasional.yAxes.push(new am4charts.ValueAxis());
    valueAxisNasional.title.text = "Jumlah Sekolah";
    valueAxisNasional.renderer.labels.template.adapter.add("text", function(text) {
      return text;
    });
    valueAxisNasional.min = 0;
    /*valueAxisNasional.min = 0;
    valueAxisNasional.max = 100;
    valueAxisNasional.strictMinMax = true;*/
    
    // Create series
    var seriesNasional = chartNasional.series.push(new am4charts.ColumnSeries3D());
    seriesNasional.dataFields.valueY = "kabupaten";
    seriesNasional.dataFields.categoryX = "komponen";
    seriesNasional.name = "Jumlah Sekolah Kab/Kota";
    seriesNasional.clustered = false;
    seriesNasional.columns.template.tooltipText = "Jumlah Sekolah Kab/Kota: [bold]{valueY}[/]";
    seriesNasional.columns.template.fillOpacity = 0.9;
    seriesNasional.columns.template.showTooltipOn = "always";
    seriesNasional.tooltip.pointerOrientation = "top";
    seriesNasional.columns.template.tooltipY = 0;
    seriesNasional.columns.template.width = am4core.percent(50);
    seriesNasional.columns.template.fill = am4core.color(window.chartColors.red);
    var series2Nasional = chartNasional.series.push(new am4charts.ColumnSeries3D());
    series2Nasional.dataFields.valueY = "provinsi";
    series2Nasional.dataFields.categoryX = "komponen";
    series2Nasional.name = "Jumlah Sekolah Provinsi";
    series2Nasional.clustered = false;
    series2Nasional.columns.template.tooltipText = "Jumlah Sekolah Provinsi: [bold]{valueY}[/]";
    series2Nasional.columns.template.showTooltipOn = "always";
    series2Nasional.tooltip.pointerOrientation = "top";
    series2Nasional.columns.template.tooltipY = 0;
    series2Nasional.columns.template.width = am4core.percent(65);
    series2Nasional.columns.template.fill = am4core.color(window.chartColors.blue);
    var series3Nasional = chartNasional.series.push(new am4charts.ColumnSeries3D());
    series3Nasional.dataFields.valueY = "nasional";
    series3Nasional.dataFields.categoryX = "komponen";
    series3Nasional.name = "Jumlah Sekolah Nasional";
    series3Nasional.clustered = false;
    series3Nasional.columns.template.tooltipText = "Jumlah Sekolah Nasional: [bold]{valueY}[/]";
    series3Nasional.columns.template.showTooltipOn = "always";
    series3Nasional.tooltip.pointerOrientation = "top";
    series3Nasional.columns.template.tooltipY = 0;
    series3Nasional.columns.template.fill = am4core.color(window.chartColors.green);
    
    }); // end am4core.ready()
    if(chartNasional){
        //chartNasional.data = data.data.chart;
    }
}
</script>
@endsection
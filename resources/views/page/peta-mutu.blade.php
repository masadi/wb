@extends('layouts.app_full')
@section('title', 'Peta Mutu')
@section('content')
<div class="row">
    <div class="col-12 m-0 p-0">
        <div id="mapid"></div>
    </div>
</div>
@endsection
@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
<style>
    #mapid { min-height: 786px; }
</style>
@endsection
@section('js')
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>

<script>
    var bounds = new L.LatLngBounds();
	var map = L.map('mapid').setView([{{ $leaflet['map_center_latitude'] }}, {{ $leaflet['map_center_longitude'] }}], {{ $leaflet['zoom_level'] }});
    var popup = L.popup();
    var baseUrl = "{{ url('/') }}";

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    $.get('{{ $api_url_map }}')
    .then(function (response) {
        var getData = response.data;
        //return false;
        /*L.geoJson(response.data, {
			onEachFeature: function (feature, peta) {
                console.log(feature);
				var style = {fillColor: "#543199",
                color: "#543199",
                "weight": 1,
                "opacity":0.6,
                "fillOpacity": 0.5,
                };
                peta.setStyle(style);
                var popup = L.popup();
                peta.on('click', function(e){
                    popup.setLatLng(e.latlng).setContent("<ul class='list-group list-group no-padding'>"+
                        "<li class='list-group-item list-group-item-info'><b>Aceh</b></li>"+
                        "<li class='list-group-item list-group-item-warning'><a href='http://103.40.55.226/dashboard/detil/060000'>230 SMK</a></li>"+
                        "</ul>")
                    .openOn(map);
                });
            }
            }).addTo(map);*/
        $.each(getData, function(item, value){
            //console.log(value);
            //return false;
            var randomColor = Math.floor(Math.random()*16777215).toString(16);
            $.getJSON("{{url('geojson')}}/"+value.json,function(result){
                L.geoJson(result.data, {
                    onEachFeature: function (feature, peta) {
                        var style = {
                            fillColor: "#" + randomColor,
                            color: "#" + randomColor,
                            "weight": 1,
                            "opacity":0.6,
                            "fillOpacity": 0.5,
                        };
                        peta.setStyle(style);
                        var popup = L.popup();
                        peta.on('click', function(e){
                            $.get('{{url('api/peta/sekolah/'.$id_level_wilayah)}}/'+value.json).then(function(sekolah){
                                var contentTooltips;
                                if(sekolah.data.id_level_wilayah == 1){
                                    contentTooltips = "<ul class='list-group list-group no-padding'>"+
                                            "<li class='list-group-item list-group-item-info'><a href='{{url('page/peta-mutu')}}/{{$id_level_wilayah}}/"+value.json+"'><b>"+value.nama+"</b></a></li>"+
                                            "<li class='list-group-item list-group-item-warning'>Jumlah SMK : "+sekolah.data.jml_smk+"<br>SMK Coe : "+sekolah.data.smk_coe+"</li>"+
                                            "</ul>";
                                } else {
                                    //http://apm-smk.local:8002/page/progres-data/3/050100
                                    contentTooltips = "<ul class='list-group list-group no-padding'>"+
                                            "<li class='list-group-item list-group-item-info'><a href='{{url('page/progres-data')}}/3/"+value.json+"'><b>"+value.nama+"</b></a></li>"+
                                            "<li class='list-group-item list-group-item-warning'>Jumlah SMK : "+sekolah.data.jml_smk+"<br>SMK Coe : "+sekolah.data.smk_coe+"</li>"+
                                            "</ul>";
                                }
                                popup
                                .setLatLng(e.latlng)
                                .setContent(contentTooltips)
                                .openOn(map);
                            }).catch(function (error) {
                                console.log(error);
                            });
                        });
                    }
                }).addTo(map);
            });
        });
    })
    .catch(function (error) {
        console.log(error);
    });
</script>
@endsection
@extends('layouts.app_full')
@section('title', 'Peta Mutu')
@section('content')
<div class="row">
    <div class="col-12 m-0 p-0">
        <div id="mapid" style="display: none;"></div>
        <img class="d-block w-100" src="{{asset('vendor/img/Under-Construction.png')}}" alt="Sedang Dalam Pengembangan">
    </div>
</div>
@endsection
@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
<style>
    #mapid { min-height: 700px; }
</style>
@endsection
@section('js')
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>

<script>
    var bounds = new L.LatLngBounds();
	var colors = ["#543199","#9f504d","#0f14ed","#641922","#0aafe4","#e834ca","#1c9d1a","#433be5","#4b3f9d","#85df3d","#ea0cb6","#827f5c","#671715","#77220d","#aa6eb6","#81b3ec","#12f1b8","#52b3bf","#52263d","#db1bef","#42e267","#5f008e","#6b8fac","#2532d6","#60483f","#0666c7","#e928b4","#ac624f","#dba259","#109b5e","#aab4ee","#906915","#211615","#3c0ad2"];
    var map = L.map('mapid').setView([{{ config('leaflet.map_center_latitude') }}, {{ config('leaflet.map_center_longitude') }}], {{ config('leaflet.zoom_level') }});
    var popup = L.popup();
    var baseUrl = "{{ url('/') }}";

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    $.get('{{ route('api.peta.index') }}')
    .then(function (response) {
        console.log(response.data);
        /*L.geoJSON(response.data, {
            pointToLayer: function(geoJsonPoint, latlng) {
                return L.marker(latlng);
            }
        })
        .bindPopup(function (layer) {
            return layer.feature.properties.map_popup_content;
        }).addTo(map);*/
        L.geoJson(response.data, {
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
            }).addTo(map);
    })
    .catch(function (error) {
        console.log(error);
    });
</script>
@endsection
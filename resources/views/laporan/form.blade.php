@if($jenis_laporan == 1)
<div class="form-group">
    <label for="jumlah_iduka">Jumlah dan nama IDUKA <span class="text-red">*</span></label>
    <textarea name="jumlah_iduka" id="jumlah_iduka" class="form-control"
        required>{{($laporan) ? $laporan->jumlah_iduka : ''}}</textarea>
</div>
<div class="form-group">
    <label for="lulusan">Berapakah jumlah lulusan dari sekolah yang bapak/ibu dampingi dapat diserap oleh IDUKA? <span
            class="text-red">*</span></label>
    <textarea name="lulusan" id="lulusan" class="form-control"
        required>{{($laporan) ? $laporan->lulusan : ''}}</textarea>
</div>
<div class="form-group">
    <label for="lulusan_all">Berapakah jumlah lulusan dari sekolah yang bapak/ibu dampingi sudah diserap oleh IDUKA?
        <span class="text-red">*</span><br> <small>Mengambil data dari tahun 2016 s.d. 2020</small></label>
    <textarea name="lulusan_all" id="lulusan_all" class="form-control"
        required>{{($laporan) ? $laporan->lulusan_all : ''}}</textarea>
</div>
<div class="form-group">
    <label for="perkembangan_smk">Perkembangan SMK <span class="text-red">*</span><br> <small>Potret sekolah sebelum,
            sesudah dan Action Plan Pelaksanaan SMK CoE</small></label>
    <textarea name="perkembangan_smk" id="perkembangan_smk" class="form-control"
        required>{{($laporan) ? $laporan->perkembangan_smk : ''}}</textarea>
</div>
<div class="form-group">
    <label for="kesimpulan_saran">Kesimpulan dan Saran <span class="text-red">*</span><br> <small>uraian singkat yang
            berisi tentang kondisi sebenarnya sekolah pada saat pelaksanaan pendampingan SMK CoE</small></label>
    <textarea name="kesimpulan_saran" id="kesimpulan_saran" class="form-control"
        required>{{($laporan) ? $laporan->kesimpulan_saran : ''}}</textarea>
</div>
<div class="form-group">
    <label for="tanggal_pelaksanaan">Tanggal Pelaksanaan Pendampingan <span class="text-red">*</span></label>
    <input class="datepicker form-control" name="tanggal_pelaksanaan"
        value="{{($laporan) ? date('m/d/Y', strtotime($laporan->tanggal_pelaksanaan)) : ''}}">
</div>
@endif
@if($jenis_laporan == 3)
<div class="form-group">
    <label for="pengisian">Adakah pengisian APM yang tidak sesuai setelah diverifikasi? <span
            class="text-red">*</span><br> <small>Jika ada (Jelaskan), Jika Tidak (tidak dijelaskan/isi dengan
            -)</small></label>
    <textarea name="pengisian" id="pengisian" class="form-control"
        required>{{($laporan) ? $laporan->pengisian : ''}}</textarea>
</div>
<div class="form-group">
    <label for="kendala">Adakah Kendala dalam Proses Verifikasi? dan cara mengatasainya <span
            class="text-red">*</span><br> <small>Jika ada (Jelaskan), Jika Tidak (tidak dijelaskan/isi dengan
            -)</small></label>
    <textarea name="kendala" id="kendala" class="form-control"
        required>{{($laporan) ? $laporan->kendala : ''}}</textarea>
</div>
<div class="form-group">
    <label for="kondisi">Bagaimana Kondisi dari Tim Pendamping pada saat melaksankanan Workshop Pertama? <span
            class="text-red">*</span><br> <small>Jika ada (Jelaskan), Jika Tidak (tidak dijelaskan/isi dengan
            -)</small></label>
    <textarea name="kondisi" id="kondisi" class="form-control"
        required>{{($laporan) ? $laporan->kondisi : ''}}</textarea>
</div>
<div class="form-group">
    <label for="tanggal_pelaksanaan">Tanggal Pelaksanaan Verifikasi <span class="text-red">*</span></label>
    <input class="datepicker form-control" name="tanggal_pelaksanaan"
        value="{{($laporan) ? date('m/d/Y', strtotime($laporan->tanggal_pelaksanaan)) : ''}}">
</div>
@endif
@if($jenis_laporan == 2)
<div class="form-group" id="dropzone">
    <label for="">
        File Dokumentasi Kegiatan <span class="text-red">*</span><br>
        <small>Berkas yang di unggah:
            <br>Foto Kegiatan Sosialisasi APM
            <br>Foto Kegiatan Pengisian APM
            <br>Foto Kegiatan Verifikasi Rapor Mutu
        </small>
    </label>
    <div id="actions" class="row">
        <div class="col-lg-6">
            <div class="btn-group w-100">
                <span class="btn btn-success col fileinput-button">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Berkas</span>
                </span>
                <button class="btn btn-primary col start">
                    <i class="fas fa-upload"></i>
                    <span>Mulai Unggah</span>
                </button>
                <button type="reset" class="btn btn-warning col cancel">
                    <i class="fas fa-times-circle"></i>
                    <span>Batalkan</span>
                </button>
            </div>
        </div>
        <div class="col-lg-6 d-flex align-items-center">
            <div class="fileupload-process w-100">
                <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0"
                    aria-valuemax="100" aria-valuenow="0">
                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                </div>
            </div>
        </div>
    </div>
    <div class="table table-striped files" id="previews">
        <div id="template" class="row mt-2">
            <div class="col-auto">
                <span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
            </div>
            <div class="col d-flex align-items-center">
                <p class="mb-0">
                    <span class="lead" data-dz-name></span>
                    (<span data-dz-size></span>)
                </p>
                <strong class="error text-danger" data-dz-errormessage></strong>
            </div>
            <div class="col-4 d-flex align-items-center">
                <div class="progress progress-striped active w-100" role="progressbar" aria-valuemin="0"
                    aria-valuemax="100" aria-valuenow="0">
                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                </div>
            </div>
            <div class="col-auto d-flex align-items-center">
                <div class="btn-group">
                    <button class="btn btn-primary start">
                        <i class="fas fa-upload"></i>
                        <span>Mulai</span>
                    </button>
                    <button data-dz-remove class="btn btn-warning cancel">
                        <i class="fas fa-times-circle"></i>
                        <span>Batalkan</span>
                    </button>
                    <button data-dz-remove class="btn btn-danger delete">
                        <i class="fas fa-trash"></i>
                        <span>Hapus</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="tempat_upload"></div>
</div>
@endif
@if($jenis_laporan == 5)
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th>Program</th>
            <th>Kegiatan</th>
            <th>Indikator Keberhasilan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($laporan as $item)
        <tr>
            <td class="text-center" rowspan="2">{{$loop->iteration}}</td>
            <td>{{$item->nama}}</td>
            <td>{!! $item->kegiatan !!}</td>
            <td>{{$item->indikator}}</td>
        </tr>
        <tr>
            <td colspan="3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Dokumen yang ditelaah</th>
                            <th class="text-center">Ada</th>
                            <th class="text-center">Tidak ada</th>
                            <th class="text-center">Kendala</th>
                            <th class="text-center">Solusi</th>
                            <th class="text-center">Tindak Lanjut</th>
                        </tr>
                    <tbody>
                        @foreach ($item->dokumen_program as $dokumen_program)
                        <tr>
                            <td>{{$dokumen_program->nama}}</td>
                            @if($dokumen_program->nilai_afirmasi)
                            <td class="text-center">
                                <input type="radio" class="{{$dokumen_program->id}}" name="ada[{{$dokumen_program->id}}]" id="{{$dokumen_program->id}}" value="1" {{($dokumen_program->nilai_afirmasi->ada)  ? 'checked' : ''}} required>
                            </td>
                            <td class="text-center">
                                <input type="radio" class="{{$dokumen_program->id}}" name="ada[{{$dokumen_program->id}}]" id="{{$dokumen_program->id}}" value="0" {{($dokumen_program->nilai_afirmasi->ada)  ? '' : 'checked'}} required>
                            </td>
                            @else
                            <td class="text-center">
                                <input type="radio" class="{{$dokumen_program->id}}" name="ada[{{$dokumen_program->id}}]" id="{{$dokumen_program->id}}" value="1" required>
                            </td>
                            <td class="text-center">
                                <input type="radio" class="{{$dokumen_program->id}}" name="ada[{{$dokumen_program->id}}]" id="{{$dokumen_program->id}}" value="0" required>
                            </td>
                            @endif
                            <td><input type="text" class="form-control form-control-sm" name="kendala[{{$dokumen_program->id}}]" value="{{($dokumen_program->nilai_afirmasi)  ? $dokumen_program->nilai_afirmasi->kendala : ''}}"></td>
                            <td><input type="text" class="form-control form-control-sm" name="solusi[{{$dokumen_program->id}}]" value="{{($dokumen_program->nilai_afirmasi)  ? $dokumen_program->nilai_afirmasi->solusi : ''}}"></td>
                            <td><input type="text" class="form-control form-control-sm" name="tindak_lanjut[{{$dokumen_program->id}}]" value="{{($dokumen_program->nilai_afirmasi)  ? $dokumen_program->nilai_afirmasi->tindak_lanjut : ''}}"></td>
                        </tr>
                        @endforeach
                    </tbody>
                    </thead>
                </table>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
<script>
    $('.datepicker').datepicker();
    /*
    Dropzone.autoDiscover = false;
    var previewNode = document.querySelector("#template");
    previewNode.id = "";
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);
    var myDropzone;
    //if(!myDropzone){
        myDropzone = new Dropzone('#dropzone', { // Make the whole body a dropzone
            url: "{{route('api.laporan.upload')}}", // Set the url
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button", // Define the element that should be used as click trigger to select files.
            success: (file, response) => {
                var fileUpload = JSON.parse(file.xhr.response);
                console.log(JSON.parse(file.xhr.response));
                $('#tempat_upload').append('<input type="text" name="berkas[]" value="'+fileUpload.success+'">')
            }
        });

        myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function(e) { 
                e.preventDefault();
                console.log('start 1');
                myDropzone.enqueueFile(file);
                console.log(file);
                return false;
            };
        });

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function(progress) {
            document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
        });

        myDropzone.on("sending", function(file) {
            // Show the total progress bar when upload starts
            document.querySelector("#total-progress").style.opacity = "1";
            // And disable the start button
            file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
        });

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function(progress) {
            document.querySelector("#total-progress").style.opacity = "0";
        });

        // Setup the buttons for all transfers
        // The "add files" button doesn't need to be setup because the config
        // `clickable` has already been specified.
        document.querySelector("#actions .start").onclick = function(e) {
            e.preventDefault();
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
            console.log('start 2');
            console.log(Dropzone);
            return false;
        };
        document.querySelector("#actions .cancel").onclick = function() {
            myDropzone.removeAllFiles(true);
        };
    //}
    */
</script>
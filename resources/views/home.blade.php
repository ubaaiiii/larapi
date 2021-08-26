@extends('master.template')
@section('master.intro-header')

    <div class="main-wrapper">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Zero Configuration</h5>
                        <button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal"
                            data-bs-target="#tambahModal">Tambah Data</button>
                        <table id="zero-conf" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama Depan</th>
                                    <th>Nama Belakang</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Agama</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_siswa as $siswa)
                                    <tr>
                                        <td>{{ $siswa->nama_depan }}</td>
                                        <td>{{ $siswa->nama_belakang }}</td>
                                        <td>{{ $siswa->jenis_kelamin }}</td>
                                        <td>{{ $siswa->agama }}</td>
                                        <td>{{ $siswa->alamat }}</td>
                                        <td>$320,800</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <form id="formSiswa" action="siswa/create" method="POST">
                            @csrf
                            <div class="mb-3 col">
                                <label for="nama_depan" class="form-label">Nama Depan</label>
                                <input type="text" name="nama_depan" class="form-control" id="nama_depan"
                                    aria-describedby="emailHelp">
                            </div>
                            <div class="mb-3 col">
                                <label for="exampleInputPassword1" class="form-label">Nama Belakang</label>
                                <input type="text" name="nama_belakang" class="form-control" id="nama_belakang">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" name="jenis_kelamin" id="jenis_kelamin"
                                    aria-label="Default select example">
                                    <option value="L">Laki-Laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3 col">
                                <label for="exampleInputPassword1" class="form-label">Agama</label>
                                <input type="text" name="agama" class="form-control" id="agama">
                            </div>
                            <div class="mb-3 col">
                                <label for="exampleInputPassword1" class="form-label">Alamat</label>
                                <input type="text" name="alamat" class="form-control" id="alamat">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btn-simpan" class="btn btn-success">Simpan</button>
                    <input type="hidden" id="siswa_id" name="siswa_id" value="0">
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script>
        $(document).ready(function($) {

            //----- Open modal CREATE -----//
            $('#btn-tambah').click(function() {
                $('#btn-simpan').val("add");
                $('#formSiswa').trigger("reset");
                $('#tambahModal').modal('show');
            });

            // CREATE
            $("#btn-simpan").click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();
                var formData = {
                    nama_depan: $('#nama_depan').val(),
                    nama_belakang: $('#nama_belakang').val(),
                    jenis_kelamin: $('#jenis_kelamin').val(),
                    agama: $('#agama').val(),
                    alamat: $('#alamat').val(),
                };
                var state = $('#btn-simpan').val();
                var type = "POST";
                var ajaxurl = 'siswa/create';
                $.ajax({
                    type: type,
                    url: ajaxurl,
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        var siswa = '<td>' + data.nama_depan + '</td><td>' + data
                            .nama_belakang + '</td><td>' + data.jenis_kelamin + '</td><td>' +
                            data.agama + '</td><td>' + data.alamat + '</td>';
                        if (state == "add") {
                            $('#zero-conf').append(siswa);
                        } else {
                            $('#siswa' + siswa_id).replaceWith(siswa);
                        }
                        $('#formSiswa').trigger('reset');
                        $('#tambahModal').modal('hide');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>
@endsection

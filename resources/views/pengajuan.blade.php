@extends('layouts.master')
@section('title', 'Pengajuan')
@section('breadcrumb', 'Pengajuan')
@section('menu', 'Pengajuan')
@section('content')
    <div class="intro-y flex items-center mt-4">
        <h2 class="text-lg font-medium mr-auto">
            Formulir Pengajuan @if (!empty($data->tertanggung)){!! 'a/n <b>' . $data->tertanggung . '</b><br>' !!}@endif
            @if (empty($method) && empty($data))
                <button class="btn btn-sm btn-primary" id="btn-add">Simpan</button>
            @endif
            @if ($method == 'update')
                <button class="btn btn-sm btn-primary" id="btn-update">Simpan</button>
                <button class="btn btn-sm btn-primary" id="btn-perpanjang">Perpanjang</button>
            @endif
            @if ($method == 'approve')
                @role('ao')
                <button class="btn btn-sm btn-success btn-approve">Ajukan</button>
                @endrole
                @role('broker|insurance')
                <button class="btn btn-sm btn-success btn-approve">Setujui</button>
                @endrole
                @role('checker')
                <button class="btn btn-sm btn-success btn-approve">Aktifkan</button>
                @endrole
            @endif
            @if ($method == 'view')
                <button class="btn btn-sm btn-warning" id="btn-rollback">Kembalikan</button>
                <button class="btn btn-sm btn-danger" id="btn-hapus">Hapus</button>
            @endif
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 lg:col-span-6">
            <!-- BEGIN: Input -->
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Data Nasabah
                    </h2>
                </div>
                <div id="horizontal-form" class="p-5">
                    <form id="frm-data-nasabah">
                        <div class="preview">
                            @if ($act == 'view' || $act == 'edit')
                                <div class="form-inline mt-5">
                                    <label for="transid" class="form-label sm:w-20">Nomor Transaksi</label>
                                    <input type="text" id="transid" class="form-control" required name="transid"
                                        value="@if (!empty($data->transid)){{ $data->transid }}@endif" readonly>
                                </div>
                            @else
                                <div class="form-inline mt-5">
                                    <label for="transid" class="form-label sm:w-20">Nomor Transaksi</label>
                                    <input type="text" id="transid" class="form-control" required name="transid"
                                        value="{{ $transid->seqlead . date($transid->seqformat) . str_pad($transid->seqno, $transid->seqlen, '0', STR_PAD_LEFT) }}"
                                        readonly>
                                </div>
                            @endif
                            <div class="form-inline mt-5">
                                <label for="type_insurance" class="ml-3 form-label sm:w-20">Tipe Asuransi</label>
                                <select id="type_insurance" name="type_insurance" required style="width:100%">
                                    @foreach ($instype as $val)
                                        <option value="{{ $val->msid }}" @if (!empty($data->id_instype) && $val->msid == $data->id_instype) selected @endif>{{ $val->msdesc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if (!empty($data) && $data->id_status >= 2)
                            <div class="form-inline mt-5">
                                <label for="asuransi" class="ml-3 form-label sm:w-20">Asuransi</label>
                                <select id="asuransi" name="asuransi" required style="width:100%">
                                    @foreach ($asuransi as $val)
                                        <option value="{{ $val->id }}" @if (!empty($data->id_asuransi) && $val->id === $data->id_asuransi) selected="true" @endif>
                                            {{ $val->nama_asuransi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="form-inline mt-5">
                                <label for="cabang" class="ml-3 form-label sm:w-20">Cabang</label>
                                <select id="cabang" name="cabang" required style="width:100%">
                                    @foreach ($cabang as $val)
                                        <option value="{{ $val->id }}" @if (empty($data->id_cabang)) @if ($val->id === Auth::user()->id_cabang) selected="true" @endif @else @if ($val->id === $data->id_cabang) selected="true" @endif @endif>
                                            {{ $val->nama_cabang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="alamat_cabang" class="form-label sm:w-20">Alamat Cabang</label>
                                <textarea id="alamat_cabang" name="alamat_cabang" class="form-control" required>@if (!empty($data->alamat_cabang)){{ $data->alamat_cabang }}@endif</textarea>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="insured" class="ml form-label sm:w-20">Tertanggung (QQ)</label>
                                <select id="insured" style="width:100%;text-transform: uppercase;" class="select2"
                                    name="insured" required>
                                </select>
                            </div>
                            @if (!empty($data->id_insured))
                                <script>
                                    var newOption = new Option('{{ $data->tertanggung }}', {{ $data->id_insured }}, false, false);
                                    $('#insured').append(newOption).trigger('change');
                                </script>
                            @endif
                            <div class="form-inline mt-5">
                                <label for="nik_insured" class="form-label sm:w-20">NIK Tertanggung</label>
                                <input type="text" id="nik_insured" class="form-control" required name="nik_insured"
                                    @if (!empty($data->nik_insured)) value="{{ $data->nik_insured }}" @endif>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="npwp_insured" class="form-label sm:w-20">NPWP Tertanggung</label>
                                <input type="text" id="npwp_insured" class="form-control masked" required
                                    @if (!empty($data->npwp_insured)) value="{{ $data->npwp_insured }}" @endif>
                                <input type="hidden" name="npwp_insured" @if (!empty($data->npwp_insured)) value="{{ $data->npwp_insured }}" @endif>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="alamat_insured" class="form-label sm:w-20">Alamat Tertanggung</label>
                                <textarea id="alamat_insured" name="alamat_insured" class="form-control" required @if (!empty($data->alamat_insured)) @endif>@if (!empty($data->alamat_insured)){{ $data->alamat_insured }}@endif</textarea>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="nopinjaman" class="form-label sm:w-20">Nomor Pinjaman</label>
                                <input type="text" class="form-control allow-decimal" placeholder="Nomor Pinjaman" name="nopinjaman"
                                    id="nopinjaman" value="@if (!empty($data->nopinjaman)){{ $data->nopinjaman }}@endif">
                            </div>
                            <div class="form-inline mt-5">
                                <label for="plafond_kredit" class="form-label sm:w-20">Plafond Kredit</label>
                                <input type="text" class="form-control allow-decimal currency masked" placeholder="Plafond Kredit"
                                    id="plafond_kredit" value="@if (!empty($data->plafond_kredit)){{ $data->plafond_kredit }}@endif">
                                <input type="hidden" name="plafond_kredit" @if (!empty($data->plafond_kredit)) value="{{ $data->plafond_kredit }}" @endif>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="policy_no" class="form-label sm:w-20">Nomor Polis</label>
                                <input type="text" class="form-control allow-decimal" placeholder="Nomor Polis" name="policy_no"
                                    id="policy_no" value="@if (!empty($data->policy_no)){{ $data->policy_no }}@endif">
                            </div>
                            <div class="form-inline mt-5">
                                <label for="nopolis_lama" class="ml-3 form-label sm:w-20">Nopolis Lama</label>
                                <div class="input-group w-full">
                                    <input type="text" class="form-control" placeholder="Nomor Polis Lama" name="nopolis_lama"
                                        id="nopolis_lama" value="@if (!empty($data->policy_parent)){{ $data->policy_parent }}@endif">
                                    <div id="nopolis_lama" class="input-group-text">Jika Renewal.</div>
                                </div>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="masa" class="ml-3 form-label sm:w-20">Masa Asuransi</label>
                                <div class="input-group w-full">
                                    <input type="text" class="form-control" name="masa"
                                        id="masa" value="@if (!empty($data->masa)){{ $data->masa }}@endif" required>
                                    <div id="masa" class="input-group-text">Bulan</div>
                                </div>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="masa" class="ml-3 form-label sm:w-20">Periode KJPP</label>
                                <div class="input-group w-full">
                                    <input id="periode_start" name="periode_start" class="date-range form-control w-full block mx-auto text-center" required
                                    value="@if (!empty($data->periode_start)){{ date_format(date_create($data->periode_start), 'd/m/Y') }}@endif">
                                    <div id="masa" class="input-group-text"> s/d </div>
                                    <input readonly id="periode_end" name="periode_end" class="date-range form-control w-full block mx-auto text-center" required
                                    value="@if (!empty($data->periode_end)){{ date_format(date_create($data->periode_end), 'd/m/Y') }}@endif">
                                </div>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="okupasi" class="ml-3 form-label sm:w-20">Okupasi</label>
                                <select id="okupasi" style="width:100%" name="okupasi" required>
                                    {{-- @foreach ($okupasi as $val)
                                        <option value="{{ $val->id }}" @if (!empty($data->id_okupasi) && $val->id === $data->id_okupasi) selected="true" @endif>
                                            {{ $val->kode_okupasi . ' - (' . $val->rate . ' ‰) ' . $val->nama_okupasi }}
                                        </option>
                                    @endforeach --}}
                                </select>
                            </div>
                            <div class="form-inline mt-1 extended-clause" style="display:none">
                                <label class="form-label sm:w-20"></label>
                                <label>Sudah Termasuk: RSMDCC, TSFWD, Others</label>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="lokasi_okupasi" class="form-label sm:w-20">Lokasi Okupasi</label>
                                <textarea id="lokasi_okupasi" name="lokasi_okupasi" class="form-control" required>@if (!empty($data->location)){{ $data->location }}@endif</textarea>
                            </div>
                            @if (!empty($data->okupasi))
                                <script>
                                    var newOption = new Option("{{ $data->kecamatan . ' / ' . $data->kelurahan . ' / ' . $data->kodepos }}",
                                        {{ $data->id_kodepos }}, false, false);
                                    $('#kodepos').append(newOption).trigger('change');
                                </script>
                            @endif
                            <div class="form-inline mt-5">
                                <label for="kodepos" class="ml-3 form-label sm:w-20">Kode Pos</label>
                                <select id="kodepos" style="width:100%" name="kodepos" required>
                                </select>
                            </div>
                            @if (!empty($data->id_kodepos))
                                <script>
                                    var newOption = new Option("{{ $data->kecamatan . ' / ' . $data->kelurahan . ' / ' . $data->kodepos }}",
                                        {{ $data->id_kodepos }}, false, false);
                                    $('#kodepos').append(newOption).trigger('change');
                                </script>
                            @endif
                            <div class="sm:ml-20 sm:pl-5 mt-5">

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END: Input -->
        </div>
        <div class="intro-y col-span-12 lg:col-span-6">
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">
                        Nilai Pertanggungan
                    </h2>
                </div>
                <form id="frm-pertanggungan">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kode / Nilai Pertanggungan</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($price as $row)
                                <tr>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-text">{{ $row->kodetrans_kode }}</div>
                                            <input style="text-align:right;" type="text"
                                                class="form-control allow-decimal tsi currency masked"
                                                placeholder="{{ $row->kodetrans_nama }}"
                                                aria-label="{{ $row->kodetrans_nama }}"
                                                d-input="{{ $row->kodetrans_input }}"
                                                id="kodetrans-value[{{ $row->kodetrans_id }}]"
                                                value="@if (!empty($pricing[$row->kodetrans_id]->value)){{ $pricing[$row->kodetrans_id]->value }}@endif">
                                            <input type="hidden" name="kodetrans-value[{{ $row->kodetrans_id }}]">
                                        </div>
                                    </td>
                                    <td><input name="kodetrans-remarks[{{ $row->kodetrans_id }}]" class="form-control"
                                            value=" @if (!empty($pricing[$row->kodetrans_id]->deskripsi)){{ $pricing[$row->kodetrans_id]->deskripsi }}@endif"></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2">
                                    <div class="input-group">
                                        <div id="group-t" class="input-group-text">Total</div>
                                        <input style="text-align:right;" id="kodetrans-value[1]" type="text" d-input="TSI"
                                            class="currency form-control allow-decimal masked total-si" placeholder="Total Nilai Pertanggungan"
                                            aria-label="Total Nilai Pertanggungan" aria-describedby="group-t" readonly
                                            value="@if (!empty($pricing[1]->value)){{ $pricing[1]->value }}@endif">
                                        <input type="hidden" name="kodetrans-value[1]">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="input-group">
                                        <div id="group-t" class="input-group-text">Premium</div>
                                        <input style="text-align:right;" id="kodetrans-value[2]" type="text" d-input="PREMI"
                                            class="currency form-control allow-decimal masked total-si" placeholder="Premium"
                                            aria-label="Total Nilai Pertanggungan" aria-describedby="group-t" readonly
                                            value="@if (!empty($pricing[2]->value)){{ $pricing[2]->value }}@endif">
                                        <input type="hidden" name="kodetrans-value[2]">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="intro-y box mt-5">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Catatan
                    </h2>
                </div>
                <div id="horizontal-form" class="p-5">
                    <form id="frm-data-nasabah">
                        <div class="preview">
                            <div class="form-inline">
                                <textarea id="catatan" name="catatan" class="form-control" required @if (!empty($data->catatan)) @endif>@if (!empty($data->catatan)){{ $data->catatan }}@endif</textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if ($act !== 'add')
            <div class="intro-y col-span-12 lg:col-span-6">
                <div class="intro-y box">
                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            Dokumen
                        </h2>
                    </div>
                    <div id="multiple-file-upload" class="p-5">
                        <div class="preview">
                            <form action="{{ url('api/dokumen') }}" class="dropzone" method="post">
                                @csrf
                                <input name="transid" type="hidden" value="{{ $data->transid }}"/>
                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
                                <div class="dz-message" data-dz-message>
                                    <div class="text-lg font-medium">Tarik dokumen kesini atau klik untuk memilih dokumen.
                                    </div>
                                    <div class="text-gray-600"> Harap melakukan <strong>kompresi dokumen</strong> terlebih dahulu sebelum mengunggahnya</div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="p-5" id="responsive-table">
                        <div class="preview">
                            <div class="overflow-x-auto">
                                <table class="table" id="tb-dokumen">
                                    <thead>
                                        <tr>
                                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">#</th>
                                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Dokumen</th>
                                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Tgl. Upload</th>
                                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Diupload Oleh</th>
                                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Ukuran</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="intro-y col-span-12 lg:col-span-6">
                <div class="intro-y box">
                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            Catatan Aktifitas
                        </h2>
                    </div>
                    <div class="p-5" id="responsive-table">
                        <div class="preview">
                            <div class="overflow-x-auto">
                                <table class="table" id="tb-aktifitas">
                                    <thead>
                                        <tr>
                                            <th class="border-b-2 dark:border-dark-5">Tanggal</th>
                                            <th class="border-b-2 dark:border-dark-5">Status</th>
                                            <th class="border-b-2 dark:border-dark-5">User</th>
                                            <th class="border-b-2 dark:border-dark-5">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
@endsection

@section('script')
    <script>
        var RATE = "";
        function reloadTable() {
            $('#tb-dokumen').DataTable().ajax.reload();
        }

        function hitung() {
            var OKUPASI = $('#okupasi').val(),
                TSI = $('[d-input="TSI"]').val();

            var @php foreach ($hitung as $row)
                
            @endphp

            console.log(tsi);
        }
        $(document).ready(function() {
            $('select').select2();
            $('#npwp_insured').inputmask("99.999.999.9-999.999");
            $('#nik_insured').inputmask("9999999999999999");
            $('.date-range').inputmask("99/99/9999");
            $('#masa').inputmask("decimal");

            $('.dt-table').DataTable();
            @if ($act !== 'add')
                var tableDokumen = $('#tb-dokumen').DataTable({
                    "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
                    "serverSide":true,
                    "ajax": {
                        url: "{{ url('api/datadokumen') }}",
                        headers: {
                            'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                        },
                        type: "POST",
                        data: function(d) {
                            d.search = $("#tb-dokumen_filter label input").val();
                            d.transid = '{{ $data->transid }}';
                            d._token = '{{ csrf_token() }}';
                            // d.length = $("[name='tb-dokumen_length']").val();
                        },
                        // success: function(d) {
                        //     console.log('sql:', d);
                        // },
                        error: function(d) {
                            console.log('error:', d.responseText);
                        },
                    },
                    "aoColumns": [{
                            "bSortable": false,
                            "className": "border-b",
                        },
                        {
                            "bSortable": true,
                            "className": "border-b",
                        },
                        {
                            "bSortable": true,
                            "className": "border-b",
                        },
                        {
                            "bSortable": true,
                            "className": "border-b",
                        },
                        {
                            "bSortable": true,
                            "className": "border-b text-right",
                        },
                    ],
                    "initComplete": function(settings, json) {
                        $("#tb-dokumen_filter label input").unbind();
                        $("#tb-dokumen_filter label input").bind('keyup', function(e) {
                            if(e.keyCode == 13) {
                                tableDokumen.search( this.value ).draw();
                            }
                        }); 
                    }
                }).on('draw',function(){
                    paginatioon(tableDokumen,$('#tb-dokumen_paginate > ul.pagination'));
                    $('.gotoPage').click(function() {
                        gotoPage($(this),tableDokumen);
                    });
                    $("[name='tb-dokumen_length']").change(function(){
                        tableDokumen.ajax.reload();
                    });
                    
                    feather.replace();
                });

                var tableAktifitas = $('#tb-aktifitas').DataTable({
                    "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
                    "serverSide":true,
                    "ajax": {
                        url: "{{ url('api/dataaktifitas') }}",
                        headers: {
                            'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                        },
                        type: "POST",
                        data: function(d) {
                            d.search = $("#tb-aktifitas_filter label input").val();
                            d.transid = '{{ $data->transid }}';
                            d._token = '{{ csrf_token() }}';
                            // d.length = $("[name='tb-aktifitas_length']").val();
                        },
                        // success: function(d) {
                        //     console.log('sql:', d);
                        // },
                        error: function(d) {
                            console.log('error:', d.responseText);
                        },
                    },
                    "initComplete": function(settings, json) {
                        $("#tb-aktifitas_filter label input").unbind();
                        $("#tb-aktifitas_filter label input").bind('keyup', function(e) {
                            if(e.keyCode == 13) {
                                tableAktifitas.search( this.value ).draw();
                            }
                        });
                    }
                }).on('draw',function(){
                    paginatioon(tableAktifitas,$('#tb-aktifitas_paginate > ul.pagination'));
                    $('.gotoPage').click(function() {
                        gotoPage($(this),tableAktifitas);
                    });
                    $("[name='tb-aktifitas_length']").change(function(){
                        tableAktifitas.ajax.reload();
                    });
                    
                    feather.replace();
                });
                
            @endif
            $("#okupasi").select2({
                language: "id",
                allowClear: true,
                placeholder: "Pilih Okupasi",
                ajax: {
                    dataType: "json",
                    url: "{{ url('api/selectokupasi') }}",
                    headers: {
                        'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                    },
                    data: function(params) {
                        return {
                            search: params.term,
                            instype: $("#type_insurance").val(),
                        };
                    },
                    processResults: function(data, page) {
                        return {
                            results: data,
                        };
                    },
                },
            });
            function cekType() {
                if ($("#type_insurance").val() === "PAR") {
                    $('.extended-clause').removeAttr('style');
                } else {
                    $('.extended-clause').css('display', 'none');
                }

                $("#okupasi").val("").trigger('change');
                $("#okupasi option").remove();
                $("#okupasi").select2("destroy");
                $("#okupasi").select2({
                    language: "id",
                    allowClear: true,
                    placeholder: "Pilih Okupasi",
                    ajax: {
                        dataType: "json",
                        url: "{{ url('api/selectokupasi') }}",
                        headers: {
                            'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                        },
                        data: function(params) {
                            return {
                                search: params.term,
                                instype: $("#type_insurance").val(),
                            };
                        },
                        processResults: function(data, page) {
                            return {
                                results: data,
                            };
                        },
                    },
                });
            }
            cekType();
            $('#type_insurance').change(function() {
                cekType();
            });

            $("#kodepos").select2({
                language: "id",
                minimumInputLength: 3,
                placeholder: "Masukkan Nama Kecamatan / Kelurahan / Kode Pos",
                ajax: {
                    dataType: "json",
                    url: "{{ url('api/selectkodepos') }}",
                    headers: {
                        'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                    },
                    data: function(params) {
                        return {
                            search: params.term,
                        };
                    },
                    processResults: function(data, page) {
                        return {
                            results: data,
                        };
                    },
                },
            });

            $("#insured").select2({
                language: "id",
                minimumInputLength: 3,
                allowClear: true,
                tags: true,
                placeholder: "Masukkan Nama Tertanggung",
                ajax: {
                    dataType: "json",
                    url: "{{ url('api/selectinsured') }}",
                    headers: {
                        'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                    },
                    data: function(params) {
                        return {
                            search: params.term,
                        };
                    },
                    processResults: function(data, page) {
                        // console.log('data', data);
                        return {
                            results: data,
                        };
                    },
                },
            });

            $('#okupasi').on('select2:select', function(e) {
                var data = e.params.data;
                RATE = data.rate;
            });

            $('#insured').on('select2:select', function(e) {
                var data = e.params.data;
                $('#npwp_insured').val("").trigger('change');
                $('#nik_insured').val("").trigger('change');
                $('#alamat').val("");
                if (data.npwp_insured !== undefined) {
                    $('#npwp_insured').val(data.npwp_insured).trigger('change');
                }
                if (data.nik_insured !== undefined) {
                    $('#nik_insured').val(data.nik_insured).trigger('change');
                }
                if (data.alamat_insured !== undefined) {
                    $('#alamat_insured').val(data.alamat_insured);
                }
            });

            $('.date-range').daterangepicker({
                singleDatePicker:true,
                autoApply: true,
                locale: {
                    format: 'DD/MM/YYYY'
                },
            }, function(start, end, label) {
                $('#periode_end').data('daterangepicker').setStartDate(start.add($('#masa').val(), 'month'));
            });

            $('#masa').keyup(function(){
                var tgl   = $('#periode_start').val(),
                    pecah = tgl.split("/"),
                    tgl_moment = pecah[2]+pecah[1]+pecah[0];
                // console.log(tgl_moment);
                // console.log(moment(tgl_moment).add($('#masa').val(), 'month'));
                $('#periode_end').data('daterangepicker').setStartDate(moment(tgl_moment).add($('#masa').val(), 'month'));
            })

            @if (empty($method) && !empty($data))
                $("#frm-data-nasabah :input").prop('disabled', true);
                $("#frm-pertanggungan :input").prop('disabled', true);
            @endif

            $('#btn-add').click(function(){
                var btnHtml = $(this).html(),
                    loading = "<i class='fas fa-spinner fa-pulse' class='mr-2'></i>&nbsp;&nbsp;Loading...",
                    nama_insured = $('#insured option:selected').text(),
                    nama_cabang = $('#cabang option:selected').text();
                $(this).attr('disabled',true).html(loading);

                $.ajax({
                    url: "{{ url('api/pengajuan') }}",
                    method: "POST",
                    data: $('#frm-data-nasabah, #frm-pertanggungan').serialize() + "&method=create&_token={{ csrf_token() }}&nama_insured="+nama_insured+"&nama_cabang="+nama_cabang,
                    headers: {
                        'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                    },
                    success: function(d) {
                        console.log('success: ',d);
                    },
                    error: function(d) {
                        console.log('error: ', d);
                    }
                });
            });

            $('.btn-approve').click(function(){
                var btnHtml = $(this).html(),
                    loading = "<i class='fas fa-spinner fa-pulse' class='mr-2'></i>&nbsp;&nbsp;Loading...";
                $(this).attr('disabled',true).html(loading);

                $.ajax({
                    url: "{{ url('api/pengajuan') }}",
                    method: "POST",
                    data: $('#frm-data-nasabah, #frm-pertanggungan').serialize() + "&method=approve&_token={{ csrf_token() }}&nama_insured="+nama_insured+"&nama_cabang="+nama_cabang,
                    headers: {
                        'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                    },
                    success: function(d) {
                        console.log('success: ',d);
                    },
                    error: function(d) {
                        console.log('error: ', d);
                    }
                });
            });

            $('#kodetrans-value[2]');

        });
    </script>
@endsection

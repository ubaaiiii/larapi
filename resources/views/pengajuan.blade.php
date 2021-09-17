@extends('layouts.master')
@section('title', 'Pengajuan')
@section('breadcrumb', 'Pengajuan')
@section('menu', 'Pengajuan')
@section('content')
    <div class="intro-y flex items-center mt-4">
        <h2 class="text-lg font-medium mr-auto">
            Formulir Pengajuan @if (!empty($data->tertanggung)){!! 'a/n <b>' . $data->tertanggung . '</b><br>' !!}@endif
            @if ($method == 'update')
                <button class="btn btn-sm btn-primary">Simpan</button>
                <button class="btn btn-sm btn-primary">Perpanjang</button>
            @endif
            @if ($method == 'view')
                <button class="btn btn-sm btn-success">Setujui</button>
                <button class="btn btn-sm btn-success">Ajukan</button>
                <button class="btn btn-sm btn-success">Aktifkan</button>
                <button class="btn btn-sm btn-warning">Kembalikan</button>
                <button class="btn btn-sm btn-danger">Hapus</button>
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
                            <label for="type-insurance" class="ml-3 form-label sm:w-20">Tipe Asuransi</label>
                            <select id="type-insurance" name="type-insurance" required style="width:100%">
                                @foreach ($instype as $val)
                                    <option value="{{ $val->msid }}" @if (!empty($data->id_instype) && $val->msid == $data->id_instype) selected @endif>{{ $val->msdesc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-inline mt-1 extended-clause" style="display:none">
                            <label class="form-label sm:w-20"></label>
                            <label>Sudah Termasuk: RSMDCC, TSFWD, Others</label>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="cabang" class="ml-3 form-label sm:w-20">Cabang</label>
                            <select id="cabang" name="cabang" required style="width:100%">
                                @foreach ($cabang as $val)
                                    <option value="{{ $val->id }}" @if (empty($data->id_cabang)) @if ($val->id === Auth::user()->cabang) selected="true" @endif @else @if ($val->id === $data->id_cabang) selected="true" @endif @endif>
                                        {{ $val->nama_cabang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="cabang-address" class="form-label sm:w-20">Alamat Cabang</label>
                            <textarea id="cabang-address" class="form-control">@if (!empty($data->alamat_cabang)){{ $data->alamat_cabang }}@endif</textarea>
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
                            <label for="nik" class="form-label sm:w-20">NIK Tertanggung</label>
                            <input type="text" id="nik" class="form-control" required name="nik"
                                @if (!empty($data->nik)) value="{{ $data->nik }}" disabled @endif>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="npwp" class="form-label sm:w-20">NPWP Tertanggung</label>
                            <input type="text" id="npwp" class="form-control" required name="npwp"
                                @if (!empty($data->npwp)) value="{{ $data->npwp }}" disabled @endif>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="alamat" class="form-label sm:w-20">Alamat Tertanggung</label>
                            <textarea id="alamat" class="form-control" required @if (!empty($data->alamat)) disabled @endif>@if (!empty($data->alamat)){{ $data->alamat }}@endif</textarea>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="nopinjaman" class="form-label sm:w-20">Nomor Pinjaman</label>
                            <input type="text" class="form-control allow-decimal" placeholder="Nomor Pinjaman" name="nopinjaman"
                                id="nopinjaman" value="@if (!empty($data->nopinjaman)){{ $data->nopinjaman }}@endif">
                        </div>
                        <div class="form-inline mt-5">
                            <label for="plafond-kredit" class="form-label sm:w-20">Plafond Kredit</label>
                            <input type="text" class="form-control allow-decimal currency" placeholder="Plafond Kredit" name="plafond-kredit"
                                id="plafond-kredit" value="@if (!empty($data->plafond_kredit)){{ $data->plafond_kredit }}@endif">
                        </div>
                        <div class="form-inline mt-5">
                            <label for="nopolis-lama" class="ml-3 form-label sm:w-20">Nopolis Lama</label>
                            <div class="input-group w-full">
                                <input type="text" class="form-control" placeholder="Nomor Polis Lama" name="nopolis-lama"
                                    id="nopolis-lama" value="@if (!empty($data->policy_parent)){{ $data->policy_parent }}@endif">
                                <div id="nopolis-lama" class="input-group-text">Jika Renewal.</div>
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
                            <label for="range-periode" class="form-label sm:w-20">Periode KJPP</label>
                            <input id="range-periode" class="date-range form-control w-full block mx-auto" required
                                value="@if (!empty($data->periode_start)) {{ date_format(date_create($data->periode_start), 'd/m/Y') . ' - ' . date_format(date_create($data->periode_end), 'd/m/Y') }} @endif">
                        </div>
                        <div class="form-inline mt-5">
                            <label for="okupasi" class="ml-3 form-label sm:w-20">Okupasi</label>
                            <select id="okupasi" style="width:100%" name="okupasi" required>
                                @foreach ($okupasi as $val)
                                    <option value="{{ $val->id }}" @if (!empty($data->id_okupasi) && $val->id === $data->id_okupasi) selected="true" @endif>
                                        {{ $val->kode_okupasi . ' - ' . $val->nama_okupasi . ' (' . $val->rate . ' ‰)' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="lokasi" class="form-label sm:w-20">Lokasi Okupasi</label>
                            <textarea id="lokasi" class="form-control" required>@if (!empty($data->location)){{ $data->location }}@endif</textarea>
                        </div>
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
                                        <div class="input-group-text">{{ $row->kodetrans_formula }}</div>
                                        <input style="text-align:right;" type="text"
                                            class="form-control allow-decimal tsi currency"
                                            placeholder="{{ $row->kodetrans_nama }}"
                                            aria-label="{{ $row->kodetrans_nama }}"
                                            name="kodetrans-value[{{ $row->kodetrans_id }}]"
                                            value="@if (!empty($pricing[$row->kodetrans_id]->value)){{ $pricing[$row->kodetrans_id]->value }}@endif">
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
                                    <input style="text-align:right;" id="tsi" name="kodetrans-value[1]" type="text"
                                        class="currency form-control allow-decimal" placeholder="Total Nilai Pertanggungan"
                                        aria-label="Total Nilai Pertanggungan" aria-describedby="group-t" readonly
                                        value="@if (!empty($pricing[1]->value)){{ $pricing[1]->value }}@endif">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
                            <form action="/file-upload" class="dropzone">
                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
                                <div class="dz-message" data-dz-message>
                                    <div class="text-lg font-medium">Tarik dokumen kesini atau klik untuk memilih dokumen.
                                    </div>
                                    <div class="text-gray-600"> Dokumen yang terpilih akan terupload ke sistem</div>
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
                                    <tbody>
                                        {{-- @foreach ($document as $doc)
                                        <tr>
                                            <td class="border-b whitespace-nowrap">
                                                <a style="cursor:pointer"
                                                    class="flex items-center text-theme-6 block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                                    <i data-feather="trash-2" class="w-4 h-4 dark:text-gray-300 mr-2"></i>
                                                    Hapus
                                                </a>
                                            </td>
                                            <td class="border-b whitespace-nowrap"><i data-feather="link"
                                                    class="w-4 h-4 dark:text-gray-300 mr-2"></i><a
                                                    href="{{ asset('public/documents/' . $doc->id_transaksi . '/' . $doc->file) }}"
                                                    target="_blank">{{ $doc->nama_file . '.' . strtolower($doc->tipe) }}</a>
                                            </td>
                                            <td class="border-b whitespace-nowrap">{{ $doc->created_at }}</td>
                                            <td class="border-b whitespace-nowrap">{{ $doc->username }}</td>
                                            <td class="border-b whitespace-nowrap text-right">{{ $doc->ukuran }} KB</td>
                                        </tr>
                                    @endforeach --}}
                                        {{-- <tr>
                                        <td class="border-b whitespace-nowrap">
                                            <a style="cursor:pointer"
                                                class="flex items-center text-theme-6 block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                                <i data-feather="trash-2" class="w-4 h-4 dark:text-gray-300 mr-2"></i>
                                                Hapus
                                            </a>
                                        </td>
                                        <td class="border-b whitespace-nowrap">Surat Pernyataan Kepemilikan</td>
                                        <td class="border-b whitespace-nowrap">2021-08-23</td>
                                        <td class="border-b whitespace-nowrap">PDF</td>
                                        <td class="border-b whitespace-nowrap">120 KB</td>
                                    </tr> --}}
                                    </tbody>
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
                                <table class="table dt-table">
                                    <thead>
                                        <tr>
                                            <th class="border-b-2 dark:border-dark-5">#</th>
                                            <th class="border-b-2 dark:border-dark-5">Status</th>
                                            <th class="border-b-2 dark:border-dark-5">Tanggal</th>
                                            <th class="border-b-2 dark:border-dark-5">User</th>
                                            <th class="border-b-2 dark:border-dark-5">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach($activity as $actv) {
                                        ?>
                                        <tr>
                                            <td class="border-b">{{ $i++ }}</td>
                                            <td class="border-b">{{ $actv->statusnya }}</td>
                                            <td class="border-b whitespace-nowrap">{{ $actv->created_at }}</td>
                                            <td class="border-b whitespace-nowrap">{{ $actv->username }}</td>
                                            <td class="border-b">{{ $actv->deskripsi }}</td>
                                        </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
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
        function cekType() {
            if ($("#type-insurance").val() === "PAR") {
                $('.extended-clause').removeAttr('style');
            } else {
                $('.extended-clause').css('display', 'none');
            }
        }
        $(document).ready(function() {
            $('#npwp').inputmask("99.999.999.9-999.999");
            $('#nik').inputmask("9999999999999999");
            $('#masa').inputmask("decimal", { onUnMask: function(maskedValue, unmaskedValue) {
                //do something with the value
                return unmaskedValue;
            }});

            $('.dt-table').DataTable();
            @if ($act !== 'add')
                $('#tb-dokumen').DataTable({
                lengthMenu: [[5, 25, 50, -1], [5, 25, 50, "All"]],
                "ajax": {
                url: "{{ url('api/datadokumen') }}",
                headers: {
                'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                },
                type: "POST",
                data: function(d) {
                d.transid = '{{ $data->transid }}';
                d._token = '{{ csrf_token() }}';
                },
                // success: function(d) {
                // console.log('sql:', d);
                // },
                error: function(d) {
                console.log('error:', d.responseText);
                },
                },
                }).on('draw',function(){
                var tablenya = $('#tb-dokumen').DataTable();
                paginatioon(tablenya,$('#tb-dokumen_paginate > ul.pagination'));
                $('.gotoPage').click(function() {
                gotoPage($(this),tablenya);
                });
                feather.replace();
                });
            @endif
            cekType();
            $('select').select2();
            $('#type-insurance').change(function() {
                cekType();
            });

            $("#kodepos").select2({
                minimumInputLength: 3,
                allowClear: true,
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
            }).on('select2:clearing', function(e) {
                $('#npwp').val("").attr('disabled', false);
                $('#alamat').val("").attr('disabled', false);
            });

            $('#insured').on('select2:select', function(e) {
                var data = e.params.data;
                $('#npwp').val("");
                $('#alamat').val("");
                $('#npwp').attr('disabled', false);
                $('#alamat').attr('disabled', false);
                if (data.npwp !== undefined) {
                    $('#npwp').val(data.npwp);
                    $('#npwp').attr('disabled', true);
                }
                if (data.alamat !== undefined) {
                    $('#alamat').val(data.alamat);
                    $('#alamat').attr('disabled', true);
                }
            });

            $('#range-periode').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'
                },
                startDate: moment().startOf('month'),
                endDate: moment().startOf('month').add(32, 'month'),
                opens: 'left'
            }, function(start, end, label) {
                // console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                //     .format('YYYY-MM-DD'));
            });
        });
    </script>
@endsection

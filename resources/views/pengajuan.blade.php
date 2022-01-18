@extends('layouts.master')
@if (!empty($method) && $method == "renewal")
    @section('title', 'Perpanjangan')
    @section('breadcrumb', 'Perpanjangan')
    @section('menu', 'Pengajuan')
@else
    @section('title', 'Pengajuan')
    @section('breadcrumb', 'Pengajuan')
    @section('menu', 'Pengajuan')
@endif
@section('content')
<style>
    .swal2-container {
        z-index: 100000;
    }
</style>
    <div class="intro-y flex items-center mt-4">
        <h2 class="text-lg font-medium mr-auto">
            Formulir @yield('title') @if (!empty($data->tertanggung)){!! 'a/n <b>' . $data->tertanggung . '</b><br>' !!}@endif
            @if (empty($method) && empty($data))
                <button class="btn btn-sm btn-primary" id="btn-add"><i class="fa fa-save mr-3"></i>Simpan</button>
            @endif
            @if ($method == 'update' && !empty($data))
                <button class="btn btn-sm btn-primary" id="btn-update"><i class="fa fa-save mr-3"></i>Simpan</button>
            @endif
            @if ($method == 'renewal' && !empty($data))
                <button class="btn btn-sm btn-primary" id="btn-perpanjang"><i class="fa fa-sync-alt mr-3"></i>Perpanjang</button>
            @endif
            @if ($method == 'approve' && !empty($data))
                @role('checker|maker')
                    @if($data->id_status == 0)
                        <button class="btn btn-sm btn-success btn-approve"><i class="fa fa-check mr-3"></i>Ajukan</button>
                    @endif
                    @if($data->id_status == 4)
                        <button class="btn btn-sm btn-success btn-approve"><i class="fa fa-check mr-3"></i>Setujui</button>
                        <button class="btn btn-sm btn-warning btn-rollback"><i class="fa fa-redo-alt mr-3"></i>Kembalikan</button>
                        <?php 
                            $status_rollback = "TERTUNDA";
                        ?>
                    @endif
                @endrole
                @role('approver')
                    <button class="btn btn-sm btn-success btn-approve"><i class="fa fa-check mr-3"></i>Setujui</button>
                    <?php 
                        $status_rollback = "TERTUNDA";
                    ?>
                @endrole
                @role('broker|adm')
                    @if ($data->id_status == 2)
                        <button class="btn btn-sm btn-success btn-approve"><i class="fa fa-check mr-3"></i>Verifikasi</button>
                        <?php 
                            $status_rollback = "DISETUJUI";
                        ?>
                    @elseif ($data->id_status == 8)
                        <button class="btn btn-sm btn-success btn-approve"><i class="fa fa-check mr-3"></i>Polis Sesuai</button>
                        <?php 
                            $status_rollback = "MENUNGGU POLIS";
                        ?>
                    @endif
                @endrole
                @role('insurance')
                    <button class="btn btn-sm btn-success btn-approve"><i class="fa fa-check mr-3"></i>Setujui</button>
                    <button class="btn btn-sm btn-primary" id="btn-update"><i class="fa fa-save mr-3"></i>Simpan</button>
                    <?php 
                        $status_rollback = "DIVERIFIKASI";
                    ?>
                @endrole
                @role('approver|broker|insurance|adm')
                    <button class="btn btn-sm btn-warning btn-rollback"><i class="fa fa-redo-alt mr-3"></i>Kembalikan</button>
                @endrole
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
                        @if (!empty($data))
                            <span class="px-2 py-1 rounded-full border text-gray-700 dark:text-gray-600 dark:border-dark-5 mr-1">{{ $status->msdesc }}</span>
                        @endif
                    </h2>
                    @role('broker|insurance|adm')
                    <a href="javascript:;" data-toggle="modal" data-target="#modal-klausula" class="btn btn-primary mr-1 mb-2"><i class="fa fa-file-alt mr-2"></i>Klausula</a>
                    @role('insurance')
                    <a class="btn btn-primary mr-1 mb-2" href="{{ url('cetak_placing/'.$data->transid) }}" target="placing"><i class="fa fa-download mr-2"></i>Placing</a>
                    @endrole
                    @endrole
                </div>
                {{-- <div class="alert alert-primary-soft show flex items-center mb-2" role="alert"> <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> Awesome alert with icon </div> --}}
                <div id="modal-klausula" class="modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1>Klausula</h1>
                            </div>
                            <div class="modal-body">
                                <div class="p-5" id="editor">
                                    <p>@if(!empty($data->klausula)){!! $data->klausula !!}@endif</p>
                                </div>
                            </div>
                            <div class="modal-footer text-right">
                                <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">
                                    Cancel
                                </button>
                                <button type="button" id="btn-klausula" class="btn btn-primary w-20">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="horizontal-form" class="p-5">
                    <form class="formnya">
                        <div class="preview">
                            @if ($method !== 'renewal')
                                @if (($act == 'view' || $act == 'edit') && !empty($data->transid))
                                    <div class="form-inline mt-5">
                                        <label for="transid" class="form-label sm:w-20">Nomor Transaksi</label>
                                        <input type="text" id="transid" class="form-control" required
                                            value="{{ $data->transid }}" disabled>
                                    </div>
                                @endif
                            @else
                                <div class="form-inline mt-5">
                                    <label for="transid_parent" class="form-label sm:w-20">Nomor Transaksi Renewal</label>
                                    <input type="text" id="transid_parent" class="form-control" required
                                        value="{{ $data->transid }}" disabled>
                                </div>
                            @endif
                            <div class="form-inline mt-5">
                                <label for="type_insurance" class="ml-3 form-label sm:w-20">Tipe Asuransi</label>
                                <select id="type_insurance" name="type_insurance" required style="width:100%">
                                    {{-- @foreach ($instype as $val)
                                        <option d-brokerage="{{ $val->brokerage_percent }}" value="{{ $val->id }}" @if (!empty($data->id_instype) && $val->id == $data->id_instype) selected @endif>{{ $val->instype_name }}
                                        </option>
                                    @endforeach --}}
                                </select>
                            </div>
                            @if (!empty($data->id_instype))
                            <script>
                                var newOption = new Option("{{ $data->instype_name }}","{{ $data->id_instype }}", false, false);
                                $('#type_insurance').append(newOption).trigger('change');
                            </script>
                            @endif
                            @if (!empty($data) && $data->id_status >= 2)
                            <div class="form-inline mt-5">
                                <label for="asuransi" class="ml-3 form-label sm:w-20">Asuransi</label>
                                <select id="asuransi" name="asuransi" required style="width:100%">
                                    {{-- @foreach ($asuransi as $val)
                                        <option value="{{ $val->id }}" @if (!empty($data->id_asuransi) && $val->id === $data->id_asuransi) selected="true" @endif>
                                            {{ $val->nama_asuransi }}
                                        </option>
                                    @endforeach --}}
                                </select>
                            </div>
                            @endif
                            <div class="form-inline mt-5">
                                <label for="cabang" class="ml-3 form-label sm:w-20">Cabang</label>
                                <select id="cabang" name="cabang" required style="width:100%">
                                    @foreach ($cabang as $val)
                                        <option alamat="{{ $val->alamat_cabang }}" value="{{ $val->id }}" @if (empty($data->id_cabang)) @if ($val->id === Auth::user()->id_cabang) selected="true" @endif @else @if ($val->id === $data->id_cabang) selected="true" @endif @endif>
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
                                <label for="nopinjaman" class="form-label sm:w-20">Nomor Pinjaman</label>
                                <input type="text" class="form-control allow-decimal" placeholder="Nomor Pinjaman" name="nopinjaman"
                                    id="nopinjaman" value="@if (!empty($data->nopinjaman)){{ $data->nopinjaman }}@endif">
                            </div>
                            <div class="form-inline mt-5">
                                <label for="cif" class="form-label sm:w-20">CIF</label>
                                <input type="text" class="form-control allow-decimal" placeholder="CIF" name="cif"
                                    id="cif" value="@if (!empty($data->cif)){{ $data->cif }}@endif">
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
                                <input type="text" id="nik_insured" class="form-control" name="nik_insured"
                                    @if (!empty($data->nik_insured)) value="{{ $data->nik_insured }}" @endif>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="npwp_insured" class="form-label sm:w-20">NPWP Tertanggung</label>
                                <input type="text" id="npwp_insured" class="form-control masked"
                                    @if (!empty($data->npwp_insured)) value="{{ $data->npwp_insured }}" @endif>
                                <input type="hidden" name="npwp_insured" @if (!empty($data->npwp_insured)) value="{{ $data->npwp_insured }}" @endif>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="nohp_insured" class="form-label sm:w-20">Kontak Tertanggung</label>
                                <div class="input-group w-full">
                                    <div class="input-group-text">+62</div>
                                    <input type="text" class="form-control" name="nohp_insured" id="nohp_insured"
                                        value="@if (!empty($data->nohp_insured)){{ $data->nohp_insured }}@endif" required>
                                </div>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="alamat_insured" class="form-label sm:w-20">Alamat Tertanggung</label>
                                <textarea id="alamat_insured" name="alamat_insured" class="form-control" required @if (!empty($data->alamat_insured)) @endif>@if (!empty($data->alamat_insured)){{ $data->alamat_insured }}@endif</textarea>
                            </div>
                            @role('maker|checker|broker|approver|adm')
                            <div class="form-inline mt-5">
                                <label for="plafond_kredit" class="form-label sm:w-20">Plafond Kredit</label>
                                <input type="text" class="form-control allow-decimal currency masked" placeholder="Plafond Kredit"
                                    id="plafond_kredit" value="@if (!empty($data->plafond_kredit)){{ $data->plafond_kredit }}@endif">
                                <input type="hidden" name="plafond_kredit" @if (!empty($data->plafond_kredit)) value="{{ $data->plafond_kredit }}" @endif>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="outstanding_kredit" class="form-label sm:w-20">Outstanding Kredit</label>
                                <input type="text" class="form-control allow-decimal currency masked" placeholder="Outstanding Kredit"
                                    id="outstanding_kredit" value="@if (!empty($data->outstanding_kredit)){{ $data->outstanding_kredit }}@endif">
                                <input type="hidden" name="outstanding_kredit" @if (!empty($data->outstanding_kredit)) value="{{ $data->outstanding_kredit }}" @endif>
                            </div>
                            @endrole
                            <div class="form-inline mt-5" @if(empty($data) ||$data->id_status <=5) style="display:none;" @endif >
                                <label for="policy_no" class="form-label sm:w-20">Nomor Polis</label>
                                <input type="text" class="form-control allow-decimal" placeholder="Nomor Polis" name="policy_no"
                                    id="policy_no" value="@if (!empty($data->policy_no)){{ $data->policy_no }}@endif">
                            </div>
                            <div class="form-inline mt-5" @if(empty($data) ||$data->id_status <=3) style="display:none;" @endif >
                                <label for="cover_note" class="form-label sm:w-20">Cover Note</label>
                                <input type="text" class="form-control allow-decimal" placeholder="Cover Note" name="cover_note"
                                    id="cover_note" value="@if (!empty($data->cover_note)){{ $data->cover_note }}@endif">
                            </div>
                            @if (!empty($data->policy_parent))
                                <div class="form-inline mt-5">
                                    <label for="nopolis_lama" class="ml-3 form-label sm:w-20">Nopolis Lama</label>
                                    <div class="input-group w-full">
                                        <input type="text" class="form-control" placeholder="Nomor Polis Lama" name="nopolis_lama"
                                            id="nopolis_lama" value="{{ $data->policy_parent }}">
                                        <div id="nopolis_lama" class="input-group-text">Jika Renewal.</div>
                                    </div>
                                </div>
                            @endif
                            <div class="form-inline mt-5">
                                <label for="periode-polis" class="form-label sm:w-20">Periode Polis</label>
                                <input id="periode-polis" class="form-control w-full block mx-auto range-periode" required>
                                <input type="hidden" name="polis_start" value="@if(!empty($data->polis_start)){{ $data->polis_start }}@endif">
                                <input type="hidden" name="polis_end" value="@if(!empty($data->polis_end)){{ $data->polis_end }}@endif">
                            </div>
                            <div class="form-inline mt-5">
                                <label for="masa" class="ml-3 form-label sm:w-20">Masa Asuransi</label>
                                <div class="input-group w-full">
                                    <input type="text" class="form-control" name="masa" id="masa" value="@if (!empty($data->masa)){{ $data->masa }}@endif" required>
                                    <input type="hidden" class="form-control" id="PRORATA">
                                    <div id="masa" class="input-group-text">Hari</div>
                                </div>
                            </div>
                            @role('maker|checker|broker|approver|adm')
                            <div class="form-inline mt-5">
                                <label for="periode-kjpp" class="form-label sm:w-20">Periode KJPP</label>
                                <input id="periode-kjpp" class="form-control w-full block mx-auto range-periode" required>
                                <input type="hidden" name="kjpp_start" value="@if(!empty($data->kjpp_start)){{ $data->kjpp_start }}@endif">
                                <input type="hidden" name="kjpp_end" value="@if(!empty($data->kjpp_start)){{ $data->kjpp_start }}@endif">
                            </div>
                            <div class="form-inline mt-5">
                                <label for="agunan_kjpp" class="form-label sm:w-20">Nilai Pasar KJPP</label>
                                <input type="text" class="form-control allow-decimal currency masked" placeholder="Nilai Pasar KJPP"
                                id="agunan_kjpp" value="@if (!empty($data->agunan_kjpp)){{ $data->agunan_kjpp }}@endif">
                                <input type="hidden" name="agunan_kjpp" @if (!empty($data->agunan_kjpp)) value="{{ $data->agunan_kjpp }}" @endif>
                            </div>
                            @endrole
                            <div class="form-inline mt-5">
                                <label for="jaminan" class="ml-3 form-label sm:w-20">Jenis Jaminan</label>
                                <select id="jaminan" name="jaminan" required style="width:100%">
                                    @foreach ($jaminan as $val)
                                        <option value="{{ $val->msid }}" @if (!empty($data->id_jaminan) && $val->msid == $data->id_jaminan) selected @endif>{{ $val->msdesc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="no_jaminan" class="form-label sm:w-20">Nomor Jenis Jaminan</label>
                                <input type="text" class="form-control allow-decimal" placeholder="Nomor Jenis Jaminan" name="no_jaminan"
                                    id="no_jaminan" value="@if (!empty($data->no_jaminan)){{ $data->no_jaminan }}@endif">
                            </div>
                            @if (!empty($data) && $data->id_status >= 2)
                                <div class="form-inline mt-5">
                                    <label for="okupasi" class="ml-3 form-label sm:w-20">Okupasi</label>
                                    <select id="okupasi" style="width:100%" name="okupasi">
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
                            @endif
                            <div class="form-inline mt-5">
                                <label for="lokasi_okupasi" class="form-label sm:w-20">Lokasi Okupasi</label>
                                <textarea id="lokasi_okupasi" name="lokasi_okupasi" class="form-control" required>@if (!empty($data->location)){{ $data->location }}@endif</textarea>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="objek_okupasi" class="form-label sm:w-20">Objek Pertanggungan</label>
                                <textarea id="objek_okupasi" name="objek_okupasi" class="form-control" required>@if (!empty($data->object)){{ $data->object }}@endif</textarea>
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
                            @if (!empty($data->transid))
                                <input type="hidden" class="form-control" required name="transid" value="{{ $data->transid }}" readonly>
                            @endif
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
                <form class="formnya">
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
                                                onChange="hitung()"
                                                id="kodetrans_value[{{ $row->kodetrans_id }}]"
                                                value="@if (!empty($pricing[$row->kodetrans_id]->value)){{ $pricing[$row->kodetrans_id]->value }}@endif">
                                            <input type="hidden" name="kodetrans_value[{{ $row->kodetrans_id }}]">
                                        </div>
                                    </td>
                                    <td><input name="kodetrans_remarks[{{ $row->kodetrans_id }}]" class="form-control"
                                            value="@if(!empty($pricing[$row->kodetrans_id]->deskripsi)){{ $pricing[$row->kodetrans_id]->deskripsi }}@endif"></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2">
                                    <div class="input-group">
                                        <div id="group-t" class="input-group-text">Total</div>
                                        <input style="text-align:right;" id="kodetrans_value[1]" type="text" d-input="TSI"
                                            class="currency form-control allow-decimal masked total-si" placeholder="Total Nilai Pertanggungan"
                                            aria-label="Total Nilai Pertanggungan" aria-describedby="group-t" readonly
                                            value="@if (!empty($pricing[1]->value)){{ $pricing[1]->value }}@endif">
                                        <input type="hidden" name="kodetrans_value[1]">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="p-5">
                        <div class="sm:grid grid-cols-2 gap-2">
                            <div class="mt-2" @if (!empty($data) && $data->id_status >= 2) @else style="display:none" @endif>
                                <label for="kodetrans_value[2]" class="form-label">Premium</label>
                                <input id="kodetrans_value[2]" d-input="PREMI" onChange="hitung()" type="text" class="currency allow-decimal masked form-control" placeholder="Premium" readonly aria-describedby="Premium" value="@if (!empty($pricing[2]->value)){{ $pricing[2]->value }}@endif">
                                <input type="hidden" name="kodetrans_value[2]">
                            </div>
                            <div class="mt-2" @if (empty($data) || $data->id_status <=1) style="display:none" @endif>
                                <label for="kodetrans_value[10]" class="form-label">Biaya Polis</label>
                                <input id="kodetrans_value[10]" d-input="POLIS" onChange="hitung()" type="text" class="currency allow-decimal masked form-control" placeholder="Biaya Polis" aria-describedby="Biaya Polis" value="@if (!empty($pricing[10]->value)){{ $pricing[10]->value }}@endif">
                                <input type="hidden" name="kodetrans_value[10]">
                            </div>
                            <div class="mt-2" @if (empty($data) || $data->id_status <=1) style="display:none" @endif>
                                <label for="kodetrans_value[11]" class="form-label">Biaya Materai</label>
                                <input id="kodetrans_value[11]" d-input="MATERAI" onChange="hitung()" type="text" class="currency allow-decimal masked form-control" placeholder="Biaya Materai" aria-describedby="Biaya Materai" value="@if (!empty($pricing[11]->value)){{ $pricing[11]->value }}@endif">
                                <input type="hidden" name="kodetrans_value[11]">
                            </div>
                            <div class="mt-2" @if (empty($data) || $data->id_status <=1) style="display:none" @endif>
                                <label for="kodetrans_value[18]" class="form-label">Gross</label>
                                <input id="kodetrans_value[18]" d-input="GROSS" onChange="hitung()" type="text" class="currency allow-decimal masked form-control" placeholder="Gross" aria-describedby="Gross" value="@if (!empty($pricing[18]->value)){{ $pricing[18]->value }}@endif">
                                <input type="hidden" name="kodetrans_value[18]">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @role('adm|broker|insurance')
                <div class="intro-y box mt-5">
            @else
                <div class="intro-y box mt-5" style="display: none">
            @endrole
                <div class="intro-y box p-5">
                    <div>
                        <form class="formnya">
                            <div class="sm:grid grid-cols-2 gap-2">
                                @foreach ($hitung as $row)
                                <div class="mt-2">
                                    <label for="kodetrans_value[{{ $row->kodetrans_id }}]" class="form-label">{{ $row->kodetrans_nama }}</label>
                                    <input id="kodetrans_value[{{ $row->kodetrans_id }}]" d-input="{{ $row->kodetrans_input }}" {!! $row->kodetrans_attribute !!}
                                        onChange="hitung()" type="text" class="@if(strpos($row->kodetrans_nama, '%') !== false) decimal @else currency @endif allow-decimal masked form-control"
                                        placeholder="{{ $row->kodetrans_nama }}" aria-describedby="{{ $row->kodetrans_nama }}"
                                        value="@if(!empty($pricing[$row->kodetrans_id]->value)){{ $pricing[$row->kodetrans_id]->value }}@endif">
                                    <input type="hidden" name="kodetrans_value[{{ $row->kodetrans_id }}]">
                                </div>
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="intro-y box mt-5">
                <div id="horizontal-form" class="p-5">
                    <form class="formnya">
                        <div class="preview">
                            Catatan
                            <div class="form-inline mt-2">
                                <textarea id="catatan" name="catatan" class="form-control" required @if (!empty($data->catatan)) @endif>@if (!empty($data->catatan)){{ $data->catatan }}@endif</textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if ($act !== 'add' && !empty($data->transid))
            <div class="intro-y col-span-12 lg:col-span-6">
                <div class="intro-y box">
                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            Dokumen
                        </h2>
                    </div>
                    <div id="multiple-file-upload" class="p-5">
                        <div class="preview">
                            <div class="alert alert-primary-soft show flex items-center mb-2" role="alert">
                                <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> Kompresi dokumen dapat dilakukan pada web berikut: &nbsp;&nbsp;<a href="https://www.ilovepdf.com/compress_pdf" target="_blank"><img src="https://www.ilovepdf.com/img/ilovepdf.svg" width="70px"></a>
                            </div>
                            <form id="frm-document" action="{{ url('api/dokumen') }}" class="dropzone" method="post">
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
                    <script>
                        $(document).ready(function(){
                            $('.dropzone').each(function () {
                                let dropzoneControl = $(this)[0].dropzone;
                                if (dropzoneControl) {
                                    dropzoneControl.destroy();
                                }
                            });
                            Dropzone.autoDiscover = false;
                            $("#frm-document").dropzone({
                                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                                acceptedFiles: "image/jpeg,image/png,application/pdf",
                                url: "{{ url('api/dokumen') }}",
                                method: "POST",
                                init: function() {
                                    this.on("sending", function(file, xhr, formData) {
                                        $.each($('#frm-document').serializeArray(), function(i, v) {
                                            formData.append(v.name, v.value);
                                        });
                                        formData.append("method", "store");
                                        for (var pair of formData.entries()) {
                                            console.log(pair[0]+ ', ' + pair[1]); 
                                        }
                                    }),
                                    this.on("success", function(file, xhr) {
                                        $('#tb-dokumen').DataTable().ajax.reload();
                                        $('#tb-aktifitas').DataTable().ajax.reload();
                                    });
                                    this.on('error', function(file, response) {
                                        $(file.previewElement).find('.dz-error-message').text(response.file);
                                    });
                                    this.on("complete", function(file, xhr) {
                                        if (file.size > 50*1024*1024) { // 50 MB
                                            alert('Harap melakukan kompresi dokumen terlebih dahulu, karena file lebih besar dari 50MB');
                                        }
                                    });
                                },
                                success: function(file, response) {
                                    console.log('respones',response);
                                },
                                error: function(file, response) {
                                    console.log('response',response);
                                    $(file.previewElement).addClass("dz-error").find('.dz-error-message').text(response.errors.file);
                                }
                            });
                        });
                    </script>
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
        var RATE    = null,
            TSFWD   = null,
            RSMDCC  = null,
            OTHERS  = null,
            maxPeriode = null,
            maxTSI  = null;
        
        @if (!empty($data->id_kodepos))
            TSFWD = {{ $data->rate_TSFWD }};
            RSMDCC = {{ $data->rate_RSMDCC }};
            OTHERS = {{ $data->rate_OTHERS }};
        @endif

        function totalRate() {
            console.log('RATE',RATE);
            console.log('TSFWD',TSFWD);
            console.log('RSMDCC',RSMDCC);
            console.log('OTHERS',OTHERS);
            if (isNaN(RATE) || isNaN(TSFWD) || isNaN(RSMDCC)|| isNaN(OTHERS)) {
                return null;
            }

            var instype = $("#type_insurance").val(),
                total = parseFloat(RATE);
            console.log('total',total);
            
            if (instype == "PAR") {
                total += parseFloat(TSFWD) + parseFloat(RSMDCC) + parseFloat(OTHERS);
            }
            return total;
        }

        function reloadTable() {
            $('#tb-dokumen').DataTable().ajax.reload();
        }

        function hitung() {
            var OKUPASI = $('#okupasi').val(),
                _RATE   = totalRate(),
                PRORATA = $('#PRORATA').val();
            
            @foreach ($value as $row)
            var {!! $row->kodetrans_input !!} = (isNaN(parseFloat($('[name="kodetrans_value[{!! $row->kodetrans_id !!}]"]').val()))) ? 0 : parseFloat($('[name="kodetrans_value[{!! $row->kodetrans_id !!}]"]').val());
            @endforeach
            if (_RATE == null || OKUPASI == null || TSI == null) {
                console.log('Rate: ',_RATE);
                console.log('Okupasi: ',OKUPASI);
                console.log('TSI: ',TSI);
                return false;
            }
            
            @foreach ($formula as $row)
                var {!! $row->kodetrans_input !!} = {!! $row->kodetrans_formula !!};
            @endforeach

            @foreach ($formula as $row)
                $('[d-input="{{ $row->kodetrans_input }}"]').val({{ $row->kodetrans_input }});
            @endforeach

            @foreach ($formula as $row)
                console.log('{!! $row->kodetrans_nama !!}',{!! $row->kodetrans_input !!});
            @endforeach
            console.log('TSI: ', TSI);
            console.log('Premium: ', PREMI);
            console.log("Rate: ", RATE);
            console.log("Materai: ", MATERAI);
            console.log("Biaya Lain: ", LAIN);
            $('.masked').trigger('keyup');
        }

        function disableInput() {
            $(":input").prop('readonly', true);
            $("select").prop('disabled', true);
            $(".range-periode").prop('disabled', true);
            $('#multiple-file-upload').hide();
            $('#catatan').removeAttr('readonly');
            $("[type='search']").removeAttr('readonly');
            $('[name*="_length"]').removeAttr('disabled');
        }
        @if ($act !== 'add' && !empty($data->transid))
            function hapusDokumen(id){
                console.log('hapus');
                $.ajax({
                    url: "{{ url('api/dokumen') }}",
                    method: "POST",
                    data: $('#frm-document').serialize() + "&method=delete&id="+id,
                    headers: {
                        'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                    },
                    success: function(d) {
                        // console.log(d);
                        // console.log('id:',id);
                        Swal.fire(
                            'Berhasil!',
                            d.message,
                            'success'
                        ).then(function() {
                            $('#tb-dokumen').DataTable().ajax.reload();
                            $('#tb-aktifitas').DataTable().ajax.reload();
                        });
                    },
                    error: function(d) {
                        Swal.fire(
                            'Gagal!',
                            d.message,
                            'error'
                        );
                        $('#tb-dokumen').DataTable().ajax.reload();
                        $('#tb-aktifitas').DataTable().ajax.reload();
                        // var message = d.responseJSON.message;
                    }
                });
            };
        @endif
        $(document).ready(function() {
            $('select').select2();
            $('#npwp_insured').inputmask("99.999.999.9-999.999");
            $('#nik_insured').inputmask("9999999999999999");
            $('.range-periode').inputmask("99/99/9999 - 99/99/9999");
            $('#masa').inputmask("decimal");
            $('#nohp_insured').inputmask("decimal");

            $('.dt-table').DataTable();
            @if ($act !== 'add' && !empty($data->transid))
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
                    "order": [[ 2, "desc" ]],
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
                    "order": [[ 0, "desc" ]],
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
                }
            });
            $("#asuransi").select2({
                language: "id",
                allowClear: true,
                placeholder: "Pilih Asuransi",
                ajax: {
                    dataType: "json",
                    url: "{{ url('api/selectasuransi') }}",
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
                }
            });
            @if (!empty($data->id_asuransi))
                var newOption = new Option("{{ $data->nama_asuransi }}",
                {{ $data->id_asuransi }}, false, false);
                $('#asuransi').append(newOption).trigger('change');
            @endif
            @if(!empty($data->polis_start) && !empty($data->polis_end))
                var startPolis = moment("{{ $data->polis_start }}","YYYY-MM-DD");
                var endPolis = moment("{{ $data->polis_end }}","YYYY-MM-DD");
            @else 
                var startPolis = moment();
                var endPolis = moment().add(1, 'year');
            @endif
            
            function cekType() {
                if ($("#type_insurance").val() === "PAR") {
                    $('.extended-clause').removeAttr('style');
                } else {
                    $('.extended-clause').css('display', 'none');
                }
                cekPeriode(startPolis, endPolis);
                cekTSI();

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
                @if (!empty($data->id_okupasi))
                    var newOption = new Option("{{ $data->kode_okupasi . ' - (' . $data->rate . ' ‰) ' . $data->nama_okupasi }}",
                    {{ $data->id_okupasi }}, false, false);
                    $('#okupasi').append(newOption).trigger('change');
                @endif
            }
            cekType();
            $('#type_insurance').change(function() {
                cekType();
            });

            $("#type_insurance").select2({
                language: "id",
                placeholder: "Pilih Tipe Asuransi",
                ajax: {
                    dataType: "json",
                    url: "{{ url('api/selectinstype') }}",
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
                RATE = parseFloat(data.rate);
                hitung();
            });

            $('#kodepos').on('select2:select', function(e) {
                var data = e.params.data;
                TSFWD  = parseFloat(data.rate_TSFWD);
                RSMDCC = parseFloat(data.rate_RSMDCC);
                OTHERS = parseFloat(data.rate_OTHERS);
                hitung();
            });

            $('#insured').on('select2:select', function(e) {
                var data = e.params.data;
                // console.log('data:',data);
                $('#npwp_insured').val("").trigger('change');
                $('[name="npwp_insured"]').val("").trigger('change');
                $('#nik_insured').val("").trigger('change');
                $('[name="nik_insured"]').val("").trigger('change');
                $('#alamat').val("");
                $('#nohp_insured').val("");
                if (data.npwp_insured !== undefined) {
                    $('#npwp_insured').val(data.npwp_insured).trigger('change');
                    $('[name="npwp_insured"]').val(data.npwp_insured).trigger('change');
                }
                if (data.nik_insured !== undefined) {
                    $('[name="nik_insured"]').val(data.nik_insured).trigger('change');
                    $('#nik_insured').val(data.nik_insured).trigger('change');
                }
                if (data.alamat_insured !== undefined) {
                    $('#alamat_insured').val(data.alamat_insured);
                }
                if (data.nohp_insured !== undefined) {
                    $('#nohp_insured').val(data.nohp_insured);
                }
            });
            
            $('#type_insurance').on('select2:select', function(e) {
                var data = e.params.data;
                $('.ql-editor').html("");
                $('[d-input="BROKERPERC"]').val("").trigger('keyup');
                if (data.klausula_template !== undefined) {
                    $('.ql-editor').html(data.klausula_template);
                }
                if (data.brokerage_percent !== undefined) {
                    $('[d-input="BROKERPERC"]').val(data.brokerage_percent).trigger('keyup');
                }
                if (data.max_tsi !== undefined) {
                    maxTSI = parseFloat(data.max_tsi);
                }
                if (data.max_periode_tahun !== undefined) {
                    maxPeriode = data.max_periode_tahun;
                }
                console.log('cek periode');
                cekPeriode(startPolis,endPolis);
                cekTSI();
            });

            $('#cabang').change(function() {
                $('#alamat_cabang').val($('#cabang option:selected').attr('alamat'));
            });

            $('#cabang').change();

            // var startPolis = moment($('#periode-polis').val().substring(0,10), "YYYYMMDD"),
            //     endPolis = moment($('#periode-polis').val().substring(15), "YYYYMMDD"),
            //     startKJPP = moment($('#periode-kjpp').val().substring(0,10), "YYYYMMDD"),
            //     endKJPP = moment($('#periode-kjpp').val().substring(15), "YYYYMMDD");

            @if(!empty($data->kjpp_start) && !empty($data->kjpp_end))
                var startKJPP = moment("{{ $data->kjpp_start }}","YYYY-MM-DD");
                var endKJPP = moment("{{ $data->kjpp_end }}","YYYY-MM-DD");
            @else 
                var startKJPP = moment();
                var endKJPP = moment().add(1, 'year');
            @endif

            function kjpp(startKJPP, endKJPP) {
                $('#periode-kjpp').html(startKJPP.format('DD/MM/YYYY') + ' - ' + endKJPP.format('DD/MM/YYYY'));
                $('[name="kjpp_start"]').val(startKJPP.format('YYYY-MM-DD'));
                $('[name="kjpp_end"]').val(endKJPP.format('YYYY-MM-DD'));
            }

            $('#periode-kjpp').daterangepicker({
                autoApply: true,
                showDropdowns: true,
                startDate: startKJPP,
                endDate: endKJPP,
                locale: {
                    format: 'DD/MM/YYYY'
                },
            }, kjpp);

            kjpp(startKJPP,endKJPP);

            function cekPeriode(startPolis,endPolis) {
                if (maxPeriode !== null) {
                    var years = endPolis.diff(startPolis,"year");
                    startPolis.add(years,'years');
                    var months = endPolis.diff(startPolis,"months");
                    startPolis.add(months,'months');
                    var days = endPolis.diff(startPolis,"days");
                    if (years > maxPeriode || (years >= maxPeriode && (months > 0 || days > 0))) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Perhatian!',
                            text: 'Harap cek kembali periode polis',
                            footer: "Maksimal periode untuk tipe asuransi "+$('#type_insurance option:selected').text()+" adalah "+maxPeriode+" tahun"
                        });
                        $('#btn-add').prop('disabled',true);
                    } else {
                        $('#btn-add').prop('disabled',false);
                    }
                }
            }

            function cekTSI() {
                console.log('cekTSI',maxTSI);
                var TSI = parseFloat($('[d-input="TSI"]').inputmask("unmaskedvalue"));
                console.log('TSI',TSI);
                if (maxTSI !== null) {
                    if (TSI > maxTSI) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Perhatian!',
                            text: 'Harap cek kembali TSI',
                            footer: "Maksimal TSI untuk tipe asuransi "+$('#type_insurance option:selected').text()+" adalah RP. "+parseFloat(maxTSI).toLocaleString()
                        });
                        $('#btn-add').prop('disabled',true);
                        $('.tsi').val(0);
                        return false;
                    } else {
                        $('#btn-add').prop('disabled',false);
                    }
                }
            }

            $('.tsi').keyup(function(){
                cekTSI();
            });

            function prorata(tglAwal, tglAkhir) {
                var bagi_tahun  = 365,
                    tahun_awal  = tglAwal.years(),
                    tahun_akhir = tglAkhir.years(),
                    durasi      = Math.floor(moment.duration(tglAkhir.diff(tglAwal)).asDays()),
                    prorata     = 0,
                    jml_kabisat = 0;
                
                for (let $i = tahun_awal; $i <= tahun_akhir; $i++) {
                    if (moment([$i]).isLeapYear()) {
                        if ($i == tahun_akhir) {
                            if (tglAkhir.format('M') == 2 && tglAkhir.format('D') >= 29 || tglAkhir.format('M') > 2) {
                                jml_kabisat++;
                            }
                        } else {
                            jml_kabisat++;
                        }
                    }
                }
                durasi = durasi - jml_kabisat;
                
                $('#PRORATA').val(durasi / 365);
                hitung();
            }

            function polis(startPolis, endPolis) {
                console.log(endPolis.format('DD/MM/YYYY'));
                $('#periode-polis').html(startPolis.format('DD/MM/YYYY') + ' - ' + endPolis.format('DD/MM/YYYY'));
                // $('#masa').val(Math.round(moment.duration(endPolis.diff(startPolis)).asDays()));
                $('[name="polis_start"]').val(startPolis.format('YYYY-MM-DD'));
                $('[name="polis_end"]').val(endPolis.format('YYYY-MM-DD'));
                var tglAwal = startPolis;
                var tglAkhir = endPolis;
                var durasi = moment.duration(tglAkhir.diff(tglAwal));
                $('#masa').val(Math.floor(durasi.asDays()));
                console.log(Math.floor(durasi.asYears()));
                prorata(tglAwal, tglAkhir);
                if (maxPeriode !== null) {
                    cekPeriode(startPolis, endPolis);
                }
            }

            $('#periode-polis').daterangepicker({
                autoApply: true,
                showDropdowns: true,
                startDate: startPolis,
                endDate: endPolis,
                locale: {
                    format: 'DD/MM/YYYY'
                },
            }, polis);

            polis(startPolis,endPolis);

            $('#masa').keyup(function() {
                endPolis = startPolis.add($(this).val(), 'days').format('DD/MM/YYYY');
                startPolis = startPolis.subtract($(this).val(), 'day');
                $('#periode-polis').data('daterangepicker').setStartDate(startPolis);
                $('#periode-polis').data('daterangepicker').setEndDate(endPolis);
                $('[name="polis_start"]').val($('#periode-polis').data('daterangepicker').startDate.format('YYYY-MM-DD'));
                $('[name="polis_end"]').val($('#periode-polis').data('daterangepicker').endDate.format('YYYY-MM-DD'));
                prorata($('#periode-polis').data('daterangepicker').startDate, $('#periode-polis').data('daterangepicker').endDate);
            });

            @if (empty($method))
                @if (!empty($data))
                    disableInput();
                @endif
            @else
                disableInput();
                @switch($data->id_status)
                    @case("0")
                        $(":input").prop('readonly', false);
                        $("select").prop('disabled', false);
                        $(".range-periode").prop('disabled', false);
                        $('#multiple-file-upload').show();
                        @break

                    @case("1")
                        $('#multiple-file-upload').show();
                        $('#asuransi').prop('disabled', false);
                        $('#okupasi').prop('disabled', false);
                        $('#lokasi_okupasi').prop('readonly', false);
                        $('#kodepos').prop('disabled', false);
                        $('[d-input="POLIS"]').prop('readonly', false);
                        $('[d-input="MATERAI"]').prop('readonly', false);
                        $('[d-input="ADMIN"]').prop('readonly', false);
                        $('[d-input="LAIN"]').prop('readonly', false);
                        $('[d-input="BROKERPERC"]').prop('disabled', false);
                        $('#frm-document :input').prop('disabled',false);
                        $('.dz-hidden-input').prop('disabled',false);
                        @break

                    @case("2")
                        $('#multiple-file-upload').show();
                        $('#asuransi').prop('disabled', false);
                        $('#okupasi').prop('disabled', false);
                        $('#lokasi_okupasi').prop('readonly', false);
                        $('#objek_okupasi').prop('readonly', false);
                        $('#kodepos').prop('disabled', false);
                        $('[d-input="POLIS"]').prop('readonly', false);
                        $('[d-input="MATERAI"]').prop('readonly', false);
                        $('[d-input="ADMIN"]').prop('readonly', false);
                        $('[d-input="LAIN"]').prop('readonly', false);
                        $('[d-input="BROKERPERC"]').prop('disabled', false);
                        $('#frm-document :input').prop('disabled',false);
                        $('.dz-hidden-input').prop('disabled',false);
                        @break

                    @case("3")
                        $('#multiple-file-upload').show();
                        $('#policy_no').prop('readonly', false);
                        $('#cover_note').prop('readonly', false);
                        $('[d-input="POLIS"]').prop('readonly', false);
                        $('[d-input="MATERAI"]').prop('readonly', false);
                        $('[d-input="ADMIN"]').prop('readonly', false);
                        $('[d-input="LAIN"]').prop('readonly', false);
                        $('#frm-document :input').prop('disabled',false);
                        $('.dz-hidden-input').prop('disabled',false);
                        @break

                    @case("10")
                        $(":input").prop('readonly', false);
                        $("select").prop('disabled', false);
                        $(".range-periode").prop('disabled', false);
                        $('#multiple-file-upload').show();
                        @break
                
                    @default
                        
                @endswitch
            @endif

            var toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons

                [{ 'header': 1 }, { 'header': 2 }],               // custom button values
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent

                [{ align: '' }, { 'align': 'center' }, { 'align': 'right' }, { 'align': 'justify' }],

                ['clean']                                         // remove formatting button
            ];
            var quill = new Quill('#editor', {
                modules: {
                    toolbar: toolbarOptions
                },
                theme: 'bubble'
            });
            var klausulaValue = $('.ql-editor').html();
            quill.on('text-change', function() {
                klausulaValue = $('.ql-editor').html();
            });

            $('#btn-add, #btn-update').click(function(){
                var btnHtml = $(this).html(),
                    loading = "<i class='fas fa-spinner fa-pulse' class='mr-2'></i>&nbsp;&nbsp;Loading...",
                    nama_insured = $('#insured option:selected').text(),
                    nama_cabang = $('#cabang option:selected').text();
                console.log('Masuk Sini');
                $(this)
                    .attr('disabled',true)
                    .html(loading);

                $.ajax({
                    url: "{{ url('api/pengajuan') }}",
                    method: "POST",
                    data: $('.formnya').serialize() + "&method=store&_token={{ csrf_token() }}&nama_insured="+nama_insured+"&nama_cabang="+nama_cabang+"&klausula="+encodeURIComponent(klausulaValue),
                    headers: {
                        'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                    },
                    success: function(d) {
                        Swal.fire(
                            'Berhasil!',
                            d.message,
                            'success'
                        ).then(function() {
                            if (d.method == 'create') {
                                window.location = "{{ url('inquiry') }}?data=pengajuan";
                            } else if (d.method == "update") {
                                // do nothing
                            } else {
                                window.top.close();
                            }
                        });
                    },
                    error: function(d) {
                        var message = d.responseJSON.message;
                        console.log('message:',message);
                        Swal.fire(
                            'Gagal!',
                            message,
                            'error'
                        );
                        $.each(d.responseJSON.errors, function(i,v){
                            $('#'+i).closest('div').addClass('has-error');
                            $('#'+i).prev().append('<div class="sm:ml-auto mt-1 sm:mt-0 text-xs pristine-error text-primary-3 mt-2">* harus diisi</div>');
                        });
                    }
                });
                $(this)
                    .attr('disabled',false)
                    .html(btnHtml);
            });

            $('.btn-approve').click(function(){
                var btnHtml = $(this).html(),
                    loading = "<i class='fas fa-spinner fa-pulse' class='mr-2'></i>&nbsp;&nbsp;Loading...",
                    nama_insured = $('#insured option:selected').text(),
                    nama_cabang = $('#cabang option:selected').text();

                $(this)
                    .attr('disabled',true)
                    .html(loading);

                $.ajax({
                    url: "{{ url('api/pengajuan') }}",
                    method: "POST",
                    data: $('.formnya').serialize() + "&method=approve&_token={{ csrf_token() }}&nama_insured="+nama_insured+"&nama_cabang="+nama_cabang+"&klausula="+encodeURIComponent(klausulaValue),
                    headers: {
                        'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                    },
                    success: function(d) {
                        console.log(d);
                        Swal.fire(
                            'Berhasil!',
                            d.message,
                            'success'
                        ).then(function() {
                            if (d.method == 'create') {
                                window.location = "{{ url('inquiry') }}";
                            } else {
                                window.top.close();
                            }
                        });
                    },
                    error: function(d) {
                        console.log(d);
                        var message = d.responseJSON.message;
                        console.log('message:',message);
                        Swal.fire(
                            'Gagal!',
                            message,
                            'error'
                        );
                        $.each(d.responseJSON.errors, function(i,v){
                            $('#'+i).closest('div').addClass('has-error');
                            $('#'+i).prev().append('<div class="sm:ml-auto mt-1 sm:mt-0 text-xs pristine-error text-primary-3 mt-2">* harus diisi</div>');
                        });
                    }
                });
                $(this)
                    .attr('disabled',false)
                    .html(btnHtml);
            });

            @if (!empty($data))
                $('#btn-klausula').click(function(){
                    var klausulaValue = $('.ql-editor').html(),
                        btnHtml = $(this).html(),
                        loading = "<i class='fas fa-spinner fa-pulse' class='mr-2'></i>&nbsp;&nbsp;Loading...";

                    $(this)
                        .attr('disabled',true)
                        .html(loading);

                    $.ajax({
                        url: "{{ url('api/pengajuan') }}",
                        method: "POST",
                        data: {
                            "klausula":klausulaValue,
                            "method":"klausula",
                            "transid":"{{ $data->transid }}",
                            "_token":"{{ csrf_token() }}"},
                        headers: {
                            'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                        },
                        success: function(d) {
                            console.log(d);
                            Swal.fire(
                                'Berhasil!',
                                d.message,
                                'success'
                            );
                        },
                        error: function(d) {
                            console.log(d);
                            var message = d.responseJSON.message;
                            console.log('message:',message);
                            Swal.fire(
                                'Gagal!',
                                message,
                                'error'
                            );
                        }
                    });
                    $(this)
                        .attr('disabled',false)
                        .html(btnHtml);
                });
            @endif

            @if (!empty($data) && $data->id_status !== 0 && $method == 'approve')
                $('.btn-rollback').click(function() {
                    var btnHtml = $(this).html(),
                        loading = "<i class='fas fa-spinner fa-pulse' class='mr-2'></i>&nbsp;&nbsp;Loading...",
                        nama_insured = $('#insured option:selected').text(),
                        method  = "rollback",
                        _token  = "{{ csrf_token() }}",
                        catatan = $('#catatan').val(),
                        transid = "{{ $data->transid }}";

                    $(this)
                        .attr('disabled',true)
                        .html(loading);

                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        text: "Data akan dikembalikan ke status {{ $status_rollback }}",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Kembalikan!',
                        cancelButtonText: 'Tidak Jadi'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Catatan Pengembalian',
                                input: 'textarea',
                                inputValue: catatan,
                                showCancelButton: true,
                                confirmButtonText: 'Konfirmasi',
                                cancelButtonText: 'Batal'
                            }).then(function(result) {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: "{{ url('api/pengajuan') }}",
                                        method: "POST",
                                        data: {catatan,transid,nama_insured,method,_token},
                                        headers: {
                                            'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                                        },
                                        success: function(d) {
                                            console.log(d);
                                            Swal.fire(
                                                'Berhasil!',
                                                d.message,
                                                'success'
                                            ).then(function() {
                                                if (d.method == 'create') {
                                                    window.location = "{{ url('inquiry') }}";
                                                } else {
                                                    window.top.close();
                                                }
                                            });
                                        },
                                        error: function(d) {
                                            var message = d.responseJSON.message;
                                            // console.log(d.responseJSON.errors);
                                            Swal.fire(
                                                'Gagal!',
                                                message,
                                                'error'
                                            )
                                        }
                                    });
                                }
                            });
                        }
                    })
                    $(this)
                        .attr('disabled',false)
                        .html(btnHtml);
                });
            @endif

            RATE = parseFloat($("#okupasi option:selected").text().slice($("#okupasi option:selected").text().indexOf("(") + 1, $("#okupasi option:selected").text().lastIndexOf("‰")));
            $('.masked').trigger('keyup');
            @if(!empty($pricing))
                hitung();
            @endif
            // $(':input').change();
        });
    </script>
@endsection

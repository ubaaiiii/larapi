@extends('layouts.master')
@if (!empty($method) && $method == "renewal")
    @section('title', 'Perpanjangan')
    @section('breadcrumb', 'Perpanjangan')
    @section('menu', 'Wholesales')
@else
    @section('title', 'Pengajuan Wholesales')
    @section('breadcrumb', 'Pengajuan Wholesales')
    @section('menu', 'Wholesales')
@endif

@section('content')

<style>
    .swal2-container {
        z-index: 999999;
    }
    .error {
        color: red;
    }
    .select2-selection--single {
        height: 100% !important;
    }
    .select2-selection__rendered{
        word-wrap: break-word !important;
        text-overflow: inherit !important;
        white-space: normal !important;
    }
    /*Select2 ReadOnly Start*/
    select[readonly].select2-hidden-accessible + .select2-container {
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
        background: #eee;
        box-shadow: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
        display: none;
    }

    /*Select2 ReadOnly End*/
</style>
<div class="intro-y flex items-center mt-2">
    <h2 class="text-lg font-medium mr-auto">
        Formulir @yield('title')
    </h2>
</div>
@if (!empty($data->transid))
    <h2 class="intro-y text-2xl font-medium mt-3 text-center mr-auto">
        {{ $data->transid }} | {{ $status->msdesc }}
        {{-- BDS220300001 | PENDING --}}
        <form class="formnya">
            <input type="hidden" id="transid" name="transid" class="form-control" value="{{ $data->transid }}" readonly>
        </form>
    </h2>
@endif
<!-- BEGIN: Pricing Tab -->
<div class="intro-y flex justify-center @if (!empty($data->transid)) mt-3 @endif">
    <div class="intro-y flex justify-center">
        <div class="pricing-tabs nav nav-tabs box rounded-full overflow-hidden" role="tablist">
            <a id="layout-tertanggung-tab" data-toggle="tab" data-target="#layout-tertanggung" href="javascript:;" class="flex-1 w-32 lg:w-40 py-2 lg:py-3 whitespace-nowrap text-center active" role="tab" aria-controls="layout-tertanggung" aria-selected="true">
                Tertanggung
            </a>
            <a id="layout-pertanggungan-tab" data-toggle="tab" data-target="#layout-pertanggungan" href="javascript:;" class="flex-1 w-32 lg:w-40 py-2 lg:py-3 whitespace-nowrap text-center" role="tab" aria-controls="layout-pertanggungan" aria-selected="false">
                Pertanggungan
            </a>
            @if (!empty($data->transid))
                <a id="layout-log-aktifitas-tab" data-toggle="tab" data-target="#layout-log-aktifitas" href="javascript:;" class="flex-1 w-32 lg:w-40 py-2 lg:py-3 whitespace-nowrap text-center" role="tab" aria-controls="layout-log-aktifitas" aria-selected="false">
                    Log Aktifitas
                </a>
                <a id="layout-dokumen-tab" data-toggle="tab" data-target="#layout-dokumen" href="javascript:;" class="flex-1 w-32 lg:w-40 py-2 lg:py-3 whitespace-nowrap text-center" role="tab" aria-controls="layout-dokumen" aria-selected="false">
                    Dokumen
                </a>
            @endif
        </div>
    </div>
</div>
<!-- END: Pricing Tab -->
<!-- BEGIN: Pricing Content -->
<div class="mt-5">
    <div class="tab-content overflow-auto">
        <div id="layout-tertanggung" class="tab-pane active" role="tabpanel" aria-labelledby="layout-tertanggung-tab">
            <div class="grid grid-cols-12 gap-6">
                <div class="intro-y box col-span-12 lg:col-span-6">
                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
                        <h2 class="font-medium text-base mr-auto">
                            Data Cabang
                        </h2>
                    </div>
                    <div id="horizontal-form" class="p-5">
                        <form class="formnya">
                            <div class="form-inline">
                                <label for="cabang" class="ml-3 form-label sm:w-20">Cabang</label>
                                <select id="cabang" name="cabang" required style="width:100%" d-element="Nama Cabang">
                                    @foreach ($cabang as $val)
                                        <option alamat="{{ $val->alamat_cabang }}" value="{{ $val->id }}" @if (empty($data->id_cabang)) @if ($val->id === Auth::user()->id_cabang) selected="true" @endif @else @if ($val->id === $data->id_cabang) selected="true" @endif @endif>
                                            {{ $val->nama_cabang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="alamat_cabang" class="form-label sm:w-20">Alamat Cabang</label>
                                <textarea id="alamat_cabang" name="alamat_cabang" class="form-control" required d-element="Alamat Cabang">@if (!empty($data->alamat_cabang)){{ $data->alamat_cabang }}@endif</textarea>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="nopinjaman" class="form-label sm:w-20">Nomor Pinjaman</label>
                                <input type="text" class="form-control allow-decimal" placeholder="Nomor Pinjaman" name="nopinjaman"
                                    id="nopinjaman" value="@if (!empty($data->nopinjaman)){{ $data->nopinjaman }}@endif" d-element="Nomor Pinjaman">
                            </div>
                            <div class="form-inline mt-5">
                                <label for="cif" class="form-label sm:w-20">CIF</label>
                                <input type="text" class="form-control allow-decimal" placeholder="CIF" name="cif"
                                    id="cif" value="@if (!empty($data->cif)){{ $data->cif }}@endif" d-element="CIF">
                            </div>
                            @role('maker|checker|broker|approver|adm')
                                <div class="form-inline mt-5">
                                    <label for="plafond_kredit" class="form-label sm:w-20">Plafond Kredit</label>
                                    <input type="text" class="form-control allow-decimal currency masked" placeholder="Plafond Kredit"
                                        id="plafond_kredit" value="@if (!empty($data->plafond_kredit)){{ $data->plafond_kredit }}@endif" required d-element="Plafond Kredit">
                                    <input type="hidden" name="plafond_kredit" @if (!empty($data->plafond_kredit)) value="{{ $data->plafond_kredit }}" @endif>
                                </div>
                                <div class="form-inline mt-5">
                                    <label for="outstanding_kredit" class="form-label sm:w-20">Outstanding Kredit</label>
                                    <input type="text" class="form-control allow-decimal currency masked" placeholder="Outstanding Kredit"
                                        id="outstanding_kredit" value="@if (!empty($data->outstanding_kredit)){{ $data->outstanding_kredit }}@endif" required d-element="Outstanding Kredit">
                                    <input type="hidden" name="outstanding_kredit" @if (!empty($data->outstanding_kredit)) value="{{ $data->outstanding_kredit }}" @endif>
                                </div>
                                <div class="form-inline mt-5">
                                    <label for="periode-kjpp" class="form-label sm:w-20">Periode KJPP</label>
                                    <input id="periode-kjpp" class="form-control w-full block mx-auto range-periode" required d-element="Periode KJPP">
                                    <input type="hidden" name="kjpp_start" value="@if(!empty($data->kjpp_start)){{ $data->kjpp_start }}@endif">
                                    <input type="hidden" name="kjpp_end" value="@if(!empty($data->kjpp_start)){{ $data->kjpp_start }}@endif">
                                </div>
                            @endrole
                        </form>
                    </div>
                </div>
                <div class="intro-y box col-span-12 lg:col-span-6">
                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
                        <h2 class="font-medium text-base mr-auto">
                            Data Nasabah
                        </h2>
                    </div>
                    <div class="p-5">
                        <form class="formnya">
                            <div class="form-inline">
                                <label for="insured" class="ml form-label sm:w-20">Nama Tertanggung (QQ)</label>
                                <select id="insured" style="width:100%;text-transform: uppercase;" class="selek2"
                                    name="insured" required d-element="Nama Tertanggung">
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
                                    @if (!empty($data->nik_insured)) value="{{ $data->nik_insured }}" @endif d-element="NIK Tertanggung">
                            </div>
                            <div class="form-inline mt-5">
                                <label for="npwp_insured" class="form-label sm:w-20">NPWP Tertanggung</label>
                                <input type="text" id="npwp_insured" class="form-control masked"
                                    @if (!empty($data->npwp_insured)) value="{{ $data->npwp_insured }}" @endif d-element="NPWP Tertanggung">
                                <input type="hidden" name="npwp_insured" @if (!empty($data->npwp_insured)) value="{{ $data->npwp_insured }}" @endif>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="nohp_insured" class="form-label sm:w-20">Kontak Tertanggung</label>
                                <div class="input-group w-full">
                                    <div class="input-group-text">+62</div>
                                    <input type="text" class="form-control" name="nohp_insured" id="nohp_insured" value="@if (!empty($data->nohp_insured)){{ $data->nohp_insured }}@endif" required d-element="Kontak Tertanggung">
                                </div>
                            </div>
                            <div class="form-inline mt-5">
                                <label for="alamat_insured" class="form-label sm:w-20">Alamat Tertanggung</label>
                                <textarea id="alamat_insured" name="alamat_insured" class="form-control" required @if (!empty($data->alamat_insured)) @endif d-element="Alamat Tertanggung">@if (!empty($data->alamat_insured)){{ $data->alamat_insured }}@endif</textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="layout-pertanggungan" class="tab-pane" role="tabpanel" aria-labelledby="layout-pertanggungan-tab">
            <div class="grid grid-cols-12 gap-6 mb-5">
                <div class="intro-y col-span-12 @if (!empty($data->transid) && $data->id_status > 2) lg:col-span-6 @endif">
                {{-- <div class="intro-y col-span-12 lg:col-span-6"> --}}
                    <div class="intro-y box">
                        <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto">
                                Data Polis
                            </h2>
                        </div>
                        <div class="p-5">
                            <form class="formnya">
                                <div class="form-inline mt-5">
                                    <label for="type_insurance" class="ml-3 form-label sm:w-20">Tipe Asuransi</label>
                                    <select id="type_insurance" name="id_instype" required style="width:100%" d-element="Tipe Asuransi">
                                    </select>
                                    @if (!empty($data->id_instype))
                                        <script>
                                            var newOption = new Option("{{ $data->instype_name }}","{{ $data->id_instype }}", false, false);
                                            $('#type_insurance').append(newOption).trigger('change');
                                        </script>
                                    @endif
                                </div>
                                @if (!empty($data->transid) && $data->id_status > 3)
                                <div class="form-inline mt-5">
                                    <label for="cover_note" class="form-label sm:w-20">Cover Note</label>
                                    <input type="text" class="form-control allow-decimal" placeholder="Cover Note" name="cover_note"
                                        id="cover_note" value="@if (!empty($data->cover_note)){{ $data->cover_note }}@endif" d-element="Cover Note">
                                </div>
                                <div class="form-inline mt-5">
                                    <label for="policy_no" class="form-label sm:w-20">Nomor Polis</label>
                                    <input type="text" class="form-control allow-decimal" placeholder="Nomor Polis" name="policy_no"
                                        id="policy_no" value="@if (!empty($data->policy_no)){{ $data->policy_no }}@endif" d-element="Nomor Polis">
                                </div>
                                @endif
                                <div class="form-inline mt-5">
                                    <label for="periode-polis" class="form-label sm:w-20">Periode Polis</label>
                                    <input id="periode-polis" class="form-control w-full block mx-auto range-periode" required d-element="Periode Polis">
                                    <input type="hidden" name="polis_start" value="@if(!empty($data->polis_start)){{ $data->polis_start }}@endif">
                                    <input type="hidden" name="polis_end" value="@if(!empty($data->polis_end)){{ $data->polis_end }}@endif">
                                </div>
                                <div class="form-inline mt-5">
                                    <label for="masa" class="ml-3 form-label sm:w-20">Masa Asuransi</label>
                                    <div class="input-group w-full">
                                        <input type="text" class="form-control" name="masa" id="masa" value="@if (!empty($data->masa)){{ $data->masa }}@endif" required d-element="Masa Asuransi">
                                        <input type="hidden" class="form-control" id="PRORATA">
                                        <div id="masa" class="input-group-text">Hari</div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="intro-y col-span-12 lg:col-span-6">
                    <style>
                        #toolbar-container .ql-font span[data-label="Sans Serif"]::before {
                            font-family: "Sans Serif";
                        }
                        
                        #toolbar-container .ql-font span[data-label="Inconsolata"]::before {
                            font-family: "Inconsolata";
                        }
                        
                        #toolbar-container .ql-font span[data-label="Roboto"]::before {
                            font-family: "Roboto";
                        }
                        
                        #toolbar-container .ql-font span[data-label="Mirza"]::before {
                            font-family: "Mirza";
                        }
                        
                        #toolbar-container .ql-font span[data-label="Arial"]::before {
                            font-family: "Arial";
                        }
                        /* Set content font-families */
                        
                        .ql-font-inconsolata {
                            font-family: "Inconsolata";
                        }
                        
                        .ql-font-roboto {
                            font-family: "Roboto";
                        }
                        
                        .ql-font-mirza {
                            font-family: "Mirza";
                        }
                        
                        .ql-font-arial {
                            font-family: "Arial";
                        }
                    </style>
                    <div id="modal-klausula" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2><b><i class="fa fa-edit mr-2"></i>Klausula</b></h2>
                                </div>
                                <div class="modal-body">
                                    <div id="standalone-container">
                                        <div id="toolbar-container">
                                            <span class="ql-formats">
                                                <select class="ql-font">
                                                    <option selected>Sans Serif</option>
                                                    <option value="inconsolata">Inconsolata</option>
                                                    <option value="roboto">Roboto</option>
                                                    <option value="mirza">Mirza</option>
                                                    <option value="arial">Arial</option>
                                                </select>
                                                <select class="ql-size"></select>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-bold"></button>
                                                <button class="ql-italic"></button>
                                                <button class="ql-underline"></button>
                                                <button class="ql-strike"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <select class="ql-color"></select>
                                                <select class="ql-background"></select>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-image"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-header" value="1"></button>
                                                <button class="ql-header" value="2"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-list" value="ordered"></button>
                                                <button class="ql-list" value="bullet"></button>
                                                <button class="ql-indent" value="-1"></button>
                                                <button class="ql-indent" value="+1"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-script" value="sub"></button>
                                                <button class="ql-script" value="super"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-clean"></button>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="editor">
                                        <p>@if(!empty($data->klausula)){!! $data->klausula !!}@endif</p>
                                    </div>
                                </div>
                                <div class="modal-footer text-right">
                                    <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">
                                        Batal
                                    </button>
                                    <button type="button" id="btn-klausula" class="btn btn-primary">
                                        <i class="fa fa-save mr-2"></i> Simpan Klausula
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="modal-installment" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl" style="width: 1300px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2>
                                        <b><i class="fa fa-credit-card mr-2"></i>Installment</b>
                                    </h2>
                                    @if((!empty($method) && !empty($data->transid)) || empty($data->transid))
                                    <div class="w-full sm:w-auto flex items-center sm:ml-auto sm:mt-0">
                                        <button type="button" class="btn btn-sm btn-primary w-32 btn-tambah" onclick="addInstallment()"><i class="fa fa-plus mr-2"></i> Tambah </button>
                                    </div>
                                    @endif
                                </div>
                                <div class="modal-body">
                                    <div id="responsive-table">
                                        <div class="preview">
                                            <div class="overflow-x-auto">
                                                <form class="formnya installment">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">No.</th>
                                                                <th width="14%" class="text-center border-b-2 dark:border-dark-5 whitespace-nowrap">TGL. TAGIHAN <i class="fa fa-info-circle text-theme-1 tooltip" title="Installment akan disimpan berurutan sesuai tanggal tagihan" data-theme="light" onmouseover="setTimeout(()=>{$('#tippy-1').css('z-index',1000000),100})"></i></th>
                                                                <th width="14%" class="text-center border-b-2 dark:border-dark-5 whitespace-nowrap">BIAYA POLIS</th>
                                                                <th width="14%" class="text-center border-b-2 dark:border-dark-5 whitespace-nowrap">BIAYA MATERAI</th>
                                                                <th width="14%" class="text-center border-b-2 dark:border-dark-5 whitespace-nowrap">BIAYA LAIN</th>
                                                                <th width="14%" class="text-center border-b-2 dark:border-dark-5 whitespace-nowrap">PREMIUM</th>
                                                                <th width="14%" class="text-center border-b-2 dark:border-dark-5 whitespace-nowrap">TOTAL</th>
                                                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="body-installment">
                                                            @if(isset($data_installment) && count($data_installment) > 0)
                                                                @foreach ($data_installment as $row => $installment)
                                                                    <tr id="{{ $installment->id }}">
                                                                        <td>{{ $row + 1 }}</td>
                                                                        <td class="whitespace-nowrap">
                                                                            <input type="hidden" name="id_installment[{{ $installment->id }}]" value="{{ $installment->id }}">
                                                                            <input type="date" class="form-control tgl-tagihan" name="tgl_tagihan[{{ $installment->id }}]" value="{{ $installment->tgl_tagihan }}">
                                                                        </td>
                                                                        @if ($row + 1 == 1)
                                                                            <td class="whitespace-nowrap">
                                                                                <input type="text" id="by_polis_installment" class="form-control form-control-sm allow-decimal currency masked w-40" placeholder="Biaya Polis" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Biaya Polis" readonly>
                                                                                <input type="hidden" name="by_polis_installment">
                                                                            </td>
                                                                            <td class="whitespace-nowrap">
                                                                                <input type="text" id="by_materai_installment" class="form-control form-control-sm allow-decimal currency masked w-40" placeholder="Biaya Materai" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Biaya Materai" readonly>
                                                                                <input type="hidden" name="by_materai_installment">
                                                                            </td>
                                                                            <td class="whitespace-nowrap">
                                                                                <input type="text" id="by_lain_installment" class="form-control form-control-sm allow-decimal currency masked w-40" placeholder="Biaya Lain" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Biaya Lain" readonly>
                                                                                <input type="hidden" name="by_lain_installment">
                                                                            </td>
                                                                        @else
                                                                            <td class="whitespace-nowrap" colspan="3"></td>
                                                                        @endif
                                                                        <td class="whitespace-nowrap">
                                                                            <input type="text" id="premium_installment[{{ $installment->id }}]" class="form-control form-control-sm allow-decimal currency masked w-40" placeholder="Premium" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Premium" readonly>
                                                                            <input type="hidden" name="premium_installment[{{ $installment->id }}]">
                                                                        </td>
                                                                        <td class="whitespace-nowrap">
                                                                            <input type="text" id="total_installment[{{ $installment->id }}]" class="form-control form-control-sm allow-decimal currency masked w-40" placeholder="Total Gross" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Total Installment" readonly>
                                                                            <input type="hidden" name="total_installment[{{ $installment->id }}]">
                                                                        </td>
                                                                        <td class="whitespace-nowrap">
                                                                            @if ($row + 1 != 1)
                                                                                @if(!empty($method))
                                                                                <div class="flex justify-center items-center">
                                                                                    <button type="button" class="flex items-center text-theme-6 btn-hapus" onclick="removeInstallment({{ $installment->id }})">
                                                                                        <i class="w-4 h-4 mr-1 fa fa-trash"></i>
                                                                                        Hapus 
                                                                                    </button>
                                                                                </div>
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr id="1">
                                                                    <td>1</td>
                                                                    <td class="whitespace-nowrap">
                                                                        <input type="hidden" name="id_installment[1]" value="1">
                                                                        <input type="date" class="form-control tgl-tagihan" name="tgl_tagihan[1]" value="{{ date('Y-m-d') }}">
                                                                    </td>
                                                                    <td class="whitespace-nowrap">
                                                                        <input type="text" id="by_polis_installment" class="form-control form-control-sm allow-decimal currency masked w-40" placeholder="Biaya Polis" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Biaya Polis" readonly>
                                                                        <input type="hidden" name="by_polis_installment">
                                                                    </td>
                                                                    <td class="whitespace-nowrap">
                                                                        <input type="text" id="by_materai_installment" class="form-control form-control-sm allow-decimal currency masked w-40" placeholder="Biaya Materai" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Biaya Materai" readonly>
                                                                        <input type="hidden" name="by_materai_installment">
                                                                    </td>
                                                                    <td class="whitespace-nowrap">
                                                                        <input type="text" id="by_lain_installment" class="form-control form-control-sm allow-decimal currency masked w-40" placeholder="Biaya Lain" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Biaya Lain" readonly>
                                                                        <input type="hidden" name="by_lain_installment">
                                                                    </td>
                                                                    <td class="whitespace-nowrap">
                                                                        <input type="text" id="premium_installment[1]" class="form-control form-control-sm allow-decimal currency masked w-40" placeholder="Premium" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Premium" readonly>
                                                                        <input type="hidden" name="premium_installment[1]">
                                                                    </td>
                                                                    <td class="whitespace-nowrap">
                                                                        <input type="text" id="total_installment[1]" class="form-control form-control-sm allow-decimal currency masked w-40" placeholder="Total Gross" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Total Installment" readonly>
                                                                        <input type="hidden" name="total_installment[1]">
                                                                    </td>
                                                                    <td class="whitespace-nowrap">
                                                                        
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td class="border-t text-right whitespace-nowrap"></td>
                                                                <td class="border-t text-right whitespace-nowrap"></td>
                                                                <td class="border-t text-right whitespace-nowrap"></td>
                                                                <td class="border-t text-right whitespace-nowrap"></td>
                                                                <td class="border-t text-right whitespace-nowrap"></td>
                                                                <td class="border-t text-right whitespace-nowrap">
                                                                    <b>TOTAL</b>
                                                                </td>
                                                                <td class="border-t whitespace-nowrap">
                                                                    <input id="total_installment" type="text" class="allow-decimal currency masked form-control form-control-sm" placeholder="Gross Premium" aria-describedby="Gross Premium" style="text-align: right; font-weight:bold;" inputmode="decimal" readonly>
                                                                    <input type="hidden" name="total_installment">
                                                                </td>
                                                                <td class="border-t whitespace-nowrap"></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if((!empty($method) && !empty($data->transid)) || empty($data->transid))
                                <div class="modal-footer text-right">
                                    <button type="button" onclick="cancelInstallment()" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">
                                        Batal
                                    </button>
                                    <button type="button" data-dismiss="modal" class="btn btn-primary">
                                        <i class="fa fa-save mr-2"></i> Simpan Installment
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (!empty($data->transid) && $data->id_status > 2)
                        <div class="intro-y box" id="div-asuransi">
                            <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
                                <h2 class="font-medium text-base mr-auto">
                                    Data Asuransi
                                </h2>
                                @if((!empty($method) && !empty($data->transid)) || empty($data->transid))
                                <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                                    {{-- <button type="button" class="btn btn-sm btn-primary w-32 mr-2 mb-2 btn-tambah" onclick="generatePlacing()"><i class="fa fa-download mr-2"></i> Placing </button> --}}
                                    <button type="button" class="btn btn-sm btn-primary w-32 mr-2 mb-2 btn-tambah" onclick="addAsuransiRow()"><i class="fa fa-plus mr-2"></i> Tambah </button>
                                    <button type="button" class="btn btn-sm btn-primary w-32 mr-2 mb-2" id="btn-asuransi"><i class="fa fa-save mr-2"></i> Simpan Asuransi </button>
                                </div>
                                @endif
                            </div>
                            <div class="p-5" id="responsive-table">
                                <div class="preview">
                                    <div class="overflow-x-auto">
                                        <form class="formnya form-asuransi">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th width="50%" class="border-b-2 dark:border-dark-5 whitespace-nowrap">ASURANSI</th>
                                                        <th width="20%" class="text-center border-b-2 dark:border-dark-5 whitespace-nowrap">SHARE (%)</th>
                                                        <th width="30%" class="border-b-2 dark:border-dark-5 whitespace-nowrap"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="body-asuransi">
                                                    @if(!empty($data_penanggung))
                                                        @foreach ($data_penanggung as $row => $penanggung)
                                                            <tr id="{{ $row + 1 }}">
                                                                <td class="whitespace-nowrap">
                                                                    <select class="asuransi" name="asuransi[{{ $row + 1 }}]" required style="width:100%" d-element="Nama Asuransi">
                                                                    </select>
                                                                    <script>
                                                                    @if (!empty($penanggung->id_asuransi))
                                                                        $(document).ready(function(){
                                                                            setTimeout(() => {
                                                                                var newOption = new Option('{{ $penanggung->nama_asuransi }}', {{ $penanggung->id_asuransi }}, false, false);
                                                                                $('[name="asuransi\\[{{ $row + 1 }}\\]"]').append(newOption).trigger('change');
                                                                            }, 500);
                                                                        });
                                                                    @endif
                                                                </script>
                                                                </td>
                                                                <td class="whitespace-nowrap">
                                                                    <input id="share[{{ $row + 1 }}]" type="text" class="share-asuransi decimal allow-decimal masked form-control form-control-sm" placeholder="Share (%)" aria-describedby="Share (%)" style="text-align: right;" inputmode="decimal" onchange="hitungAsuransi()" d-element="Share Asuransi" value="{{ $penanggung->share_pertanggungan }}">
                                                                    <input type="hidden" name="share[{{ $row + 1 }}]" value="{{ $penanggung->share_pertanggungan }}">
                                                                </td>
                                                                <td class="whitespace-nowrap">
                                                                    <div class="flex justify-center items-center">
                                                                        <button type="button" class="flex items-center text-theme-1 btn-hapus mr-2" onclick="printPlacing({{ $row + 1 }})">
                                                                            <i class="w-4 h-4 mr-1 fa fa-print"></i>
                                                                            Placing
                                                                        </button>
                                                                        <button type="button" class="flex items-center text-theme-6 btn-hapus" onclick="removeAsuransiRow({{ $row + 1 }})">
                                                                            <i class="w-4 h-4 mr-1 fa fa-trash"></i>
                                                                            Hapus
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td class="border-t text-right whitespace-nowrap">
                                                            <b>TOTAL</b>
                                                        </td>
                                                        <td class="border-t whitespace-nowrap">
                                                            <input id="total_share" type="text" class="decimal allow-decimal masked form-control form-control-sm" placeholder="Total Share (%)" aria-describedby="Total Share (%)" style="text-align: right; font-weight:bold;" inputmode="decimal" readonly>
                                                            <div class="pristine-error text-primary-3 mt-2" hidden>
                                                                Total share tidak boleh lebih dari 100%
                                                            </div>
                                                            <input type="hidden" name="total_share">
                                                        </td>
                                                        <td class="border-t whitespace-nowrap"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            {{-- <div class="text-right border-t">
                                                <button type="button" onclick="cancelInstallment()" data-dismiss="modal" class="mt-2 btn btn-outline-secondary w-20 mr-1">
                                                    Cancel
                                                </button>
                                                <button type="button" id="btn-asuransi" class="btn btn-primary w-20">
                                                    Save
                                                </button>
                                            </div> --}}
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="intro-y col-span-12 mb-5">
                <div class="intro-y box">
                    <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            Data Objek Pertanggungan
                        </h2>
                        @if((!empty($method) && !empty($data->transid) && $data->id_status == 0) || empty($data->transid))
                        <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                            <button type="button" class="btn btn-sm btn-primary w-32 mr-2 mb-2 btn-tambah" onclick="addObjekRow()"><i class="fa fa-plus mr-2"></i> Tambah </button>
                        </div>
                        @endif
                    </div>
                    <div class="p-5" id="responsive-table">
                        <div class="preview">
                            <div class="overflow-x-auto">
                                <form class="formnya">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="whitespace-nowrap">No.</th>
                                                <th class="text-center whitespace-nowrap">OBJEK PERTANGGUNGAN</th>
                                                <th class="text-center whitespace-nowrap">ALAMAT OBJEK / KODEPOS</th>
                                                <th class="text-center whitespace-nowrap">JENIS JAMINAN / DESKRIPSI</th>
                                                <th class="text-center whitespace-nowrap">SUM INSURED</th>
                                                @if (!empty($data->transid) && $data->id_status > 1)
                                                    <th class="text-center whitespace-nowrap">PERLUASAN</th>
                                                @endif
                                                <th class="text-center whitespace-nowrap">NILAI PASAR OBJEK / TAKSASI CI / Retaksasi CI</th>
                                                <th class="whitespace-nowrap"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="body-objek">
                                            @if (!empty($data->transid))
                                                @foreach ($data_objek as $row => $objek)
                                                    {{-- @dd($objek) --}}
                                                    <tr class="intro-x" valign="top" id="{{ $row + 1 }}">
                                                        <td class="text-center">{{ $row + 1 }}</td>
                                                        <td class="text-center">
                                                            <textarea name="objek[{{ $row + 1 }}]" class="form-control form-control-sm" required placeholder="DESKRIPSI OBJEK" d-element="Deskripsi Objek {{ $row + 1 }}">{{ $objek->objek }}</textarea>
                                                            <input type="hidden" name="id_objek[{{ $row + 1 }}]" value="{{ $objek->id_objek }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <textarea name="alamat[{{ $row + 1 }}]" class="form-control form-control-sm" required placeholder="ALAMAT LENGKAP LOKASI OBJEK" d-element="Alamat Objek {{ $row + 1 }}">{{ $objek->alamat_objek }}</textarea>
                                                            <select class="kodepos" id="id_kodepos[{{ $row + 1 }}]" name="id_kodepos[{{ $row + 1 }}]" style="width:100%" required d-element="Kodepos {{ $row + 1 }}"></select>
                                                            <script>
                                                                // var newOption = new Option('{{ $objek->nama_kodepos }}', {{ $objek->id_kodepos }}, false, true);
                                                                $(document).ready(function(){
                                                                    var newOption = $("<option selected='selected'></option>").val({{ $objek->id_kodepos }}).text("{{ $objek->nama_kodepos }}").attr('wilayah',"{{ $objek->wilayah }}");
                                                                    $('[name="id_kodepos\\[{{ $row + 1 }}\\]"]').append(newOption).trigger('change');
                                                                    initKodepos({{ $row + 1 }});
                                                                });
                                                            </script>
                                                        </td>
                                                        <td>
                                                            <div class="input-group-text">
                                                                <select class="selek2" name="id_jaminan[{{ $row + 1 }}]" required style="width:100%" d-element="Jenis Jaminan {{ $row + 1 }}">
                                                                    @foreach ($jaminan as $val)
                                                                        <option value="{{ $val->msid }}" @if (!empty($objek->id_jaminan) && $val->msid == $objek->id_jaminan) selected @endif>{{ $val->msdesc }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <textarea name="no_jaminan[{{ $row + 1 }}]" class="form-control form-control-sm mt-1" required placeholder="Deskripsi Jaminan / Nomor" d-element="Deskripsi Jaminan {{ $row + 1 }}">{{ $objek->no_jaminan }}</textarea>
                                                        </td>
                                                        <td id="col_sumins[{{ $row + 1 }}]">
                                                            @foreach ($data_objek_pricing[$objek->id_objek] as $row_objek_pricing => $objek_pricing)
                                                                <div id="{{ $row_objek_pricing + 1 }}" class="sumins">
                                                                    <div class="input-group mb-1">
                                                                        @if (((!empty($method) && !empty($data->transid) && $data->id_status == 0)) || (empty($method) && empty($data->transid)))
                                                                            <button type="button" type="button" class="btn btn-primary form-control btn-tambah" onclick="addSumInsured({{ $row + 1 }})" style="width:50px"><i class="fa fa-plus"></i></button>
                                                                        @endif
                                                                        <div class="input-group-text">
                                                                            <select class="selek2" name="sumins_type[{{ $row + 1 }}][{{ $row_objek_pricing + 1 }}]" required d-element="Tipe Sum Insured {{ $row + 1 }} {{ $row_objek_pricing + 1 }}">
                                                                                @foreach ($price as $val)
                                                                                    <option value="{{ $val->kodetrans_id }}" @if ($val->kodetrans_id == $objek_pricing->id_kodetrans) selected @endif>{{ $val->kodetrans_nama }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <input type="text" class="form-control allow-decimal currency masked w-40" placeholder="Nilai Pertanggungan" id="sumins_value[{{ $row + 1 }}][{{ $row_objek_pricing + 1 }}]" style="text-align: right;" inputmode="decimal" onchange="hitungTSI()" required d-element="Nilai Pertanggungan {{ $row + 1 }}" value="{{ $objek_pricing->value }}">
                                                                        @if((!empty($method) && !empty($data->transid) && $data->id_status == 0) || (empty($method) && empty($data->transid)))
                                                                        <button type="button" class="btn btn-danger form-control ml-2 btn-hapus" onclick="removeSumInsured({{ $row + 1 }}, '#{{ $row_objek_pricing + 1 }}')" style="width:50px"><i class="fa fa-trash"></i></button>
                                                                        @endif
                                                                    </div>
                                                                    <input type="hidden" name="sumins_value[{{ $row + 1 }}][{{ $row_objek_pricing + 1 }}]" value="{{ $objek_pricing->value }}">
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                        @if (!empty($data->transid) && $data->id_status > 1)
                                                        <td>
                                                            <select class="perluasan" d-id="{{ $row + 1 }}" id="perluasan[{{ $row + 1 }}]" name="perluasan[{{ $row + 1 }}][]" style="width:100%" d-element="Perluasan {{ $row + 1 }}" multiple="multiple"></select>
                                                            <script>
                                                                $(document).ready(function(){
                                                                    var value = [];
                                                                    @foreach ($data_objek_perluasan[$objek->id_objek] as $objek_perluasan)
                                                                        var newOption = $("<option selected='selected'></option>").val({{ $objek_perluasan->id_perluasan }}).text("{{ $objek_perluasan->kode }}");
                                                                        $('#perluasan\\[{{ $row + 1 }}\\]').append(newOption).trigger('change');
                                                                        value.push({{ $objek_perluasan->id_perluasan }});
                                                                    @endforeach
                                                                    setTimeout(() => {
                                                                        $('#perluasan\\[{{ $row + 1 }}\\]').val(value).change();
                                                                    }, 1000);
                                                                });
                                                            </script>
                                                        </td>
                                                        @endif
                                                        <td class="text-center">
                                                            <input type="text" class="form-control form-control-sm allow-decimal currency masked w-100" placeholder="Nilai Pasar OBJEK / Taksasi CI / Retaksasi CI" id="agunan_kjpp[{{ $row + 1 }}]" style="text-align: right;" inputmode="decimal" onchange="hitungTSI()" required d-element="Nilai Pasar {{ $row + 1 }}" value="{{ $objek->agunan_kjpp }}">
                                                            <input type="hidden" name="agunan_kjpp[{{ $row + 1 }}]" value="{{ $objek->agunan_kjpp }}">
                                                        </td>
                                                        <td class="table-report__action">
                                                            @if((!empty($method) && !empty($data->transid) && $data->id_status == 0) || (empty($method) && empty($data->transid)))
                                                            <div class="flex justify-center items-center">
                                                                <button type="button" class="flex items-center text-theme-6 btn-hapus" onclick="removeObjekRow(1)">
                                                                    <i class="w-4 h-4 mr-1 fa fa-trash"></i>
                                                                    Hapus 
                                                                </button>
                                                            </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (!empty($data->transid) && $data->id_status > 1)
                <div class="intro-y col-span-12 mb-5">
                    <div class="intro-y box">
                        <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto">
                                Data Okupasi & Premi
                            </h2>
                            @if ($data->id_status >= 2)
                                <a href="javascript:;" data-toggle="modal" data-target="#modal-klausula" class="btn btn-sm btn-primary mr-1 mb-2"><i class="fa fa-file-alt mr-2"></i>Klausula</a>
                                <a href="javascript:;" data-toggle="modal" data-target="#modal-installment" class="btn btn-sm btn-primary mr-1 mb-2 btn-installment"><i class="fa fa-credit-card mr-2"></i>Installment</a>
                            @endif
                        </div>
                        <div class="p-5" id="responsive-table">
                            <div class="preview">
                                <div class="overflow-x-auto">
                                    <form class="formnya">
                                        <table class="table" style="display: block;overflow-x: auto;">
                                            <thead>
                                                <tr>
                                                    <th width="1%" class="text-center whitespace-nowrap">No.</th>
                                                    <th width="15%" class="text-center whitespace-nowrap">JENIS PERTANGGUNGAN</th>
                                                    <th width="34%" class="text-center whitespace-nowrap">KODE OKUPASI</th>
                                                    <th width="16%" class="text-center whitespace-nowrap" id="head-rate">RATE (‰)</th>
                                                    <th width="16%" class="text-center whitespace-nowrap">TOTAL SUM INSURED</th>
                                                    <th width="16%" class="text-center whitespace-nowrap" id="head-premi">TOTAL PREMIUM</th>
                                                </tr>
                                            </thead>
                                            <tbody id="body-okupasi" valign="top">
                                                @if (!empty($data->transid))
                                                    @foreach ($data_objek as $row => $objek)
                                                        <tr class="intro-x row-okupasi-{{ $row + 1 }}" id="{{ $row + 1 }}">
                                                            <td class="w-10 text-center">
                                                                {{ $row + 1 }}
                                                            </td>
                                                            <td class="text-center">
                                                                <select class="kelas" id="id_kelas[{{ $row + 1 }}]" d-id="{{ $row + 1 }}" name="id_kelas[{{ $row + 1 }}]" style="width:100%" d-element="Jenis Pertanggungan {{ $row + 1 }}"></select>
                                                                <script>
                                                                    @if (!empty($objek->id_kelas))
                                                                        $(document).ready(function(){
                                                                            setTimeout(() => {
                                                                                var newOption = new Option('{{ $objek->nama_kelas }}', {{ $objek->id_kelas }}, false, false);
                                                                                $('[name="id_kelas\\[{{ $row + 1 }}\\]"]').append(newOption).trigger('change');
                                                                            }, 500);
                                                                        });
                                                                    @endif
                                                                </script>
                                                            </td>
                                                            <td>
                                                                <select class="okupasi" id="okupasi[{{ $row + 1 }}]" name="okupasi[{{ $row + 1 }}]" required style="width: 100%" d-element="Okupasi 1"></select>
                                                                <script>
                                                                    @if (!empty($objek->id_okupasi))
                                                                        $(document).ready(function(){
                                                                            setTimeout(() => {
                                                                                var newOption = new Option('{{ $objek->nama_okupasi }}', {{ $objek->id_okupasi }}, false, false);
                                                                                $('[name="okupasi\\[{{ $row + 1 }}\\]"]').append(newOption).trigger('change');
                                                                            }, 500);
                                                                        });
                                                                    @endif
                                                                </script>
                                                            </td>
                                                            <td class="text-center">
                                                                <input id="rate_okupasi[{{ $row + 1 }}]" type="text" placeholder="Rate (‰)" class="rate form-control form-control-sm allow-decimal decimal masked" style="text-align: right; width: 100%" required inputmode="decimal" onchange="hitungTSI()" d-element="Rate Okupasi {{ $row + 1 }}" value="@if(!empty($objek->rate)){{ $objek->rate }}@endif">
                                                                <input type="hidden" name="rate_okupasi[{{ $row + 1 }}]" class="rate" value="@if(!empty($objek->rate)){{ $objek->rate }}@endif">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="border-theme-9 form-control form-control-sm allow-decimal currency masked" placeholder="Total Sum Insured" id="kodetrans_value[1][{{ $row + 1 }}]" style="text-align: right; width: 100%; font-weight: bold" inputmode="decimal" readonly d-element="TSI {{ $row + 1 }}">
                                                                <input type="hidden" name="kodetrans_value[1][{{ $row + 1 }}]">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control form-control-sm allow-decimal currency masked" placeholder="Premium" id="kodetrans_value[2][{{ $row + 1 }}]" style="text-align: right; width: 100%" inputmode="decimal" readonly d-element="Premium {{ $row + 1 }}">
                                                                <input type="hidden" name="kodetrans_value[2][{{ $row + 1 }}]">
                                                            </td>
                                                        </tr>
                                                        @if(!empty($data_objek_perluasan[$objek->id_objek]))
                                                            @foreach ($data_objek_perluasan[$objek->id_objek] as $row_perluasan => $objek_perluasan)
                                                                <tr class="perluasan-row-{{ $row + 1 }}-{{ $objek_perluasan->id }}">
                                                                    <td colspan="2"></td>
                                                                    <td>
                                                                        <b>{{ $objek_perluasan->kode }}</b> - {{ $objek_perluasan->keterangan }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <input id="rate_perluasan[{{ $row + 1 }}][{{ $objek_perluasan->id }}]" type="text" placeholder="Rate Perluasan (‰)" class="rate-perluasan rate form-control form-control-sm allow-decimal decimal masked" style="text-align: right; width: 100%" required inputmode="decimal" onchange="hitungPerluasan()" d-element="Rate Perluasan {{ $row + 1 }}" value="@if(!empty($objek_perluasan->rate) && $objek_perluasan->rate !== 0){{ $objek_perluasan->rate }}@endif">
                                                                        <input type="hidden" name="rate_perluasan[{{ $row + 1 }}][{{ $objek_perluasan->id }}]" class="rate" value="@if(!empty($objek_perluasan->rate) && $objek_perluasan->rate !== 0){{ $objek_perluasan->rate }}@endif">
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <div class="input-group">
                                                                            <div id="input-group-email" class="form-control form-control-sm input-group-text" style="width:35px;">x</div>
                                                                            <input type="text" class="rate form-control form-control-sm allow-decimal currency masked" placeholder="Nilai Pengkali Perluasan" id="value_perluasan[{{ $row + 1 }}][{{ $objek_perluasan->id }}]" style="text-align: right; width: 100%" inputmode="decimal" onchange="hitungPerluasan()" d-element="Value Perluasan {{ $row + 1 }}" value="{{ $objek_perluasan->value }}">
                                                                        </div>
                                                                        <input type="hidden" name="value_perluasan[{{ $row + 1 }}][{{ $objek_perluasan->id }}]" value="{{ $objek_perluasan->value }}">
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <input type="text" class="form-control form-control-sm allow-decimal currency masked" placeholder="Premium" id="premi_perluasan[{{ $row + 1 }}][{{ $objek_perluasan->id }}]" style="text-align: right; width: 100%" inputmode="decimal" onchange="hitungPerluasan()" readonly d-element="Premium {{ $row + 1 }}">
                                                                        <input type="hidden" name="premi_perluasan[{{ $row + 1 }}][{{ $objek_perluasan->id }}]" class="premi-perluasan">
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr class="intro-x border-t" id="total-premi">
                                                    <td class="text-right" colspan="4">
                                                        <b>TOTAL </b>
                                                        <input type="hidden" id="total-perluasan">
                                                    </td>
                                                    <td class="text-right">
                                                        <input type="text" class="border-theme-9 form-control form-control-sm allow-decimal currency masked w-40" placeholder="Total Sum Insured" id="kodetrans_value[1]" style="text-align: right; width: 100%; font-weight:bold;" inputmode="decimal" readonly>
                                                        <input type="hidden" name="kodetrans_value[1]">
                                                    </td>
                                                    <td class="text-right">
                                                        <input type="text" class="form-control form-control-sm allow-decimal currency masked w-40" placeholder="Premium" id="kodetrans_value[2]" style="text-align: right; width: 100%; font-weight:bold;" inputmode="decimal" readonly>
                                                        <input type="hidden" name="kodetrans_value[2]">
                                                    </td>
                                                </tr>
                                                <tr class="intro-x">
                                                    <td class="text-right" width="20%" colspan="5">
                                                        <b>BIAYA POLIS</b>
                                                    </td>
                                                    <td class="text-right" width="20%">
                                                        <input type="text" id="kodetrans_value[10]" class="biaya form-control form-control-sm allow-decimal currency masked w-40" placeholder="Biaya Polis" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Biaya Polis" value="@if (!empty($pricing[10]->value)){{ $pricing[10]->value }}@endif">
                                                        <input type="hidden" name="kodetrans_value[10]" class="biaya" value="@if (!empty($pricing[10]->value)){{ $pricing[10]->value }}@endif">
                                                    </td>
                                                </tr>
                                                <tr class="intro-x">
                                                    <td class="text-right" width="20%" colspan="5">
                                                        <b>BIAYA MATERAI</b>
                                                    </td>
                                                    <td class="text-right" width="20%">
                                                        <input type="text" id="kodetrans_value[11]" class="biaya form-control form-control-sm allow-decimal currency masked w-40" placeholder="Biaya Materai" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Biaya Materai" value="@if (!empty($pricing[11]->value)){{ $pricing[11]->value }}@endif">
                                                        <input type="hidden" name="kodetrans_value[11]" class="biaya" value="@if (!empty($pricing[11]->value)){{ $pricing[11]->value }}@endif">
                                                    </td>
                                                </tr>
                                                <tr class="intro-x">
                                                    <td class="text-right" width="20%" colspan="5">
                                                        <b>BIAYA LAIN</b>
                                                    </td>
                                                    <td class="text-right" width="20%">
                                                        <input type="text" id="kodetrans_value[16]" class="biaya form-control form-control-sm allow-decimal currency masked w-40" placeholder="Biaya Lain-Lain" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Biaya Lain" value="@if (!empty($pricing[16]->value)){{ $pricing[16]->value }}@endif">
                                                        <input type="hidden" name="kodetrans_value[16]" class="biaya" value="@if (!empty($pricing[16]->value)){{ $pricing[16]->value }}@endif">
                                                    </td>
                                                </tr>
                                                <tr class="intro-x">
                                                    <td class="text-right border-t" width="20%" colspan="5">
                                                        <b>GROSS PREMIUM</b>
                                                    </td>
                                                    <td class="text-right border-t" width="20%">
                                                        <input type="text" id="kodetrans_value[18]" class="form-control form-control-sm allow-decimal currency masked w-40" placeholder="Gross Premium" style="text-align: right; width: 100%; font-weight:bold;" inputmode="decimal" readonly d-element="Gross Premium" value="@if (!empty($pricing[18]->value)){{ $pricing[18]->value }}@endif">
                                                        <input type="hidden" name="kodetrans_value[18]" value="@if (!empty($pricing[18]->value)){{ $pricing[18]->value }}@endif">
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="intro-y col-span-12 mb-5">
                    <div class="intro-y box">
                        <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto">
                                Data Biaya
                            </h2>
                        </div>
                        <div class="p-5">
                            <div class="preview">
                                <div class="overflow-x-auto">
                                    <form class="formnya">
                                        <div class="grid grid-cols-12 p-3">
                                            @foreach ($hitung as $row)
                                            <div class="mb-2 mr-2 col-span-6 lg:col-span-3">
                                                <label for="kodetrans_value[{{ $row->kodetrans_id }}]" class="form-label">{{ $row->kodetrans_nama }}</label>
                                                <input id="kodetrans_value[{{ $row->kodetrans_id }}]" d-input="{{ $row->kodetrans_input }}" {!! $row->kodetrans_attribute !!}
                                                    onChange="hitungTOTAL()" type="text" class="@if(strpos($row->kodetrans_nama, '%') !== false) decimal @else currency @endif allow-decimal masked form-control"
                                                    placeholder="{{ $row->kodetrans_nama }}" aria-describedby="{{ $row->kodetrans_nama }}"
                                                    value="@if(!empty($pricing[$row->kodetrans_id]->value)){{ $pricing[$row->kodetrans_id]->value }}@endif">
                                                <input type="hidden" name="kodetrans_value[{{ $row->kodetrans_id }}]" value="@if(!empty($pricing[$row->kodetrans_id]->value)){{ $pricing[$row->kodetrans_id]->value }}@endif">
                                            </div>
                                            @endforeach
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @if (!empty($data->transid))
            <div id="layout-log-aktifitas" class="tab-pane" role="tabpanel" aria-labelledby="layout-log-aktifitas-tab">
                <div class="intro-y col-span-12 lg:col-span-6">
                    <div class="intro-y box">
                        <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto">
                                Catatan
                            </h2>
                        </div>
                        <div id="horizontal-form" class="p-5">
                            <form class="formnya">
                                <div class="preview">
                                    <div class="form-inline mt-2">
                                        <textarea id="catatan" name="catatan" class="form-control">@if (!empty($data->catatan)){{ $data->catatan }}@endif</textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="intro-y box mt-2">
                        <div class="p-5" id="responsive-table">
                            <div class="preview">
                                <div class="overflow-x-auto">
                                    <table class="table" id="tb-aktifitas" style="width: 100%">
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
            </div>
            <div id="layout-dokumen" class="tab-pane" role="tabpanel" aria-labelledby="layout-dokumen-tab">
                <div class="intro-y col-span-12 lg:col-span-6">
                    <div class="intro-y box">
                        <div id="multiple-file-upload" class="p-5">
                            @if(!empty($method))
                            <div class="preview upload-dokumen">
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
                            @endif
                        </div>
                        <script>
                            $(document).ready(function(){
                                @if(!empty($method))
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
                                        console.log('err frm-document',response);
                                        $(file.previewElement).addClass("dz-error").find('.dz-error-message').text(response.errors.file);
                                    }
                                });
                                @endif
                            });
                        </script>
                        <div class="p-5" id="responsive-table">
                            <div class="preview">
                                <div class="overflow-x-auto">
                                    <table class="table" id="tb-dokumen" style="width: 100%">
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
            </div>
        @endif
        <div class="w-100 text-right mt-2 form-inline">
            @if (empty($data->transid))
                <button id="btn-simpan" class="btn btn-primary mr-1"><i class="fa fa-save mr-2"></i>Simpan</button>
            @elseif ($data->id_status == 0)
                @role('adm|maker|checker')
                    <button id="btn-ajukan" class="btn btn-success mr-1"><i class="fa fa-check mr-2"></i>Ajukan</button>
                    <button id="btn-hapus" class="btn btn-danger mr-1"><i class="fa fa-trash mr-2"></i>Hapus</button>
                @endrole
            @endif
            @if (!empty($method))
                @if ($data->id_status == 1)
                    @role('adm|approver')
                        <button class="btn btn-success mr-1 btn-approve"><i class="fa fa-check mr-2"></i>Setujui</button>
                    @endrole
                @endif
                @role('adm|broker')
                    @if ($data->id_status == 2)
                        <button class="btn btn-success mr-1 btn-approve"><i class="fa fa-check mr-2"></i>Verifikasi</button>
                    @elseif ($data->id_status == 3)
                        <button class="btn btn-success mr-1 btn-approve"><i class="fa fa-check mr-2"></i>Penawaran Ke Tertanggung</button>
                    @endif
                @endrole
                @if ($data->id_status == 4)
                    @role('adm|maker|checker')
                        <button class="btn btn-success mr-1 btn-approve"><i class="fa fa-check mr-2"></i>Setujui</button>
                    @endrole
                @endif
                @role('adm|broker|approver')
                    <button class="btn btn-warning mr-1 btn-rollback"><i class="fa fa-redo-alt mr-2"></i>Kembalikan</button>
                @endrole
            @endif
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        function resetOkupasi(id) {
            $("[name='okupasi\\["+id+"\\]']").val(null).trigger('change');
            $("[name='okupasi\\["+id+"\\]']").select2({
                language: "id",
                allowClear: true,
                // minimumInputLength: 3,
                placeholder: "Pilih Okupasi",
                ajax: {
                    dataType: "json",
                    url: "{{ url('api/selectokupasi/wholesales') }}",
                    headers: {
                        'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                    },
                    data: function(params) {
                        return {
                            search: params.term,
                            id_instype: $("#type_insurance").val(),
                            id_kelas: $("[name='id_kelas\\["+id+"\\]']").val(),
                            wilayah: $("#id_kodepos\\["+id+"\\] :selected").attr("wilayah"),
                        };
                    },
                    processResults: function(data, page) {
                        // console.log('okupasi',data);
                        return {
                            results: data,
                        };
                    },
                }
            });
        }

        function initKodepos(id) {
            $("#id_kodepos\\["+id+"\\]").select2({
                language: "id",
                minimumInputLength: 3,
                placeholder: "Masukkan Kode Pos / Kelurahan / Kecamatan",
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
                        // console.log('kodepos',data);
                        return {
                            results: data,
                        };
                    },
                },
            });
        }
        
        function hitungAsuransi() {
            var SHARE = 0;
            
            $('#body-asuransi tr').each(function(){
                var idRow = $(this).attr('id');
                // console.log('idRow',idRow);
                SHARE += isNaN(parseFloat($('#share\\['+idRow+'\\]').val())) ? 0 : parseFloat($('#share\\['+idRow+'\\]').val());
            });

            $('#total_share').val(SHARE).keyup();
            if (SHARE > 100) {
                $('#total_share').parent('td').addClass('has-error');
                $('#total_share').next().show();
            } else {
                $('#total_share').parent('td').removeClass('has-error');
                $('#total_share').next().hide();
            }
            // console.log('SHARE',SHARE);
        }

        function hitungTOTAL() {
            initTSI();
            var RATE        = 0;
            var TSI         = 0;
            var PREMI       = 0;
            var GROSS       = 0;
            var PRORATA     = $('#PRORATA').val();
            var PERLUASAN   = isNaN(parseFloat($('#total-perluasan').val())) ? 0 : parseFloat($('#total-perluasan').val());
            var POLIS       = isNaN(parseFloat($('[name="kodetrans_value[10]"]').val())) ? 0 : parseFloat($('[name="kodetrans_value[10]"]').val());
            var MATERAI     = isNaN(parseFloat($('[name="kodetrans_value[11]"]').val())) ? 0 : parseFloat($('[name="kodetrans_value[11]"]').val());
            var LAIN        = isNaN(parseFloat($('[name="kodetrans_value[16]"]').val())) ? 0 : parseFloat($('[name="kodetrans_value[16]"]').val());

            var jmlObjek = 0;
            
            $('#body-objek tr').each(function(){
                var idRow = $(this).attr('id');
                RATE += parseFloat($("[name='rate_okupasi\\["+idRow+"\\]']").val());
                TSI += parseFloat($("[name='kodetrans_value\\[1\\]\\["+idRow+"\\]']").val());
                PREMI += parseFloat($("[name='kodetrans_value\\[2\\]\\["+idRow+"\\]']").val() * PRORATA);
                jmlObjek++;
            });

            PREMI += PERLUASAN;

            var _RATE = RATE;

            if (isNaN(TSI)) {
                console.log('TSI', TSI);
            } else {
                $('#kodetrans_value\\[1\\]').val(TSI).keyup();
            }

            if (isNaN(PREMI)) {
                console.log('PREMI', PREMI);
            } else {
                $('#kodetrans_value\\[2\\]').val(PREMI).keyup();
            }

            GROSS = PREMI + POLIS + MATERAI + LAIN;
            if (isNaN(GROSS)) {
                console.log('GROSS', GROSS);
            } else {
                $('#kodetrans_value\\[18\\]').val(GROSS).keyup();
            }

            
            @foreach ($value as $row)
                var {!! $row->kodetrans_input !!} = (isNaN(parseFloat($('[name="kodetrans_value[{!! $row->kodetrans_id !!}]"]').val()))) ? 0 : parseFloat($('[name="kodetrans_value[{!! $row->kodetrans_id !!}]"]').val());
            @endforeach
            @foreach ($formula as $row)
                @if ($row->kodetrans_input !== "PREMI")
                    var {!! $row->kodetrans_input !!} = {!! $row->kodetrans_formula !!};
                @endif
            @endforeach

            @foreach ($formula as $row)
                $('[d-input="{{ $row->kodetrans_input }}"]').val({{ $row->kodetrans_input }}).keyup();
            @endforeach

            hitungInstallment();
        }

        function hitungTSI() {
            $('#body-objek tr').each(function(){
                var idRow = $(this).attr('id');
                var TSI = 0;
                var RATE = parseFloat($('[name="rate_okupasi\\[' + idRow + '\\]"]').val());
                // console.log('#col_sumins\\['+idRow+'\\] .sumins');
                $('#col_sumins\\['+idRow+'\\] .sumins').each(function(){
                    var idSumInsured = $(this).attr('id');
                    // console.log($("[name='sumins_value\\["+idRow+"\\]\\["+idSumInsured+"\\]']").val());
                    TSI += isNaN(parseFloat($("[name='sumins_value\\["+idRow+"\\]\\["+idSumInsured+"\\]']").val())) ? 0 : parseFloat($("[name='sumins_value\\["+idRow+"\\]\\["+idSumInsured+"\\]']").val());
                });
                var PREMI = TSI * RATE / 1000;
                
                $('#kodetrans_value\\[1\\]\\['+idRow+'\\]').val(TSI).keyup();
                $('#kodetrans_value\\[2\\]\\['+idRow+'\\]').val(PREMI).keyup();
            });
            hitungTOTAL();
        }

        function hitungPerluasan() {
            var total_perluasan = isNaN(parseFloat($("#total_perluasan").val())) ? 0 : parseFloat($("#total_perluasan").val());

            $('.rate-perluasan').each(function(){
                var field_perluasan = $(this).attr('id').replace(/\[/g,"\\[").replace(/\]/g,"\\]").replace("rate_","");

                var rate_perluasan  = isNaN(parseFloat($("[name='rate_"+field_perluasan+"']").val())) ? 0 : parseFloat($("[name='rate_"+field_perluasan+"']").val());
                var value_perluasan = isNaN(parseFloat($("[name='value_"+field_perluasan+"']").val())) ? 0 : parseFloat($("[name='value_"+field_perluasan+"']").val());
                var premi_perluasan = rate_perluasan * value_perluasan / 1000;

                $('#premi_'+field_perluasan).val(premi_perluasan).keyup();
                
                total_perluasan += premi_perluasan;
                
                $('#total-perluasan').val(total_perluasan);
                // console.log('rate_perluasan',rate_perluasan);
                // console.log('value_perluasan',value_perluasan);
                // console.log('total_perluasan',total_perluasan);
                // console.log('#premi_'+field_perluasan);
            });
            hitungTOTAL();
        }

        function refreshFunction() {
            initSelect();
            initAsuransi();
            initCurrency();
            initDecimal();
            initTSI();
        }

        function initAsuransi() {
            $(".asuransi").select2({
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
        }

        function initSelect() {
            $('.selek2').select2();
        }

        @if(!empty($data->transid))
            function printPlacing(id) {
                var asuransi = $('[name="asuransi['+id+']"]').val();
                if (asuransi !== null) {
                    window.open("{{ url('cetak_klausula',$data->transid) }}/placing/"+asuransi, "placing_"+asuransi);
                } else {
                    Swal.fire(
                        'Gagal!',
                        'Harap memilih asuransi terlebih dahulu sebelum cetak placing',
                        'error'
                    );
                }
            }

            function removeAsuransiRow(id) {
                var hitung = $('#body-asuransi tr').length;
                if (hitung > 1) {
                    // btn.parent().parent().remove();
                    $('#body-asuransi tr#'+id).remove();
                }
                hitungAsuransi();
            }

            function addAsuransiRow() {
                var hitung = $('#body-asuransi tr').length;
                var id = hitung;
                while ($('#body-asuransi tr#'+id).length > 0) {
                    id++;
                }
                var html = `<tr id="`+id+`">
                                <td class="whitespace-nowrap">
                                    <select class="asuransi" name="asuransi[`+id+`]" required style="width:100%" d-element="Nama Asuransi `+id+`"></select>
                                </td>
                                <td class="whitespace-nowrap">
                                    <input id="share[`+id+`]" type="text" class="share-asuransi decimal allow-decimal masked form-control form-control-sm" placeholder="Share (%)" aria-describedby="Share (%)" style="text-align: right;" inputmode="decimal" onchange="hitungAsuransi()" d-element="Share Asuransi `+id+`">
                                    <input type="hidden" name="share[`+id+`]">
                                </td>
                                <td class="whitespace-nowrap">
                                    @if(!empty($method))
                                    <div class="flex justify-center items-center">
                                        <button type="button" class="flex items-center text-theme-1 btn-hapus mr-2" onclick="printPlacing(`+id+`)">
                                            <i class="w-4 h-4 mr-1 fa fa-print"></i>
                                            Placing
                                        </button>
                                        <button type="button" class="flex items-center text-theme-6 btn-hapus" onclick="removeAsuransiRow(`+id+`)">
                                            <i class="w-4 h-4 mr-1 fa fa-trash"></i>
                                            Hapus 
                                        </button>
                                    </div>
                                    @endif
                                </td>
                            </tr>`;
                $('#body-asuransi').append(html);
                refreshFunction();
                hitungAsuransi();
            }
        @endif

        function removeObjekRow(id) {
            var hitung = $('#body-objek tr').length;
            if (hitung > 1) {
                // btn.parent().parent().remove();
                $('#body-objek tr#'+id).remove();
                removeOkupasiRow(id);
            }
        }

        function addObjekRow() {
            var hitung = $('#body-objek tr').length;
            var id = hitung;
            while ($('#body-objek tr#'+id).length > 0) {
                id++;
            }
            if (id == 0) {
                id++
            }

            var html = `<tr class="intro-x" valign="top" id="`+id+`">
                            <td class="text-center">`+id+`</td>
                            <td class="text-center">
                                <textarea name="objek[`+id+`]" class="form-control form-control-sm" required placeholder="DESKRIPSI OBJEK" d-element="Deskripsi Objek `+id+`"></textarea>
                                <input type="hidden" name="id_objek[`+id+`]">
                            </td>
                            <td class="text-center">
                                <textarea name="alamat[`+id+`]" class="form-control form-control-sm" required placeholder="ALAMAT LENGKAP LOKASI OBJEK" d-element="Alamat Objek `+id+`"></textarea>
                                <select class="kodepos" id="id_kodepos[`+id+`]" name="id_kodepos[`+id+`]" style="width:100%" required d-element="Kodepos `+id+`"></select>
                            </td>
                            <td>
                                <div class="input-group-text">
                                    <select class="selek2" name="id_jaminan[`+id+`]" required style="width:100%" d-element="Jenis Jaminan `+id+`">
                                        @foreach ($jaminan as $val)
                                            <option value="{{ $val->msid }}" @if (!empty($objek->id_jaminan) && $val->msid == $objek->id_jaminan) selected @endif>{{ $val->msdesc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <textarea name="no_jaminan[`+id+`]" class="form-control form-control-sm mt-1" required placeholder="Deskripsi Jaminan / Nomor" d-element="Deskripsi Jaminan `+id+`"></textarea>
                            </td>
                            <td id="col_sumins[`+id+`]">
                                <div id="1" class="sumins">
                                    <div class="input-group mb-1">
                                        @if (((!empty($method) && !empty($data->transid) && $data->id_status == 0)) || (empty($method) && empty($data->transid)))
                                        <button type="button" class="btn btn-primary form-control btn-tambah" onclick="addSumInsured(`+id+`)" style="width:50px"><i class="fa fa-plus"></i></button>
                                        @endif
                                        <div class="input-group-text">
                                            <select class="selek2" name="sumins_type[`+id+`][1]" required d-element="Tipe Sum Insured `+id+`">
                                                <option value="3">Bangunan</option>
                                                <option value="4">Kendaraan</option>
                                                <option value="5">Mesin</option>
                                                <option value="6">Stok</option>
                                                <option value="7">Inventaris</option>
                                                <option value="8">Perabotan</option>
                                                <option value="9">Lain-lain</option>
                                            </select>
                                        </div>
                                        <input type="text" class="form-control allow-decimal currency masked w-40" placeholder="Uang Pertanggungan" id="sumins_value[`+id+`][1]" style="text-align: right;" inputmode="decimal" onchange="hitungTSI()" required d-element="Uang Pertanggungan `+id+`">
                                        @if (((!empty($method) && !empty($data->transid) && $data->id_status == 0)) || (empty($method) && empty($data->transid)))
                                        <button type="button" class="btn btn-danger form-control ml-2 btn-hapus" onclick="removeSumInsured(`+id+`,'#1')"><i class="fa fa-trash"></i></button>
                                        @endif
                                    </div>
                                    <input type="hidden" name="sumins_value[`+id+`][1]" value="">
                                </div>
                            </td>
                            <td class="text-center">
                                <input type="text" class="form-control form-control-sm allow-decimal currency masked w-100" placeholder="Nilai Pasar OBJEK / Taksasi CI / Retaksasi CI" id="agunan_kjpp[`+id+`]" style="text-align: right;" inputmode="decimal" onchange="hitungTSI()" required d-element="Nilai Pasar `+id+`">
                                <input type="hidden" name="agunan_kjpp[`+id+`]">
                            </td>
                            <td class="table-report__action">
                                @if((!empty($method) && !empty($data->transid) && $data->id_status == 0) || (empty($method) && empty($data->transid)))
                                <div class="flex justify-center items-center">
                                    <button type="button" class="flex items-center text-theme-6 btn-hapus" onclick="removeObjekRow(`+id+`)">
                                        <i class="w-4 h-4 mr-1 fa fa-trash"></i>
                                        Hapus 
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>`;

            $('#body-objek').append(html);
            addOkupasiRow();
            refreshFunction();
            cekTipeAsuransi();
            initKodepos(id);
        }

        function hitungInstallment() {
            var total_premi     = isNaN(parseFloat($('[name="kodetrans_value\\[2\\]"]').val())) ? 0 : parseFloat($('[name="kodetrans_value\\[2\\]"]').val());
            var gross_premi     = isNaN(parseFloat($('[name="kodetrans_value\\[18\\]"]').val())) ? 0 : parseFloat($('[name="kodetrans_value\\[18\\]"]').val());
            var biaya_polis     = isNaN(parseFloat($('[name="kodetrans_value\\[10\\]"]').val())) ? 0 : parseFloat($('[name="kodetrans_value\\[10\\]"]').val());
            var biaya_materai   = isNaN(parseFloat($('[name="kodetrans_value\\[11\\]"]').val())) ? 0 : parseFloat($('[name="kodetrans_value\\[11\\]"]').val());
            var biaya_lain      = isNaN(parseFloat($('[name="kodetrans_value\\[16\\]"]').val())) ? 0 : parseFloat($('[name="kodetrans_value\\[16\\]"]').val());
            var biaya           = biaya_polis + biaya_materai + biaya_lain;
            var banyak_installment = $('#body-installment tr').length;
            var grand_total     = 0;
            if (banyak_installment > 1) {
                $('.btn-installment').html('<i class="fa fa-credit-card mr-2"></i>Installment ('+banyak_installment+'x)');
            } else {
                $('.btn-installment').html('<i class="fa fa-credit-card mr-2"></i>Installment');
            }

            $('#body-installment tr').each(function(){
                var id = $(this).attr('id');
                if (id == 1) {
                    $('#by_polis_installment').val(biaya_polis).keyup();
                    $('#by_materai_installment').val(biaya_materai).keyup();
                    $('#by_lain_installment').val(biaya_lain).keyup();
                    var premi_installment = total_premi / banyak_installment;
                    var total_installment = (total_premi / banyak_installment) + biaya;
                } else {
                    var premi_installment = total_premi / banyak_installment;
                    var total_installment = total_premi / banyak_installment;
                }

                var premi_genap = Math.round(premi_installment/1000)*1000;
                var total_genap = Math.round(total_installment/1000)*1000;

                $('#premium_installment\\['+id+'\\]').val(premi_genap).keyup();
                $('#total_installment\\['+id+'\\]').val(total_genap).keyup();
                // console.log('[name="total_installment\\['+id+'\\]"]',$('[name="total_installment\\['+id+'\\]"]').val());

                grand_total += parseFloat($('[name="total_installment\\['+id+'\\]"]').val());
            });

            if (grand_total !== gross_premi) {
                var selisih = grand_total - gross_premi;
                var premi_selisih = parseFloat($('[name="premium_installment\\[1\\]"]').val()) - parseFloat(selisih);
                var total_selisih = parseFloat($('[name="total_installment\\[1\\]"]').val()) - parseFloat(selisih);
                $('#premium_installment\\[1\\]').val(premi_selisih).keyup();
                $('#total_installment\\[1\\]').val(total_selisih).keyup();
                grand_total -= selisih;
            }

            $('#total_installment').val(grand_total).keyup();
        }

        function removeInstallment(id) {
            var hitung = $('#body-installment tr').length;
            if (hitung > 1) {
                // btn.parent().parent().remove();
                $('#body-installment tr#'+id).remove();
            }
            hitungInstallment();
        }

        function cancelInstallment() {
            $('#body-installment tr').each(function(){
                var id = $(this).attr('id');
                // console.log('id',id);
                // console.log(id !== "1");
                if (id !== "1" && $('[name="id_installment\\['+id+'\\]"]').val().length == 0) {
                    removeInstallment(id);
                }
            });
        }

        function addInstallment() {
            var id = $('#body-installment tr').length + 1;

            // if (id == 0) {
            //     id++;
            // }
            while ($('#body-installment tr#'+id).length > 0) {
                id++;
            }
            var dueDate = moment($('[name="tgl_tagihan\\[' + (id - 1) + '\\]"]').val());
                dueDate = moment(dueDate).add(1, 'M');
            var html = `<tr id="` + id + `">
                            <td>` + id + `</td>
                            <td class="whitespace-nowrap">
                                <input type="hidden" name="id_installment[` + id + `]">
                                <input type="date" class="form-control tgl-tagihan" name="tgl_tagihan[` + id + `]" value="` + dueDate.format('YYYY-MM-DD') + `">    
                            </td>
                            <td class="whitespace-nowrap" colspan="3"></td>
                            <td class="whitespace-nowrap">
                                <input type="text" id="premium_installment[` + id + `]" class="form-control form-control-sm allow-decimal currency masked w-40" placeholder="Premium" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Premium" readonly>
                                <input type="hidden" name="premium_installment[` + id + `]">
                            </td>
                            <td class="whitespace-nowrap">
                                <input type="text" id="total_installment[` + id + `]" class="form-control form-control-sm allow-decimal currency masked w-40" placeholder="Total Gross" style="text-align: right; width: 100%" onchange="hitungTOTAL()" inputmode="decimal" @role('maker|checker') readonly @endrole d-element="Total Installment" readonly>
                                <input type="hidden" name="total_installment[` + id + `]">
                            </td>
                            <td class="whitespace-nowrap">
                                @if(!empty($method))
                                <div class="flex justify-center items-center">
                                    <button type="button" class="flex items-center text-theme-6 btn-hapus" onclick="removeInstallment(` + id + `)">
                                        <i class="w-4 h-4 mr-1 fa fa-trash"></i>
                                        Hapus 
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>`;
            $('#body-installment').append(html);
            // refreshFunction();
            initTSI();
            hitungInstallment();
            initCurrency();
            initDecimal();
        }

        function removeOkupasiRow(id) {
            var hitung = $('#body-okupasi tr').length;
            if (hitung > 1) {
                // btn.parent().parent().remove();
                $('#body-okupasi tr#'+id).remove();
            }
        }

        function addOkupasiRow() {
            var id = $('#body-okupasi tr').length;
            while ($('#body-okupasi tr#'+id).length > 0) {
                id++;
            }

            var html = `<tr class="intro-x" id="`+id+`">
                            <td class="w-10 text-center" width="5%">
                                `+id+`
                            </td>
                            <td class="text-center" width="10%">
                                <select class="kelas" id="id_kelas[`+id+`]" name="id_kelas[`+id+`]" style="width:100%" d-element="Jenis Pertanggungan `+id+`"></select>
                            </td>
                            <td width="35%">
                                <select class="okupasi" id="okupasi[`+id+`]" name="okupasi[`+id+`]" required style="width: 100%" d-element="Okupasi 1"></select>
                            </td>
                            <td class="text-center" width="20%">
                                <input id="rate_okupasi[`+id+`]" type="text" name="rate[`+id+`]" placeholder="Rate (‰)" class="form-control form-control-sm allow-decimal decimal masked" style="text-align: right; width: 100%" required inputmode="decimal" onchange="hitungTSI()" d-element="Rate Okupasi `+id+`">
                                <input type="hidden" name="rate_okupasi[`+id+`]">
                            </td>
                            <td class="text-right" width="20%">
                                <input type="text" class="form-control form-control-sm allow-decimal currency masked" placeholder="Total Sum Insured" id="kodetrans_value[1][`+id+`]" style="text-align: right; width: 100%" inputmode="decimal" readonly d-element="TSI `+id+`">
                                <input type="hidden" name="kodetrans_value[1][`+id+`]">
                            </td>
                            <td class="text-right" width="20%">
                                <input type="text" class="form-control form-control-sm allow-decimal currency masked" placeholder="Premium" id="kodetrans_value[2][`+id+`]" style="text-align: right; width: 100%" inputmode="decimal" readonly d-element="Premium `+id+`">
                                <input type="hidden" name="kodetrans_value[2][`+id+`]">
                            </td>
                        </tr>`;
            $('#body-okupasi').append(html);
            initCurrency();
            initDecimal();
        }

        function removeSumInsured(idRow, target) {
            var hitung = $('#col_sumins\\['+idRow+'\\] .sumins').length;
            if (hitung > 1) {
                // btn.parent().parent().remove();
                $('#col_sumins\\['+idRow+'\\] '+target).remove();
            }
            hitungTSI();
        }

        function addSumInsured(idRow) {
            var id = $('#col_sumins\\['+idRow+'\\] .sumins').length;
            while ($('#col_sumins\\['+idRow+'\\] #'+id).length > 0) {
                id++;
            }
            var html = `<div id="`+id+`" class="sumins">
                            <div class="input-group mb-1">
                                @if (!empty($method) || (empty($method) && empty($data->transid)))
                                <button type="button" class="btn btn-primary form-control btn-tambah" onclick="addSumInsured(`+idRow+`)" style="width:50px"><i class="fa fa-plus"></i></button>
                                @endif
                                <div class="input-group-text">
                                    <select class="selek2" name="sumins_type[`+idRow+`][`+id+`]" style="width:100%">
                                        <option value="3">Bangunan</option>
                                        <option value="4">Kendaraan</option>
                                        <option value="5">Mesin</option>
                                        <option value="6">Stok</option>
                                        <option value="7">Inventaris</option>
                                        <option value="8">Perabotan</option>
                                        <option value="9">Lain-lain</option>
                                    </select>
                                </div>
                                <input type="text" class="form-control allow-decimal currency masked w-40" placeholder="Uang Pertanggungan" id="sumins_value[`+idRow+`][`+id+`]" style="text-align: right;" inputmode="decimal" onchange="hitungTSI()" required>
                                @if (!empty($method) || (empty($method) && empty($data->transid)))
                                <button type="button" class="btn btn-danger form-control ml-2 btn-hapus" onclick="removeSumInsured(`+idRow+`, '#`+id+`')" style="width:50px"><i class="fa fa-trash"></i></button>
                                @endif
                            </div>
                            <input type="hidden" name="sumins_value[`+idRow+`][`+id+`]" value="">
                        </div>`;
            $('#col_sumins\\['+idRow+'\\]').append(html);
            refreshFunction();
            hitungTSI();
        }

        function addPerluasanRow(select, data) {
            console.log('data', data);
            var id = select.attr('d-id');
            var html = `<tr class="perluasan-row-`+id+`-`+data.id+`">
                            <td colspan="2"></td>
                            <td>
                                <b>`+data.text+`</b> - `+data.field+`
                            </td>
                            <td class="text-center">
                                <input id="rate_perluasan[`+id+`][`+data.id+`]" type="text" placeholder="Rate Perluasan (‰)" class="rate-perluasan rate form-control form-control-sm allow-decimal decimal masked" style="text-align: right; width: 100%" required inputmode="decimal" onchange="hitungPerluasan()" d-element="Rate Perluasan `+id+`" value="`+data.rate+`">
                                <input type="hidden" name="rate_perluasan[`+id+`][`+data.id+`]" class="rate" value="`+data.rate+`">
                            </td>
                            <td class="text-right">
                                <div class="input-group">
                                    <div id="input-group-email" class="form-control form-control-sm input-group-text" style="width:35px;">x</div>
                                    <input type="text" class="rate form-control form-control-sm allow-decimal currency masked" placeholder="Nilai Pengkali Perluasan" id="value_perluasan[`+id+`][`+data.id+`]" style="text-align: right; width: 100%" inputmode="decimal" onchange="hitungPerluasan()" d-element="Value Perluasan `+id+`">
                                </div>
                                <input type="hidden" name="value_perluasan[`+id+`][`+data.id+`]">
                            </td>
                            <td class="text-right">
                                <input type="text" class="form-control form-control-sm allow-decimal currency masked" placeholder="Premium" id="premi_perluasan[`+id+`][`+data.id+`]" style="text-align: right; width: 100%" inputmode="decimal" onchange="hitungPerluasan()" readonly d-element="Premium `+id+`">
                                <input type="hidden" name="premi_perluasan[`+id+`][`+data.id+`]" class="premi-perluasan">
                            </td>
                        </tr>`;
            
            $('.row-okupasi-'+id).after(html);
            initTSI();
            initDecimal();
            initCurrency();
        }

        function removePerluasanRow(select, data) {
            var id = select.attr('d-id');
            $('.perluasan-row-'+id+'-'+data.id).remove();
        }

        function cekProrata(prorata) {
            if (prorata % 1 == 0) {
                $('#head-premi').text('TOTAL PREMIUM');
            } else {
                $('#head-premi').text('TOTAL PREMIUM (PRORATA)');
            }
        }

        function cekTipeAsuransi() {
            var tipe = $('#type_insurance').val();
            // console.log('tipe',tipe);
            var id = "";
            
            $('.perluasan').each(function(){
                var preselected = [];
                var id = $(this).attr('d-id');
                // console.log('id',id);
                $('#perluasan\\['+id+'\\]').val(null).trigger('change');
                $('#perluasan\\['+id+'\\]').select2({
                    language: "id",
                    placeholder: "Pilih Beberapa",
                    multiple: true,
                    ajax: {
                        dataType: "json",
                        url: "{{ url('api/selectperluasan') }}",
                        headers: {
                            'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                        },
                        data: function(params) {
                            return {
                                search: params.term,
                                "instype": $('#type_insurance').val()
                            };
                        },
                        processResults: function(data, page) {
                            $.each(data, function (i, v) { 
                                 if (v.selected == true){
                                     preselected.push(v.id)
                                 }
                            });
                            return {
                                results: data,
                            };
                        },
                    },
                }).on('select2:select', function(e) {
                    var data = e.params.data;
                    addPerluasanRow($(this), data);
                }).on('select2:unselect', function(e) {
                    var data = e.params.data;
                    removePerluasanRow($(this), data);
                    hitungPerluasan();
                });
                setTimeout(() => {
                    $('#perluasan\\['+id+'\\]').val(preselected).change();
                }, 500);
            });
            
            $('.kelas').each(function(){
                id = $(this).attr('d-id');
                $('[name="id_kelas\\['+id+'\\]"]').val(null).trigger('change');
                $('[name="id_kelas\\['+id+'\\]"]').select2({
                    language: "id",
                    placeholder: "Pilih Salah Satu",
                    ajax: {
                        dataType: "json",
                        url: "{{ url('api/selectkelas') }}",
                        headers: {
                            'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                        },
                        data: function(params) {
                            return {
                                search: params.term,
                                "tipe": $('#type_insurance').val()
                            };
                        },
                        processResults: function(data, page) {
                            return {
                                results: data,
                            };
                        },
                    },
                });
                resetOkupasi(id);
            });
        }

        function hapusDokumen(id){
            $.ajax({
                url: "{{ url('api/dokumen') }}",
                method: "POST",
                data: $('#frm-document').serialize() + "&method=delete&id="+id,
                headers: {
                    'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                },
                success: function(d) {
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
                }
            });
        };

        function disableForm() {
            $('.formnya :input').prop('readonly',true);
            $('.formnya select').attr('readonly',true);
            $('.btn-tambah').hide();
            $('.btn-hapus').hide();
            $('.upload-dokumen').hide();
            $('#transid').prop('readonly',false);
            $('#catatan').prop('readonly',false);
            $('#periode-polis').prop('disabled',true);
        }

        $(document).ready(function(){
            @if (empty($data->transid))
                addObjekRow();
            @endif
            @if (!empty($data->transid) && (empty($data_penanggung) || count($data_penanggung) == 0))
                addAsuransiRow();
            @endif
            @if (empty($method) && !empty($data->transid))
                disableForm();
            @elseif (!empty($method) && $data->id_status == 1)
                disableForm();
            @elseif (!empty($method) && $data->id_status == 2)
                disableForm();
                $('.perluasan-value').prop('readonly',false);
                $('.kelas').removeAttr('readonly');
                $('.okupasi').removeAttr('readonly');
                $('.perluasan').removeAttr('readonly');
                $('.rate').prop('readonly',false);
                $('.biaya').prop('readonly',false);
                $('#kodetrans_value\\[12\\]').prop('readonly',false);
                $('#modal-installment .btn-tambah').show();
                $('#modal-installment .btn-hapus').show();
                $('.tgl-tagihan').prop('readonly', false);
                $('#tb-dokumen a.flex').hide();
                $('[name^="id_objek"]').prop('readonly',false);
            @elseif (!empty($method) && $data->id_status == 3)
                disableForm();
                setTimeout(() => {
                    $('#cover_note').prop('readonly',false);
                    $('#policy_no').prop('readonly',false);
                    $('.form-asuransi .share-asuransi ').prop('readonly',false);
                    $('.form-asuransi select').removeAttr('readonly');
                    $('#modal-installment .btn-tambah').show();
                    $('#modal-installment .btn-hapus').show();
                    $('#div-asuransi .btn-tambah').show();
                    $('#div-asuransi .btn-hapus').show();
                    $('.upload-dokumen').show();
                }, 500);
            @endif
            initSelect();
            initTSI();
            initAsuransi();
            cekTipeAsuransi();
            hitungPerluasan();

            $('.kelas').on('select2:select', function(){
                var id = $(this).attr('d-id');
                resetOkupasi(id);
            });

            @if (!empty($data->transid) && $data->id_status >=2)
                let Font = Quill.import('formats/font');
                Font.whitelist = ['inconsolata', 'roboto', 'mirza', 'arial'];
                Quill.register(Font, true);

                var quill = new Quill('#editor', {
                    modules: {
                        toolbar: '#toolbar-container',
                        imageResize: {
                            displaySize: true
                        }
                    },
                    theme: 'snow'
                });
            @endif

            $('#btn-simpan, #btn-ajukan').click(function(e){
                e.preventDefault();
                var i = 1;
                var validasi = true;
                var validator = $('form.formnya').validate();
                var errorText = "";
                if (!$('form.formnya').valid()) {
                    validasi = false;
                }

                $('form.formnya').each(function(){
                    var validator = $(this).validate();
                    if (!$(this).valid()) {
                        validasi = false;
                    }
                    i++;
                    validator.currentElements.each(function(){
                        if (!$(this).valid()) {
                            errorText += "- " + $(this).attr('d-element') + "<br>"
                        }
                    });
                });

                if (validasi) {
                    var datanya = $('.formnya').serializeArray();
                    datanya.push(
                        {name:"_token", value: "{{ csrf_token() }}"},
                        {name:"method", value: "store"},
                        {name:"nama_insured", value: $('#insured option:selected').text()},
                        {name:"nama_cabang", value: $('#cabang option:selected').text()},
                    );
                    $.ajax({
                        type: "POST",
                        url: "{{ url('api/wholesales') }}",
                        headers: {
                            'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                        },
                        data: datanya,
                        dataType: "json",
                        success: function (d) {
                            Swal.fire(
                                'Berhasil!',
                                d.message,
                                'success'
                            ).then(function() {
                                if (d.method == 'create') {
                                    window.location = "{{ url('inquiry') }}?data=pengajuan";
                                } else {
                                    window.top.close();
                                }
                            });
                        },
                        error: function (response) {
                            console.log('err btn-ajukan',response);
                        }
                    });
                } else {
                    Swal.fire({
                        'title' : 'Data Belum Lengkap!',
                        'icon' : 'error',
                        'html' : '<p style="text-align: left;">Periksa kembali isian berikut:<br>' + errorText + '</p>',
                    });
                }
            });

            @if (!empty($data->transid))
            $('#btn-klausula').click(function(){
                $.ajax({
                    type: "POST",
                    url: "{{ url('api/klausula') }}",
                    data: {
                        "_token"    : "{{ csrf_token() }}",
                        "transid"   : "{{ $data->transid }}",
                        "klausula"  : $('.ql-editor').html()
                    },
                    success: function (response) {
                        Swal.fire(
                            'Berhasil!',
                            response.message,
                            'success'
                        ).then(function() {
                            cash('#modal-klausula').modal('hide');
                        });
                    },
                    error: function (response) {
                        console.log('response',response);
                    }
                });
            });

            $('#btn-asuransi').click(function(){
                var datanya = $('.form-asuransi').serializeArray();
                datanya.push(
                    {name:"_token", value: "{{ csrf_token() }}"},
                    {name:"transid", value: "{{ $data->transid }}"},
                );
                $.ajax({
                    type: "POST",
                    url: "{{ url('api/penanggung') }}",
                    data: datanya,
                    success: function (response) {
                        console.log('response',response);
                        // Swal.fire(
                        //     'Berhasil!',
                        //     response.message,
                        //     'success'
                        // ).then(function() {
                        //     window.top.close();
                        // });
                    },
                    error: function (response) {
                        Swal.fire(
                            'Gagal!',
                            response.responseJSON.message,
                            'error'
                        )
                    }
                });
            });
            @endif

            @if (!empty($data) && $data->id_status > 0 && $method == 'approve')
            $('.btn-approve').click(function(){
                var datanya = $('.formnya').serializeArray();
                datanya.push(
                    {name:"_token", value: "{{ csrf_token() }}"},
                    {name:"method", value: "approve"},
                    {name:"klausula", value: $('.ql-editor').html()},
                );
                $.ajax({
                    type: "POST",
                    url: "{{ url('api/wholesales') }}",
                    headers: {
                        'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                    },
                    data: datanya,
                    dataType: "json",
                    success: function (d) {
                        console.log('d',d);
                        Swal.fire(
                            'Berhasil!',
                            d.message,
                            'success'
                        )
                        .then(function() {
                            window.top.close();
                        });
                    },
                    error: function (response) {
                        var pesan = "<div style='text-align:left' class='p-10'>";
                        $.each(response.responseJSON.errors, function (i, v) { 
                            pesan += "- " + v + "<br>";
                        });
                        pesan += "</div>";
                        Swal.fire({
                            title: response.responseJSON.message,
                            icon: 'error',
                            html: pesan,
                        });
                    }
                });
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
                        transid = $('#transid').val();

                    $(this)
                        .attr('disabled',true)
                        .html(loading);

                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        text: "Data akan dikembalikan ke status sebelumnya",
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
                                    if (result.value) {
                                        catatan = result.value;
                                    }
                                    $.ajax({
                                        url: "{{ url('api/wholesales') }}",
                                        method: "POST",
                                        data: {catatan,transid,nama_insured,method,_token},
                                        headers: {
                                            'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                                        },
                                        success: function(d) {
                                            // console.log(d);
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

            @if(!empty($data->polis_start) && !empty($data->polis_end))
                var startPolis = moment("{{ $data->polis_start }}","YYYY-MM-DD");
                var endPolis = moment("{{ $data->polis_end }}","YYYY-MM-DD");
            @else 
                var startPolis = moment();
                var endPolis = moment().add(1, 'year');
            @endif

            function polis(startPolis, endPolis) {
                // console.log(endPolis.format('DD/MM/YYYY'));
                $('#periode-polis').html(startPolis.format('DD/MM/YYYY') + ' - ' + endPolis.format('DD/MM/YYYY'));
                // $('#masa').val(Math.round(moment.duration(endPolis.diff(startPolis)).asDays()));
                $('[name="polis_start"]').val(startPolis.format('YYYY-MM-DD'));
                $('[name="polis_end"]').val(endPolis.format('YYYY-MM-DD'));
                var tglAwal = startPolis;
                var tglAkhir = endPolis;
                var durasi = moment.duration(tglAkhir.diff(tglAwal));
                var prorata_value = prorata(tglAwal, tglAkhir);
                $('#masa').val(Math.floor(durasi.asDays()));
                // console.log(Math.floor(durasi.asYears()));
                $('#PRORATA').val(prorata_value);
                cekProrata(prorata_value);
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
                var prorata_value = prorata($('#periode-polis').data('daterangepicker').startDate, $('#periode-polis').data('daterangepicker').endDate);
                $('#PRORATA').val(prorata_value);
                cekProrata(prorata_value);
            });

            $("#type_insurance").select2({
                language: "id",
                placeholder: "Pilih Tipe Asuransi",
                ajax: {
                    dataType: "json",
                    url: "{{ url('api/selectinstype/wholesales') }}",
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
            }).on('select2:select', function(e) {
                cekTipeAsuransi();
            });

            $('.okupasi').on('select2:select', function(e) {
                var data = e.params.data;
                var field_okupasi = $(this).attr('name').replace("[","\\[").replace("]","\\]");
                $('#rate_' + field_okupasi).val(data.rate).keyup().change();
                hitungTOTAL();
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
            }).on('select2:select', function(e) {
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
            $('#cabang').select2().change(function() {
                $('#alamat_cabang').val($('#cabang option:selected').attr('alamat'));
            });

            $('#cabang').change();
            @if (!empty($data->transid))
                var tableDokumen = $('#tb-dokumen').DataTable({
                    "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
                    "serverSide": true,
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
                            console.log('err tb-dokumen', d.responseText);
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
                            console.log('err tb-aktifitas', d.responseText);
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

                @if ($data->id_status >= 2)
                    hitungTSI();
                @endif
            @endif
        });
    </script>
@endsection

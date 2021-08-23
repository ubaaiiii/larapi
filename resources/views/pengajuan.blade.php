@extends('layouts.master')
@section('title', 'Pengajuan')
@section('breadcrumb', 'Pengajuan')
@section('menu', 'Pengajuan')
@section('content')
    <div class="intro-y flex items-center mt-4">
        <h2 class="text-lg font-medium mr-auto">
            Formulir Pengajuan
            @if ($act !== 'view')
                <button class="btn btn-sm btn-primary">Simpan</button>
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
                        @if ($act == 'view')
                            <div class="form-inline mt-5">
                                <label for="noapp" class="form-label sm:w-20">Nomor Aplikasi</label>
                                <input type="text" id="noapp" class="form-control" required name="noapp"
                                    value="BDS{{ date('my') }}00001" disabled>
                            </div>
                        @endif
                        <div class="form-inline mt-5">
                            <label for="type-insurance" class="ml-3 form-label sm:w-20">Tipe Asuransi</label>
                            <select id="type-insurance" name="type-insurance" required style="width:100%">
                                @foreach ($instype as $val)
                                    <option value="{{ $val->msid }}" selected>{{ $val->msdesc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-inline mt-1 extended-clause" style="display:none">
                            <label class="form-label sm:w-20"></label>
                            <label>Termasuk: RSMDCC, TSFWD, Others</label>
                        </div>
                        {{-- <div class="extended-clause">
                            <div class="form-inline ml-3 mt-5">
                                <label for="extend-clause" class="form-label sm:w-20">Extended Clause</label>
                                <div class="input-group w-full">
                                    <div id="rsmdcc" class="input-group-text @if (Auth::user()->level != 'adm') w-full @endif">
                                        <input id="extend-clause-1" class="form-check-input" type="checkbox"
                                            name="extend-clause">
                                        <label class="form-check-label" for="extend-clause-1">RSMDCC </label>
                                    </div>
                                    @if (Auth::user()->level == 'adm')
                                        <input type="text" class="allow-decimal form-control" placeholder="Rate"
                                            aria-label="Rate" aria-describedby="rsmdcc" style="text-align:right;">
                                    @endif

                                </div>
                            </div>
                            <div class="form-inline ml-3 mt-1">
                                <label for="extend-clause" class="form-label sm:w-20"></label>
                                <div class="input-group w-full">
                                    <div id="tsfwd" class="input-group-text @if (Auth::user()->level != 'adm') w-full @endif">
                                        <input id="extend-clause-2" class="form-check-input" type="checkbox"
                                            name="extend-clause">
                                        <label class="form-check-label" for="extend-clause-2">TSFWD </label>
                                    </div>
                                    @if (Auth::user()->level == 'adm')
                                        <input type="text" class="allow-decimal form-control" placeholder="Rate"
                                            aria-label="Rate" aria-describedby="tsfwd" style="text-align:right;">
                                    @endif
                                </div>
                            </div>
                            <div class="form-inline ml-3 mt-1">
                                <label for="extend-clause" class="form-label sm:w-20"></label>
                                <div class="input-group w-full">
                                    <div id="others" class="input-group-text @if (Auth::user()->level != 'adm') w-full @endif">
                                        <input id="extend-clause-3" class="form-check-input" type="checkbox"
                                            name="extend-clause">
                                        <label class="form-check-label" for="extend-clause-3">Others </label>
                                    </div>
                                    @if (Auth::user()->level == 'adm')
                                        <input type="text" class="allow-decimal form-control" placeholder="Rate"
                                            aria-label="Rate" aria-describedby="others" style="text-align:right;">
                                    @endif
                                </div>
                            </div>
                        </div> --}}
                        <div class="form-inline mt-5">
                            <label for="insured" class="ml form-label sm:w-20">Tertanggung (QQ)</label>
                            <select id="insured" style="width:100%;text-transform: uppercase;" class="select2"
                                name="insured" required>
                            </select>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="nik" class="form-label sm:w-20">NIK / No KTP</label>
                            <input type="text" id="nik" class="form-control" required name="nik">
                        </div>
                        <div class="form-inline mt-5">
                            <label for="insured-address" class="form-label sm:w-20">Alamat Tertanggung</label>
                            <textarea id="insured-address" class="form-control"></textarea>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="nopolis-lama" class="ml-3 form-label sm:w-20">Nopolis Lama</label>
                            <div class="input-group w-full">
                                <input type="text" class="form-control" placeholder="Nomor Polis Lama" name="nopolis-lama"
                                    id="nopolis-lama">
                                <div id="nopolis-lama" class="input-group-text">Jika Renewal.</div>
                            </div>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="range-periode" class="form-label sm:w-20">Periode</label>
                            <input id="range-periode" data-daterange="true"
                                class="datepicker form-control w-full block mx-auto">
                        </div>
                        {{-- @if (Auth::user()->level !== 'ao')
                            <div class="form-inline mt-5">
                                <label for="editor" class="ml-3 form-label sm:w-20">Klausa</label>
                                <div class="w-full">
                                    <div data-simple-toolbar="true" class="editor">
                                        <p>Contoh Klausa</p>
                                    </div>
                                </div>
                            </div>
                        @endif --}}
                        <div class="form-inline mt-5">
                            <label for="okupasi" class="ml-3 form-label sm:w-20">Okupasi</label>
                            <select id="okupasi" style="width:100%" name="okupasi" required>
                            </select>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="lokasi" class="form-label sm:w-20">Lokasi Okupasi</label>
                            <textarea id="lokasi" class="form-control"></textarea>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="kecamatan" class="ml-3 form-label sm:w-20">Kode Pos</label>
                            <select id="kecamatan" style="width:100%" name="kecamatan" required>
                            </select>
                        </div>
                        <div class="sm:ml-20 sm:pl-5 mt-5">

                        </div>
                    </div>
                    <div class="source-code hidden">
                        <button data-target="#copy-horizontal-form" class="copy-code btn py-1 px-2 btn-outline-secondary">
                            <i data-feather="file" class="w-4 h-4 mr-2"></i> Copy example code </button>
                        <div class="overflow-y-auto mt-3 rounded-md">
                            <pre id="copy-horizontal-form"
                                class="source-preview"> <code class="text-xs p-0 rounded-md html pl-5 pt-8 pb-4 -mb-10 -mt-10"> HTMLOpenTagdiv class=&quot;form-inline&quot;HTMLCloseTag HTMLOpenTaglabel for=&quot;horizontal-form-1&quot; class=&quot;form-label sm:w-20&quot;HTMLCloseTagEmailHTMLOpenTag/labelHTMLCloseTag HTMLOpenTaginput id=&quot;horizontal-form-1&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;example@gmail.com&quot;HTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;form-inline mt-5&quot;HTMLCloseTag HTMLOpenTaglabel for=&quot;horizontal-form-2&quot; class=&quot;form-label sm:w-20&quot;HTMLCloseTagPasswordHTMLOpenTag/labelHTMLCloseTag HTMLOpenTaginput id=&quot;horizontal-form-2&quot; type=&quot;password&quot; class=&quot;form-control&quot; placeholder=&quot;secret&quot;HTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;form-check sm:ml-20 sm:pl-5 mt-5&quot;HTMLCloseTag HTMLOpenTaginput id=&quot;horizontal-form-3&quot; class=&quot;form-check-input&quot; type=&quot;checkbox&quot; value=&quot;&quot;HTMLCloseTag HTMLOpenTaglabel class=&quot;form-check-label&quot; for=&quot;horizontal-form-3&quot;HTMLCloseTagRemember meHTMLOpenTag/labelHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;sm:ml-20 sm:pl-5 mt-5&quot;HTMLCloseTag HTMLOpenTagbutton class=&quot;btn btn-primary&quot;HTMLCloseTagLoginHTMLOpenTag/buttonHTMLCloseTag HTMLOpenTag/divHTMLCloseTag </code> </pre>
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
                        <tr>
                            <td>
                                <div class="input-group">
                                    <div id="group-b" class="input-group-text">B</div> <input style="text-align:right;"
                                        type="text" class="form-control allow-decimal tsi currency" placeholder="Bangunan"
                                        aria-label="Bangunan" aria-describedby="group-b">
                                </div>
                            </td>
                            <td><input name="InterestRemarks[0]" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group">
                                    <div id="group-s" class="input-group-text">M</div> <input style="text-align:right;"
                                        type="text" class="form-control allow-decimal tsi currency" placeholder="Mesin"
                                        aria-label="Mesin" aria-describedby="group-s">
                                </div>
                            </td>
                            <td><input name="InterestRemarks[2]" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group">
                                    <div id="group-s" class="input-group-text">K</div> <input style="text-align:right;"
                                        type="text" class="form-control allow-decimal tsi currency"
                                        placeholder="Ketersediaan" aria-label="Ketersediaan" aria-describedby="group-s">
                                </div>
                            </td>
                            <td><input name="InterestRemarks[3]" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group">
                                    <div id="group-pk" class="input-group-text">PK</div> <input style="text-align:right;"
                                        type="text" class="form-control allow-decimal tsi currency"
                                        placeholder="Peralatan Kantor" aria-label="Peralatan Kantor"
                                        aria-describedby="group-pk">
                                </div>
                            </td>
                            <td><input name="InterestRemarks[5]" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group">
                                    <div id="group-ip" class="input-group-text">I/P</div> <input style="text-align:right;"
                                        type="text" class="form-control allow-decimal tsi currency"
                                        placeholder="Isi / Perabotan" aria-label="Isi / Perabotan"
                                        aria-describedby="group-ip">
                                </div>
                            </td>
                            <td><input name="InterestRemarks[6]" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group">
                                    <div id="group-l" class="input-group-text">L</div> <input style="text-align:right;"
                                        type="text" class="form-control allow-decimal tsi currency" placeholder="Lain-Lain"
                                        aria-label="Lain-Lain" aria-describedby="group-l">
                                </div>
                            </td>
                            <td><input name="InterestRemarks[4]" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group">
                                    <div id="group-t" class="input-group-text">Total</div> <input style="text-align:right;"
                                        id="tsi" name="tsi" type="text" class="currency form-control allow-decimal"
                                        placeholder="Total Nilai Pertanggungan" aria-label="Total Nilai Pertanggungan"
                                        aria-describedby="group-t" disabled>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
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
                                <div class="text-lg font-medium">Tarik dokumen kesini atau klik untuk memilih dokumen.</div>
                                <div class="text-gray-600"> Ini hanya demonstrasi. File yang terpilih <span
                                        class="font-medium">tidak</span> benar benar diupload. </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="p-5" id="responsive-table">
                    <div class="preview">
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">#</th>
                                        <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Nama Dokumen</th>
                                        <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Tgl. Upload</th>
                                        <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Tipe Dokumen</th>
                                        <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Ukuran</th>
                                        <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border-b whitespace-nowrap">
                                            <div class="dropdown"> <button
                                                    class="btn btn-primary mr-1 mb-2 dropdown-toggle btn-sm"> <i
                                                        data-feather="align-left" class="w-5 h-5"></i> </button>
                                                <div class="dropdown-menu w-40">
                                                    <div class="dropdown-menu__content box dark:bg-dark-1">
                                                        <div
                                                            class="px-4 py-2 border-b border-gray-200 dark:border-dark-5 font-medium">
                                                            Aksi</div>
                                                        <div class="p-2">
                                                            <a href=""
                                                                class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                                                <i data-feather="printer"
                                                                    class="w-4 h-4 dark:text-gray-300 mr-2"></i>
                                                                Lihat
                                                            </a>
                                                            <a href=""
                                                                class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                                                <i data-feather="external-link"
                                                                    class="w-4 h-4 dark:text-gray-300 mr-2"></i>
                                                                Hapus
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border-b whitespace-nowrap">Angelina</td>
                                        <td class="border-b whitespace-nowrap">Jolie</td>
                                        <td class="border-b whitespace-nowrap">@angelinajolie</td>
                                        <td class="border-b whitespace-nowrap">angelinajolie@gmail.com</td>
                                        <td class="border-b whitespace-nowrap">260 W. Storm Street New York, NY 10025.</td>
                                    </tr>
                                    <tr>
                                        <td class="border-b whitespace-nowrap">
                                            <div class="dropdown"> <button
                                                    class="btn btn-primary mr-1 mb-2 dropdown-toggle btn-sm"> <i
                                                        data-feather="align-left" class="w-5 h-5"></i> </button>
                                                <div class="dropdown-menu w-40">
                                                    <div class="dropdown-menu__content box dark:bg-dark-1">
                                                        <div
                                                            class="px-4 py-2 border-b border-gray-200 dark:border-dark-5 font-medium">
                                                            Aksi</div>
                                                        <div class="p-2">
                                                            <a href=""
                                                                class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                                                <i data-feather="printer"
                                                                    class="w-4 h-4 dark:text-gray-300 mr-2"></i>
                                                                Lihat
                                                            </a>
                                                            <a href=""
                                                                class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                                                <i data-feather="external-link"
                                                                    class="w-4 h-4 dark:text-gray-300 mr-2"></i>
                                                                Hapus
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border-b whitespace-nowrap">Brad</td>
                                        <td class="border-b whitespace-nowrap">Pitt</td>
                                        <td class="border-b whitespace-nowrap">@bradpitt</td>
                                        <td class="border-b whitespace-nowrap">bradpitt@gmail.com</td>
                                        <td class="border-b whitespace-nowrap">47 Division St. Buffalo, NY 14241.</td>
                                    </tr>
                                    <tr>
                                        <td class="border-b whitespace-nowrap">
                                            <div class="dropdown"> <button
                                                    class="btn btn-primary mr-1 mb-2 dropdown-toggle btn-sm"> <i
                                                        data-feather="align-left" class="w-5 h-5"></i> </button>
                                                <div class="dropdown-menu w-40">
                                                    <div class="dropdown-menu__content box dark:bg-dark-1">
                                                        <div
                                                            class="px-4 py-2 border-b border-gray-200 dark:border-dark-5 font-medium">
                                                            Aksi</div>
                                                        <div class="p-2">
                                                            <a href=""
                                                                class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                                                <i data-feather="external-link"
                                                                    class="w-4 h-4 dark:text-gray-300 mr-2"></i>
                                                                Lihat
                                                            </a>
                                                            <a href=""
                                                                class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                                                <i data-feather="trash"
                                                                    class="w-4 h-4 dark:text-gray-300 mr-2"></i>
                                                                Hapus
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border-b whitespace-nowrap">Charlie</td>
                                        <td class="border-b whitespace-nowrap">Hunnam</td>
                                        <td class="border-b whitespace-nowrap">@charliehunnam</td>
                                        <td class="border-b whitespace-nowrap">charliehunnam@gmail.com</td>
                                        <td class="border-b whitespace-nowrap">8023 Amerige Street Harriman, NY 10926.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @if ($act !== '')
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
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Status</th>
                                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">User</th>
                                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Tanggal</th>
                                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border-b whitespace-nowrap">Angelina</td>
                                            <td class="border-b whitespace-nowrap">Jolie</td>
                                            <td class="border-b whitespace-nowrap">@angelinajolie</td>
                                            <td class="border-b whitespace-nowrap">angelinajolie@gmail.com</td>
                                        </tr>
                                        <tr>
                                            <td class="border-b whitespace-nowrap">Brad</td>
                                            <td class="border-b whitespace-nowrap">Pitt</td>
                                            <td class="border-b whitespace-nowrap">@bradpitt</td>
                                            <td class="border-b whitespace-nowrap">bradpitt@gmail.com</td>
                                        </tr>
                                        <tr>
                                            <td class="border-b whitespace-nowrap">Charlie</td>
                                            <td class="border-b whitespace-nowrap">Hunnam</td>
                                            <td class="border-b whitespace-nowrap">@charliehunnam</td>
                                            <td class="border-b whitespace-nowrap">charliehunnam@gmail.com</td>
                                        </tr>
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
            cekType();
            $('select').select2();
            $('#type-insurance').change(function() {
                cekType();
            });

            $("#kecamatan").select2({
                minimumInputLength: 3,
                allowClear: true,
                placeholder: "Masukkan Nama Kecamatan / Kelurahan / Kode Pos",
                ajax: {
                    dataType: "json",
                    url: "api/selectkodepos",
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

            $("#okupasi").select2({
                minimumInputLength: 3,
                allowClear: true,
                placeholder: "Pilih Okupasi",
                ajax: {
                    dataType: "json",
                    url: "api/selectokupasi",
                    data: function(params) {
                        return {
                            search: params.term,
                            instype: $('#type-insurance').val()
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
                    url: "api/selectinsured",
                    data: function(params) {
                        return {
                            search: params.term,
                        };
                    },
                    processResults: function(data, page) {
                        console.log('data', data);
                        return {
                            results: data,
                        };
                    },
                },
            });

            $('#insured').on('select2:select', function(e) {
                var data = e.params.data;
                $('#nik').val("");
                $('#insured-address').val("");
                $('#nik').attr('disabled', false);
                $('#insured-address').attr('disabled', false);
                if (data.noktp !== undefined) {
                    $('#nik').val(data.noktp);
                    $('#nik').attr('disabled', true);
                }
                if (data.alamat !== undefined) {
                    $('#insured-address').val(data.alamat);
                    $('#insured-address').attr('disabled', true);
                }
            });
        });
    </script>
@endsection

@extends('layouts.master')
@section('title', 'Pengajuan')
@section('breadcrumb', 'Pengajuan')
@section('menu', 'Pengajuan')
@section('content')
    <div class="intro-y flex items-center mt-4">
        <h2 class="text-lg font-medium mr-auto">
            Formulir Pengajuan <button class="btn btn-sm btn-primary">Simpan</button>
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
                        <div class="form-inline mt-5">
                            <label for="noapp" class="form-label sm:w-20">Nomor Aplikasi</label>
                            <input type="text" id="noapp" class="form-control" required name="noapp"
                                value="BDS{{ date('my') }}00001" disabled>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="type-insurance" class="ml-3 form-label sm:w-20">Tipe Asuransi</label>
                            <select id="type-insurance" data-search="true" class="tom-select w-full" name="type-insurance"
                                required>
                                <option value="PAR" selected>Property All Risk</option>
                                <option value="FIRE">Fire Insurance(PSAKI)</option>
                            </select>
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
                            <label for="insured" class="ml-3 form-label sm:w-20">Tetanggung (QQ)</label>
                            <select id="insured" data-search="true" class="w-full" name="insured" required>
                                <option value="bpk">Bpk. Tambunan</option>
                                <option value="ynt">Yanti Susimiloyo</option>
                                <option value="slm">Sulaiman B. Mail</option>
                                <option value="sdr">Sudarsono</option>
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
                        @if (Auth::user()->level !== 'ao')
                            <div class="form-inline mt-5">
                                <label for="editor" class="ml-3 form-label sm:w-20">Klausa</label>
                                <div class="w-full">
                                    <div data-simple-toolbar="true" class="editor">
                                        <p>Contoh Klausa</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-inline mt-5">
                            <label for="okupasi" class="ml-3 form-label sm:w-20">Okupasi</label>
                            <select id="okupasi" data-search="true" class="tom-select w-full" name="okupasi" required>
                                <option disabled selected>- Pilih Okupasi -</option>
                                <option value="2976|0.0294%">2976 - Rumah Tinggal (0.0294%)</option>
                                <option value="2971|0.0368%">2971 - Kantor / Apartemen s/d 6 lantai(0.0368%)</option>
                                <option value="2971|0.0385%">2971 - Kantor / Apartemen 6 s/d 18 lantai(0.0385%)</option>
                                <option value="2971|0.0376%">2971 - Kantor / Apartemen diatas 18 lantai(0.0376%)</option>
                                <option value="2934|0.1520%">2934 - Toko / Ruko (0.1520%)</option>
                                <option value="2971-1|0.1127%">29371 - Gudang Pribadi (Building Only , 0.1127%)</option>
                                <option value="2945|0.1479%">2945 - Restaurants (0.1479%)</option>
                                <option value="2930|0.0889%">2930 - Dispensaries (Apotik, 0.0889%)</option>
                                <option value="2951|0.0378%">2951 - Sanotarium,hospitals,doctors consulting rooms, old
                                    peoples and childrens home(0.0378%)</option>
                            </select>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="lokasi" class="form-label sm:w-20">Lokasi</label>
                            <textarea id="lokasi" class="form-control"></textarea>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="provinsi" class="ml-3 form-label sm:w-20">Provinsi</label>
                            <select id="provinsi" data-search="true" class="tom-select w-full" name="provinsi" required>
                                @foreach ($provinsi as $prv)
                                    <option value="{{ $prv->provinsi }}">{{ $prv->provinsi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="kabupaten" class="ml-3 form-label sm:w-20">Kota / Kabupaten</label>
                            <select id="kabupaten" data-search="true" class="w-full" name="kabupaten" required disabled>
                            </select>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="kecamatan" class="ml-3 form-label sm:w-20">Kecamatan</label>
                            <select id="kecamatan" data-search="true" class="w-full" name="kecamatan" required disabled>
                            </select>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="kelurahan" class="ml-3 form-label sm:w-20">Kelurahan / Kode Pos</label>
                            <select id="kelurahan" class="w-full js-data-example-ajax" name="kelurahan" required>
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
                                        type="text" class="form-control" placeholder="Bangunan" aria-label="Bangunan"
                                        aria-describedby="group-b">
                                </div>
                            </td>
                            <td><input name="InterestRemarks[0]" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group">
                                    <div id="group-s" class="input-group-text">M</div> <input style="text-align:right;"
                                        type="text" class="form-control" placeholder="Mesin" aria-label="Mesin"
                                        aria-describedby="group-s">
                                </div>
                            </td>
                            <td><input name="InterestRemarks[2]" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group">
                                    <div id="group-s" class="input-group-text">K</div> <input style="text-align:right;"
                                        type="text" class="form-control" placeholder="Ketersediaan"
                                        aria-label="Ketersediaan" aria-describedby="group-s">
                                </div>
                            </td>
                            <td><input name="InterestRemarks[3]" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group">
                                    <div id="group-pk" class="input-group-text">PK</div> <input style="text-align:right;"
                                        type="text" class="form-control" placeholder="Peralatan Kantor"
                                        aria-label="Peralatan Kantor" aria-describedby="group-pk">
                                </div>
                            </td>
                            <td><input name="InterestRemarks[5]" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group">
                                    <div id="group-ip" class="input-group-text">I/P</div> <input style="text-align:right;"
                                        type="text" class="form-control" placeholder="Isi / Perabotan"
                                        aria-label="Isi / Perabotan" aria-describedby="group-ip">
                                </div>
                            </td>
                            <td><input name="InterestRemarks[6]" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group">
                                    <div id="group-l" class="input-group-text">L</div> <input style="text-align:right;"
                                        type="text" class="form-control" placeholder="Lain-Lain" aria-label="Lain-Lain"
                                        aria-describedby="group-l">
                                </div>
                            </td>
                            <td><input name="InterestRemarks[4]" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group">
                                    <div id="group-t" class="input-group-text">Total</div> <input style="text-align:right;"
                                        type="text" class="form-control" placeholder="Total Nilai Pertanggungan"
                                        aria-label="Total Nilai Pertanggungan" aria-describedby="group-t" disabled>
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
                                <div class="text-lg font-medium">Drop files here or click to upload.</div>
                                <div class="text-gray-600"> This is just a demo dropzone. Selected files are <span
                                        class="font-medium">not</span> actually uploaded. </div>
                            </div>
                        </form>
                    </div>
                    <div class="source-code hidden">
                        <button data-target="#copy-multiple-file-upload"
                            class="copy-code btn py-1 px-2 btn-outline-secondary"> <i data-feather="file"
                                class="w-4 h-4 mr-2"></i> Copy example code </button>
                        <div class="overflow-y-auto mt-3 rounded-md">
                            <pre id="copy-multiple-file-upload"
                                class="source-preview"> <code class="text-xs p-0 rounded-md html pl-5 pt-8 pb-4 -mb-10 -mt-10"> HTMLOpenTagform action=&quot;/file-upload&quot; class=&quot;dropzone&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;fallback&quot;HTMLCloseTag HTMLOpenTaginput name=&quot;file&quot; type=&quot;file&quot; multiple/HTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;dz-message&quot; data-dz-messageHTMLCloseTag HTMLOpenTagdiv class=&quot;text-lg font-medium&quot;HTMLCloseTagDrop files here or click to upload.HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;text-gray-600&quot;HTMLCloseTag This is just a demo dropzone. Selected files are HTMLOpenTagspan class=&quot;font-medium&quot;HTMLCloseTagnotHTMLOpenTag/spanHTMLCloseTag actually uploaded. HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/formHTMLCloseTag </code> </pre>
                        </div>
                    </div>
                </div>
                <div class="p-5" id="responsive-table">
                    <div class="preview">
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">#</th>
                                        <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">First Name</th>
                                        <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Last Name</th>
                                        <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Username</th>
                                        <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Email</th>
                                        <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border-b whitespace-nowrap">1</td>
                                        <td class="border-b whitespace-nowrap">Angelina</td>
                                        <td class="border-b whitespace-nowrap">Jolie</td>
                                        <td class="border-b whitespace-nowrap">@angelinajolie</td>
                                        <td class="border-b whitespace-nowrap">angelinajolie@gmail.com</td>
                                        <td class="border-b whitespace-nowrap">260 W. Storm Street New York, NY 10025.</td>
                                    </tr>
                                    <tr>
                                        <td class="border-b whitespace-nowrap">2</td>
                                        <td class="border-b whitespace-nowrap">Brad</td>
                                        <td class="border-b whitespace-nowrap">Pitt</td>
                                        <td class="border-b whitespace-nowrap">@bradpitt</td>
                                        <td class="border-b whitespace-nowrap">bradpitt@gmail.com</td>
                                        <td class="border-b whitespace-nowrap">47 Division St. Buffalo, NY 14241.</td>
                                    </tr>
                                    <tr>
                                        <td class="border-b whitespace-nowrap">3</td>
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
                    <div class="source-code hidden">
                        <button data-target="#copy-responsive-table" class="copy-code btn py-1 px-2 btn-outline-secondary">
                            <i data-feather="file" class="w-4 h-4 mr-2"></i> Copy example code </button>
                        <div class="overflow-y-auto mt-3 rounded-md">
                            <pre class="source-preview"
                                id="copy-responsive-table"> <code class="text-xs p-0 rounded-md html pl-5 pt-8 pb-4 -mb-10 -mt-10"> HTMLOpenTagdiv class=&quot;overflow-x-auto&quot;HTMLCloseTag HTMLOpenTagtable class=&quot;table&quot;HTMLCloseTag HTMLOpenTagtheadHTMLCloseTag HTMLOpenTagtrHTMLCloseTag HTMLOpenTagth class=&quot;border-b-2 dark:border-dark-5 whitespace-nowrap&quot;HTMLCloseTag#HTMLOpenTag/thHTMLCloseTag HTMLOpenTagth class=&quot;border-b-2 dark:border-dark-5 whitespace-nowrap&quot;HTMLCloseTagFirst NameHTMLOpenTag/thHTMLCloseTag HTMLOpenTagth class=&quot;border-b-2 dark:border-dark-5 whitespace-nowrap&quot;HTMLCloseTagLast NameHTMLOpenTag/thHTMLCloseTag HTMLOpenTagth class=&quot;border-b-2 dark:border-dark-5 whitespace-nowrap&quot;HTMLCloseTagUsernameHTMLOpenTag/thHTMLCloseTag HTMLOpenTagth class=&quot;border-b-2 dark:border-dark-5 whitespace-nowrap&quot;HTMLCloseTagEmailHTMLOpenTag/thHTMLCloseTag HTMLOpenTagth class=&quot;border-b-2 dark:border-dark-5 whitespace-nowrap&quot;HTMLCloseTagAddressHTMLOpenTag/thHTMLCloseTag HTMLOpenTag/trHTMLCloseTag HTMLOpenTag/theadHTMLCloseTag HTMLOpenTagtbodyHTMLCloseTag HTMLOpenTagtrHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag1HTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagAngelinaHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagJolieHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag@angelinajolieHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagangelinajolie@gmail.comHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag260 W. Storm Street New York, NY 10025.HTMLOpenTag/tdHTMLCloseTag HTMLOpenTag/trHTMLCloseTag HTMLOpenTagtrHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag2HTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagBradHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagPittHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag@bradpittHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagbradpitt@gmail.comHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag47 Division St. Buffalo, NY 14241.HTMLOpenTag/tdHTMLCloseTag HTMLOpenTag/trHTMLCloseTag HTMLOpenTagtrHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag3HTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagCharlieHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagHunnamHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag@charliehunnamHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagcharliehunnam@gmail.comHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag8023 Amerige Street Harriman, NY 10926.HTMLOpenTag/tdHTMLCloseTag HTMLOpenTag/trHTMLCloseTag HTMLOpenTag/tbodyHTMLCloseTag HTMLOpenTag/tableHTMLCloseTag HTMLOpenTag/divHTMLCloseTag </code> </pre>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.js-data-example-ajax').select2({
                minimumInputLength: 2,
                tags: [],
                ajax: {
                    url: URL,
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    data: function(term) {
                        return {
                            term: term
                        };
                    },
                    results: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.completeName,
                                    slug: item.slug,
                                    id: item.id
                                }
                            })
                        };
                    }
                }
            });
        });
    </script>
@endsection

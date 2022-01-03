@extends('layouts.master')
@section('title', 'Inquiry')
@section('breadcrumb', 'Inquiry')
@section('link', url('inquiry'))
@section('menu', 'Inquiry')

@section('content')
    <style>
        .swal2-container {
            z-index: 99999;
        }

    </style>
    <h2 class="intro-y text-lg font-medium mt-5" id="text-inquiry">
        Data {{ !empty($_GET['data']) ? ucwords($_GET['data']) : 'Pengajuan Asuransi' }}
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                <select id="filterlength" class="w-20 form-select box mt-3 sm:mt-0" onChange="changelen()">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
            </div>
            <div class="dropdown ml-3 mt-3 sm:mt-0 sm:ml-auto md:ml-3 mr-3">
                <button id="btn-proses" class="dropdown-toggle btn btn-primary" aria-expanded="false" disabled><i
                        data-feather="settings" class="w-4 h-4 dark:text-gray-300 mr-2"></i>
                    Proses</button>
                <div class="proses dropdown-menu w-56">
                    <div class="dropdown-menu__content box dark:bg-dark-1">
                        <div class="p-2">
                            <a id="ps-lihat"
                                class="flex items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"
                                style="cursor:pointer">
                                <i data-feather="search" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                Lihat 
                            </a>
                            @role('maker|checker|adm')
                                {{-- <a id="ps-ubah"
                                    class="flex items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-0"
                                    style="cursor:pointer">
                                    <i data-feather="edit" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Ubah
                                </a> --}}
                                <a class="ps-approve flex text-theme-9 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-0"
                                    style="cursor:pointer">
                                    <i data-feather="check-square" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Ajukan
                                </a>
                                <a class="ps-approve flex text-theme-9 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-4"
                                    style="cursor:pointer">
                                    <i data-feather="check-square" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Setujui
                                </a>
                                <a class="ps-renewal flex text-theme-9 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-10"
                                    style="cursor:pointer">
                                    <i data-feather="refresh-cw" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Perpanjang
                                </a>
                                <a id="ps-rollback"
                                    class="flex text-theme-11 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-4"
                                    style="cursor:pointer">
                                    <i data-feather="rotate-ccw" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Batal Mengajukan
                                </a>
                                <a id="ps-hapus"
                                    class="flex text-theme-6 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-0"
                                    style="cursor:pointer">
                                    <i data-feather="trash-2" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Hapus
                                </a>
                            @endrole
                            @role('approver|adm')
                                <a class="ps-approve flex text-theme-9 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-1"
                                    style="cursor:pointer">
                                    <i data-feather="check-square" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Setujui
                                </a>
                                <a id="ps-rollback"
                                    class="flex text-theme-11 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-1"
                                    style="cursor:pointer">
                                    <i data-feather="rotate-ccw" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Kembalikan
                                </a>
                            @endrole
                            @role('broker|adm')
                                <a class="ps-approve flex text-theme-9 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-2"
                                    style="cursor:pointer">
                                    <i data-feather="check-square" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Verifikasi
                                </a>
                                <a class="ps-approve flex text-theme-9 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-8"
                                    style="cursor:pointer">
                                    <i data-feather="edit-3" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Cek Polis
                                </a>
                                <a id="ps-rollback"
                                    class="flex text-theme-11 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-2"
                                    style="cursor:pointer">
                                    <i data-feather="rotate-ccw" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Kembalikan
                                </a>
                            @endrole
                            @role('finance|adm')
                                <a class="ps-bayar flex text-theme-9 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-5"
                                    style="cursor:pointer">
                                    <i data-feather="edit-3" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Input Pembayaran
                                </a>
                                @endrole
                            @role('insurance|adm')
                                <a class="ps-approve flex text-theme-9 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-3"
                                    style="cursor:pointer">
                                    <i data-feather="check-square" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Setujui
                                </a>
                                <a class="ps-polis flex text-theme-9 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-7"
                                    style="cursor:pointer">
                                    <i data-feather="upload" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Upload E-Polis
                                </a>
                                <a id="ps-rollback"
                                    class="flex text-theme-11 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-3"
                                    style="cursor:pointer">
                                    <i data-feather="rotate-ccw" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Kembalikan
                                </a>
                            @endrole
                        </div>
                        @role('checker|maker|broker|finance|adm')
                            <div class="p-2 border-t border-gray-200 dark:border-dark-5 ps-st st-5 st-6 st-7 st-8 st-9 st-10 text-theme-9">
                                <a id="ps-invoice"
                                    class="flex items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"
                                    style="cursor:pointer">
                                    <i data-feather="file-text" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Invoice
                                </a>
                            </div>
                        @endrole
                    </div>
                </div>
            </div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-gray-700 dark:text-gray-300">
                    <input type="text" id="filterSearch" class="form-control w-56 box pr-10 placeholder-theme-13"
                        placeholder="Search..." value="{{ empty($_GET['q']) ? $qsearch : $_GET['q'] }}">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-auto">

            <table id="tb-inquiry" class="hover table mt-2 table-report table-report--tabulator">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">No. App</th>
                        <th class="whitespace-nowrap">Asuransi</th>
                        <th class="whitespace-nowrap">Tipe Asuransi</th>
                        <th class="whitespace-nowrap">Cabang</th>
                        <th class="whitespace-nowrap">Tertanggung</th>
                        <th class="whitespace-nowrap">No. Polis</th>
                        <th class="whitespace-nowrap">Cover Note</th>
                        <th class="whitespace-nowrap">No. Pinjaman</th>
                        <th class="whitespace-nowrap">Periode Polis</th>
                        <th class="whitespace-nowrap">Nilai Pertanggungan</th>
                        <th class="whitespace-nowrap">Premium</th>
                        <th class="whitespace-nowrap">Tanggal Dibuat</th>
                        <th class="whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            <ul class="pagination">
            </ul>
        </div>
        <!-- END: Pagination -->
    </div>
    <div id="modal-bayar" class="modal" data-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <form id="frm-bayar">
                    @csrf
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">
                            Input Pembayaran
                        </h2>
                    </div>
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 sm:col-span-6">
                            <label for="transid" class="form-label">Nomor Transaksi</label>
                            <input id="transid" type="text" class="form-control" readonly>
                            <input type="hidden" name="transid" readonly>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label for="nama_insured" class="form-label">Nama Tertanggung</label>
                            <input id="nama_insured" name="nama_insured" type="text" class="form-control" readonly>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label for="cover_note" class="form-label">Cover Note</label>
                            <input id="cover_note" name="cover_note" type="text" class="form-control" readonly>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label for="tgl_bayar" class="form-label">Tanggal Bayar</label>
                            <input id="tgl_bayar" required name="tgl_bayar" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label for="tagihan" class="form-label">Tagihan Premi</label>
                            <input id="tagihan" type="text" class="form-control currency allow-decimal masked" readonly>
                            <input name="tagihan" type="hidden">
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label for="paid" class="form-label">Nominal Dibayar</label>
                            <input id="paid" required type="text" class="form-control currency allow-decimal masked">
                            <input name="paid" required type="hidden">
                        </div>
                    </div>
                    <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer text-right">
                        <button type="button" data-dismiss="modal"
                            class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                        <button type="submit" class="btn btn-primary w-20">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-polis" class="modal" data-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <form id="frm-polis">
                    @csrf
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">
                            Input Polis
                        </h2>
                    </div>
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 sm:col-span-6">
                            <label for="transid" class="form-label">Nomor Transaksi</label>
                            <input id="transid" type="text" class="form-control" readonly>
                            <input type="hidden" name="transid" readonly>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label for="nama_insured" class="form-label">Nama Tertanggung</label>
                            <input id="nama_insured" name="nama_insured" type="text" class="form-control" readonly>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label for="cover_note" class="form-label">Cover Note</label>
                            <input id="cover_note" name="cover_note" type="text" class="form-control" readonly>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label for="nopolis" class="form-label">Nomor Polis</label>
                            <input id="nopolis" required name="nopolis" type="text" class="form-control">
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label for="e-polis" class="form-label">Upload E-Polis</label>
                            <input id="e-polis" type="file" name="polis" class="form-control" accept="application/pdf"
                                required>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label for="invoice" class="form-label">Upload Invoice</label>
                            <input id="invoice" type="file" name="invoice" class="form-control" accept="application/pdf"
                                required>
                        </div>
                    </div>
                    <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer text-right">
                        <button type="button" data-dismiss="modal"
                            class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                        <button type="submit" class="btn btn-primary w-20">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="frm" method="POST" target="new_page">
        @csrf
        <input type="hidden" id="frm-method" name="method">
    </form>
    <!-- END: Delete Confirmation Modal -->
@endsection

@section('script')
    <script>
        function reloadTable() {
            $('#tb-inquiry').DataTable().ajax.reload();
        }

        function changelen() {
            $('#tb-inquiry').DataTable().page.len($('#filterlength').val()).draw();
        }

        $(document).ready(function() {
            var tablenya = $('#tb-inquiry').DataTable({
                "dom": "Bti",
                "select": "single",
                "processing": true,
                "serverSide": true,
                "bLengthChange": true,
                "aoColumns": [{
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
                        "className": "border-b text-right"
                    },
                    {
                        "bSortable": true,
                        "className": "border-b text-right"
                    },
                    {
                        "bSortable": true,
                        "className": "border-b",
                    },
                    {
                        "visible": false,
                        "className": "border-b",
                    },
                ],
                "ajax": {
                    url: "{{ url('api/datatransaksi') }}",
                    headers: {
                        'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                    },
                    type: "POST",
                    data: function(d) {
                        d.search = $("#filterSearch").val();
                        d.length = $("#filterlength").val();
                        d._token = '{{ csrf_token() }}';
                        @if (!empty($_GET['data']))
                            d.data = '{{ $_GET['data'] }}';
                        @endif
                        // console.log('datanya: ', d);
                    },
                    // success: function(d) {
                    //     // console.log(d);
                    //     $.each(d.sql, function(i,v) {
                    //         console.log(v.query);
                    //     })
                    // },
                    error: function(d) {
                        console.log('error:', d);
                        if (d.status = 401) {
                            // window.location.href = "{{ url('logout') }}";
                        }
                    },
                },
                "createdRow": function(row, data, dataIndex) {
                    $(row).addClass("hover:bg-gray-200");
                }
            });

            $(window).focus(function() {
                reloadTable();
            });

            $('#filterSearch').on('keypress', function(e) {
                if (e.which == 13) {
                    reloadTable();
                }
            });
            $('#filterSearch').keyup(function() {
                if ($(this).val().length) {
                    return false;
                } else {
                    reloadTable();
                }
            });

            tablenya.on('draw', function() {
                paginatioon(tablenya, $('ul.pagination'));

                feather.replace();

                $('.gotoPage').click(function() {
                    gotoPage($(this), tablenya);
                });

                $('#tb-inquiry > tbody > tr').each(function() {
                    $(this).addClass('dark:hover:bg-dark-2');
                });
            });

            $('#tb-inquiry').on('click', 'tbody > tr', function() {
                setTimeout(() => {
                    if ($('.selected')[0]) {
                        $('.dropdown-toggle').attr('disabled', false);
                    } else {
                        $('.dropdown-toggle').attr('disabled', true);
                        $('.ps-st').css('display', 'none');
                    }
                }, 64);
                var statusnya = tablenya.row(this).data()[13];
                $('.ps-st').css('display', 'none');
                $('.st-' + statusnya).css('display', 'block');
            });

            $('#ps-lihat').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({
                    selected: true
                }).data()[0];
                window.open("{{ url('pengajuan') }}/" + transid);
            });

            $('#frm').submit(function() {
                // window.open('about:blank','new_page');
            });

            $('.ps-approve').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({
                    selected: true
                }).data()[0];
                $('#frm-method').val('approve');
                $('#frm').attr('action', "{{ url('pengajuan') }}/" + transid).submit();
            });

            $('.ps-renewal').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({
                    selected: true
                }).data()[0];
                $('#frm-method').val('renewal');
                $('#frm').attr('action', "{{ url('pengajuan') }}/" + transid).submit();
            });

            // $('#ps-ubah').click(function(e) {
            //     e.preventDefault();
            //     var transid = tablenya.row({
            //         selected: true
            //     }).data()[0];
            //     $('#frm-method').val('update');
            //     $('#frm').attr('action', "{{ url('pengajuan') }}/" + transid).submit();
            // });

            $('.ps-bayar').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({
                    selected: true
                }).data()[0];
                $.ajax({
                    url: "{{ url('api/caritransaksi') }}",
                    headers: {
                        'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                    },
                    type: "GET",
                    data: {
                        transid
                    },
                    success: function(d) {
                        $('#frm-bayar #transid').val(d.transaksi.transid);
                        $('#frm-bayar [name="transid"]').val(d.transaksi.transid);
                        $('#frm-bayar #nama_insured').val(d.insured.nama_insured);
                        $('#frm-bayar #cover_note').val(d.transaksi.cover_note);
                        $('#frm-bayar #tagihan').val(d.pricing[18].value);
                        $('.masked').trigger('keyup');
                        cash('#modal-bayar').modal('show');
                    },
                    error: function(d) {
                        console.log('d', d);
                    },
                });
            });

            $('.ps-polis').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({
                    selected: true
                }).data()[0];

            });

            cash('#modal-bayar').on('hidden.bs.modal', function() {
                console.log('tutup');
            });

            $('.ps-polis').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({
                    selected: true
                }).data()[0];
                $.ajax({
                    url: "{{ url('api/caritransaksi') }}",
                    headers: {
                        'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                    },
                    type: "GET",
                    data: {
                        transid
                    },
                    success: function(d) {
                        $('#frm-polis #transid').val(d.transaksi.transid);
                        $('#frm-polis [name="transid"]').val(d.transaksi.transid);
                        $('#frm-polis #nama_insured').val(d.insured.nama_insured);
                        $('#frm-polis #cover_note').val(d.transaksi.cover_note);
                        cash('#modal-polis').modal('show');
                    },
                    error: function(d) {
                        console.log('d', d);
                    },
                });
            })

            $('#frm-bayar').submit(function(e) {
                e.preventDefault();
                var data = $(this).serializeArray(),
                    paid = data[6].value,
                    tagihan = data[5].value;
                data.push({
                    name: "method",
                    value: "bank"
                });
                console.log(data);
                if (paid == tagihan) {
                    $.ajax({
                        url: "{{ url('api/pembayaran') }}",
                        data: data,
                        type: "POST",
                        dataType: "JSON",
                        success: function(d) {
                            console.log(d);
                            Swal.fire(
                                'Berhasil!',
                                d.message,
                                'success'
                            );
                            $(':input', '#frm-bayar')
                                .not(':button, :submit, :reset, :hidden')
                                .val('');
                            $('#tgl_bayar').val("{{ date('Y-m-d') }}");
                            cash('#modal-bayar').modal('hide');
                            reloadTable();
                        },
                        error: function(d) {
                            console.log(d.responseJSON);
                            Swal.fire(
                                'Gagal!',
                                d.responseJSON.message,
                                'error'
                            );
                        },
                    });
                } else {
                    Swal.fire(
                        'Gagal!',
                        'Nominal yang dibayar tidak sesuai dengan tagihan premi',
                        'error'
                    );
                }
            });

            $('#frm-polis').submit(function(e) {
                e.preventDefault();
                var data = new FormData(this);
                data.append("method", "store");
                console.log('data', data);
                $.ajax({
                    url: "{{ url('api/polis') }}",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    data: data,
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(d) {
                        reloadTable();
                        $('#frm-polis').trigger('reset');
                        console.log(d);
                        Swal.fire(
                            'Berhasil!',
                            d.message,
                            'success'
                        ).then(function() {
                            cash('#modal-polis').modal('hide');
                        });
                    },
                    error: function(d) {
                        console.log(d);
                        Swal.fire(
                            'Gagal!',
                            d.message,
                            'error'
                        );
                    },
                });
            })

            $('#ps-hapus').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({
                        selected: true
                    }).data()[0],
                    _token = "{{ csrf_token() }}",
                    method = "delete";
                Swal.fire({
                    title: 'Yakin ingin hapus?',
                    html: "Data <b>" + transid + "</b> tidak akan dapat dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    // confirmButtonColor: '#1C3FAA',
                    // cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Tidak!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Masukan Alasan Menghapus Data',
                            input: 'textarea',
                            showCancelButton: true,
                            confirmButtonText: 'Konfirmasi',
                            cancelButtonText: 'Batal'
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                var catatan = "";
                                if (result.value) {
                                    catatan = result.value;
                                }
                                $.ajax({
                                    url: "{{ url('api/pengajuan') }}",
                                    data: {
                                        transid,
                                        _token,
                                        method,
                                        catatan
                                    },
                                    type: "POST",
                                    success: function(d) {
                                        console.log('response :', d);
                                        d = d.responseJSON;
                                        Swal.fire(
                                            'Berhasil!',
                                            d.message,
                                            'success'
                                        ).then(function() {
                                            reloadTable();
                                        });
                                    },
                                    error: function(d) {
                                        console.log('response :', d);
                                        d = d.responseJSON;
                                        Swal.fire(
                                            'Gagal!',
                                            d.message,
                                            'error'
                                        );
                                    }
                                });
                            }
                        });
                    }
                });
            });

            $('#ps-rollback').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({
                        selected: true
                    }).data()[0],
                    _token = "{{ csrf_token() }}",
                    method = "rollback";
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    html: "Data <b>" + transid + "</b> akan dikembalikan ke status sebelumnya.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kembalikan!',
                    cancelButtonText: 'Tidak!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Catatan Pengembalian',
                            input: 'textarea',
                            showCancelButton: true,
                            confirmButtonText: 'Konfirmasi',
                            cancelButtonText: 'Batal'
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                var catatan = "";
                                if (result.value) {
                                    catatan = result.value;
                                }
                                $.ajax({
                                    url: "{{ url('api/pengajuan') }}",
                                    data: {
                                        transid,
                                        _token,
                                        method,
                                        catatan
                                    },
                                    type: "POST",
                                    success: function(d) {
                                        console.log('response :', d);
                                        Swal.fire(
                                            'Berhasil!',
                                            d.message,
                                            'success'
                                        ).then(function() {
                                            reloadTable();
                                        });
                                    },
                                    error: function(d) {
                                        console.log('response :', d);
                                        Swal.fire(
                                            'Gagal!',
                                            d.message,
                                            'error'
                                        );
                                    }
                                });
                            }
                        });
                    }
                });
            });

            $('#ps-invoice').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({
                    selected: true
                }).data()[0];
                window.open("{{ url('cetak_invoice') }}/" + transid);
            });

            $('a').click(function() {
                $('#text-inquiry').click();
            })
        });
    </script>
@endsection

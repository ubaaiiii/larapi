@extends('layouts.master')
@section('title', 'Pembayaran')
@section('breadcrumb', 'Pembayaran')
@section('link', url('Pembayaran'))
@section('menu', 'Pembayaran')

@section('content')
<style>
    .swal2-container {
        z-index: 99999;
    }
</style>
<h2 class="intro-y text-lg font-medium mt-5" id="text-inquiry">
    Data Pembayaran
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
            <div class="ml-3">
                <div class="flex flex-col sm:flex-row">
                    <label class="mr-2">Filter Data : </label>
                    <div class="form-check mr-2">
                        <input id="belum_dibayar" name="belum_dibayar" class="form-check-input cek-filter" type="checkbox">
                        <label class="form-check-label" for="belum_dibayar">Belum Dibayar</label>
                    </div>
                    <div class="form-check mr-2">
                        <input id="sudah_dibayar" name="sudah_dibayar" class="form-check-input cek-filter" type="checkbox">
                        <label class="form-check-label" for="sudah_dibayar">Sudah Dibayar</label>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function(){
                    $('.cek-filter').click(function() {
                        reloadTable();
                        console.log('reload table');
                    });
                });
            </script>
        </div>
        @role('broker|adm')
        <a href="javascript:;" data-toggle="modal" data-target="#modal-import"
            class="ml-3 mt-3 sm:mt-0 md:ml-3 btn btn-primary">
            <i data-feather="upload" class="w-4 h-4 dark:text-gray-300 mr-2"></i>Import Excel</a>
        @endrole
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
                        <a class="ps-ubah flex items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"
                            style="cursor:pointer">
                            <i data-feather="edit" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                            Ubah 
                        </a>
                        @role('finance|adm')
                            <a class="ps-bayar flex text-theme-9 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-1"
                                style="cursor:pointer">
                                <i data-feather="edit-3" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                Bayar ke Asuransi
                            </a>
                            
                        @endrole
                    </div>
                    @role('finance|adm')
                        <div class="p-2 border-t border-gray-200 dark:border-dark-5 text-theme-6">
                            <a class="ps-batal flex text-theme-6 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-1"
                                style="cursor:pointer">
                                <i data-feather="trash-2" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                Batal Bayar Bank
                            </a>
                            <a class="ps-batal flex text-theme-6 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-0"
                                style="cursor:pointer">
                                <i data-feather="trash-2" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                Batal Bayar Asuransi
                            </a>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                <input type="text" id="filterSearch" class="form-control w-56 box pr-10 placeholder-theme-13"
                    placeholder="Search..." value="{{ (empty($_GET['q'])) ? $qsearch : $_GET['q'] }}">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search" onclick="reloadTable()"></i>
            </div>
        </div>
    </div>
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-auto">
        <table id="tb-pembayaran" class="hover table mt-2 table-report table-report--tabulator">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">Terima Dari Bukopin</th>
                    <th class="whitespace-nowrap">Bayar Ke Asuransi</th>
                    <th class="whitespace-nowrap">ID Transaksi</th>
                    <th class="whitespace-nowrap">Covernote</th>
                    <th class="whitespace-nowrap">No. Polis</th>
                    <th class="whitespace-nowrap">Asuransi</th>
                    <th class="whitespace-nowrap">No. Rekening</th>
                    <th class="whitespace-nowrap">Tagihan</th>
                    <th class="whitespace-nowrap">Komisi</th>
                    <th class="whitespace-nowrap">PPN</th>
                    <th class="whitespace-nowrap">PPh</th>
                    <th class="whitespace-nowrap">Total Payment</th>
                    <th class="whitespace-nowrap">ID Pembayaran</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <!-- END: Data List -->
    <!-- BEGIN: Pagination -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        <ul class="pagination pagination-bayar">
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
                        <label for="nama_asuransi" class="form-label">Tujuan Pembayaran</label>
                        <input id="nama_asuransi" name="nama_asuransi" type="text" class="form-control" readonly>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label for="rekening_asuransi" class="form-label">No. Rekening</label>
                        <input id="rekening_asuransi" name="rekening_asuransi" type="text" class="form-control" readonly>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label for="tgl_bayar" class="form-label">Tanggal Bayar</label>
                        <input id="tgl_bayar" required name="tgl_bayar" type="date" class="form-control">
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label for="tagihan" class="form-label">Gross NET</label>
                        <input id="tagihan" type="text" class="form-control currency allow-decimal masked" readonly>
                        <input name="tagihan" type="hidden">
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label for="paid" class="form-label">Nominal Dibayar</label>
                        <input id="paid" required type="text" class="form-control currency allow-decimal masked">
                        <input name="paid" type="hidden">
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

<div id="modal-ubah" class="modal" data-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <form id="frm-ubah">
                @csrf
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">
                        Ubah Pembayaran
                    </h2>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12 sm:col-span-6">
                        <label for="transid" class="form-label">Nomor Transaksi</label>
                        <input id="transid" type="text" class="form-control" readonly>
                        <input type="hidden" name="transid" readonly>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label for="nama_asuransi" class="form-label">Tujuan Pembayaran</label>
                        <input id="nama_asuransi" name="nama_asuransi" type="text" class="form-control" readonly>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label for="diterima" class="form-label">Nominal Terima</label>
                        <input id="diterima" type="text" class="form-control currency allow-decimal masked" readonly>
                        <input name="diterima" type="hidden">
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label for="dibayar" class="form-label">Nominal Bayar</label>
                        <input id="dibayar" required type="text" class="form-control currency allow-decimal masked" readonly>
                        <input name="dibayar" required type="hidden">
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label for="tgl_terima" class="form-label">Tanggal Terima</label>
                        <input id="tgl_terima" name="tgl_terima" type="date" class="form-control" required>
                    </div>
                    <div class="col-span-12 sm:col-span-6" id="div_bayar">
                        <label for="tgl_bayar" class="form-label">Tanggal Bayar</label>
                        <input id="tgl_bayar" required name="tgl_bayar" type="date" class="form-control">
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

<div id="modal-import" class="modal" tabindex="-1" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
    <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">
                    Import Pembayaran
                </h2>
                <form id="frm-import">
                    @csrf
                    <div class="input-group mt-2">
                        <input id="file-import" type="file" class="form-control" name="file-import" aria-describedby="file-import">
                        <button class="btn btn-outline-primary input-group-text">
                            <i data-feather="upload" class="w-4 h-4 mr-2"></i>Upload
                        </button>
                    </div>
                </form>
            </div>
            <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <div class="modal-body">
                <div class="overflow-auto">
                    <table id="tb-import" class="hover table mt-2 table-report table-report--tabulator whitespace-nowrap">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">Import</th>
                                <th class="whitespace-nowrap">No.</th>
                                <th class="whitespace-nowrap">Tgl Bayar</th>
                                <th class="whitespace-nowrap">ID Transaksi</th>
                                <th class="whitespace-nowrap">Tertanggung</th>
                                <th class="whitespace-nowrap">Debit</th>
                                <th class="whitespace-nowrap">Credit</th>
                                <th class="whitespace-nowrap">Saldo</th>
                                <th class="whitespace-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- BEGIN: Pagination -->
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                <ul class="pagination pagination-import">
                </ul>
            </div>
            <!-- END: Pagination -->
            <!-- END: Modal Body -->
            <!-- BEGIN: Modal Footer -->
            <div class="modal-footer text-right">
                <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1"
                    onclick="tutupModal('modal-import');$('#tb-import').DataTable().clear();$('#tb-import').DataTable().draw();">Batal</button>
                <button id="btn-import" type="button" class="btn btn-primary w-20">Import</button>
            </div>
            <!-- END: Modal Footer -->
        </div>
    </div>
</div>

<form id="frm" method="POST" target="_blank">
    @csrf
    <input type="hidden" id="frm-method" name="method">
</form>
<!-- END: Delete Confirmation Modal -->
@endsection

@section('script')
    <script>
        function reloadTable()
        {
            $('#tb-pembayaran').DataTable().ajax.reload();
        }

        function changelen() {
            $('#tb-pembayaran').DataTable().page.len($('#filterlength').val()).draw();
        }

        function tutupModal(id) {
            $('#'+id).find('form')[0].reset();
        }

        $(document).ready(function() {
            var tablenya = $('#tb-pembayaran').DataTable({
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
                        "className": "border-b text-right",
                    },
                    {
                        "bSortable": true,
                        "className": "border-b text-right",
                    },
                    {
                        "bSortable": true,
                        "className": "border-b text-right",
                    },
                    {
                        "bSortable": true,
                        "className": "border-b text-right",
                    },
                    {
                        "bSortable": true,
                        "className": "border-b text-right",
                    },
                    {
                        "visible": false
                    },
                ],
                "order": [[ 2, "desc" ]],
                "ajax": {
                    url: "{{ url('api/datapembayaran') }}",
                    headers: {
                        'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                    },
                    type: "POST",
                    data: function(d) {
                        d.search = $("#filterSearch").val();
                        d.length = $("#filterlength").val();
                        d._token = '{{ csrf_token() }}';
                        d.filter_belum_dibayar = $('#belum_dibayar').is(':checked');
                        d.filter_sudah_dibayar = $('#sudah_dibayar').is(':checked');
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

            $(window).focus(function(){
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
                paginatioon(tablenya,$('.pagination-bayar'));

                feather.replace();

                $('.gotoPage').click(function() {
                    gotoPage($(this),tablenya);
                });

                $('#tb-pembayaran > tbody > tr').each(function() {
                    $(this).addClass('dark:hover:bg-dark-2');
                });
            });

            $('#tb-pembayaran').on('click', 'tbody > tr', function() {
                setTimeout(() => {
                    if ($('.selected')[0]) {
                        $('.dropdown-toggle').attr('disabled', false);
                    } else {
                        $('.dropdown-toggle').attr('disabled', true);
                        $('.ps-st').css('display', 'none');
                    }
                }, 64);
                var statusnya = tablenya.row(this).data()[12];
                $('.ps-st').css('display', 'none');
                $('.st-' + statusnya).css('display', 'block');
            });

            $('#ps-lihat').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({ selected: true }).data()[2];
                window.open("{{ url('pengajuan') }}/"+transid);
            });

            $('.ps-batal').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({ selected: true }).data()[2],
                    _token = "{{ csrf_token() }}",
                    method = "batal";
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    html: "Data <b>" + transid + "</b> akan dihapus pembayarannya.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Tidak!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Catatan Penghapusan',
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
                                    url: "{{ url('api/pembayaran') }}",
                                    data: {
                                        transid,
                                        _token,
                                        catatan,
                                        method,
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

            $('#frm-bayar').submit(function(e) {
                e.preventDefault();
                var data = $(this).serializeArray(),
                    paid = data[6].value,
                    tagihan = data[5].value;
                data.push({ name: "method", value: "asuransi" });
                if (paid == tagihan) {
                    $.ajax({
                        url: "{{ url('api/pembayaran') }}",
                        data: data,
                        type: "POST",
                        success: function(d) {
                            Swal.fire(
                                'Berhasil!',
                                d.message,
                                'success'
                            );
                            $(':input','#frm-bayar')
                                .not(':button, :submit, :reset, :hidden')
                                .val('');
                            $('#tgl_bayar').val("{{ date('Y-m-d') }}");
                            cash('#modal-bayar').modal('hide');
                            reloadTable();
                        },
                        error: function(d) {
                            Swal.fire(
                                'Gagal!',
                                d.message,
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

            $('#frm-ubah').submit(function(e) {
                e.preventDefault();
                var data       = $(this).serializeArray();
                data.push({ name: "method", value: "ubah" });

                $.ajax({
                    url: "{{ url('api/pembayaran') }}",
                    data: data,
                    type: "POST",
                    success: function(d) {
                        Swal.fire(
                            'Berhasil!',
                            d.message,
                            'success'
                        );
                        $(':input','#frm-ubah')
                            .not(':button, :submit, :reset, :hidden')
                            .val('');
                        $('#frm-ubah #tgl_terima').val("{{ date('Y-m-d') }}");
                        $('#frm-ubah #tgl_bayar').val("{{ date('Y-m-d') }}");
                        cash('#modal-ubah').modal('hide');
                        reloadTable();
                    },
                    error: function(d) {
                        Swal.fire(
                            'Gagal!',
                            d.message,
                            'error'
                        );
                    },
                });
            });

            var tableImport = $('#tb-import').DataTable({
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                "aoColumns": [{
                        "bSortable": true,
                        "className": "border-b select-checkbox",
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
                        "className": "border-b text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2, ''),
                    },
                    {
                        "bSortable": true,
                        "className": "border-b text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2, ''),
                    },
                    {
                        "bSortable": true,
                        "className": "border-b text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2, ''),
                    },
                    {
                        "bSortable": true,
                        "className": "border-b",
                    },
                ],
            });
            tableImport.on('draw',function(){
                paginatioon(tableImport,$('#tb-import_paginate .pagination'));
                feather.replace();
                
                $('.gotoPage').click(function() {
                    gotoPage($(this),tableImport);
                });

                tableImport.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    if (this.data()[0].indexOf("checked") >= 0) {
                        this.select();
                    }
                });
            });
            tableImport.draw();

            $('#frm-import').submit(function(e){
                e.preventDefault();
                var data = new FormData(this);
                $.ajax({
                    url: "{{ url('api/importpembayaran') }}",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    data: data,
                    type: "POST",
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: function(d) {
                        tableImport.clear();
                        tableImport.rows.add(d);
                        tableImport.draw();
                    },
                    error: function(d) {
                        Swal.fire(
                            'Gagal!',
                            d.message,
                            'error'
                        );
                    },
                });
            });

            $('#btn-import').click(function(){
                var data  = tableImport.rows('.selected').data().toArray();
                var total  = tableImport.rows('.selected').count();
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    html: "Total "+total+" data akan diimport pembayarannya ke sistem.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Import!',
                    cancelButtonText: 'Tidak!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{{ url('api/simpanimport') }}",
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            },
                            data: {data},
                            dataType: "json",
                            success: function (d) {
                                Swal.fire(
                                    'Berhasil!',
                                    d.message,
                                    'success'
                                ).then(function() {
                                    tableImport.clear();
                                    tableImport.draw();
                                    reloadTable();
                                    $('#frm-import').trigger('reset');
                                    cash('#modal-import').modal('hide');
                                });

                            },
                            error: function (d) {
                                console.log('error: ',d);
                            },
                        });
                    }
                });
            });
            
            $('a').click(function() {
                $('#text-inquiry').click();
            })
        });
</script>
@endsection
@extends('layouts.master')
@section('title', 'Pembayaran')
@section('breadcrumb', 'Pembayaran')
@section('menu', 'Pembayaran')

@section('content')
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
            </div>
            <div class="dropdown ml-3 mt-3 sm:mt-0 sm:ml-auto md:ml-3 mr-3">
                <button id="btn-proses" class="dropdown-toggle btn btn-primary" aria-expanded="false" disabled><i data-feather="settings"
                        class="w-4 h-4 dark:text-gray-300 mr-2"></i>
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
                            @role('ao|checker|adm')
                                <a id="ps-ubah"
                                    class="flex items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-0"
                                    style="cursor:pointer">
                                    <i data-feather="edit" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Ubah
                                </a>
                            @endrole
                            @role('ao|checker|adm')
                                <a 
                                    class="ps-approve flex text-theme-9 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-0"
                                    style="cursor:pointer">
                                    <i data-feather="check-square" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Ajukan
                                </a>
                            @endrole
                            @role('approver|adm')
                                <a 
                                    class="ps-approve flex text-theme-9 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-1"
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
                                <a
                                    class="ps-approve flex text-theme-9 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-2"
                                    style="cursor:pointer">
                                    <i data-feather="check-square" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Verifikasi
                                </a>
                                <a id="ps-rollback"
                                    class="flex text-theme-11 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-2"
                                    style="cursor:pointer">
                                    <i data-feather="rotate-ccw" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Kembalikan
                                </a>
                            @endrole
                            @role('insurance|adm')
                                <a 
                                    class="ps-approve flex text-theme-9 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-3"
                                    style="cursor:pointer">
                                    <i data-feather="check-square" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Aktifkan
                                </a>
                                <a id="ps-rollback"
                                    class="flex text-theme-11 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-3"
                                    style="cursor:pointer">
                                    <i data-feather="rotate-ccw" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                    Kembalikan
                                </a>
                            @endrole
                            @role('ao|checker|adm')
                            <a id="ps-hapus"
                                class="flex text-theme-6 items-center block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md ps-st st-0"
                                style="cursor:pointer">
                                <i data-feather="trash-2" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                                Hapus
                            </a>
                            @endrole
                        </div>
                        @role('checker|ao|broker|adm')
                        <div class="p-2 border-t border-gray-200 dark:border-dark-5 ps-st st-4">
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
                        placeholder="Search..." value="{{ (empty($_GET['q'])) ? $qsearch : $_GET['q'] }}">
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
                        <th class="whitespace-nowrap">Periode Polis</th>
                        <th class="whitespace-nowrap">Tanggal Dibuat</th>
                        <th class="whitespace-nowrap">Nilai Pertanggungan</th>
                        <th class="whitespace-nowrap">Premium</th>
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
    <!-- BEGIN: Delete Confirmation Modal -->
    <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-feather="x-circle" class="w-16 h-16 text-theme-6 mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">Apakah Anda Yakin?</div>
                        <div class="text-gray-600 mt-2">
                            Apa benar Anda ingin mengembalikan data?
                        </div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-dismiss="modal"
                            class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                        <button type="button" class="btn btn-danger w-24">Delete</button>
                    </div>
                </div>
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
                            window.location.href = "{{ url('logout') }}";
                        }
                    },
                },
                "createdRow": function(row, data, dataIndex) {
                    $(row).addClass("hover:bg-gray-200");
                }
            });

            $(window).focus(function(){
                reloadTable();
            })

            $('#filterSearch').on('keypress', function(e) {
                if (e.which == 13) {
                    reloadTable();
                }
            });

            tablenya.on('draw', function() {
                paginatioon(tablenya,$('ul.pagination'));

                feather.replace();

                $('.gotoPage').click(function() {
                    gotoPage($(this),tablenya);
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
                var statusnya = tablenya.row(this).data()[12];
                $('.ps-st').css('display', 'none');
                $('.st-' + statusnya).css('display', 'block');
            });

            $('#ps-lihat').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({ selected: true }).data()[0];
                window.open("{{ url('pengajuan') }}/"+transid);
            });

            $('.ps-approve').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({ selected: true }).data()[0];
                $('#frm-method').val('approve');
                $('#frm').attr('action',"{{ url('pengajuan') }}/"+transid).submit();
            });
            
            $('#ps-ubah').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({ selected: true }).data()[0];
                $('#frm-method').val('update');
                $('#frm').attr('action',"{{ url('pengajuan') }}/"+transid).submit();
            });
            
            $('#ps-hapus').click(function(e) {
                e.preventDefault();
                var transid = tablenya.row({ selected: true }).data()[0],
                    _token  = "{{ csrf_token() }}",
                    method  = "delete";
                Swal.fire({
                    title: 'Yakin ingin hapus?',
                    html: "Data <b>"+transid+"</b> tidak akan dapat dikembalikan.",
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
                                    data: {transid,_token,method,catatan},
                                    type: "POST",
                                    success: function(d){
                                        console.log('response :',d);
                                        d = d.responseJSON;
                                        Swal.fire(
                                            'Berhasil!',
                                            d.message,
                                            'success'
                                        ).then(function() {
                                            reloadTable();
                                        });
                                    },
                                    error:function(d){
                                        console.log('response :',d);
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
                var transid = tablenya.row({ selected: true }).data()[0],
                    _token  = "{{ csrf_token() }}",
                    method  = "rollback";
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    html: "Data <b>"+transid+"</b> akan dikembalikan ke status sebelumnya.",
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
                                    data: {transid,_token,method,catatan},
                                    type: "POST",
                                    success: function(d){
                                        console.log('response :',d);
                                        Swal.fire(
                                            'Berhasil!',
                                            d.message,
                                            'success'
                                        ).then(function() {
                                            reloadTable();
                                        });
                                    },
                                    error:function(d){
                                        console.log('response :',d);
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
                var transid = tablenya.row({ selected: true }).data()[0];
                window.open("{{ url('cetak_invoice') }}/"+transid);
            });

            $('a').click(function() {
                $('#text-inquiry').click();
            })
        });
    </script>
@endsection

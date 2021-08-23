@extends('layouts.master')
@section('title', 'Inquiry')
@section('breadcrumb', 'Inquiry')
@section('menu', 'Inquiry')
@section('content')
    <h2 class="intro-y text-lg font-medium mt-5">
        Inquiry
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <div class="hidden md:block mx-auto text-gray-600">Showing 1 to 10 of 150 entries</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-gray-700 dark:text-gray-300">
                    <input type="text" class="form-control w-56 box pr-10 placeholder-theme-13" placeholder="Search...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">No. App</th>
                        <th class="whitespace-nowrap">Tipe Asuransi</th>
                        <th class="whitespace-nowrap">Tertanggung</th>
                        <th class="whitespace-nowrap">No. Polis</th>
                        <th class="whitespace-nowrap">Tanggal Dibuat</th>
                        <th class="whitespace-nowrap">Nilai Pertanggungan</th>
                        <th class="whitespace-nowrap">Status</th>
                        <th class="whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>123456789</td>
                        <td>Property All Risk</td>
                        <td>Bpk. Sulaiman</td>
                        <td>489876543214456</td>
                        <td>1 Jan 2021</td>
                        <td>Rp. 9.000.000.000</td>
                        <td>Tertunda</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center text-theme-1 mr-3" href="javascript:;"> <i
                                        data-feather="check-square" class="w-4 h-4 mr-1"></i> Ajukan </a>
                                <a class="flex items-center mr-3" href="javascript:;"> <i data-feather="edit"
                                        class="w-4 h-4 mr-1"></i> Ubah </a>
                                <a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal"
                                    data-target="#delete-confirmation-modal"> <i data-feather="trash-2"
                                        class="w-4 h-4 mr-1"></i> Hapus </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>123456789</td>
                        <td>Property All Risk</td>
                        <td>Bpk. Sulaiman</td>
                        <td>489876543214456</td>
                        <td>1 Jan 2021</td>
                        <td>Rp. 9.000.000.000</td>
                        <td>Tertunda</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center text-theme-1 mr-3" href="javascript:;"> <i
                                        data-feather="check-square" class="w-4 h-4 mr-1"></i> Ajukan </a>
                                <a class="flex items-center mr-3" href="javascript:;"> <i data-feather="edit"
                                        class="w-4 h-4 mr-1"></i> Ubah </a>
                                <a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal"
                                    data-target="#delete-confirmation-modal"> <i data-feather="trash-2"
                                        class="w-4 h-4 mr-1"></i> Hapus </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>123456789</td>
                        <td>Property All Risk</td>
                        <td>Bpk. Sulaiman</td>
                        <td>489876543214456</td>
                        <td>1 Jan 2021</td>
                        <td>Rp. 9.000.000.000</td>
                        <td>Diajukan</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="javascript:;"> <i data-feather="search"
                                        class="w-4 h-4 mr-1"></i> Lihat </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>123456789</td>
                        <td>Property All Risk</td>
                        <td>Bpk. Sulaiman</td>
                        <td>489876543214456</td>
                        <td>1 Jan 2021</td>
                        <td>Rp. 9.000.000.000</td>
                        <td>Diajukan</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="javascript:;"> <i data-feather="search"
                                        class="w-4 h-4 mr-1"></i> Lihat </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>123456789</td>
                        <td>Property All Risk</td>
                        <td>Bpk. Sulaiman</td>
                        <td>489876543214456</td>
                        <td>1 Jan 2021</td>
                        <td>Rp. 9.000.000.000</td>
                        <td>Verifikasi</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center mr-3" href="javascript:;"> <i data-feather="search"
                                            class="w-4 h-4 mr-1"></i> Lihat </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>123456789</td>
                        <td>Fire Insurance (PSAKI)</td>
                        <td>Bpk. Sulaiman</td>
                        <td>489876543214456</td>
                        <td>1 Jan 2021</td>
                        <td>Rp. 9.000.000.000</td>
                        <td>Verifikasi</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center mr-3" href="javascript:;"> <i data-feather="search"
                                            class="w-4 h-4 mr-1"></i> Lihat </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>123456789</td>
                        <td>Fire Insurance (PSAKI)</td>
                        <td>Bpk. Sulaiman</td>
                        <td>489876543214456</td>
                        <td>1 Jan 2021</td>
                        <td>Rp. 9.000.000.000</td>
                        <td>Disetujui</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center text-theme-1 mr-3" href="javascript:;"> <i
                                        data-feather="check-square" class="w-4 h-4 mr-1"></i> Aktifkan </a>
                                <a class="flex items-center mr-3" href="javascript:;"> <i data-feather="search"
                                        class="w-4 h-4 mr-1"></i> Lihat </a>
                                <a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal"
                                    data-target="#delete-confirmation-modal"> <i data-feather="rotate-ccw"
                                        class="w-4 h-4 mr-1"></i> Kembalikan </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>123456789</td>
                        <td>Fire Insurance (PSAKI)</td>
                        <td>Bpk. Sulaiman</td>
                        <td>489876543214456</td>
                        <td>1 Jan 2021</td>
                        <td>Rp. 9.000.000.000</td>
                        <td>Aktif</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center text-theme-1 mr-3" href="javascript:;"> <i
                                        data-feather="file-text" class="w-4 h-4 mr-1"></i> Invoice </a>
                                <a class="flex items-center mr-3" href="javascript:;"> <i data-feather="search"
                                        class="w-4 h-4 mr-1"></i> Lihat </a>
                                <a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal"
                                    data-target="#delete-confirmation-modal"> <i data-feather="rotate-ccw"
                                        class="w-4 h-4 mr-1"></i> Kembalikan </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>123456789</td>
                        <td>Fire Insurance (PSAKI)</td>
                        <td>Bpk. Sulaiman</td>
                        <td>489876543214456</td>
                        <td>1 Jan 2021</td>
                        <td>Rp. 9.000.000.000</td>
                        <td>Dibayar</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="javascript:;"> <i data-feather="search"
                                        class="w-4 h-4 mr-1"></i> Lihat </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>123456789</td>
                        <td>Fire</td>
                        <td>Bpk. Sulaiman</td>
                        <td>489876543214456</td>
                        <td>1 Jan 2021</td>
                        <td>Rp. 9.000.000.000</td>
                        <td>Ditolak</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="javascript:;"> <i data-feather="search"
                                        class="w-4 h-4 mr-1"></i> Lihat </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            <ul class="pagination">
                <li>
                    <a class="pagination__link" href=""> <i class="w-4 h-4" data-feather="chevrons-left"></i> </a>
                </li>
                <li>
                    <a class="pagination__link" href=""> <i class="w-4 h-4" data-feather="chevron-left"></i> </a>
                </li>
                <li> <a class="pagination__link" href="">...</a> </li>
                <li> <a class="pagination__link" href="">1</a> </li>
                <li> <a class="pagination__link pagination__link--active" href="">2</a> </li>
                <li> <a class="pagination__link" href="">3</a> </li>
                <li> <a class="pagination__link" href="">...</a> </li>
                <li>
                    <a class="pagination__link" href=""> <i class="w-4 h-4" data-feather="chevron-right"></i> </a>
                </li>
                <li>
                    <a class="pagination__link" href=""> <i class="w-4 h-4" data-feather="chevrons-right"></i> </a>
                </li>
            </ul>
            <select class="w-20 form-select box mt-3 sm:mt-0">
                <option>10</option>
                <option>25</option>
                <option>35</option>
                <option>50</option>
            </select>
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
                        <div class="text-3xl mt-5">Are you sure?</div>
                        <div class="text-gray-600 mt-2">
                            Do you really want to delete these records?
                            <br>
                            This process cannot be undone.
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
    <!-- END: Delete Confirmation Modal -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection

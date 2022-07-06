@extends('layouts.master')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('menu', 'Dashboard')
@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: General Report -->
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Statistik Data<br>
                        </h2>
                        <a id="segarkan" class="ml-auto flex items-center text-theme-1 dark:text-theme-10 cursor-pointer"> <i class="fa fa-sync-alt w-4 h-4 mr-3"></i> Segarkan Data </a>
                    </div>
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5" onclick="filterInquiry('pengajuan')">
                                    <div class="flex">
                                        <i class="fa fa-file-medical text-theme-10" style="font-size: 40px"></i>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6 text-right" id="angka-pengajuan"><i class="fas fa-spinner fa-pulse"></i></div>
                                    <div class="text-base text-gray-600 mt-1" id="text-pengajuan">Pengajuan Bank</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5" onclick="filterInquiry('verifikasi')">
                                    <div class="flex">
                                        <i class="fa fa-tasks text-theme-11" style="font-size: 40px"></i>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6 text-right" id="angka-verifikasi"><i class="fas fa-spinner fa-pulse"></i></div>
                                    <div class="text-base text-gray-600 mt-1" id="text-approval">Verifikasi Broker</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5" onclick="filterInquiry('persetujuan asuransi')">
                                    <div class="flex">
                                        <i class="fa fa-check text-theme-11" style="font-size: 40px"></i>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6 text-right" id="angka-asuransi"><i class="fas fa-spinner fa-pulse"></i></div>
                                    <div class="text-base text-gray-600 mt-1">Persetujuan Asuransi</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5" onclick="filterInquiry('persetujuan bank')">
                                    <div class="flex">
                                        <i class="fa fa-check-double text-theme-11" style="font-size: 40px"></i>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6 text-right" id="angka-bank"><i class="fas fa-spinner fa-pulse"></i></div>
                                    <div class="text-base text-gray-600 mt-1">Persetujuan Bank</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5" onclick="filterInquiry('menunggu ftc')">
                                    <div class="flex">
                                        <i class="fa fa-money-check-alt text-theme-12" style="font-size: 40px"></i>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6 text-right" id="angka-tagihan"><i class="fas fa-spinner fa-pulse"></i></div>
                                    <div class="text-base text-gray-600 mt-1">Menunggu FTC</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5" onclick="filterInquiry('tagihan')">
                                    <div class="flex">
                                        <i class="fa fa-money-check-alt text-theme-12" style="font-size: 40px"></i>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6 text-right" id="angka-tagihan"><i class="fas fa-spinner fa-pulse"></i></div>
                                    <div class="text-base text-gray-600 mt-1">Tagihan Premi</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5" onclick="filterInquiry('dibayar bank')">
                                    <div class="flex">
                                        <i class="fa fa-hand-holding-usd text-theme-12" style="font-size: 40px"></i>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6 text-right" id="angka-dibayar-bank"><i class="fas fa-spinner fa-pulse"></i></div>
                                    <div class="text-base text-gray-600 mt-1">Premi Dibayar Bank</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5" onclick="filterInquiry('dibayar broker')">
                                    <div class="flex">
                                        <i class="fa fa-hand-holding-usd text-theme-9" style="font-size: 40px"></i>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6 text-right" id="angka-dibayar-broker"><i class="fas fa-spinner fa-pulse"></i></div>
                                    <div class="text-base text-gray-600 mt-1">Premi Dibayar Broker</div>
                                </div>
                            </div>
                        </div>
						<div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5" onclick="filterInquiry('pengecekan polis')">
                                    <div class="flex">
                                        <i class="fa fa-search text-theme-9" style="font-size: 40px"></i>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6 text-right" id="angka-pengecekan-polis"><i class="fas fa-spinner fa-pulse"></i></div>
                                    <div class="text-base text-gray-600 mt-1">Pengecekan Polis</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5" onclick="filterInquiry('polis siap')">
                                    <div class="flex">
                                        <i class="fa fa-file-invoice text-theme-9" style="font-size: 40px"></i>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6 text-right" id="angka-polis"><i class="fas fa-spinner fa-pulse"></i></div>
                                    <div class="text-base text-gray-600 mt-1">Polis SIAP</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5" onclick="filterInquiry('covernote batal')">
                                    <div class="flex">
                                        <i class="fa fa-exclamation-triangle text-theme-6" style="font-size: 40px"></i>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6 text-right" id="angka-batal"><i class="fas fa-spinner fa-pulse"></i></div>
                                    <div class="text-base text-gray-600 mt-1">Cover Note Dibatalkan</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="frm" method="GET">
        <input type="hidden" id="frm-data" name="data">
    </form>
@endsection

@section('script')
    <script>
        function filterInquiry(type) {
            console.log(type);
            $('#frm-data').val(type);
            $('#frm').attr('action','{{ url('inquiry') }}').submit();
        }
        function reloadDashboard() {
            $.ajax({
                url : "{{ url('api/datadashboard') }}",
                type: "GET",
                data: {"id":123},
                headers: {
                    'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                },
                success: function(d) {
                    console.log(d);
                    $('#angka-pengajuan').text(d.Pengajuan);
                    $('#angka-verifikasi').text(d.Verifikasi);
                    $('#angka-asuransi').text(d.Asuransi);
                    $('#angka-bank').text(d.Bank);
                    $('#angka-MenungguFTC').text(d.Tagihan);
                    $('#angka-tagihan').text(d.Tagihan);
                    $('#angka-dibayar-bank').text(d.DibayarBank);
                    $('#angka-dibayar-broker').text(d.DibayarBroker);
                    $('#angka-pengecekan-polis').text(d.PengecekanPolis);
                    $('#angka-polis').text(d.Polis);
                    $('#angka-batal').text(d.Batal);
                    // console.log('success: ',d);
                },
                error: function(d) {
                    console.log('error: ',d);
                },
            });
        }

        $(document).ready(function() {
            reloadDashboard();
            $('#segarkan').click(function(e){
                $('#segarkan i').toggleClass('fa-spin');
                e.preventDefault();
                $.when( reloadDashboard() ).done(function() {
                    $('#segarkan i').toggleClass('fa-spin');
                });
            });
            
            $(window).focus(function(){
                reloadDashboard();
            });
        });
    </script>
@endsection
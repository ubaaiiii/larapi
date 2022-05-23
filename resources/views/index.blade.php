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
                {{-- <div class="col-span-12 xl:col-span-4 mt-6">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Penutupan Asuransi
                        </h2>
                    </div>
                    <div class="mt-5">
                        <div class="intro-y">
                            <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                <div class="w-10 h-10 flex-none image-fit rounded-md overflow-hidden">
                                    <i class="fa fa-file-medical text-theme-10" style="font-size: 40px"></i>
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">Pengajuan Bank</div>
                                    <div class="text-slate-500 text-xs mt-0.5">100 Data</div>
                                </div>
                                <div class="py-1 px-2 rounded-full text-xs bg-success cursor-pointer font-medium">IDR 1.000.000.000</div>
                            </div>
                        </div>
                        <div class="intro-y">
                            <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                <div class="w-10 h-10 flex-none image-fit rounded-md overflow-hidden">
                                    <i class="fa fa-tasks text-theme-11" style="font-size: 40px"></i>
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">Verifikasi Broker</div>
                                    <div class="text-slate-500 text-xs mt-0.5">100 Data</div>
                                </div>
                                <div class="py-1 px-2 rounded-full text-xs bg-success cursor-pointer font-medium">IDR 1.000.000.000</div>
                            </div>
                        </div>
                        <div class="intro-y">
                            <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                <div class="w-10 h-10 flex-none image-fit rounded-md overflow-hidden">
                                    <i class="fa fa-check text-theme-11" style="font-size: 40px"></i>
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">Persetujuan Asuransi</div>
                                    <div class="text-slate-500 text-xs mt-0.5">100 Data</div>
                                </div>
                                <div class="py-1 px-2 rounded-full text-xs bg-success cursor-pointer font-medium">IDR 1.000.000.000</div>
                            </div>
                        </div>
                        <div class="intro-y">
                            <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                <div class="w-10 h-10 flex-none image-fit rounded-md overflow-hidden">
                                    <i class="fa fa-check-double text-theme-11" style="font-size: 40px"></i>
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">Persetujuan Bank</div>
                                    <div class="text-slate-500 text-xs mt-0.5">100 Data</div>
                                </div>
                                <div class="py-1 px-2 rounded-full text-xs bg-success cursor-pointer font-medium">IDR 1.000.000.000</div>
                            </div>
                        </div>
                        <div class="intro-y">
                            <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                <div class="w-10 h-10 flex-none image-fit rounded-md overflow-hidden">
                                    <i class="fa fa-money-check-alt text-theme-12" style="font-size: 40px"></i>
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">Tagihan Premi</div>
                                    <div class="text-slate-500 text-xs mt-0.5">100 Data</div>
                                </div>
                                <div class="py-1 px-2 rounded-full text-xs bg-success cursor-pointer font-medium">IDR 1.000.000.000</div>
                            </div>
                        </div>
                        <div class="intro-y">
                            <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                <div class="w-10 h-10 flex-none image-fit rounded-md overflow-hidden">
                                    <i class="fa fa-hand-holding-usd text-theme-12" style="font-size: 40px"></i>
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">Premi Dibayar Bank</div>
                                    <div class="text-slate-500 text-xs mt-0.5">100 Data</div>
                                </div>
                                <div class="py-1 px-2 rounded-full text-xs bg-success cursor-pointer font-medium">IDR 1.000.000.000</div>
                            </div>
                        </div>
                        <div class="intro-y">
                            <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                <div class="w-10 h-10 flex-none image-fit rounded-md overflow-hidden">
                                    <i class="fa fa-hand-holding-usd text-theme-9" style="font-size: 40px"></i>
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">Premi Dibayar Broker</div>
                                    <div class="text-slate-500 text-xs mt-0.5">100 Data</div>
                                </div>
                                <div class="py-1 px-2 rounded-full text-xs bg-success cursor-pointer font-medium">IDR 1.000.000.000</div>
                            </div>
                        </div>
                        <div class="intro-y">
                            <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                <div class="w-10 h-10 flex-none image-fit rounded-md overflow-hidden">
                                    <i class="fa fa-file-invoice text-theme-9" style="font-size: 40px"></i>
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">Polis SIAP</div>
                                    <div class="text-slate-500 text-xs mt-0.5">100 Data</div>
                                </div>
                                <div class="py-1 px-2 rounded-full text-xs bg-success cursor-pointer font-medium">IDR 1.000.000.000</div>
                            </div>
                        </div>
                        <div class="intro-y">
                            <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                <div class="w-10 h-10 flex-none image-fit rounded-md overflow-hidden">
                                    <i class="fa fa-exclamation-triangle text-theme-6" style="font-size: 40px"></i>
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">Cover Note Dibatalkan</div>
                                    <div class="text-slate-500 text-xs mt-0.5">100 Data</div>
                                </div>
                                <div class="py-1 px-2 rounded-full text-xs bg-success cursor-pointer font-medium">IDR 1.000.000.000</div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!-- END: General Report -->
                <!-- BEGIN: Sales Report -->
                {{-- <div class="col-span-12 lg:col-span-6 mt-8">
                    <div class="intro-y block sm:flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Sales Report
                        </h2>
                        <div class="sm:ml-auto mt-3 sm:mt-0 relative text-gray-700 dark:text-gray-300">
                            <i data-feather="calendar" class="w-4 h-4 z-10 absolute my-auto inset-y-0 ml-3 left-0"></i>
                            <input type="text" class="datepicker form-control sm:w-56 box pl-10">
                        </div>
                    </div>
                    <div class="intro-y box p-5 mt-12 sm:mt-5">
                        <div class="flex flex-col xl:flex-row xl:items-center">
                            <div class="flex">
                                <div>
                                    <div class="text-theme-19 dark:text-gray-300 text-lg xl:text-xl font-medium">$15,000
                                    </div>
                                    <div class="mt-0.5 text-gray-600 dark:text-gray-600">This Month</div>
                                </div>
                                <div
                                    class="w-px h-12 border border-r border-dashed border-gray-300 dark:border-dark-5 mx-4 xl:mx-5">
                                </div>
                                <div>
                                    <div class="text-gray-600 dark:text-gray-600 text-lg xl:text-xl font-medium">$10,000
                                    </div>
                                    <div class="mt-0.5 text-gray-600 dark:text-gray-600">Last Month</div>
                                </div>
                            </div>
                            <div class="dropdown xl:ml-auto mt-5 xl:mt-0">
                                <button class="dropdown-toggle btn btn-outline-secondary font-normal" aria-expanded="false">
                                    Filter by Category <i data-feather="chevron-down" class="w-4 h-4 ml-2"></i> </button>
                                <div class="dropdown-menu w-40">
                                    <div class="dropdown-menu__content box dark:bg-dark-1 p-2 overflow-y-auto h-32"> <a
                                            href=""
                                            class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">PC
                                            & Laptop</a> <a href=""
                                            class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">Smartphone</a>
                                        <a href=""
                                            class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">Electronic</a>
                                        <a href=""
                                            class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">Photography</a>
                                        <a href=""
                                            class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">Sport</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="report-chart">
                            <canvas id="report-line-chart" height="169" class="mt-6"></canvas>
                        </div>
                    </div>
                </div>
                <!-- END: Sales Report -->
                <!-- BEGIN: Weekly Top Seller -->
                <div class="col-span-12 sm:col-span-6 lg:col-span-3 mt-8">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Weekly Top Seller
                        </h2>
                        <a href="" class="ml-auto text-theme-1 dark:text-theme-10 truncate">Show More</a>
                    </div>
                    <div class="intro-y box p-5 mt-5">
                        <canvas class="mt-3" id="report-pie-chart" height="300"></canvas>
                        <div class="mt-8">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-theme-11 rounded-full mr-3"></div>
                                <span class="truncate">17 - 30 Years old</span>
                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden">
                                </div>
                                <span class="font-medium xl:ml-auto">62%</span>
                            </div>
                            <div class="flex items-center mt-4">
                                <div class="w-2 h-2 bg-theme-1 rounded-full mr-3"></div>
                                <span class="truncate">31 - 50 Years old</span>
                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden">
                                </div>
                                <span class="font-medium xl:ml-auto">33%</span>
                            </div>
                            <div class="flex items-center mt-4">
                                <div class="w-2 h-2 bg-theme-12 rounded-full mr-3"></div>
                                <span class="truncate">>= 50 Years old</span>
                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden">
                                </div>
                                <span class="font-medium xl:ml-auto">10%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Weekly Top Seller -->
                <!-- BEGIN: Sales Report -->
                <div class="col-span-12 sm:col-span-6 lg:col-span-3 mt-8">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Sales Report
                        </h2>
                        <a href="" class="ml-auto text-theme-1 dark:text-theme-10 truncate">Show More</a>
                    </div>
                    <div class="intro-y box p-5 mt-5">
                        <canvas class="mt-3" id="report-donut-chart" height="300"></canvas>
                        <div class="mt-8">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-theme-11 rounded-full mr-3"></div>
                                <span class="truncate">17 - 30 Years old</span>
                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden">
                                </div>
                                <span class="font-medium xl:ml-auto">62%</span>
                            </div>
                            <div class="flex items-center mt-4">
                                <div class="w-2 h-2 bg-theme-1 rounded-full mr-3"></div>
                                <span class="truncate">31 - 50 Years old</span>
                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden">
                                </div>
                                <span class="font-medium xl:ml-auto">33%</span>
                            </div>
                            <div class="flex items-center mt-4">
                                <div class="w-2 h-2 bg-theme-12 rounded-full mr-3"></div>
                                <span class="truncate">>= 50 Years old</span>
                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden">
                                </div>
                                <span class="font-medium xl:ml-auto">10%</span>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!-- END: Sales Report -->
            </div>
        </div>
        {{-- <div class="col-span-12 2xl:col-span-3">
            <div class="2xl:border-l border-theme-5 -mb-10 pb-10">
                <div class="2xl:pl-6 grid grid-cols-12 gap-6">
                    <!-- BEGIN: Transactions -->
                    <div class="col-span-12 md:col-span-6 xl:col-span-4 2xl:col-span-12 mt-3 2xl:mt-8">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">
                                Transactions
                            </h2>
                        </div>
                        <div class="mt-5">
                            <div class="intro-x">
                                <div class="box px-5 py-3 mb-3 flex items-center zoom-in">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                        <img alt="Rubick Tailwind HTML Admin Template" src="dist/images/profile-12.jpg">
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium">Robert De Niro</div>
                                        <div class="text-gray-600 text-xs mt-0.5">24 September 2021</div>
                                    </div>
                                    <div class="text-theme-9">+$126</div>
                                </div>
                            </div>
                            <div class="intro-x">
                                <div class="box px-5 py-3 mb-3 flex items-center zoom-in">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                        <img alt="Rubick Tailwind HTML Admin Template" src="dist/images/profile-2.jpg">
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium">Tom Cruise</div>
                                        <div class="text-gray-600 text-xs mt-0.5">24 June 2022</div>
                                    </div>
                                    <div class="text-theme-6">-$41</div>
                                </div>
                            </div>
                            <div class="intro-x">
                                <div class="box px-5 py-3 mb-3 flex items-center zoom-in">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                        <img alt="Rubick Tailwind HTML Admin Template" src="dist/images/profile-12.jpg">
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium">Johnny Depp</div>
                                        <div class="text-gray-600 text-xs mt-0.5">21 November 2020</div>
                                    </div>
                                    <div class="text-theme-6">-$50</div>
                                </div>
                            </div>
                            <div class="intro-x">
                                <div class="box px-5 py-3 mb-3 flex items-center zoom-in">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                        <img alt="Rubick Tailwind HTML Admin Template" src="dist/images/profile-11.jpg">
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium">Morgan Freeman</div>
                                        <div class="text-gray-600 text-xs mt-0.5">26 September 2020</div>
                                    </div>
                                    <div class="text-theme-6">-$48</div>
                                </div>
                            </div>
                            <div class="intro-x">
                                <div class="box px-5 py-3 mb-3 flex items-center zoom-in">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                        <img alt="Rubick Tailwind HTML Admin Template" src="dist/images/profile-14.jpg">
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium">Robert De Niro</div>
                                        <div class="text-gray-600 text-xs mt-0.5">9 March 2021</div>
                                    </div>
                                    <div class="text-theme-6">-$57</div>
                                </div>
                            </div>
                            <a href=""
                                class="intro-x w-full block text-center rounded-md py-3 border border-dotted border-theme-15 dark:border-dark-5 text-theme-16 dark:text-gray-600">View
                                More</a>
                        </div>
                    </div>
                    <!-- END: Transactions -->
                    <!-- BEGIN: Recent Activities -->
                    <div class="col-span-12 md:col-span-6 xl:col-span-4 2xl:col-span-12 mt-3">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">
                                Recent Activities
                            </h2>
                            <a href="" class="ml-auto text-theme-1 dark:text-theme-10 truncate">Show More</a>
                        </div>
                        <div class="report-timeline mt-5 relative">
                            <div class="intro-x relative flex items-center mb-3">
                                <div class="report-timeline__image">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                        <img alt="Rubick Tailwind HTML Admin Template" src="dist/images/profile-11.jpg">
                                    </div>
                                </div>
                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in">
                                    <div class="flex items-center">
                                        <div class="font-medium">Morgan Freeman</div>
                                        <div class="text-xs text-gray-500 ml-auto">07:00 PM</div>
                                    </div>
                                    <div class="text-gray-600 mt-1">Has joined the team</div>
                                </div>
                            </div>
                            <div class="intro-x relative flex items-center mb-3">
                                <div class="report-timeline__image">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                        <img alt="Rubick Tailwind HTML Admin Template" src="dist/images/profile-6.jpg">
                                    </div>
                                </div>
                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in">
                                    <div class="flex items-center">
                                        <div class="font-medium">Al Pacino</div>
                                        <div class="text-xs text-gray-500 ml-auto">07:00 PM</div>
                                    </div>
                                    <div class="text-gray-600">
                                        <div class="mt-1">Added 3 new photos</div>
                                        <div class="flex mt-2">
                                            <div class="tooltip w-8 h-8 image-fit mr-1 zoom-in"
                                                title="Sony Master Series A9G">
                                                <img alt="Rubick Tailwind HTML Admin Template"
                                                    class="rounded-md border border-white"
                                                    src="dist/images/preview-13.jpg">
                                            </div>
                                            <div class="tooltip w-8 h-8 image-fit mr-1 zoom-in" title="Dell XPS 13">
                                                <img alt="Rubick Tailwind HTML Admin Template"
                                                    class="rounded-md border border-white"
                                                    src="dist/images/preview-11.jpg">
                                            </div>
                                            <div class="tooltip w-8 h-8 image-fit mr-1 zoom-in"
                                                title="Sony Master Series A9G">
                                                <img alt="Rubick Tailwind HTML Admin Template"
                                                    class="rounded-md border border-white" src="dist/images/preview-9.jpg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="intro-x text-gray-500 text-xs text-center my-4">12 November</div>
                            <div class="intro-x relative flex items-center mb-3">
                                <div class="report-timeline__image">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                        <img alt="Rubick Tailwind HTML Admin Template" src="dist/images/profile-3.jpg">
                                    </div>
                                </div>
                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in">
                                    <div class="flex items-center">
                                        <div class="font-medium">Angelina Jolie</div>
                                        <div class="text-xs text-gray-500 ml-auto">07:00 PM</div>
                                    </div>
                                    <div class="text-gray-600 mt-1">Has changed <a class="text-theme-1 dark:text-theme-10"
                                            href="">Apple MacBook Pro 13</a> price and description</div>
                                </div>
                            </div>
                            <div class="intro-x relative flex items-center mb-3">
                                <div class="report-timeline__image">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                        <img alt="Rubick Tailwind HTML Admin Template" src="dist/images/profile-4.jpg">
                                    </div>
                                </div>
                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in">
                                    <div class="flex items-center">
                                        <div class="font-medium">Leonardo DiCaprio</div>
                                        <div class="text-xs text-gray-500 ml-auto">07:00 PM</div>
                                    </div>
                                    <div class="text-gray-600 mt-1">Has changed <a class="text-theme-1 dark:text-theme-10"
                                            href="">Nike Tanjun</a> description</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Recent Activities -->
                    <!-- BEGIN: Important Notes -->
                    <div
                        class="col-span-12 md:col-span-6 xl:col-span-12 xl:col-start-1 xl:row-start-1 2xl:col-start-auto 2xl:row-start-auto mt-3">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-auto">
                                Important Notes
                            </h2>
                            <button data-carousel="important-notes" data-target="prev"
                                class="tiny-slider-navigator btn px-2 border-gray-400 text-gray-700 dark:text-gray-300 mr-2">
                                <i data-feather="chevron-left" class="w-4 h-4"></i> </button>
                            <button data-carousel="important-notes" data-target="next"
                                class="tiny-slider-navigator btn px-2 border-gray-400 text-gray-700 dark:text-gray-300 mr-2">
                                <i data-feather="chevron-right" class="w-4 h-4"></i> </button>
                        </div>
                        <div class="mt-5 intro-x">
                            <div class="box zoom-in">
                                <div class="tiny-slider" id="important-notes">
                                    <div class="p-5">
                                        <div class="text-base font-medium truncate">Lorem Ipsum is simply dummy text</div>
                                        <div class="text-gray-500 mt-1">20 Hours ago</div>
                                        <div class="text-gray-600 text-justify mt-1">Lorem Ipsum is simply dummy text of
                                            the printing and typesetting industry. Lorem Ipsum has been the industry's
                                            standard dummy text ever since the 1500s.</div>
                                        <div class="font-medium flex mt-5">
                                            <button type="button" class="btn btn-secondary py-1 px-2">View Notes</button>
                                            <button type="button"
                                                class="btn btn-outline-secondary py-1 px-2 ml-auto ml-auto">Dismiss</button>
                                        </div>
                                    </div>
                                    <div class="p-5">
                                        <div class="text-base font-medium truncate">Lorem Ipsum is simply dummy text</div>
                                        <div class="text-gray-500 mt-1">20 Hours ago</div>
                                        <div class="text-gray-600 text-justify mt-1">Lorem Ipsum is simply dummy text of
                                            the printing and typesetting industry. Lorem Ipsum has been the industry's
                                            standard dummy text ever since the 1500s.</div>
                                        <div class="font-medium flex mt-5">
                                            <button type="button" class="btn btn-secondary py-1 px-2">View Notes</button>
                                            <button type="button"
                                                class="btn btn-outline-secondary py-1 px-2 ml-auto ml-auto">Dismiss</button>
                                        </div>
                                    </div>
                                    <div class="p-5">
                                        <div class="text-base font-medium truncate">Lorem Ipsum is simply dummy text</div>
                                        <div class="text-gray-500 mt-1">20 Hours ago</div>
                                        <div class="text-gray-600 text-justify mt-1">Lorem Ipsum is simply dummy text of
                                            the printing and typesetting industry. Lorem Ipsum has been the industry's
                                            standard dummy text ever since the 1500s.</div>
                                        <div class="font-medium flex mt-5">
                                            <button type="button" class="btn btn-secondary py-1 px-2">View Notes</button>
                                            <button type="button"
                                                class="btn btn-outline-secondary py-1 px-2 ml-auto ml-auto">Dismiss</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Important Notes -->
                    <!-- BEGIN: Schedules -->
                    <div
                        class="col-span-12 md:col-span-6 xl:col-span-4 2xl:col-span-12 xl:col-start-1 xl:row-start-2 2xl:col-start-auto 2xl:row-start-auto mt-3">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">
                                Schedules
                            </h2>
                            <a href="" class="ml-auto text-theme-1 dark:text-theme-10 truncate flex items-center"> <i
                                    data-feather="plus" class="w-4 h-4 mr-1"></i> Add New Schedules </a>
                        </div>
                        <div class="mt-5">
                            <div class="intro-x box">
                                <div class="p-5">
                                    <div class="flex">
                                        <i data-feather="chevron-left" class="w-5 h-5 text-gray-600"></i>
                                        <div class="font-medium text-base mx-auto">April</div>
                                        <i data-feather="chevron-right" class="w-5 h-5 text-gray-600"></i>
                                    </div>
                                    <div class="grid grid-cols-7 gap-4 mt-5 text-center">
                                        <div class="font-medium">Su</div>
                                        <div class="font-medium">Mo</div>
                                        <div class="font-medium">Tu</div>
                                        <div class="font-medium">We</div>
                                        <div class="font-medium">Th</div>
                                        <div class="font-medium">Fr</div>
                                        <div class="font-medium">Sa</div>
                                        <div class="py-0.5 rounded relative text-gray-600">29</div>
                                        <div class="py-0.5 rounded relative text-gray-600">30</div>
                                        <div class="py-0.5 rounded relative text-gray-600">31</div>
                                        <div class="py-0.5 rounded relative">1</div>
                                        <div class="py-0.5 rounded relative">2</div>
                                        <div class="py-0.5 rounded relative">3</div>
                                        <div class="py-0.5 rounded relative">4</div>
                                        <div class="py-0.5 rounded relative">5</div>
                                        <div class="py-0.5 bg-theme-18 dark:bg-theme-9 rounded relative">6</div>
                                        <div class="py-0.5 rounded relative">7</div>
                                        <div class="py-0.5 bg-theme-1 dark:bg-theme-1 text-white rounded relative">8</div>
                                        <div class="py-0.5 rounded relative">9</div>
                                        <div class="py-0.5 rounded relative">10</div>
                                        <div class="py-0.5 rounded relative">11</div>
                                        <div class="py-0.5 rounded relative">12</div>
                                        <div class="py-0.5 rounded relative">13</div>
                                        <div class="py-0.5 rounded relative">14</div>
                                        <div class="py-0.5 rounded relative">15</div>
                                        <div class="py-0.5 rounded relative">16</div>
                                        <div class="py-0.5 rounded relative">17</div>
                                        <div class="py-0.5 rounded relative">18</div>
                                        <div class="py-0.5 rounded relative">19</div>
                                        <div class="py-0.5 rounded relative">20</div>
                                        <div class="py-0.5 rounded relative">21</div>
                                        <div class="py-0.5 rounded relative">22</div>
                                        <div class="py-0.5 bg-theme-17 dark:bg-theme-11 rounded relative">23</div>
                                        <div class="py-0.5 rounded relative">24</div>
                                        <div class="py-0.5 rounded relative">25</div>
                                        <div class="py-0.5 rounded relative">26</div>
                                        <div class="py-0.5 bg-theme-14 dark:bg-theme-12 rounded relative">27</div>
                                        <div class="py-0.5 rounded relative">28</div>
                                        <div class="py-0.5 rounded relative">29</div>
                                        <div class="py-0.5 rounded relative">30</div>
                                        <div class="py-0.5 rounded relative text-gray-600">1</div>
                                        <div class="py-0.5 rounded relative text-gray-600">2</div>
                                        <div class="py-0.5 rounded relative text-gray-600">3</div>
                                        <div class="py-0.5 rounded relative text-gray-600">4</div>
                                        <div class="py-0.5 rounded relative text-gray-600">5</div>
                                        <div class="py-0.5 rounded relative text-gray-600">6</div>
                                        <div class="py-0.5 rounded relative text-gray-600">7</div>
                                        <div class="py-0.5 rounded relative text-gray-600">8</div>
                                        <div class="py-0.5 rounded relative text-gray-600">9</div>
                                    </div>
                                </div>
                                <div class="border-t border-gray-200 dark:border-dark-5 p-5">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-theme-11 rounded-full mr-3"></div>
                                        <span class="truncate">UI/UX Workshop</span>
                                        <div
                                            class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden">
                                        </div>
                                        <span class="font-medium xl:ml-auto">23th</span>
                                    </div>
                                    <div class="flex items-center mt-4">
                                        <div class="w-2 h-2 bg-theme-1 dark:bg-theme-10 rounded-full mr-3"></div>
                                        <span class="truncate">VueJs Frontend Development</span>
                                        <div
                                            class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden">
                                        </div>
                                        <span class="font-medium xl:ml-auto">10th</span>
                                    </div>
                                    <div class="flex items-center mt-4">
                                        <div class="w-2 h-2 bg-theme-12 rounded-full mr-3"></div>
                                        <span class="truncate">Laravel Rest API</span>
                                        <div
                                            class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden">
                                        </div>
                                        <span class="font-medium xl:ml-auto">31th</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Schedules -->
                </div>
            </div>
        </div> --}}
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
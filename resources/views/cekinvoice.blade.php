<!DOCTYPE html>
<html lang="en" class="light">
<!-- BEGIN: Head -->

<head>
  <meta charset="utf-8">
  <link href="{{ url('public/dist/images/logo.svg') }}" rel="shortcut icon">
  <title>Cek Invoice | BDS General</title>
  <!-- BEGIN: CSS Assets-->
  <link rel="stylesheet" href="{{ url('public/dist/css/app.css') }}" />
  <!-- END: CSS Assets-->
</head>
<!-- END: Head -->

<body class="main">
  <!-- BEGIN: Mobile Menu -->
  <div class="mobile-menu md:hidden">
    <div class="mobile-menu-bar">
      <a href="" class="flex mr-auto">
        <img alt="Rubick Tailwind HTML Admin Template" class="w-6" src="{{ url('public/dist/images/logo.svg') }}">
      </a>
    </div>
  </div>
  <!-- END: Mobile Menu -->
  <!-- BEGIN: Top Bar -->
  <div class="border-b border-theme-29 -mt-10 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 pt-3 md:pt-0 mb-10">
    <div class="top-bar-boxed flex items-center">
      <!-- BEGIN: Logo -->
      <a href="{{ url("") }}" class="-intro-x hidden md:flex">
        <img alt="Rubick Tailwind HTML Admin Template" class="w-6" src="{{ url('public/dist/images/logo.svg') }}">
        <span class="text-white text-lg ml-3"> <span class="font-medium">BDS</span> General</span>
      </a>
      <!-- END: Logo -->
    </div>
  </div>
  <!-- END: Top Bar -->
  <!-- BEGIN: Content -->
  <div class="content">
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
      <h2 class="text-lg font-medium mr-auto">
        Cek Invoice
      </h2>
    </div>
    <!-- BEGIN: Invoice -->
    <div class="intro-y box overflow-hidden mt-5">
      @if(empty($data))
        <div class="border-b border-gray-200 dark:border-dark-5 text-center sm:text-left">
          <div class="px-5 py-10 sm:px-20 sm:py-20">
            <div class="text-theme-1 dark:text-theme-10 font-semibold text-3xl">INVOICE TIDAK DITEMUKAN</div>
            <div class="mt-2">Transaksi ID <span class="font-medium">#<a href="{{ url('pengajuan',$id) }}">{{ $id }}</a></span> </div>
            <div class="alert alert-danger-soft show flex items-center mt-3" role="alert"> <i data-feather="alert-octagon" class="w-6 h-6 mr-2"></i> Harap cek kembali Transaksi ID yang dipindai. </div>
          </div>
        </div>
      @else
        <div class="border-b border-gray-200 dark:border-dark-5 text-center sm:text-left">
          <div class="px-5 py-10 sm:px-20 sm:py-20">
            <div class="text-theme-1 dark:text-theme-10 font-semibold text-3xl">INVOICE</div>
            <div class="alert alert-primary-soft show flex items-center mb-2" role="alert"> <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> Harap menyertakan Transaksi ID saat akan transfer. </div>
            <div class="mt-2">Transaksi ID <span class="font-medium">#<a href="{{ url('pengajuan',$id) }}">{{ $id }}</a></span> </div>
            <div class="mt-1">{TGL_PRODUKSI}</div>
          </div>
          <div class="flex flex-col lg:flex-row px-5 sm:px-20 pt-10 pb-10 sm:pb-20">
            <div>
              <div class="text-base text-gray-600">Ditagihkan Kepada</div>
              <div class="text-lg font-medium text-theme-1 dark:text-theme-10 mt-2">PT. BANK KB BUKOPIN, TBK.</div>
              <div class="mt-1">CAB. {CABANG}</div>
              <div class="mt-1">{ALAMAT_CABANG}</div>
            </div>
            <div class="lg:text-right mt-10 lg:mt-0 lg:ml-auto">
              <div class="text-base text-gray-600">Ditagih Oleh</div>
              <div class="text-lg font-medium text-theme-1 dark:text-theme-10 mt-2">PT. BINA DANA SEJAHTERA</div>
              <div class="mt-1">
                <b>Ruko Sentradana Kalimalang</b><br>
                Jl. Seulawah Raya No.B Jakarta Timur - 13620<br>
                Telp: +62 21 22 32 20 32. Fax: +62 21 22 32 20 17<br>
                www.bdspt.com<br>
                No. Keanggotaan: 083/APPARINDO/2003</div>
            </div>
          </div>
        </div>
        <div class="px-5 sm:px-16 py-10 sm:py-20">
          <div class="overflow-x-auto">
            <table class="table">
              <thead>
                <tr>
                  <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">DESKRIPSI</th>
                  <th class="border-b-2 dark:border-dark-5 text-right whitespace-nowrap">HARGA</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="border-b dark:border-dark-5">
                    <div class="font-medium whitespace-nowrap">Premium</div>
                    <div class="text-gray-600 text-sm mt-0.5 whitespace-nowrap">Biaya pengalihan risiko dari Pemegang Polis kepada Penyedia Asuransi</div>
                  </td>
                  <td class="text-right border-b dark:border-dark-5 w-32 font-medium">{PREMI}</td>
                </tr>
                <tr>
                  <td class="border-b dark:border-dark-5">
                    <div class="font-medium whitespace-nowrap">Biaya Polis</div>
                    <div class="text-gray-600 text-sm mt-0.5 whitespace-nowrap">Biaya atas pencetakan / pembuatan Polis</div>
                  </td>
                  <td class="text-right border-b dark:border-dark-5 w-32 font-medium">{POLIS}</td>
                </tr>
                <tr>
                  <td class="border-b dark:border-dark-5">
                    <div class="font-medium whitespace-nowrap">Biaya Materai</div>
                    <div class="text-gray-600 text-sm mt-0.5 whitespace-nowrap">Biaya atas pembelian materai</div>
                  </td>
                  <td class="text-right border-b dark:border-dark-5 w-32 font-medium">{MATERAI}</td>
                </tr>
                <tr>
                  <td class="border-b dark:border-dark-5">
                    <div class="font-medium whitespace-nowrap">Biaya Admin</div>
                    <div class="text-gray-600 text-sm mt-0.5 whitespace-nowrap">Biaya administrasi yang telah disepakati sebelumnya</div>
                  </td>
                  <td class="text-right border-b dark:border-dark-5 w-32 font-medium">{ADMIN}</td>
                </tr>
                <tr>
                  <td>
                    <div class="font-medium whitespace-nowrap">Biaya Lain</div>
                    <div class="text-gray-600 text-sm mt-0.5 whitespace-nowrap">Biaya lain-lain yang ditentukan dalam Polis</div>
                  </td>
                  <td class="text-right w-32 font-medium">{LAIN}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="px-5 sm:px-20 pb-10 sm:pb-20 flex flex-col-reverse sm:flex-row">
          <div class="text-center sm:text-left mt-10 sm:mt-0">
            <div class="text-base text-gray-600">Bank Transfer</div>
            <div class="text-lg text-theme-1 dark:text-theme-10 font-medium mt-2">PT. BINA DANA SEJAHTERA</div>
            <div class="mt-1">No. Rekening : {REKENING}</div>
          </div>
          <div class="text-center sm:text-right sm:ml-auto">
            <div class="text-base text-gray-600">Gross Net</div>
            <div class="text-xl text-theme-1 dark:text-theme-10 font-medium mt-2">{NET}</div>
            {{-- <div class="mt-1 tetx-xs">Sudah termasuk pajak</div> --}}
          </div>
        </div>
      @endif
    </div>
    <!-- END: Invoice -->
  </div>
  <!-- END: Content -->
  <!-- BEGIN: JS Assets-->
  <script src="{{ url('public/dist/js/app.js') }}"></script>
  <!-- END: JS Assets-->
</body>

</html>
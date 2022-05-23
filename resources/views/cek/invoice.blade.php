<!DOCTYPE html>
<html lang="en" class="light">
<!-- BEGIN: Head -->

<head>
  <meta charset="utf-8">
  <link href="{{ url('public/dist/images/logo.svg') }}" rel="shortcut icon">
  <title>Cek Invoice | BDS General</title>
  <!-- BEGIN: CSS Assets-->
  <link rel="stylesheet" href="{{ url('public/dist/css/app.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ url('public/vendor/fontawesome/all.css') }}" />
  <!-- END: CSS Assets-->
  <style>
    .stamp {
      transform: rotate(12deg);
      color: #555;
      font-size: 6rem;
      font-weight: 700;
      border: 0.25rem solid #555;
      display: inline-block;
      padding: 0.25rem 1rem;
      text-transform: uppercase;
      border-radius: 1rem;
      font-family: 'Courier';
      -webkit-mask-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/8399/grunge.png');
      -webkit-mask-size: 944px 604px;
      mix-blend-mode: multiply;
    }
    .is-lunas {
      color: #1CAC4D;
      border: 1rem double #1CAC4D;
      transform: rotate(-5deg);
      font-size: 4rem;
      font-family: "Open sans", Helvetica, Arial, sans-serif;
      border-radius: 0;
      padding: 1rem;
      /* position:fixed;
      margin-left: -200px;
      margin-top: -10px; */
    } 
    .is-batal {
      color: #FF0000;
      border: 1rem double #FF0000;
      transform: rotate(-5deg);
      font-size: 4rem;
      font-family: "Open sans", Helvetica, Arial, sans-serif;
      border-radius: 0;
      padding: 1rem;
      /* position:fixed;
      margin-left: -200px;
      margin-top: -10px; */
    } 
  </style>
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
            <div class="mt-2">Transaksi ID <span class="font-medium">#<a href="{{ url('pengajuan',$id) }}">{{ $id }} <i data-feather="external-link" class="w-6 h-6 mr-2"></i></a></span> </div>
            <div class="alert alert-danger-soft show flex items-center mt-3" role="alert"> <i data-feather="alert-octagon" class="w-6 h-6 mr-2"></i> Harap cek kembali QRCode yang dipindai. </div>
          </div>
        </div>
      @else
        <div class="border-b border-gray-200 dark:border-dark-5 text-center sm:text-left">
          <div class="px-5 py-10 sm:px-20 sm:py-20">
            <div class="text-theme-1 dark:text-theme-10 font-semibold text-3xl">INVOICE</div>
            @if (!empty($pembayaran))
              @php
                $date = date_create($pembayaran->paid_at);
              @endphp
              <div class="alert alert-success-soft show flex items-center mb-2" role="alert"> <i data-feather="check-circle" class="w-6 h-6 mr-2"></i> Pembayaran telah diterima pada tanggal : {{ FunctionsHelp::tgl_indo($date->format('Y-m-d')) }} </div>
            @else
              @if ($data->id_status == 18)
			  	@php
                  $date = date_create($data->billing_at);
                  date_add($date,date_interval_create_from_date_string("30 days"));
                @endphp
                <div class="alert alert-danger-soft show flex items-center mb-2" role="alert"> <i data-feather="alert-octagon" class="w-6 h-6 mr-2"></i> Transaksi DIBATALKAN karena melebihi tanggal jatuh tempo ( {{ FunctionsHelp::tgl_indo($date->format('Y-m-d')) }} ) belum ada pembayaran. </div>
              @else
                <div class="alert alert-primary-soft show flex items-center mb-2" role="alert"> <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> Harap menyertakan catatan pembayaran seperti berikut saat akan transfer :<br>PEMB_PREMI {{ $id." A/N ".$data->nama_insured." NO. COVER NOTE: ".$data->cover_note }} </div>
                @php
                  $date = date_create($data->billing_at);
                  date_add($date,date_interval_create_from_date_string("14 days"));
                @endphp
                <div class="alert alert-warning-soft show flex items-center mb-2" role="alert"> <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> Harap membayar sebelum tanggal jatuh tempo : <br>{{ FunctionsHelp::tgl_indo($date->format('Y-m-d')) }} ( 14 hari dari tanggal invoice ) </div>
              @endif
            @endif
            <div class="mt-2">Transaksi ID <span class="font-medium">#<a href="{{ url('pengajuan',$id) }}">{{ $id }} <i data-feather="external-link" class="w-4 h-4 mb-1"></i></a></span> </div>
            <div class="mt-1">Tanggal Invoice: {{ FunctionsHelp::tgl_indo($data->billing_at) }}</div>
            <a href="{{ url($dokumen->lokasi_file) }}" target="dokumen_covernote" class="btn btn-sm btn-elevated-rounded-primary w-24 mr-1 mt-2"><i class="fa fa-file-download mr-2"></i>Download</a><br>
          </div>
          <div class="flex flex-col lg:flex-row px-5 sm:px-20">
            <div>
              <div class="text-base text-gray-600">Ditagihkan Kepada</div>
              <div class="text-lg font-medium text-theme-1 dark:text-theme-10 mt-2">PT. BANK KB BUKOPIN, TBK.</div>
              <div class="mt-1">CAB. {{ $data->nama_cabang }}</div>
              <div class="mt-1">{{ $data->alamat_cabang }}</div>
            </div>
            <div class="lg:text-right mt-10 lg:mt-0 lg:ml-auto">
              <div class="text-base text-gray-600">Ditagih Oleh</div>
              <div class="text-lg font-medium text-theme-1 dark:text-theme-10 mt-2">PT. BINA DANA SEJAHTERA</div>
              <div class="mt-1">
                <b>Ruko Sentradana Kalimalang</b><br>
                Jl. Seulawah Raya No.B Jakarta Timur - 13620<br>
                Telp: +62 21 22 32 20 32. Fax: +62 21 22 32 20 17
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
                  <td class="text-right border-b dark:border-dark-5 w-32 font-medium">{{ number_format($pricing[2]->value,2) }}</td>
                </tr>
                <tr>
                  <td class="border-b dark:border-dark-5">
                    <div class="font-medium whitespace-nowrap">Biaya Polis</div>
                    <div class="text-gray-600 text-sm mt-0.5 whitespace-nowrap">Biaya atas pencetakan / pembuatan Polis</div>
                  </td>
                  <td class="text-right border-b dark:border-dark-5 w-32 font-medium">{{ number_format($pricing[10]->value,2) }}</td>
                </tr>
                <tr>
                  <td class="border-b dark:border-dark-5">
                    <div class="font-medium whitespace-nowrap">Biaya Materai</div>
                    <div class="text-gray-600 text-sm mt-0.5 whitespace-nowrap">Biaya atas pembelian materai</div>
                  </td>
                  <td class="text-right border-b dark:border-dark-5 w-32 font-medium">{{ number_format($pricing[11]->value,2) }}</td>
                </tr>
                <tr style="border-bottom: 3pt double black;">
                  <td>
                    <div class="font-medium whitespace-nowrap">Biaya Lain</div>
                    <div class="text-gray-600 text-sm mt-0.5 whitespace-nowrap">Biaya lain-lain yang ditentukan dalam Polis</div>
                  </td>
                  <td class="text-right w-32 font-medium">{{ number_format($pricing[16]->value,2) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="px-5 sm:px-20 pb-10 sm:pb-20 flex flex-col-reverse sm:flex-row">
          <div class="text-center sm:text-left mt-10 sm:mt-0">
            <div class="text-base text-gray-600">Bank Transfer</div>
            <div class="text-lg text-theme-1 dark:text-theme-10 font-medium mt-2">PT. BINA DANA SEJAHTERA</div>
            <div class="mt-1">
              PT. Bank KB Bukopin Capem Bulog II, Jakarta
              <br>No. Rekening: <b>101.5266.011 (IDR)  </b></div>
          </div>
          <div class="text-center sm:text-right sm:ml-auto">
            <div class="text-base text-gray-600">Gross Net</div>
            <div class="text-xl text-theme-1 dark:text-theme-10 font-medium mt-2">{{ number_format($pricing[18]->value,2) }}</div>
            {{-- <div class="mt-1 tetx-xs">Sudah termasuk pajak</div> --}}
            @if (!empty($pembayaran))
            <span class="stamp is-lunas">LUNAS</span>
            @endif
            @if (!empty($data) && $data->id_status == 18)
            <span class="stamp is-batal">KADALUARSA</span>
            @endif
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
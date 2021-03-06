<style>
  /* @page { 
    size: 18.5cm 16.5cm;
  } */
  body {
    font-family: Arial, Helvetica, sans-serif;
    margin:-20px -20px;
  }
  table {
    width: 100%;
  }
  .gbawah {
    border-bottom: 1px solid black !important;
  }
  .tright {
    text-align: right;
  }
</style>
@foreach ($data['asuransi'] as $asuransi)
  <body>
    <table style="font-size:6" width="100%">
      <tr>
        <td><img src="{{ public_path('dist/images/Logo BDS insurance SIUP.png') }}" width="35%"></td>
        <td style="text-align: right">
          <b>Ruko Sentradana Kalimalang</b><br>
          Jl. Seulawah Raya No.B<br>
          Jakarta Timur - 13620<br>
          Telp: +62 21 22 32 20 32. Fax: +62 21 22 32 20 17<br>
          www.bdspt.com<br>
          No. Keanggotaan: 083/APPARINDO/2003
        </td>
      </tr>
      <tr>
        <td colspan="2" style="border-bottom: 1px solid #238bcc !important;">&nbsp;</td>
      </tr>
    </table>
    <table width="100%">
      <tr>
        <td width="65%" style="font-size:11">
          <b>PT. BANK KB BUKOPIN, TBK.<br>
            CAB {{ $data['cabang']->nama_cabang }}</b><br>
            {{ $data['cabang']->alamat_cabang }}
        </td>
        <td align="right" width="auto" style="font-size:8">
          <table>
            <tr>
              <td>Tanggal Cetak</td><td>:</td><td>{{ FunctionsHelp::tgl_indo(date('Y-m-d')) }}</td>
            </tr>
            <tr>
              <td>ID Trasaksi</td><td>:</td><td>{{ $data['transaksi']->transid }}</td>
            </tr>
            <tr>
              <td>Mata Uang</td><td>:</td><td>IDR (Rupiah)</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <table width="100%">
      <tr>
        <td colspan="4" class="gbawah" style="font-size: 11;text-align: center;"><b>INVOICE</b></td>
      </tr>
      <tr>
        <table style="font-size: 8;">
          <tr>
            <td width="25%">TIPE ASURANSI</td>
            <td width="44%">{{ $data['instype']->instype_name }}</td>
            <td width="13%">Premium</td>
            <td width="17%" class="tright">{{ number_format($data['pricing'][2]->value,2) }}</td>
          </tr>
          <tr>
            <td>ASURANSI</td>
            <td>{{ $asuransi->nama_asuransi }}</td>
            <td>By. Polis</td>
            <td class="tright">{{ number_format($data['pricing'][10]->value,2) }}</td>
          </tr>
          <tr>
            <td>NO. COVERNOTE</td>
            <td>{{ $data['transaksi']->cover_note }}</td>
            <td>By. Materai</td>
            <td class="tright">{{ number_format($data['pricing'][11]->value,2) }}</td>
          </tr>
          <tr>
            <td>PERIODE POLIS</td>
            <td>{{ FunctionsHelp::tgl_indo($data['transaksi']->polis_start) ." - ". FunctionsHelp::tgl_indo($data['transaksi']->polis_end) }}</td>
            {{-- <td>{{ date_format(date_create($data['transaksi']->polis_start),"d F Y") }} - {{ date_format(date_create($data['transaksi']->polis_end),"d F Y") }}</td> --}}
            <td>By. Lain</td>
            <td class="tright gbawah">{{ number_format($data['pricing'][16]->value,2) }}</td>
          </tr>
          <tr>
            <td>TERTANGGUNG</td>
            <td>QQ {{ $data['insured']->nama_insured }}</td>
            <td>Total Tagihan</td>
            <td class="tright">{{ number_format($data['pricing'][18]->value,2) }}</td>
          </tr>
          <tr>
            <td>OBJEK PERTANGGUNGAN</td>
            <td colspan="3">{{ $data['transaksi']->object }}</td>
          </tr>
          <tr>
            <td>TOTAL PERTANGGUNGAN</td>
            <td colspan="3">{{ number_format($data['pricing'][1]->value,2) }}</td>
          </tr>
          <tr>
            <td>LOKASI PERTANGGUNGAN</td>
            <td colspan="3">{{ $data['transaksi']->location }}</td>
          </tr>
          <tr>
            <td>TANGGAL JATUH TEMPO</td>
            @php
              $date = date_create($data['transaksi']->billing_at);
              date_add($date,date_interval_create_from_date_string("30 days"));
            @endphp
            <td colspan="3">
          {{ FunctionsHelp::tgl_indo($date->format('Y-m-d')) }} (30 Hari sejak <i>Cover Note</i> diterbitkan)<br>
          <b>*Transaksi akan hilang setelah tanggal jatuh tempo, harap input pengajuan kembali saat transaksi hilang.</b>
        </td>
          </tr>
        </table>
      </tr>
    </table>
    <table>
      <tr>
        <td valign="top" style="font-size: 8;" width="75%">
          <u><b>Notes</b></u><br>
          <br>
          <i>
            <b>
              Pembayaran ditransfer ke rekening PT. BINA DANA SEJAHTERA pada:<br>
              PT. Bank KB Bukopin Capem Bulog II, Jakarta No. 101.5266.011 (IDR Rupiah)
            </b>
          </i>
        </td>
        <td align="center" style="font-size: 9;">
          JAKARTA, {{ FunctionsHelp::tgl_indo($data['transaksi']->billing_at) }}<br>
          <u>PT. BINA DANA SEJAHTERA</u><br>
          <b id="test"></b>
          <br>
          {{-- <img src="data:image/png;base64, {!! $qrcode !!}"> --}}
          <img src="data:image/svg;base64, {!! $qrcode !!} ">
        </td>
      </tr>
    </table>
  </body>
@endforeach
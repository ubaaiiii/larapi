<html>
<head>
  <style>
    @page { margin: 100px 25px; }
    body { font-family: Arial, Helvetica, sans-serif; margin:20px 20px;}
    header { text-align:center; position: fixed; top: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
    footer { text-align:center; position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
    table { width: 100%; }
    table.main td { padding: 4px; }
  </style>
</head>
<body>
  <header>Header Asuransi</header>
  <footer>Footer Asuransi</footer>
  <main>
    <table>
      <tr>
        <td align="center" style="font-size:20; border-bottom: 1px solid black !important;"><strong>AKSEPTASI ASURANSI</strong></td>
      </tr>
    </table>
    <table style="font-size:10pt" class="main">
      <tr valign="top">
        <td width="32%">ID Transaksi</td>
        <td width="1%">:</td>
        <td width="67%">{{ $data['transaksi']->transid }}</td>
      </tr>
      <tr valign="top">
        <td>Nama Tertanggung</td>
        <td>:</td>
        <td>PT. BANK KB BUKOPIN, TBK. CAB. {{ $data['cabang']->nama_cabang }} QQ {{ $data['tertanggung']->nama_insured }}</td>
      </tr>
      <tr valign="top">
        <td>Alamat Pengiriman Polis</td>
        <td>:</td>
        <td>{{ $data['cabang']->alamat_cabang }}</td>
      </tr>
      <tr valign="top">
        <td>Nomor Telepon</td>
        <td>:</td>
        <td>+62 {{ $data['tertanggung']->nohp_insured }}</td>
      </tr>
      <tr valign="top">
        <td>Periode Pertanggungan</td>
        <td>:</td>
        <td>{{ FunctionsHelp::tgl_indo($data['transaksi']->polis_start) ." - ". FunctionsHelp::tgl_indo($data['transaksi']->polis_end) }}</td>
      </tr>
      <tr valign="top">
        <td>Jangka Waktu</td>
        <td>:</td>
        <td>{{ $data['transaksi']->masa }} hari / {{ $data['transaksi']->masa/365 }} tahun</td>
      </tr>
      <tr valign="top">
        <td>Objek Pertanggungan</td>
        <td>:</td>
        <td>{{ $data['transaksi']->object }}</td>
      </tr>
      <tr valign="top">
        <td>Lokasi Objek Pertanggungan</td>
        <td>:</td>
        <td>{{ $data['transaksi']->location }}</td>
      </tr>
      <tr valign="top">
        <td>Tipe Asuransi</td>
        <td>:</td>
        <td>
          {{ $data['instype']->instype_name }}
        </td>
      </tr>
      <tr valign="top">
        <td>Kode / Okupasi</td>
        <td>:</td>
        <td>{{ $data['okupasi']->kode_okupasi }} / {{ $data['okupasi']->nama_okupasi }}</td>
      </tr>
      <tr valign="top">
        <td><strong>Rate</strong></td>
        <td><strong>:</strong></td>
        <td><strong>{{ $data['okupasi']->rate }} ‰</strong></td>
      </tr>
      @if($data['instype']->id == "PAR")
      <tr valign="top">
        <td align="right">Sudah Termasuk</td>
        <td></td>
        <td>
          - <i>Riot, Strike, Malicious Damage, Civil Commotion / RSMDCC : 0.00001 ‰</i><br>
          - <i>Typhoon, Storm, Flood, dan Water Damage / TSFWD : 0.50000 ‰</i><br>
          - <i>Others : 0.00001 ‰</i>
        </td>
      </tr>
      @endif
      <tr valign="top">
        <td><strong>Total Nilai Pertanggungan</strong></td>
        <td>:</td>
        <td></td>
      </tr>
      <?php $i = 1 ?>
      @foreach ($data['tsi'] as $tsi)
      <tr valign="top">
        <td>- {{ $tsi->kodetrans_nama }}</td>
        <td>:</td>
        <td>Rp. {{ number_format($tsi->value,2) }}</td>
      </tr>
      @endforeach
      <tr valign="top">
        <td align="right"><strong>TOTAL</strong></td>
        <td><strong>:</strong></td>
        <td><strong>Rp. {{ number_format($data['pricing'][0]->value,2) }}</strong></td>
      </tr>
      <tr valign="top">
        <td><strong>Perhitungan Premi</strong></td>
        <td>:</td>
        <td></td>
      </tr>
    </table>
    <table style="font-size:10pt;font-weight:bold;">
      <tr>
        <td width="10%">&nbsp;&nbsp;Premi</td>
        <td width="5%">:</td>
        <td width="55%">Rp. {{ number_format($data['pricing'][0]->value,2) }} &nbsp;&nbsp;&nbsp;x {{ $data['okupasi']->rate }} ‰</td>
        <td width="6%" align="right">= Rp.</td>
        <td width="" align="right">{{ number_format($data['pricing'][1]->value,2) }}</td>
        <td width="1%"></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td>Biaya Materai + Admin + Polis</td>
        <td align="right">= Rp.</td>
        <td align="right" style="border-bottom: 1px solid black !important;">{{ number_format($data['pricing'][9]->value+$data['pricing'][10]->value+$data['pricing'][11]->value,2) }}</td>
        <td>+</td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td>Total Premi</td>
        <td align="right">= Rp.</td>
        <td align="right">{{ number_format($data['pricing'][18]->value,2) }}</td>
        <td></td>
      </tr>
    </table>
  </main>
</body>
</html>
<html>

<head>
  <style>
    @page {
      margin: 100px 25px;
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      margin: 20px 20px;
    }

    header {
      text-align: center;
      position: fixed;
      top: -60px;
      left: 0px;
      right: 0px;
      background-color: lightblue;
      height: 50px;
    }

    footer {
      text-align: center;
      position: fixed;
      bottom: -60px;
      left: 0px;
      right: 0px;
      background-color: lightblue;
      height: 50px;
    }

    table {
      width: 100%;
    }

    table.main td {
      padding: 4px;
    }

    li {
      list-style-type: 'square';
      padding-inline-start: 1ch;
    }
  </style>
</head>

<body>
  <header><img src="{{ url('public/dist/images/Header') }}/Header-KB.jpg"></header>
  <footer>Footer Asuransi</footer>
  <main>
    <table>
      <tr>
        <td align="center" style="font-size:20"><strong>COVER NOTE</strong></td>
      </tr>
      <tr>
        <td align="center" style="font-size:12"><strong><u>{{ $data['covernote']; }}</strong></u></td>
      </tr>
      <tr>
        <?php
        $date = date_create($data['tgl_aktif']->created_at);
        date_add($date, date_interval_create_from_date_string("30 days"));
        ?>
        <td align="center" style="font-size:8; border-bottom: 1px solid black !important;">*Masa berlaku Cover Note ini 30 hari dari tanggal persetujuan asuransi<br>menunggu pembayaran hingga {{ FunctionsHelp::tgl_indo($date->format('Y-m-d')) }}</td>
      </tr>
    </table>
    <table style="font-size:10pt" class="main">
      <tr valign="top">
        <td width="32%">ID Transaksi</td>
        <td width="1%">:</td>
        <td width="67%">{{ $data['transaksi']->transid }}</td>
      </tr>
      <tr valign="top">
        <td>Tipe Asuransi</td>
        <td>:</td>
        <td>
          {{ $data['instype']->instype_name }}
        </td>
      </tr>
      <tr valign="top">
        <td width="32%">Form</td>
        <td width="1%">:</td>
        <td width="67%">Munich Re wording (amended)</td>
      </tr>
      <tr valign="top">
        <td width="32%">Tertanggung</td>
        <td width="1%">:</td>
        <td width="67%">PT. BANK KB BUKOPIN, TBK. CAB. {{ $data['cabang']->nama_cabang }} QQ {{ $data['tertanggung']->nama_insured }}</td>
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
        <td>Lokasi Objek Pertanggungan</td>
        <td>:</td>
        <td>{{ $data['transaksi']->location }}</td>
      </tr>
      <tr valign="top">
        <td>Kode / Okupasi</td>
        <td>:</td>
        <td></td>
      </tr>
      <tr>
        <td colspan="3">
          <table border=1 style="border-collapse: collapse">
            <tr>
              <th>No.</th>
              <th>Keterangan</th>
              <th>Kode Okupasi</th>
              <th>Kelas Konstruksi 1<br>Rate ‰<br>(Tarif Bawah)</th>
            </tr>
            <tr>
              <th>1</th>
              <td>{{ $data['okupasi']->nama_okupasi }}</td>
              <td align="center">{{ $data['okupasi']->kode_okupasi }}</td>
              <td align="center">{{ $data['okupasi']->rate }}</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr valign="top">
        <td>Interest Insured</td>
        <td>:</td>
        <td style="text-align: justify;">Semua properti nyata dan pribadi dalam bentuk apa pun, sifat dan deskripsi dalam/atau
          tentang tempat yang dikenal sebagai bangunan tersebut di atas, termasuk bahan di tanah dan semua perlengkapan
          struktural, perbaikan, peningkatan, perlengkapan, peralatan, terikat dan termasuk di sini dan dinding luar, gerbang, pagar
          (termasuk struktur pendukung dan pelindung), perabotan lunak, mebel, perlengkapan dan peralatan, mesin dari
          setiap uraian, persediaan, Inventaris yang dimiliki oleh Tertanggung atau yang mungkin menjadi tanggung jawab
          Tertanggung atau akan menanggung tanggung jawab dan semua isi lainnya, yang dipegang oleh mereka dalam kepercayaan atau
          komisi atau yang menjadi tanggung jawab mereka. (Sesuai yang dinyatakan pada Uang Pertanggungan)</td>
      </tr>
      <tr valign="top">
        <td>Deductibles</td>
        <td>:</td>
        <td>
          <table>
            <tr valign="top">
              <td>-</td>
              <td>Fire, Lightning, Explosion, Aircraft Impact & Smoke ; NIL any one accident</td>
            </tr>
            <tr valign="top">
              <td>-</td>
              <td>Riot, Strike, Malicious Damage 4.1B/2007 : 10% of claim, minimum IDR 10.000.000,- any one accident</td>
            </tr>
            <tr valign="top">
              <td>-</td>
              <td>Typhoon, Storm, Flood & Water Damage (including landslide and subsidience); 10% of claim min IDR 10.000.000,- a.o.a.</td>
            </tr valign="top">
            <td>-</td>
            <td>Other Looses ; IDR 1.000.000,00 any one accident</td>
      </tr>
    </table>
    </td>
    </tr>
    <tr valign="top">
      <td><strong>Total Nilai Pertanggungan</strong></td>
      <td>:</td>
      <td><strong>Maksimal Rp. {{ number_format($data['instype']->max_tsi,2) }}</strong></td>
    </tr>
    <?php $i = 1 ?>
    @foreach ($data['tsi'] as $tsi)
    <tr valign="middle">
      <td>- {{ $tsi->kodetrans_nama }}</td>
      <td>:</td>
      <td>
        <table>
          <tr valign="top">
            <td>Rp.</td>
            <td>{{ number_format($tsi->value,2) }}</td>
          </tr>
        </table>
      </td>
    </tr>
    @endforeach
    <tr valign="middle">
      <td align="right"><strong>TOTAL</strong></td>
      <td><strong>:</strong></td>
      <td>
        <table>
          <tr>
            <td><strong>Rp.</strong></td>
            <td><strong>{{ number_format($data['pricing'][0]->value,2) }}</strong></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr valign="top">
      <td><strong>Klausa</strong></td>
      <td>:</td>
      <td></td>
    </tr>
    </table>
    <p style="font-size:10pt">
      - Klausul 72 Jam; hanya untuk Gempa, Letusan Gunung Berapi dan Tsunami<br>
      - Klausul Perubahan & Perbaikan; dalam 30 hari<br>
      - Semua Klausul Konten Lainnya; IDR 10.000.000/item & agregat IDR 250.000.000<br>
      - Klausul Penilaian; limit 5% dari Total Uang Pertanggungan<br>
      - Biaya Arsitek, Surveyor, dan Engineer (5% dari Uang Pertanggungan)<br>
      - Pengembalian Otomatis Klausul Uang Pertanggungan;<br>
      - Klausul Bantuan Rata-rata; 85%<br>
      - Tenda, Tirai, Tanda atau Perlengkapan Luar Ruangan Lainnya atau Klausul Pemasangan; batas Rp. 100.000.000,- a.o.a<br>
      - Klausul Bankir; i.f.o PT BANK KB BUKOPIN, TBK.<br>
      - Klausul Pembatalan (prorata); 30 hari<br>
      - Klausul Otoritas Sipil;<br>
      - Klausul Persiapan Klaim; Batasi 10% dari klaim<br>
      - Klausul Catatan Komputer; Limit Rp100.000.000,-<br>
      - Biaya Klausul Re-Erection; Batasi 10% dari klaim<br>
      - Klausul Biaya Penulisan Ulang Catatan; Batasi 10% dari klaim<br>
      - Klausul Mata Uang<br>
      - Klausul Pengecualian Risiko Cyber ​​NMA 2915<br>
      - Klausul Sengketa<br>
      - Klausul Kesalahan & Kelalaian;<br>
      - Klausul Pengecualian Kewajiban Kontrak Ekstra<br>
      - Klausul Pengenalan Tanggal Elektronik<br>
      - Klausul Retribusi Pemadam Kebakaran jika kurang dari Rp 50.000.000,- Tidak Perlu Tagihan<br>
      - Klausul Biaya Pemadaman Kebakaran; Limit Rp 50.000.000,- a.o.a<br>
      - Pengesahan Kerusakan Air Badai Angin Topan Banjir<br>
      - Klausul Kepentingan Umum;<br>
      - Dampak oleh Klausul Kendaraan Sendiri;<br>
      - Klausul Penghapusan Internal;<br>
      - Perjanjian Klarifikasi TI<br>
      - Klausul Klarifikasi Bahaya Teknologi Informasi NMA 2912<br>
      - Klausul Properti yang Disewakan<br>
      - Klausul Pemberitahuan Kerugian; 30 hari<br>
      - Perubahan Kecil dan Klausul Perbaikan;<br>
      - Klausul Non Pembatalan<br>
      - Klausul Pemberitahuan;<br>
      - Klausul Pengecualian Risiko Energi Nuklir<br>
      - Klausul Bangunan Luar;<br>
      - Pembayaran Pada Klausul Rekening; 25% dari klaim yang diajukan oleh tertanggung<br>
      - Klausul Klarifikasi Kerusakan Properti<br>
      - Klausul Otoritas Publik;<br>
      - Kerusuhan, Pemogokan, Kerusakan Berbahaya 4.1B/2007/AAUI Pengesahan; sesuai kata-kata DAI<br>
      - Klausul Layanan<br>
      - Klausul Kebocoran Sprinkler;<br>
      - Klausul Perubahan Struktural;<br>
      - Klausul Penghapusan Sementara;<br>
      - Klausul Pengecualian Jalur Transmisi dan Distribusi<br>
      - Pengesampingan Subrogasi; terhadap anak perusahaan saja<br>
      - Klausul Pembayaran Garansi (30 hari)<br>
      - Klausul Pekerja;<br>
      - Klausul Pengecualian Perang dan Perang Saudara<br>
      - Klausul Pengatur Kerugian yang Dinominasikan;<br>
      PT. Bahtera Arung Persada<br>
      PT. Radhita Hutama Internusa<br>
      PT. Bahtera Arung Persada<br>
      PT. Penyetel Primayasa Vaisha<br>
      - Pengecualian Terorisme & Sabotase Pengesahan NMA 2920
    </p>
    <table style="font-size: 10pt">
      <tr valign="top">
        <td><strong>Rate</strong></td>
        <td width="2%">:</td>
        <td width="65%">
          - <i>Fire, Lightning, Explosion, Aircraft Falling, Smoke</i><br>
          @if($data['instype']->id == "PAR")
          - <i>Riot, Strike, Malicious Damage, Civil Commotion / RSMDCC</i><br>
          - <i>Typhoon, Storm, Flood, dan Water Damage / TSFWD</i><br>
          - <i>Others</i>
          @endif
        </td>
        <td width="2%">
          :<br>
          @if($data['instype']->id == "PAR")
          :<br>
          :<br>
          :
          @endif
        </td>
        <td style="border-bottom: 1px solid black !important;">
          {{ $data['okupasi']->rate }} ‰<br>
          @php($total_rate = $data['okupasi']->rate)
          @if($data['instype']->id == "PAR")
          @php($total_rate = $data['okupasi']->rate + $data['kodepos']->rate_RSMDCC + $data['kodepos']->rate_TSFWD + $data['kodepos']->rate_OTHERS)
          {{ $data['kodepos']->rate_RSMDCC }} ‰<br>
          {{ $data['kodepos']->rate_TSFWD }} ‰<br>
          {{ $data['kodepos']->rate_OTHERS }} ‰
          @endif
        </td>
        <td>
          <br>
          <br>
          <br>
          +
        </td>
      </tr>
      <tr>
        <td colspan="3" align="right"><strong>TOTAL</strong></td>
        <td>:</td>
        <td><strong>{{ $total_rate }} ‰</strong></td>
      </tr>
    </table>
    <table style="font-size:10pt">
      <tr valign="top">
        <td><strong>Perhitungan Premi</strong></td>
        <td></td>
        <td></td>
      </tr>
    </table>
    <table style="font-size:10pt;font-weight:bold;">
      <tr>
        <td width="10%">&nbsp;&nbsp;Premi</td>
        <td width="5%">:</td>
        <td width="55%">Rp. {{ number_format($data['pricing'][0]->value,2) }} &nbsp;&nbsp;&nbsp;x {{ $total_rate }} ‰</td>
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
    <br>
    <br>
    <div style="font-size: 10pt">
      Demikian cover note ini dibuat, sementara polis asuransi <i>original</i> sedang dibuat dan menunggu pembayaran.
    </div>
    <br>
    <table style="font-size: 10pt">
      <tr>
        <td width="70%"></td>
        <td align="center">JAKARTA, {{ FunctionsHelp::tgl_indo($data['tgl_aktif']->created_at->format('Y-m-d')) }}</td>
      </tr>
      <tr>
        <td></td>
        <td align="center"><img src="data:image/png;base64, {!! $qrcode !!}"></td>
      </tr>
      <tr>
        <td></td>
        <td align="center">{{ $data['asuransi']->nama_asuransi }}</td>
      </tr>
    </table>
    {{-- <div style="font-size: 10pt">
      {!! $data['transaksi']->klausula !!}
    </div> --}}
  </main>
</body>

</html>
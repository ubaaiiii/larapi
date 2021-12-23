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
      /* background-color: lightblue; */
      height: 50px;
    }

    footer {
      text-align: center;
      position: fixed;
      bottom: -60px;
      left: 0px;
      right: 0px;
      /* background-color: lightblue; */
      height: 50px;
    }

    table {
      width: 100%;
    }

    table.main td {
      padding: 4px;
    }

    li {
      font-size:10pt;
    }

    p {
      margin: 0;
      font-size:10pt;
      padding: 0;
    }
  </style>
</head>

<body>
  <header><img src="{{ public_path('dist/images/Header') }}/Header-{{ $data['asuransi']->akronim }}.jpg" height="87 px" style="display:block; margin-top: -15;margin-left: auto;margin-right: auto;"></header>
  <footer><img src="{{ public_path('dist/images/Footer') }}/Footer-{{ $data['asuransi']->akronim }}.jpg" height="87 px" style="display:block; margin-top: -15;margin-left: auto;margin-right: auto;"></footer>
  <main>
    <table>
      <tr>
        <td align="center" style="font-size:20"><strong>AKSEPTASI ASURANSI</strong></td>
      </tr>
      <tr>
        <td align="center" style="font-size:8; border-bottom: 1px solid black !important;"></td>
      </tr>
    </table>
    <table style="font-size:10pt" class="main" CELLSPACING=0>
      <tr valign="top">
        <td width="32%">ID Aplikasi SIAP</td>
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
        @if($data['instype']->id == "PAR")
        <td width="67%">Munich Re wording (amended)</td>
        @else
        <td width="67%">FLEXAS (PSAKI)</td>
        @endif
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
        <td>{{ FunctionsHelp::tgl_indo($data['transaksi']->polis_start) ." s/d ". FunctionsHelp::tgl_indo($data['transaksi']->polis_end) }}</td>
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
        @if($data['instype']->id == "PAR")
        <td style="text-align: justify;">All real and personal property of any kind, nature and description in or
          about the premises known as the above mentioned building, including materials on the ground and all structural
          appurtunances, improvements, fixtures, fittings, attached and belonging here to and external wall, gates, fences
          (including supporting and protective structure) soft furnishing, furniture, fixtures and fitting, machineries of
          every description, stock, Inventory owned by the Insured or which the Insured may be liable or shall have assumed
          liability and all other contents, held by them in trust or on commission or for which they are responsible. (as per declare Sum Insured)
        </td>
        @else
        <td style="text-align: justify;">
          Building (class 1)
        </td>
        @endif
      </tr>
      <tr valign="top">
        <td>Deductibles</td>
        <td>:</td>
        <td>
          <table CELLSPACING=0>
            <tr valign="top">
              <td>-</td>
              <td>Fire, Lightning, Explosion, Aircraft Impact & Smoke ; {{ $data['okupasi']->deductible }}.</td>
            </tr>
            @if($data['instype']->id == "PAR")
            <tr valign="top">
              <td>-</td>
              <td>Riot, Strike, Malicious Damage 4.1B/2007 : 10% of claim, minimum IDR 10.000.000,- any one accident.</td>
            </tr>
            <tr valign="top">
              <td>-</td>
              <td>Typhoon, Storm, Flood & Water Damage (including landslide and subsidience); 10% of claim min IDR 10.000.000,- any one accident.</td>
            </tr valign="top">
            <td>-</td>
            <td>Other Looses ; IDR 1.000.000,- any one accident</td>
            @endif
      </tr>
    </table>
    </td>
    </tr>
    <tr>
      <td><strong>Total Nilai Pertanggungan</strong></td>
      <td>:</td>
      <td></td>
    </tr>
  </table>
  <table style="font-size:10pt">
    @foreach ($data['tsi'] as $tsi)
    <tr valign="middle">
      <td width="32%">&nbsp;&nbsp;&nbsp;- {{ $tsi->kodetrans_nama }}</td>
      <td width="1%">:</td>
      <td width="67%">
        <table cellspacing=0 style="width: 40% !important">
          <tr valign="top">
            <td>IDR</td>
            <td align="right">{{ number_format($tsi->value,2) }}</td>
          </tr>
        </table>
      </td>
    </tr>
    @endforeach
    <tr valign="middle">
      <td align="right"><strong>TOTAL</strong></td>
      <td><strong>:</strong></td>
      <td>
        <table cellspacing=0 style="width: 40% !important">
          <tr>
            <td><strong>IDR</strong></td>
            <td align="right" style="border-top: 1px solid black;"><strong>{{ number_format($data['pricing'][1]->value,2) }}</strong></td>
          </tr>
        </table>
      </td>
    </tr>
    </table>
    <br>
    <table style="font-size: 10pt" cellspacing=0>
      <tr valign="top">
        <td width="10%"><strong>Clauses</strong></td>
        <td>:</td>
        <td></td>
      </tr>
    </table>
    <div style="margin-left:15px !important" class="ql-editor">
      {!! $data['transaksi']->klausula !!}
    </div>
    <br>
    <table style="font-size: 10pt" cellspacing=0>
      <tr valign="top">
        <td><strong>Rate</strong></td>
        <td width="2%">:</td>
        <td width="65%">
          - <i>Fire, Lightning, Explosion, Aircraft Falling, Smoke (FLEXAS)</i><br>
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
        <td style="border-bottom: 1px solid black;">
          {{ $data['okupasi']->rate }} ‰<br>
          @php($total_rate = $data['okupasi']->rate)
          @if($data['instype']->id == "PAR")
          @php($total_rate = $data['okupasi']->rate + $data['kodepos']->rate_RSMDCC + $data['kodepos']->rate_TSFWD + $data['kodepos']->rate_OTHERS)
          {{ $data['kodepos']->rate_RSMDCC }} ‰<br>
          {{ $data['kodepos']->rate_TSFWD }} ‰<br>
          {{ $data['kodepos']->rate_OTHERS }} ‰
          @endif
        </td>
        <td style="vertical-align:bottom;">
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
        <td width="55%">IDR {{ number_format($data['pricing'][1]->value,2) }} &nbsp;&nbsp;&nbsp;x {{ $total_rate }} ‰</td>
        <td width="6%" align="right">= IDR</td>
        <td width="" align="right">{{ number_format($data['pricing'][2]->value,2) }}</td>
        <td width="1%"></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td>Biaya Materai</td>
        <td align="right">= IDR</td>
        <td align="right">{{ number_format($data['pricing'][10]->value,2) }}</td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td>Biaya Polis</td>
        <td align="right">= IDR</td>
        <td align="right" style="border-bottom: 1px solid black !important;">{{ number_format($data['pricing'][11]->value,2) }}</td>
        <td>+</td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td>Total Premi</td>
        <td align="right">= IDR</td>
        <td align="right">{{ number_format($data['pricing'][18]->value,2) }}</td>
        <td></td>
      </tr>
    </table>
  </main>
</body>
</html>
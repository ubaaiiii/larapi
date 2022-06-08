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
      bottom: -10px;
      left: 0px;
      right: 0px;
      /* background-color: lightblue; */
      height: 50px;
      z-index: -1;
    }

    table {
      width: 100%;
    }

    table.main td {
      padding: 4px;
    }

    table.tabel-objek td {
      padding: 8px;
    }

    table td, table th {
      word-wrap: break-word;
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
<body style="color:black">
  <header><img src="{{ public_path('dist/images/Header') }}/Header-BDS.jpg" height="100 px" style="display:block; margin-top: -15;margin-left: auto;margin-right: auto;"></header>
  <footer><img src="{{ public_path('dist/images/Footer') }}/Footer-BDS.png" height="143 px" style="display:block; margin-top: -15;margin-left: auto;margin-right: auto;"></footer>
  <main>
    <table>
      <tr>
        <td align="center" style="font-size:25"><strong>{{ $data['jenis'] }}</strong></td>
      </tr>
      <tr>
        <td align="center" style="font-size:11">No. {{ $data['nomor_surat']; }}</td>
      </tr>
      <tr>
        <td align="center" style="font-size:8; border-bottom: 1px solid black !important;"></td>
      </tr>
    </table>
    <table style="font-size:10pt" class="main" CELLSPACING=0>
      <tr valign="top">
        <td width="25%">DigiSIAP ID</td>
        <td width="1%">:</td>
        <td width="74%">{{ $data['transaksi']->transid }}</td>
      </tr>
      <tr valign="top">
        <td>Type of Insurance</td>
        <td>:</td>
        <td>
          {{ $data['instype']->instype_name }}
          {{-- @if (!empty($data['perluasan']))
            ; amended to include the following risk :<br>
            @foreach ($data['perluasan'] as $perluasan)
              - {{ $perluasan->keterangan }} ({{ $perluasan->kode }}) <br>
            @endforeach
          @endif --}}
        </td>
      </tr>
      <tr valign="top">
        <td>Form</td>
        <td>:</td>
        @if($data['instype']->id !== "FIRE")
        <td>Munich Re wording (amended)</td>
        @else
        <td>FLEXAS (PSAKI)</td>
        @endif
      </tr>
      <tr valign="top">
        <td>The Insured</td>
        <td>:</td>
        <td style="text-align: justify;"><b>PT. BANK KB BUKOPIN, TBK. CAB. {{ $data['cabang']->nama_cabang }} QQ {{ $data['tertanggung']->nama_insured }}</b>, being the principal insured and/or  its subsidiary, associated, or affiliated companies and/or other companies owned, operated, managed or controlled by the insured for their respective rights and interest</td>
      </tr>
      <tr valign="top">
        <td>The Insured Address</td>
        <td>:</td>
        <td>{{ $data['cabang']->alamat_cabang }}</td>
      </tr>
      <tr valign="top">
        <td>Phone Number</td>
        <td>:</td>
        <td>+62 {{ $data['tertanggung']->nohp_insured }}</td>
      </tr>
      <tr valign="top">
        <td>Period of Insurance</td>
        <td>:</td>
        <td>
          {{ date_format(date_create($data['transaksi']->polis_start), "d F Y") ." - to - ". date_format(date_create($data['transaksi']->polis_end), "d F Y") }}
          <br>
          (both dates inclusive at 12 o'clock noon)
        </td>
      </tr>
      <tr valign="top">
        <td>Insured Object</td>
        <td>:</td>
        <td></td>
      </tr>
      <tr>
        <td colspan="3">
          <table border=1 style="border-collapse: collapse;" class="tabel-objek">
            <tr>
              <th>No.</th>
              <th>Object</th>
              <th>Risk Location</th>
              <th>Occupation / Code</th>
            </tr>
            @php($i = 1)
            @foreach ($data['objek'] as $objek)
              <tr valign="top">
                <td align="center">{{ $i++ }}</td>
                <td>{{ $objek->objek }}</td>
                <td>{{ $objek->alamat_objek . " (" . $objek->no_jaminan . ") / " . $objek->kelurahan . ", " . $objek->kodepos }}</td>
                <td>{{ $objek->nama_okupasi . " / " . $objek->kode_okupasi  . " / " . $objek->nama_kelas }}</td>
              </tr>
            @endforeach
          </table>
        </td>
      </tr>
      <tr valign="top">
        <td><strong>Sum Insured</strong></td>
        <td>:</td>
        <td>
          <table style="font-size:10pt; width:80%;white-space: nowrap;">
            @php($id_objek_pricing = 0)
            @php($i = 1)
            @php($total_objek_pricing = count($data['objek_pricing']))
            @foreach ($data['objek_pricing'] as $row => $objek_pricing)
            <tr valign="top" style="padding: 0px;">
              <td width="5%"  style="padding: 2px;">@if ($objek_pricing->id_objek !== $id_objek_pricing) {{ $i }}. @endif</td>
              @php($id_objek_pricing = $objek_pricing->id_objek)
              <td width="45%" style="padding: 2px;">{{ $objek_pricing->kodetrans_nama }}</td>
              <td width="10%" style="padding: 2px;">IDR</td>
              <td width="35%" align="right" style="padding: 2px;">{{ number_format($objek_pricing->value,2) }}</td>
              <td width="5%" style="padding: 2px;">@if ($i++ == $total_objek_pricing) + @endif</td>
            </tr>
            @endforeach
            <tr valign="top">
              <td style="padding: 2px;"></td>
              <td align="right" style="padding: 2px;"><strong>TOTAL&nbsp;&nbsp;&nbsp;</strong></td>
              <td style="padding: 2px;">IDR</td>
              <td align="right" style="border-top: 1px solid black;padding: 2px;"><strong>{{ number_format($data['pricing'][1]->value,2) }}</strong></td>
              <td style="padding: 2px;"></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <br>
    <div style="margin-left:6px !important" class="ql-editor">
      {!! $data['transaksi']->klausula !!}
      @php($jml_installment = count($data['installment']))
      @php($i = 1)
      @if ($jml_installment > 1)
        <p class="mt-0">
          <table style="font-size:10pt;" width="50%">
            <tr>
              <td colspan=4 style="padding: 0px">Defferred Premium Clause (Installment {{ $jml_installment }} in {{ $jml_installment }} months)</td>
            </tr>
            @foreach ($data['installment'] as $installment)
              <tr>
                <td width="5%" style="padding: 0px">&nbsp;&nbsp;&nbsp;{{ $i }}.</td>
                <td width="15%" style="padding: 0px">Installment {{ FunctionsHelp::angka_romawi($i++) }}</td>
                <td width="2%" style="padding: 0px">:</td>
                <td style="padding: 0px">{{ date_format(date_create($installment->tgl_tagihan),"d F Y") }}</td>
              </tr>
            @endforeach
          </table>
        </p>
      @endif
    </div>
    <br>
    <table style="font-size:10pt;font-weight:bold; width: 100%;">
      <tr valign="top">
        <td width="13%">&nbsp;</td>
        <td width="2%"></td>
        <td width="4%"></td>
        <td width="56%"></td>
        <td width="6%"></td>
        <td width="3%"></td>
        <td width="14%"></td>
        <td width="2%"></td>
      </tr>
      <tr valign="top">
        <td>Rate (per annum)</td>
        <td>:</td>
        <td colspan="6"><i><u>{{ $data['instype']->instype_name }} :</u></i></td>
      </tr>
      @foreach ($data['objek'] as $row_objek => $objek)
        <tr valign="top">
          <td></td>
          <td></td>
          <td>{{ $row_objek + 1 }}.</td>
          <td colspan="2"><b><i>{{ $objek->nama_okupasi }} ({{ $objek->kode_okupasi }}) ({{ $objek->nama_kelas }})</i></b></td>
          <td align="right">:</td>
          <td align="right" colspan="2">{{ $objek->rate }} ‰</td>
        </tr>
        @if (isset($data['perluasan']) && count($data['perluasan']) > 0)
          <tr valign="top" style="font-weight:normal">
            <td></td>
            <td></td>
            <td></td>
            <td colspan="5"><u><i>Expansions :</i></u></td>
          </tr>
            @foreach ($data['perluasan'] as $row => $perluasan)
              @if ($perluasan->id_objek == $objek->objek_id)
                <tr valign="top" style="font-weight:normal">
                  <td></td>
                  <td></td>
                  <td align="right">-</td>
                  <td colspan="2" style="overflow-wrap: anywhere;"><i>(<b>{{ $perluasan->kode }}</b>) {{ $perluasan->keterangan }}</i></td>
                  <td align="right">:</td>
                  <td align="right" colspan="2">{{ $perluasan->rate }} ‰</td>
                </tr>
              @endif
            @endforeach
          @endif
      @endforeach
      <tr>
        <td colspan="8">&nbsp;</td>
      </tr>
      @foreach ($data['objek'] as $row_objek => $objek)
        @php($TSI_objek = 0)
        @foreach ($data['objek_pricing'] as $objek_pricing)
          @if($objek->objek_id == $objek_pricing->id_objek)
            @php($TSI_objek += $objek_pricing->value);
          @endif
        @endforeach
        <tr>
          <td>@if($row_objek == 0)Premi @endif</td>
          <td>@if($row_objek == 0): @endif</td>
          <td>{{ $row_objek + 1 }}.</td>
          <td>Premi =&nbsp;&nbsp;IDR {{ number_format($TSI_objek,2) }} &nbsp;&nbsp;x {{ $objek->rate }} ‰</td>
          <td align="right">= IDR</td>
          <td align="right" colspan="2">{{ number_format($TSI_objek * $objek->rate / 1000,2) }}</td>
          <td></td>
        </tr>
        @if (!empty($data['perluasan']))
          @foreach ($data['perluasan'] as $row => $perluasan)
            @if ($perluasan->id_objek == $objek->objek_id)
              <tr valign="top">
                <td></td>
                <td></td>
                <td align="right">-</td>
                <td>{{ $perluasan->kode }} =&nbsp;&nbsp;IDR {{ number_format($perluasan->value,2) }} &nbsp;&nbsp;x {{ $perluasan->rate }} ‰</td>
                <td align="right">= IDR</td>
                <td align="right" colspan="2">{{ number_format($perluasan->value * $perluasan->rate / 1000,2) }}</td>
                <td></td>
              </tr>
            @endif
          @endforeach
        @endif
      @endforeach
      <tr>
        <td></td>
        <td></td>
        <td colspan="2">Biaya Materai</td>
        <td align="right">= IDR</td>
        <td align="right" colspan="2">{{ number_format($data['pricing'][10]->value,2) }}</td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td colspan="2">Biaya Polis</td>
        <td align="right">= IDR</td>
        <td align="right" colspan="2">{{ number_format($data['pricing'][11]->value,2) }}</td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td colspan="2">Biaya Lain</td>
        <td align="right">= IDR</td>
        <td align="right" colspan="2"  style="border-bottom: 1px solid black !important;">{{ number_format($data['pricing'][16]->value,2) }}</td>
        <td>+</td>
      </tr>
      {{-- <tr>
        <td></td>
        <td></td>
        <td colspan="2">PPH23 (2%)</td>
        <td align="right">= IDR</td>
        <td align="right" colspan="2" style="border-bottom: 1px solid black !important;">{{ number_format($data['pricing'][15]->value,2) }}</td>
        <td>+</td>
      </tr> --}}
      <tr>
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <td align="right">= IDR</td>
        <td align="right" colspan="2">{{ number_format($data['pricing'][18]->value,2) }}</td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td colspan="2">Brokerage ({{ number_format($data['pricing'][12]->value,0) }}% x Premi)</td>
        <td align="right">= IDR</td>
        <td align="right" colspan="2" style="border-bottom: 1px solid black !important;">{{ number_format($data['pricing'][13]->value,2) }}</td>
        <td>-</td>
      </tr>
      <tr> 
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <td align="right">= IDR</td>
        <td align="right" colspan="2">{{ number_format($data['pricing'][20]->value,2) }}</td>
        <td></td>
      </tr> 
      <tr> 
        <td></td>
        <td></td>
        <td colspan="2">PPH23 (2% x Brokerage)</td>
        <td align="right">= IDR</td>
        <td align="right" colspan="2" style="border-bottom: 1px solid black !important;">{{ number_format($data['pricing'][15]->value,2) }}</td>
        <td>+</td>
      </tr> 
      <tr>
        <td></td>
        <td></td>
        <td align="right" colspan="2">TOTAL PREMI</td>
        <td align="right">= IDR</td>
        <td align="right" colspan="2">{{ number_format($data['pricing'][19]->value,2) }}</td>
        <td></td>
      </tr>
      <tr>
        <td colspan="8">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;&nbsp;Security</td>
          <td>:</td>
          <td colspan="6">{{ $data['asuransi']->nama_asuransi }}</td>
      </tr>
    </table>
    <br>
    <br>
    <table style="font-size: 10pt">
      <tr>
        <td colspan="5">
          <b>Jakarta, {{ date('d F Y') }}</b><br>
          Signed for and on behalf of,<br>
          <b>PT. BINA DANA SEJAHTERA</b>
          <br>
        </td>
      </tr>
      <tr>
        <td colspan="5"><br></td>
      </tr>
      <tr align="center">
        <td><img src="{{ public_path('dist/images/TTD') }}/ttd-wahyu.png" height="65px"></td>
        <td></td>
        <td><img src="{{ public_path('dist/images/TTD') }}/ttd-hargo.png" height="40px"></td>
        <td></td>
        <td><img src="{{ public_path('dist/images/TTD') }}/ttd-{{ Auth::user()->id }}.png" height="65px"></td>
      </tr>
      <tr align="center">
        <td width="30%" style="border-bottom: 1px solid black !important;">Wahyusenja D. AAAIK CIIB</td>
        <td></td>
        <td width="30%" style="border-bottom: 1px solid black !important;">Hargo Nugroho CIIB</td>
        <td></td>
        <td width="30%" style="border-bottom: 1px solid black !important;">{{ Auth::user()->name }}</td>
      </tr>
    </table>
  </main>
</body>

</html>

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
          @if (!empty($data['perluasan']))
            ; amended to include the following risk :<br>
            @foreach ($data['perluasan'] as $perluasan)
              - {{ $perluasan->keterangan }} ({{ $perluasan->kode }}) <br>
            @endforeach
          @endif
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
              <tr>
                <th>{{ $i++ }}</th>
                <td>{{ $objek->objek }}</td>
                <td>{{ $objek->alamat_objek . " (" . $objek->no_jaminan . ") / " . $objek->kelurahan . ", " . $objek->kodepos }}</td>
                <td>{{ $objek->nama_okupasi . " / " . $objek->kode_okupasi }}</td>
              </tr>
            @endforeach
          </table>
        </td>
      </tr>
      {{-- <tr valign="top">
        <td>Risk Location / Postal Code</td>
        <td>:</td>
        <td style="text-align: justify;">
          @if (count($data['objek']) > 1)
            @php($i = 1)
            <ol>
            @foreach ($data['objek'] as $objek)
              <li>{{ $objek->alamat_objek . " (" . $objek->no_jaminan . ") / " . $objek->kelurahan . ", " . $objek->kodepos }}</li>
            @endforeach
            </ol>
          @else 
            {{ $data['objek']->alamat_objek . " (" . $objek->no_jaminan . ") / " . $objek->kelurahan . ", " . $objek->kodepos }}
          @endif
        </td>
      </tr>
      <tr valign="top">
        <td>Occupation / Code</td>
        <td>:</td>
        <td style="text-align: justify;">
          @if (count($data['objek']) > 1)
            @php($i = 1)
            <ol>
            @foreach ($data['objek'] as $objek)
              <li>{{ $objek->nama_okupasi . " (" . $objek->kode_okupasi . ")" }}</li>
            @endforeach
            </ol>
          @else 
            {{ $data['objek']->nama_okupasi . " (" . $objek->kode_okupasi . ")" }}
          @endif
        </td>
      </tr> --}}
      {{-- <tr valign="top">
        <td>Nomor { jaminan }</td>
        <td>:</td>
        <td>{{ $data['transaksi']->no_jaminan }}</td>
      </tr>
      <tr valign="top">
        <td>Kode Pos / Kelurahan</td>
        <td>:</td>
        <td>{ kodepos } / { kelurahan }</td>
      </tr> --}}
      {{-- <tr valign="top">
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
      </tr> --}}
      {{-- <tr valign="top">
        <td>Deductibles</td>
        <td>:</td>
        <td>
          <table CELLSPACING=0>
            <tr valign="top">
              <td>-</td>
              <td>Fire, Lightning, Explosion, Aircraft Impact & Smoke ; { deductible }.</td>
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
      </tr> --}}
      <tr valign="top">
        <td><strong>Sum Insured</strong></td>
        <td>:</td>
        <td>
          <table style="font-size:10pt; width:80%">
            @php($id_objek_pricing = 0)
            @php($i = 1)
            @foreach ($data['objek_pricing'] as $row => $objek_pricing)
            <tr valign="top" style="padding: 0px;">
              <td width="5%" style="padding: 2px;">@if ($objek_pricing->id_objek !== $id_objek_pricing) {{ $i++ }} @endif</td>
              @php($id_objek_pricing = $objek_pricing->id_objek)
              <td width="45%" style="padding: 2px;">{{ $objek_pricing->kodetrans_nama }}</td>
              <td width="50%" style="padding: 0px;">
                <table cellspacing=0 style="width: 40% !important" align="right" style="padding: 0px;">
                  <tr valign="top">
                    <td>IDR</td>
                    <td align="right">{{ number_format($objek_pricing->value,2) }}</td>
                  </tr>
                </table>
              </td>
            </tr>
            @endforeach
            <tr valign="top">
              <td width="1%" style="padding: 2px;"></td>
              <td align="right"><strong>TOTAL</strong></td>
              <td width="50%" style="padding: 0px;">
                <table cellspacing=0 style="width: 40% !important" align="right" style="padding: 0px;">
                  <tr>
                    <td><strong>IDR</strong></td>
                    <td align="right" style="border-top: 1px solid black;"><strong>{{ number_format($data['pricing'][1]->value,2) }}</strong></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <div style="margin-left:6px !important" class="ql-editor">
      {!! $data['transaksi']->klausula !!}
      @php($jml_installment = count($data['installment']))
      @php($i = 1)
      @if ($jml_installment > 1)
        <p>
          Defferred Premium Clause (Installment {{ $jml_installment }} in {{ $jml_installment }} months)<br>
          <table style="font-size:10pt;">
            @foreach ($data['installment'] as $installment)
              <tr>
                <td width="5%">&nbsp;&nbsp;&nbsp;{{ $i }}.</td>
                <td width="15%">Installment {{ FunctionsHelp::angka_romawi($i++) }}</td>
                <td width="2%">:</td>
                <td>{{ date_format(date_create($installment->tgl_tagihan),"d F Y") }}</td>
              </tr>
            @endforeach
          </table>
        </p>
      @endif
    </div>
    <br>
    <table style="font-size: 10pt" cellspacing=0>
      <tr valign="top">
        <td width="10%"><strong>Rate<br>(per annum)</strong></td>
        <td width="2%">:</td>
        <td>
          <i><u>{{ $data['instype']->instype_name }} :</u></i><br>
          <table>
            @php($i = 1)
            @php($total_rate = 0)
            @php($jml_objek = count($data['objek']))
            @foreach ($data['objek'] as $objek)
              @php($total_rate += $objek->rate)
              <tr style="padding:0px;" valign="top">
                <td width="4%" style="padding:0px;">{{ $i++ }}.</td>
                <td width="78%" style="padding:0px;"><b><i>{{ $objek->nama_okupasi }} ({{ $objek->kode_okupasi }}) ({{ $objek->nama_kelas }})</i></b></td>
                <td width="2%" style="padding:0px;">:</td>
                <td style="padding:0px;" align="right">{{ $objek->rate }} ‰</td>
              </tr>
              @if (!empty($data['perluasan']))
              <tr>
                <td></td>
                <td colspan="3">
                  <u>Expansions:</u>
                </td>
              </tr>
                @foreach ($data['perluasan'] as $row => $perluasan)
                  @if ($perluasan->id_objek == $objek->objek_id)
                    <tr style="padding:0px;" valign="top">
                      <td style="padding:0px;" align="right">-</td>
                      <td style="padding:0px;"><i>{{ $perluasan->keterangan }}</i></td>
                      <td style="padding:0px;">:</td>
                      <td style="padding:0px;" align="right">{{ $perluasan->rate }} ‰</td>
                    </tr>
                  @endif
                @endforeach
              @endif
            @endforeach
          </table>
        </td>
      </tr>
    </table>
    <br>
    <table style="font-size:10pt">
      <tr valign="top">
        <td><strong>Perhitungan Premi</strong></td>
        <td></td>
        <td></td>
      </tr>
    </table>
    <table style="font-size:10pt;font-weight:bold;">
      @foreach ($data['objek'] as $row => $objek)
        @php($TSI_objek = 0)
        @foreach ($data['objek_pricing'] as $objek_pricing)
          @if($objek->objek_id == $objek_pricing->id_objek)
            @php($TSI_objek += $objek_pricing->value);
          @endif
        @endforeach
        <tr>
          <td width="10%">@if($row == 0)&nbsp;&nbsp;Premi @endif</td>
          <td width="5%">@if($row == 0): @endif</td>
          <td width="55%">TSI ({{ $row + 1 }}) =  IDR {{ number_format($TSI_objek,2) }} &nbsp;&nbsp;&nbsp;x {{ $objek->rate }} ‰</td>
          <td width="6%" align="right">= IDR</td>
          <td width="" align="right">{{ number_format($TSI_objek * $objek->rate / 1000,2) }}</td>
          <td width="1%"></td>
        </tr>
        @if (!empty($data['perluasan']))
          @foreach ($data['perluasan'] as $row => $perluasan)
            @if ($perluasan->id_objek == $objek->objek_id)
              <tr>
                <td width="10%"></td>
                <td width="5%"></td>
                <td width="55%">- {{ $perluasan->kode }} =  IDR {{ number_format($perluasan->value,2) }} &nbsp;&nbsp;&nbsp;x {{ $perluasan->rate }} ‰</td>
                <td width="6%" align="right">= IDR</td>
                <td width="" align="right">{{ number_format($perluasan->value * $perluasan->rate / 1000,2) }}</td>
                <td width="1%"></td>
              </tr>
            @endif
          @endforeach
        @endif
      @endforeach
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
        <td align="right">{{ number_format($data['pricing'][11]->value,2) }}</td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td>PPH23 (2%)</td>
        <td align="right">= IDR</td>
        <td align="right" style="border-bottom: 1px solid black !important;">{{ number_format($data['pricing'][15]->value,2) }}</td>
        <td>+</td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td align="right">= IDR</td>
        <td align="right">{{ number_format($data['pricing'][18]->value,2) }}</td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td>Brokerage {{ number_format($data['pricing'][12]->value,2) }}% (Exclude VAT)</td>
        <td align="right">= IDR</td>
        <td align="right" style="border-bottom: 1px solid black !important;">{{ number_format($data['pricing'][13]->value,2) }}</td>
        <td>-</td>
      </tr>
      {{-- <tr>
        <td></td>
        <td></td>
        <td>PPN (10%)</td>
        <td align="right">= IDR</td>
        <td align="right" style="border-bottom: 1px solid black !important;">{{ number_format($data['pricing'][14]->value,2) }}</td>
        <td>-</td>
      </tr>  --}}
      <tr>
        <td></td>
        <td></td>
        <td><u>TOTAL PREMI</u></td>
        <td align="right">= IDR</td>
        <td align="right">{{ number_format($data['pricing'][19]->value,2) }}</td>
        <td></td>
      </tr>
      <tr>
        <td colspan="6"></td>
      </tr>
      <tr>
        <td width="10%">&nbsp;&nbsp;Security</td>
          <td width="5%">:</td>
          <td width="55%">{{ $data['asuransi']->nama_asurasi }}</td>
          <td width="6%" align="right"></td>
          <td width="" align="right"></td>
          <td width="1%"></td>
      </tr>
    </table>
    <br>
    <br>
    <table style="font-size: 10pt">
      <tr>
        <td>
          <b>Jakarta, {{ date('d F Y') }}</b><br>
          Signed for and on behalf of,
          <b>PT. BINA DANA SEJAHTERA</b>
        </td>
        <td colspan="4"></td>
      </tr>
      <tr>
        <td>Wahyusenja D. AAAIK CIIB</td>
        <td></td>
        <td>Hargo Nugroho CIIB</td>
        <td></td>
        <td>Broker</td>
      </tr>
      {{-- <tr>
        <td width="70%"></td>
        <td align="center">JAKARTA, _______________</td>
      </tr>
      <tr>
        <td></td>
        <td align="center">
          <br><br><br><br><br><br>
        </td>
      </tr>
      <tr>
        <td></td>
        <td align="center">{ nama_asuransi }</td>
      </tr> --}}
    </table>
  </main>
</body>

</html>
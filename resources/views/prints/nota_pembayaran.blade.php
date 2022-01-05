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
  table.padding td {
      padding: 4px 4px 4px 4px;
      font-size: 11px;
   }
</style>
<body>
  <table style="font-size:6" width="100%">
    <tr>
      <td><img src="{{ public_path('dist/images/Logo BDS insurance SIUP.png') }}" width="45%"></td>
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
      <td colspan="4" style="font-size: 11;text-align: center;"><u><b>&nbsp;NOTA PEMBAYARAN&nbsp;</b></u></td>
    </tr>
    <tr>
      <td colspan="4" style="font-size: 8;text-align: center;"><b>ID TRANSAKSI: {{ $data['transaksi']->transid }}</b></td>
    </tr>
    <tr>
      <td width="100%">
        <table style="font-size: 8;" class="padding">
          <tr>
            <td>
              <table width="100%" style="border-collapse: collapse;" border="1">
                <tr>
                  <td width="30%">
                    <b><u>Nama & Alamat Tertanggung</u></b><br>
                    <i>(Name & Address of Insured)</i>
                  </td>
                  <td width="70%">
                    PT. BANK KB BUKOPIN, TBK. CAB {{ $data['cabang']->nama_cabang }} QQ {{ $data['insured']->nama_insured }}<br>
                    {{ $data['cabang']->alamat_cabang }}
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="100%">
        <table style="font-size: 8;" width="100%"  class="padding">
          <tr>
            <td width="65%" valign="top">
              <table border=1 style="border-collapse: collapse;">
                <tr>
                  <td width="46%">
                    <b><u>No. Covernote</u></b><br>
                    <i>(Covernote Number)</i>
                  </td>
                  <td width="54%">
                    {{ $data['transaksi']->cover_note }}
                  </td>
                </tr>
                <tr>
                  <td>
                    <b><u>Nama Penanggung</u></b><br>
                    <i>(Insurer)</i>
                  </td>
                  <td>
                    {{ $data['asuransi']->nama_asuransi }}
                  </td>
                </tr>
                <tr>
                  <td>
                    <b><u>Jangka Waktu</u></b><br>
                    <i>(Periode)</i>
                  </td>
                  <td>
                    {{ FunctionsHelp::tgl_indo($data['transaksi']->polis_start) ." s/d ". FunctionsHelp::tgl_indo($data['transaksi']->polis_end) }}
                  </td>
                </tr>
                <tr>
                  <td>
                    <b><u>Jenis Asuransi</u></b><br>
                    <i>(Class of Business)</i>
                  </td>
                  <td>
                    {{ $data['instype']->instype_name }}
                  </td>
                </tr>
                <tr>
                  <td>
                    <b><u>Obyek Asuransi</u></b><br>
                    <i>(Insured Interest)</i>
                  </td>
                  <td>
                    {{ $data['transaksi']->object }}
                  </td>
                </tr>
                <tr>
                  <td>
                    <b><u>Jumlah Pertanggungan</u></b><br>
                    <i>(Total Sum Insured)</i>
                  </td>
                  <td>
                    {{ number_format($data['pricing'][1]->value,2) }}
                  </td>
                </tr>
              </table>
              <table style="width: 100% !important;">
                <tr style="">
                  <td valign="top" style="font-size: 8;" width="45%">
                    <u><b>Perhitungan Premi :</b></u>
                  </td>
                  <td>
                    &nbsp;
                  </td>
                  <td width="50%">
                    <u><b>Dibayarkan Kepada :</b></u>
                  </td>
                </tr>
                <tr>
                  <td style="font-size: 9;">
                    <table style="font-style:italic;  padding:0px;">
                      <tr>
                        <td style=" padding:0px !important;">1. Total Premi</td>
                        <td style="text-align: right; padding:0px !important;">{{ number_format($data['pricing'][2]->value,2) }}</td>
                      </tr>
                      <tr>
                        <td style=" padding:0px !important;">2. Biaya Polis</td>
                        <td style="text-align: right; padding:0px !important;">{{ number_format($data['pricing'][10]->value,2) }}</td>
                      </tr>
                      <tr>
                        <td style=" padding:0px !important;">3. Biaya Materai</td>
                        <td style="text-align: right; padding:0px !important;">{{ number_format($data['pricing'][11]->value,2) }}</td>
                      </tr>
                      <tr>
                        <td style=" padding:0px !important;">4. Lain-Lain</td>
                        <td style="text-align: right; padding:0px !important;" class="gbawah">{{ number_format($data['pricing'][16]->value,2) }}</td>
                      </tr>
                      <tr>
                        <td style="text-align: right; padding:0px !important;">Total Tagihan</td>
                        <td style="text-align: right; padding:0px !important;" class="gbawah">{{ number_format($data['pricing'][18]->value,2) }}</td>
                      </tr>
                    </table>
                  </td>
                  <td>
                    &nbsp;
                  </td>
                  <td valign="top">
                    {{ $data['asuransi']->bank_asuransi }}<br>
                    {{ $data['asuransi']->rekening_asuransi }}
                  </td>
                </tr>
              </table>
            </td>
            <td width="35%" valign="top">
              <table border=1 style="border-collapse: collapse;">
                <tr style="background-color: #eaeaea">
                  <td width="50%" style="text-align: center;">
                    <b><u>Perincian</u></b><br>
                    <i>(Detail)</i>
                  </td>
                  <td width="50%" style="text-align: center;">
                    <b><u>Jumlah</u></b><br>
                    <i>(Amount)</i>
                  </td>
                </tr>
                <tr>
                  <td>
                    Tagihan
                  </td>
                  <td align="right">
                    {{ number_format($data['pricing'][18]->value,2) }}
                  </td>
                </tr>
                <tr>
                  <td>
                    Komisi
                  </td>
                  <td align="right">
                    - {{ number_format($data['pricing'][13]->value,2) }}
                  </td>
                </tr>
                <tr>
                  <td>
                    PPN
                  </td>
                  <td align="right">
                    - {{ number_format($data['pricing'][14]->value,2) }}
                  </td>
                </tr>
                <tr>
                  <td>
                    PPh_23
                  </td>
                  <td align="right">
                    {{ number_format($data['pricing'][15]->value,2) }}
                  </td>
                </tr>
                <tr style="background-color: #eaeaea">
                  <td style="text-align: center;">
                    <b><u>Jumlah</u></b><br>
                    <i>(Total)</i>
                  </td>
                  <td align="right">
                    <b>{{ number_format($data['pricing'][19]->value,2) }}</b>
                  </td>
                </tr>
              </table>
              <br>
              <br>
              <table>
                <tr>
                  <td align="center" style="font-size: 9;">
                    JAKARTA, {{ FunctionsHelp::tgl_indo(explode(" ",$data['pembayaran']->paid_at)[0]) }}<br>
                    <u>PT. BINA DANA SEJAHTERA</u><br>
                    <b id="test"></b>
                    <br>
                    <img src="data:image/png;base64, {!! $qrcode !!}">
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
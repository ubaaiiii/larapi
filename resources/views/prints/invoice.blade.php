<style>
  @page { 
    size: 21cm 14.8cm;
  }
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
<body>
  <table style="font-size:6" width="100%">
    <tr>
      <td><img src="{{ url('public/dist/images/Logo BDS insurance SIUP.png') }}" width="35%"></td>
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
            <td>Tanggal</td><td>:</td><td>{{ date_format(date_create($data['transaksi']->created_at),"d F Y") }}</td>
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
          <td width="17%" class="tright">{{ number_format($data['pricing'][1]->value,2) }}</td>
        </tr>
        <tr>
          <td>ASURANSI</td>
          <td>{{ $data['asuransi']->nama_asuransi }}</td>
          <td>By. Polis</td>
          <td class="tright">{{ number_format($data['pricing'][9]->value,2) }}</td>
        </tr>
        <tr>
          <td>NO. POLIS</td>
          <td>{{ $data['transaksi']->policy_no }}</td>
          <td>By. Materai</td>
          <td class="tright">{{ number_format($data['pricing'][10]->value,2) }}</td>
        </tr>
        <tr>
          <td>PERIODE POLIS</td>
          <td>{{ date_format(date_create($data['transaksi']->polis_start),"d F Y") }} - {{ date_format(date_create($data['transaksi']->polis_end),"d F Y") }}</td>
          <td>By. Lain</td>
          <td class="tright gbawah">{{ number_format($data['pricing'][16]->value,2) }}</td>
        </tr>
        <tr>
          <td>TERTANGGUNG</td>
          <td>QQ {{ $data['insured']->nama_insured }}</td>
          <td>Gross Premium</td>
          <td class="tright">{{ number_format($data['pricing'][18]->value,2) }}</td>
        </tr>
        <tr>
          <td>OBJEK PERTANGGUNGAN</td>
          <td>{{ $data['transaksi']->object }}</td>
          <td>By. Admin</td>
          <td class="tright gbawah">{{ number_format($data['pricing'][11]->value,2) }}</td>
        </tr>
        <tr>
          <td>TOTAL PERTANGGUNGAN</td>
          <td>{{ number_format($data['pricing'][0]->value,2) }}</td>
          <td>Gross NET</td>
          <td class="tright">{{ number_format($data['pricing'][19]->value,2) }}</td>
        </tr>
        <tr>
          <td>LOKASI PERTANGGUNGAN</td>
          <td colspan="3">{{ $data['transaksi']->location }}</td>
        </tr>
        <tr>
          <td>TANGGAL JATUH TEMPO</td>
          <td colspan="3">{{ (!empty($data['due_date']->created_at))?$data['due_date']->created_at:"" }}</td>
        </tr>
      </table>
    </tr>
  </table>
  <table>
    <tr>
      <td valign="top" style="font-size: 7;">
        <u><b>Notes</b></u><br>
        <br>
        <i>
          <b>
            Pembayaran ditransfer ke rekening PT. BINA DANA SEJAHTERA pada:<br>
            {REKENING}<br>
            Mohon pembayaran premi tidak melebihi dari 14 hari untuk menjaga<br>
            berlakunya coverage/jaminan dari polis ini.
          </b>
        </i>
      </td>
      <td align="center"  style="font-size: 9;">
        JAKARTA, {{ date('d F Y') }}<br>
        <br>
        <u>PT. BINA DANA SEJAHTERA</u><br>
        <br>
        <img src="data:image/png;base64, {!! $qrcode !!}">
      </td>
    </tr>
  </table>
</body>
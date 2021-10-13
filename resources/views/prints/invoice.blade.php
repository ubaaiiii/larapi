<style>
  body {
    font-family: Arial, Helvetica, sans-serif;
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
  <table style="font-size:9" width="100%">
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
      <td width="70%" style="font-size:15">
        <b>PT. BANK KB BUKOPIN, TBK. CAB {CABANG}</b><br>
        {ALAMAT_CABANG}
      </td>
      <td align="right" width="auto" style="font-size:12">
        <table>
          <tr>
            <td>Tanggal</td><td>:</td><td>11 November 2021</td>
          </tr>
          <tr>
            <td>ID Trasaksi</td><td>:</td><td>{TRANSID}</td>
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
      <td colspan="4" class="gbawah" style="font-size: 16;text-align: center;"><b>INVOICE</b></td>
    </tr>
    <tr>
      <table class="content">
        <tr>
          <td width="25%">TIPE ASURANSI</td>
          <td width="44%">{INSTYPE}</td>
          <td width="13%">Premium</td>
          <td width="17%" class="tright">{PREMI}</td>
        </tr>
        <tr>
          <td>ASURANSI</td>
          <td>{ASURANSI}</td>
          <td>By. Polis</td>
          <td class="tright">{POLIS}</td>
        </tr>
        <tr>
          <td>NO. POLIS</td>
          <td>{NOPOLIS}</td>
          <td>By. Materai</td>
          <td class="tright">{MATERAI}</td>
        </tr>
        <tr>
          <td>PERIODE POLIS</td>
          <td>{PERIODE}</td>
          <td>By. Lain</td>
          <td class="tright gbawah">{LAIN}</td>
        </tr>
        <tr>
          <td>TERTANGGUNG</td>
          <td>{INSURED}</td>
          <td>Gross Premium</td>
          <td class="tright">{GROSS}</td>
        </tr>
        <tr>
          <td>OBJEK PERTANGGUNGAN</td>
          <td>{OBJECT}</td>
          <td>By. Admin</td>
          <td class="tright gbawah">{ADMIN}</td>
        </tr>
        <tr>
          <td>TOTAL PERTANGGUNGAN</td>
          <td>{TSI}</td>
          <td>Gross NET</td>
          <td class="tright">{NET}</td>
        </tr>
        <tr>
          <td>LOKASI PERTANGGUNGAN</td>
          <td>{LOCATION}</td>
          <td>&nbsp;</td>
          <td class="tright">&nbsp;</td>
        </tr>
        <tr>
          <td>TANGGAL JATUH TEMPO</td>
          <td>{DUEDATE}</td>
          <td>&nbsp;</td>
          <td class="tright">&nbsp;</td>
        </tr>
      </table>
    </tr>
  </table>

  <table>
    <tr>
      <td valign="top">
        <u><b>Notes</b></u><br>
        <br>
        <i>
          <b>
            Pembayaran ditransfer ke rekening PT. BINA DANA SEJAHTERA pada:<br>
            {REKENING}<br>
            Mohon pembayaran premi tidak melebihi dari 14 hari untuk menjaga berlakunya coverage/jaminan dari polis ini.
          </b>
        </i>
      </td>
      <td class="gbawah">
        JAKARTA, {{ date('d F Y') }}<br>
        <br>
        <u>PT. BINA DANA SEJAHTERA</u><br>
        <br>
        <br>
        <br>
        <br>
        <br>
      </td>
    </tr>
  </table>
</body>
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\DataController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function tableLaporan(Request $request)
    {
        $dataController = new DataController;
        $table = DB::table("transaksi");

        if ($request->instype !== 'ALL') {
            $table->where('transaksi.id_instype', '=', $request->instype);
        }

        if ($request->cabang !== 'ALL') {
            $table->where('transaksi.id_cabang', '=', $request->cabang);
        }

        if ($request->asuransi !== 'ALL') {
            $table->where('transaksi.id_asuransi', '=', $request->asuransi);
        }

        switch ($request->jenis) {
            case '1':
                // Laporan Produksi All Status
                $select = [
                    'transaksi.created_at   AS "Tanggal Dibuat"',
                    'transid                AS "Nomor Transaksi"',
                    'cif                    AS "CIF"',
                    'nopinjaman             AS "Nomor Pinjaman"',
                    'nama_cabang            AS "Cabang KB Bukopin"',
                    'nama_insured           AS "Nama Tertanggung"',
                    'plafond_kredit         AS "Plafond"',
                    'outstanding_kredit     AS "Outstanding"',
                    'alamat_insured         AS "Alamat Tertanggung"',
                    'nama_okupasi           AS "Okupasi"',
                    'location               AS "Lokasi Okupasi"',
                    'nama_asuransi          AS "Asuransi"',
                    'instype_name           AS "Tipe Asuransi"',
                    'tsi.value              AS "Nilai Pertanggungan"',
                    'premi.value            AS "Premium"',
                    'polis_start            AS "Polis Mulai"',
                    'polis_end              AS "Polis Selesai"',
                    'sts.msdesc             AS "Status"',
                    'catatan                AS "Catatan"',
                ];
                if (!empty($request->dtable)) {
                    $table->whereBetween('transaksi.created_at', [$request->periode_start, $request->periode_end]);

                    $column = [
                        'transaksi.created_at',
                        'transid',
                        'cif',
                        'nopinjaman',
                        'nama_insured',
                        'alamat_insured',
                        'nama_cabang',
                        'nama_asuransi',
                        'plafond_kredit',
                        'cover_note',
                        'policy_no',
                        'instype_name',
                        'polis_start',
                        'polis_end',
                        'nama_okupasi',
                        'location',
                        'tsi.value',
                        'premi.value',
                        'sts.msdesc',
                        'catatan',
                    ];
                    $joins = [
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'transaksi.id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }
                break;

            case '2':
                // Laporan Pembayaran
                $select = [
                    'pd.created_at          AS "Tanggal Dibayar"',
                    'transid                AS "Nomor Transaksi"',
                    'cif                    AS "CIF"',
                    'nopinjaman             AS "Nomor Pinjaman"',
                    'nama_insured           AS "Tertanggung"',
                    'alamat_insured         AS "Alamat Tertanggung"',
                    'nama_cabang            AS "Cabang KB Bukopin"',
                    'nama_asuransi          AS "Asuransi"',
                    'plafond_kredit         AS "Plafond"',
                    'cover_note             AS "Covernote"',
                    'policy_no              AS "Nomor Polis"',
                    'instype_name           AS "Tipe Asuransi"',
                    'polis_start            AS "Polis Mulai"',
                    'polis_end              AS "Polis Selesai"',
                    'nama_okupasi           AS "Okupasi"',
                    'location               AS "Lokasi Okupasi"',
                    'tsi.value              AS "Nilai Pertanggungan"',
                    'premi.value            AS "Premium"',
                    'sts.msdesc             AS "Status"',
                    'catatan                AS "Catatan"',
                ];
                if (!empty($request->dtable)) {
                    $table->whereBetween('transaksi.created_at', [$request->periode_start, $request->periode_end])
                        ->where('id_status', '=', '5');

                    $column = [
                        'pd.created_at',
                        'transid',
                        'cif',
                        'nopinjaman',
                        'nama_insured',
                        'alamat_insured',
                        'nama_cabang',
                        'nama_asuransi',
                        'plafond_kredit',
                        'cover_note',
                        'policy_no',
                        'instype_name',
                        'polis_start',
                        'polis_end',
                        'nama_okupasi',
                        'location',
                        'tsi.value',
                        'premi.value',
                        'sts.msdesc',
                        'catatan',
                    ];

                    $joins = [
                        ['transaksi_pembayaran AS pd', 'transid = pd.id_transaksi'],
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'transaksi.id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }
                break;

            case '3':
                // Laporan Klaim
                $select = [
                    'kl.tgl_lapor            AS "Tanggal Pengajuan Klaim"',
                    'transid                 AS "Nomor Transaksi"',
                    'nama_cabang             AS "Cabang KB Bukopin"',
                    'nama_insured            AS "Nama Tertanggung"',
                    'alamat_insured          AS "Alamat Tertanggung"',
                    'nama_okupasi            AS "Okupasi"',
                    'location                AS "Lokasi Okupasi"',
                    'nama_asuransi           AS "Asuransi"',
                    'policy_no               AS "Nomor Polis"',
                    'instype_name            AS "Tipe Asuransi"',
                    'kl.nilai_yang_disetujui AS "Nilai Klaim"',
                    'sts.msdesc              AS "Status"',
                    'catatan                 AS "Catatan"',
                ];
                if (!empty($request->dtable)) {
                    $table->whereBetween('kl.tgl_lapor', [$request->periode_start, $request->periode_end])
                        ->where('id_status', '>=', '20');

                    $column = [
                        'kl.tgl_lapor',
                        'transid',
                        'nama_cabang',
                        'nama_insured',
                        'alamat_insured',
                        'nama_okupasi',
                        'location',
                        'nama_asuransi',
                        'policy_no',
                        'instype_name',
                        'sts.msdesc',
                        'kl.nilai_yang_disetujui',
                        'catatan',
                    ];
                    $joins = [
                        ['transaksi_klaim AS kl', 'transid = kl.id_transaksi'],
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'transaksi.id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                    ];
                }
                break;

            case '4':
                // Laporan Pembatalan Pengajuan
                $select = [
                    'transaksi.created_at   AS "Tanggal Produksi"',
                    'transid                AS "Nomor Transaksi"',
                    'cif                    AS "CIF"',
                    'nopinjaman             AS "Nomor Pinjaman"',
                    'nama_cabang            AS "Cabang KB Bukopin"',
                    'cover_note             AS "Covernote"',
                    'nama_insured           AS "Nama Tertanggung"',
                    'nama_asuransi          AS "Asuransi"',
                    'nama_okupasi           AS "Okupasi"',
                    'location               AS "Lokasi Okupasi"',
                    'instype_name           AS "Tipe Asuransi"',
                    'tsi.value              AS "Nilai Pertanggungan"',
                    'tag_premi.value        AS "TAG Premi"',
                    'polis_start            AS "Polis Mulai"',
                    'polis_end              AS "Polis Selesai"',
                    'sts.msdesc             AS "Status"',
                    'catatan                AS "Catatan"',
                ];
                if (!empty($request->dtable)) {
                    $table->whereBetween('transaksi.created_at', [$request->periode_start, $request->periode_end])
                        ->whereIn('transaksi.id_status', ['12', '13', '18']);

                    $column = [
                        'transaksi.created_at',
                        'transid',
                        'cif',
                        'nopinjaman',
                        'nama_cabang',
                        'cover_note',
                        'nama_insured',
                        'nama_asuransi',
                        'nama_okupasi',
                        'location',
                        'instype_name',
                        'tsi.value',
                        'tag_premi.value',
                        'polis_start',
                        'polis_end',
                        'sts.msdesc',
                        'catatan',
                    ];
                    $joins = [
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'transaksi.id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS tag_premi', ['transid = tag_premi.id_transaksi', 'tag_premi.id_kodetrans = 18']],
                    ];
                }
                break;

            case '5':
                // Laporan Refund
                $select = [
                    'rf.tgl_batal           AS "Tanggal Pengajuan Refund"',
                    'transid                AS "Nomor Transaksi"',
                    'nama_cabang            AS "Cabang KB Bukopin"',
                    'nama_insured           AS "Nama Tertanggung"',
                    'alamat_insured         AS "Alamat Tertanggung"',
                    'nama_okupasi           AS "Okupasi"',
                    'location               AS "Lokasi Okupasi"',
                    'nama_asuransi          AS "Asuransi"',
                    'policy_no              AS "Nomor Polis"',
                    'instype_name           AS "Tipe Asuransi"',
                    'rf.refund              AS "Nilai Refund"',
                    'sts.msdesc             AS "Status"',
                    'catatan                AS "Catatan"',
                ];
                if (!empty($request->dtable)) {
                    $column = [
                        'rf.tgl_batal',
                        'transid',
                        'nama_cabang',
                        'nama_insured',
                        'alamat_insured',
                        'nama_okupasi',
                        'location',
                        'nama_asuransi',
                        'policy_no',
                        'instype_name',
                        'sts.msdesc',
                        'rf.refund',
                        'catatan',
                    ];
                    $joins = [
                        ['transaksi_refund AS rf', 'transid = rf.id_transaksi'],
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'transaksi.id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                    ];
                }
                break;

            case '6':
                // Laporan Klaim Reject
                $select = [
                    'transaksi.created_at   AS "Tanggal Produksi"',
                    'transid                AS "Nomor Transaksi"',
                    'cif                    AS "CIF"',
                    'nopinjaman             AS "Nomor Pinjaman"',
                    'nama_cabang            AS "Cabang KB Bukopin"',
                    'cover_note             AS "Covernote"',
                    'nama_insured           AS "Nama Tertanggung"',
                    'nama_asuransi          AS "Asuransi"',
                    'nama_okupasi           AS "Okupasi"',
                    'location               AS "Lokasi Okupasi"',
                    'instype_name           AS "Tipe Asuransi"',
                    'tsi.value              AS "Nilai Pertanggungan"',
                    'tag_premi.value        AS "TAG Premi"',
                    'polis_start            AS "Polis Mulai"',
                    'polis_end              AS "Polis Selesai"',
                    'catatan                AS "Catatan"',
                ];
                if (!empty($request->dtable)) {
                    $table->whereBetween('transaksi.created_at', [$request->periode_start, $request->periode_end])
                        ->where('transaksi.id_status', '=', '24');

                    $column = [
                        'transaksi.created_at',
                        'transid',
                        'cif',
                        'nopinjaman',
                        'nama_cabang',
                        'cover_note',
                        'nama_insured',
                        'nama_asuransi',
                        'nama_okupasi',
                        'location',
                        'instype_name',
                        'tsi.value',
                        'tag_premi.value',
                        'polis_start',
                        'polis_end',
                        'catatan',
                    ];
                    $joins = [
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'transaksi.id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS tag_premi', ['transid = tag_premi.id_transaksi', 'tag_premi.id_kodetrans = 18']],
                    ];
                }
                break;

            case '7':
                // Laporan Jatuh Tempo
                $select = [
                    'transaksi.created_at   AS "Tanggal Dibuat"',
                    'transid                AS "Nomor Transaksi"',
                    'cif                    AS "CIF"',
                    'nopinjaman             AS "Nomor Pinjaman"',
                    'nama_insured           AS "Nama Tertanggung"',
                    'nama_cabang            AS "Cabang KB Bukopin"',
                    'plafond_kredit         AS "Plafond"',
                    'outstanding_kredit     AS "Outstanding Kredit"',
                    'nama_okupasi           AS "Okupasi"',
                    'location               AS "Lokasi Okupasi"',
                    'nama_asuransi          AS "Nama Asuransi"',
                    'policy_no              AS "Nomor Polis"',
                    'instype_name           AS "Tipe Asuransi"',
                    'tsi.value              AS "Nilai Pertanggungan"',
                    'premi.value            AS "Premium"',
                    'polis_start            AS "Polis Mulai"',
                    'polis_end              AS "Polis Selesai"',
                    'catatan                AS "Keterangan"',
                ];
                if (!empty($request->dtable)) {
                    $table->whereBetween('transaksi.created_at', [$request->periode_start, $request->periode_end])
                        ->where('transaksi.id_status', '=', '4');

                    $column = [
                        'transaksi.created_at',
                        'transid',
                        'cif',
                        'nopinjaman',
                        'nama_insured',
                        'nama_cabang',
                        'plafond_kredit',
                        'outstanding_kredit',
                        'nama_okupasi',
                        'location',
                        'nama_asuransi',
                        'policy_no',
                        'instype_name',
                        'tsi.value',
                        'premi.value',
                        'polis_start',
                        'polis_end',
                        'catatan',
                    ];
                    $joins = [
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'transaksi.id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }
                break;

            case '8':
                // Laporan Master Asuransi
                $select = [
                    'transaksi.created_at   AS "Tanggal Dibuat"',
                    'transid                AS "Nomor Transaksi"',
                    'cif                    AS "CIF"',
                    'nopinjaman             AS "Nomor Pinjaman"',
                    'nama_insured           AS "Tertanggung"',
                    'alamat_insured         AS "Alamat Tertanggung"',
                    'nama_cabang            AS "Cabang KB Bukopin"',
                    'nama_asuransi          AS "Asuransi"',
                    'plafond_kredit         AS "Plafond"',
                    'cover_note             AS "Covernote"',
                    'policy_no              AS "Nomor Polis"',
                    'polis_start            AS "Polis Mulai"',
                    'polis_end              AS "Polis Selesai"',
                    'nama_okupasi           AS "Okupasi"',
                    'location               AS "Lokasi Okupasi"',
                    'tsi.value              AS "Nilai Pertanggungan"',
                    'premi.value            AS "Premium"',
                    'sts.msdesc             AS "Status"',
                    'catatan                AS "Catatan"',
                ];
                if (!empty($request->dtable)) {
                    $table->whereBetween('transaksi.created_at', [$request->periode_start, $request->periode_end]);

                    $column = [
                        'transaksi.created_at',
                        'transid',
                        'cif',
                        'nopinjaman',
                        'nama_insured',
                        'alamat_insured',
                        'nama_cabang',
                        'nama_asuransi',
                        'plafond_kredit',
                        'cover_note',
                        'policy_no',
                        'polis_start',
                        'polis_end',
                        'nama_okupasi',
                        'location',
                        'tsi.value',
                        'premi.value',
                        'sts.msdesc',
                        'catatan',
                    ];
                    $joins = [
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'transaksi.id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }
                break;

            case '9':
                // Report All Polis
                $select = [
                    'transaksi.created_at   AS "Tanggal Dibuat"',
                    'transid                AS "Nomor Transaksi"',
                    'cif                    AS "CIF"',
                    'nopinjaman             AS "Nomor Pinjaman"',
                    'nama_insured           AS "Nama Tertanggung"',
                    'nama_cabang            AS "Cabang KB Bukopin"',
                    'alamat_insured         AS "Alamat Tertanggung"',
                    'plafond_kredit         AS "Plafond"',
                    'outstanding_kredit     AS "Outstanding"',
                    'nama_okupasi           AS "Okupasi"',
                    'location               AS "Lokasi Okupasi"',
                    'nama_asuransi          AS "Asuransi"',
                    'policy_no              AS "Nomor Polis"',
                    'instype_name           AS "Tipe Asuransi"',
                    'tsi.value              AS "Nilai Pertanggungan"',
                    'premi.value            AS "Premium"',
                    'polis_start            AS "Polis Mulai"',
                    'polis_end              AS "Polis Selesai"',
                    'sts.msdesc             AS "Status"',
                ];
                if (!empty($request->dtable)) {
                    $table->whereBetween('transaksi.created_at', [$request->periode_start, $request->periode_end]);

                    $column = [
                        'transaksi.created_at',
                        'transid',
                        'cif',
                        'nopinjaman',
                        'nama_insured',
                        'nama_cabang',
                        'alamat_insured',
                        'plafond_kredit',
                        'outstanding_kredit',
                        'nama_okupasi',
                        'location',
                        'nama_asuransi',
                        'policy_no',
                        'instype_name',
                        'tsi.value',
                        'premi.value',
                        'polis_start',
                        'polis_end',
                        'sts.msdesc',
                    ];
                    $joins = [
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'transaksi.id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }
                break;

            case '10':
                // Laporan Produksi Finance
                $select = [
                    'transid                AS "Nomor Transaksi"',
                    'transaksi.created_at   AS "Tanggal Dibuat"',
                    'cetak_cn.created_at    AS "Tanggal CN"',
                    'sts.msdesc             AS "Status"',
                    DB::raw('CONCAT(\'PT. KB BUKOPIN, TBk CAB \', nama_cabang, \' QQ \', nama_insured) AS "Tertanggung"'),
                    'alamat_cabang          AS "Alamat Cabang Bukopin"',
                    'nama_asuransi          AS "Nama Asuransi"',
                    'instype_name           AS "Jenis Asuransi"',
                    'policy_no              AS "Nomor Polis"',
                    'cover_note             AS "Covernote"',
                    'location               AS "Lokasi Okupasi"',
                    'polis_start            AS "Polis Mulai"',
                    'polis_end              AS "Polis Selesai"',
                    'tsi.value              AS "Nilai Pertanggungan"',
                    'premi.value            AS "Premium"',
                    'polis.value            AS "By. Polis"',
                    'materai.value          AS "By. Materai"',
                    'lain.value             AS "By. Lain"',
                    'tagihan.value          AS "Tagihan"',
                    'hutang.value           AS "Hutang"',
                    'komisi.value           AS "Komisi"',
                    'reward.value           AS "Reward"',
                    'ppn.value              AS "PPN"',
                    'pph.value              AS "PPh23"',
                    'netkomisi.value        AS "Net Komisi"',
                    'rekening_asuransi      AS "Rekening Asuransi"',
                    'terima.paid_amount     AS "Jumlah Terima"',
                    'terima.paid_at         AS "Tanggal Terima"',
                    'bayar.paid_amount      AS "Jumlah Bayar"',
                    'bayar.paid_at          AS "Tanggal Bayar"',
                ];
                if (!empty($request->dtable)) {
                    $table->whereBetween('transaksi.created_at', [$request->periode_start, $request->periode_end]);

                    $column = [
                        'transid',
                        'transaksi.created_at',
                        'cetak_cn.created_at',
                        'sts.msdesc',
                        'nama_cabang',
                        'alamat_cabang',
                        'nama_asuransi',
                        'instype_name',
                        'policy_no',
                        'cover_note',
                        'location',
                        'polis_start',
                        'polis_end',
                        'tsi.value',
                        'premi.value',
                        'polis.value',
                        'materai.value',
                        'lain.value',
                        'tagihan.value',
                        'hutang.value',
                        'komisi.value',
                        'reward.value',
                        'ppn.value',
                        'pph.value',
                        'netkomisi.value',
                        'rekening_asuransi',
                        'terima.paid_amount',
                        'terima.paid_at',
                        'bayar.paid_amount',
                        'bayar.paid_at',
                    ];

                    $joins = [
                        ['insured', 'id_insured = insured.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'transaksi.id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                        ['transaksi_pricing AS polis', ['transid = polis.id_transaksi', 'polis.id_kodetrans = 10']],
                        ['transaksi_pricing AS materai', ['transid = materai.id_transaksi', 'materai.id_kodetrans = 11']],
                        ['transaksi_pricing AS lain', ['transid = lain.id_transaksi', 'lain.id_kodetrans = 16']],
                        ['transaksi_pricing AS tagihan', ['transid = tagihan.id_transaksi', 'tagihan.id_kodetrans = 18']],
                        ['transaksi_pricing AS hutang', ['transid = hutang.id_transaksi', 'hutang.id_kodetrans = 19']],
                        ['transaksi_pricing AS komisi', ['transid = komisi.id_transaksi', 'komisi.id_kodetrans = 13']],
                        ['transaksi_pricing AS reward', ['transid = reward.id_transaksi', 'reward.id_kodetrans = 21']],
                        ['transaksi_pricing AS ppn', ['transid = ppn.id_transaksi', 'ppn.id_kodetrans = 14']],
                        ['transaksi_pricing AS pph', ['transid = pph.id_transaksi', 'pph.id_kodetrans = 15']],
                        ['transaksi_pricing AS netkomisi', ['transid = netkomisi.id_transaksi', 'netkomisi.id_kodetrans = 17']],
                        ['transaksi_pembayaran AS terima', ['transid = terima.id_transaksi', 'terima.paid_type = PD01']],
                        ['transaksi_pembayaran AS bayar', ['transid = bayar.id_transaksi', 'bayar.paid_type = PD02']],
                        ['activities AS cetak_cn', ['transid = cetak_cn.id_transaksi', 'cetak_cn.id_status = 5']],
                    ];
                }
                break;

            case '11':
                // Laporan CN Belum Dibayar
                $select = [
                    'transaksi.created_at   AS "Tanggal Produksi"',
                    'transid                AS "Nomor Transaksi"',
                    'cif                    AS "CIF"',
                    'nopinjaman             AS "Nomor Pinjaman"',
                    'nama_cabang            AS "Cabang KB Bukopin"',
                    'cover_note             AS "Covernote"',
                    'nama_insured           AS "Nama Tertanggung"',
                    'nama_asuransi          AS "Asuransi Penanggung"',
                    'nama_okupasi           AS "Okupasi"',
                    'location               AS "Lokasi Okupasi"',
                    'instype_name           AS "Tipe Asuransi"',
                    'tsi.value              AS "Nilai Pertanggungan"',
                    'premi.value            AS "Tag Premium"',
                    'polis_start            AS "Polis Mulai"',
                    'polis_end              AS "Polis Selesai"',
                    'catatan                AS "Keterangan"',
                ];
                if (!empty($request->dtable)) {
                    $table->whereBetween('transaksi.created_at', [$request->periode_start, $request->periode_end])
                        ->where('transaksi.id_status', 5);

                    $column = [
                        'transaksi.created_at',
                        'transid',
                        'cif',
                        'nopinjaman',
                        'nama_insured',
                        'alamat_insured',
                        'nama_cabang',
                        'nama_asuransi',
                        'plafond_kredit',
                        'cover_note',
                        'policy_no',
                        'instype_name',
                        'polis_start',
                        'polis_end',
                        'nama_okupasi',
                        'location',
                        'tsi.value',
                        'premi.value',
                        'sts.msdesc',
                        'catatan',
                    ];
                    $joins = [
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'transaksi.id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }

                break;

            case '12':
                // Laporan CN Dibayar
                $select = [
                    'transaksi.created_at   AS "Tanggal Produksi"',
                    'transid                AS "Nomor Transaksi"',
                    'cif                    AS "CIF"',
                    'nopinjaman             AS "Nomor Pinjaman"',
                    'nama_cabang            AS "Cabang KB Bukopin"',
                    'cover_note             AS "Covernote"',
                    'nama_insured           AS "Nama Tertanggung"',
                    'nama_asuransi          AS "Asuransi Penanggung"',
                    'nama_okupasi           AS "Okupasi"',
                    'location               AS "Lokasi Okupasi"',
                    'instype_name           AS "Tipe Asuransi"',
                    'tsi.value              AS "Nilai Pertanggungan"',
                    'premi.value            AS "Tag Premium"',
                    'polis_start            AS "Polis Mulai"',
                    'polis_end              AS "Polis Selesai"',
                    'pemb.paid_at           AS "Tanggal Dibayar"',
                    'catatan                AS "Keterangan"',
                ];
                if (!empty($request->dtable)) {
                    $table->whereBetween('transaksi.created_at', [$request->periode_start, $request->periode_end])
                        ->whereRaw('pemb.id IS NOT NULL');

                    $column = [
                        'transaksi.created_at',
                        'transid',
                        'cif',
                        'nopinjaman',
                        'nama_insured',
                        'alamat_insured',
                        'nama_cabang',
                        'nama_asuransi',
                        'plafond_kredit',
                        'cover_note',
                        'policy_no',
                        'instype_name',
                        'polis_start',
                        'polis_end',
                        'nama_okupasi',
                        'location',
                        'tsi.value',
                        'premi.value',
                        'sts.msdesc',
                        'catatan',
                    ];
                    $joins = [
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'transaksi.id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pembayaran AS pemb', ['pemb.id_transaksi = transid', 'pemb.paid_type = PD01']],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }

                break;

            case '13':
                // Report Outstanding
                $select = [
                    DB::raw('DATE_FORMAT(transaksi.created_at, \'%d-%m-%Y\') AS "Tanggal Produksi"'),
                    DB::raw('DATE_FORMAT(DATE_ADD(billing_at, INTERVAL 30 DAY), \'%d-%m-%Y\') AS "Tagihan Jatuh Tempo"'),
                    'nama_cabang            AS "Cabang KB Bukopin"',
                    'transid                AS "Nomor Transaksi"',
                    'nama_insured           AS "Nama Tertanggung"',
                    'nama_asuransi          AS "Asuransi Penanggung"',
                    'cover_note             AS "Covernote"',
                    'policy_no              AS "No Polis"',
                    'gross.value            AS "Tagihan Premi"'
                ];
                if (!empty($request->dtable)) {
                    $table->whereBetween('transaksi.created_at', [$request->periode_start, $request->periode_end])
                        ->where('id_status', 5);

                    $column = [
                        'transaksi.created_at',
                        'billing_at',
                        'nama_cabang',
                        'transid',
                        'nama_insured',
                        'nama_asuransi',
                        'cover_note',
                        'policy_no',
                        'gross.value',
                    ];
                    $joins = [
                        ['insured', 'id_insured = insured.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing AS gross', ['transid = gross.id_transaksi', 'gross.id_kodetrans = 18']],
                    ];
                }
                break;

            case '14':
                // Data Order Polis - TPI
                $select = [
                    'kode_okupasi           AS "Kode Okupasi"',
                    'location               AS "Lokasi Okupasi"',
                    DB::raw('CONCAT(kodepos.kodepos, \' / \', kodepos.kelurahan) AS "Kode Pos / Kelurahan"'),
                    'nama_okupasi           AS "Nama Okupasi"',
                    'no_jaminan             AS "Nomor Jenis Jaminan"',
                    'transid                AS "Nomor App"',
                    'nama_asuransi          AS "Asuransi"',
                    'instype_name           AS "Tipe Asuransi"',
                    'nama_cabang            AS "Cabang"',
                    'nama_insured           AS "Nama Tertanggung"',
                    'nik_insured            AS "NIK"',
                    'npwp_insured           AS "NPWP"',
                    'policy_no              AS "No. Polis"',
                    'cover_note             AS "Cover Note"',
                    'nopinjaman             AS "Nomor Pinjaman"',
                    'polis_start            AS "Polis Mulai"',
                    'polis_end              AS "Polis Selesai"',
                    DB::raw('IF(bangunan.value IS NULL, 0, bangunan.value) AS "Nilai Bangunan"'),
                    DB::raw('IF(kendaraan.value IS NULL, 0, kendaraan.value) AS "Nilai Kendaraan"'),
                    DB::raw('IF(mesin.value IS NULL, 0, mesin.value) AS "Nilai Mesin"'),
                    DB::raw('IF(stok.value IS NULL, 0, stok.value) AS "Nilai Stok"'),
                    DB::raw('IF(inventaris.value IS NULL, 0, inventaris.value) AS "Nilai Inventaris"'),
                    DB::raw('IF(perabotan.value IS NULL, 0, perabotan.value) AS "Nilai Perabotan"'),
                    DB::raw('IF(lain.value IS NULL, 0, lain.value) AS "Nilai Lain-Lain"'),
                    'tsi.value              AS "Nilai Pertanggungan"',
                    'premi.value            AS "Premium"',
                    'transaksi.created_at   AS "Tanggal Dibuat"',
                    'sts.msdesc             AS "Status"',
                ];
                if (!empty($request->dtable)) {
                    $table->whereBetween('transaksi.created_at', [$request->periode_start, $request->periode_end])
                        ->where('transaksi.id_status', '=', '7')
                        // digrup biar ga duplikat laporannya
                        ->groupBy('transid', 
                            'kode_okupasi', 
                            'location', 
                            'kodepos', 
                            'kelurahan', 
                            'nama_okupasi', 
                            'no_jaminan', 
                            'transid', 
                            'nama_asuransi',
                            'instype_name', 
                            'nama_cabang', 
                            'nama_insured', 
                            'nik_insured', 
                            'npwp_insured', 
                            'policy_no', 
                            'cover_note', 
                            'nopinjaman', 
                            'polis_start', 
                            'polis_end', 
                            'bangunan.value', 
                            'kendaraan.value', 
                            'mesin.value', 
                            'stok.value', 
                            'inventaris.value', 
                            'perabotan.value', 
                            'lain.value', 
                            'tsi.value', 
                            'premi.value', 
                            'transaksi.created_at', 
                            'sts.msdesc');

                    $column = [
                        'kode_okupasi',
                        'location',
                        DB::raw('CONCAT(kodepos.kodepos, \' / \', kodepos.kelurahan)'),
                        'nama_okupasi',
                        'no_jaminan',
                        'transid',
                        'nama_asuransi',
                        'instype_name',
                        'nama_cabang',
                        'nama_insured',
                        'nik_insured',
                        'npwp_insured',
                        'policy_no',
                        'cover_note',
                        'nopinjaman',
                        'polis_start',
                        'polis_end',
                        'bangunan.value',
                        'kendaraan.value',
                        'mesin.value',
                        'stok.value',
                        'inventaris.value',
                        'perabotan.value',
                        'lain.value', 
                        'tsi.value',
                        'premi.value',
                        'transaksi.created_at',
                        'sts.msdesc',
                    ];
                    $joins = [
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'transaksi.id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['kodepos', 'id_kodepos = kodepos.id'],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                        ['transaksi_pricing AS bangunan', ['transid = bangunan.id_transaksi', 'bangunan.id_kodetrans = 3', 'bangunan.value <> 0', 'bangunan.id_objek IS NULL']],
                        ['transaksi_pricing AS kendaraan', ['transid = kendaraan.id_transaksi','kendaraan.id_kodetrans = 4','kendaraan.value <> 0', 'kendaraan.id_objek IS NULL']],
                        ['transaksi_pricing AS mesin', ['transid = mesin.id_transaksi','mesin.id_kodetrans = 5','mesin.value <> 0', 'mesin.id_objek IS NULL']],
                        ['transaksi_pricing AS stok', ['transid = stok.id_transaksi','stok.id_kodetrans = 6','stok.value <> 0', 'stok.id_objek IS NULL']],
                        ['transaksi_pricing AS inventaris', ['transid = inventaris.id_transaksi','inventaris.id_kodetrans = 7','inventaris.value <> 0', 'inventaris.id_objek IS NULL']],
                        ['transaksi_pricing AS perabotan', ['transid = perabotan.id_transaksi','perabotan.id_kodetrans = 8','perabotan.value <> 0', 'perabotan.id_objek IS NULL']],
                        ['transaksi_pricing AS lain', ['transid = lain.id_transaksi','lain.id_kodetrans = 9','lain.value <> 0', 'lain.id_objek IS NULL']],
                    ];
                }
                break;

            default:
                return redirect()->route('logout');
                break;
        }

        if (!empty($request->dtable)) {
            $query = $dataController->generateQuery($request, $table, $column, $select, $joins);

            $data = [];
            foreach ($query[0] as $row) {
                $nestedData = array();
                foreach ($row as $item) {
                    $nestedData[] = $item;
                }
                $data[] = $nestedData;
            }

            return response()->json([
                "draw"            => intval($request->draw),
                "recordsTotal"    => intval($query[1]),
                "recordsFiltered" => intval($query[2]),
                "data"            => $data,
                "sql"             => $query[3],
            ], 200);
        }

        return $select;
    }
}

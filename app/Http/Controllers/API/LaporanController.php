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
            $table->where('id_instype','=',$request->instype);
        }

        if ($request->cabang !== 'ALL') {
            $table->where('id_cabang','=',$request->cabang);
        }

        if ($request->asuransi !== 'ALL') {
            $table->where('id_asuransi','=',$request->asuransi);
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
                    $table->whereBetween('transaksi.created_at',[$request->periode_start, $request->periode_end]);

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
                        ['instype', 'id_instype = instype.id'],
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
                        ->where('id_status','=','5');

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
                        ['instype', 'id_instype = instype.id'],
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
                    'pd.created_at          AS "Tanggal Input Klaim"',
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
                    ];
                    $joins = [
                            ['transaksi_pembayaran AS pd', 'transid = pd.id_transaksi'],
                            ['insured', 'id_insured = insured.id'],
                            ['okupasi', 'id_okupasi = okupasi.id'],
                            ['asuransi', 'id_asuransi = asuransi.id'],
                            ['instype', 'id_instype = instype.id'],
                            ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                            ['cabang', 'id_cabang = cabang.id'],
                            ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                            ['transaksi_pricing AS premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                        ];
                }
                break;

            case '4':
                // Laporan Pembatalan
                $select = [
                    'nopinjaman         AS "Nomor Pinjaman"',
                    'nama_insured       AS "Tertanggung"',
                    'alamat_insured     AS "Alamat Tertanggung"',
                    'nama_cabang        AS "Cabang KB Bukopin"',
                    'plafond_kredit     AS "Plafond"',
                    'cover_note         AS "Covernote"',
                    'policy_no          AS "Nomor Polis"',
                    'instype_name       AS "Tipe Asuransi"',
                    'polis_start        AS "Polis Mulai"',
                    'polis_end          AS "Polis Selesai"',
                    'nama_okupasi       AS "Okupasi"',
                    'location           AS "Lokasi Okupasi"',
                    'tsi.value          AS "Nilai Pertanggungan"',
                    'premi.value        AS "Premium"',
                    'sts.msdesc         AS "Status"',
                ];
                if (!empty($request->dtable)) {
                    $column = [
                        'transid',
                        'cif',
                        'nopinjaman',
                        'nama_insured',
                        'alamat_insured',
                        'nama_cabang',
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
                    ];
                    $joins = [
                        ['insured', 'id_insured = insured.id'],
                        ['instype', 'id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }
                break;

            case '5':
                // Laporan Refund
                $select = [
                    'nopinjaman         AS "Nomor Pinjaman"',
                    'nama_insured       AS "Tertanggung"',
                    'alamat_insured     AS "Alamat Tertanggung"',
                    'nama_cabang        AS "Cabang KB Bukopin"',
                    'plafond_kredit     AS "Plafond"',
                    'cover_note         AS "Covernote"',
                    'policy_no          AS "Nomor Polis"',
                    'instype_name       AS "Tipe Asuransi"',
                    'polis_start        AS "Polis Mulai"',
                    'polis_end          AS "Polis Selesai"',
                    'nama_okupasi       AS "Okupasi"',
                    'location           AS "Lokasi Okupasi"',
                    'tsi.value          AS "Nilai Pertanggungan"',
                    'premi.value        AS "Premium"',
                    'sts.msdesc         AS "Status"',
                    'catatan            AS "Catatan"',
                ];
                if (!empty($request->dtable)) {
                    $column = [
                        'transid',
                        'cif',
                        'nopinjaman',
                        'nama_insured',
                        'alamat_insured',
                        'nama_cabang',
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
                        ['instype', 'id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }
                break;

            case '6':
                // Laporan Reject
                $select = [
                    'act.created_at         AS "Tanggal Penolakan"',
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
                    'act.deskripsi          AS "Catatan"',
                ];
                if (!empty($request->dtable)) {
                    $table->whereBetween('act.created_at', [$request->periode_start, $request->periode_end])
                        ->where('transaksi.id_status', '=', '6');

                    $column = [
                        'act.created_at',
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
                        'act.deskripsi'
                    ];
                    $joins = [
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['activities AS act', ['transid = act.id_transaksi', 'act.id_status = 6']],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
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
                    'outstanding_kredit     AS "Outstanding Kredit"',
                    'policy_no              AS "Nomor Polis"',
                    'policy_parent          AS "Nomor Polis Lama"',
                    DB::raw('DATEDIFF(' . date('Y-m-d') . ',polis_end) AS "Lama Jatuh Tempo"'),
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
                        'transid',
                        'polis_end',
                    ];
                    $joins = [
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'id_instype = instype.id'],
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
                    'nik_insured            AS "Nomor KTP"',
                    'npwp_insured           AS "NPWP"',
                    'kjpp_start             AS "KJPP Mulai"',
                    'kjpp_end               AS "KJPP Selesai"',
                    'instype_name           AS "Tipe Asuransi"',
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
                        ['instype', 'id_instype = instype.id'],
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
                        ['instype', 'id_instype = instype.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing AS tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing AS premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']
                        ],
                    ];
                }
                break;

            case '10':
                // Laporan Produksi Finance
                $select = [
                    'transid                AS "Nomor Transaksi"',
                    'transaksi.created_at   AS "Tanggal Dibuat"',
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
                        ['instype', 'id_instype = instype.id'],
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
                        ['instype', 'id_instype = instype.id'],
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
                        ['instype', 'id_instype = instype.id'],
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
                        ->where('id_status',5);

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

            default:
                return redirect()->route('logout');
                break;
        }

        if (!empty($request->dtable)) {
            $query = $dataController->generateQuery($request, $table, $column, $select, $joins);

            $data = [];
            foreach($query[0] as $row) {
                $nestedData = array();
                foreach($row as $item) {
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
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
                    'transaksi.created_at   as "Tanggal Dibuat"',
                    'transid                as "Nomor Transaksi"',
                    'cif                    as "CIF"',
                    'nopinjaman             as "Nomor Pinjaman"',
                    'nama_insured           as "Tertanggung"',
                    'alamat_insured         as "Alamat Tertanggung"',
                    'nama_cabang            as "Cabang KB Bukopin"',
                    'nama_asuransi          as "Asuransi"',
                    'plafond_kredit         as "Plafond"',
                    'instype_name           as "Tipe Asuransi"',
                    'polis_start            as "Polis Mulai"',
                    'polis_end              as "Polis Selesai"',
                    'nama_okupasi           as "Okupasi"',
                    'location               as "Lokasi Okupasi"',
                    'tsi.value              as "Nilai Pertanggungan"',
                    'premi.value            as "Premium"',
                    'sts.msdesc             as "Status"',
                    'catatan                as "Catatan"',
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
                        ['masters as sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }
                break;

            case '2':
                // Laporan Pembayaran
                $select = [
                    'pd.created_at          as "Tanggal Dibayar"',
                    'transid                as "Nomor Transaksi"',
                    'cif                    as "CIF"',
                    'nopinjaman             as "Nomor Pinjaman"',
                    'nama_insured           as "Tertanggung"',
                    'alamat_insured         as "Alamat Tertanggung"',
                    'nama_cabang            as "Cabang KB Bukopin"',
                    'nama_asuransi          as "Asuransi"',
                    'plafond_kredit         as "Plafond"',
                    'instype_name           as "Tipe Asuransi"',
                    'polis_start            as "Polis Mulai"',
                    'polis_end              as "Polis Selesai"',
                    'nama_okupasi           as "Okupasi"',
                    'location               as "Lokasi Okupasi"',
                    'tsi.value              as "Nilai Pertanggungan"',
                    'premi.value            as "Premium"',
                    'sts.msdesc             as "Status"',
                    'catatan                as "Catatan"',
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
                        ['transaksi_pembayaran as pd', 'transid = pd.id_transaksi'],
                        ['insured', 'id_insured = insured.id'],
                        ['okupasi', 'id_okupasi = okupasi.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['instype', 'id_instype = instype.id'],
                        ['masters as sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }
                break;

            case '3':
                // Laporan Klaim
                $select = [
                    'pd.created_at          as "Tanggal Input Klaim"',
                    'transid                as "Nomor Transaksi"',
                    'cif                    as "CIF"',
                    'nopinjaman             as "Nomor Pinjaman"',
                    'nama_insured           as "Tertanggung"',
                    'alamat_insured         as "Alamat Tertanggung"',
                    'nama_cabang            as "Cabang KB Bukopin"',
                    'nama_asuransi          as "Asuransi"',
                    'plafond_kredit         as "Plafond"',
                    'instype_name           as "Tipe Asuransi"',
                    'polis_start            as "Polis Mulai"',
                    'polis_end              as "Polis Selesai"',
                    'nama_okupasi           as "Okupasi"',
                    'location               as "Lokasi Okupasi"',
                    'tsi.value              as "Nilai Pertanggungan"',
                    'premi.value            as "Premium"',
                    'sts.msdesc             as "Status"',
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
                            ['transaksi_pembayaran as pd', 'transid = pd.id_transaksi'],
                            ['insured', 'id_insured = insured.id'],
                            ['okupasi', 'id_okupasi = okupasi.id'],
                            ['asuransi', 'id_asuransi = asuransi.id'],
                            ['instype', 'id_instype = instype.id'],
                            ['masters as sts', ['id_status = sts.msid', 'sts.mstype = status']],
                            ['cabang', 'id_cabang = cabang.id'],
                            ['transaksi_pricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                            ['transaksi_pricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                        ];
                }
                break;

            case '4':
                // Laporan Pembatalan
                $select = [
                    'nopinjaman         as "Nomor Pinjaman"',
                    'nama_insured       as "Tertanggung"',
                    'alamat_insured     as "Alamat Tertanggung"',
                    'nama_cabang        as "Cabang KB Bukopin"',
                    'plafond_kredit     as "Plafond"',
                    'instype_name       as "Tipe Asuransi"',
                    'polis_start        as "Polis Mulai"',
                    'polis_end          as "Polis Selesai"',
                    'nama_okupasi       as "Okupasi"',
                    'location           as "Lokasi Okupasi"',
                    'tsi.value          as "Nilai Pertanggungan"',
                    'premi.value        as "Premium"',
                    'sts.msdesc         as "Status"',
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
                        ['masters as sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }
                break;

            case '5':
                // Laporan Refund
                $select = [
                    'nopinjaman         as "Nomor Pinjaman"',
                    'nama_insured       as "Tertanggung"',
                    'alamat_insured     as "Alamat Tertanggung"',
                    'nama_cabang        as "Cabang KB Bukopin"',
                    'plafond_kredit     as "Plafond"',
                    'instype_name       as "Tipe Asuransi"',
                    'polis_start        as "Polis Mulai"',
                    'polis_end          as "Polis Selesai"',
                    'nama_okupasi       as "Okupasi"',
                    'location           as "Lokasi Okupasi"',
                    'tsi.value          as "Nilai Pertanggungan"',
                    'premi.value        as "Premium"',
                    'sts.msdesc         as "Status"',
                    'catatan            as "Catatan"',
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
                        ['masters as sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }
                break;

            case '6':
                // Laporan Reject
                $select = [
                    'act.created_at         as "Tanggal Penolakan"',
                    'transid                as "Nomor Transaksi"',
                    'cif                    as "CIF"',
                    'nopinjaman             as "Nomor Pinjaman"',
                    'nama_insured           as "Tertanggung"',
                    'alamat_insured         as "Alamat Tertanggung"',
                    'nama_cabang            as "Cabang KB Bukopin"',
                    'nama_asuransi          as "Asuransi"',
                    'plafond_kredit         as "Plafond"',
                    'instype_name           as "Tipe Asuransi"',
                    'polis_start            as "Polis Mulai"',
                    'polis_end              as "Polis Selesai"',
                    'nama_okupasi           as "Okupasi"',
                    'location               as "Lokasi Okupasi"',
                    'tsi.value              as "Nilai Pertanggungan"',
                    'premi.value            as "Premium"',
                    'sts.msdesc             as "Status"',
                    'act.deskripsi          as "Catatan"',
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
                        ['masters as sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['activities as act', ['transid = act.id_transaksi', 'act.id_status = 6']],
                        ['transaksi_pricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }
                break;

            case '7':
                // Laporan Jatuh Tempo
                $select = [
                    'transaksi.created_at   as "Tanggal Dibuat"',
                    'transid                as "Nomor Transaksi"',
                    'cif                    as "CIF"',
                    'nopinjaman             as "Nomor Pinjaman"',
                    'nama_insured           as "Tertanggung"',
                    'alamat_insured         as "Alamat Tertanggung"',
                    'nama_cabang            as "Cabang KB Bukopin"',
                    'nama_asuransi          as "Asuransi"',
                    'plafond_kredit         as "Plafond"',
                    'instype_name           as "Tipe Asuransi"',
                    'polis_start            as "Polis Mulai"',
                    'polis_end              as "Polis Selesai"',
                    'nama_okupasi           as "Okupasi"',
                    'location               as "Lokasi Okupasi"',
                    'tsi.value              as "Nilai Pertanggungan"',
                    'premi.value            as "Premium"',
                    'sts.msdesc             as "Status"',
                    'catatan                as "Catatan"',
                    'outstanding_kredit     as "Outstanding Kredit"',
                    'policy_no              as "Nomor Polis"',
                    'policy_parent          as "Nomor Polis Lama"',
                    DB::raw('DATEDIFF(' . date('Y-m-d') . ',polis_end) as "Lama Jatuh Tempo"'),
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
                        ['masters as sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }
                break;

            case '8':
                // Laporan Master Asuransi
                $select = [
                    'transaksi.created_at   as "Tanggal Dibuat"',
                    'transid                as "Nomor Transaksi"',
                    'cif                    as "CIF"',
                    'nopinjaman             as "Nomor Pinjaman"',
                    'nama_insured           as "Tertanggung"',
                    'alamat_insured         as "Alamat Tertanggung"',
                    'nama_cabang            as "Cabang KB Bukopin"',
                    'nama_asuransi          as "Asuransi"',
                    'plafond_kredit         as "Plafond"',
                    'nik_insured            as "Nomor KTP"',
                    'npwp_insured           as "NPWP"',
                    'kjpp_start             as "KJPP Mulai"',
                    'kjpp_end               as "KJPP Selesai"',
                    'instype_name           as "Tipe Asuransi"',
                    'policy_no              as "Nomor Polis"',
                    'polis_start            as "Polis Mulai"',
                    'polis_end              as "Polis Selesai"',
                    'nama_okupasi           as "Okupasi"',
                    'location               as "Lokasi Okupasi"',
                    'tsi.value              as "Nilai Pertanggungan"',
                    'premi.value            as "Premium"',
                    'sts.msdesc             as "Status"',
                    'catatan                as "Catatan"',
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
                        ['masters as sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                    ];
                }
                break;

            case '9':
                // Report All Polis
                $select = [
                    'transaksi.created_at   as "Tanggal Dibuat"',
                    'transid                as "Nomor Transaksi"',
                    'cif                    as "CIF"',
                    'nopinjaman             as "Nomor Pinjaman"',
                    'nama_insured           as "Tertanggung"',
                    'alamat_insured         as "Alamat Tertanggung"',
                    'nama_cabang            as "Cabang KB Bukopin"',
                    'nama_asuransi          as "Asuransi"',
                    'plafond_kredit         as "Plafond"',
                    'instype_name           as "Tipe Asuransi"',
                    'polis_start            as "Polis Mulai"',
                    'polis_end              as "Polis Selesai"',
                    'nama_okupasi           as "Okupasi"',
                    'location               as "Lokasi Okupasi"',
                    'tsi.value              as "Nilai Pertanggungan"',
                    'premi.value            as "Premium"',
                    'sts.msdesc             as "Status"',
                    'catatan                as "Catatan"',
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
                        ['masters as sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']
                        ],
                    ];
                }
                break;

            case '10':
                // Laporan Produksi Finance
                $select = [
                    'transid                as "Nomor Transaksi"',
                    'transaksi.created_at   as "Tanggal Dibuat"',
                    'sts.msdesc             as "Status"',
                    DB::raw("CONCAT('PT. KB BUKOPIN, TBk CAB ', nama_cabang, ' QQ ', nama_insured) as Tertanggung"),
                    'alamat_cabang          as "Alamat Cabang Bukopin"',
                    'nama_asuransi          as "Nama Asuransi"',
                    'instype_name           as "Jenis Asuransi"',
                    'policy_no              as "Nomor Polis"',
                    'cover_note             as "Covernote"',
                    'location               as "Lokasi Okupasi"',
                    'polis_start            as "Polis Mulai"',
                    'polis_end              as "Polis Selesai"',
                    'tsi.value              as "Nilai Pertanggungan"',
                    'premi.value            as "Premium"',
                    'polis.value            as "By. Polis"',
                    'materai.value          as "By. Materai"',
                    'lain.value             as "By. Lain"',
                    'tagihan.value          as "Tagihan"',
                    'hutang.value           as "Hutang"',
                    'komisi.value           as "Komisi"',
                    'reward.value           as "Reward"',
                    'ppn.value              as "PPN"',
                    'pph.value              as "PPh23"',
                    'netkomisi.value        as "Net Komisi"',
                    'rekening_asuransi      as "Rekening Asuransi"',
                    'terima.paid_amount     as "Jumlah Terima"',
                    'terima.paid_at         as "Tanggal Terima"',
                    'bayar.paid_amount      as "Jumlah Bayar"',
                    'bayar.paid_at          as "Tanggal Bayar"',
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
                        ['masters as sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                        ['transaksi_pricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
                        ['transaksi_pricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
                        ['transaksi_pricing as polis', ['transid = polis.id_transaksi', 'polis.id_kodetrans = 10']],
                        ['transaksi_pricing as materai', ['transid = materai.id_transaksi', 'materai.id_kodetrans = 11']],
                        ['transaksi_pricing as lain', ['transid = lain.id_transaksi', 'lain.id_kodetrans = 16']],
                        ['transaksi_pricing as tagihan', ['transid = tagihan.id_transaksi', 'tagihan.id_kodetrans = 18']],
                        ['transaksi_pricing as hutang', ['transid = hutang.id_transaksi', 'hutang.id_kodetrans = 19']],
                        ['transaksi_pricing as komisi', ['transid = komisi.id_transaksi', 'komisi.id_kodetrans = 13']],
                        ['transaksi_pricing as reward', ['transid = reward.id_transaksi', 'reward.id_kodetrans = 21']],
                        ['transaksi_pricing as ppn', ['transid = ppn.id_transaksi', 'ppn.id_kodetrans = 14']],
                        ['transaksi_pricing as pph', ['transid = pph.id_transaksi', 'pph.id_kodetrans = 15']],
                        ['transaksi_pricing as netkomisi', ['transid = netkomisi.id_transaksi', 'netkomisi.id_kodetrans = 17']],
                        ['transaksi_pembayaran as terima', ['transid = terima.id_transaksi', 'terima.paid_type = PD01']],
                        ['transaksi_pembayaran as bayar', ['transid = bayar.id_transaksi', 'bayar.paid_type = PD02']],
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

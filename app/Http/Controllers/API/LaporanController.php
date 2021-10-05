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
                    DB::raw('DATEDIFF(' . date('Y-m-d') . ',transaksi.created_at) as "Lama Jatuh Tempo"'),
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
                        'DATEDIFF(transaksi.created_at,' . date('Y-m-d') . ')',
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
                // "sql"             => $query[3],
            ], 200);
        }

        return $select;
    }

    public function dataLaporan(Request $request)
    {
        switch ($request->jenis) {
            case '1':
                
                break;

            default:
                # code...
                break;
        }
    }
}

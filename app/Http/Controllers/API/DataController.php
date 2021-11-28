<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Asuransi;
use App\Models\Document;
use App\Models\Instype;
use App\Models\Insured;
use App\Models\KodePos;
use App\Models\Okupasi;
use App\Models\Pricing;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class DataController extends Controller
{
    function index(Request $request)
    {
        return response()->json([
            'message'   => 'ini halaman data'
        ], 200);
    }

    public function selectKodepos(Request $request)
    {
        // DB::enableQueryLog();
        $provinsi = KodePos::select('id', 'kecamatan', 'kelurahan', 'kodepos', 'rate_TSFWD', 'rate_RSMDCC', 'rate_OTHERS')->distinct();
        if (!empty($request->search)) {
            $provinsi->cariKecamatan($request->search)->orWhere->cariKelurahan($request->search)->orWhere->cariKodePos($request->search);
        }
        $provinsi = $provinsi->get();
        // return DB::getQueryLog();
        $list = [];
        $key = 0;
        foreach ($provinsi as $row) {
            $list[$key]['id'] = $row['id'];
            $list[$key]['text'] = $row['kecamatan'] . " / " . $row['kelurahan'] . " / " . $row['kodepos'];
            $list[$key]['rate_TSFWD'] = $row['rate_TSFWD'];
            $list[$key]['rate_RSMDCC'] = $row['rate_RSMDCC'];
            $list[$key]['rate_OTHERS'] = $row['rate_OTHERS'];
            $key++;
        }
        return response()->json($list);
    }

    public function selectInstype(Request $request)
    {
        $instype = Instype::select('id', 'instype_name', 'brokerage_percent', 'klausula_template', 'max_tsi', 'max_periode_tahun');
        if (!empty($request->search)) {
            $instype->where('id', 'like', '%' . $request->search . '%')
                ->orWhere('instype_name', 'like', '%' . $request->search . '%');
        }
        $instype = $instype->get();
        $list = [];
        $key = 0;
        foreach ($instype as $row) {
            $list[$key]['id'] = $row['id'];
            $list[$key]['text'] = $row['instype_name'];
            $list[$key]['brokerage_percent'] = $row['brokerage_percent'];
            $list[$key]['klausula_template'] = $row['klausula_template'];
            $list[$key]['max_tsi'] = $row['max_tsi'];
            $list[$key]['max_periode_tahun'] = $row['max_periode_tahun'];
            $key++;
        }
        return response()->json($list);
    }

    public function selectInsured(Request $request)
    {
        $insured = Insured::select('*');
        if (!empty($request->search)) {
            $insured->where('nama_insured', 'like', '%' . $request->search . '%');
        }
        $insured = $insured->get();
        $list = [];
        $key = 0;
        foreach ($insured as $row) {
            $list[$key]['id'] = $row['id'];
            $list[$key]['text'] = $row['nama_insured'];
            $list[$key]['npwp_insured'] = $row['npwp_insured'];
            $list[$key]['nik_insured'] = $row['nik_insured'];
            $list[$key]['nohp_insured'] = $row['nohp_insured'];
            $list[$key]['alamat_insured'] = $row['alamat_insured'];
            $key++;
        }
        return response()->json($list);
    }

    public function selectOkupasi(Request $request)
    {
        // return $request->all();
        $okupasi = Okupasi::select('id', 'kode_okupasi', 'nama_okupasi', 'rate');
            // ->where('instype', $request->instype);
        if (!empty($request->search)) {
            $okupasi->where('nama_okupasi', 'like', '%' . $request->search . '%')
                ->orWhere('kode_okupasi', 'like', '%' . $request->search . '%')
                ->orWhere('rate', 'like', '%' . $request->search . '%');
        }
        $okupasi = $okupasi->orderBy('kode_okupasi')->get();

        $list = [];
        $key = 0;
        foreach ($okupasi as $row) {
            $list[$key]['id'] = $row['id'];
            $list[$key]['text'] = $row['kode_okupasi'] . " - (" . $row['rate'] . " ‰) " . $row['nama_okupasi'];
            $list[$key]['rate'] = $row['rate'];
            $key++;
        }
        return response()->json($list);
    }

    public function selectAsuransi(Request $request)
    {
        // return $request->all();
        $asuransi = Asuransi::select('*');
        if (!empty($request->search)) {
            $asuransi->where('nama_asuransi', 'like', '%' . $request->search . '%');
        }
        $asuransi = $asuransi->orderBy('nama_asuransi')->get();

        $list = [];
        $key = 0;
        foreach ($asuransi as $row) {
            $list[$key]['id'] = $row['id'];
            $list[$key]['text'] = $row['nama_asuransi'];
            $key++;
        }
        return response()->json($list);
    }

    public function generateQuery($request, $table, $columns, $select, $joins)
    {
        DB::enableQueryLog();

        if (!empty($joins)) {
            foreach ($joins as $join) {
                if (is_array($join[1])) {
                    $table->leftJoin($join[0], function ($jn) use ($join) {
                        $pecah = explode(" ", $join[1][0]);
                        $jn->on($pecah[0], $pecah[1], $pecah[2]);
                        for ($i = 1; $i < count($join[1]); $i++) {
                            $pecah = explode(" ", $join[1][$i]);
                            $jn->where($pecah[0], $pecah[1], $pecah[2]);
                        }
                    });
                } else {
                    $pecah = explode(" ", $join[1]);
                    $table->leftJoin($join[0], $pecah[0], $pecah[1], $pecah[2]);
                }
            }
        }

        $table->select($select);
        $awal = $table->get()->count();

        if (!empty($request->search)) {
            for ($i = 0; $i < count($columns); $i++) {
                if ($i == 0) {
                    $table->where($columns[$i], 'like', '%' . $request->search . '%');
                } else {
                    $table->orWhere($columns[$i], 'like', '%' . $request->search . '%');
                }
            }
        }

        if (!empty($request->order) && isset($request->order)) {
            for ($i = 0; $i < count($request->order); $i++) {
                $table->orderBy($columns[$request->order[$i]['column']], $request->order[$i]['dir']);
            }
        }

        $all_record = $table->get()->count();

        if (!empty($request->start)) {
            $table->skip($request->start);
        } else {
            $table->skip(0);
        }

        if (!empty($request->length)) {
            $table->take($request->length);
        }

        $result = $table->get();

        // return DB::getQueryLog();
        return [$result, $awal, $all_record, DB::getQueryLog()];
        // return response()->json([
        //     "draw"              => 1,
        //     "recordsTotal"      => $awal,
        //     "recordsFiltered"   => $result->count(),
        //     "data"              => (object) $result,
        // ], 200);
    }

    public function dataDashboard(Request $request)
    {
        $customWhere    = "WHERE 1";
        $user           = Auth::user()->getRoleNames()[0];

        switch ($user) {
            case 'ao':
                $statPengajuan  = "0";
                $statApproval   = "4";
                $statDibayar    = "7";
                $statPolis      = "10";
                $customWhere    .= " AND created_by = ". Auth::user()->id;
                break;
            case 'checker':
                $statPengajuan  = "0";
                $statApproval   = "4";
                $statDibayar    = "7";
                $statPolis      = "10";
                $customWhere    .= " AND id_cabang = " . Auth::user()->id_cabang;
                break;
            case 'approver':
                $statPengajuan  = "1";
                $statApproval   = "4";
                $statDibayar    = "7";
                $statPolis      = "10";
                $customWhere    .= " AND id_cabang = " . Auth::user()->id_cabang;
                break;
            case 'broker':
                $statPengajuan  = "2";
                $statApproval   = "5";
                $statDibayar    = "6,7,8";
                $statPolis      = "10";
                $customWhere    = "";
                break;
            case 'insurance':
                $statPengajuan  = "3";
                $statApproval   = "7";
                $statDibayar    = "7";
                $statPolis      = "8,9,10";
                $customWhere    .= " AND id_asuransi = " . Auth::user()->id_asuransi;
                break;
            case 'finance':
                $statPengajuan  = "3";
                $statApproval   = "5";
                $statDibayar    = "8,10";
                $statPolis      = "10";
                $customWhere    = "";
                break;
            case 'adm':
                $statPengajuan  = "1";
                $statApproval   = "1";
                $statDibayar    = "1";
                $statPolis      = "10";
                $customWhere    = "";
                break;
            
            default:
                $statPengajuan  = "0";
                $statApproval   = "0";
                $statDibayar    = "0";
                $statPolis      = "0";
                break;
        }
        $query = "  SELECT
                        IFNULL(SUM(case when id_status IN ($statPengajuan) then 1 else 0 end), 0) as Pengajuan,
                        IFNULL(SUM(case when id_status IN ($statApproval) then 1 else 0 end), 0) as Approval,
                        IFNULL(SUM(case when id_status IN ($statDibayar) then 1 else 0 end), 0) as Dibayar,
                        IFNULL(SUM(case when id_status IN ($statPolis) then 1 else 0 end), 0) as Polis
                    FROM `transaksi`
                    $customWhere ";
        $result = (object) DB::select($query)[0];
        return $result;
    }

    public function dataTransaksi(Request $request)
    {
        // sorting column datatables
        $columns = [
            'transid',
            'nama_asuransi',
            'instype_name',
            'cabang.nama_cabang',
            'insured.nama_insured',
            'policy_no',
            'cover_note',
            'polis_start',
            'transaksi.created_at',
            'tsi.value',
            'premi.value',
            'sts.msdesc',
        ];

        $select = [
            'transaksi.*',
            'nama_asuransi',
            'instype_name',
            'insured.nama_insured as tertanggung',
            'transaksi.created_at as tgl_dibuat',
            'tsi.value as tsi',
            'premi.value as premi',
            'sts.msdesc as statusnya',
            'cabang.nama_cabang as cabang',
            'cabang.alamat_cabang',
            'docs.lokasi_file'
        ];

        $table = DB::table("transaksi")->whereNull('transaksi.deleted_at');

        $user = Auth::user()->getRoleNames()[0];
        switch ($user) {
            case 'ao':
                $table->where('transaksi.created_by', Auth::user()->id);
                break;

            case 'checker':
                $table->where('transaksi.id_cabang', Auth::user()->id_cabang);
                break;

            case 'approver':
                $table->where('transaksi.id_cabang', Auth::user()->id_cabang);
                break;
                
            case 'broker':
                // wherenya broker
                break;

            case 'insurance':
                $table->where('transaksi.id_asuransi', Auth::user()->id_asuransi);
                break;

            case 'finance':

                break;

            case 'adm':
                // wherenya administrator
                break;

            default:
                return redirect()->route('logout');
                break;
        }

        if (!empty($request->data)) {
            switch ($request->data) {
                case 'pengajuan':
                    switch ($user) {
                        case 'ao':
                            $table->where('id_status', "0");
                            break;

                        case 'checker':
                            $table->where('id_status', "0");
                            break;

                        case 'approver':
                            $table->where('id_status', "1");
                            break;

                        case 'broker':
                            $table->where('id_status', "2");
                            break;

                        case 'insurance':
                            $table->where('id_status', "3");
                            break;

                        case 'finance':
                            
                            break;

                        case 'adm':
                            
                            break;

                        default:
                            return redirect()->route('logout');
                            break;
                    }
                    break;

                case 'approval':
                    switch ($user) {
                        case 'ao':
                            $table->where('id_status', "4");
                            break;

                        case 'checker':
                            $table->where('id_status', "4");
                            break;

                        case 'approver':
                            $table->where('id_status', "4");
                            break;

                        case 'broker':
                            $table->where('id_status', "5");
                            break;

                        case 'insurance':
                            $table->where('id_status', "7");
                            break;

                        case 'finance':
                            $table->where('id_status', "5");
                            break;

                        case 'adm':
                            // wherenya administrator
                            break;
                    }
                    break;

                case 'dibayar':
                    switch ($user) {
                        case 'ao':
                            $table->where('id_status', "7");
                            break;

                        case 'checker':
                            $table->where('id_status', "7");
                            break;

                        case 'approver':
                            $table->where('id_status', "7");
                            break;

                        case 'broker':
                            $table->where('id_status', "8");
                            break;

                        case 'insurance':
                            $table->where('id_status', "7");
                            break;

                        case 'finance':
                            $table->leftJoin('activities pmby', function ($q) use ($user) {
                                $q->on('transaksi.transid', '=', 'pmby.id_transaksi')
                                ->where('pmby.id_status', '=', "9");
                            });
                            $table->whereNull('pmby.id_transaksi');
                            $table->whereIn('transaksi.id_status', ["8","10"]);
                            break;

                        case 'adm':
                            // wherenya administrator
                            break;

                        default:
                            return redirect()->route('logout');
                            break;
                    }
                    break;

                case 'polis siap':
                    switch ($user) {
                        case 'ao':
                            $table->where('id_status', "10");
                            break;

                        case 'checker':
                            $table->where('id_status', "10");
                            break;

                        case 'approver':
                            $table->where('id_status', "10");
                            break;

                        case 'broker':
                            $table->where('id_status', "10");
                            break;

                        case 'insurance':
                            $table->whereIn('id_status', [8,9,10]);
                            break;

                        case 'finance':
                            $table->where('id_status', "10");
                            break;

                        case 'adm':
                            // wherenya administrator
                            break;

                        default:
                            return redirect()->route('logout');
                            break;
                    }
                    break;

                default:
                    return redirect()->route('logout');
                    break;
            }
        }

        $joins = [
            ['insured', 'id_insured = insured.id'],
            ['instype', 'id_instype = instype.id'],
            ['asuransi', 'id_asuransi = asuransi.id'],
            ['masters as sts', ['id_status = sts.msid', "sts.mstype = status"]],
            ['cabang', 'id_cabang = cabang.id'],
            ['documents as docs', ['transid = docs.id_transaksi', 'docs.jenis_file = COVERNOTE']],
            ['transaksi_pricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
            ['transaksi_pricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
        ];

        $query = $this->generateQuery($request, $table, $columns, $select, $joins);

        $data = array();
        foreach ($query[0] as $row) {
            $nestedData = array();
            $nestedData[] = $row->transid;
            $nestedData[] = $row->nama_asuransi;
            $nestedData[] = $row->instype_name;
            $nestedData[] = $row->cabang;
            $nestedData[] = $row->tertanggung;
            $nestedData[] = $row->policy_no;
            $nestedData[] = "<a href='$row->lokasi_file' target='covernote'>$row->cover_note</a>";
            $nestedData[] = date_format(date_create($row->polis_start), "d-M-Y") . " s/d " . date_format(date_create($row->polis_end), "d-M-Y");
            $nestedData[] = $row->tgl_dibuat;
            $nestedData[] = number_format($row->tsi, 2);
            $nestedData[] = number_format($row->premi, 2);
            $nestedData[] = $row->statusnya;

            // hidden
            $nestedData[] = $row->id_status;

            $data[] = $nestedData;
        }

        return response()->json([
            "draw"            => intval($request->draw),
            "recordsTotal"    => intval($query[1]),
            "recordsFiltered" => intval($query[2]),
            "data"            => $data,
            "sql"             => $query[3]
        ], 200);
    }
    
    public function dataPembayaran(Request $request)
    {
        // sorting column datatables
        $columns = [
            'transid',
            'nama_asuransi',
            'instype_name',
            'cabang.nama_cabang',
            'insured.nama_insured',
            'policy_no',
            'cover_note',
            'polis_start',
            'transaksi.created_at',
            'tsi.value',
            'premi.value',
            'sts.msdesc',
        ];

        $select = [
            'transaksi.*',
            'nama_asuransi',
            'instype_name',
            'insured.nama_insured as tertanggung',
            'transaksi.created_at as tgl_dibuat',
            'tsi.value as tsi',
            'premi.value as premi',
            'sts.msdesc as statusnya',
            'cabang.nama_cabang as cabang',
            'cabang.alamat_cabang',
            'docs.lokasi_file'
        ];

        $table = DB::table("transaksi")->whereNull('transaksi.deleted_at');

        $user = Auth::user()->getRoleNames()[0];
        switch ($user) {
            case 'ao':
                $table->where('transaksi.created_by', Auth::user()->id);
                break;

            case 'checker':
                $table->where('transaksi.id_cabang', Auth::user()->id_cabang);
                break;

            case 'approver':
                $table->where('transaksi.id_cabang', Auth::user()->id_cabang);
                break;
                
            case 'broker':
                // wherenya broker
                break;

            case 'insurance':
                $table->where('transaksi.id_asuransi', Auth::user()->id_asuransi);
                break;

            case 'finance':
                // wherenya finance
                break;

            case 'adm':
                // wherenya administrator
                break;

            default:
                return redirect()->route('logout');
                break;
        }

        if (!empty($request->data)) {
            switch ($request->data) {
                case 'pengajuan':
                    switch ($user) {
                        case 'ao':
                            $table->where('id_status', "0");
                            break;

                        case 'checker':
                            $table->where('id_status', "0");
                            break;

                        case 'approver':
                            $table->where('id_status', "1");
                            break;

                        case 'broker':
                            $table->where('id_status', "2");
                            break;

                        case 'insurance':
                            $table->where('id_status', "3");
                            break;

                        case 'finance':
                            
                            break;

                        case 'adm':
                            
                            break;

                        default:
                            return redirect()->route('logout');
                            break;
                    }
                    break;

                case 'approval':
                    switch ($user) {
                        case 'ao':
                            $table->where('id_status', "4");
                            break;

                        case 'checker':
                            $table->where('id_status', "4");
                            break;

                        case 'approver':
                            $table->where('id_status', "4");
                            break;

                        case 'broker':
                            $table->where('id_status', "5");
                            break;

                        case 'insurance':
                            $table->where('id_status', "7");
                            break;

                        case 'finance':
                            $table->where('id_status', "5");
                            break;

                        case 'adm':
                            // wherenya administrator
                            break;
                    }
                    break;

                case 'dibayar':
                    switch ($user) {
                        case 'ao':
                            $table->where('id_status', "7");
                            break;

                        case 'checker':
                            $table->where('id_status', "7");
                            break;

                        case 'approver':
                            $table->where('id_status', "7");
                            break;

                        case 'broker':
                            $table->where('id_status', "8");
                            break;

                        case 'insurance':
                            $table->where('id_status', "7");
                            break;

                        case 'finance':
                            $table->leftJoin('activities pmby', function ($q) use ($user) {
                                $q->on('transaksi.transid', '=', 'pmby.id_transaksi')
                                ->where('pmby.id_status', '=', "9");
                            });
                            $table->whereNull('pmby.id_transaksi');
                            $table->whereIn('transaksi.id_status', ["8","10"]);
                            break;

                        case 'adm':
                            // wherenya administrator
                            break;

                        default:
                            return redirect()->route('logout');
                            break;
                    }
                    break;

                case 'polis siap':
                    switch ($user) {
                        case 'ao':
                            $table->where('id_status', "10");
                            break;

                        case 'checker':
                            $table->where('id_status', "10");
                            break;

                        case 'approver':
                            $table->where('id_status', "10");
                            break;

                        case 'broker':
                            $table->where('id_status', "10");
                            break;

                        case 'insurance':
                            $table->whereIn('id_status', [8,9,10]);
                            break;

                        case 'finance':
                            $table->where('id_status', "10");
                            break;

                        case 'adm':
                            // wherenya administrator
                            break;

                        default:
                            return redirect()->route('logout');
                            break;
                    }
                    break;

                default:
                    return redirect()->route('logout');
                    break;
            }
        }

        $joins = [
            ['insured', 'id_insured = insured.id'],
            ['instype', 'id_instype = instype.id'],
            ['asuransi', 'id_asuransi = asuransi.id'],
            ['masters as sts', ['id_status = sts.msid', "sts.mstype = status"]],
            ['cabang', 'id_cabang = cabang.id'],
            ['documents as docs', ['transid = docs.id_transaksi', 'docs.jenis_file = COVERNOTE']],
            ['transaksi_pricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
            ['transaksi_pricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
        ];

        $query = $this->generateQuery($request, $table, $columns, $select, $joins);

        $data = array();
        foreach ($query[0] as $row) {
            $nestedData = array();
            $nestedData[] = $row->transid;
            $nestedData[] = $row->nama_asuransi;
            $nestedData[] = $row->instype_name;
            $nestedData[] = $row->cabang;
            $nestedData[] = $row->tertanggung;
            $nestedData[] = $row->policy_no;
            $nestedData[] = "<a href='$row->lokasi_file' target='covernote'>$row->cover_note</a>";
            $nestedData[] = date_format(date_create($row->polis_start), "d-M-Y") . " s/d " . date_format(date_create($row->polis_end), "d-M-Y");
            $nestedData[] = $row->tgl_dibuat;
            $nestedData[] = number_format($row->tsi, 2);
            $nestedData[] = number_format($row->premi, 2);
            $nestedData[] = $row->statusnya;

            // hidden
            $nestedData[] = $row->id_status;

            $data[] = $nestedData;
        }

        return response()->json([
            "draw"            => intval($request->draw),
            "recordsTotal"    => intval($query[1]),
            "recordsFiltered" => intval($query[2]),
            "data"            => $data,
            "sql"             => $query[3]
        ], 200);
    }

    public function dataBelumDibayar(Request $request)
    {
        // sorting column datatables
        $columns = [
            'transid',
            'nama_asuransi',
            'instype_name',
            'cabang.nama_cabang',
            'insured.nama_insured',
            'policy_no',
            'cover_note',
            'polis_start',
            'transaksi.created_at',
            'tsi.value',
            'premi.value',
            'sts.msdesc',
        ];

        $select = [
            'transaksi.*',
            'nama_asuransi',
            'instype_name',
            'insured.nama_insured as tertanggung',
            'transaksi.created_at as tgl_dibuat',
            'tsi.value as tsi',
            'premi.value as premi',
            'sts.msdesc as statusnya',
            'cabang.nama_cabang as cabang',
            'cabang.alamat_cabang',
        ];

        $table = DB::table("transaksi")->whereNull('transaksi.deleted_at');

        $user = Auth::user()->getRoleNames()[0];
        switch ($user) {
            case 'broker':
                // wherenya broker
                break;

            case 'finance':

                break;

            case 'adm':
                // wherenya administrator
                break;

            default:
                return redirect()->route('logout');
                break;
        }

        $joins = [
            ['insured', 'id_insured = insured.id'],
            ['instype', 'id_instype = instype.id'],
            ['asuransi', 'id_asuransi = asuransi.id'],
            ['masters as sts', ['id_status = sts.msid', "sts.mstype = status"]],
            ['cabang', 'id_cabang = cabang.id'],
            ['cabang', 'id_cabang = cabang.id'],
            ['transaksi_pricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
            ['transaksi_pricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
        ];

        $query = $this->generateQuery($request, $table, $columns, $select, $joins);

        $data = array();
        foreach ($query[0] as $row) {
            $nestedData = array();
            $nestedData[] = $row->transid;
            $nestedData[] = $row->nama_asuransi;
            $nestedData[] = $row->instype_name;
            $nestedData[] = $row->cabang;
            $nestedData[] = $row->tertanggung;
            $nestedData[] = $row->policy_no;
            $nestedData[] = $row->cover_note;
            $nestedData[] = date_format(date_create($row->polis_start), "d-M-Y") . " s/d " . date_format(date_create($row->polis_end), "d-M-Y");
            $nestedData[] = $row->tgl_dibuat;
            $nestedData[] = number_format($row->tsi, 2);
            $nestedData[] = number_format($row->premi, 2);
            $nestedData[] = $row->statusnya;

            // hidden
            $nestedData[] = $row->id_status;

            $data[] = $nestedData;
        }

        return response()->json([
            "draw"            => intval($request->draw),
            "recordsTotal"    => intval($query[1]),
            "recordsFiltered" => intval($query[2]),
            "data"            => $data,
            "sql"             => $query[3]
        ], 200);
    }

    public function dataPengajuan($transid)
    {
        $select = [
            'transaksi.*',
            'alamat_cabang',
            'insured.nama_insured as tertanggung',
            'insured.npwp_insured',
            'insured.nik_insured',
            'insured.alamat_insured',
            'insured.nohp_insured',
            'okupasi.kode_okupasi',
            'okupasi.nama_okupasi',
            'okupasi.rate',
            'asuransi.nama_asuransi',
            'kecamatan',
            'kelurahan',
            'kodepos',
            'rate_TSFWD',
            'rate_RSMDCC',
            'rate_OTHERS',
            'instype_name',
        ];
        $data = DB::table('transaksi')
            ->leftJoin('cabang', 'id_cabang', '=', 'cabang.id')
            ->leftJoin('insured', 'id_insured', '=', 'insured.id')
            ->leftJoin('kodepos', 'id_kodepos', '=', 'kodepos.id')
            ->leftJoin('okupasi', 'id_okupasi', '=', 'okupasi.id')
            ->leftJoin('instype', 'id_instype', '=', 'instype.id')
            ->leftJoin('asuransi', 'id_asuransi', '=', 'asuransi.id')
            ->where('transid', '=', $transid)
            ->select($select)
            ->first();

        return $data;
    }

    public function dataPricing($transid)
    {
        $data = Pricing::where('id_transaksi', '=', $transid)->get();
        $new = array();
        foreach ($data as $val) {
            $new[$val->id_kodetrans] = $val;
        }
        return $new;
    }

    public function dataAktifitas(Request $request)
    {
        $columns = [
            'activities.created_at',
            'masters.msdesc',
            'name',
            'deskripsi',
        ];

        $select = [
            'activities.created_at',
            'masters.msdesc as statusnya',
            'users.name',
            'activities.deskripsi',
        ];

        $table = DB::table("activities");
        $table->where('id_transaksi', $request->transid);

        $joins = [
            ['masters', ['activities.id_status = masters.msid', 'masters.mstype = status']],
            ['users', 'activities.created_by = users.id'],
        ];

        $query = $this->generateQuery($request, $table, $columns, $select, $joins);
        // return $query;

        $data = array();
        $i = 1;
        foreach ($query[0] as $row) {
            $nestedData = array();
            $nestedData[] = $row->created_at;
            $nestedData[] = $row->statusnya;
            $nestedData[] = $row->name;
            $nestedData[] = $row->deskripsi;

            $data[] = $nestedData;
        }

        return response()->json([
            "draw"            => intval($request->draw),
            "recordsTotal"    => intval($query[1]),
            "recordsFiltered" => intval($query[2]),
            "data"            => $data,
            "sql"             => $query[3]
        ], 200);
    }

    public function dataDokumen(Request $request)
    {
        // return $request->all();
        // die;
        $columns = [
            'documents.id',
            'nama_file',
            'documents.created_at',
            'username',
            'ukuran_file',
        ];

        $select = [
            'documents.*',
            'username',
        ];

        $table = DB::table("documents");
        $table->where('id_transaksi', $request->transid);
        $table->whereNull('documents.deleted_at');

        $joins = [
            ['users', 'documents.created_by = users.id'],
        ];

        $query = $this->generateQuery($request, $table, $columns, $select, $joins);
        // return $query;

        $transaksi = Transaksi::find($request->transid);
        $role = Auth::user()->getRoleNames()[0];

        $data = array();
        foreach ($query[0] as $row) {
            dd(explode(",", $row->visible_by));
            if (!empty($row->visible_by) && !in_array($role,explode(",",$row->visible_by))) {
                continue;
            }
            if (in_array($role,['ao','checker','approver'])) {
                if ($transaksi->id_status <= 8 && $row->jenis_file == 'POLIS') {
                    continue;
                }
            }
            $nestedData = array();
            if ($row->jenis_file !== null) {
                if ($role == 'insurance') {
                    $nestedData[] = '<a style="cursor:pointer" onClick="hapusDokumen(' . $row->id . ')" class="flex items-center text-theme-6 block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                        <i data-feather="trash-2" class="w-4 h-4 dark:text-gray-300 mr-2"></i>
                                        Hapus
                                    </a>';
                } else {
                    $nestedData[] = "";
                }
            } else {
                $nestedData[] = '<a style="cursor:pointer" onClick="hapusDokumen(' . $row->id . ')" class="flex items-center text-theme-6 block p-2 bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                    <i data-feather="trash-2" class="w-4 h-4 dark:text-gray-300 mr-2"></i>
                                    Hapus
                                </a>';
            }
            $nestedData[] = "<i data-feather='link' class='w-4 h-4 dark:text-gray-300 mr-2'></i><a href='" . url($row->lokasi_file) . "' target='_blank'>" . $row->nama_file . "</a>";
            $nestedData[] = $row->created_at;
            $nestedData[] = $row->username;
            $nestedData[] = number_format((float)$row->ukuran_file, 2, '.', '') . " MB";

            $data[] = $nestedData;
        }

        return response()->json([
            "draw"            => intval($request->draw),
            "recordsTotal"    => intval($query[1]),
            "recordsFiltered" => intval($query[2]),
            "data"            => $data,
            "sql"             => $query[3]
        ], 200);
    }

    public function dataUser(Request $request)
    {
        // sorting column datatables
        $columns = [
            'us.name',
            'us.username',
            'us.unpass',
            'pr.username as parent',
            'lv.msdesc as role',
            'nama_cabang'
        ];

        $select = [
            'us.id',
            'us.name',
            'us.username',
            'us.unpass',
            'pr.username as parent',
            'lv.msdesc as role',
            'nama_cabang'
        ];

        $table = DB::table("users as us");

        $joins = [
            ['model_has_roles as mr', 'mr.model_id = us.id'],
            ['roles as r', 'mr.role_id = r.id'],
            ['masters as lv', 'r.name = lv.msid'],
            ['cabang as c', 'c.id = us.id_cabang'],
            ['users as pr', 'pr.id = us.id_parent'],
        ];

        $query = $this->generateQuery($request, $table, $columns, $select, $joins);
        // return $query;

        $data = array();
        foreach ($query[0] as $row) {
            $nestedData = array();
            $nestedData[] = $row->transid;
            $nestedData[] = $row->tipeins;
            $nestedData[] = $row->cabang;
            $nestedData[] = $row->tertanggung;
            $nestedData[] = $row->policy_no;
            $nestedData[] = date_format(date_create($row->polis_start), "d-M-Y") . " s/d " . date_format(date_create($row->polis_end), "d-M-Y");
            $nestedData[] = $row->tgl_dibuat;
            $nestedData[] = number_format($row->tsi, 2);
            $nestedData[] = number_format($row->premi, 2);
            $nestedData[] = $row->statusnya;

            // hidden
            $nestedData[] = $row->id_status;

            $data[] = $nestedData;
        }

        return response()->json([
            "draw"            => intval($request->draw),
            "recordsTotal"    => intval($query[1]),
            "recordsFiltered" => intval($query[2]),
            "data"            => $data,
            // "sql"             => $query[3]
        ], 200);
    }

    public function dataNotifikasi(Request $request) {
        // DB::enableQueryLog();
        // $user = User::find($request->id);
        // $notif = $user->notifications->where('type', 'App\Notifications\PushNotification')->sortBy('created_by');
        // if (!empty($request->search)) {
        //     $notif->where('notifications.data','like', '%'. $request->search. '%');
        // }
        // if (!empty($request->limit)) {
        //     $notif->take($request->limit);
        // }
        // return $request->search;
        // return DB::getQueryLog();
        $notif = DB::table('notifications')
            ->where('notifiable_id',$request->id)
            ->where('type', 'App\Notifications\PushNotification');
        if (!empty($request->search)) {
            $notif->where('data','like', '%'. $request->search. '%');
        }
        if (!empty($request->skip)) {
            $notif->skip($request->skip);
        }
        if (!empty($request->limit)) {
            $notif->take($request->limit);
        }
        return $notif->get();
    }

    public function cariTransaksi(Request $request) {
        $data = [
            'transaksi' => Transaksi::find($request->transid),
            'pricing'   => Pricing::where('id_transaksi',$request->transid)->orderBy('id_kodetrans','ASC')->get(),
        ];
        $data['insured'] = Insured::find($data['transaksi']->id_insured);
        return $data;
    }
}

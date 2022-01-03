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
use Carbon\Carbon;
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
        $customWhere    = "WHERE transaksi.deleted_at IS NULL";
        $user           = Auth::user()->getRoleNames()[0];
        $customJoin     = "";

        switch ($user) {
            case 'maker':
                $statPengajuan  = "0,1";
                $statDibayar    = "6";
                if (Auth::user()->id_cabang !== 1) {    // All Cabang
                    $customWhere    .= " AND transaksi.id_cabang = " . Auth::user()->id_cabang;
                    $customWhere    .= " AND transaksi.created_by = " . Auth::user()->id;
                }
                break;
            case 'checker':
                $statPengajuan  = "0,1";
                $statDibayar    = "6";
                if (Auth::user()->id_cabang !== 1) {    // All Cabang
                    $customWhere    .= " AND transaksi.id_cabang = " . Auth::user()->id_cabang;
                    $customWhere    .= " AND transaksi.created_by = " . Auth::user()->id;
                }
                break;
            case 'approver':
                $statPengajuan  = "1";
                $statDibayar    = "6";
                if (Auth::user()->id_cabang !== 1) {    // All Cabang
                    $customWhere    .= " AND transaksi.id_cabang = " . Auth::user()->id_cabang;
                }
                break;
            case 'broker':
                $statPengajuan  = "1";
                $statDibayar    = "6";
                if (Auth::user()->id_cabang !== 1) {    // All Cabang
                    $customJoin     = " INNER JOIN cabang as cbg_broker ON cbg_broker.id = transaksi.id_cabang AND cbg_broker.id_broker = '".Auth::user()->id."' ";
                }
                break;
            case 'insurance':
                $statPengajuan  = "1";
                $statDibayar    = "9";
                $customWhere    .= " AND id_asuransi = " . Auth::user()->id_asuransi;
                break;
            case 'finance':
                $statPengajuan  = "1";
                $statDibayar    = "6";
                break;
            case 'adm':
                $statPengajuan  = "0,1";
                $statDibayar    = "6";
                break;
            
            default:
                $statDibayar    = "0";
                break;
        }
        $query = "  SELECT
                        IFNULL(SUM(case when transaksi.id_status IN ($statPengajuan) then 1 else 0 end), 0) as Pengajuan,
                        IFNULL(SUM(case when transaksi.id_status IN (2) then 1 else 0 end), 0) as Verifikasi,
                        IFNULL(SUM(case when transaksi.id_status IN (3) then 1 else 0 end), 0) as Asuransi,
                        IFNULL(SUM(case when transaksi.id_status IN (4) then 1 else 0 end), 0) as Bank,
                        IFNULL(SUM(case when transaksi.id_status IN (5) then 1 else 0 end), 0) as Tagihan,
                        IFNULL(SUM(case when activities.id_transaksi IS NOT NULL then 1 else 0 end), 0) as Dibayar,
                        IFNULL(SUM(case when transaksi.id_status IN (10) then 1 else 0 end), 0) as Polis,
                        IFNULL(SUM(case when transaksi.id_status IN (15) then 1 else 0 end), 0) as Batal
                    FROM `transaksi`
                    LEFT JOIN activities ON id_transaksi = transid AND activities.id_status = $statDibayar 
                    $customJoin
                    $customWhere";
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
            'nopinjaman',
            'polis_start',
            'tsi.value',
            'premi.value',
            'transaksi.created_at',
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
            'cn.lokasi_file as lokasi_file_cn',
            'polis.lokasi_file as lokasi_file_polis',
        ];

        $table = DB::table("transaksi")->whereNull('transaksi.deleted_at');

        $user = Auth::user()->getRoleNames()[0];
        switch ($user) {
            case 'maker':
                if (Auth::user()->id_cabang !== 1) {    // All Cabang
                    $table->where('transaksi.created_by', Auth::user()->id);
                    $table->where('transaksi.id_cabang', Auth::user()->id_cabang);
                }
                break;

            case 'checker':
                if (Auth::user()->id_cabang !== 1) {    // All Cabang
                    $table->where('transaksi.created_by', Auth::user()->id);
                    $table->where('transaksi.id_cabang', Auth::user()->id_cabang);
                }
                break;

            case 'approver':
                if (Auth::user()->id_cabang !== 1) {    // All Cabang
                    $table->where('transaksi.id_cabang', Auth::user()->id_cabang);
                }
                break;
                
            case 'broker':
                if (Auth::user()->id_cabang !== 1) {    // All Cabang
                    $table->join('cabang as cbg_broker', function ($q) {
                        $q->on('cbg_broker.id', '=', 'transaksi.id_cabang')
                        ->where('cbg_broker.id_broker', '=', Auth::user()->id);
                    });
                }
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
                    if (in_array($user,['maker','checker','adm'])) {
                        $table->whereIN('id_status', ["0","1"]);
                    } else {
                        $table->whereIN('id_status', ["1"]);
                    }
                    break;

                case 'verifikasi':
                    $table->where('id_status', "2");
                    break;

                case 'persetujuan asuransi':
                    $table->where('id_status', "3");
                    break;

                case 'persetujuan bank':
                    $table->where('id_status', "4");
                    break;

                case 'tagihan':
                    $table->where('id_status', "5");
                    break;

                case 'dibayar':
                    if (in_array($user, ['insurance'])) {
                        $table->leftJoin('activities as pmby', function ($q) use ($user) {
                            $q->on('transaksi.transid', '=', 'pmby.id_transaksi')
                                ->where('pmby.id_status', '=', "9");
                        });
                        $table->whereNotNull('pmby.id_transaksi');
                    } else {
                        $table->leftJoin('activities as pmby', function ($q) use ($user) {
                            $q->on('transaksi.transid', '=', 'pmby.id_transaksi')
                                ->where('pmby.id_status', '=', "6");
                        });
                        $table->whereNotNull('pmby.id_transaksi');
                    }
                    break;

                case 'polis siap':
                    $table->where('id_status', "10");
                    break;

                case 'covernote batal':
                    $table->where('id_status', "15");
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
            ['masters as sts', ['transaksi.id_status = sts.msid', "sts.mstype = status"]],
            ['cabang', 'id_cabang = cabang.id'],
            ['documents as cn', ['transid = cn.id_transaksi', 'cn.jenis_file = COVERNOTE']],
            ['documents as polis', ['transid = polis.id_transaksi', 'polis.jenis_file = POLIS']],
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
            $nestedData[] = "<a href='$row->lokasi_file_polis' target='polis'>$row->policy_no</a>";
            $nestedData[] = "<a href='$row->lokasi_file_cn' target='covernote'>$row->cover_note</a>";
            $nestedData[] = $row->nopinjaman;
            $nestedData[] = date_format(date_create($row->polis_start), "d-M-Y") . " s/d " . date_format(date_create($row->polis_end), "d-M-Y");
            $nestedData[] = number_format($row->tsi, 2);
            $nestedData[] = number_format($row->premi, 2);
            $nestedData[] = date_format(date_create($row->tgl_dibuat), "d-M-Y");
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
            'id_transaksi',
            'nama_asuransi',
            'instype_name',
            'nama_cabang',
            'nama_insured',
            'dc',
            'paid_amount',
            'paid_at',
            'pby.created_by',
            'dsk.msdesc',
        ];

        $select = [
            'pby.*',
            'asn.nama_asuransi',
            'prd.instype_name',
            'cbg.nama_cabang',
            'isd.nama_insured',
            'usr.name',
            'dsk.msdesc as deskripsi'
        ];

        $table = DB::table("transaksi_pembayaran as pby")->whereNull('pby.deleted_at');

        $user = Auth::user()->getRoleNames()[0];

        $joins = [
            ['transaksi as tsk', 'id_transaksi = tsk.transid'],
            ['insured as isd', 'id_insured = isd.id'],
            ['instype as prd', 'id_instype = prd.id'],
            ['asuransi as asn', 'id_asuransi = asn.id'],
            ['cabang as cbg', 'id_cabang = cbg.id'],
            ['users as usr', 'pby.created_by = usr.id'],
            ['masters as dsk', ['paid_type = dsk.msid', "dsk.mstype = paidtype"]],
        ];

        $query = $this->generateQuery($request, $table, $columns, $select, $joins);

        $data = array();
        foreach ($query[0] as $row) {
            $nestedData = array();
            $nestedData[] = $row->id_transaksi;
            $nestedData[] = $row->nama_asuransi;
            $nestedData[] = $row->instype_name;
            $nestedData[] = $row->nama_cabang;
            $nestedData[] = $row->nama_insured;
            $nestedData[] = $row->dc;
            $nestedData[] = number_format($row->paid_amount, 2);
            $nestedData[] = $row->paid_at;
            $nestedData[] = $row->name;
            $nestedData[] = $row->deskripsi;

            // hidden
            $nestedData[] = $row->id;

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
        $role = Auth::user()->getRoleNames()[0];
        $table = DB::table("documents");
        $table->where('id_transaksi', $request->transid);
        $table->where(function($q) use ($role) {
            $q->whereNull('visible_by')
              ->orWhere('visible_by', 'like', '%' . $role . '%');
        });
        $table->whereNull('documents.deleted_at');

        $joins = [
            ['users', 'documents.created_by = users.id'],
        ];

        $query = $this->generateQuery($request, $table, $columns, $select, $joins);
        // return $query;

        $transaksi = Transaksi::find($request->transid);

        $data = array();
        foreach ($query[0] as $row) {
            if (in_array($role,['maker','checker','approver'])) {
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
        $notif = $notif->get();

        $rowdata = [];
        foreach ($notif as $row) {
            $data = [];
            $data['id']                 = $row->id;
            $data['type']               = $row->type;
            $data['notifiable_type']    = $row->notifiable_type;
            $data['data']               = $row->data;
            $data['read_at']            = $row->read_at;
            $data['created_at']         = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->diffForHumans();
            $data[] = $data;

            $rowdata[] = $data;
        }

        return response()->json($rowdata);
    }

    public function cariTransaksi(Request $request) {
        $dataPricing = Pricing::where('id_transaksi', $request->transid)->orderBy('id_kodetrans')->get();
        foreach ($dataPricing as $row) {
            // echo $row->id_kodetrans;
            $pricing[$row->id_kodetrans] = $row;
        }
        $data = [
            'transaksi' => Transaksi::find($request->transid),
            'pricing'   => $pricing,
        ];
        $data['insured'] = Insured::find($data['transaksi']->id_insured);
        return $data;
    }
}

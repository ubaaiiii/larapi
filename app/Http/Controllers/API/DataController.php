<?php

namespace App\Http\Controllers\api;

use App\Helpers\Functions;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Asuransi;
use App\Models\Document;
use App\Models\Installment;
use App\Models\Instype;
use App\Models\Insured;
use App\Models\KodePos;
use App\Models\Okupasi;
use App\Models\Pembayaran;
use App\Models\Perluasan;
use App\Models\Pricing;
use App\Models\Transaksi;
use App\Models\TransaksiObjek;
use App\Models\TransaksiPenanggung;
use App\Models\TransaksiPerluasan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

use function PHPUnit\Framework\isNull;

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
        $provinsi = KodePos::select('*')->distinct();
        if (!empty($request->search)) {
            $provinsi->cariKecamatan($request->search)->orWhere->cariKelurahan($request->search)->orWhere->cariKodePos($request->search);
        }
        $provinsi = $provinsi->get();
        // return DB::getQueryLog();
        $list = [];
        $key = 0;
        foreach ($provinsi as $row) {
            $list[$key]['id'] = $row['id'];
            $list[$key]['text'] = $row['kodepos'] . " / " . $row['kelurahan'] . " / " . $row['kecamatan'];
            $list[$key]['wilayah'] = $row['wilayah'];
            $list[$key]['rate_TSFWD'] = $row['rate_TSFWD'];
            $list[$key]['rate_RSMDCC'] = $row['rate_RSMDCC'];
            $list[$key]['rate_OTHERS'] = $row['rate_OTHERS'];
            $key++;
        }
        // $list['sql'] = DB::getQueryLog();
        return response()->json($list);
    }

    public function selectInstype(Request $request, $bisnis = "sme")
    {
        $instype = Instype::select('id', 'instype_name')->where('bisnis', 'like', '%' . $bisnis . '%');
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

    public function selectOkupasi(Request $request, $format = null)
    {
        // return $request->all();
        // DB::enableQueryLog();
        $okupasi = Okupasi::select('id', 'kode_okupasi', 'nama_okupasi', 'rate');
        // ->where('instype', $request->instype);
        if (!empty($request->search)) {
            $okupasi->where(function ($query) use ($request) {
                return $query->where('nama_okupasi', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_okupasi', 'like', '%' . $request->search . '%')
                    ->orWhere('rate', 'like', '%' . $request->search . '%');
            });
        }
        if ($format == "wholesales") {
            $okupasi->where('bisnis', '=', 'WHOLESALES')
                ->where('id_instype', 'like', '%' . $request->id_instype . '%')
                ->where('wilayah', 'like', '%' . $request->wilayah . '%');
            if (!empty($request->id_kelas)) {
                $okupasi->where('id_kelas_pertanggungan', 'like', '%' . $request->id_kelas . '%');
            }
        } elseif (isNull($format) || $format == "sme") {
            $okupasi->where('bisnis', '=', 'SME');
        }
        $okupasi = $okupasi->orderBy('kode_okupasi')->get();

        $list = [];
        $key = 0;
        foreach ($okupasi as $row) {
            $list[$key]['id'] = $row['id'];
            if ($format == "wholesales") {
                $list[$key]['text'] = $row['kode_okupasi'] . " - " . $row['nama_okupasi'];
            } else {
                $list[$key]['text'] = $row['kode_okupasi'] . " - (" . $row['rate'] . " ‰) " . $row['nama_okupasi'];
            }
            $list[$key]['rate'] = $row['rate'];
            $key++;
        }
        // $list['sql'] = DB::getQueryLog();
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

    public function selectKelas(Request $request)
    {
        // return $request->all();
        $kelas = DB::table("kelas_pertanggungan")
            ->where('id_instype', 'like', '%' . $request->tipe . '%');
        if (!empty($request->search)) {
            $kelas->where('nama_kelas', 'like', '%' . $request->tipe . '%');
        }
        $kelas = $kelas->orderBy('id')->get();

        $list = [];
        $key = 0;
        foreach ($kelas as $row) {
            $list[$key]['id'] = $row->id;
            $list[$key]['text'] = $row->nama_kelas;
            $list[$key]['field'] = $row->nama_field;
            $key++;
        }
        return response()->json($list);
    }

    public function selectPerluasan(Request $request)
    {
        // DB::enableQueryLog();
        $perluasan = Perluasan::where('id_instype', $request->instype);
        $select = [
            'perluasan.id',
            'perluasan.kode',
            'perluasan.keterangan',
            'perluasan.required',
        ];

        if (!empty($request->transid)) {
            array_push(
                $select,
                DB::raw('IF (transaksi_perluasan.id_transaksi IS NOT NULL, IF (transaksi_perluasan.rate IS NOT NULL, transaksi_perluasan.rate, perluasan.rate), perluasan.rate) as rate'),
                DB::raw('IF (transaksi_perluasan.id_transaksi IS NOT NULL, IF (transaksi_perluasan.value IS NOT NULL, transaksi_perluasan.value, 0), 0) as value'),
                DB::raw('IF (transaksi_perluasan.id_transaksi IS NOT NULL, "checked", NULL) as checked')
            );
            $perluasan->leftJoin('transaksi_perluasan', function ($q) use ($request) {
                $q->on('perluasan.id', '=', 'id_perluasan')
                    ->where('id_transaksi', '=', $request->transid);
            })->select($select);
        } else {
            array_push($select, 'perluasan.rate', DB::raw('null as checked'));
            $perluasan->select($select);
        }
        if (!empty($request->search)) {
            $perluasan->where('perluasan.kode', 'like', '%' . $request->search . '%')
                ->orWhere('perluasan.keterangan', 'like', '%' . $request->search . '%');
        }
        // $perluasan->get();
        // return DB::getQueryLog();
        $perluasan = $perluasan->get();

        // return $request->all();
        // $kelas = DB::table("kelas_pertanggungan")
        //     ->where('id_instype', 'like', '%' . $request->tipe . '%');
        // if (!empty($request->search)) {
        //     $kelas->where('nama_kelas', 'like', '%' . $request->tipe . '%');
        // }
        // $kelas = $kelas->orderBy('id')->get();

        // return DB::getQueryLog();

        $list = [];
        foreach ($perluasan as $key => $row) {
            $list[$key]['id'] = $row->id;
            $list[$key]['text'] = $row->kode;
            $list[$key]['field'] = $row->keterangan;
            $list[$key]['rate'] = $row->rate;
            if ($row->required) {
                $list[$key]['selected'] = true;
            } else {
                $list[$key]['selected'] = false;
            }
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
                        if (in_array($pecah[1], ["=", ">", ">=", "<", "<=", "<>"])) {
                            $jn->on($pecah[0], $pecah[1], $pecah[2]);
                        } else {
                            $jn->on(DB::raw($join[1][0]), DB::raw(''), DB::raw(''));
                        }
                        for ($i = 1; $i < count($join[1]); $i++) {
                            $pecah = explode(" ", $join[1][$i]);
                            if (in_array($pecah[1], ["=", ">", ">=", "<", "<=", "<>"])) {
                                $jn->where($pecah[0], $pecah[1], $pecah[2]);
                            } else {
                                $jn->whereRaw($join[1][$i]);
                            }
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
            $table->where(function ($query) use ($columns, $request) {
                for ($i = 0; $i < count($columns); $i++) {
                    if ($i == 0) {
                        $query->where($columns[$i], 'like', '%' . $request->search . '%');
                    } else {
                        $query->orWhere($columns[$i], 'like', '%' . $request->search . '%');
                    }
                }
            });
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
                if (Auth::user()->id_cabang !== 1) {    // All Cabang
                    $customWhere    .= " AND transaksi.id_cabang = " . Auth::user()->id_cabang;
                    $customWhere    .= " AND transaksi.created_by = " . Auth::user()->id;
                }
                break;
            case 'checker':
                $statPengajuan  = "0,1";
                if (Auth::user()->id_cabang !== 1) {    // All Cabang
                    $customWhere    .= " AND transaksi.id_cabang = " . Auth::user()->id_cabang;
                    $customWhere    .= " AND transaksi.created_by = " . Auth::user()->id;
                }
                break;
            case 'approver':
                $statPengajuan  = "1";
                if (Auth::user()->id_cabang !== 1) {    // All Cabang
                    $customWhere    .= " AND transaksi.id_cabang = " . Auth::user()->id_cabang;
                }
                break;
            case 'broker':
                $statPengajuan  = "1";
                if (Auth::user()->id_cabang !== 1) {    // All Cabang
                    $customJoin     = " INNER JOIN cabang as cbg_broker ON cbg_broker.id = transaksi.id_cabang AND cbg_broker.id_broker like \"%'" . Auth::user()->id . "'%\" ";
                }
                break;
            case 'insurance':
                $statPengajuan  = "1";
                $customWhere    .= " AND id_asuransi = " . Auth::user()->id_asuransi;
                break;
            case 'finance':
                $statPengajuan  = "1";
                break;
            case 'adm':
                $statPengajuan  = "0,1";
                break;

            default:

                break;
        }
        $query = "  SELECT
                        IFNULL(SUM(case when transaksi.id_status IN ($statPengajuan) then 1 else 0 end), 0) as Pengajuan,
                        IFNULL(SUM(case when transaksi.id_status IN (2) then 1 else 0 end), 0) as Verifikasi,
                        IFNULL(SUM(case when transaksi.id_status IN (3) then 1 else 0 end), 0) as Asuransi,
                        IFNULL(SUM(case when transaksi.id_status IN (4) then 1 else 0 end), 0) as Bank,
                        IFNULL(SUM(case when transaksi.id_status IN (4.5) then 1 else 0 end), 0) as MenungguFTC,
                        IFNULL(SUM(case when transaksi.id_status IN (5) then 1 else 0 end), 0) as Tagihan,
                        IFNULL(SUM(case when bankPaid.id_transaksi IS NOT NULL AND brokerPaid.id_transaksi IS NULL then 1 else 0 end), 0) as DibayarBank,
                        -- IFNULL(SUM(case when brokerPaid.id_transaksi IS NOT NULL AND transaksi.id_status < 9 then 1 else 0 end), 0) as DibayarBroker,
                        IFNULL(SUM(case when transaksi.id_status IN (7) then 1 else 0 end), 0) as DibayarBroker,
                        IFNULL(SUM(case when transaksi.id_status IN (8) then 1 else 0 end), 0) as PengecekanPolis,
                        IFNULL(SUM(case when transaksi.id_status IN (10) then 1 else 0 end), 0) as Polis,
                        IFNULL(SUM(case when transaksi.id_status IN (18) then 1 else 0 end), 0) as Batal
                    FROM `transaksi`
                    LEFT JOIN transaksi_pembayaran bankPaid ON bankPaid.id_transaksi = transid AND bankPaid.deleted_at IS NULL AND bankPaid.paid_type = 'PD01' 
                    LEFT JOIN transaksi_pembayaran brokerPaid ON brokerPaid.id_transaksi = transid AND brokerPaid.deleted_at IS NULL AND brokerPaid.paid_type = 'PD02' 
                    $customJoin
                    $customWhere";
        $result = (object) DB::select($query)[0];
        return $result;
    }

    public function dataTransaksi(Request $request)
    {
        // sorting column datatables
        $columns = [
            'kode_okupasi',
            'nama_okupasi',
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
            'okupasi.*',
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
                            ->where('cbg_broker.id_broker', 'like', "%'" . Auth::user()->id . "'%");
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
                    if (in_array($user, ['maker', 'checker', 'adm'])) {
                        $table->whereIN('id_status', ["0", "1"]);
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

                case 'dibayar bank':
                    $table->leftJoin('transaksi_pembayaran as pmby', function ($q) use ($user) {
                        $q->on('transaksi.transid', '=', 'pmby.id_transaksi')
                            ->where('pmby.paid_type', '=', "PD01")
                            ->whereNull('pmby.deleted_at');
                    });
                    $table->leftJoin('transaksi_pembayaran as pmby2', function ($q) use ($user) {
                        $q->on('transaksi.transid', '=', 'pmby2.id_transaksi')
                            ->where('pmby2.paid_type', '=', "PD02")
                            ->whereNull('pmby2.deleted_at');
                    });
                    $table->whereNotNull('pmby.id_transaksi')
                        ->whereNull('pmby2.id_transaksi');
                    break;

                    // case 'dibayar broker':
                    //     $table->leftJoin('transaksi_pembayaran as pmby', function ($q) use ($user) {
                    //         $q->on('transaksi.transid', '=', 'pmby.id_transaksi')
                    //             ->where('pmby.paid_type', '=', "PD02")
                    //             ->whereNull('pmby.deleted_at');
                    //     });
                    //     $table->whereNotNull('pmby.id_transaksi')
                    //         ->whereRaw('transaksi.id_status < 9');
                    //     break;

                case 'dibayar broker':
                    $table->where('id_status', "7");
                    break;

                case 'pengecekan polis':
                    $table->where('id_status', "8");
                    break;

                case 'polis siap':
                    $table->where('id_status', "10");
                    break;

                case 'covernote batal':
                    $table->where('id_status', "18");
                    break;

                default:
                    return redirect()->route('logout');
                    break;
            }
        }

        $table->leftJoin('instype', function ($q) {
            $q->on('transaksi.id_instype', '=', 'instype.id')
                ->on('instype.bisnis', '=', 'transaksi.bisnis');
        });

        $joins = [
            ['insured', 'id_insured = insured.id'],
            // ['instype', ['id_instype = instype.id', 'instype.bisnis = transaksi.bisnis']],
            ['okupasi', 'id_okupasi = okupasi.id'],
            ['asuransi', 'id_asuransi = asuransi.id'],
            ['masters as sts', ['transaksi.id_status = sts.msid', "sts.mstype = status"]],
            ['cabang', 'id_cabang = cabang.id'],
            ['documents as cn', ['transid = cn.id_transaksi', 'cn.jenis_file = COVERNOTE']],
            ['documents as polis', ['transid = polis.id_transaksi', 'polis.jenis_file = POLIS']],
            ['transaksi_pricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1', 'id_parent_transaksi is null']],
            ['transaksi_pricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
        ];

        $query = $this->generateQuery($request, $table, $columns, $select, $joins);

        $data = array();
        foreach ($query[0] as $row) {
            $nestedData = array();
            $nestedData[] = $row->kode_okupasi;
            $nestedData[] = $row->nama_okupasi;
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
            $nestedData[] = $row->bisnis;

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
            'pby_bank.paid_at',
            'pby_broker.paid_at',
            'pby_bank.id_transaksi',
            'cover_note',
            'policy_no',
            'nama_asuransi',
            'rekening_asuransi',
            'tagihan.value',
            'komisi.value',
            'ppn.value',
            'pph.value',
            'grossnet.value',
        ];

        $select = [
            'pby_bank.*',
            'pby_bank.paid_at as pby_bank',
            'pby_broker.paid_at as pby_broker',
            'cover_note',
            'policy_no',
            'id_status',
            'asn.nama_asuransi',
            'asn.rekening_asuransi',
            'tagihan.value as tagihan',
            'komisi.value as komisi',
            'ppn.value as ppn',
            'pph.value as pph',
            'grossnet.value as grossnet',
        ];

        $table = DB::table("transaksi_pembayaran as pby_bank")->whereNull('pby_bank.deleted_at')->where('pby_bank.paid_type', 'PD01');

        if (isset($request->filter_sudah_dibayar) && isset($request->filter_belum_dibayar)) {
            $table->whereRaw('1');
        } elseif (!isset($request->filter_sudah_dibayar) && isset($request->filter_belum_dibayar)) {
            $table->whereNull('pby_broker.id_transaksi');
        } elseif (isset($request->filter_sudah_dibayar) && !isset($request->filter_belum_dibayar)) {
            $table->whereNotNull('pby_broker.id_transaksi');
        } else {
            $table->whereRaw('0');
        }

        $joins = [
            ['transaksi as tsk', 'pby_bank.id_transaksi = tsk.transid'],
            ['asuransi as asn', 'id_asuransi = asn.id'],
            ['transaksi_pembayaran as pby_broker', ['pby_broker.id_transaksi = pby_bank.id_transaksi', 'pby_broker.paid_type = PD02', 'pby_broker.deleted_at is null']],
            ['transaksi_pricing as tagihan', ['tagihan.id_transaksi = tsk.transid', 'tagihan.id_kodetrans = 18']],
            ['transaksi_pricing as komisi', ['komisi.id_transaksi = tsk.transid', 'komisi.id_kodetrans = 13']],
            ['transaksi_pricing as ppn', ['ppn.id_transaksi = tsk.transid', 'ppn.id_kodetrans = 14']],
            ['transaksi_pricing as pph', ['pph.id_transaksi = tsk.transid', 'pph.id_kodetrans = 15']],
            ['transaksi_pricing as grossnet', ['grossnet.id_transaksi = tsk.transid', 'grossnet.id_kodetrans = 19']],
        ];

        $query = $this->generateQuery($request, $table, $columns, $select, $joins);

        $data = array();
        foreach ($query[0] as $row) {
            $nestedData = array();
            $nestedData[] = !empty($row->pby_bank) ? date_create($row->pby_bank)->format('d-m-Y') : "<i>Belum Dibayar</i>";
            $nestedData[] = !empty($row->pby_broker) ? date_create($row->pby_broker)->format('d-m-Y') : "<i>Belum Dibayar</i>";
            $nestedData[] = $row->id_transaksi;
            $nestedData[] = $row->cover_note;
            $nestedData[] = $row->policy_no;
            $nestedData[] = $row->nama_asuransi;
            $nestedData[] = $row->rekening_asuransi;
            $nestedData[] = number_format($row->tagihan, 2);
            $nestedData[] = number_format($row->komisi, 2);
            $nestedData[] = number_format($row->ppn, 2);
            $nestedData[] = number_format($row->pph, 2);
            $nestedData[] = number_format($row->grossnet, 2);

            // hidden
            $nestedData[] = !empty($row->pby_broker) ? 0 : 1;

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

    public function dataKlaim(Request $request)
    {
        $table = DB::table("transaksi");

        switch ($request->jenis) {
            case '1':

                break;

            default:
                $select = [
                    'id_transaksi           AS "Nomor Transaksi"',
                    'nama_insured           AS "Nama Tertanggung"',
                    'nama_cabang            AS "Cabang"',
                    'nama_asuransi          AS "Asuransi"',
                    'tgl_lapor              AS "Tanggal Lapor"',
                    'tgl_kejadian           AS "Tanggal Kejadian"',
                    'pic                    AS "PIC"',
                    'kontak_pic             AS "Kontak PIC"',
                    'nama_surveyor          AS "Surveyor"',
                    'nilai_tuntutan         AS "Nilai Tuntutan"',
                    'nilai_ganti_surveyor   AS "Nilai Surveyor"',
                    'nilai_yang_disetujui   AS "Nilai Yg Disetujui"',
                    'sts.msdesc             AS "Status"',
                    'catatan                AS "Catatan"'
                ];
                if (!empty($request->dtable)) {
                    // $table->whereBetween('transaksi.created_at', [$request->periode_start, $request->periode_end]);

                    $column = [
                        'id_transaksi',
                        'nama_insured',
                        'nama_cabang',
                        'nama_asuransi',
                        'tgl_lapor',
                        'tgl_kejadian',
                        'pic',
                        'kontak_pic',
                        'nama_surveyor',
                        'nilai_tuntutan',
                        'nilai_ganti_surveyor',
                        'nilai_yang_disetujui',
                        'sts.msdesc',
                        'catatan"'
                    ];
                    $joins = [
                        ['transaksi', 'transid = id_transaksi'],
                        ['insured', 'id_insured = insured.id'],
                        ['asuransi', 'id_asuransi = asuransi.id'],
                        ['masters AS sts', ['id_status = sts.msid', 'sts.mstype = status']],
                        ['cabang', 'id_cabang = cabang.id'],
                    ];
                }
                // return redirect()->route('logout');
                break;
        }

        if (!empty($request->dashKlaim)) {
            $select = [
                'tgl_lapor              AS "Tanggal Lapor"',
                'id_transaksi           AS "Nomor Transaksi"',
                'tgl_kejadian           AS "Tanggal Kejadian"',
                'pic                    AS "PIC"',
                'kontak_pic             AS "Kontak PIC"',
                'nama_surveyor          AS "Surveyor"',
                'nilai_tuntutan         AS ""',
                'nilai_ganti_surveyor',
                'nilai_yang_disetujui'
            ];
        }

        if (!empty($request->dtable)) {
            $query = $this->generateQuery($request, $table, $column, $select, $joins);

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
            ->leftJoin('kodepos', 'transaksi.id_kodepos', '=', 'kodepos.id')
            ->leftJoin('okupasi', 'id_okupasi', '=', 'okupasi.id')
            ->leftJoin('instype', 'transaksi.id_instype', '=', 'instype.id')
            ->leftJoin('asuransi', 'id_asuransi', '=', 'asuransi.id')
            ->where('transid', '=', $transid)
            ->select($select)
            ->first();

        return $data;
    }

    public function dataPricing($transid)
    {
        $data = Pricing::where('id_transaksi', '=', $transid)->whereNull('id_objek')->get();
        $new = array();
        foreach ($data as $val) {
            $new[$val->id_kodetrans] = $val;
        }
        return $new;
    }

    public function dataPerluasan($transid)
    {
        $data = TransaksiPerluasan::where('id_transaksi', '=', $transid)->get();
        $new = array();
        foreach ($data as $val) {
            $new[$val->id_perluasan] = $val;
        }
        return $new;
    }

    public function dataInstallment($transid)
    {
        $data = Installment::where('id_transaksi', '=', $transid)->orderBy('tgl_tagihan')->get();
        return $data;
    }

    public function dataPenanggung($transid)
    {
        $data = TransaksiPenanggung::join('asuransi', 'id_asuransi', '=', 'asuransi.id')
            ->where('id_transaksi', '=', $transid)
            ->orderBy('share_pertanggungan', 'DESC')->get();
        return $data;
    }

    public function cekPerluasan(Request $request)
    {
        DB::enableQueryLog();
        $perluasan = Perluasan::where('id_instype', $request->instype);
        $select = [
            'perluasan.id',
            'perluasan.kode',
            'perluasan.keterangan',
            'perluasan.required',
        ];

        if (!empty($request->transid)) {
            array_push(
                $select,
                DB::raw('IF (transaksi_perluasan.id_transaksi IS NOT NULL, IF (transaksi_perluasan.rate IS NOT NULL, transaksi_perluasan.rate, perluasan.rate), perluasan.rate) as rate'),
                DB::raw('IF (transaksi_perluasan.id_transaksi IS NOT NULL, IF (transaksi_perluasan.value IS NOT NULL, transaksi_perluasan.value, 0), 0) as value'),
                DB::raw('IF (transaksi_perluasan.id_transaksi IS NOT NULL, "checked", NULL) as checked')
            );
            $perluasan->leftJoin('transaksi_perluasan', function ($q) use ($request) {
                $q->on('perluasan.id', '=', 'id_perluasan')
                    ->where('id_transaksi', '=', $request->transid);
            })->select($select);
        } else {
            array_push($select, 'perluasan.rate', DB::raw('null as checked'));
            $perluasan->select($select);
        }
        // $perluasan->get();
        // return DB::getQueryLog();
        return $perluasan->get();
    }

    public function dataObjekPricing($transid)
    {
        $objek = TransaksiObjek::where('id_transaksi', $transid)->get();
        $data_objek_pricing = [];
        foreach ($objek as $i => $v) {
            $pricing = Pricing::where('id_transaksi', $transid)->where('id_objek', $v->id)->get();
            $data_objek_pricing[$v->id] = $pricing;
        }
        return $data_objek_pricing;
    }

    public function dataObjekPerluasan($transid)
    {
        $objek = TransaksiObjek::where('id_transaksi', $transid)->get();
        $data_objek_perluasan = [];
        $select = [
            'perluasan.id',
            'perluasan.kode',
            'perluasan.keterangan',
            'transaksi_perluasan.id_perluasan',
            DB::raw('IF (transaksi_perluasan.rate IS NOT NULL, transaksi_perluasan.rate, perluasan.rate) as rate'),
            DB::raw('IF (transaksi_perluasan.value IS NOT NULL, transaksi_perluasan.value, 0) as value')
        ];
        foreach ($objek as $v) {
            $perluasan = TransaksiPerluasan::where('id_transaksi', $transid)
                ->join('perluasan', 'id_perluasan', '=', 'perluasan.id')
                ->where('id_objek', $v->id)
                ->select($select)->get();
            $data_objek_perluasan[$v->id] = $perluasan;
        }
        return $data_objek_perluasan;
    }

    public function dataObjek($transid)
    {
        // DB::enableQueryLog();
        $data = TransaksiObjek::join('masters as jaminan', function ($jn) {
            $jn->on('transaksi_objek.id_jaminan', '=', 'jaminan.msid')
                ->where('jaminan.mstype', '=', 'jaminan');
        })
            ->leftJoin('kelas_pertanggungan as kelas', 'transaksi_objek.id_kelas', '=', 'kelas.id')
            ->leftJoin('kodepos', 'transaksi_objek.id_kodepos', '=', 'kodepos.id')
            ->leftJoin('okupasi', 'transaksi_objek.id_okupasi', '=', 'okupasi.id');

        $data->where('transaksi_objek.id_transaksi', $transid);

        $data->select([
            'transaksi_objek.id as id_objek',
            'transaksi_objek.objek',
            'transaksi_objek.alamat_objek',
            'transaksi_objek.id_kodepos',
            DB::raw('CONCAT(kodepos.kodepos, " / ", kelurahan, " / ", kecamatan) as nama_kodepos'),
            'kodepos.wilayah',
            'transaksi_objek.id_jaminan',
            'jaminan.msdesc as nama_jaminan',
            'transaksi_objek.no_jaminan',
            'transaksi_objek.agunan_kjpp',
            'transaksi_objek.id_kelas',
            'kelas.nama_kelas',
            'transaksi_objek.id_okupasi',
            DB::raw('CONCAT(okupasi.kode_okupasi, " - ", okupasi.nama_okupasi) as nama_okupasi'),
            'transaksi_objek.rate',
        ]);

        return $data->get();
        // return DB::getQueryLog();
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

        $table = DB::table("activities")->where('id_transaksi', $request->transid);

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
            $nestedData[] = Carbon::parse($row->created_at)->format('Y-m-d h:m:s');
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
        $table->where(function ($q) use ($role) {
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
            if (in_array($role, ['maker', 'checker', 'approver'])) {
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
            $nestedData[] = "<i data-feather='link' class='w-4 h-4 dark:text-gray-300 mr-2'></i><a href='" . url($row->lokasi_file) . "?t=" . date('Y-m-d_h:m:s') . "' target='_blank'>" . $row->nama_file . "</a>";
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

    public function dataNotifikasi(Request $request)
    {
        $notif = DB::table('notifications')
            ->where('notifiable_id', $request->id)
            ->where('type', 'App\Notifications\PushNotification');
        if (!empty($request->search)) {
            $notif->where('data', 'like', '%' . $request->search . '%');
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

    public function getBiayaKlausula(Request $request)
    {
        if (is_numeric($request->premi) && is_numeric($request->tsi) && is_numeric($request->periode_tahun)) {
            $config = DB::table('asuransi_config')
                ->where('id_insurance', $request->id_insurance)
                ->where('id_instype', $request->id_instype)
                ->whereRaw($request->premi . " BETWEEN `min_premi` AND `max_premi`")
                ->whereRaw($request->tsi . " BETWEEN `min_tsi` AND `max_tsi`");
            // ->whereRaw($request->periode_tahun . " BETWEEN `min_periode_tahun` AND `max_periode_tahun`");

            $data = $config->first();
        } else {
            $data = [
                'by_lain'           => 0,
                'by_materai'        => 0,
                'by_polis'          => 0,
                'klausula_template' => ''
            ];
        }
        return response()->json($data);
    }

    public function cariTransaksi(Request $request)
    {
        $dataPricing = Pricing::where('id_transaksi', $request->transid)->orderBy('id_kodetrans')->get();
        foreach ($dataPricing as $row) {
            // echo $row->id_kodetrans;
            $pricing[$row->id_kodetrans] = $row;
        }
        $data = [
            'transaksi'   => Transaksi::find($request->transid),
            'pembayaran1' => Pembayaran::select('paid_at')->where('id_transaksi', $request->transid)->where('paid_type', 'PD01')->first(),
            'pembayaran2' => Pembayaran::select('paid_at')->where('id_transaksi', $request->transid)->where('paid_type', 'PD02')->first(),
            'pricing'     => $pricing,
        ];
        $data['insured']  = Insured::find($data['transaksi']->id_insured);
        $data['asuransi'] = Asuransi::find($data['transaksi']->id_asuransi);
        return $data;
    }
}
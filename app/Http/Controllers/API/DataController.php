<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Insured;
use App\Models\KodePos;
use App\Models\Okupasi;
use App\Models\Pricing;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    function index(Request $request)
    {
        return response()->json([
            'message'   => 'ini halaman kodepos'
        ], 200);
    }

    public function selectKodepos(Request $request)
    {
        // DB::enableQueryLog();
        $provinsi = KodePos::select('id', 'kecamatan', 'kelurahan', 'kodepos')->distinct();
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
            $key++;
        }
        return response()->json($list);
    }

    public function selectInsured(Request $request)
    {
        $insured = Insured::select('id', 'kode', 'npwp', 'alamat');
        if (!empty($request->search)) {
            $insured->where('kode', 'like', '%' . $request->search . '%');
        }
        $insured = $insured->get();
        $list = [];
        $key = 0;
        foreach ($insured as $row) {
            $list[$key]['id'] = $row['id'];
            $list[$key]['text'] = $row['kode'];
            $list[$key]['npwp'] = $row['npwp'];
            $list[$key]['alamat'] = $row['alamat'];
            $key++;
        }
        return response()->json($list);
    }

    public function selectOkupasi(Request $request)
    {
        $okupasi = Okupasi::select('kode_okupasi', 'nama_okupasi', 'rate')->where('instype', 'like', '%' . $request->instype . '%');
        if (!empty($request->search)) {
            $okupasi->where('kode_okupasi', 'like', '%' . $request->search . '%')
                ->orWhere('nama_okupasi', 'like', '%' . $request->search . '%')
                ->orWhere('rate', 'like', '%' . $request->search . '%');
        }
        $okupasi = $okupasi->get();
        $list = [];
        $key = 0;
        foreach ($okupasi as $row) {
            $list[$key]['id'] = $row['kode_okupasi'];
            $list[$key]['text'] = $row['kode_okupasi'] . " - " . $row['nama_okupasi'] . " (" . $row['rate'] . ")";
            $key++;
        }
        return response()->json($list);
    }

    public function queryBuilder($table, $type, $select, $where, $joins)
    {
    }

    public function generateQuery($request, $table, $columns, $select, $joins)
    {
        DB::enableQueryLog();

        $table = DB::table($table);

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

        if (!empty($request->order)) {
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
        } else {
            $table->take(10);
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

    public function dataTransaksi(Request $request)
    {
        // buat sorting kolomnya
        $columns = [
            'transid',
            'itp.msdesc',
            'cbg.msdesc',
            'insured.kode',
            'policy_no',
            'periode_start',
            'transaksi.created_at',
            'tsi.value',
            'premi.value',
            'sts.msdesc',
        ];
        // buat as as nya, misalkan ada field yang sama
        $select = [
            'transid',
            'itp.msdesc as tipeins',
            'insured.kode as tertanggung',
            'policy_no',
            'transaksi.created_at as tgl_dibuat',
            'tsi.value as tsi',
            'premi.value as premi',
            'sts.msdesc as statusnya',
            'id_status',
            'periode_start',
            'periode_end',
            'cbg.msdesc as cabang',
        ];

        $table = "transaksi";

        $joins = [
            ['insured', 'id_insured = insured.id'],
            ['masters as itp', ['id_instype = itp.msid', 'itp.mstype = instype']],
            ['masters as sts', ['id_status = sts.msid', 'sts.mstype = status']],
            ['masters as cbg', ['id_cabang = cbg.msid', 'cbg.mstype = cabang']],
            ['transpricing as tsi', ['transid = tsi.id_transaksi', 'tsi.id_kodetrans = 1']],
            ['transpricing as premi', ['transid = premi.id_transaksi', 'premi.id_kodetrans = 2']],
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
            $nestedData[] = date_format(date_create($row->periode_start), "d-M-Y") . " s/d " . date_format(date_create($row->periode_end), "d-M-Y");
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

    public function dataPengajuan($transid)
    {
        $select = [
            'transid',
            'id_instype',
            'id_cabang',
            'alamat_cabang',
            'id_insured',
            'insured.kode as tertanggung',
            'insured.npwp',
            'policy_parent',
            'periode_start',
            'periode_end',
            'id_okupasi',
            'location',
            'id_kodepos',
            'kecamatan',
            'kelurahan',
            'kodepos'
        ];
        $data = DB::table('transaksi')
            ->leftJoin('cabang', 'id_cabang', '=', 'cabang.id')
            ->leftJoin('insured', 'id_insured', '=', 'insured.id')
            ->leftJoin('kodepos', 'id_kodepos', '=', 'kodepos.id')
            ->where('transid', '=', $transid)
            ->select($select)
            ->first();

        return $data;
    }

    public function dataPricing($transid)
    {
        $data = Pricing::where('id_transaksi','=',$transid)->get();
        $new = array();
        foreach($data as $val) {
            $new[$val->id_kodetrans] = $val;
        }
        return $new;
    }

    public function dataAktifitas($transid)
    {
        
    }
}

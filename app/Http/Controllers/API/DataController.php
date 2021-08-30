<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Insured;
use App\Models\KodePos;
use App\Models\Okupasi;
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

    public function generateQuery($request, $table, $columns, $select, $joins)
    {
        // DB::enableQueryLog();

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
        return [$awal, $result->count(), $result];
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
            0 => 'transid',
            1 => 'itp.msdesc',
            2 => 'insured.kode',
            3 => 'policy_no',
            4 => 'transaksi.created_at',
            5 => 'tsi.value',
            6 => 'sts.msdesc',
        ];
        // buat as as nya, misalkan ada field yang sama
        $select = [
            0 => 'transid',
            1 => 'itp.msdesc as tipeins',
            2 => 'insured.kode',
            3 => 'policy_no',
            4 => 'transaksi.created_at as tgl_dibuat',
            5 => 'tsi.value as tsi',
            6 => 'sts.msdesc as statusnya',
            7 => 'transid as aksi',
        ];

        $table = "transaksi";

        $joins = [
            ['insured', 'id_insured = insured.id'],
            ['masters as itp', ['id_instype = itp.msid', 'itp.mstype = "instype"']],
            ['masters as sts', ['id_status = sts.msid', 'sts.mstype = "status"']],
            ['transpricing as tsi', ['transid = id_transaksi', 'id_kodetrans = 1']],
        ];

        $query = $this->generateQuery($request, $table, $columns, $select, $joins);
    }
}

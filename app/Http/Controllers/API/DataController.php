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
        $insured = Insured::select('id', 'kode', 'noktp', 'alamat');
        if (!empty($request->search)) {
            $insured->where('kode', 'like', '%' . $request->search . '%');
        }
        $insured = $insured->get();
        $list = [];
        $key = 0;
        foreach ($insured as $row) {
            $list[$key]['id'] = $row['id'];
            $list[$key]['text'] = $row['kode'];
            $list[$key]['noktp'] = $row['noktp'];
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
            $list[$key]['id'] = $row['id'];
            $list[$key]['text'] = $row['kode_okupasi'] . " - " . $row['nama_okupasi'] . " (" . $row['rate'] . ")";
            $key++;
        }
        return response()->json($list);
    }

    public function dataTransaksi(Request $request)
    {
        $data = Transaksi::all();
        return response()->json($data);
    }
}
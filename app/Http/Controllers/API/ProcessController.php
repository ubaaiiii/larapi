<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User;
use App\Models\Activity;
use App\Models\Cabang;
use App\Models\Insured;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProcessController extends Controller
{
    function index(Request $request)
    {
        return response()->json([
            'message'   => 'ini halaman data'
        ], 200);
    }

    public function user(Request $request)
    {
        switch ($request->method) {
            case 'create':
                $request->validate([
                    'name'          => 'required|string|max:60',
                    'username'      => 'required|unique:users|alpha_dash|max:16',
                    'email'         => 'required|email',
                    'notelp'        => 'required|regex:/(0)[0-9]{9}/',
                    'password'      => 'required|alpha_dash',
                    'id_cabang'     => 'required|numeric',
                    'id_parent'     => 'required|numeric',
                    'level'         => 'required|string',
                ]);

                $user = User::create([
                    'name'          => $request->name,
                    'username'      => $request->username,
                    'email'         => $request->email,
                    'notelp'        => $request->notelp,
                    'password'      => Hash::make($request->password),
                    'unpass'        => $request->password,
                    'id_cabang'     => $request->id_cabang,
                    'id_parent'     => $request->id_parent,
                    'created_by'    => Auth::user()->id,
                    'created_at'    => date('Y-m-d h:m:s'),
                ]);

                $user->assignRole($request->level);

                // $student = Student::where('id', $student->id)->first();

                return response()->json([
                    'message'   => 'User ' . $request->name . ' Berhasil Dibuat',
                    'data'      => $user,
                ], 200);
                break;

            case 'update':
                $user = User::find($request->id);

                $request->validate([
                    'name'          => 'required|string|max:60',
                    'username'      => 'required|unique:users|alpha_dash|max:16',
                    'email'         => 'required|email',
                    'notelp'        => 'required|regex:/(0)[0-9]{9}/',
                    'password'      => 'required|alpha_dash',
                    'id_cabang'     => 'required|numeric',
                    'id_parent'     => 'required|numeric',
                ]);

                $user = $user->update([
                    'name'          => $request->name,
                    'username'      => $request->username,
                    'email'         => $request->email,
                    'notelp'        => $request->notelp,
                    'password'      => Hash::make($request->password),
                    'unpass'        => $request->password,
                    'id_cabang'     => $request->id_cabang,
                    'id_parent'     => $request->id_parent,
                    'updated_at'    => date('Y-m-d h:m:s'),
                ]);

                $user->syncRoles($request->level);

                // $student = Student::where('id', $student->id)->first();

                return response()->json([
                    'message'   => 'User ' . $request->name . ' Berhasil Diubah',
                    'data'      => $user,
                ], 200);
                break;

            case 'delete':
                $user = User::find($request->id);

                $user->syncRoles('ao');
                $user->removeRole('ao');

                $user->delete();

                return response()->json([
                    'message'   => 'User ' . $request->name . ' Berhasil Dihapus',
                    'data'      => $user,
                ], 200);
                break;
            
            default:
                return response()->json([
                    'message'   => 'Method Not Found',
                ], 404);
                break;
        }
    }

    public function dokumen(Request $request)
    {
        $request->validate([
            'transid'   => 'required|',
            'file'      => 'required|mimes:xlsx,xls,pdf,doc,docx,jpg,png,jpeg|max:40960', // 20 MB
        ]);

        $file = $request->file('file');
        $name = $file->getClientOriginalName();
        $type = $file->extension();
        $size = $file->getSize();
        $path = 'public/files/'.$request->transid;

        if (!is_dir($path)) {
            mkdir($path, 0777, TRUE);
        }
        $path = $file->move($path,$name);

        $save = Document::create([
            'id_transaksi'  => $request->transid,
            'nama_file'     => $name,
            'tipe_file'     => $type,
            'ukuran_file'   => $size/1000000,
            'lokasi_file'   => $path,
            'created_by'    => Auth::user()->id,
        ]);

        // return response()->json([
        //     'message'   => 'Dokumen <strong>' . $name . '</strong> berhasil diunggah',
        //     'data'      => $file,
        // ], 200);

        return "
        <script>
            reloadTable();
        </script>
        ";
    }

    public function pengajuan(Request $request)
    {
        return $request->all();
        switch ($request->method) {
            case 'create':
                $request->validate([
                    'transid'           => 'required|string|max:14',
                    'type_insurance'    => 'required|string',
                    'cabang'            => 'required',
                    'alamat_cabang'     => 'required|string',
                    'insured'           => 'required',
                    'nik_insured'       => 'integer',
                    'npwp_insured'      => 'required|string',
                    'alamat_insured'    => 'required|string',
                    'nopinjaman'        => 'required|numeric',
                    'plafond_kredit'    => 'required',
                    'policy_no'         => 'alpha_dash',
                    'nopolis_lama'      => 'alpha_dash',
                    'masa'              => 'required|numeric',
                    'periode_start'     => 'required|string',
                    'periode_end'       => 'required|string',
                    'okupasi'           => 'required|numeric',
                    'lokasi_okupasi'    => 'required|string',
                    'kodepos'           => 'required|numeric',
                ]);

                $date_start = explode("/",$request->periode_start);
                $date_end   = explode("/",$request->periode_end);

                if (!is_numeric($request->insured)) {
                    $request->insured = null;
                }
                if (!is_numeric($request->cabang)) {
                    $request->cabang = null;
                }

                $insured = Insured::updateOrCreate(
                    ['id' => $request->insured],
                    [
                        'nik_insured'       => $request->nik_insured,
                        'nama_insured'      => strtoupper($request->nama_insured),
                        'npwp_insured'      => $request->npwp_insured,
                        'alamat_insured'    => $request->alamat_insured,
                        'created_by'         => Auth::user()->id,
                    ]
                );

                if ($insured->wasRecentlyCreated){
                    $detail = "Pembuatan Tertanggung Baru:<br>
                               - Nama: $request->nama_insured";
                    if (!empty($request->nik_insured)) {
                        $detail .= "<br>- NIK: $request->nik_insured";
                    }
                    if (!empty($request->npwp_insured)) {
                        $detail .= "<br>- NPWP: $request->npwp_insured";
                    }
                    if (!empty($request->alamat_insured)) {
                        $detail .= "<br>- Alamat: $request->alamat_insured";
                    }
                    $this->aktifitas($request->transid,'7',$detail);
                } else {
                    $tertanggung = Insured::find($request->insured);
                    $detail = "Perubahan Tertanggung a/n $tertanggung->nama_insured:";
                    $update = false;
                    if (!empty($request->nik_insured) && $request->nik_insured !== $tertanggung->nik_insured) {
                        $update = true;
                        $detail .= "<br>- NIK: $tertanggung->nik_insured menjadi $request->nik_insured";
                    }
                    if (!empty($request->npwp_insured) && $request->npwp_insured !== $tertanggung->npwp_insured) {
                        $update = true;
                        $detail .= "<br>- NPWP: $tertanggung->npwp_insured menjadi $request->npwp_insured";
                    }
                    if (!empty($request->alamat_insured) && $request->alamat_insured !== $tertanggung->alamat_insured) {
                        $update = true;
                        $detail .= "<br>- Alamat: $tertanggung->alamat_insured menjadi $request->alamat_insured";
                    }
                    if ($update){
                        $this->aktifitas($request->transid,'7',$detail);
                    }
                }

                $cabang = Cabang::updateOrCreate(
                    ['id' => $request->cabang],
                    [
                        'nama_cabang'   => strtoupper($request->nama_cabang),
                        'alamat_cabang' => $request->alamat_cabang,
                        'created_by'    => Auth::user()->id,
                    ]
                );

                if ($cabang->wasRecentlyCreated) {
                    $detail = "Pembuatan Cabang Baru:<br>
                               - Cabang: $request->nama_cabang";
                    if (!empty($request->alamat_cabang)) {
                        $detail .= "<br>- Alamat Cabang: $request->alamat_cabang";
                    }
                    $this->aktifitas($request->transid, '7', $detail);
                } else {
                    $cbg = Cabang::find($request->cabang);
                    $detail = "Perubahan Cabang $cbg->nama_cabang:";
                    $update = false;
                    if (!empty($request->alamat_cabang) && $request->alamat_cabang !== $cbg->alamat_cabang) {
                        $update = true;
                        $detail .= "<br>- Alamat Cabang: $cbg->alamat_cabang menjadi $request->alamat_cabang";
                    }
                    if ($update) {
                        $this->aktifitas($request->transid, '7', $detail);
                    }
                }

                $save = Transaksi::create([
                    'transid'           => $request->transid,
                    'id_instype'        => $request->type_insurance,
                    'id_insured'        => $insured->id,
                    'id_cabang'         => $cabang->id,
                    'nopinjaman'        => $request->nopinjaman,
                    'plafond_kredit'    => $request->plafond_kredit,
                    'policy_no'         => $request->policy_no,
                    'policy_parent'     => $request->nopolis_lama,
                    'masa'              => $request->masa,
                    'id_status'         => '0',
                    'periode_start'     => $date_start[2]."/". $date_start[1]."/". $date_start[0],
                    'periode_end'       => $date_end[2] . "/" . $date_end[1] . "/" . $date_end[0],
                    'id_okupasi'        => $request->okupasi,
                    'location'          => $request->lokasi_okupasi,
                    'id_kodepos'        => $request->kodepos,
                    'created_by'        => Auth::user()->id,
                ]);

                $this->aktifitas($request->transid,'0','Pembuatan data pengajuan');

                return response()->json([
                    'message'   => 'Pengajuan ' . $request->name . ' Berhasil Dibuat',
                    'data'      => $save,
                ], 200);
                break;

            case 'update':
                $request->validate([
                    'transid'           => 'required|string|max:14',
                    'type_insurance'    => 'required|string',
                    'cabang'            => 'required|integer',
                    'alamat_cabang'     => 'required|string',
                    'insured'           => 'required|integer',
                    'nik_insured'       => 'integer',
                    'npwp_insured'      => 'required|string',
                    'alamat_insured'    => 'required|string',
                    'nopinjaman'        => 'required|integer',
                    'plafond_kredit'    => 'required|integer',
                    'policy_no'         => 'alpha_dash',
                    'nopolis_lama'      => 'alpha_dash',
                    'masa'              => 'required|integer',
                    'periode_start'     => 'required|string',
                    'periode_end'       => 'required|string',
                    'okupasi'           => 'required|integer',
                    'lokasi_okupasi'    => 'required|string',
                    'kodepos'           => 'required|integer',
                ]);

                $date_start = explode("/", $request->periode_start);
                $date_end   = explode("/", $request->periode_end);

                $insured = Insured::updateOrCreate(
                    ['id' => $request->insured],
                    [
                        'nik_insured'       => $request->nik_insured,
                        'nama_insured'      => $request->insured,
                        'npwp_insured'      => $request->npwp_insured,
                        'alamat_insured'    => $request->alamat_insured,
                        'create_by'         => Auth::user()->id,
                    ]
                );

                if ($insured->wasRecentlyCreated) {
                    $detail = "Pembuatan Tertanggung Baru:<br>
                               - Nama: $request->insured";
                    if (!empty($request->nik_insured)) {
                        $detail .= "<br>- NIK: $request->nik_insured";
                    }
                    if (!empty($request->npwp_insured)) {
                        $detail .= "<br>- NPWP: $request->npwp_insured";
                    }
                    if (!empty($request->alamat_insured)) {
                        $detail .= "<br>- Alamat: $request->alamat_insured";
                    }
                    $this->aktifitas($request->transid, '7', $detail);
                } else {
                    $tertanggung = Insured::find($request->insured);
                    $detail = "Perubahan Tertanggung a/n $tertanggung->nama_insured:";
                    if (!empty($request->nik_insured)) {
                        $detail .= "<br>- NIK: $request->nik_insured";
                    }
                    if (!empty($request->npwp_insured)) {
                        $detail .= "<br>- NPWP: $request->npwp_insured";
                    }
                    if (!empty($request->alamat_insured)) {
                        $detail .= "<br>- Alamat: $request->alamat_insured";
                    }
                    $this->aktifitas($request->transid, '7', $detail);
                }

                $cabang = Cabang::updateOrCreate(
                    ['id' => $request->cabang],
                    [
                        'nama_cabang' => $request->cabang,
                        'alamat_cabang' => $request->alamat_cabang,
                    ]
                );

                if ($cabang->wasRecentlyCreated) {
                    $detail = "Pembuatan Cabang Baru:<br>
                               - Cabang: $request->cabang";
                    if (!empty($request->alamat_cabang)) {
                        $detail .= "<br>- Alamat Cabang: $request->alamat_cabang";
                    }
                    $this->aktifitas($request->transid, '7', $detail);
                } else {
                    $cbg = Cabang::find($request->cabang);
                    $detail = "Perubahan Cabang $cbg->nama_cabang:";
                    if (!empty($request->alamat_cabang)) {
                        $detail .= "<br>- Alamat Cabang: $request->alamat_cabang";
                    }
                    $this->aktifitas($request->transid, '7', $detail);
                }

                $save = Transaksi::create([
                    'transid'           => $request->transid,
                    'id_instype'        => $request->type_insurance,
                    'id_insured'        => $insured->id,
                    'id_cabang'         => $cabang->id,
                    'nopinjaman'        => $request->nopinjaman,
                    'plafond_kredit'    => $request->plafond_kredit,
                    'policy_no'         => $request->policy_no,
                    'policy_parent'     => $request->nopolis_lama,
                    'masa'              => $request->masa,
                    'id_status'         => '0',
                    'periode_start'     => $date_start[2] . "/" . $date_start[1] . "/" . $date_start[0],
                    'periode_end'       => $date_end[2] . "/" . $date_end[1] . "/" . $date_end[0],
                    'id_okupasi'        => $request->okupasi,
                    'location'          => $request->lokasi_okupasi,
                    'id_kodepos'        => $request->kodepos,
                    'created_by'        => Auth::user()->id,
                ]);

                $this->aktifitas($request->transid, '0', 'Pembuatan data pengajuan');

                return response()->json([
                    'message'   => 'Pengajuan ' . $request->name . ' Berhasil Dibuat',
                    'data'      => $save,
                ], 200);
                break;

            case 'delete':
                $user = User::find($request->id);

                $user->syncRoles('ao');
                $user->removeRole('ao');

                $user->delete();

                return response()->json([
                    'message'   => 'User ' . $request->name . ' Berhasil Dihapus',
                    'data'      => $user,
                ], 200);
                break;
            
            default:
                return response()->json([
                    'message'   => 'Method Not Found',
                ], 404);
                break;
        }
    }

    public function aktifitas($transid, $status, $deskripsi)
    {
        Activity::create([
            'id_transaksi'  => $transid,
            'id_status'     => $status,
            'deskripsi'     => $deskripsi,
            'created_by'     => Auth::user()->id
        ]);
    }
}

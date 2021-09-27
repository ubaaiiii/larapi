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
        $update = null;
        switch ($request->method) {
            case 'store':
                $request->validate([
                    'transid'           => 'required|string|max:14',
                    'type_insurance'    => 'required|string',
                    'cabang'            => 'required',
                    'alamat_cabang'     => 'required|string',
                    'nama_cabang'       => 'required|string',
                    'nopinjaman'        => 'required|numeric',
                    'insured'           => 'required',
                    'nik_insured'       => 'integer',
                    'npwp_insured'      => 'required|string',
                    'nama_insured'      => 'required|string',
                    'alamat_insured'    => 'required|string',
                    'plafond_kredit'    => 'required',
                    'outstanding_kredit'=> 'required',
                    'policy_no'         => 'alpha_dash',
                    'nopolis_lama'      => 'alpha_dash',
                    'polis_start'       => 'required|string',
                    'polis_end'         => 'required|string',
                    'masa'              => 'required|numeric',
                    'kjpp_start'        => 'required|string',
                    'kjpp_end'          => 'required|string',
                    'agunan_kjpp'       => 'required',
                    'jaminan'           => 'required|string',
                    'no_jaminan'        => 'required',
                    'okupasi'           => 'required|numeric',
                    'lokasi_okupasi'    => 'required|string',
                    'kodepos'           => 'required|numeric',
                    'catatan'           => 'string|nullable',
                    'kodetrans-value'   => 'required|array|min:1|nullable',
                    'kodetrans-remarks' => 'array|nullable',
                ]);

                $insured = $this->tertanggung($request);
                $cabang = $this->cabang($request);
                $data = Transaksi::find($request->transid);
                $save = Transaksi::updateOrCreate([
                    'transid'           => $request->transid,
                ],
                [
                    'id_instype'        => $request->type_insurance,
                    'id_insured'        => $insured->id,
                    'id_cabang'         => $cabang->id,
                    'nopinjaman'        => $request->nopinjaman,
                    'plafond_kredit'    => round($request->plafond_kredit,2),
                    'outstanding_kredit'=> round($request->outstanding_kredit,2),
                    'agunan_kjpp'       => round($request->agunan_kjpp,2),
                    'policy_no'         => $request->policy_no,
                    'policy_parent'     => $request->nopolis_lama,
                    'masa'              => $request->masa,
                    'id_status'         => '0',
                    'id_jaminan'        => $request->jaminan,
                    'no_jaminan'        => $request->no_jaminan,
                    'polis_start'       => $request->polis_start,
                    'polis_end'         => $request->polis_end,
                    'kjpp_start'        => $request->kjpp_start,
                    'kjpp_end'          => $request->kjpp_end,
                    'id_okupasi'        => $request->okupasi,
                    'location'          => $request->lokasi_okupasi,
                    'id_kodepos'        => $request->kodepos,
                    'catatan'           => $request->catatan,
                    'created_by'        => Auth::user()->id,
                ]);

                if (!$save->wasRecentlyCreated) {
                    $changes = $save->getChanges();
                    // $original = $save->getRawOriginal();
                    $text = 'Perubahan data pengajuan, sebelumnya:';
                    foreach($changes as $key => $value) {
                        if ($key !== "updated_at") {
                            $text .= "<br>- ".$key." : ". $data->$key;  
                        }
                    }
                    $this->aktifitas($request->transid, '7', $text);
                } else if ($save->wasRecentlyCreated) {
                    $this->aktifitas($request->transid, '0', 'Pembuatan data pengajuan');
                } else {
                    return response()->json([
                        'message'   => 'Gagal menyimpan data',
                        'data'      => $save,
                    ], 500);
                }

                return response()->json([
                    'message'   => 'Pengajuan ' . $request->name . ' Berhasil Disimpan',
                    'data'      => $save,
                    'update'    => $update
                ], 200);
                break;

            case 'delete':
                $data = Transaksi::find($request->transid);
                $data->delete();

                if (!empty($request->catatan)) {
                    $catatan = "";
                }

                $this->aktifitas($request->transid, '8', 'Penghapusan data '.$request->transid);

                return response()->json([
                    'message'   => 'Transaksi '.$request->transid.' berhasil dihapus',
                    'data'      => $data,
                ], 200);
                break;

            case 'approve':
                $role = Auth::user()->getRoleNames()[0];
                switch ($role) {
                    case 'ao':
                        $status = 1;
                        $string = "ajukan";
                        break;
                    case 'broker':
                        $status = 2;
                        $string = "verifikasi";
                        break;
                    case 'insurance':
                        $status = 3;
                        $string = "setujui";
                        break;
                    case 'checker':
                        $status = 4;
                        $string = "aktifkan";
                        break;
                    
                    default:
                        return redirect()->route('logout');
                        break;
                }

                $data = Transaksi::where('transid',$request->transid)->update([
                    'id_status' => $status,
                ]);
                
                $this->aktifitas($request->transid, $status, 'Approval by '. $role);
                
                return response()->json([
                    'message'   => 'Debitur '. $request->nama_insured ." berhasil di".$string,
                    'data'      => $data,
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

    public function tertanggung($request)
    {
        if (!is_numeric($request->insured)) {
            $request->insured = null;
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

        if ($save->hasChanged()) {
            $changes = $save->getChanges();
            // $original = $save->getRawOriginal();
            $text = 'Perubahan data pengajuan, sebelumnya:';
            foreach ($changes as $key => $value) {
                if ($key !== "updated_at") {
                    $text .= "<br>- " . $key . " : " . $data->$key;
                }
            }
            $this->aktifitas($request->transid, '7', $text);
        } else if ($save->wasRecentlyCreated) {
            $this->aktifitas($request->transid, '0', 'Pembuatan data pengajuan');
        } else {
            return response()->json([
                'message'   => 'Gagal menyimpan data',
                'data'      => $save,
            ], 500);
        }

        if ($insured->wasRecentlyCreated) {
            $detail = "Pembuatan Tertanggung Baru:<br>
                               - Nama: " . strtoupper($request->nama_insured);
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
            $detail = "Perubahan Tertanggung a/n " . strtoupper($tertanggung->nama_insured) . ":";
            $update['insured'] = false;
            if (!empty($request->nik_insured) && $request->nik_insured !== $tertanggung->nik_insured) {
                $update['insured'] = true;
                $detail .= "<br>- NIK: $tertanggung->nik_insured menjadi $request->nik_insured";
            }
            if (!empty($request->npwp_insured) && $request->npwp_insured !== $tertanggung->npwp_insured) {
                $update['insured'] = true;
                $detail .= "<br>- NPWP: $tertanggung->npwp_insured menjadi $request->npwp_insured";
            }
            if (!empty($request->alamat_insured) && $request->alamat_insured !== $tertanggung->alamat_insured) {
                $update['insured'] = true;
                $detail .= "<br>- Alamat: $tertanggung->alamat_insured menjadi $request->alamat_insured";
            }
            if ($update['insured']) {
                $update['insured-detail'] = $detail;
                $this->aktifitas($request->transid, '7', $detail);
            }
        }

        return $insured;
    }

    public function cabang($request)
    {
        if (!is_numeric($request->insured)) {
            $request->insured = null;
        }
        
        $cabang = Cabang::updateOrCreate(
            ['id' => $request->cabang],
            [
                'alamat_cabang' => $request->alamat_cabang,
                'created_by'    => Auth::user()->id,
            ]
        );

        $cbg = Cabang::find($request->cabang);
        $detail = "Perubahan Cabang " . strtoupper($cbg->nama_cabang) . ":";
        $update['cabang'] = false;
        if (!empty($request->alamat_cabang) && $request->alamat_cabang !== $cbg->alamat_cabang) {
            $update['cabang'] = true;
            $detail .= "<br>- Alamat Cabang: $cbg->alamat_cabang menjadi $request->alamat_cabang";
        }
        if ($update['cabang']) {
            $update['cabang-detail'] = $detail;
            $this->aktifitas($request->transid, '7', $detail);
        }

        return $cabang;
    }
}

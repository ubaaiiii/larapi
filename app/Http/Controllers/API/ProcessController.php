<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User;
use App\Models\Activity;
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

    public function aktifitas($transid, $status, $deskripsi)
    {
        Activity::create([
            'id_transaksi'  => $transid,
            'id_status'     => $status,
            'deskripsi'     => $deskripsi,
            'create_by'     => Auth::user()->id
        ]);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\CetakController;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User;
use App\Models\Activity;
use App\Models\Cabang;
use App\Models\Insured;
use App\Models\KodeTrans;
use App\Models\Pricing;
use App\Models\Sequential;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
                    'id_asuransi'   => 'nullable|numeric',
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
                    'id_asuransi'   => $request->id_asuransi,
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
                    'id_asuransi'   => 'nullable|numeric',
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
                    'id_asuransi'   => 'nullable|numeric',
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

    public function dokumen(Request $request, $jenis_file = null)
    {
        switch ($request->method) {
            case 'store':
                $request->validate([
                    'transid'   => 'required|',
                    'file'      => 'required|mimes:xlsx,xls,pdf,doc,docx,jpg,png,jpeg|max:40960', // 20 MB
                ]);

                $file = $request->file('file');
                $name = $file->getClientOriginalName();
                $type = $file->extension();
                $size = $file->getSize();
                $path = 'public/files/' . $request->transid;

                if (!is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                }
                $path = $file->move($path, $name);

                $data = [
                    'id_transaksi'  => $request->transid,
                    'nama_file'     => $name,
                    'tipe_file'     => $type,
                    'ukuran_file'   => $size / 1000000,
                    'lokasi_file'   => $path,
                    'created_by'    => Auth::user()->id,
                ];

                if (!empty($jenis_file)) {
                    $data['jenis_file'] = $jenis_file;
                }

                $save = Document::create($data);

                return response()->json([
                        'message'   => 'Dokumen <strong>' . $name . '</strong> berhasil diunggah',
                        'data'      => $file,
                    ], 200);
                break;
            
            case 'delete':
                DB::enableQueryLog();
                $data   = Document::find($request->id);
                $role   = Auth::user()->getRoleNames()[0];
                if ($data->jenis_file == "POLIS" || $data->jenis_file == 'COVERNOTE') {
                    if ($role !== 'insurance') {
                        return response()->json([
                            'message'   => 'Anda tidak memiliki hak untuk menghapus POLIS/COVERNOTE',
                            'data'      => $data,
                        ], 401);
                    }
                }
                $transaksi  = Transaksi::find($data->id_transaksi);
                $nama_file  = $data->nama_file;
                $data->delete();
                
                $this->aktifitas($request->transid, $transaksi->id_status, 'Menghapus file ' . $nama_file);

                return response()->json([
                    'message'   => 'File ' . $nama_file . ' berhasil dihapus',
                    'data'      => $data,
                ], 200);
                break;

            default:
                return redirect()->route('logout');
                break;
        }
    }

    public function pricing(Request $request)
    {
        foreach ($request->kodetrans_value as $key => $value) {
            if (!isset($request->kodetrans_remarks[$key])) {
                $remarks = null;
            } else {
                $remarks = $request->kodetrans_remarks[$key];
            }
            if ($value == null) {
                $value = 0;
            }
            $pricing = Pricing::updateOrCreate(
                ['id_transaksi' => $request->transid, 'id_kodetrans' => $key],
                ['value' => $value, 'deskripsi' => $remarks]
            );
        }
        
        return $pricing;
    }

    public function pengajuan(Request $request)
    {
        // return $request->all();
        $role = Auth::user()->getRoleNames()[0];
        switch ($request->method) {
            case 'store':
                $request->validate([
                    'transid'           => 'string|max:12',
                    'type_insurance'    => 'required|string',
                    'asuransi'          => 'string',
                    'cabang'            => 'required',
                    'alamat_cabang'     => 'required|string',
                    'nama_cabang'       => 'required|string',
                    'nopinjaman'        => 'required|numeric',
                    'cif'               => 'string',
                    'insured'           => 'required',
                    'nik_insured'       => 'numeric',
                    'npwp_insured'      => 'required|numeric',
                    'nama_insured'      => 'required|string',
                    'nohp_insured'      => 'required|string',
                    'alamat_insured'    => 'required|string',
                    'plafond_kredit'    => 'required',
                    'outstanding_kredit'=> 'required',
                    'policy_no'         => 'alpha_dash|nullable',
                    'nopolis_lama'      => 'alpha_dash|nullable',
                    'polis_start'       => 'required|string',
                    'polis_end'         => 'required|string',
                    'masa'              => 'required|numeric',
                    'kjpp_start'        => 'required|string',
                    'kjpp_end'          => 'required|string',
                    'agunan_kjpp'       => 'required',
                    'jaminan'           => 'required|string',
                    'no_jaminan'        => 'required',
                    'okupasi'           => 'numeric',
                    'lokasi_okupasi'    => 'string',
                    'objek_okupasi'     => 'string',
                    'kodepos'           => 'numeric',
                    'catatan'           => 'string|nullable',
                    'kodetrans_value'   => 'required|array|min:1|nullable',
                    'kodetrans_remarks' => 'array|nullable',
                    'klausula'          => 'required',
                ]);
                // return $request->all();
                
                if (empty($request->transid) || !isset($request->transid)) {
                    $nourut     = DB::table('transaksi')->whereYear('created_at','=',date('Y'))->whereMonth('created_at','=',date('m'))->count();
                    $sequential = Sequential::where('seqdesc', 'transid')->first();
                    $transid    = $sequential->seqlead.date($sequential->seqformat).str_pad($nourut, $sequential->seqlen, '0', STR_PAD_LEFT);
                    $request->merge(['transid' => $transid]);
                    
                    if (Transaksi::find($transid) !== null) {
                        $nourut++;
                        $transid = $sequential->seqlead.date($sequential->seqformat).str_pad($nourut, $sequential->seqlen, '0', STR_PAD_LEFT);
                        $request->merge(['transid' => $transid]);
                    }
                }
                
                $insured    = $this->tertanggung($request);
                $cabang     = $this->cabang($request);
                $pricing    = $this->pricing($request);
                // return $pricing;
                
                $data       = Transaksi::find($request->transid);
                
                $save = Transaksi::updateOrCreate([
                    'transid'           => $request->transid,
                ],
                [
                    'id_instype'        => $request->type_insurance,
                    'id_cabang'         => $cabang->id,
                    'nopinjaman'        => $request->nopinjaman,
                    'cif'               => $request->cif,
                    'id_insured'        => $insured->id,
                    'plafond_kredit'    => round($request->plafond_kredit, 2),
                    'outstanding_kredit'=> round($request->outstanding_kredit, 2),
                    'policy_no'         => $request->policy_no,
                    'policy_parent'     => $request->nopolis_lama,
                    'polis_start'       => $request->polis_start,
                    'polis_end'         => $request->polis_end,
                    'masa'              => $request->masa,
                    'kjpp_start'        => $request->kjpp_start,
                    'kjpp_end'          => $request->kjpp_end,
                    'agunan_kjpp'       => round($request->agunan_kjpp, 2),
                    'id_jaminan'        => $request->jaminan,
                    'no_jaminan'        => $request->no_jaminan,
                    'id_okupasi'        => $request->okupasi,
                    'location'          => $request->lokasi_okupasi,
                    'object'            => $request->objek_okupasi,
                    'id_kodepos'        => $request->kodepos,
                    'catatan'           => $request->catatan,
                    'klausula'          => $request->klausula,
                    'created_by'        => Auth::user()->id,
                    'id_status'         => '0',
                ]);

                if (!$save->wasRecentlyCreated && $save->wasChanged()) {
                    $method = "update";
                    $changes = $save->getChanges();
                    // $original = $save->getRawOriginal();
                    $text = 'Perubahan data pengajuan, sebelumnya:';
                    foreach($changes as $key => $value) {
                        if ($key !== "updated_at" && $key !== "catatan" && $key !== "created_by") {
                            $text .= "<br>- ".$key." : ". $data->$key;  
                        }
                    }
                    if (!empty($request->catatan)) {
                        $text .= "<br>Catatan: ".$request->catatan;
                    }
                    $this->aktifitas($request->transid, '7', $text);
                } else if ($save->wasRecentlyCreated) {
                    $method = "create";
                    $this->aktifitas($request->transid, '0', $request->catatan);
                }

                return response()->json([
                    'message'   => 'Pengajuan ' . $request->name . ' Berhasil Disimpan',
                    'method'    => $method,
                    'data'      => $save
                ], 200);
                break;

            case 'delete':
                $data = Transaksi::find($request->transid);
                
                if ($data->id_status == "0") {
                    $catatan = "";
                    if (!empty($request->catatan)) {
                        $catatan = ". Catatan: ".$request->catatan;
                    }
                    
                    $this->aktifitas($request->transid, '11', 'Penghapusan data '.$request->transid.$catatan);
                    $data->update([
                        'id_status' => 11,
                        'catatan'   => $request->catatan
                    ]);
                    $data->delete();
                    
                    return response()->json([
                        'message'   => 'Transaksi '.$request->transid.' berhasil dihapus',
                        'data'      => $data,
                    ], 200);
                } else {
                    return response()->json([
                        'message'   => 'Tidak dapat menghapus data yang sudah berjalan, harap kembalikan ke status <b>TERTUNDA</b>',
                        'data'      => $data,
                    ], 405);
                }
                break;

            case 'approve':
                $transaksi = Transaksi::find($request->transid);
                switch ($role) {
                    case 'ao':
                        $status     = 1;
                        $string     = "ajukan";
                        $insured    = $this->tertanggung($request);
                        $cabang     = $this->cabang($request);
                        $pricing    = $this->pricing($request);
                        $update     = [
                            'id_instype'        => $request->type_insurance,
                            'id_cabang'         => $cabang->id,
                            'nopinjaman'        => $request->nopinjaman,
                            'cif'               => $request->cif,
                            'id_insured'        => $insured->id,
                            'plafond_kredit'    => round($request->plafond_kredit, 2),
                            'outstanding_kredit' => round($request->outstanding_kredit, 2),
                            'policy_parent'     => $request->nopolis_lama,
                            'polis_start'       => $request->polis_start,
                            'polis_end'         => $request->polis_end,
                            'masa'              => $request->masa,
                            'kjpp_start'        => $request->kjpp_start,
                            'kjpp_end'          => $request->kjpp_end,
                            'agunan_kjpp'       => round($request->agunan_kjpp, 2),
                            'id_jaminan'        => $request->jaminan,
                            'no_jaminan'        => $request->no_jaminan,
                            'catatan'           => $request->catatan,
                        ];
                        break;
                        
                    case 'checker':
                        $status     = 1;
                        $string     = "ajukan";
                        $insured    = $this->tertanggung($request);
                        $cabang     = $this->cabang($request);
                        $pricing    = $this->pricing($request);

                        $request->validate([
                            'type_insurance'    => 'required|string',
                            'cabang'            => 'required',
                            'alamat_cabang'     => 'required|string',
                            'nama_cabang'       => 'required|string',
                            'nopinjaman'        => 'required|numeric',
                            'cif'               => 'string',
                            'insured'           => 'required',
                            'nik_insured'       => 'numeric',
                            'npwp_insured'      => 'required|numeric',
                            'nama_insured'      => 'required|string',
                            'nohp_insured'      => 'required|string',
                            'alamat_insured'    => 'required|string',
                            'plafond_kredit'    => 'required',
                            'outstanding_kredit'=> 'required',
                            'nopolis_lama'      => 'alpha_dash|nullable',
                            'polis_start'       => 'required|string',
                            'polis_end'         => 'required|string',
                            'masa'              => 'required|numeric',
                            'kjpp_start'        => 'required|string',
                            'kjpp_end'          => 'required|string',
                            'agunan_kjpp'       => 'required',
                            'jaminan'           => 'required|string',
                            'no_jaminan'        => 'required',
                            'catatan'           => 'string|nullable',
                            'kodetrans_value'   => 'required|array|min:1|nullable',
                            'kodetrans_remarks' => 'array|nullable',
                        ]);

                        $update     = [
                            'id_instype'        => $request->type_insurance,
                            'id_cabang'         => $cabang->id,
                            'nopinjaman'        => $request->nopinjaman,
                            'cif'               => $request->cif,
                            'id_insured'        => $insured->id,
                            'plafond_kredit'    => round($request->plafond_kredit, 2),
                            'outstanding_kredit'=> round($request->outstanding_kredit, 2),
                            'policy_parent'     => $request->nopolis_lama,
                            'polis_start'       => $request->polis_start,
                            'polis_end'         => $request->polis_end,
                            'masa'              => $request->masa,
                            'kjpp_start'        => $request->kjpp_start,
                            'kjpp_end'          => $request->kjpp_end,
                            'agunan_kjpp'       => round($request->agunan_kjpp, 2),
                            'id_jaminan'        => $request->jaminan,
                            'no_jaminan'        => $request->no_jaminan,
                            'catatan'           => $request->catatan,
                        ];
                        break;

                    case 'approver':
                        $status = 2;
                        $string = "setujui";
                        break;

                    case 'broker':
                        $status = 3;
                        $string = "verifikasi";
                        
                        $request->validate([
                            'asuransi'          => 'required|string',
                            'okupasi'           => 'required|numeric',
                            'kodepos'           => 'required|numeric',
                            'lokasi_okupasi'    => 'required|string',
                            // 'objek_okupasi'     => 'required|string',
                        ]);
                        
                        $update = [
                            'id_asuransi'   => $request->asuransi,
                            'id_okupasi'    => $request->okupasi,
                            'id_kodepos'    => $request->kodepos,
                            'location'      => $request->lokasi_okupasi,
                            // 'object'        => $request->objek_okupasi,
                        ];

                        break;
                        
                    case 'insurance':
                        if ($transaksi->id_status == 3) {
                            $status = 4;
                            $string = "aktifkan";
                            $cetakController = new CetakController;
                            $covernote = $cetakController->cetakCoverNote($request->transid);
                            $update = [
                                'cover_note'    => $covernote,
                            ];
                        } else if ($transaksi->id_status == 6) {
                            $status = 7;
                            $string = "aktifkan polisnya";
                        }
                        break;
                    
                    default:
                        return redirect()->route('logout');
                        break;
                }

                $update['id_status'] = $status;
                $update['catatan'] = $request->catatan;

                $data = Transaksi::where('transid',$request->transid)->update($update);

                $this->pricing($request);
                $this->aktifitas($request->transid, $status, $request->catatan);
                
                return response()->json([
                    'message'   => 'Debitur '. $request->nama_insured ." berhasil di".$string,
                    'data'      => $data,
                    'method'    => "approve",
                ], 200);

                break;

            case 'rollback':
                $transaksi = Transaksi::find($request->transid);
                switch ($role) {
                    case 'ao':
                        $status = 0;
                        break;
                    case 'checker':
                        $status = 0;
                        break;
                    case 'approver':
                        $status = 0;
                        break;
                    case 'broker':
                        if ($transaksi->id_status == "2") {
                            $status = 1;
                        } else if ($transaksi->id_status == "7") {
                            $status = 6;
                        }
                        break;
                    case 'insurance':
                        $status = 2;
                        break;
                    
                    default:
                        return redirect()->route('logout');
                        break;
                }

                $catatan = "Dikembalikan.";
                if (!empty($request->catatan)) {
                    $catatan .= " Catatan: ".$request->catatan;
                }
                $data = Transaksi::where('transid',$request->transid)->update([
                    'id_status' => $status,
                    'catatan'   => $catatan,
                ]);
                
                $this->aktifitas($request->transid, $status, $catatan);
                
                return response()->json([
                    'message'   => 'Debitur '. $request->nama_insured ." berhasil dikembalikan ",
                    'data'      => $data,
                    'method'    => "rollback",
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
            'created_by'    => Auth::user()->id
        ]);
    }

    public function tertanggung($request)
    {
        if (!is_numeric($request->insured)) {
            $request->insured = null;
        }

        $data = Insured::find($request->insured);
        $insured = Insured::updateOrCreate(
            ['id' => $request->insured],
            [
            'nik_insured'       => $request->nik_insured,
            'nama_insured'      => strtoupper($request->nama_insured),
            'npwp_insured'      => $request->npwp_insured,
            'alamat_insured'    => $request->alamat_insured,
            'nohp_insured'      => $request->nohp_insured,
            'created_by'        => Auth::user()->id,
            ]
        );
        // return $insured;
        
        if (!$insured->wasRecentlyCreated && $insured->wasChanged()) {
            $changes = $insured->getChanges();
            // $original = $insured->getRawOriginal();
            $text = 'Perubahan data tertanggung, sebelumnya:';
            foreach ($changes as $key => $value) {
                if ($key !== "updated_at" && $key !== "catatan" && $key !== "created_by") {
                    $text .= "<br>- " . $key . " : " . $data->$key;
                }
            }
            if (strpos($text, '-') !== false) {
                $this->aktifitas($request->transid, '0', $text);
            }
        } else if ($insured->wasRecentlyCreated) {
            $this->aktifitas($request->transid, '0', 'Pembuatan data tertanggung baru a/n '. strtoupper($request->nama_insured));
        }

        return $insured;
    }

    public function cabang($request)
    {
        if (!is_numeric($request->cabang)) {
            $request->cabang = null;
        }
        
        $data = Cabang::find($request->cabang);
        $cabang = Cabang::updateOrCreate(
            ['id' => $request->cabang],
            [
            'nama_cabang'   => $request->nama_cabang,
            'alamat_cabang' => $request->alamat_cabang,
            'created_by'    => Auth::user()->id,
            ]
        );

        if (!$cabang->wasRecentlyCreated && $cabang->wasChanged()) {
            $changes = $cabang->getChanges();
            // $original = $cabang->getRawOriginal();
            $text = 'Perubahan data cabang, sebelumnya:';
            foreach ($changes as $key => $value) {
                if ($key !== "updated_at" && $key !== "catatan" && $key !== "created_by") {
                    $text .= "<br>- " . $key . " : " . $data->$key;
                }
            }
            if (strpos($text, '-') !== false) {
                $this->aktifitas($request->transid, '0', $text);
            }
        } else if ($cabang->wasRecentlyCreated) {
            $this->aktifitas($request->transid, '0', 'Pembuatan data cabang baru');
        }

        return $cabang;
    }
}

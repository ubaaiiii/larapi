<?php

namespace App\Http\Controllers\API;

use App\Helpers\Functions;
use App\Http\Controllers\API\DataController;
use App\Http\Controllers\CetakController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Models\Document;
use App\Models\User;
use App\Models\Activity;
use App\Models\Asuransi;
use App\Models\Cabang;
use App\Models\Currency;
use App\Models\Installment;
use App\Models\Insured;
use App\Models\KodeTrans;
use App\Models\Pembayaran;
use App\Models\Perluasan;
use App\Models\Pricing;
use App\Models\Sequential;
use App\Models\Transaksi;
use App\Models\TransaksiObjek;
use App\Models\TransaksiPenanggung;
use App\Models\TransaksiPerluasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
        $role   = Auth::user()->getRoleNames()[0];
        switch ($request->method) {
            case 'create':
                $request->validate([
                    'name'          => 'required|string|max:60',
                    'username'      => 'required|unique:users|alpha_dash|max:16',
                    'email'         => 'required|email',
                    'notelp'        => 'required|numeric',
                    'password'      => 'required',
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
                // return $request->all();
                $user = User::find($request->id);

                $request->validate([
                    'name'          => 'string|max:60',
                    'username'      => 'alpha_dash|max:16',
                    'email'         => 'email',
                    'notelp'        => 'numeric',
                    'old_password'  => 'string',
                    'new_password'  => 'string',
                    'id_cabang'     => 'numeric',
                    'id_parent'     => 'numeric',
                    'id_asuransi'   => 'numeric',
                ]);

                $errors = [];
                $ada_error = false;

                if ($request->username !== $user->username) {
                    $cekUsername = User::where('username', $request->username)->get();
                    if ($cekUsername->count() > 0) {
                        $ada_error = true;
                        $errors[] = [
                            'username'  => [
                                'Username ' . $request->username . ' telah digunakan, harap menggunakan username lain',
                            ]
                        ];
                    }
                }

                if ($request->email !== $user->email) {
                    $cekemail = User::where('email', $request->email)->get();
                    if ($cekemail->count() > 0) {
                        $ada_error = true;
                        $errors[] = [
                            'email'  => [
                                'email ' . $request->email . ' telah digunakan, harap menggunakan email lain',
                            ]
                        ];
                    }
                }

                $dataUpdate = [];

                if ($request->update_pw === "on") {
                    if ($user->unpass !== $request->old_password) {
                        $ada_error = true;
                        $errors[] = [
                            'password'  => [
                                'Katasandi lama tidak sesuai',
                            ]
                        ];
                    }
                    $dataUpdate = [
                        'password'      => Hash::make($request->new_password),
                        'unpass'        => $request->new_password,
                    ];
                } else {
                    $dataUpdate = [
                        'name'          => $request->name,
                        'username'      => $request->username,
                        'email'         => $request->email,
                        'notelp'        => $request->notelp,
                    ];

                    if (in_array($role, ['admin', 'broker'])) {
                        array_merge($dataUpdate, [
                            'id_cabang'     => $request->id_cabang,
                            'id_parent'     => $request->id_parent,
                            'id_asuransi'   => 'numeric',
                        ]);
                    }
                }

                if ($ada_error) {
                    return response()->json(['errors' => $errors], 422);
                }

                if (!empty($request->level)) {
                    $user->syncRoles($request->level);
                }
                $user = $user->update($dataUpdate);


                return response()->json([
                    'message'   => 'User ' . $request->name . ' Berhasil Diubah',
                    'data'      => $user,
                ], 200);
                break;

            case 'delete':
                $user = User::find($request->id);

                $user->syncRoles('maker');
                $user->removeRole('maker');

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
        $role   = Auth::user()->getRoleNames()[0];
        switch ($request->method) {
            case 'store':
                $request->validate([
                    'transid'   => 'required|',
                    'file'      => 'required|mimes:xlsx,xls,pdf,doc,docx,jpg,png,jpeg|max:52428', // 50 MB
                ], [
                    'file.max'  => 'Ukuran dokumen tidak boleh melebihi 50 MB.'
                ]);

                $file = $request->file('file');
                $name = $file->getClientOriginalName();
                $type = $file->extension();
                $size = $file->getSize();
                $path = "public/files/" . $request->transid;

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

                if ($save) {
                    return response()->json([
                        'message'   => 'Dokumen <strong>' . $name . '</strong> berhasil diunggah',
                        'data'      => $file,
                    ], 200);
                } else {
                    return response()->json([
                        'message'   => 'Dokumen <strong>' . $name . '</strong> gagal diunggah',
                        'data'      => $file,
                    ], 200);
                }
                break;

            case 'delete':
                DB::enableQueryLog();
                $data   = Document::find($request->id);
                $transaksi  = Transaksi::find($data->id_transaksi);

                if ($data->jenis_file == "POLIS" || $data->jenis_file == 'COVERNOTE') {
                    if ($role !== 'insurance') {
                        return response()->json([
                            'message'   => 'Anda tidak memiliki hak untuk menghapus POLIS/COVERNOTE',
                            'data'      => $data,
                        ], 401);
                    } else {
                        if ($data->jenis_file == 'COVERNOTE') {
                            $transaksi->update(['cover_note' => null]);
                        }
                    }
                }

                $nama_file  = $data->nama_file;
                $data->forceDelete();

                $this->aktifitas($request->transid, $transaksi->id_status, 'Menghapus file ' . $nama_file);

                return response()->json([
                    'message'   => 'File ' . $nama_file . ' berhasil dihapus',
                    'data'      => $data,
                ], 200);
                break;

            case 'covernote':
                $request->validate([
                    'transid'       => 'required',
                    'no_covernote'  => 'required',
                    'file'          => 'required|mimes:pdf|max:52428', // 50 MB
                ], [
                    'file.max'      => 'Ukuran dokumen tidak boleh melebihi 50 MB.'
                ]);

                $transaksi = Transaksi::find($request->transid);
                $transaksi->update(
                    [
                        'cover_note'        => $request->no_covernote,
                        'cover_note_manual' => 1
                    ]
                );

                $file = $request->file('file');
                $name = $file->getClientOriginalName();
                $type = $file->extension();
                $size = $file->getSize();
                $path   = "public/files/$request->transid/";
                $filename = "Cover_Note-$request->transid.pdf";

                $eksis = false;
                if (file_exists($path . $filename)) {
                    $eksis = true;
                }
                if (!is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                }

                $path = $file->move($path, $filename);
                // return redirect($path . $filename);
                $insert = [
                    'id_transaksi'  => $transaksi->transid,
                    'nama_file'     => $filename,
                    'tipe_file'     => $type,
                    'ukuran_file'   => $size / 1024000,
                    'lokasi_file'   => $path,
                    'jenis_file'    => "COVERNOTE",
                    'created_by'    => 1,
                ];

                if (!$eksis) {
                    Document::create($insert);
                }

                return response()->json([
                    'message'   => 'Covernote berhasil diupload',
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
            $pricing = Pricing::whereNull('id_objek')->updateOrCreate(
                ['id_transaksi' => $request->transid, 'id_kodetrans' => $key],
                ['value' => $value, 'deskripsi' => $remarks],
            );
        }

        return $pricing;
    }

    public function pembayaran(Request $request)
    {
        $role = Auth::user()->getRoleNames()[0];
        if ($role == "finance") {
            $transaksi = Transaksi::where('transid', $request->transid);
            switch ($request->method) {
                case 'bank':
                    $transaksi = $transaksi->where('cover_note', $request->cover_note)->first();
                    $request->validate([
                        'transid'   => 'required',
                        'paid'      => 'required',
                        'tgl_bayar' => 'required',
                    ]);

                    $insert = [
                        'id_transaksi'  => $request->transid,
                        'paid_amount'   => $request->paid,
                        'paid_type'     => "PD01",
                        'dc'            => "C",
                        'paid_at'       => $request->tgl_bayar,
                        'created_by'    => Auth::user()->id,
                    ];

                    $cekPembayaran = Pembayaran::where('id_transaksi', $request->tarnsid)->where('paid_type', 'PD01')->first();
                    if (!empty($cekPembayaran)) {
                        return response()->json([
                            'message'   => 'Gagal input pembayaran karena data sudah pernah dibayar pada tanggal ' . Functions::tgl_indo($cekPembayaran->paid_at),
                            'data'      => $cekPembayaran,
                        ], 400);
                    }

                    Pembayaran::create($insert);
                    $transaksi->update(['id_status' => 7]);

                    $this->aktifitas($transaksi->transid, '6', 'Pembayaran Premi Diterima Oleh BDS Pada Tanggal: ' . Functions::tgl_indo($request->tgl_bayar));
                    $this->aktifitas($transaksi->transid, '7', 'Menunggu E-Polis untuk diupload oleh Asuransi.');

                    return response()->json([
                        'message'   => 'Berhasil input pembayaran atas Nomor Transaksi ' . $transaksi->transid,
                        'data'      => $transaksi,
                    ], 200);
                    break;

                case 'asuransi':
                    $transaksi = $transaksi->first();
                    $request->validate([
                        'transid'   => 'required',
                        'paid'      => 'required',
                        'tgl_bayar' => 'required',
                    ]);

                    $insert = [
                        'id_transaksi'  => $request->transid,
                        'paid_amount'   => $request->paid,
                        'paid_type'     => "PD02",
                        'dc'            => "D",
                        'paid_at'       => $request->tgl_bayar,
                        'created_by'    => Auth::user()->id,
                    ];

                    $cekPembayaran = Pembayaran::where('id_transaksi', $request->tarnsid)->where('paid_type', 'PD02')->first();
                    if (!empty($cekPembayaran)) {
                        return response()->json([
                            'message'   => 'Gagal input pembayaran karena data sudah pernah dibayar pada tanggal ' . Functions::tgl_indo($cekPembayaran->paid_at),
                            'data'      => $cekPembayaran,
                        ], 400);
                    }

                    Pembayaran::create($insert);
                    $this->aktifitas($transaksi->transid, '9', 'Premi Dibayarkan Oleh BDS ke Asuransi Pada Tanggal: ' . Functions::tgl_indo($request->tgl_bayar));

                    $cetak = new CetakController;
                    $cetak->cetakNotaPembayaran($request->transid);

                    return response()->json([
                        'message'   => 'Berhasil input pembayaran atas Nomor Transaksi ' . $transaksi->transid,
                        'data'      => $transaksi,
                    ], 200);
                    break;

                case 'batal':
                    $transaksi = $transaksi->first();
                    $request->validate([
                        'transid'   => 'required',
                        'catatan'   => 'required'
                    ]);
                    $cekPembayaran2 = Pembayaran::where('id_transaksi', $request->transid)->where('paid_type', 'PD02')->first();
                    $cekPembayaran1 = Pembayaran::where('id_transaksi', $request->transid)->where('paid_type', 'PD01')->first();

                    if (!empty($cekPembayaran2)) {
                        $cekPembayaran2->forceDelete();
                        $nota_pembayaran = Document::where('id_transaksi', $request->transid)->where('jenis_file', 'NOTAPEMBAYARAN');
                        $path = str_replace("public/", "", $nota_pembayaran->first()->lokasi_file);
                        if (file_exists(public_path($path))) {
                            unlink(public_path($path));
                        }
                        $nota_pembayaran->forceDelete();
                    } elseif (!empty($cekPembayaran1)) {
                        $cekPembayaran1->forceDelete();
                        $transaksi->update(['id_status' => 5]);
                    } else {
                        return response()->json([
                            'message'   => 'Gagal batalkan pembayaran karena tidak ada datanya'
                        ], 400);
                    }
                    $this->aktifitas($transaksi->transid, '16', $request->catatan);

                    return response()->json([
                        'message'   => 'Berhasil membatalkan pembayaran atas Nomor Transaksi ' . $transaksi->transid,
                        'data'      => $transaksi,
                    ], 200);

                    break;

                case 'ubah':
                    $transaksi = $transaksi->first();
                    $request->validate([
                        'transid'    => 'required',
                    ]);
                    $cekPembayaran1 = Pembayaran::where('id_transaksi', $request->transid)->where('paid_type', 'PD01')->first();
                    $cekPembayaran2 = Pembayaran::where('id_transaksi', $request->transid)->where('paid_type', 'PD02')->first();
                    $catatan = "";

                    if (!empty($cekPembayaran1) or !empty($cekPembayaran2)) {
                        $catatan .= "Perubahan Tanggal Pembayaran:";
                        if (!empty($cekPembayaran1)) {
                            $tgl_sebelumnya = explode(" ", $cekPembayaran1->paid_at)[0];
                            if ($tgl_sebelumnya !== $request->tgl_terima) {
                                $catatan .= "<br>- Tgl Terima Dari Bank:
                                <br> " . Functions::tgl_indo($tgl_sebelumnya) . " Menjadi " . Functions::tgl_indo($request->tgl_terima);
                                $cekPembayaran1->update(['paid_at' => $request->tgl_terima]);
                            }
                        }

                        if (!empty($cekPembayaran2)) {
                            $tgl_sebelumnya = explode(" ", $cekPembayaran2->paid_at)[0];
                            if ($tgl_sebelumnya !== $request->tgl_bayar) {
                                $catatan .= "<br>- Tgl Bayar Ke Asuransi:
                                <br> " . Functions::tgl_indo($tgl_sebelumnya) . " Menjadi " . Functions::tgl_indo($request->tgl_bayar);
                                $cekPembayaran2->update(['paid_at' => $request->tgl_bayar]);
                            }
                        }
                    } else {
                        return response()->json([
                            'message'   => 'Gagal batalkan pembayaran karena tidak ada datanya'
                        ], 400);
                    }
                    $this->aktifitas($transaksi->transid, '17', $catatan);
                    // return "masuk sini";

                    return response()->json([
                        'message'   => 'Berhasil merubah tanggal pembayaran atas Nomor Transaksi ' . $transaksi->transid,
                        'data'      => $transaksi,
                    ], 200);

                default:
                    return response()->json([
                        'message'   => 'Gagal, Kesalahan Method'
                    ], 400);
                    break;
            }
        } else {
            abort(403, "Tidak Berkepentingan, Tidak Dapat Diproses");
        }
    }

    public function polis(Request $request)
    {
        switch ($request->method) {
            case 'store':
                $request->validate([
                    'cover_note' => 'required',
                    'nopolis'    => 'required',
                    'transid'    => 'required',
                    'polis'      => 'required|mimes:pdf|max:20480',
                    'invoice'    => 'required|mimes:pdf|max:20480',
                ]);
                $transaksi  = Transaksi::find($request->transid);
                $asuransi   = Asuransi::find($transaksi->id_asuransi);
                $update = [
                    'policy_no' => $request->nopolis,
                    'id_status' => 8
                ];

                $polis       = $request->file('polis');
                $invoice     = $request->file('invoice');
                $polisExt    = $polis->extension();
                $invoiceExt  = $invoice->extension();
                $polisSize   = $polis->getSize();
                $invoiceSize = $invoice->getSize();
                $filePolis   = "E-Polis_" . $asuransi->akronim . "-" . $request->transid;
                $fileInvoice = "Invoice_Asuransi_" . $asuransi->akronim . "-" . $request->transid;
                $path        = 'public/files/' . $request->transid;
                $public_path = 'files/' . $request->transid;
                if (!is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                }

                $exists = false;
                if ($exists = Storage::exists(public_path($public_path . "/" . $filePolis . "." . $polisExt))) {
                    echo "polis eksis";
                } else if ($exists = Storage::exists(public_path($public_path . "/" . $fileInvoice . "." . $invoiceExt))) {
                    echo "polis eksis";
                }

                if ($exists = file_exists(public_path($public_path . "/" . $filePolis . "." . $polisExt))) {
                    Storage::move(public_path($public_path . "/" . $filePolis . "." . $polisExt), public_path($public_path . $filePolis . " - Copy" . "." . $polisExt));
                } else if ($exists = file_exists(public_path($public_path . "/" . $fileInvoice . "." . $invoiceExt))) {
                    Storage::move(public_path($public_path . "/" . $fileInvoice . "." . $invoiceExt), public_path($public_path . $fileInvoice . " - Copy" . "." . $invoiceExt));
                }

                $pathPolis   = $polis->move($path, $filePolis . "." . $polisExt);
                $pathInvoice = $invoice->move($path, $fileInvoice . "." . $invoiceExt);
                if (!$exists) {
                    Document::create([
                        'id_transaksi'  => $request->transid,
                        'nama_file'     => $filePolis,
                        'tipe_file'     => $polisExt,
                        'jenis_file'    => "POLIS",
                        'ukuran_file'   => $polisSize / 1000000,
                        'lokasi_file'   => $pathPolis,
                        'created_by'    => Auth::user()->id,
                    ]);
                    Document::create([
                        'id_transaksi'  => $request->transid,
                        'nama_file'     => $fileInvoice,
                        'tipe_file'     => $invoiceExt,
                        'jenis_file'    => "INVOICE ASURANSI",
                        'visible_by'    => 'broker,insurance,finance',
                        'ukuran_file'   => $invoiceSize / 1000000,
                        'lokasi_file'   => $pathInvoice,
                        'created_by'    => Auth::user()->id,
                    ]);
                }

                $transaksi->update($update);
                $this->aktifitas($request->transid, '8', 'Asuransi mengunggah E-Polis');
                return response()->json([
                    'message'   => 'E-Polis dan Invoice berhasil diunggah',
                ], 200);
                break;

            default:
                abort(404);
                break;
        }
    }

    public function endorsement(Request $request)
    {
        switch ($request->method) {
            case 'store':
                $request->validate([
                    'cover_note' => 'required',
                    'nopolis'    => 'required',
                    'transid'    => 'required',
                    'endorsement' => 'required|mimes:pdf|max:20480',
                ]);
                $transaksi  = Transaksi::find($request->transid);
                $asuransi   = Asuransi::find($transaksi->id_asuransi);
                $update = [
                    'id_status' => 8
                ];

                $endorsement        = $request->file('polis');
                $endorsementExt     = $endorsement->extension();
                $endorsementSize    = $endorsement->getSize();
                $fileEndorsement    = "Endorsement_" . $asuransi->akronim . "-" . $request->transid;
                $path               = 'public/files/' . $request->transid;
                if (!is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                }

                $path   = $endorsement->move($path, $fileEndorsement . "." . $endorsementExt);
                Document::create([
                    'id_transaksi'  => $request->transid,
                    'nama_file'     => $fileEndorsement,
                    'tipe_file'     => $endorsementExt,
                    'jenis_file'    => "ENDORSEMENT",
                    'ukuran_file'   => $endorsementSize / 1000000,
                    'lokasi_file'   => $path,
                    'created_by'    => Auth::user()->id,
                ]);

                $transaksi->update($update);
                $this->aktifitas($request->transid, '8', 'Asuransi mengunggah E-Polis');
                return response()->json([
                    'message'   => 'E-Polis dan Invoice berhasil diunggah',
                ], 200);
                break;

            default:
                abort(404);
                break;
        }
    }

    public function klausula(Request $request)
    {
        // return $request->all();
        $transaksi = Transaksi::where('transid', $request->transid);
        if (!empty($transaksi)) {
            $transaksi->update(['klausula' => $request->klausula]);
            return response()->json([
                'message'   => 'Klausula berhasil diperbaharui',
            ], 200);
        } else {
            return response()->json([
                'message'   => 'ID transaksi tidak ditemukan',
            ], 400);
        }
    }

    public function wholesales(Request $request)
    {
        // return response()->json([
        //     'message'   => $request->all(),
        // ], 400);
        // return $this->objek($request);
        $role = Auth::user()->getRoleNames()[0];
        switch ($request->method) {
            case "store":
                $request->validate([
                    'agunan_kjpp'       => 'array|required',
                    'alamat'            => 'array|required',
                    'alamat_cabang'     => 'string|required',
                    'alamat_insured'    => 'string|required',
                    // 'check_perluasan'   => 'array|required',
                    'cif'               => 'string',
                    'insured'           => 'required',
                    'id_jaminan'        => 'array|required',
                    'id_kelas'          => 'array',
                    'kjpp_end'          => 'string|required',
                    'kjpp_start'        => 'string|required',
                    'cabang'            => 'required',
                    'id_instype'        => 'string|required',
                    'id_kodepos'        => 'array|required',
                    'id_currency'       => 'required',
                    'masa'              => 'numeric|required',
                    'nik_insured'       => 'numeric|nullable',
                    'no_jaminan'        => 'required',
                    'nohp_insured'      => 'numeric|required',
                    'nopinjaman'        => 'string',
                    'npwp_insured'      => 'numeric|nullable',
                    'objek'             => 'array|required',
                    'outstanding_kredit' => 'required',
                    'plafond_kredit'    => 'required',
                    'polis_end'         => 'string|required',
                    'polis_start'       => 'string|required',
                    'sumins_type'       => 'array|required',
                    'sumins_value'      => 'array|required',
                ]);

                if (empty($request->transid) || !isset($request->transid)) {
                    $totalBulanIni  = DB::table('transaksi')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->count();
                    $sequential = Sequential::where('seqdesc', 'transid')->first();
                    if ($totalBulanIni == 0) {
                        $sequential = tap(Sequential::where('seqdesc', 'transid'))->update(['seqno' => 0])->first();
                    }
                    $sequential = tap(Sequential::where('seqdesc', 'transid'))->update(['seqno' => $sequential->seqno + 1])->first();
                    $transid    = $sequential->seqlead . date($sequential->seqformat) . str_pad($sequential->seqno, $sequential->seqlen, '0', STR_PAD_LEFT);

                    if (Transaksi::withTrashed()->where('transid', '=', $transid)->count() !== 0) {
                        $sequential = tap(Sequential::where('seqdesc', 'transid'))->update(['seqno' => $sequential->seqno + 1])->first();
                        $transid    = $sequential->seqlead . date($sequential->seqformat) . str_pad($sequential->seqno, $sequential->seqlen, '0', STR_PAD_LEFT);
                    }

                    $request->merge(['transid' => $transid]);
                }

                $insured    = $this->tertanggung($request);
                $cabang     = $this->cabang($request);
                $objek      = $this->objek($request); // udah termasuk pricing objeknya
                // $perluasan  = $this->perluasan($request);
                $data       = Transaksi::find($request->transid);

                $dataController = new DataController;
                $currency = $dataController->getExchangeRate($request);
                // $currency = $dataController->getExchangeRate($request);
                return response()->json([
                    'message'   => $currency,
                ], 400);
                if (empty($data)) {
                    $id_status = 0;
                    $textResponse = " Berhasil Disimpan";
                    $ajukan = false;
                } else {
                    $id_status = 1;
                    $textResponse = " Berhasil Diajukan";
                    $ajukan = true;
                }

                $save   = Transaksi::updateOrCreate(
                    [
                        'transid'           => $request->transid,
                    ],
                    [
                        'id_cabang'             => $cabang->id,
                        'cif'                   => $request->cif,
                        'id_insured'            => $insured->id,
                        'kjpp_end'              => $request->kjpp_end,
                        'kjpp_start'            => $request->kjpp_start,
                        'masa'                  => $request->masa,
                        'nopinjaman'            => $request->nopinjaman,
                        'outstanding_kredit'    => round($request->outstanding_kredit, 2),
                        'plafond_kredit'        => round($request->plafond_kredit, 2),
                        'polis_end'             => $request->polis_end,
                        'polis_start'           => $request->polis_start,
                        'id_instype'            => $request->id_instype,
                        'created_by'            => Auth::user()->id,
                        'bisnis'                => 'wholesales',
                        'id_currency'           => $request->id_currency,
                        // 'exchange_rate'         => round($currency->kurs_tengah, 2),
                        // 'exchange_rate_date'    => $currency->tanggal_kurs,
                        'id_status'             => $id_status,
                    ]
                );

                if (!empty($data) && $data->catatan == $request->catatan) {
                    $request->catatan = "";
                }

                if (!$save->wasRecentlyCreated && $save->wasChanged()) {
                    $method = "update";
                    $changes = $save->getChanges();
                    // $original = $save->getRawOriginal();
                    $text = 'Perubahan data pengajuan:';
                    foreach ($changes as $key => $value) {
                        if ($key !== "updated_at" && $key !== "catatan" && $key !== "created_by" && $key !== "id_status") {
                            if ($data->$key != $value)
                                $text .= "<br>- " . $key . " : " . $data->$key . " menjadi " . $value;
                        }
                    }
                    if (!empty($request->catatan)) {
                        $text .= "<br>Catatan: " . $request->catatan;
                    }
                    // if ($text !== "Perubahan data pengajuan:") {
                    $this->aktifitas($request->transid, '19', $text);
                    // }
                } else if ($save->wasRecentlyCreated) {
                    $method = "create";
                    $this->aktifitas($request->transid, $id_status, $request->catatan);
                }

                if ($ajukan) {
                    $this->aktifitas($request->transid, $id_status, $request->catatan);
                }

                return response()->json([
                    'message'   => 'Pengajuan ' . $insured->nama_insured . $textResponse,
                    'method'    => $method,
                    'data'      => $save
                ], 200);
                break;

            case 'approve':
                $transaksi = Transaksi::find($request->transid);
                switch ($role) {
                    case 'maker':
                        if ($transaksi->id_status == 4) {
                            $status         = "5";
                            $string         = "setujui";
                        }
                        break;
                    
                    case 'approver':
                        $status = 2;
                        $string = "setujui";
                        break;

                    case 'broker' || 'adm':
                        if ($transaksi->id_status == 2) {
                            $status = 3;
                            $string = "verifikasi";

                            $request->validate([
                                'okupasi'               => 'required|array',
                                'okupasi.*'             => 'required',
                                'rate_perluasan'        => 'array',
                                'id_kelas'              => 'required|array',
                                'id_kelas.*'            => 'required',
                                'klausula'              => ['required', Rule::notIn(['<p><br></p>'])],
                                'kodetrans_value.10'    => 'required|not_in:0',
                                'kodetrans_value.11'    => 'required|not_in:0',
                            ]);

                            $update = [
                                'klausula'  => $request->klausula,
                            ];

                            $this->pricing($request);
                            $this->objek($request);
                            // $this->perluasan($request);
                            $this->installment($request);
                        } else if ($transaksi->id_status == 3) {
                            $status = 4;
                            $string = "tawarkan ke tertanggung";

                            $request->validate([
                                'asuransi'               => 'required|array',
                                'asuransi.*'             => 'required',
                                'total_share'            => 'required',
                            ]);

                            if ($request->total_share != 100) {
                                return response()->json([
                                    'message'   => 'Total share belum mencapai 100%',
                                    'data'      => $request->all(),
                                ], 400);
                            }

                            $this->penanggung($request);

                        } else if ($transaksi->id_status == 8) {
                            $status = 10;
                            $string = "cek kebenaran polisnya";
                        } else {
                            return response()->json([
                                'message'   => 'Status tidak sesuai dengan user yang digunakan',
                            ], 400);
                        }

                        break;

                    case 'insurance':
                        $request->validate([
                            'klausula'  => 'required',
                        ]);

                        $update = [
                            'klausula'  => $request->klausula,
                        ];
                        if ($transaksi->id_status == 3) {
                            $status = 4;
                            $string = "setujui";
                            $cetakAkseptasi = true;
                        } else if ($transaksi->id_status == 6) {
                            $status = 7;
                            $string = "aktifkan polisnya";
                        }
                        $this->pricing($request);
                        break;

                    default:
                        echo response()->json([
                            'message'   => "Bukan Role Yang Sah",
                        ], 401);
                        return redirect()->route('logout');
                        break;
                }

                $update['id_status'] = $status;
                if ($transaksi->catatan === $request->catatan) {
                    $request->merge([
                        'catatan' => "",
                    ]);
                }
                $update['catatan'] = $request->catatan;

                $data = Transaksi::where('transid', $request->transid)->update($update);

                $this->aktifitas($request->transid, $status, $request->catatan);

                $cetak = new CetakController;
                if (!empty($cetakAkseptasi)) {
                    $cetak->cetakAkseptasi($request->transid);
                }
                if (!empty($cetakCoverNote)) {
                    $cetak->cetakCoverNote($request->transid);
                }
                if (!empty($cetakInvoice)) {
                    $cetak->cetakInvoice($request->transid);
                }

                return response()->json([
                    'message'   => 'Debitur ' . $request->nama_insured . " berhasil di" . $string,
                    'data'      => $data,
                    'method'    => "approve",
                ], 200);
                break;

            case 'rollback':
                $transaksi = Transaksi::find($request->transid);
                switch ($role) {
                    case 'maker':
                        // PENDING
                        $status = 0;

                        // DISETUJUI ASURANSI
                        if ($transaksi->id_status == 4) {
                            $status = 3;
                        }
                        break;

                    case 'checker':
                        // PENDING
                        $status = 0;

                        // DISETUJUI ASURANSI
                        if ($transaksi->id_status == 4) {
                            $status = 3;
                        }
                        break;

                    case 'approver':
                        // PENDING
                        $status = 0;

                        // DISETUJUI ASURANSI
                        if ($transaksi->id_status == 4) {
                            $status = 3;
                        }
                        break;

                    case 'broker' || 'adm':
                        // DISETUJUI -> TERTUNDA
                        if ($transaksi->id_status == "2") {
                            $status = 1;

                            // PENGECEKAN POLIS -> MENUNGGU E-POLIS
                        } else if ($transaksi->id_status == "8") {
                            $status = 7;
                        }
                        break;

                    case 'insurance':
                        // DISETUJUI
                        $status = 2;
                        break;

                    default:
                        return redirect()->route('logout');
                        break;
                }

                $catatan = "Dikembalikan.";
                if ($transaksi->catatan == $request->catatan) {
                    $request->merge([
                        'catatan' => "",
                    ]);
                }
                if (!empty($request->catatan)) {
                    $catatan .= " Dengan Catatan: " . $request->catatan;
                }
                $update['id_status']    = $status;
                $update['catatan']      = $catatan;
                $data = Transaksi::where('transid', $request->transid)->update($update);

                // Status 11 = DIKEMBALIKAN
                $this->aktifitas($request->transid, 11, $catatan);
                $notif = new NotificationController;
                $notif->sendPushNotif($request->transid, $transaksi->created_by, "rollback", $catatan);

                return response()->json([
                    'message'   => 'Debitur ' . $request->nama_insured . " berhasil dikembalikan ",
                    'data'      => $data,
                    'method'    => "rollback",
                ], 200);

                break;

            default:
                return response()->json([
                    'message'   => 'Method tidak ditemukan, data tidak dapat diproses',
                ], 400);
                break;
        }
    }

    public function sme(Request $request)
    {
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
                    'nopinjaman'        => 'string',
                    'cif'               => 'string',
                    'insured'           => 'required',
                    'nik_insured'       => 'numeric|nullable',
                    'npwp_insured'      => 'numeric|nullable',
                    'nama_insured'      => 'required|string',
                    'nohp_insured'      => 'required|string',
                    'alamat_insured'    => 'required|string',
                    'plafond_kredit'    => 'required',
                    'outstanding_kredit' => 'required',
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
                    // 'klausula'          => 'required',
                ]);
                // return $request->all();

                if (empty($request->transid) || !isset($request->transid)) {
                    $totalBulanIni  = DB::table('transaksi')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->count();
                    $sequential = Sequential::where('seqdesc', 'transid')->first();
                    if ($totalBulanIni == 0) {
                        $sequential = tap(Sequential::where('seqdesc', 'transid'))->update(['seqno' => 0])->first();
                    }
                    $sequential = tap(Sequential::where('seqdesc', 'transid'))->update(['seqno' => $sequential->seqno + 1])->first();
                    $transid    = $sequential->seqlead . date($sequential->seqformat) . str_pad($sequential->seqno, $sequential->seqlen, '0', STR_PAD_LEFT);

                    if (Transaksi::withTrashed()->where('transid', '=', $transid)->count() !== 0) {
                        $sequential = tap(Sequential::where('seqdesc', 'transid'))->update(['seqno' => $sequential->seqno + 1])->first();
                        $transid    = $sequential->seqlead . date($sequential->seqformat) . str_pad($sequential->seqno, $sequential->seqlen, '0', STR_PAD_LEFT);
                    }

                    $request->merge(['transid' => $transid]);
                }

                $insured    = $this->tertanggung($request);
                $cabang     = $this->cabang($request);
                $pricing    = $this->pricing($request);
                // return $pricing;

                $data       = Transaksi::find($request->transid);

                $save = Transaksi::updateOrCreate(
                    [
                        'transid'           => $request->transid,
                    ],
                    [
                        'id_instype'        => $request->type_insurance,
                        'id_cabang'         => $cabang->id,
                        'nopinjaman'        => $request->nopinjaman,
                        'cif'               => $request->cif,
                        'id_insured'        => $insured->id,
                        'plafond_kredit'    => round($request->plafond_kredit, 2),
                        'outstanding_kredit' => round($request->outstanding_kredit, 2),
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
                        'bisnis'            => 'sme',
                        'id_status'         => '0',
                    ]
                );

                if (!$save->wasRecentlyCreated && $save->wasChanged()) {
                    $method = "update";
                    $changes = $save->getChanges();
                    // $original = $save->getRawOriginal();
                    $text = 'Perubahan data pengajuan, sebelumnya:';
                    foreach ($changes as $key => $value) {
                        if ($key !== "updated_at" && $key !== "catatan" && $key !== "created_by") {
                            $text .= "<br>- " . $key . " : " . $data->$key;
                        }
                    }
                    if (!empty($request->catatan)) {
                        $text .= "<br>Catatan: " . $request->catatan;
                    }
                    $this->aktifitas($request->transid, '0', $text);
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
                        $catatan = ". Catatan: " . $request->catatan;
                    }

                    $this->aktifitas($request->transid, '13', 'Penghapusan data ' . $request->transid . $catatan);
                    $data->update([
                        'id_status' => 13,
                        'catatan'   => $request->catatan
                    ]);
                    $data->delete();

                    return response()->json([
                        'message'   => 'Transaksi ' . $request->transid . ' berhasil dihapus',
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
                    case 'makerchecker' || 'checker':
                        if ($transaksi->id_status == 0) {
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
                                'agunan_kjpp'       => $request->agunan_kjpp,
                                'id_jaminan'        => $request->jaminan,
                                'no_jaminan'        => $request->no_jaminan,
                                'location'          => $request->lokasi_okupasi,
                                'catatan'           => $request->catatan,
                            ];
                            $pricing = true;
                        } else if ($transaksi->id_status == 4) {
                            $status         = 5;
                            $string         = "setujui";
                            $cetakCoverNote = true;
                            $cetakInvoice   = true;
                            $pricing        = false;
                        }
                        break;

                    case 'approver':
                        $status = 2;
                        $string = "setujui";
                        $pricing = true;
                        break;

                    case 'broker' || 'adm':
                        if ($transaksi->id_status == 2) {
                            $status = 3;
                            $string = "verifikasi";

                            $request->validate([
                                'asuransi'           => 'required|string',
                                'okupasi'            => 'required|numeric',
                                'kodepos'            => 'required|numeric',
                                'lokasi_okupasi'     => 'required|string',
                                'objek_okupasi'      => 'required|string',
                                'klausula'           => ['required', Rule::notIn(['<p><br></p>'])],
                                'kodetrans_value.10' => 'required|not_in:0',
                                'kodetrans_value.11' => 'required|not_in:0',
                            ]);

                            $update = [
                                'id_asuransi'   => $request->asuransi,
                                'id_okupasi'    => $request->okupasi,
                                'id_kodepos'    => $request->kodepos,
                                'location'      => $request->lokasi_okupasi,
                                'object'        => $request->objek_okupasi,
                                'klausula'      => $request->klausula,
                            ];
                            $pricing = true;
                        } else if ($transaksi->id_status == 8) {
                            $status = 10;
                            $string = "cek kebenaran polisnya";
                            $pricing = false;
                        }

                        break;

                    case 'insurance':
                        $request->validate([
                            'klausula'  => 'required',
                        ]);

                        $update = [
                            'klausula'  => $request->klausula,
                        ];
                        if ($transaksi->id_status == 3) {
                            $status = 4;
                            $string = "setujui";
                            $cetakAkseptasi = true;
                            $pricing = true;
                        } else if ($transaksi->id_status == 6) {
                            $status = 7;
                            $string = "aktifkan polisnya";
                            $pricing = false;
                        }
                        break;

                    default:
                        return redirect()->route('logout');
                        break;
                }

                $update['id_status'] = $status;
                if ($transaksi->catatan == $request->catatan) {
                    $request->merge([
                        'catatan' => "",
                    ]);
                }
                $update['catatan'] = $request->catatan;

                $data = Transaksi::where('transid', $request->transid)->update($update);

                if ($pricing) {
                    $this->pricing($request);
                }
                $this->aktifitas($request->transid, $status, $request->catatan);

                $cetak = new CetakController;
                if (!empty($cetakAkseptasi)) {
                    $cetak->cetakAkseptasi($request->transid);
                }
                if (!empty($cetakCoverNote)) {
                    $cetak->cetakCoverNote($request->transid);
                }
                if (!empty($cetakInvoice)) {
                    $cetak->cetakInvoice($request->transid);
                }

                return response()->json([
                    'message'   => 'Debitur ' . $request->nama_insured . " berhasil di" . $string,
                    'data'      => $data,
                    'method'    => "approve",
                ], 200);

                break;

            case 'rollback':
                $transaksi = Transaksi::find($request->transid);
                switch ($role) {
                    case 'maker':
                        // PENDING
                        $status = 0;

                        // DISETUJUI ASURANSI
                        if ($transaksi->id_status == 4) {
                            $update = [
                                'id_okupasi'    => NULL,
                            ];
                            Pricing::where('id_transaksi', $request->transid)->whereNotIn('id_kodetrans', [1, 3, 4, 5, 6, 7, 8, 9])->update(['value' => 0]);
                        }
                        break;

                    case 'checker':
                        // PENDING
                        $status = 0;

                        // DISETUJUI ASURANSI
                        if ($transaksi->id_status == 4) {
                            $update = [
                                'id_okupasi'    => NULL,
                            ];
                            Pricing::where('id_transaksi', $request->transid)->whereNotIn('id_kodetrans', [1, 3, 4, 5, 6, 7, 8, 9])->update(['value' => 0]);
                        }
                        break;

                    case 'approver':
                        // PENDING
                        $status = 0;
                        break;

                    case 'broker' || 'adm':
                        // DISETUJUI -> TERTUNDA
                        if ($transaksi->id_status == "2") {
                            $status = 1;

                        // PENGECEKAN POLIS -> MENUNGGU E-POLIS
                        } else if ($transaksi->id_status == "8") {
                            $status = 7;
                        }
                        break;

                    case 'insurance':
                        // DISETUJUI
                        $status = 2;
                        break;

                    default:
                        return redirect()->route('logout');
                        break;
                }

                $catatan = "Dikembalikan.";
                if ($transaksi->catatan == $request->catatan) {
                    $request->merge([
                        'catatan' => "",
                    ]);
                }
                if (!empty($request->catatan)) {
                    $catatan .= " Catatan: " . $request->catatan;
                }
                $update['id_status']    = $status;
                $update['catatan']      = $catatan;
                $data = Transaksi::where('transid', $request->transid)->update($update);

                // Status 11 = DIKEMBALIKAN
                $this->aktifitas($request->transid, 11, $catatan);
                $notif = new NotificationController;
                $notif->sendPushNotif($request->transid, $transaksi->created_by, "rollback", $catatan);

                return response()->json([
                    'message'   => 'Debitur ' . $request->nama_insured . " berhasil dikembalikan ",
                    'data'      => $data,
                    'method'    => "rollback",
                ], 200);

                break;

            case 'renewal':
                $request->validate([
                    'transid_parent'    => 'string|max:12',
                    'type_insurance'    => 'required|string',
                    'asuransi'          => 'string',
                    'cabang'            => 'required',
                    'alamat_cabang'     => 'required|string',
                    'nama_cabang'       => 'required|string',
                    'nopinjaman'        => 'string',
                    'cif'               => 'string',
                    'insured'           => 'required',
                    'nik_insured'       => 'numeric|nullable',
                    'npwp_insured'      => 'numeric|nullable',
                    'nama_insured'      => 'required|string',
                    'nohp_insured'      => 'required|string',
                    'alamat_insured'    => 'required|string',
                    'plafond_kredit'    => 'required',
                    'outstanding_kredit' => 'required',
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
                    $totalBulanIni  = DB::table('transaksi')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->count();
                    $sequential = Sequential::where('seqdesc', 'transid')->first();
                    if ($totalBulanIni == 0) {
                        $sequential = tap(Sequential::where('seqdesc', 'transid'))->update(['seqno' => 0])->first();
                    }
                    $sequential = tap(Sequential::where('seqdesc', 'transid'))->update(['seqno' => $sequential->seqno + 1])->first();
                    $transid    = $sequential->seqlead . date($sequential->seqformat) . str_pad($sequential->seqno, $sequential->seqlen, '0', STR_PAD_LEFT);

                    if (Transaksi::withTrashed()->where('transid', '=', $transid)->count() !== 0) {
                        $sequential = tap(Sequential::where('seqdesc', 'transid'))->update(['seqno' => $sequential->seqno + 1])->first();
                        $transid    = $sequential->seqlead . date($sequential->seqformat) . str_pad($sequential->seqno, $sequential->seqlen, '0', STR_PAD_LEFT);
                    }

                    $request->merge(['transid' => $transid]);
                }

                $insured    = $this->tertanggung($request);
                $cabang     = $this->cabang($request);
                $pricing    = $this->pricing($request);
                // return $pricing;

                $data       = Transaksi::find($request->transid);

                $save = Transaksi::updateOrCreate(
                    [
                        'transid'           => $request->transid,
                    ],
                    [
                        'id_instype'        => $request->type_insurance,
                        'id_cabang'         => $cabang->id,
                        'nopinjaman'        => $request->nopinjaman,
                        'cif'               => $request->cif,
                        'id_insured'        => $insured->id,
                        'plafond_kredit'    => round($request->plafond_kredit, 2),
                        'outstanding_kredit' => round($request->outstanding_kredit, 2),
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
                    ]
                );

                if (!$save->wasRecentlyCreated && $save->wasChanged()) {
                    $method = "update";
                    $changes = $save->getChanges();
                    // $original = $save->getRawOriginal();
                    $text = 'Perubahan data pengajuan, sebelumnya:';
                    foreach ($changes as $key => $value) {
                        if ($key !== "updated_at" && $key !== "catatan" && $key !== "created_by") {
                            $text .= "<br>- " . $key . " : " . $data->$key;
                        }
                    }
                    if (!empty($request->catatan)) {
                        $text .= "<br>Catatan: " . $request->catatan;
                    }
                    $this->aktifitas($request->transid, '0', $text);
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

            case 'klausula':
                if (in_array($role, ['adm', 'broker', 'insurance'])) {
                    $request->validate([
                        'transid'   => 'required',
                        'klausula'  => 'required',
                    ]);

                    $transaksi = tap(Transaksi::where('transid', $request->transid))->update(['klausula'  => $request->klausula])->first();
                    $insured = Insured::find($transaksi->id_insured);

                    return response()->json([
                        'message'   => 'Klausula debitur ' . $insured->nama_insured . " berhasil diubah",
                        'data'      => $transaksi,
                    ], 200);
                } else {
                    return response()->json([
                        'message'   => 'Unauthorized',
                    ], 401);
                }
                break;

            default:
                return response()->json([
                    'message'   => 'Method Not Found',
                ], 404);
                break;
        }
    }

    public function aktifitas($transid, $status, $deskripsi, $user = null)
    {
        if ($user == null) {
            $user = Auth::user()->id;
        }
        Activity::create([
            'id_transaksi'  => $transid,
            'id_status'     => $status,
            'deskripsi'     => $deskripsi,
            'created_by'    => $user
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
                'id_kodepos'        => $request->kodepos_insured,
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
            $this->aktifitas($request->transid, '0', 'Pembuatan data tertanggung baru a/n ' . strtoupper($request->nama_insured));
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

    public function installment($request)
    {
        if (!empty($request->id_installment)) {
            $id_installment = [];
            foreach ($request->id_installment as $i => $v) {
                $row = Installment::updateOrCreate(
                    [
                        'id'              => $v,
                        'id_transaksi'    => $request->transid,
                    ],
                    [
                        'tgl_tagihan'     => $request->tgl_tagihan[$i],
                        'nominal_premi'   => $request->premium_installment[$i],
                        'nominal_tagihan' => $request->total_installment[$i],
                        'created_by'      => Auth::user()->id,
                    ],
                );
                if (!empty($v)) {
                    $id_installment[] = $v;
                } else {
                    $id_installment[] = $row->id;
                }
                $installment[] = $row;
            }
            Installment::where('id_transaksi', $request->transid)->whereNotIn('id', $id_installment)->forceDelete();

            return $installment;
        }
    }

    public function penanggung(Request $request)
    {
        // return $request->all();
        $request->share = array_filter($request->share);
        if (!empty($request->asuransi) && !empty($request->share)) {
            $id_asuransi = [];
            foreach ($request->asuransi as $i => $v) {
                $arr[] = $i . " " . $v;
                $row = TransaksiPenanggung::updateOrCreate(
                    [
                        'id_transaksi'          => $request->transid,
                        'id_asuransi'           => $v,
                    ],
                    [
                        'share_pertanggungan'   => $request->share[$i],
                        'created_by'            => Auth::user()->id,
                    ],
                );
                if (!empty($v)) {
                    $id_asuransi[] = $v;
                }
                $asuransi[] = $row;
            }
            TransaksiPenanggung::where('id_transaksi', $request->transid)->whereNotIn('id_asuransi', $id_asuransi)->forceDelete();

            // return $asuransi;
            return response()->json([
                'message'   => "Berhasil simpan data asuransi",
            ], 200);
        } else {
            $txt = "";
            if (empty($request->asuransi)) {
                $txt .= "memilih asuransi";
            }
            if (empty($request->share)) {
                if (empty($request->asuransi)) {
                    $txt .= " dan ";
                }
                $txt .= "mengisi share";
            }
            return response()->json([
                'message'   => "Harap $txt terlebih dahulu",
            ], 400);
        }
    }

    public function objek($request)
    {
        if (!empty($request->objek)) {
            // TransaksiObjek::where('id_transaksi', $request->transid)->forceDelete();
            // Pricing::where('id_transaksi', $request->transid)->whereNotNull('id_objek')->forceDelete();

            foreach ($request->objek as $i => $v) {
                if (!is_numeric($request->id_objek[$i])) {
                    $id_objek = $request->id_objek;
                    $id_objek[$i] = null;
                    $request->id_objek = $id_objek;
                    // $request->id_objek[$i] = null;
                }

                $data = [
                    'objek'         => $v,
                    'alamat_objek'  => $request->alamat[$i],
                    'id_kodepos'    => $request->id_kodepos[$i],
                    'id_jaminan'    => $request->id_jaminan[$i],
                    'no_jaminan'    => $request->no_jaminan[$i],
                    'agunan_kjpp'   => $request->agunan_kjpp[$i],
                    'created_by'    => Auth::user()->id,
                ];

                if (!empty($request->id_kelas[$i])) {
                    $data['id_kelas'] = $request->id_kelas[$i];
                }
                if (!empty($request->okupasi[$i])) {
                    $data['id_okupasi'] = $request->okupasi[$i];
                }
                if (!empty($request->rate_okupasi[$i])) {
                    $data['rate'] = $request->rate_okupasi[$i];
                }

                $objek = TransaksiObjek::updateOrCreate(
                    ['id' => $request->id_objek[$i], 'id_transaksi'  => $request->transid],
                    $data
                );

                // objek pricing
                foreach ($request->sumins_type[$i] as $j => $k) {
                    $value = $request->sumins_value[$i][$j];
                    if ($value == null) {
                        $value = 0;
                    }
                    $pricing = Pricing::updateOrCreate(
                        [
                            'id_transaksi'  => $request->transid,
                            'id_kodetrans'  => $k,
                            'id_objek'      => $objek->id
                        ],
                        [
                            'value'     => $value,
                        ],
                    );
                    $data_pricing[] = $pricing;
                }

                // objek perluasan
                if (!empty($request->perluasan)) {
                    foreach ($request->perluasan[$i] as $j => $k) {
                        $value = (isset($request->value_perluasan[$i][$k])) ? $request->value_perluasan[$i][$k] : null;
                        $rate  = (isset($request->rate_perluasan[$i][$k])) ? $request->rate_perluasan[$i][$k] : null;

                        $perluasan = TransaksiPerluasan::updateOrCreate(
                            [
                                'id_transaksi'  => $request->transid,
                                'id_perluasan'  => $k,
                                'id_objek'      => $objek->id
                            ],
                            [
                                'rate'          => $rate,
                                'value'         => $value,
                                'created_by'    => Auth::user()->id,
                            ],
                        );
                        $data_perluasan[] = $perluasan;
                    }
                }

                $data_objek[] = [$objek, $data_pricing];
            }

            return $data_objek;
        }
    }

    public function perluasan($request)
    {
        // DB::enableQueryLog();
        if (!empty($request->perluasan)) {
            $data_perluasan = [];
            $id_perluasan = [];
            foreach ($request->perluasan as $i => $v) {
                $rate = (isset($request->rate_perluasan[$i])) ? $request->rate_perluasan[$i] : null;
                $perluasan = TransaksiPerluasan::updateOrCreate(
                    [
                        'id_transaksi'  => $request->transid,
                        'id_perluasan'  => $i,
                        'id_objek'
                    ],
                    [
                        'rate'          => $rate,
                        'created_by'    => Auth::user()->id,
                    ],
                );
                $data_perluasan[] = $perluasan;
                $id_perluasan[] = $i;
            }
            return $data_perluasan;
            TransaksiPerluasan::where('id_transaksi', $request->transid)->whereNotIn('id_perluasan', $id_perluasan)->forceDelete();

            return $data_perluasan;
        }
    }

    public function cekGagalBayar()
    {
        $transaksi = Transaksi::whereRaw('DATEDIFF(NOW(),`billing_at`) > 30')->whereRaw('CAST(id_status AS int) < 7');
        foreach ($transaksi->get() as $row) {
            $this->aktifitas($row->transid, 18, "Pembayaran tidak diterima BDS selama 30 hari. Covernote Dibatalkan.", "1");
            // echo $row->transid;
        }
        $transaksi->update(['id_status' => '18']);
    }
}

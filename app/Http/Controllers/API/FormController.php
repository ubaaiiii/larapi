<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class FormController extends Controller
{
    function index(Request $request)
    {
        return response()->json([
            'message'   => 'ini halaman form'
        ], 200);
    }

    function create(Request $request, Student $student)
    {
        $request->validate([
            'nama'      => 'required|max:50',
            'alamat'    => 'required',
            'notelp'    => 'required|numeric|min:10',
        ]);

        $student = Student::create([
            'nama'      => $request->nama,
            'alamat'    => $request->alamat,
            'notelp'    => $request->notelp,
        ]);

        // $student = Student::where('id', $student->id)->first();

        return response()->json([
            'message'   => 'Siswa ' . $request->nama . ' Berhasil Ditambahkan',
            'data'      => $student,
        ], 200);
    }

    function edit($id)
    {
        $student = Student::find($id);
        return response()->json([
            'message'   => 'Success',
            'data'      => $student,
        ], 200);
    }

    function update(Request $request, $id)
    {
        $student = Student::find($id);

        $student = $student->update([
            'nama'      => $request->nama,
            'alamat'    => $request->alamat,
            'notelp'    => $request->notelp,
        ]);

        return response()->json([
            'message'   => 'Siswa ' . $request->nama . ' Berhasil Diubah',
            'data'      => $student,
        ], 200);
    }
}

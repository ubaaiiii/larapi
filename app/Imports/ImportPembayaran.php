<?php

namespace App\Imports;

use App\Models\PembayaranTemp;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportPembayaran implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return PembayaranTemp::all();
    }
}

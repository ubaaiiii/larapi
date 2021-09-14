<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Page extends Model
{
    use HasFactory;

    public static function roleHasPages($id, $visible = null, $parent_id = null) {
        $result = DB::table('role_has_pages')
        ->join('pages', 'role_has_pages.page_id','=','pages.id')
        ->where('role_id',$id);

        if ($parent_id == null) {
            $result->whereNull('parent_id');
        } else {
            $result->where('parent_id',$parent_id);
        }

        if ($visible == null) {
            $result->where('visible',0);
        } else {
            $result->where('visible',$visible);
        }
        return $result;
    }
}

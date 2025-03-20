<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoNumbering extends Model
{
    use HasFactory;

    protected $connection = 'mysqlSUGARCRM';
    protected $table = 'so_numbering_c';
    protected $keyType = 'string';
    public $incrementing = false;


    public function so_numbering_c_npwp_account_c_c()
    {
        return $this->hasMany(SoNPWPAccount::class, 'so_numbering_c_npwp_account_cso_numbering_c_idb', 'id');
    }

    public function so_numbering_c()
    {
        return $this->hasOne(PenomoranKontrak::class, 'so_id', 'id');
    }

}

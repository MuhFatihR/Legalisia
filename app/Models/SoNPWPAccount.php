<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoNPWPAccount extends Model
{
    use HasFactory;

    protected $connection = 'mysqlSUGARCRM';
    protected $table = 'so_numbering_c_npwp_account_c_c';
    protected $keyType = 'string';
    public $incrementing = false;

    public function so_numbering_c(){
        return $this->belongsTo(SoNumbering::class, 'so_numbering_c_npwp_account_cso_numbering_c_idb', 'id');
    }

    public function npwp_account_c(){
        return $this->belongsTo(NPWPAccount::class, 'so_numbering_c_npwp_account_cnpwp_account_c_ida', 'id');
    }

    // public function so_numbering_c(){
    //     return $this->hasMany(Approver::class, 'employee_id', 'id');
    // }

    // public function npwp_account_c(){
    //     return $this->hasMany(Approver::class, 'employee_id', 'id');
    // }

}

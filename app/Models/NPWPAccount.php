<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NPWPAccount extends Model
{
    use HasFactory;

    protected $connection = 'mysqlSUGARCRM';
    protected $table = 'npwp_account_c';
    protected $keyType = 'string';
    public $incrementing = false;

    public function so_numbering_c_npwp_account_c_c()
    {
        return $this->hasMany(SoNPWPAccount::class, 'so_numbering_c_npwp_account_cnpwp_account_c_ida', 'id');
    }

}

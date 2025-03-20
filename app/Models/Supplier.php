<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $connection = 'mysqlFAPG';
    protected $table = 'master_supplier';
    // protected $keyType = 'string';
    // public $incrementing = false;


}

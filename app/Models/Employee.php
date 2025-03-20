<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $connection = 'mysqlHRD';
    protected $table = 'employee';

    protected $primaryKey = 'id'; // default primary key
    public $incrementing = true; // default autoincrement
    protected $keyType = 'int'; // default key type

    // protected $keyType = 'string';
    // public $incrementing = false;

    public function approver(){
        return $this->hasMany(Approver::class, 'employee_id', 'id');
    }

    public function user(){
        return $this->hasOne(User::class, 'email', 'email');
    }

}

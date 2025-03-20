<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model{
    use HasFactory;

    protected $connection = 'mysqlHRD';
    protected $table = 'department';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      'id',
    //   'date_created',
    //   'last_updated',
      'name',
      'head',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
      'date_created' => 'datetime',
      'last_updated' => 'datetime',
    ];

    public $timestamps = false;

}

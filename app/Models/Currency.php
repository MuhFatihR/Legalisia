<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'currency';

    protected $fillable = [
        'id',
        'currency_name'
    ];

    public function penomoran_final_kontrak(){
        return $this->belongsTo(PenomoranFinalKontrak::class, 'currency_id', 'id');
    }

}

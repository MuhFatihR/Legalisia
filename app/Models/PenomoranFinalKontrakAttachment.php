<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenomoranFinalKontrakAttachment extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'penomoran_final_kontrak_attachment';

    protected $fillable = [
        'pk_id',
        'path',
        'file_name'
    ];

    protected $casts = [
        'timestamp' => 'datetime'
    ];

    public function penomoran_final_kontrak()
    {
        return $this->belongsTo(PenomoranFinalKontrak::class, 'pk_id', 'id');
    }

    public $timestamps = false;

}

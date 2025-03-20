<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenomoranKontrakAttachment extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'penomoran_kontrak_attachment';

    // public static $table_name = "fapg_inventory.item";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pk_id',
        'path',
        'file_name'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'timestamp' => 'datetime'
    ];

    public function penomoran_kontrak()
    {
        return $this->belongsTo(PenomoranKontrak::class, 'pk_id', 'id');
    }

    public function approver(){
        return $this->hasMany(Approver::class, 'attachment_id', 'id');
    }

    public $timestamps = false;

}

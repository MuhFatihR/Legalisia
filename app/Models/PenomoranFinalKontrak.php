<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenomoranFinalKontrak extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'penomoran_final_kontrak';

    protected $fillable = [
        'so_id',
        'supplier_id',
        'po_number',
        'job_position',
        'company_name',
        'no_urut',
        'customer_name',
        'tanggal_kontrak',
        'template_kontrak',
        'no_kontrak_compnet',
        'no_kontrak_customer',
        'nama_uploader',
        'deskripsi',
        'final_format',
        'job_position_full',
        'company_name_full',
        'amount',
        'currency_id',
        'currency_full'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function so_numbering_c()
    {
        return $this->belongsTo(SoNumbering::class, 'so_id', 'id');
    }

    public function master_supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id_supplier');
    }

    public function penomoran_final_kontrak_attachment(){
        return $this->hasOne(PenomoranFinalKontrakAttachment::class, 'pk_id', 'id');
    }

    public function currency(){
        return $this->hasOne(Currency::class, 'currency_id', 'id');
    }

}

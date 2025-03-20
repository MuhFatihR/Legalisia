<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenomoranKontrak extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'penomoran_kontrak';

    // public static $table_name = "fapg_inventory.item";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
        'project_name',
        'job_position_full',
        'email_uploader',
        'no_need_numbering'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_created' => 'datetime',
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

    public function penomoran_kontrak_attachment(){
        return $this->hasOne(PenomoranKontrakAttachment::class, 'pk_id', 'id');
    }

    public function approver(){
        return $this->hasMany(Approver::class, 'pk_id', 'id')->orderBy('approver_level','asc');
    }

}

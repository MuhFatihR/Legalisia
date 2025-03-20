<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approver extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'approver';

    // public static $table_name = "fapg_inventory.item";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pk_id', // punya penomoran
        'attachment_id',
        'employee_id',
        'approver_email',
        'approver_name',
        'approver_level',
        'status_approval',
        'tanggal_approval',
        'notes'
    ];
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'date_created' => 'datetime',
    //     'last_updated' => 'datetime',
    // ];

    public function penomoran_kontrak()
    {
        return $this->belongsTo(PenomoranKontrak::class, 'pk_id', 'id');
    }

    public function penomoran_kontrak_attachment()
    {
        return $this->belongsTo(PenomoranKontrakAttachment::class, 'attachment_id', 'id');
    }


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

}

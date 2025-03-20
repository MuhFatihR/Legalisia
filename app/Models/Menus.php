<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Menus extends Model{
    use HasFactory;

    protected $connection = 'mysqlHRD';
    protected $table = 'menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        // 'date_created',
        // 'last_updated',
        'apps_id',
        'apps_name',
        'name',
        'description',
        'url',
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

    public function departement() {
        return $this->hasMany(DepartmentMenus::class, 'menus_id', 'id');
    }

    public static function getMenus($department) {
        $data = DB::connection('mysqlHRD')->select(
                    "SELECT a.id, a.date_created, a.last_updated, a.apps_name AS apps_name, a.name AS menu_name, a.url, a.description, dp.name, d.department_id
                        FROM hrd.department_menus d
                        LEFT JOIN hrd.menus a ON a.id = d.menus_id
                        LEFT JOIN hrd.department dp ON dp.id = d.department_id
                        WHERE (a.apps_name = 'Portal Legal') AND dp.name = '" . $department . "'
                        ORDER BY a.date_created ASC");
        return $data;
    }

    public static function isMenuExist($array, $search){
        return collect($array)->contains(function ($row) use ($search) {
            return collect($row)->contains(function ($value) use ($search) {
                return strpos($value, $search) !== false;
            });
        });
    }

    // public static function getMenu($array, $search){
    //     return collect($array)->filter(function ($row) use ($search) {
    //         return collect($row)->contains(function ($value) use ($search) {
    //             return strpos($value, $search) !== false;
    //         });
    //     })->first();
    // }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LdapRecord\Laravel\Auth\HasLdapUser;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Models\Attributes\Timestamp;

class User extends Authenticatable implements LdapAuthenticatable
{
    use HasApiTokens, HasFactory, Notifiable, AuthenticatesWithLdap, HasLdapUser;

    protected $primaryKey = 'id'; // default primary key
    public $incrementing = true; // default autoincrement
    protected $keyType = 'int'; // default key type

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'password',
        'email',
        'mobile',
        'gender',
        'location',
        'badgeid',
        'nik',
        'level',
        'title',
        'section',
        'department',
        'division',
        'directorate',
        'company',
        'supervisor',
        'manager',
        'photo',
        'domain',
        'guid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'email', 'email');
    }

    public function department() {
        // $department = Department::find($this->department);
        $department = Department::query()->where('name', 'LIKE', $this->department)->first();
        return $department;
    }

    public function hasRole($role) {
        foreach($role AS $dept){
          $dept = str_replace("-"," ",$dept);
        // dd($this->department());
        // dd(auth()->user());
          if (strtolower($this->department()->name) == strtolower($dept)) {
              return true;
          }
        }
        return false;
    }

    public function hasAdmin() {
        if ($this->hasRole(['it-support'])) {
            return true;
        }
        return false;
    }
    
    public $timestamps = false;
}

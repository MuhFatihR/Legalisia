<?php

namespace App\Ldap\Compnet;

use LdapRecord\Models\ActiveDirectory\User AS Model;

class User extends Model
{
    protected $connection = 'compnet';

    /**
     * The object classes of the LDAP model.
     *
     * @var array
     */
    public static $objectClasses = [];
}

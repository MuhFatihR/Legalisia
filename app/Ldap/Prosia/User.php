<?php

namespace App\Ldap\Prosia;

use LdapRecord\Models\OpenLDAP\User AS Model;

class User extends Model
{
    protected $connection = 'prosia';

    /**
     * The object classes of the LDAP model.
     *
     * @var array
     */
    public static $objectClasses = [];
    
    protected $guidKey = 'entryUUID';
}

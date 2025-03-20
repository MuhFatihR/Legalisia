<?php

namespace App\Ldap\Prosia;

use App\Models\User as DatabaseUser;
use App\Ldap\Prosia\User as LdapUser;

class LdapAttributeHandler
{
    public function handle(LdapUser $ldap, DatabaseUser $database)
    {
        $database->name = $ldap->getFirstAttribute('cn');
        $database->email = $ldap->getFirstAttribute('mail');
        $database->mobile = $ldap->getFirstAttribute('mobile');
        $database->gender = $ldap->getFirstAttribute('initials');
        $database->location = $ldap->getFirstAttribute('l');
        $database->title = $ldap->getFirstAttribute('title');
    }
}
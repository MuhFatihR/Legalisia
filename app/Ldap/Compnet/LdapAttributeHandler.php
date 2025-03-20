<?php

namespace App\Ldap\Compnet;

use App\Models\User as DatabaseUser;
use App\Ldap\Compnet\User as LdapUser;
use LdapRecord\Models\ActiveDirectory\User;

class LdapAttributeHandler
{
    public function handle(LdapUser $ldap, DatabaseUser $database)
    {
        $spv = User::find($ldap->getFirstAttribute('comment'));
        $mgr = User::find($ldap->getFirstAttribute('manager'));
        $database->name = $ldap->getFirstAttribute('cn');
        $database->email = $ldap->getFirstAttribute('mail');
        $database->mobile = $ldap->getFirstAttribute('mobile');
        $database->gender = $ldap->getFirstAttribute('personalTitle');
        $database->location = $ldap->getFirstAttribute('l');
        $database->badgeid = $ldap->getFirstAttribute('employeeId');
        $database->nik = $ldap->getFirstAttribute('employeeNumber');
        $database->level = $ldap->getFirstAttribute('description');
        $database->title = $ldap->getFirstAttribute('title');
        $database->section = $ldap->getFirstAttribute('info');
        $database->department = $ldap->getFirstAttribute('department');
        $database->division = $ldap->getFirstAttribute('division');
        $database->directorate = $ldap->getFirstAttribute('businessCategory');
        $database->company = $ldap->getFirstAttribute('company');
        $database->supervisor = $spv->getFirstAttribute('mail');
        $database->manager = $mgr->getFirstAttribute('mail');
        $database->photo = base64_encode($ldap->getFirstAttribute('photo'));
    }
}
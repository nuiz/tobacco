<?php
function _ad($username, $password){
    $ldap_url = "tobacco.or.th";
    $ldap_domain = "tobacco.or.th";
    $ldap_dn = "DC=tobacco,DC=or,DC=th";
    
    $ldap = ldap_connect($ldap_url);

    $ldaprdn = $username . "@" . $ldap_domain;

    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    $bind = @ldap_bind($ldap, $ldaprdn, $password);

    if ($bind) {
        @ldap_close($ldap);
        return true;
    } else {
        return false;
    }
}

echo _ad($_REQUEST["username"], $_REQUEST["password"])? 1: 0;
<?php

if (isset($_POST['username']) && isset($_POST['password'])) {
    $adServer = "41.242.57.127";
    $ldap = ldap_connect($adServer, 389);
    
    if (!$ldap) {
        die('Could not connect to LDAP server.');
    }
    
    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
    
    $username = $_POST['username'];
    $password = $_POST['password'];

 
    $ldaprdn = "cn=$username,dc=ferma,dc=gov,dc=ng";
    $bind = @ldap_bind($ldap, $ldaprdn, $password);
    
    if ($bind) {
        echo 'LDAP bind successful.';
    } else {
        $ldap_error = ldap_error($ldap);
        echo "LDAP bind failed: $ldap_error";
    }

    ldap_close($ldap);
} else {
    ?>
    <form action="#" method="POST">
        <label for="username">Username: </label>
        <input id="username" type="text" name="username" />
        <label for="password">Password: </label>
        <input id="password" type="password" name="password" />
        <input type="submit" name="submit" value="Submit" />
    </form>
    <?php
}

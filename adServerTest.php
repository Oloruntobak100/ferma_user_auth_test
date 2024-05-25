<?php



if (isset($_POST['username']) && isset($_POST['password'])) {

    $adServer = "41.242.57.127";
    $ldap = ldap_connect($adServer, 389);
     
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    $ldaprdn = $username;

    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    $bind = @ldap_bind($ldap, $ldaprdn, $password);
    echo '<pre>';
    print_r($bind);
    echo '</pre>';
    
    if ($bind) {
        $filter = "(sAMAccountName=$username)";
        $result = ldap_search($ldap, "dc=ferma,dc=gov,dc=ng", $filter);
        ldap_sort($ldap, $result, "sn");
        $info = ldap_get_entries($ldap, $result);
        echo '<pre>';
        print_r($info);
        echo '</pre>';
        for ($i = 0; $i < $info["count"]; $i++) {
            if ($info['count'] > 1)
                break;
            echo "<p>You are accessing <strong> " . $info[$i]["sn"][0] . ", " . $info[$i]["givenname"][0] . "</strong><br /> (" . $info[$i]["samaccountname"][0] . ")</p>\n";
            echo '<pre>';
            var_dump($info);
            echo '</pre>';
            $userDn = $info[$i]["distinguishedname"][0];
        }
        @ldap_close($ldap);
    } else {
        $msg = "Invalid email address / password";
        echo $msg;
    }

} else {
?>
    <form action="#" method="POST">
        <label for="username">Username: </label><input id="username" type="text" name="username" /> 
        <label for="password">Password: </label><input id="password" type="password" name="password" />        
        <input type="submit" name="submit" value="Submit" />
    </form>
<?php
}
?>

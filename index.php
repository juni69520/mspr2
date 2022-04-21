<!DOCTYPE html>
<html>
    <head>
        <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
        <link href="./include/connexion.css" rel="stylesheet"/>
        <title></title>
    </head>
    <body>
        <div class="circles">
            <div class="circle1"></div>
            <div class="circle2"></div>
        </div>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" class="login_form">
            <h1>Clinique du Chatelet</h1>
            <p>Se connecter à votre compte.</p>
            <input type="username" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <input type="submit" value="Login">
        </form>
    </body>
</html>

<?php
    require_once('include/include.inc.php');
    session_start();

    if(!isset($_COOKIE['failConnection'])) {
        setcookie("failConnection", 0);
    }elseif($_COOKIE['failConnection'] == 5){
        echo '<script type="text/javascript">toastr.error("Trop de tentative de connexion, rechargement de la page dans 5s")</script>';
        sleep(30);
        unset($_COOKIE['failConnection']); 
        setcookie('failConnection', null, -1, '/'); 
        header("Refresh:5");
    }
    
    if(isset($_POST['username']) && $_POST['username'] != ''){
        $ldaprdn  = $_POST['username'];
        $ldappass = $_POST['password'];

        $sth = $conn->prepare("SELECT * FROM user WHERE nom_user = :nom_user");
        $sth->bindParam(':nom_user', $ldaprdn, PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        $oIp = new ip();
        $config = parse_ini_file('./private/config.ini');
        $ldapconn = ldap_connect($config['APP_LDAP_IP'])
        or die("Could not connect to LDAP server.");

        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

        if ($ldapconn) {
            $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);
            if ($ldapbind) {
                $_SESSION['logged'] = 'oui';
                $ldap_base_dn = $config['APP_LDAP_DC'];
                $search_filter = "(UserPrincipalName={$ldaprdn})";
                $resultLdap = ldap_search($ldapconn, $ldap_base_dn, $search_filter);
                $entries = ldap_get_entries($ldapconn, $resultLdap);
                $email = $entries[0]["mail"][0];

                if($oIp->getCountry() != 'FR'){
                    new mail($email, 'ipHorsFrance');
                    die();
                }elseif($result['ip_user'] != $oIp->getIpAddress()){
                    new mail($email, 'default');
                }elseif($result['navigateur_user'] != $oIp->getBrowser()){
                    new mail($email, 'NavigateurDifferent');
                }

                if(!isset($result['id_user']) || (isset($result['id_user']) && $result['two_factor'] != 'o')){
                    echo '<script type="text/javascript">toastr.error("Connexion réussie, veuillez confirmer votre email.")</script>';
                    
                    if(!isset($result['id_user'])){
                        $code = base64_encode(random_bytes(10));
                        $sql = "INSERT INTO user (nom_user, ip_user, navigateur_user, code_user) VALUES (?,?,?,?)";
                        $stmt= $conn->prepare($sql);
                        $stmt->execute([$ldaprdn, $oIp->getIpAddress(), $oIp->getBrowser(), $code]);
                        $_SESSION['id_user'] = $conn->lastInsertId();
                    }else{
                        $_SESSION['id_user'] = $result['id_user'];
                        $code = $result['code_user'];
                    }

                    new mail($email, '2fa_'.$code);
                    header('Location: 2fa.php');
                    exit();
                }else{
                    echo '<script type="text/javascript">toastr.error("Connexion réussie")</script>';
                    $_SESSION['id_user'] = $result['id_user'];

                    header('Location: files.php');
                    exit();
                }
            } else {
                $value = $_COOKIE['failConnection'] + 1;
                setcookie("failConnection", $value);
                echo '<script type="text/javascript">toastr.error("Echec de la connexion")</script>';
            }
        }
    }
?>

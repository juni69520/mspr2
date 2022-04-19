<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
    <head>
        <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
        <title></title>
    </head>
    <body>
        <form action="" method="post">
            <input name="username">
            <input type="password" name="password">
            <input type="submit" value="Submit">
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

        if(isset($result['id_user']) && $result['id_user'] != ''){
            if($result['ip_user'] != $oIp->getIpAddress()){
                new mail('quentin.viegas@gmail.com', 'default');
            }elseif($result['navigateur_user'] != $oIp->getBrowser()){
                new mail('quentin.viegas@gmail.com', 'NavigateurDifferent');
            }elseif($oIp->getBrowser() != '' &&  $oIp->getCountry() != 'France'){
                new mail('quentin.viegas@gmail.com', 'ipHorsFrance');
            }
        }

        $ldapconn = ldap_connect("ldap://192.168.1.21:389")
        or die("Could not connect to LDAP server.");
        if ($ldapconn) {
            $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);
            if ($ldapbind) {
                $_SESSION['logged'] = 'oui';
                if(!isset($result['id_user'])){
                    $code = base64_encode(random_bytes(10));
                    $sql = "INSERT INTO user (nom_user, ip_user, navigateur_user, code_user) VALUES (?,?,?,?)";
                    $stmt= $conn->prepare($sql);
                    $stmt->execute([$ldaprdn, $oIp->getIpAddress(), $oIp->getBrowser(), $code]);
                    $_SESSION['id_user'] = $conn->lastInsertId();

                    new mail('quentin.viegas@gmail.com', '2fa_'.$code);
                    header('Location: 2fa.php');
                    exit();
                }else{
                    $_SESSION['id_user'] = $result['id_user'];
                    header('Location: files.php');
                    exit();
                }
            } else {
                $value = $_COOKIE['failConnection'] + 1;
                setcookie("failConnection", $value);
                echo "Connexion au serveur LDAP échoué, vérifiez vos identifiants.";
            }
        }
    }
?>
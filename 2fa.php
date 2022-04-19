<!DOCTYPE html>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <form action="" method="post">
            <input name="2fa" placeholder="code vérifiaction">
            <input type="submit" value="Submit">
        </form>
    </body>
</html>
<?php
require_once('include/include.inc.php');
session_start();
if(isset($_SESSION['id_user']) && isset($_POST['2fa']) && $_POST['2fa'] != ''){
    $sth = $conn->prepare("SELECT * FROM user WHERE id_user = :id_user");
    $sth->bindParam(':id_user', $_SESSION['id_user'], PDO::PARAM_STR);
    $sth->execute();
    $result = $sth->fetch(PDO::FETCH_ASSOC);

    if($result['code_user'] == $_POST['2fa']){
        $sql = "UPDATE user SET two_factor = ? WHERE id_user = ?";
        $sth = $conn->prepare($sql);
        $sth->execute(['o',  $_SESSION['id_user']]);
        header('Location: files.php');
    }else{
        echo "Le code de vérification est incorect.";
    }
}
?>
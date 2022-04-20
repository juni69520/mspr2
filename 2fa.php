<?php
session_start();
if(!isset($_SESSION['logged']) || (isset($_SESSION['logged']) && $_SESSION['logged'] != 'oui')){
    header("Location: index.php");
}
?>

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
            <p>Rentrer le code de sécurisation.</p>
            <input name="2fa" placeholder="code vérifiaction">
            <input type="submit" value="Submit">
        </form>
    </body>
</html>
<?php
require_once('include/include.inc.php');

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
        echo '<script type="text/javascript">toastr.error("Le code de vérification est incorect.")</script>';
    }
}
?>
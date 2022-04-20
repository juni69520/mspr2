<?php
session_start();

if(!isset($_SESSION['logged']) || (isset($_SESSION['logged']) && $_SESSION['logged'] != 'oui')){
  header("Location: index.php");
}

$files1 = scandir('/partage/medecin');
if ($handle = opendir('/partage/medecin')) {
  while (false !== ($file = readdir($handle))) {
    if ($file != "." && $file != "..") {
        $thelist .= '<li>'.$file.'</li>';
      }
    }
    closedir($handle);
  }
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
    <h1>Liste des fichiers:</h1>
    <ul><?php echo $thelist; ?></ul>
  </body>
</html>
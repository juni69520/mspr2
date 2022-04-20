<?php
    try {
        $conn = new PDO('mysql:host=127.0.0.1:3306;dbname=cchatelet;charset=utf8', 'cchalet', 'P@$$w0rD');
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
?>
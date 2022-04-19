<?php
    try {
        $conn = new PDO('mysql:host=localhost:3306;dbname=cchatelet', 'cchalet', 'P@$$w0rD');
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
?>
<?php
    $config = parse_ini_file('./private/config.ini');
    try {
        $conn = new PDO('mysql:host='.$config['APP_BDD_IP'].';dbname='.$config['APP_BDD_DB'].';charset=utf8', $config['APP_BDD_USER'], $config['APP_BDD_PW']);
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
?>
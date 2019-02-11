<?php
session_start();
//Connexion avec la DB :
$db_jeuenergie = new mysqli("127.0.0.1", "root", "", "jeuenergie", 3306);

$stmt = $db_jeuenergie->prepare
(
    "UPDATE game
    SET etat = 'decision'
    WHERE id = ?"
);
$stmt->bind_param("i", $_SESSION['id_game']);
$stmt->execute();
$stmt->close();

$stmt = $db_jeuenergie->prepare
(
    "UPDATE game
    SET id_decis_active = id_decis_1
    WHERE id = ?"
);
$stmt->bind_param("i", $_SESSION['id_game']);
$stmt->execute();
$stmt->close();

$db_jeuenergie->close();
    
?>
<?php
session_start();

$db_jeuenergie = new mysqli("127.0.0.1", "root", "", "jeuenergie", 3306);

//Increment de la vote choisi
$stmt = $db_jeuenergie->prepare
(
    'UPDATE votes v
    INNER JOIN game g
        ON g.id = v.id_game
    SET v.nb_votes = v.nb_votes+1
    WHERE g.id = ? AND v.id_votes = ?'
);
$stmt->bind_param("ii", $_SESSION['id_game'], $_POST['id_choix']);
$stmt->execute();
$stmt->close();
//On indique dans la table use rque le joueur a voté
$stmt = $db_jeuenergie->prepare
(
    'UPDATE user
    SET votes = 1
    WHERE id = ?'
);
$stmt->bind_param("i", $_SESSION['id_user']);
$stmt->execute();
$stmt->close();

$db_jeuenergie->close();
?>
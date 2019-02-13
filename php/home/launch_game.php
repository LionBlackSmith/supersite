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

//On ajoute dans la table Votes les votes assicié à la premiere decision
$stmt = $db_jeuenergie->prepare
(
    'SELECT c.id id_choix
    FROM decision d
    INNER JOIN game g
        ON g.id_decis_active = d.id
    INNER JOIN choice c
        ON c.id_decision = d.id
    WHERE g.id =?'
);
$stmt->bind_param("i", $_SESSION['id_game']);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();

while ($deci = $res->fetch_array())
{
    $stmt = $db_jeuenergie->prepare
    (
        'INSERT INTO votes(id_game, id_votes)
        VALUES(?,?)'
    );
    $stmt->bind_param("ii", $_SESSION['id_game'], $deci['id_choix']);
    $stmt->execute();
    $stmt->close();
}
$res->close();

$db_jeuenergie->close();    
?>
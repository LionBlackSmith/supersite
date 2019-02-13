<?php

session_start();

$db_jeuenergie = new mysqli("127.0.0.1", "root", "", "jeuenergie", 3306);


$stmt = $db_jeuenergie->prepare('SELECT SUM(nb_votes) nb
                                FROM votes        
                                WHERE id_game = ?');
$stmt->bind_param('i', $_SESSION['id_game']);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();
$votes = $res->fetch_assoc();
$res->close();

$stmt = $db_jeuenergie->prepare('SELECT COUNT(*) nb
                                FROM game g
                                INNER JOIN user u
                                    ON g.id = u.id_game        
                                WHERE g.id  = ?');
$stmt->bind_param('i', $_SESSION['id_game']);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();
$joueurs = $res->fetch_assoc();
$res->close();

$db_jeuenergie->close();

echo $joueurs['nb'] - $votes['nb'];
?>
<?php

session_start();

$db_jeuenergie = new mysqli("127.0.0.1", "root", "", "jeuenergie", 3306);

//On recupere la derniere decision active et on vérifie si elle est déjà dans la table des decission passée si oui on en tire une autre
$stmt = $db_jeuenergie->prepare
(
    'SELECT id_decis_active ida
    FROM game
    WHERE id = ?'
);      
$stmt->bind_param('i', $_SESSION['id_game']);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();
$game = $res->fetch_assoc();
$res->close();

$stmt = $db_jeuenergie->prepare
(
    'SELECT id_decision
    FROM past_decisions
    WHERE id_game = ?'
);      
$stmt->bind_param('i', $_SESSION['id_game']);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();
$past_dec = $res->fetch_all();
$res->close();

$past_dec_id = array_column($past_dec,0);

if (in_array($game['ida'],$past_dec_id)) 
{
    //tirage aleatoire d'une decision en excluant celles deja tombées
    $stmt = $db_jeuenergie->prepare
    (
        'SELECT d.id id
        FROM decision d 
        LEFT OUTER JOIN (SELECT * 
                        FROM past_decisions 
                        WHERE id_game = ?) p 
            ON d.id = p.id_decision 
        WHERE p.id_decision IS NULL'
    );        
    $stmt->bind_param('i', $_SESSION['id_game']);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    $matrix_dec= $res->fetch_all();
    $res->close();
    $decision = array_column($matrix_dec,0);
    shuffle($decision);

    $stmt = $db_jeuenergie->prepare
    (
        'UPDATE game
        SET id_decis_active = ?
        WHERE id = ?'
    );
    $stmt->bind_param('ii', $decision[0], $_SESSION['id_game']);
    $stmt->execute();
    $stmt->close();
    
    //On ajoute dans la table VOTES les votes assicié à la decision active
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
}

$db_jeuenergie->close();
?>


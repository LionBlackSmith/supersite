<?php
session_start();
//Connextion avec la DB
$db_jeuenergie = new mysqli("127.0.0.1", "root", "", "jeuenergie", 3306);

//On detruit le joueur de la DB et on reboot sa session
$stmt = $db_jeuenergie->prepare
(
    'DELETE 
    FROM user 
    WHERE id =?'
);
$stmt->bind_param("i", $_SESSION['id_user']);
$stmt->execute();
$stmt->close();  

//On recupere le nombre de joueur et l'id de l'host
$stmt = $db_jeuenergie->prepare
(
    'SELECT nombre_joueur nj, id_host 
    FROM game 
    WHERE id = ?'
);
$stmt->bind_param("i", $_SESSION['id_game']);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();
$game = $res->fetch_array();
$res->close();

//Si il ne reste que le joueur se déconectant on supprime la partie
if ($game['nj'] <= 1) 
{
    $stmt = $db_jeuenergie->prepare
    (
        'DELETE 
        FROM game
        WHERE id =?'
    );
    $stmt->bind_param("i", $_SESSION['id_game']);
    $stmt->execute();
    $stmt->close();     
}
else 
{
    // Actualisation du nombre de joueur :
    $stmt = $db_jeuenergie->prepare
    (
        'UPDATE game
        SET nombre_joueur = (
                            SELECT COUNT(*)
                            FROM user
                            WHERE id_game = ?
                            )
        WHERE id = ?'
    );
    $stmt->bind_param("ii", $_SESSION['id_game'], $_SESSION['id_game']);
    $stmt->execute();
    $stmt->close();

    //Si le joueur se deconnectant est l'host on change d'host et on rectifie le nombre de joueurs
    if ($game['id_host'] == $_SESSION['id_user']) 
    {
        $stmt = $db_jeuenergie->prepare
        (
            'SELECT user.id id
            FROM user
            INNER JOIN game
                ON game.id = user.id_game
            WHERE game.id = ?'
        );
        $stmt->bind_param("i", $_SESSION['id_game']);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        $host = $res->fetch_array();
        $res->close();

        //Implementation du nouvel host
        $stmt = $db_jeuenergie->prepare
        (
            'UPDATE game 
            SET id_host = ? 
            WHERE id =?'
        );
        $stmt->bind_param("ii",$host['id'],  $_SESSION['id_game']);
        $stmt->execute();
        $stmt->close();
    }
}

session_unset();
//Déconnexion de la DB :
$db_jeuenergie->close();

?>
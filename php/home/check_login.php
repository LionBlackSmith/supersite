<?php

//Connexion avec la DB :
$db_jeuenergie = new mysqli("127.0.0.1", "root", "", "jeuenergie", 3306);

//Verification de l'existance de la partie
$stmt = $db_jeuenergie->prepare
(
    'SELECT id
    FROM game 
    WHERE id= ?'
);
$stmt->bind_param("i", $_POST['id_game']);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();
if($res->num_rows == 0)
{
    $res->close();
    $db_jeuenergie->close();
    exit('0');
}
$res->close();

//Verification de la correspondance du mdp
$stmt = $db_jeuenergie->prepare
(
    'SELECT id, password 
    FROM game 
    WHERE id= ? AND password=?'
);
$stmt->bind_param("is", $_POST['id_game'], $_POST['password']);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();
if($res->num_rows == 0)
{
    $res->close();
    $db_jeuenergie->close();
    exit('1');
}
$res->close();


//Verification du nombre de places restante :
$stmt = $db_jeuenergie->prepare
(
    'SELECT nombre_joueur nj, etat
    FROM game 
    WHERE id= ?'
);
$stmt->bind_param("i", $_POST['id_game']);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();
$res->close();

if($row['nj'] > 10)
{
    $db_jeuenergie->close();
    exit('2');
}

if($row['etat'] != 'lobby')
{
    $db_jeuenergie->close();
    exit('3');
}

//Déconnexion de la DB :
$db_jeuenergie->close();
exit('4');
    
?>
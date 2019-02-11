<?php
session_start();

//Connexion avec la DB :
$db_jeuenergie = new mysqli("127.0.0.1", "root", "", "jeuenergie", 3306);

if ($db_jeuenergie->connect_errno) 
{
    echo "Echec lors de la connexion à la base de donnée: (" . $db_jeuenergie->connect_errno . ") " . $db_jeuenergie->connect_error;
    exit();
}

//On creer les variables session
$_SESSION['pseudo']         = $_POST['pseudo'];
$_SESSION['password']       = $_POST['password'];

//Creation de la partie dans la table GAME de la DB :
if(!($stmt = $db_jeuenergie->prepare('INSERT INTO game(password) 
                                    VALUES(?)')))
{
    echo $stmt->error;
}
if(!($stmt->bind_param("s", $_SESSION['password'])))
{
    echo $stmt->error;
}
if(!$stmt->execute())
{
    echo $stmt->error;
}
$_SESSION['id_game'] = $stmt->insert_id;
$stmt->close();

//Tirage aléatoire des N decisions :
$result = $db_jeuenergie->query
(
    'SELECT id 
    FROM decision'
);
$dec = $result->fetch_all();
shuffle($dec);

//Tirage des rôles et idéologies
$result = $db_jeuenergie->query
(
    'SELECT id 
    FROM roles'
);
$roles = $result->fetch_all();
shuffle($roles);

$result = $db_jeuenergie->query
(
    'SELECT id 
    FROM ideology'
);
$ideo = $result->fetch_all();
shuffle($ideo);

//Creation du joueur host dans la table USER :
if(!$stmt = $db_jeuenergie->prepare('INSERT INTO user(username, id_game, id_role, id_ideo)
                                    VALUES(?, ?, ?, ?)'))
{
    echo $stmt->error;
}
if(!$stmt->bind_param("siii", $_SESSION['pseudo'], $_SESSION['id_game'], $roles[0][0], $ideo[0][0]))
{
    echo $stmt->error;
}
if(!$stmt->execute())
{
    echo $stmt->error;
}
$_SESSION['id_user'] = $stmt->insert_id;
$stmt->close();

//Ajout de l'id_host et des id decsision dans la table GAME :
if(!$stmt = $db_jeuenergie->prepare('UPDATE game 
                                    SET id_host=?, id_decis_1=?, id_decis_2=?, id_decis_3=?, id_decis_4=? 
                                    WHERE id=?'))
{
    echo $stmt->error;
}
if(!$stmt->bind_param("iiiiii", $_SESSION['id_user'], $dec[0][0], $dec[1][0], $dec[2][0], $dec[3][0], $_SESSION['id_game']))
{
    echo $stmt->error;
}
if(!$stmt->execute())
{
    echo $stmt->error;
}
$stmt->close();

//Déconnexion de la DB :
$db_jeuenergie->close();

?>
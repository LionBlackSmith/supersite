<?php
session_start();

//Connexion avec la DB :
$db_jeuenergie = new mysqli("127.0.0.1", "root", "", "jeuenergie", 3306);

if ($db_jeuenergie->connect_errno) 
{
    echo "Echec lors de la connexion à MySQL: (" . $db_jeuenergie->connect_errno . ") " . $db_jeuenergie->connect_error;
    exit();
}

//Initialisation des données session
$_SESSION['id_game']        = $_POST['id_game'];
$_SESSION['pseudo']         = $_POST['pseudo'];
$_SESSION['password']       = $_POST['password'];

//Tirage des rôles et idéologies
$stmt = $db_jeuenergie->prepare
(
    'SELECT id 
    FROM roles 
    WHERE id NOT IN (
                    SELECT id_role 
                    FROM user 
                    WHERE id_game = ?
                    )'
);
$stmt->bind_param('i', $_SESSION['id_game']);
$stmt->execute();
$res = $stmt->get_result();
$roles = $res->fetch_all();
$stmt->close();
$res->close();
shuffle($roles);
    
$result = $db_jeuenergie->query
(
    'SELECT id 
    FROM ideology'
);
$ideo = $result->fetch_all();
shuffle($ideo);

//Creation du joueur dans la DB
$stmt = $db_jeuenergie->prepare
(
    'INSERT INTO user(username, id_game, id_role, id_ideo) 
    VALUE(?, ?, ?, ?)'
);
$stmt->bind_param("siii",$_SESSION['pseudo'],  $_SESSION['id_game'], $roles[0][0], $ideo[0][0]);
$stmt->execute();
$_SESSION['id_user'] = $stmt->insert_id;
$stmt->close();

$_SESSION['id_role']    = $roles[0][0];
$_SESSION['id_ideo']    = $ideo[0][0];

//Actualisation du nombre de joueur :
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


//Déconnexion de la DB :
$db_jeuenergie->close();
    
?>
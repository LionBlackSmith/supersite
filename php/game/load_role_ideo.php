<?php

$db_jeuenergie = new mysqli("127.0.0.1", "root", "", "jeuenergie", 3306);

$stmt = $db_jeuenergie->prepare('SELECT roles.nom nom_role, ideology.nom nom_ideo, roles.description desc_role, ideology.description desc_ideo, user.voted voted
                                FROM user 
                                INNER JOIN roles 
                                    ON roles.id = user.id_role
                                INNER JOIN ideology 
                                    ON ideology.id = user.id_ideo        
                                WHERE user.id = ?');
$stmt->bind_param('i', $_SESSION['id_user']);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();
$player = $res->fetch_assoc();
$res->close();

$db_jeuenergie->close();
?>
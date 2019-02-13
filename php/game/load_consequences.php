<?php

session_start();

$db_jeuenergie = new mysqli("127.0.0.1", "root", "", "jeuenergie", 3306);


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

if (!in_array($game['ida'],$past_dec_id)) 
{
    $stmt = $db_jeuenergie->prepare
    (
        'INSERT INTO past_decisions(id_game, id_decision)
        VALUE(?,?)'
    );        
    $stmt->bind_param('ii', $_SESSION['id_game'], $game['ida']);
    $stmt->execute();
    $stmt->close();
}

$stmt = $db_jeuenergie->prepare
(
    'SELECT id_votes id
    FROM votes
    WHERE id_game = ? 
    AND nb_votes = (SELECT max(nb_votes) 
                    FROM votes
                    WHERE id_game = ?)'
);
        
$stmt->bind_param('ii', $_SESSION['id_game'], $_SESSION['id_game']);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();
$choix = $res->fetch_assoc();
$res->close();


// $stmt = $db_jeuenergie->prepare
// (
//     'UPDATE game
//     SET id_last_choice = ?
//     WHERE id = ?'
// );
// $stmt->bind_param("ii", $choix['id'], $_SESSION['id_user']);
// $stmt->execute();
// $stmt->close();

$stmt = $db_jeuenergie->prepare
(
    'SELECT nom_choix, nom_conseq, txt_conseq
    FROM choice       
    WHERE id  = ?'
);
$stmt->bind_param('i', $choix['id']);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();
$data = $res->fetch_assoc();
$res->close();

$stmt = $db_jeuenergie->prepare
(
    'UPDATE user
    SET voted = 0
    WHERE id = ?'
);
$stmt->bind_param("i", $_SESSION['id_user']);
$stmt->execute();
$stmt->close();

$db_jeuenergie->close();

$datajson = array_map('utf8_encode', $data);
echo json_encode($datajson);
?>
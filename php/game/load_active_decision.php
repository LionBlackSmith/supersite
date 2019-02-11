<?php
session_start();

$db_jeuenergie = new mysqli("127.0.0.1", "root", "", "jeuenergie", 3306);

//On detruit le joueur de la DB et on reboot sa session
$stmt = $db_jeuenergie->prepare
(
    'SELECT d.nom decision, d.description decision_txt, c.nom_choix choix
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
$nbchoix = $res->num_rows;
$deci = $res->fetch_assoc();

$data['decision'] = $deci['decision'];
$data['decision_txt'] = $deci['decision_txt'];
$data['choix'] = "  <p>
                        <input type='button' class='choix' id='choix_1' name='choix_1' value='".$deci['choix']."'/>    
                    </p>";

for ($i=2; $i <= $nbchoix; $i++) 
{
    $deci = $res->fetch_assoc();
    $data['choix'] .= " <p>
                            <input type='button' id='choix_".$i."' name='choix_".$i."' value='".$deci['choix']."'/>
                        </p>";   
}


?>
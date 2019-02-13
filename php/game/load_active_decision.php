<?php
session_start();

$db_jeuenergie = new mysqli("127.0.0.1", "root", "", "jeuenergie", 3306);

//on recupere les nom et description de la decisions en cours et des choix qui lui son associé
$stmt = $db_jeuenergie->prepare
(
    'SELECT d.nom decision, d.description decision_txt, c.nom_choix choix, c.id id_choix
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
$deci = $res->fetch_all();

$data['decision'] = $deci[0][0];
$data['decision_txt'] = $deci[0][1];
$data['choix'] = "";

for ($i=0; $i < $nbchoix; $i++) 
{
    $data['choix'] .= " <p>
                            <input type='button' class='choix' id='".$deci[$i][3]."' name='choix_".$i."' value='".$deci[$i][2]."'/>
                        </p>";   
}
  
$db_jeuenergie->close();
echo json_encode($data);
?>
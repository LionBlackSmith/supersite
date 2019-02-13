<?php
session_start();

$nbjoueurmin = 1;

$db_jeuenergie = new mysqli("127.0.0.1", "root", "", "jeuenergie", 3306);

//On recupere le nom et l'id de l'host :
$stmt = $db_jeuenergie->prepare('SELECT game.id_host id_host, game.nombre_joueur nj, game.etat etat
                                FROM game
                                INNER JOIN user
                                    ON user.id = game.id_host
                                WHERE game.id = ?');
$stmt->bind_param("i", $_SESSION['id_game']);
$stmt->execute();
$res = $stmt->get_result();
$game = $res->fetch_assoc();
$stmt->close();
$res->close();

//On recupere tous les noms, roles et idéologies des joueurs de la partie :
$stmt = $db_jeuenergie->prepare('SELECT user.id id, user.username pseudo, roles.nom nom_role, ideology.nom nom_ideo
                                FROM user 
                                INNER JOIN roles 
                                    ON roles.id = user.id_role
                                INNER JOIN ideology 
                                    ON ideology.id = user.id_ideo        
                                WHERE user.id_game = ?');
$stmt->bind_param('i', $_SESSION['id_game']);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();

$data['table'] = '  <tr>
                        <th>Joueur</th>
                        <td>Rôle</td>
                        <td>Idéologie</td>
                    </tr>' ;

// On remplie le tableau
while($player = $res->fetch_assoc())
{
    if($player['id'] == $game['id_host']) 
    {
        $data['table'] .= ' <tr>
                                <th>'.$player['pseudo'].' <mark>(hôte)</mark></th>
                                <td>'.$player['nom_role'].'</td>
                                <td>'.$player['nom_ideo'].'</td>
                            </tr>';
    }
    else
    {
        $data['table'] .= ' <tr>
                                <th>'.$player['pseudo'].'</th>
                                <td>'.$player['nom_role'].'</td>
                                <td>'.$player['nom_ideo'].'</td>
                            </tr>';
    }
}
$res->close();


//Si le joueur envoyant la requete est l'hote, on si affiche le boutton permetant de lancer la partie :
if ($_SESSION['id_user'] == $game['id_host']) 
{
    if ($game['nj'] >= $nbjoueurmin) 
    {
        $data['button'] = "<form id='form_launch_game' onsubmit = 'return false;'>
                            <input type='submit' id='launch_game' name='launch_game' value='Lancer la partie' />    
                        </form>";
    }else
    {
        $data['button'] = "<p>Un minimum de ".$nbjoueurmin." joueurs est requis pour lancer la partie.</p>";
    }
}else
{
    $data['button'] = "<p>Seul l'hôte peut lancer la partie.</p>";
}

//Déconnexion de la DB :
$db_jeuenergie->close(); 

$data['game_state'] = $game['etat'];
echo json_encode($data);    

?>
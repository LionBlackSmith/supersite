<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Jeu Energies</title>
    </head>

    <body>
        <h1>Titre imortant de la page (Le jeu energie)</h1>

        <p>J'écris un paragraphe<br />
        je saute une ligne</p> 

        <h2>Titre de 2nd niveau (Regles du jeu)</h2>


        <p>une liste non ordonnée :</p>
        <ul>
            <li>Fraises</li>
            <li>Framboises</li>
            <li>Cerises</li>   
        </ul>

        <p>une liste ordonnée :</p>
        <ol>
            <li>Fraises</li>
            <li>Framboises</li>
            <li>Cerises</li>
        </ol>

        <?php
        $test = array("table" => "valueA", "button" => "valueB");
        var_dump($test);
        $json =json_encode($test);
        var_dump($json);


        echo json_encode($test);
        ?>



    
        </p>

    </body>
</html>
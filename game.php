<?php 
session_start();

if (!isset($_SESSION['id_game'])) 
{
    header('Location: index.php'); 
}
require('php/game/load_role_ideo.php');
?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8" />
        <title>Jeu Energies</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="script/dist/jquery.validate.js"></script>
        <script src="script/dist/localization/messages_fr.js"></script>
    </head>

    <header>
        <h1><strong>Jeu Energie</strong> : le jeu des energies!</h1>
    </header>

    <body>

        <h2 id="titre_role_ideo">Role et ideologie</h2>
        <div id="role_ideo">

            <h3 id="titre_role"><?php echo $player['nom_role']; ?></h3>
            <article id="role">
                <p><?php echo $player['desc_role']; ?></p>
            </article>

            <h3 id="titre_ideo"><?php echo $player['nom_ideo']; ?></h3>
            <article id="ideo">
                <p> <?php echo $player['desc_ideo']; ?> </p>
            </article> 
        </div>

        <h2 id="title_decision"></h2>
        <div id='decision'>
            <div id="text_decision"></div>

            <div id="votes"></div>       
        </div>

        <form id="form_logout" onsubmit = "return false;">
            <p>
                <input type="submit" id="logout" name="logout" value="Quitter la partie" />
            </p>
        </form>
    </body>

    <script>
        
        $(document).ready(function() 
        {
            var voted = <?php echo $player['voted']; ?>;
            if (!voted){ load_active_decision(); }
            else{ wait_votes(); }  
            

            $("#titre_role_ideo").click(function(){$('#role_ideo').toggle(); });

            $("#titre_role").click(function(){ $("#role").toggle(); }); 

            $("#titre_ideo").click(function() { $("#ideo").toggle(); });

            $(document).on('click','.choix', function()
            {
                var id_choix = $(this).attr('id');
                $.ajax(
                {
                    url : "php/game/save_vote",
                    method : "POST",
                    data : 
                    {
                        id_choix : id_choix
                    },
                    success : function()
                    {
                        wait_votes();
                    }
                });
            });   

            $(document).on('submit','#form_logout', function()
            {
                log_out();
            });

            $(document).on('submit','#form_next', function()
            {
                change_active_decision();
                load_active_decision();
            }); 
















            //Suppression des information du joueur dans la DB et de la session, renvoit à la page d'acceuil
            function log_out()
            {
                $.ajax(
                {
                    url : 'php/common/log_out.php',
                    success:function()
                    {
                        document.location.replace('index.php')                       
                    }
                });
            } 

            //Affiche la decision a prendre avec un texte explicatif et les bouttons de votes associé
            function load_active_decision()
            {
                $.ajax(
                {
                    url : 'php/game/load_active_decision.php',
                    dataType : 'json',
                    success:function(data)
                    {
                        $("#title_decision").html(data.decision);
                        $("#text_decision").html(data.decision_txt);
                        $("#votes").html(data.choix);                                           
                    }
                });                
            }

            //actualise l'affichage du nombre de votes manquant et lance la fonction suivante (load_consequences) si tout le monde a voté
            function wait_votes()
            {
                $.ajax(
                {
                    url : 'php/game/wait_votes.php',                   
                    success:function(data)
                    {
                        if(data > 0)
                        {
                            if (data == 1) 
                            {
                                $('#votes').html("<p>Un joueur n'a pas encore voté.</p>");
                            }
                            else
                            {
                                var text = "<p>" + data + " joueurs n'ont pas encore voté.</p>";
                                $('#votes').html(text);
                            }
                            setTimeout(wait_votes, 1000);
                        }
                        else
                        {
                            load_consequences();
                        }                 
                    }
                });
            } 

            //Affiche les concequences et un boutton suivant permattant de passer a la decision suivante
            function load_consequences()
            {
                $.ajax(
                {
                    url : 'php/game/load_consequences.php',
                    dataType : 'json',                   
                    success:function(data)
                    {
                        var choix = "<p>La majorité a voté pour : "+ data.nom_choix +"</p>";
                        $("#title_decision").html(data.nom_conseq);
                        $("#text_decision").html(data.txt_conseq);
                        $('#text_decision').prepend(choix);  
                        $("#votes").html("");
                        setTimeout(function() 
                        {
                            $("#votes").html("<form id='form_next' onsubmit = 'return false;'><p><input type='submit' id='next' name='next' value='Suivant'/></p></form>");
                        }, 1000);
                        
                    }
                });           
            } 

            function change_active_decision()
            {
                $.ajax(
                {
                    url : 'php/game/change_active_decision.php'
                }); 
            }                                               
        });
    
    </script>
</html>
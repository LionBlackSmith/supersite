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

            <h3 id="titre_role"><?php echo $role_ideo['nom_role']; ?></h3>
            <article id="role">
                <p><?php echo $role_ideo['desc_role']; ?></p>
            </article>

            <h3 id="titre_ideo"><?php echo $role_ideo['nom_ideo']; ?></h3>
            <article id="ideo">
                <p> <?php echo $role_ideo['desc_ideo']; ?> </p>
            </article> 
        </div>

        <h2 id="title_decision">Decisions</h2>
        <div id='decision'>
            <div id="text_decision">
            Question
            </div>

            <div id="choices">
            Choix
            </div>       
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
            $("#titre_role_ideo").click(function(){
                $('#role_ideo').toggle();
            });

            $("#titre_role").click(function(){
                $("#role").toggle();
            }); 

            $("#titre_ideo").click(function(){
                $("#ideo").toggle();
            });   

            $(document).on('submit','#form_logout', function()
            {
                log_out();
            }); 

            function log_out()
            {
                $.ajax(
                {
                    url : 'php/common/log_out.php',
                    success:function(data)
                    {
                        console.log(data);
                        $("#main").load("php/home/display.php #new", function()
                        {                                    
                            $('#form_new_game').validate();
                            $('#form_join_game').validate();
                        });                        
                    }
                });
            } 

            function load_active_decision()
            {
                $.ajax(
                {
                    url : 'php/game/load_active_decision.php',
                    dataType : 'json',
                    success:function(data)
                    {
                                           
                    }
                });                
            }      
                         
        });
    
    </script>
</html>
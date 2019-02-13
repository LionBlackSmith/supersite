<?php session_start(); ?>

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

        <div id="main">        
               
        </div>

        <section>
            <article>
                <h3>Qu'est-ce que <strong>Jeu Energie</strong> ?</h3>

                <p>Description du jeu</p>
            </article>

            <article>
                <h3>Règles du jeu :</h3>

                <p>description des regles du jeu</p>
            </article>

            <article>
                <h3> But du Jeu : </h3>

                <p>description du but du jeu</p> 
            </article>    
        </section>

    </body>

    <script>
        
        $(document).ready(function() 
        {              
            var id_game = "<?php echo isset($_SESSION['id_game']); ?>";

            if(id_game == true) 
            {
                $("#main").load("php/home/display.php #lobby"); 
                update_players();
            }
            else
            {
                $("#main").load("php/home/display.php #new", function()
                {                                    
                    $('#form_new_game').validate();
                    $('#form_join_game').validate();
                });
            }

            $(document).on('submit','#form_new_game', function()
            {
                create_game();  
            }); 

            $(document).on('submit','#form_join_game', function()
            {
                var id_game = $("#id_game").val();
                var password = $("#password_join").val();

                $.ajax(
                {                      
                    url: 'php/home/check_login.php',
                    method : 'POST',
                    data :
                    {
                        id_game : id_game,
                        password : password
                    },
                    success: function(data)
                    {
                        switch (data) 
                        {
                            case '0':
                                alert("La partie n'existe pas.");
                                break;
                            case '1':
                                alert("Le mot de passe est incorrect.")
                                break;
                            case '2':
                                alert("La partie est complète.");
                                break;
                            case '3':
                                alert("La partie a déjà été lancée.");                                    
                                break;                            
                            case '4':
                                join_game();                                    
                                break;    
                            default:
                                alert('WHAT THE FU**???');
                                break;
                        }
                    }
                });
                
            }); 

            $(document).on('submit','#form_logout', function()
            {
                log_out();
            }); 

            $(document).on('submit','#form_launch_game', function()
            {
                console.log('click');  
                launch_game();
            }); 

            function create_game() 
            {
                var pseudo = $('#pseudo_new').val();
                var password = $('#password_new').val();

                $.ajax(
                {
                    url : 'php/home/create_game.php',
                    method : 'POST',
                    data : 
                    { 
                        pseudo : pseudo,
                        password : password                                            
                    },                    
                    success:function()
                    {
                        $("#main").load("php/home/display.php #lobby"); 
                        update_players();                        
                    }
                });               
            }

            function join_game() 
            {
                var pseudo = $('#pseudo_join').val();
                var password = $('#password_join').val();
                var id_game = $('#id_game').val();

                $.ajax(
                {
                    url : 'php/home/join_game.php',
                    method : 'POST',
                    data : 
                    { 
                        pseudo      : pseudo,
                        password    : password,
                        id_game     : id_game                                            
                    },                    
                    success:function()
                    {
                        $("#main").load("php/home/display.php #lobby"); 
                        update_players();                        
                    }
                });                
            }

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

            function update_players()
            {
                $.ajax(
                {
                    url : 'php/home/lobby_reload.php',
                    dataType : 'json',
                    success:function(data)
                    {
                        $("#joueurs").html(data.table);                        
                        $("#div_launch").html(data.button);
                        if (data.game_state != 'lobby') 
                        {
                            document.location.replace('game.php');
                        }                      
                    },
                    complete: function(data)
                    {
                        setTimeout(update_players, 1000);
                    }
                });
            }  

            function launch_game()
            {
                $.ajax(
                {
                    url : 'php/home/launch_game.php',
                    success:function()
                    {
                        document.location.replace('game.php');
                                                   
                    }
                });
            }             
        });
    
    </script>

</html>
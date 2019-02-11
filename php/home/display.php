<?php session_start(); ?>


<section id="new">
    <div>
    <form id="form_new_game" onsubmit = "return false;">
    <fieldset>
    <legend>Créer une partie :</legend>

    <p>
        <label for="pseudo">Ton pseudo</label> :<br> 
        <input type="text" name="pseudo" id="pseudo_new" placeholder="Pseudo" required> <br>             

        <label for="password">Mot de passe</label> :<br> 
        <input type="password" name="password" id="password_new" placeholder="Mot de passe" required> <br> 

        <input type="submit" id="new_game" name="new_game" value="Créer" />  
        
    </p>

    </fieldset>
    </form>
    </div>


    <div>
    <form id="form_join_game" onsubmit = "return false;">
    <fieldset>
    <legend>Rejoindre une partie :</legend>


        <label for="pseudo">Ton pseudo</label> :<br> 
        <input type="text" name="pseudo" id="pseudo_join" placeholder="Pseudo" required> <br>       

        <label for="id_game">N° de partie</label> :<br> 
        <input type="number" name="id_game" id="id_game" placeholder="N° de partie" required> <br> 

        <label for="password">Mot de passe</label> :<br> 
        <input type="password" name="password" id="password_join" placeholder="Mot de passe" required> <br> 

        <input type="submit" name="join_game" id="join_game" value="rejoindre">  

    </fieldset>
    </form> 
    </div>

</section>

<section id="lobby">
    <h2>Lobby</h2>
        <p>N° de partie : <?php echo $_SESSION['id_game'] ?></p>

        <table id="joueurs"></table>

        <form id="form_logout" onsubmit = "return false;">
            <p>
                <input type="submit" id="logout" name="logout" value="Quitter la partie" />
            </p>
        </form>

        <form action="fiche_perso.php" id="launch_game"></form>
</section>

<?php
    session_start();
        
    echo '<h1>Bienvenido a SuperBlackjack!!</h1>';
    
    echo '<div>
    <form action="jugar.php" method="get" enctype="text/plain" >
    <p>Introduce el número de jugadores: <input style="" type="number" min=1 max=5 name="ID"></p>
    <input type="submit" value="enviar">
    </form>
    </div>';
    
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

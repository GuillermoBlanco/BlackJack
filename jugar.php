<?php
    include_once './baraja.class.php';
    include_once './jugador.class.php';
    include_once './partida.class.php';
    
    session_start();
        
    $partida= new Partida();
    
   
    if (!isset($_SESSION['partida'])) {
        //Valores iniciales por defecto de la partida
        inicializarPartida($partida);
        $_SESSION['partida']=$partida;
    }
     else {
        //Rescata partida de la sesion
        $partida=$_SESSION['partida'];
    }
    
    if (isset($_GET['opcion'])){
        
        switch ($_GET['opcion']) {
            case "Robar":
                $partida->decPasa();
                repartirJugador($partida, 1);
                if ($partida->puntos($partida->jugadores[$partida->getTurno()]->getCartas())>21){
                   $partida->jugadores[$partida->getTurno()]->planta();
                    $partida->incPlanta();
                   avanzar($partida);
                }
                break;
            
            case "Pasar":
                
                if (($partida->getParados())==count($partida->jugadores)-1){

                }
                $partida->incPasa();
                avanzar($partida);
                break;
                
            case "Plantar":
                $partida->jugadores[$partida->getTurno()]->planta();
                $partida->incPlanta();
                avanzar($partida);
                break;

            default:
                break;
        }

        $_SESSION['partida']=$partida;
    }

    //var_dump($partida);

//    repartirJugador($partida, 1, 4);

    
    mostrarPartida($partida);
//    var_dump($partida);

    function inicializarPartida($partida){
        
        $partida->setTurno(0);
        $partida->setRonda(0);
        
        $num_jug=4;
        if (isset($_GET['ID'])){
            $num_jug= $_GET['ID'];

        }
        
        for ($x=0; $x<$num_jug; $x++){
            $partida->addJugador();
        }
        
        $baraja= new Baraja();
        $baraja->barajar();
        
        $partida->setBaraja($baraja);
        
        repartir($partida, 2);
        
    }

    function repartir ($partida,$num_cartas) {
        
        if (count($partida->jugadores)>0){
            
            for ($x=0; $x<count($partida->jugadores); $x++){
            
//            $partida->jugadores[$x]->setCartas(array_splice($partida->getBaraja()->cartas, 0, $num_cartas));
            $partida->jugadores[$x]->addCartas(array_splice($partida->getBaraja()->cartas, 0, $num_cartas));
            
            }
        }
        
    }
    
    function repartirJugador ($partida,$num_cartas) {
        
        if (count($partida->getbaraja())>0){
//            $partida->jugadores[$partida->getTurno()]->setCartas(array_splice($partida->getBaraja()->cartas, 0, $num_cartas));
            $partida->jugadores[$partida->getTurno()]->addCartas(array_splice($partida->getBaraja()->cartas, 0, $num_cartas));
        }
    }
    
    function avanzar($partida){
            if ($partida->getTurno()<(count($partida->jugadores)-1)) $partida->incTurno();
            else {
                $partida->setTurno(0);
                $partida->incRonda();
            }
            if ($partida->jugadores[$partida->getTurno()]->isPlante()) avanzar ($partida);
    }
    
    function mostrarPartida($partida){

        echo '<h1 style="font-size:3em; margin-bottom:-20px">SuperBlackjack </h1>';
        echo '<div style="float:left">';
        echo '<h1 style="color:red">Turno jugador: '.($partida->getTurno()+1).'</h1>';
        echo '<h2 style="">Ronda: '.($partida->getRonda()+1).'</h1>';
        echo '</div>';
        
        
        for ($index = count($partida->jugadores)-1; $index >= 0; $index--) {

            echo '<div style="float:right; padding-top:50px; margin:30px;">';

            echo '<h2 style="text-align:right">Jugador '.($index+1).' </h2>';

            $partida->pintaCartas($partida->jugadores[$index]->getCartas());
            
            if ($index==$partida->getTurno() && $partida->getParados()!=count($partida->jugadores) ){
                echo '<div style="float:bottom; padding-top:3px; margin:3px;">';
                echo '<FORM METHOD="get" ACTION="jugar.php">
                    <INPUT TYPE="radio" name="opcion" VALUE="Robar">Robar</br>
                    <INPUT TYPE="radio" name="opcion" VALUE="Pasar">Pasar</br>
                    <INPUT TYPE="radio" name="opcion" VALUE="Plantar">Plantarse</br>
                    <INPUT TYPE="submit" VALUE="OK">
                    </FORM>';

                echo '</div>';
            }
            
            echo '</div>';
        }
    }
 
//        function pasaTurno() {
//            $partida['carta']='';
//            if ($partida['turno']<($partida['num_jug']-1)) $partida['turno']++;
//            else $partida['turno']=0;
//        }
    
?>

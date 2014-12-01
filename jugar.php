<?php
    include_once './baraja.class.php';
    include_once './jugador.class.php';
    include_once './partida.class.php';
    
    session_start();
        
    $partida= new Partida();
    
   
    if (!isset($_SESSION['partida'])) {
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
        
        repartir($partida, 3);
        $_SESSION['partida']=$partida;
    }
     else {
        $partida=$_SESSION['partida'];
    }
    
    if (isset($_GET['turno'])){
            $partida['carta']='';
            if ($partida->getTurno()<(count($partida->jugadores)-1)) $partida->incTurno();
            else {
                $partida->setTurno(0);
                $partida->incRonda();
            }
            $_SESSION['partida']=$partida;
    }
    if (isset($_GET['num_cartas'])) {
        repartirJugador($partida, $_GET['num_cartas']);
    }
    
    if (isset($_GET['palo']) && isset($_GET['numero'])){
        $carta=array('palo'=>$_GET['palo'],'numero'=>$_GET['numero'],'valor'=>$_GET['valor']);
        lanzar($partida, $carta);
    }
    
    //var_dump($partida);

//    repartirJugador($partida, 1, 4);

    
    mostrarPartida($partida);

    function repartir ($partida,$num_cartas) {
        
        if (count($partida->jugadores)>0){
            
            for ($x=0; $x<count($partida->jugadores); $x++){
            
            $partida->jugadores[$x]->setCartas(array_splice($partida->getBaraja()->cartas, 0, $num_cartas));
            
            }
            
            var_dump($partida);
        }        
    }
    function repartirJugador ($partida,$num_cartas) {
        
        if (count($partida->getbaraja())>0){
            $partida->jugadores[$partida->getTurno()]->setCartas(array_splice($partidagetBaraja()->cartas, 0, $num_cartas));
        }
    }
    
    function mostrarPartida($partida){

        echo '<h1 style="font-size:3em; margin-bottom:-20px">SuperBlackjack </h1>';
        echo '<div style="float:left">';
        echo '<h1 style="color:red">Turno jugador: '.($partida->getTurno()+1).'</h1>';
        echo '<h2 style="">Ronda: '.($partida->getRonda()+1).'</h1>';
        echo '</div>';
        
        for ($index = 0; $index < count($partida->jugadores); $index++) {

            echo '<div style="float:right; padding-top:50px">';

            echo '<h2 style="text-align:right">Jugador '.($index+1).' </h2>';

            $partida->pintaCartas($partida->jugadores[$index]->getCartas());

        }
    }

    function lanzar (&$partida, $carta) {
        $jugador=$partida['turno'];
        if ($carta['numero']==5){
            array_push ($partida['tablero'][$carta['palo']], $carta);
            for ($x=0; $x<count($partida[$jugador]); $x++) {
                                if (($partida[$jugador][$x]['numero']==$carta['numero'])&&
                                    ($partida[$jugador][$x]['palo']==$carta['palo'])){
                                    unset ($partida[$jugador][$x]);
                                    $partida[$jugador] = array_values($partida[$jugador]);
                                    break;
                                    }
                            }
//            pasaTurno();
            $partida['carta']='';
            if ($partida['turno']<($partida['num_jug']-1)) $partida['turno']++;
            else {
                $partida['turno']=0;
                $partida['ronda']++;
            }
            
        }
        else {
            
            $palo=$carta['palo'];

            if (!empty($partida['tablero'][$palo])){
                $cartas_mesa1=$partida['tablero'][$palo];
                $cartas_mesa2=$partida['tablero'][$palo];
                $primera=array_shift($cartas_mesa1);
                $ultima=array_pop($cartas_mesa2);
//                print_r($primera);
//                print_r($ultima);

                if (($carta['numero'])==$primera['numero']-1) {
                    //Agrego elemento al final
                    array_unshift ($partida['tablero'][$palo], $carta);
                    //Eliminar elemento de mano del jugador ¿????
                    for ($x=0; $x<count($partida[$jugador]); $x++) {
                        if (($partida[$jugador][$x]['numero']==$carta['numero'])&&
                            ($partida[$jugador][$x]['palo']==$carta['palo'])){
                            unset ($partida[$jugador][$x]);
                            $partida[$jugador] = array_values($partida[$jugador]);
                            break;
                            }
                        }
                    
//                    pasaTurno();
                    $partida['carta']='';
                    if ($partida['turno']<($partida['num_jug']-1)) $partida['turno']++;
                    else {
                        $partida['turno']=0;
                        $partida['ronda']++;
                    }
                }
                elseif (($carta['numero'])==$ultima['numero']+1) {

                    //Agrego elemento al principio
                    array_push ($partida['tablero'][$palo], $carta);
                    //Eliminar elemento de mano del jugador ¿????
                    for ($x=0; $x<count($partida[$jugador]); $x++) {
                        if (($partida[$jugador][$x]['numero']==$carta['numero'])&&
                            ($partida[$jugador][$x]['palo']==$carta['palo'])) {
                            unset ($partida[$jugador][$x]);
                            $partida[$jugador] = array_values($partida[$jugador]);
                            break;
                            }
                    }
//                    pasaTurno();
                    $partida['carta']='';
                    if ($partida['turno']<($partida['num_jug']-1)) 
                        $partida['turno']++;
                    else {
                        $partida['turno']=0;
                        $partida['ronda']++;
                    }
               }
        
            }
        }
        $_SESSION['partida']=$partida;
//        print_r($partida['tablero']);

}   
//        function pasaTurno() {
//            $partida['carta']='';
//            if ($partida['turno']<($partida['num_jug']-1)) $partida['turno']++;
//            else $partida['turno']=0;
//        }
    
?>

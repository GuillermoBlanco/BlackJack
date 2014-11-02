<?php
    session_start();
    
    include './baraja.class.php';
    include './jugador.class.php';
    include './partida.class.php';
    
    $partida= new Partida();
    
   
    if (!isset($_SESSION['partida'])) {
        $partida->setTurno(0);
        $partida->setRonda(0);
        
        $num_jug=4;
        if (isset($_GET['ID'])){
            $num_jug= $_GET['ID'];

        }
        
        for ($x=0; $x<$num_jug; $x++){
            $partida[$x]= new Jugador();
        }
        
        $baraja= new Baraja();
        $baraja->barajar;
        
        $partida->setBaraja($baraja);
        
        repartir($partida, 5);
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
    
    print_r($partida);

//    repartirJugador($partida, 1, 4);
    
    mostrarbaraja($partida);

    function repartir ($partida,$num_cartas) {
        if (count($partida->baraja)>0){
            
            for ($x=0; $x<count($partida->jugadores); $x++){ 
            $partida->jugadores[$x]->setCartas(array_splice($partida->baraja, 0, $num_cartas));
            }
        }        
    }
    function repartirJugador ($partida,$num_cartas) {
        
        if (count($partida['baraja'])>0){
            $partida->jugadores[$partida->getTurno()]->setCartas(array_splice($partida['baraja'], 0, $num_cartas));
        }
    }
    
    function puntos ($mano){
        $suma=0;
        foreach ($mano as $carta) {
            $suma=$suma+($carta->getValor());
        }
        
        return $suma;
    }
    
    function pintaCartas ($mano){
        $suma=0;
        foreach ($mano as $carta) {
            echo '<img src="naipes/'.$carta['palo'].'-'.$carta['numero'].'.gif"'.' WIDTH="40" HEIGHT="64">';
            $suma=$suma+$carta['valor'];
            }
        echo '<p>Puntos : '.$suma.'</p>';
        echo '</br>';
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

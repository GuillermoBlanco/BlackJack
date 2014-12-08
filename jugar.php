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
//                $partida->decPasa();
                repartirJugador($partida->jugadores[$partida->getTurno()],$partida->getbaraja(), 1);
                if ($partida->puntos($partida->jugadores[$partida->getTurno()]->getCartas())>21){
                   $partida->jugadores[$partida->getTurno()]->pasa();
                    $partida->incPasa();
                   avanzar($partida);
                }
                elseif ($partida->puntos($partida->jugadores[$partida->getTurno()]->getCartas())==21){
                    $partida->jugadores[$partida->getTurno()]->planta();
                    $partida->incPlanta();
                    avanzar($partida);
                    if($partida->getTurno()<  count($partida->jugadores)){
                         for ($i=$partida->getTurno(); ($partida->jugadores[$i]->isPasa()|| $partida->jugadores[$i]->isPlante());  avanzar($partida) );
                    }
                }
                break;
            
            case "Pasar":
                $partida->incPasa();
                $partida->jugadores[$partida->getTurno()]->pasa();
                avanzar($partida);
                if($partida->getTurno()<  count($partida->jugadores)){
                    for ($i=$partida->getTurno(); ($partida->jugadores[$i]->isPasa()|| $partida->jugadores[$i]->isPlante());  avanzar($partida) );
                }
                break;
                
            case "Plantar":
                $partida->jugadores[$partida->getTurno()]->planta();
                $partida->incPlanta();
                avanzar($partida);
                if($partida->getTurno()<  count($partida->jugadores)){
                    for ($i=$partida->getTurno(); ($partida->jugadores[$i]->isPasa()|| $partida->jugadores[$i]->isPlante());  avanzar($partida) );
                }
                break;
            
            case "Croupier":
                //Avanzar croupier
                if (resolverCroupier($partida)){
                    $partida->fin=true;
                }
                break;

            default:
                break;
        }

        $_SESSION['partida']=$partida;
    }

    mostrarPartida($partida);
    
    if ($partida->getParados()>=count($partida->jugadores)){
        if ( !$partida->fin){
        sleep(1);
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'jugar.php?opcion=Croupier';
        header("Location: http://$host$uri/$extra");
        exit;
        }
        else{
            calcularGanador($partida);
        }
    }

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
        repartirJugador($partida->croupier,$partida->getbaraja(), 2);
        if ($partida->puntos($partida->croupier->getCartas())>=21){
           $partida->croupier->planta();
        }
        if ($partida->puntos($partida->jugadores[0]->getCartas())>=21){
            avanzar($partida);
        }
        
    }

    function repartir ($partida,$num_cartas) {
        
        if (count($partida->jugadores)>0){
            
            for ($x=0; $x<count($partida->jugadores); $x++){
            
//            $partida->jugadores[$x]->setCartas(array_splice($partida->getBaraja()->cartas, 0, $num_cartas));
            $partida->jugadores[$x]->addCartas(array_splice($partida->getBaraja()->cartas, 0, $num_cartas));
            if ($partida->puntos($partida->jugadores[$x]->getCartas())>=21){
                   $partida->jugadores[$x]->planta();
                    $partida->incPlanta();
                }
            }
        }
        
    }
    
    function repartirJugador ($jugador,$baraja,$num_cartas) {
        
        if (count($baraja)>0){
//            $partida->jugadores[$partida->getTurno()]->setCartas(array_splice($partida->getBaraja()->cartas, 0, $num_cartas));
            $jugador->addCartas(array_splice($baraja->cartas, 0, $num_cartas));
        }
        
    }
    
    function avanzar($partida){
            if ($partida->getTurno()<(count($partida->jugadores)-1)) {
                $partida->incTurno();
                
                if ( $partida->jugadores[$partida->getTurno()]->isPlante() || 
                    $partida->jugadores[$partida->getTurno()]->isPasa()) 
                    avanzar ($partida);
                
            }
            else {
                //Turno Croupier
                $partida->incTurno();
            }

//            print_r($partida->getTurno());
//            print_r($partida->jugadores[$partida->getTurno()]->isPlante());
//            print_r($partida->jugadores[$partida->getTurno()]->isPasa());
            
    }
    
    function mostrarPartida($partida){
        
        echo '<h1 style="font-size:3em; margin-bottom:-20px">SuperBlackjack </h1>';
        echo '<div style="float:left">';
        if ($partida->getParados()<count($partida->jugadores)){
        echo '<h1 style="color:red">Turno jugador: '.($partida->getTurno()+1).'</h1>';
        }
        else echo '<h1 style="color:red">Fin de la partida</h1>';
        
        echo '<div style="float:left; padding:10px; margin:30px; margin-bottom:50px; border: solid black;">';
        echo '<h2 style="text-align:right; "><span>Croupier</span></h2>';
        $partida->pintaCartas($partida->croupier->getCartas());
        echo '</div>';
        
        for ($index = count($partida->jugadores)-1; $index >= 0; $index--) {

            echo '<div style="float:right; padding:10px; margin:30px;">';

            echo '<h2 style="text-align:right">Jugador '.($index+1).' </h2>';

            if ($partida->pintaCartas($partida->jugadores[$index]->getCartas())){
                
                if ($index==$partida->getTurno() && $partida->getParados()<count($partida->jugadores) ){
                    echo '<div style="float:bottom; padding-top:3px; margin:3px;">';
                    echo '<FORM METHOD="get" ACTION="jugar.php">
                        <INPUT TYPE="radio" name="opcion" VALUE="Robar">Robar</br>
                        <INPUT TYPE="radio" name="opcion" VALUE="Plantar">Plantarse</br>
                        <INPUT TYPE="radio" name="opcion" VALUE="Pasar">Pasar</br>
                        
                        <INPUT TYPE="submit" VALUE="OK">
                        </FORM>';

                    echo '</div>';
                }
            }
            else {
//                avanzar($partida);
            }
            
            
            echo '</div>';
        }
    }
 
    function resolverCroupier($partida) {
        if ($partida->puntos( $partida->croupier->getCartas() )<21 && count($partida->croupier->getCartas())<17 ){
//            sleep(2);
            $gana=true;
            foreach ($partida->jugadores as $jugador) {
                if (!$jugador->isPasa()){
                    if ($partida->puntos( $partida->croupier->getCartas() )>=$partida->puntos( $jugador->getCartas() ));
                    else {
                      repartirJugador($partida->croupier,$partida->getbaraja(), 1);
                      $gana=false;
                    }  
                }

            }
            return $gana;
        }  else {
            $partida->fin=true;
            return true;
        }
    }
    
    function calcularGanador($partida){
        $juegan=false;
        $max_jugadores=NULL;
        
        for ($i=0; $i<count($partida->jugadores); $i++){
            if (!$partida->jugadores[$i]->isPasa()){
                if ($max_jugadores==NULL) $max_jugadores=$i;
                if ($partida->puntos( $partida->jugadores[$i]->getCartas() )>=$partida->puntos( $partida->jugadores[$max_jugadores]->getCartas() )){
                       $max_jugadores=$i;
                       print_r($partida->jugadores[$i]->isPasa());
                }
                $juegan=true;
            }
        }

//        print_r($max_jugadores);
        
        if ($juegan){
            if ($partida->puntos( $partida->jugadores[$max_jugadores]->getCartas() )<=$partida->puntos( $partida->croupier->getCartas() ) 
                && $partida->puntos( $partida->croupier->getCartas())<=21){
                echo '<p>El ganador es.... el Croupier!!!!</p>';
            }
            else {
                echo '<p>El ganador es.... el Jugador '.($max_jugadores+1).' !!!!</p>';
            }
        }
        else{
            echo "<p>Nadie apuesta :( La banca gana</p>";
        }
        
//        var_dump($partida);

    }
    
?>

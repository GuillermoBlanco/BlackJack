            <?php
            
            include './carta.class.php';
            
            class Baraja{
                
            public $cartas = array();
            
            private $palos = array('corazones','picas','treboles','diamantes');
            private $numeros=array(1,2,3,4,5,6,7,8,9,10,11,12,13);
            private $valor=array(11,2,3,4,5,6,7,8,9,10,10,10,10);
            
            
            public function __constructor() {
               
                foreach ($this->palos as $palo) {
                    for ($x=0;$x<count($this->numeros);$x++){
//                        $carta=array('palo'=>$palo,'numero'=>$numeros[$x], 'valor'=>$valor[$x]);                        
                        $carta= new Carta($palo,  $this->numeros[$x],  $this->valor[$x]);                        
                        array_push($this->cartas, $carta);
                    }
                }
            }

            function mostrarbaraja (){
//                echo '<h1 style="font-size:3em; margin-bottom:-20px">SuperBlackjack </h1>';
//                echo '<div style="float:left">';
//                echo '<h1 style="color:red">Turno jugador: '.($partida['turno']+1).'</h1>';
//                echo '<h2 style="">Ronda: '.($partida['ronda']+1).'</h1>';
//                echo '</div>';

                echo '<div style="float:right; padding-top:50px">';

                echo '<h2 style="text-align:right">Jugador '.($x+1).' </h2>';

                    foreach ($this->cartas as $carta){
                        $carta->mostrarCarta();
                    }
                echo '</br>';
            }
            
            function barajar () {
                shuffle($this->cartas);
            }
}
?>
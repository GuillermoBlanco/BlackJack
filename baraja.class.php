            <?php
            
            include_once './carta.class.php';
            
            class Baraja{
                
            public $cartas = array();
            
            public $palos = array('corazones','picas','treboles','diamantes');
            public $numeros=array(1,2,3,4,5,6,7,8,9,10,11,12,13);
            public $valor=array(11,2,3,4,5,6,7,8,9,10,10,10,10);
            
            
            public function __construct() {
               
                foreach ($this->palos as $palo) {
                    for ($x=0;$x<count($this->numeros);$x++){
//                        $carta=array('palo'=>$palo,'numero'=>$numeros[$x], 'valor'=>$valor[$x]);                        
                        $carta= new Carta($palo,  $this->numeros[$x],  $this->valor[$x]);                        
                        array_push($this->cartas, $carta);
                    }
                }
            }
            function barajar () {
                shuffle($this->cartas);
            }
}
?>
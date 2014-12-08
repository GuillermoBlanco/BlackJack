<?php
    include_once './baraja.class.php';
    include_once './jugador.class.php';
    
    class Partida{
        
        private $baraja;
        public $jugadores= array();
        public $croupier;
        
        public $fin=false;
        private $ronda;
        private $turno;
        private $pasa=0;
        private $planta=0;
                
        function __construct() {
            $this->croupier=new Jugador();
            
        }
        
        public function getBaraja() {
            return $this->baraja;
        }

        public function setBaraja($baraja) {
            $this->baraja = $baraja;
        }

        public function getRonda() {
            return $this->ronda;
        }

        public function setRonda($ronda) {
            $this->ronda = $ronda;
        }
        
        public function incRonda(){
            $this->ronda++;
        }

        public function getTurno() {
            return $this->turno;
        }

        public function setTurno($turno) {
            $this->turno = $turno;
        }
        
        public function incTurno(){
            $this->turno++;
        }
        
        public function getParados(){
            return $this->pasa+$this->planta;
        }
        
        public function incPasa(){
            $this->pasa++;
        }
        
        public function incPlanta(){
            $this->planta++;
        }
        
        public function decPasa(){
            $this->pasa--;
        }
        
        public function getJugador($jug) {
            if ($this->jugadores[$jug]){
                return $this->jugadores[$jug];}
            else return 0;
        }

        public function addJugador() {
            array_push($this->jugadores , new Jugador());
        }
        
        function puntos($mano){
            $suma=0;
            foreach ($mano as $carta) {
                $suma=$suma+($carta->getValor());
            }

            return $suma;
        }

    //        $message = "wrong answer";
    //        echo "<script type='text/javascript'>alert('$message');</script>";
        
        public function pintaCartas($mano){
            $fuera=false;
            foreach ($mano as $carta){
                        $carta->mostrarCarta();
                    }
            $puntos=$this->puntos($mano);
                echo '</br>';
                if ($puntos>=21){  
                    echo '<p style="color:red">';
                    $fuera=false;
                }
                else{  
                    echo '<p>';
                    $fuera =true;
                }
                
                echo 'Puntos : '.$puntos.'</p>';
                echo '</br>';
            return $fuera;
        }
        
        public function escondeCartas($mano){
            foreach ($mano as $carta){
                $carta->esconderCarta();
            }
        }
    }
?>

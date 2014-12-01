<?php
    include_once './baraja.class.php';
    include_once './jugador.class.php';
    
    class Partida{
        
        private $baraja;
        public $jugadores= array();
        
        private $ronda;
        private $turno;
        
        function __construct() {
            
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
            
            foreach ($mano as $carta){
                        $carta->mostrarCarta();
                    }
                echo '</br>';
                echo '<p>Puntos : '.$this->puntos($mano).'</p>';
                echo '</br>';
                
            }
    }
?>

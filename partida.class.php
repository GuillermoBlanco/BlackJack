<?php
    include './baraja.class.php';
    include './jugador.class.php';
    
    class Partida{
        
        private $baraja;
        public $jugadores=array();
        
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
        
    }
?>

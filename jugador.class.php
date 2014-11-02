<?php
    class Jugador {
       
        private $nombre;
        private $cartas=array();
        
        function __construct() {
           
        }
        
        public function getNombre() {
            return $this->nombre;
        }

        public function setNombre($nombre) {
            $this->nombre = $nombre;
        }

        public function getCartas() {
            return $this->cartas;
        }

        public function setCartas($cartas) {
            $this->cartas = $cartas;
        }
        
    }
    
?>

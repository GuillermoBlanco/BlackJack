<?php
    class Jugador {
       
        private $nombre;
        private $cartas=array();
        private $plante=false;
        
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
//        array_push($this->cartas, $cartas);
        }
        
        public function addCartas($cartas){
            $this->cartas=array_merge($this->cartas, $cartas);
        }

        public function planta(){
            $this->plante=true;
        }
        public function isPlante(){
            return $this->plante;
        }
                
        
    }
    
?>

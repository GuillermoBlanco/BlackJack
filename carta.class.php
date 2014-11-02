<?php

    class Carta {
        public $palo;
        public $numero;
        public $valor;

        public function mostrarCarta (){
            echo '<img src="naipes/'.$this->palo.'-'.$this->numero.'.gif"'.' WIDTH="40" HEIGHT="64">';
        }

        public function __construct($palo, $numero, $valor) {
            $this->palo=$palo;
            $this->numero=$numero;
            $this->valor=$valor;
        }
        
        public function getPalo() {
            return $this->palo;
        }

        public function setPalo($palo) {
            $this->palo = $palo;
        }

        public function getNumero() {
            return $this->numero;
        }

        public function setNumero($numero) {
            $this->numero = $numero;
        }

        public function getValor() {
            return $this->valor;
        }

        public function setValor($valor) {
            $this->valor = $valor;
        }


    }
    
    

?>

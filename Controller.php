<?php

/**
 * Description of Controller
 *
 * @author komputer
 */
class Controller {
    
    public function rotate($id, $where) {
        return '<unit id="' . $id . '"><go rotate="' . $where . '" /></unit>';
    }
    
    public function action($id, $action ) {
        return '<unit id="' . $id . '"><go action="' . $action . '" /></unit>';
    }
    
    public function move($id, $direction ) {
        return '<unit id="' . $id . '"><go direction="' . $direction . '" /></unit>';
    }
    

}

//  Dla parametru direction możemy użyć jednego z 6 kierunków: (NE,E,SE,SW,W,NW).
//  Parametr rotate posiada 2 dozwolone wartości: "rotateRight" (obrót w prawo) "rotateLeft" (obrót w lewo).
//  Parametr action posiada możliwe akcje: "drag" [podnieś], "drop" [upuść], "heal" [lecz], "attack" [atakuj], "hold" [nie rób nic]. 
//  <unit id="IDJEDNOSTKI"><go [action=""] [rotate=""] [direction=""] /></unit>


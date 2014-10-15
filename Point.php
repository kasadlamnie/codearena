<?php

class Point {
    private $X = 0;
    private $Y = 0;
    private $neighborSpots = array();
    
    public function __construct( $x = 0, $y = 0) {
        $this->X = $x;
        $this->Y = $y;
        $this->generateNeighbors( new Point( $x, $y ) );
    }
    
    public function getPoint() {
        return $this;
    }
    
    public function getX() {
        return $this->X;
    }
    public function getY() {
        return $this->Y;
    }
    
    public function printPoint() {
        echo "<br/>PUNKT:"; var_dump( $this->X, $this->Y ); echo "<hr/>";
    }
    
    public function shiftPoint( $x, $y ) {
        return new Point( $this->X + $x, $this->Y + $y );
    }
        
    // return Point - max X and max Y from two points: A (this) and B ($point)
    public function returnMaxValues ( Point $point ) {
        $tmpX = $this->X;
        $tmpY = $this->Y;
        
        if( $this->X < $point->X ) {
            $tmpX = $point->X;
        }
        if( $this->Y < $point->Y ) {
            $tmpY = $point->Y;
        }
        
        return new Point( $tmpX, $tmpY );
    }
    
    private function generateNeighbors( Point $point ) {
        $this->neighborSpots['C']   = $point;
        $this->neighborSpots['NE']  = $point->shiftPoint( 1, -1 );
        $this->neighborSpots['E']   = $point->shiftPoint( 1, 0 );
        $this->neighborSpots['SE']  = $point->shiftPoint( 1, 1 );
        $this->neighborSpots['NW']  = $point->shiftPoint( -1, -1 );
        $this->neighborSpots['W']   = $point->shiftPoint( -1, 0 );
        $this->neighborSpots['SW']  = $point->shiftPoint( -1, 1 );
    }
    
    public function getNeighbors() {
        return $this->neighborSpots;
    }
}

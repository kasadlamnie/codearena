<?php

class Point {
    private $X = 0;
    private $Y = 0;
    
    public function __construct( $x = 0, $y = 0) {
        $this->X = $x;
        $this->Y = $y;
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
    
    public function generateNeighbors( ) {
        $neighborSpots['C']   = $this;
        $neighborSpots['NE']  = $this->shiftPoint( 1, -1 );
        $neighborSpots['E']   = $this->shiftPoint( 1, 0 );
        $neighborSpots['SE']  = $this->shiftPoint( 1, 1 );
        $neighborSpots['NW']  = $this->shiftPoint( -1, -1 );
        $neighborSpots['W']   = $this->shiftPoint( -1, 0 );
        $neighborSpots['SW']  = $this->shiftPoint( -1, 1 );
        
        return $neighborSpots;
    }
}

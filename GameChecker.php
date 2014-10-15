<?php

require_once 'Point.php';

class GameChecker {

    private $baseLocation;
    private $mapSize;
    private $allObjects = array( 'base', 'stone', 'diamond' );
    private $allBackgrounds = array( 'green', 'orange', 'blue', 'red', 'stone', 'black' );
    private $allowBackgroundsUnit = array( 'green', 'orange', 'blue', 'red' );
    private $allowBackgroundsObject = array( 'black', 'red', 'blue', 'green' );
    
    private $spotsToCheck = array();
    private $spotsChecked = array();
    
    public function __construct() {
        $this->baseLocation = new Point();
        $this->mapSize = new Point();
    }
    
    public function getBaseLocation() {
        return $this->baseLocation;
    }
    public function setBaseLocation( Point $point ) {
        $this->baseLocation = $point;
    }
    
    public function getMapSize() {
        return $this->mapSize;
    }
    public function setMapSize( Point $point ) {
        $this->mapSize = $point;
    }
    
    public function getAllObjects() {
        return $this->allObjects;
    }
    public function getallBackgrounds() {
        return $this->allBackgrounds;
    }
    public function getAllowBackgroundsUnit() {
        return $this->allowBackgroundsUnit;
    }
    public function getAllowBackgroundsObject() {
        return $this->allowBackgroundsObject;
    }
    
    public function transformBaseLocation( $direction, Point $unit ) {
        $baseLocation = $unit->generateNeighbors();
        return $baseLocation[$direction];
    }
    
    
    public function addPointsToCheck( Point $point ) {
        $this->spotsToCheck []= $point ;
        echo "Dodaje punkt " . $point->getX() . " - " . $point->getY() . "<br/>";
    }
    
    public function checkIfSpotToCheck( Point $point ) {
        if( in_array( $point, $this->spotsToCheck ) ) {
            return true;
        }
        
        return false;
    }
    
    public function checkIfSpotChecked( Point $point ) {
        if( in_array( $point, $this->spotsChecked ) ) {
            return true;
        }
        
        return false;
    }
    
    public function markSpotAsChecked( Point $point ) {
        print_r( count($this->spotsToCheck));echo" --> ";
        $spotKey = array_search( $point, $this->spotsToCheck );
        unset( $this->spotsToCheck[$spotKey] );
        array_push( $this->spotsChecked, $point );
        print_r(count($this->spotsToCheck));echo"<br/>";
    }
}

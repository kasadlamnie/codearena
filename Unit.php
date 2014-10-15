<?php

require_once 'Point.php';

class Unit {
    
    private $uId = 0;
    private $uLocation;
    private $uHp = 0;
    private $uStatus = 0;
    private $uAction = 0;
    private $uOrientation = 0;
    private $uLevel = 0;
    private $uPlayer = 0;
    private $uSees = array(
        "NE" => array(),
        "E" => array(),
        "S" => array(),
        "SW" => array(),
        "W" => array(),
        "NW" => array()
    );
    
    public function __construct() {
        $this->uLocation = new Point();
    }
    
    public function getuId() {
        return $this->uId;
    }
    public function setuId( $uId ) {
        $this->uId = $uId;
    }
    
    public function getuLocation() {
        return $this->uLocation;
    }
    public function setuLocation( Point $point ) {
        $this->uLocation = $point;
    }
    
    public function getuHp() {
        return $this->uHp;
    }
    public function setuHp( $uHp ) {
        $this->uHp = (int)$uHp;
    }
    
    public function getuStatus() {
        return $this->uStatus;
    }
    public function setuStatus( $uStatus ) {
        $this->uStatus = $uStatus;
    }
    
    public function getuAction() {
        return $this->uAction;
    }
    public function setuAction( $uAction ) {
        $this->uAction = $uAction;
    }
    
    public function getuOrientation() {
        return $this->uOrientation;
    }
    public function setuOrientation( $uOrientation ) {
        $this->uOrientation = $uOrientation;
    }
    
    public function getuLevel() {
        return $this->uLevel;
    }
    public function setuLevel( $uLevel ) {
        $this->uLevel = $uLevel;
    }
    
    public function getuPlayer() {
        return $this->uPlayer;
    }
    public function setuPlayer( $uPlayer ) {
        $this->uPlayer = $uPlayer;
    }
    
    public function getuSees() {
        return $this->uSees;
    }
    public function setuSees( $uSees ) {
        $this->uSees = $uSees;
    }
}

<?php
/**
 * Description of Connector
 *
 * @author komputer
 */
class Connector {
    const HOST = 'codearena.pl';
    const PORT = 7654;
    const USERID = "635";
    const HASHID = "dd5130f12020d493872e7707f5ef9657";
    
    private $socket = false;
    private $package = "";
    
    public function __construct() {
        $this->package = "<connect userid='" . self::USERID . "' hashid='" . self::HASHID . "' />";
        $this->socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );

        if( socket_connect( $this->socket, self::HOST, self::PORT ) ) {
            return socket_send( $this->socket, $this->package, strlen($this->package), 0);
        }
        return false;
    }
    
    public function getSocket() {
        return $this->socket;
    }
    
    
}

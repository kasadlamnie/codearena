<?php

require_once 'Unit.php';
require_once 'Point.php';

/**
 * Description of ResponseAnalizer
 *
 * @author komputer
 */
class ResponseAnalizer {

    public function checkGameStatus( SimpleXMLElement $status ) {
        
        $sleepTime = 0;
        
        switch ( $status ) {
            case 'WRONG_CREDENTIALS':
                echo("Bad Uid or Hash");
                return false;
            case 'BUSY':
                echo "Serwer zajety spie 30 sekund: ";
                $sleepTime = 30;
                break;
            case 'GAME_READY':
                return true;
            case 'WAITING_FOR_PLAYER':
                echo "Czekaj na przeciwnika spie 2 sekundy: ";
                $sleepTime = 2;
                break;
            case 'CHECK_WWW':
                echo "sprawdź ustawienia w konsoli na stronie www (np. gracz nie zaakceptował regulaminu lub jego konto jest nieaktualne, wyjaśnienie, o ile dostępne, pojawi się w polu DESCRIPTION) - skrypt poinformuje jeśli coś się zmieni.";
                return false;
        }
        
        return $sleepTime;
    }
    
    public function checkResponseTime ( SimpleXMLElement $response ) {
        return $response->general[0]->timeSec[0];
    }
    
    public function checkResponseRoundNumber ( SimpleXMLElement $response ) {
        return (int)$response->general[0]->roundNum[0];
    }
    
    public function checkResponseAmountOfPoints ( SimpleXMLElement $response ) {
        return (int)$response->general[0]->amountOfPoints[0];
    }

    private function checkResponseSees ( SimpleXMLElement $sees ) {
        $uSees = null;
        
        for( $i = 0; $i < count( $sees->unit->sees ); $i++ ) {
//            echo "aaa: " . $sees->unit->sees[$i]['direction'] . " | "; print_r( $sees->unit->sees[$i] ); echo '<br/>';
            $k = strval( $sees->unit->sees[$i]['direction'] );
            
            foreach ( $sees->unit->sees[$i] as $key => $value) {
//                echo "bbb ".$key." - ".$value."<br/>";
                $uSees[$k][strval( $key )] = strval( $value );
            }
            
        }
        
        return $uSees;
    }

    public function checkResponseUnit ( SimpleXMLElement $response ) {
        // <game>
        // <general>
        //      <timeSec>0.040080070495605</timeSec>
        //      <roundNum>2</roundNum>
        //      <amountOfPoints>0</amountOfPoints>
        // </general>
        // <units>
        //      <unit id="8" x="5" y="4" hp="99" status="" action="" orientation="E" level="0" player="1">
        //      <sees direction="NE"><background>green</background></sees>
        //      <sees direction="E"><background>green</background></sees>
        //      <sees direction="SE"><background>green</background></sees>
        //      <sees direction="SW"><background>green</background></sees>
        //      <sees direction="W"><building player="1">base</building><background>green</background></sees>
        //      <sees direction="NW"><background>green</background></sees>
        //      </unit>
        // </units>
        // </game>
        
        $thisUnit = $response->units[0]->unit[0];
        $unit = new Unit();
        $unit->setuId( strval( $thisUnit['id'] ) );
        $unit->setuLocation( new Point( (int)$thisUnit['x'], (int)$thisUnit['y'] ) );
        $unit->setuHp( strval( $thisUnit['hp'] ) );
        $unit->setuStatus( strval( $thisUnit['status'] ) );
        $unit->setuAction( strval( $thisUnit['action'] ) );
        $unit->setuOrientation( strval( $thisUnit['orientation'] ) );
        $unit->setuLevel( strval( $thisUnit['level'] ) );
        $unit->setuPlayer( strval( $thisUnit['player'] ) );
        $unit->setuSees( $this->checkResponseSees( $response->units[0] ) );

        return $unit;

    }

}

<?php

require_once 'GameChecker.php';
require_once 'Controller.php';
require_once 'Connector.php';
require_once 'ResponseAnalizer.php';

$game = new GameChecker();
$control = new Controller();
$responseAnalizer = new ResponseAnalizer();
$conn = new Connector();
if( !$conn ) {
    exit("bubu nie polaczylem sie");
}

$socket = $conn->getSocket();

while ($data = socket_read($socket, 2555, PHP_NORMAL_READ)) {
    echo "****************************************" . htmlspecialchars($data) . "****************************************<br/>";
    if (strpos(trim($data), "<?xml") === 0) {
    } else {
        try {
            $in = new SimpleXMLElement( $data );
            $type = $in->getName();
            if ($type == "response") {
                $status = $in['status'];
                
                $action = $responseAnalizer->checkGameStatus( $status );
                if( $action === false ) {
                    exit("bad response");
            } elseif ( in_array( (int)$action, array( 2, 30 ) ) ) {
                    sleep( (int)$action );
                }
                
            } elseif ($type == "game") {
                $gameResult = $in['result'];
                if ($gameResult) {
                    echo ("game ended with result: " . $gameResult);
                    break;
                } else {
// tutaj podejmujemy decyzje co chcemy zrobic w danym ruchu. 

                    // buduj stan jednostki
                    $unit = $responseAnalizer->checkResponseUnit( $in );
                    
                    // sprawdz co widzimy
                    $sees = $unit->getuSees();
                    echo'<pre>';print_r( $unit );echo'</pre><hr/>';

                    // ustalam wielkosc mapy do sprawdzenia
                    $mapSizeChanged = false;                    
                    if( $responseAnalizer->checkResponseRoundNumber( $in ) === 1 ) {
                        $game->setMapSize( $unit->getuLocation()->returnMaxValues( $game->getMapSize() ) );
                        $mapSizeChanged = true;
                        
                        // zapisz lokalizacje bazy
                        foreach ($sees as $key => $value) {
                            if( isset($value['building']) && $value['building'] === 'base' ) {
                                $searchKey = $key;
                                break;
                            }
                        }
                        
                        $baseLocation = $game->transformBaseLocation( $key, $unit->getuLocation() );                        
                        $game->setBaseLocation( $baseLocation->getPoint() );
                    }
                    echo'BAZA:<pre>';print_r( $game->getBaseLocation() );echo'</pre><hr/>';
                                        
                    if( in_array( $sees['E']['background'], $game->getAllBackgrounds() ) || in_array( $sees['NW']['background'], $game->getAllBackgrounds() ) ) {
                        if( $unit->getuLocation()->getX() % 2 === 0 ) {
                            $game->setMapSize( $unit->getuLocation()->shiftPoint( 1, 0 )->returnMaxValues( $game->getMapSize() ) );
                        } else {
                            $game->setMapSize( $unit->getuLocation()->shiftPoint( 1, 1 )->returnMaxValues( $game->getMapSize() ) );
                        }
                        $mapSizeChanged = true;
                    }
                    echo'ROZMIAR MAPY:<pre>';print_r( $game->getMapSize() );echo'</pre><hr/>';
                    
                    // buduj stan pol do sprawdzenia
                    if( $mapSizeChanged ) {
                        for( $y = 0; $y <= $game->getMapSize()->getY(); $y++ ) {
                            for( $x = 0; $x <= $game->getMapSize()->getX(); $x++ ) {
                                if( !$game->checkIfSpotToCheck( new Point( $x, $y ) ) && !$game->checkIfSpotChecked( new Point( $x, $y ) ) ) {
                                    $game->addPointsToCheck( new Point($x, $y) );
                                }
                            }
                        }
                    }
                    
                    
                    
                    
/*                    
                    echo $responseAnalizer->checkResponseTime( $in );
                    echo "<br/>";
                    echo $responseAnalizer->checkResponseRoundNumber( $in );
                    echo "<br/>";
                    echo $responseAnalizer->checkResponseAmountOfPoints( $in );
                    echo "<br/>";
*/                    
                    
                    
                    
                    
                    
                    /*
                    BAZUJAC NA WSPOLRZEDNYCH X,Y MUSIMY BRAC POD UWAGE HEXADECYMALNA MAPE A NIE KWADRATOWA
                            - POLE (E) -> +1,0
                            - POLE (NW) -> -1,-1
                            -- jak na dole
                            
                        -- przerobic sprawdzanie wielkosci mapy
                        -- przerobic walidacje pol
                    */
                    
                    
                    
                    
                    
                    
                    // weryfikuj czy pole zostalo juz sprawdzone
                        // buduj tablece pol sasiednich
                    $neighborSpots = array();
                    $neighborSpots['C'] = new Point( $unit->getuLocation() );
                    $neighborSpots['NE'] = new Point( $unit->getuLocation()->shiftPoint( 1, -1 ) );
                    $neighborSpots['E'] = new Point( $unit->getuLocation()->shiftPoint( 1, 0 ) );
                    $neighborSpots['SE'] = new Point( $unit->getuLocation()->shiftPoint( 1, 1 ) );
                    $neighborSpots['NW'] = new Point( $unit->getuLocation()->shiftPoint( -1, -1 ) );
                    $neighborSpots['W'] = new Point( $unit->getuLocation()->shiftPoint( -1, 0 ) );
                    $neighborSpots['SW'] = new Point( $unit->getuLocation()->shiftPoint( -1, 1 ) );
                    
                    foreach ($neighborSpots as $key => $value) {
                        if( $game->checkIfSpotToCheck( $value ) ) {
                            // sprawdzam punkt
                            // przenosze do sprawdzonych
                            $game->markSpotAsChecked( $value );
                        }
                    }

                    
//  test
                    if( $unit->getuOrientation() == "E" && $unit->getuLocation()->getY() > 1 ) {
                        $package = $control->move( $unit->getuId(), "SE");
                    } elseif( $unit->getuOrientation() == "E" && $unit->getuLocation()->getX() > 0 ) {
                        $package = $control->move( $unit->getuId(), "E" );
                    } else {
                        $package = $control->rotate( $unit->getuId(), "rotateRight" );
                    }
// !test                    
                    
                    echo "wysylam ". strlen( $package ) . " - " . htmlentities($package) . "<br/>";
                    socket_send($socket, $package, strlen($package), 0);
                }
            } elseif ($type == "error") { }
        } catch (Exception $ex) {
            echo("blad<br />");
            $ex->getTraceAsString();
        }
    }

    flush();
}

socket_close($socket);
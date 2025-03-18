<?php

    /**
     *  Read all Bonuses
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/SocialBonus.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Social Bonus' object
    $bonus = new SocialBonus($db);

    // Get 'read' method
    $result = $bonus->read();
    $num    = $result->rowCount();

    // Check if any Bonus
    if($num > 0){
        // Bonus array
        $bonus_arr = array();
        $bonus_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $bonus_item = array(
                'id' => $projID,
                'aguinaldo' => $bonusAguinaldo,
                'subsidios' => $bonusSubsidios,
                'indemnizacion' => $bonusIndemnizacion,
                'otros' => $bonusOtros,
                'subtotal_bonos'  => $bonusSubtotal
            );

            // Push to 'data'
            array_push($bonus_arr['data'], $bonus_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($bonus_arr));

    }else{
        // No Bonuses
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Bonuses Found')
        );
    }
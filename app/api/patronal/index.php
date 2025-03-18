<?php

    /**
     *  Read all Patronal Input
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/SocialPatronalInput.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Social Patronal Input' object
    $patronal_in = new SocialPatronalInput($db);

    // Get 'read' method
    $result = $patronal_in->read();
    $num    = $result->rowCount();

    // Check if any patronal input
    if($num > 0){
        // Patronal input array
        $patronal_arr = array();
        $patronal_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $patronal_item = array(
                'id' => $projID,
                'cnss' => $patronalCNSS,
                'infocal'  => $patronalInfocal,
                'aporte_vivencia' => $patronalAporteVivencia,
                'afps' => $patronalAfps,
                'subtotal_ap' => $patronalSubtotal,
                'equivalente_dc'  => $patronalEquivalenteDC
            );

            // Push to 'data'
            array_push($patronal_arr['data'], $patronal_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($patronal_arr));

    }else{
        // No Patronal input
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Patronal Input Found')
        );
    }
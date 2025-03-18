<?php

    /**
     *  Read all APU-Workers
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/ApuCostHrsWorker.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-Workers' object
    $apu_worker = new ApuCostHrsWorker($db);

    // Get 'read' method
    $result = $apu_worker->read();
    $num    = $result->rowCount();

    // Check if any APU-Workers
    if($num > 0){
        // Array
        $arr = array();
        $arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $item = array(
                'proj_id' => $projID,
                'apu_id' => $apuID,
                'cw_id' => $cwID,
                'cant' => $apuCwCant,
                'precio_productivo' => $apuCwPrecioProductivo,
                'costo_total' => $apuCwCostoTotal
            );

            // Push to 'data'
            array_push($arr['data'], $item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($arr));

    }else{
        // No APU-Workers
        http_response_code(404);
        echo json_encode(
            array('message' => 'No APU-Workers Found')
        );
    }
<?php

    /**
     *  Read a Single APU-Workers (s)
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

    // Get APU-Workers ID
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get APU id & Supp id
    $exp = explode("&", $data);
    $apu_worker->proj_id = $exp[0];
    $apu_worker->apu_id  = $exp[1];
    $apu_worker->cw_id = $exp[2];

    // Get APU-Workers
    $apu_worker->read_single();

    if ($apu_worker->exists){
        // Create array
        $arr['data'] = array(
            'proj_id' => $apu_worker->proj_id,
            'apu_id' => $apu_worker->apu_id,
            'cw_id' => $apu_worker->cw_id,
            'cant' => $apu_worker->cant,
            'precio_productivo' => $apu_worker->precio_productivo,
            'costo_total' => $apu_worker->costo_total
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($arr));

    }else{
        // No APU-Workers found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No IDs Found')
        );
    }

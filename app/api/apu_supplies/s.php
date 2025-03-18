<?php

    /**
     *  Read a Single APU-Supplies (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/ApuSupply.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-Supplies' object
    $apu_supply = new ApuSupply($db);

    // Get APU-Supplies ID
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get APU id & Supp id
    $exp = explode("&", $data);
    $apu_supply->proj_id = $exp[0];
    $apu_supply->apu_id  = $exp[1];
    $apu_supply->supp_id = $exp[2];

    // Get APU-Supplies
    $apu_supply->read_single();

    if ($apu_supply->exists){
        // Create array
        $arr['data'] = array(
            'proj_id' => $apu_supply->proj_id,
            'apu_id' => $apu_supply->apu_id,
            'supp_id' => $apu_supply->supp_id,
            'cant' => $apu_supply->cant,
            'precio_productivo' => $apu_supply->precio_productivo,
            'costo_total' => $apu_supply->costo_total
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($arr));

    }else{
        // No APU-Supplies found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No IDs Found')
        );
    }

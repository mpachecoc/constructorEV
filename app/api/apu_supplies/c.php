<?php

    /**
     *  Create an APU-Supplies (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ApuSupply.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-Supplies' object
    $apu_supply = new ApuSupply($db);

    // Get raw json APU-Supplies data
    $data = json_decode(file_get_contents("php://input"));

    $apu_supply->proj_id = $data->proj_id;
    $apu_supply->apu_id = $data->apu_id;
    $apu_supply->supp_id = $data->supp_id;
    $apu_supply->cant = $data->cant;
    $apu_supply->precio_productivo = $data->precio_productivo;
    $apu_supply->costo_total = $data->costo_total;


    // Create APU-Supplies
    if ($apu_supply->create()) {
        http_response_code(201);
        echo json_encode(
            array('message' => 'Item Created Successfully.')
        );
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Item Not Created.')
        );
    }
<?php

    /**
     *  Create an APU-Workers (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ApuCostHrsWorker.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-Workers' object
    $apu_worker = new ApuCostHrsWorker($db);

    // Get raw json APU-Workers data
    $data = json_decode(file_get_contents("php://input"));

    $apu_worker->proj_id = $data->proj_id;
    $apu_worker->apu_id = $data->apu_id;
    $apu_worker->cw_id = $data->cw_id;
    $apu_worker->cant = $data->cant;
    $apu_worker->precio_productivo = $data->precio_productivo;
    $apu_worker->costo_total = $data->costo_total;


    // Create APU-Workers
    if ($apu_worker->create()) {
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
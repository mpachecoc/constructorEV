<?php

    /**
     *  Create a Cost Man Hrs. Worker (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/CostManHrsWorker.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Cost Man Hrs. Worker' object
    $worker = new CostManHrsWorker($db);

    // Get raw json data
    $data = json_decode(file_get_contents("php://input"));

    $worker->proj_id  = $data->proj_id;
    $worker->worker_id = $data->worker_id;
    $worker->basico = $data->basico;
    $worker->bono = $data->bono;
    $worker->epp = $data->epp;
    $worker->cant  = $data->cant;
    $worker->transporte = $data->transporte;
    $worker->gasto_mensual_tot = $data->gasto_mensual_tot;
    $worker->bs_x_hr = $data->bs_x_hr;


    // Create
    if ($worker->create()) {
        http_response_code(201);
        $worker_arr['data'] = array(
            'id' => $worker->id,
            'proj_id' => $worker->proj_id,
            'worker_id' => $worker->worker_id,
            'basico' => $worker->basico,
            'bono' => $worker->bono,
            'epp'  => $worker->epp,
            'cant' => $worker->cant,
            'transporte' => $worker->transporte,
            'gasto_mensual_tot' => $worker->gasto_mensual_tot,
            'bs_x_hr' => $worker->bs_x_hr
        );
        print_r(json_encode($worker_arr));
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Item Not Created.')
        );
    }
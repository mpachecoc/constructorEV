<?php

    /**
     *  Read a Single Cost Man Hrs. Worker (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/CostManHrsWorker.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Cost Man Hrs. Worker' object
    $worker = new CostManHrsWorker($db);

    // Get ID
    $worker->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check
    $worker->read_single();

    if ($worker->exists){
        // Create array
        $worker_arr['data'] = array(
            'id' => $worker->id,
            'proj_id' => $worker->proj_id,
            'worker_id' => $worker->worker_id,
            'basico' => $worker->basico,
            'bono' => $worker->bono,
            'epp'  => $worker->epp,
            'cant' => $worker->cant,
            'transporte' => $worker->transporte,
            'hrs_extra_mes' => $worker->hrs_extra_mes,
            'gasto_mensual_tot' => $worker->gasto_mensual_tot,
            'bs_x_hr' => $worker->bs_x_hr
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($worker_arr));

    }else{
        // No ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No ID Found')
        );
    }

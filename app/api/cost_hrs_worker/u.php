<?php

    /**
     *  Update a Cost Man Hrs. Worker (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
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

    $worker->id = $data->id; // Set ID to update

    // Check if ID exists with 'read_single' method
    $worker->read_single();

    if ($worker->exists){
        // Get rest of data to update
        $worker->proj_id  = $data->proj_id;
        $worker->worker_id = $data->worker_id;
        $worker->basico = $data->basico;
        $worker->bono = $data->bono;
        $worker->epp = $data->epp;
        $worker->cant  = $data->cant;
        $worker->transporte = $data->transporte;
        $worker->hrs_extra_mes = $data->hrs_extra_mes;
        $worker->gasto_mensual_tot = $data->gasto_mensual_tot;
        $worker->bs_x_hr = $data->bs_x_hr;

        // Update
        if ($worker->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Item Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Item Not Updated.')
            );
        }

    }else{
        // No ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No ID Found')
        );
    }
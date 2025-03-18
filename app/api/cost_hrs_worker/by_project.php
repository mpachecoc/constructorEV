<?php

    /**
     *  Read Cost Man Hrs. Worker by Project
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

    // Get Project ID
    $worker->proj_id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get Data by Project
    $result = $worker->read_by_project();
    $num    = $result->rowCount();

    if($num > 0){
        // Array
        $worker_arr = array();
        $worker_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $worker_item = array(
                'id' => $cwID,
                'proj_id' => $projID,
                'worker_id' => $workerID,
                'basico' => $cwBasico,
                'bono' => $cwBono,
                'epp'  => $cwEPP,
                'cant' => $cwCantidad,
                'transporte' => $cwTransporte,
                'gasto_mensual_tot' => $cwGastoMensualTotal,
                'bs_x_hr' => $cwBsXHr
            );

            // Push to 'data'
            array_push($worker_arr['data'], $worker_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($worker_arr));

    }else{
        // No APUs
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Data Found')
        );
    }

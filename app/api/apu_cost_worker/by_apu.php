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

    // Get APU ID
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get APU id & Supp id
    $exp = explode("&", $data);
    $apu_worker->proj_id = $exp[0];
    $apu_worker->apu_id  = $exp[1];

    // Get 'read by APU' method
    $result = $apu_worker->read_by_apu();
    $num    = $result->rowCount();

    // Check if any APU-Workers by APU
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
        // No APU-Workers by APU
        http_response_code(404);
        echo json_encode(
            array('message' => 'No data found with the APU ID')
        );
    }

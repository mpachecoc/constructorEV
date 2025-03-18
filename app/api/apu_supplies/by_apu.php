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

    // Get APU ID
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get Proj id & APU id
    $exp = explode("&", $data);
    $apu_supply->proj_id = $exp[0];
    $apu_supply->apu_id  = $exp[1];

    // Get 'read by APU' method
    $result = $apu_supply->read_by_apu();
    $num    = $result->rowCount();

    // Check if any APU-Supplies by APU
    if($num > 0){
        // Array
        $arr = array();
        $arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $item = array(
                'proj_id' => $projID,
                'apu_id' => $apuID,
                'supp_id' => $suppID,
                'cant' => $apuSuppCant,
                'precio_productivo' => $apuSuppPrecioProductivo,
                'costo_total' => $apuSuppCostoTotal
            );

            // Push to 'data'
            array_push($arr['data'], $item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($arr));

    }else{
        // No APU-Supplies by APU
        http_response_code(404);
        echo json_encode(
            array('message' => 'No data found with the APU ID')
        );
    }

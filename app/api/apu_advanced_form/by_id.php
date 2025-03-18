<?php

    /**
     *  Read APU-AdvancedForm by 'Adv. Form ID' (by_id)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/ApuAdvancedForm.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-AdvancedForm' object
    $apu_adv_form = new ApuAdvancedForm($db);

    // Get Proj/ID
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get Proj id & id
    $exp = explode("&", $data);
    $apu_adv_form->proj_id = $exp[0];
    $apu_adv_form->adv_form_id  = $exp[1];

    // Get 'read by Id' method
    $result = $apu_adv_form->read_by_id();
    $num    = $result->rowCount();

    // Check if any APU-AdvancedForm by APU
    if($num > 0){
        // Array
        $arr = array();
        $arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $item = array(
                'proj_id' => $projID,
                'apu_id' => $apuID,
                'adv_form_id' => $advFormID,
                'cant' => $apuAdvFormCant,
                'total' => $apuAdvFormTotal,
                'percent' => $apuAdvFormPercent
            );

            // Push to 'data'
            array_push($arr['data'], $item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($arr));

    }else{
        // No APU-AdvancedForm by APU
        http_response_code(404);
        echo json_encode(
            array('message' => 'No data found with the Adv. Form ID')
        );
    }

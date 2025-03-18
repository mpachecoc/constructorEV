<?php

    /**
     *  Read all Advanced Forms
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/AdvancedForm.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Advanced Form' object
    $adv_form = new AdvancedForm($db);

    // Get 'read' method
    $result = $adv_form->read();
    $num    = $result->rowCount();

    // Check if any Advanced Form
    if($num > 0){
        // Array
        $arr = array();
        $arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $item = array(
                'proj_id' => $projID,
                'id' => $advFormID,
                'date_ini' => $advFormDateIni,
                'date_end' => $advFormDateEnd
            );

            // Push to 'data'
            array_push($arr['data'], $item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($arr));

    }else{
        // No Advanced Forms
        http_response_code(404);
        echo json_encode(
            array('message' => 'No \'Advanced Forms\' Found')
        );
    }
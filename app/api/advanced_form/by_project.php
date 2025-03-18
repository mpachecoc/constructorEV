<?php

    /**
     *  Read Advanced Forms by Project (by_project)
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

    // Get Project ID
    $adv_form->proj_id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get Advanced Forms
    $result = $adv_form->read_by_project();
    $num    = $result->rowCount();

    if($num > 0){
        // Create Array
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

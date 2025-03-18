<?php

    /**
     *  Read a Single Annual Working (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/SocialAnnualWorkingTime.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Annual Working' object
    $annual_working = new SocialAnnualWorkingTime($db);

    // Get Annual Working ID
    $annual_working->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get Annual Working
    $annual_working->read_single();

    if ($annual_working->exists){
        // Create array
        $annual_working_arr['data'] = array(
            'id' => $annual_working->id,
            'year_days' => $annual_working->year_days,
            'inactividad' => $annual_working->inactividad,
            'vacaciones'  => $annual_working->vacaciones,
            'feriados' => $annual_working->feriados,
            'lluvias'  => $annual_working->lluvias,
            'enfermedades' => $annual_working->enfermedades,
            'dias_no_trab' => $annual_working->dias_no_trab,
            'subtotal_anual_work' => $annual_working->subtotal
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($annual_working_arr));

    }else{
        // No Annual Working ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Annual Working ID Found')
        );
    }

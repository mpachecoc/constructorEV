<?php

    /**
     *  Update a Annual Working (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/SocialAnnualWorkingTime.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Annual Working' object
    $annual_working = new SocialAnnualWorkingTime($db);

    // Get raw json Annual Working data
    $data = json_decode(file_get_contents("php://input"));

    $annual_working->id = $data->id; // Set ID to update

    // Check if Annual Working ID exists with 'read_single' method
    $annual_working->read_single();

    if ($annual_working->exists){
        // Get rest of data to update
        $annual_working->year_days = $data->year_days;
        $annual_working->inactividad = $data->inactividad;
        $annual_working->vacaciones = $data->vacaciones;
        $annual_working->feriados = $data->feriados;
        $annual_working->lluvias = $data->lluvias;
        $annual_working->enfermedades = $data->enfermedades;
        $annual_working->dias_no_trab = $data->dias_no_trab;
        $annual_working->subtotal = $data->subtotal_anual_work;

        // Update Annual Working data
        if ($annual_working->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Annual Working data Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Annual Working data Not Updated.')
            );
        }

    }else{
        // No Annual Working ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Annual Working ID Found')
        );
    }
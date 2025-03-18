<?php

    /**
     *  Delete a Annual Working (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/SocialAnnualWorkingTime.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Annual Working' object
    $annual_working = new SocialAnnualWorkingTime($db);

    // Get Annual Working ID
    $annual_working->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if Annual Working ID exists with 'read_single' method
    $annual_working->read_single();

    if ($annual_working->exists){
        // Delete Annual Working data
        if ($annual_working->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Annual Working data Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Annual Working data Not Deleted.')
            );
        }

    }else{
        // No Annual Working ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Annual Working ID Found')
        );
    }
<?php

    /**
     *  Create a Supply (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Supplies.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Supplies' object
    $supplies = new Supplies($db);

    // Get raw json supply data
    $data = json_decode(file_get_contents("php://input"));

    $supplies->name = $data->name;
    $supplies->desc = $data->desc;
    $supplies->und  = $data->und;
    $supplies->precio = $data->precio;
    $supplies->moneda = $data->moneda;


    // Create Supply
    if ($supplies->create()) {
        http_response_code(200);
        echo json_encode(
            array('message' => 'Supply Created Successfully.')
        );
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Supply Not Created.')
        );
    }
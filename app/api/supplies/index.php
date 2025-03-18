<?php

    /**
     *  Read all Supplies
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Supplies.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Supplies' object
    $supplies = new Supplies($db);

    // Get 'read' method
    $result = $supplies->read();
    $num    = $result->rowCount();

    // Check if any supplies
    if($num > 0){
        // Project array
        $supplies_arr = array();
        $supplies_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $supplies_item = array(
                'id' => $suppID,
                'name' => $suppName,
                'desc' => $suppDesc,
                'und'   => $suppUnd,
                'precio' => $suppPrecio,
                'moneda' => $suppMoneda
            );

            // Push to 'data'
            array_push($supplies_arr['data'], $supplies_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($supplies_arr));

    }else{
        // No supplies
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Supplies Found')
        );
    }
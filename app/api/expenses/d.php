<?php

    /**
     *  Delete an Expense (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Expenses.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Expenses' object
    $expenses = new Expenses($db);

    // Get Expense ID
    $expenses->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if ID exists with 'read_single' method
    $expenses->read_single();

    if ($expenses->exists){
        // Delete Expense
        if ($expenses->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Expense Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Expense Not Deleted.')
            );
        }

    }else{
        // No Expense ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Expense ID Found')
        );
    }
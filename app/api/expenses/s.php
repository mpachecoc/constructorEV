<?php

    /**
     *  Read a Single Expense (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Expenses.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Expenses' object
    $expenses = new Expenses($db);

    // Get Expense ID
    $expenses->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if Id exists
    $expenses->read_single();

    if ($expenses->exists){
        // Create array
        $exp_arr['data'] = array(
            'id' => $expenses->id,
            'project_id' => $expenses->proj_id,
            'type' => $expenses->type,
            'discharge' => $expenses->discharge,
            'date' => $expenses->date,
            'supplier'  => $expenses->supplier,
            'desc' => $expenses->desc,
            'item' => $expenses->item,
            'sub_item' => $expenses->sub_item,
            'object' => $expenses->object,
            'amount' => $expenses->amount,
            'number' => $expenses->number,
            'invoice' => $expenses->invoice,
            'origin' => $expenses->origin,
            'authorization' => $expenses->authorization,
            'cond_1' => $expenses->cond_1,
            'cond_2' => $expenses->cond_2
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($exp_arr));

    }else{
        // No Expense ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Expense ID Found')
        );
    }

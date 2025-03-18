<?php

    /**
     *  Update an Expense (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Expenses.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Expenses' object
    $expenses = new Expenses($db);

    // Get raw json data
    $data = json_decode(file_get_contents("php://input"));

    $expenses->id = $data->id; // Set ID to update

    // Check if ID exists with 'read_single' method
    $expenses->read_single();

    if ($expenses->exists){
        // Get rest of data to update
        $expenses->proj_id  = $data->project_id;
        $expenses->type = $data->type;
        $expenses->discharge = $data->discharge;
        list($d,$m,$y) = sscanf($data->date, "%d-%d-%d");
        $expenses->date = $y."-".$m."-".$d;
        $expenses->supplier = $data->supplier;
        $expenses->desc  = $data->desc;
        $expenses->item = $data->item;
        $expenses->sub_item = $data->sub_item;
        $expenses->object = $data->object;
        $expenses->amount = $data->amount;
        $expenses->number = $data->number;
        $expenses->invoice = $data->invoice;
        $expenses->origin = $data->origin;
        $expenses->authorization = $data->authorization;
        $expenses->cond_1 = $data->cond_1;
        $expenses->cond_2 = $data->cond_2;

        // Update Expense
        if ($expenses->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Expense Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Expense Not Updated.')
            );
        }

    }else{
        // No Expense ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Expense ID Found')
        );
    }
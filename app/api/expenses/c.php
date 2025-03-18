<?php

    /**
     *  Create an Expense (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
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

    // Create Expense
    if ($expenses->create()) {
        http_response_code(201);
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
        print_r(json_encode($exp_arr));
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Expense Not Created.')
        );
    }
<?php

    /**
     *  Read Expenses by Project
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

    // Get Project ID
    $expenses->proj_id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get Data by Project
    $result = $expenses->read_by_project();
    $num    = $result->rowCount();

    if($num > 0){
        // Expense Array
        $exp_arr = array();
        $exp_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $exp_item = array(
                'id' => $expenseID,
                'project_id' => $projID,
                'type' => $expenseType,
                'discharge' => $expDischarge,
                'date' => $expDate,
                'supplier'  => $expSupplier,
                'desc' => $expDesc,
                'item' => $expItem,
                'sub_item' => $expSubItem,
                'object' => $expObject,
                'amount' => $expAmount,
                'number' => $expNumber,
                'invoice' => $expInvoice,
                'origin' => $expOrigin,
                'authorization' => $expAuthorization,
                'cond_1' => $expCond1,
                'cond_2' => $expCond2
            );

            // Push to 'data'
            array_push($exp_arr['data'], $exp_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($exp_arr));

    }else{
        // No Expenses found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Expenses Found')
        );
    }

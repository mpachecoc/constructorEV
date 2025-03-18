<?php

    /**
     *  Create a Project-User (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ProjectUser.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project-User' object
    $proj_user = new ProjectUser($db);

    // Get raw json Project-User data
    $data = json_decode(file_get_contents("php://input"));

    $proj_user->user_id = $data->user_id;
    $proj_user->project_id = $data->project_id;
    $proj_user->assigned_date = $data->assigned_date;

    // Create Project-User
    if ($proj_user->create()) {
        http_response_code(201);
        echo json_encode(
            array('message' => 'Item Created Successfully.')
        );
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Item Not Created.')
        );
    }
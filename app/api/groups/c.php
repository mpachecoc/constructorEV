<?php

    /**
     *  Create a Group (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Group.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Group' object
    $group = new Group($db);

    // Get raw json Group data
    $data = json_decode(file_get_contents("php://input"));

    $group->name = $data->name;
    $group->project_id = $data->project_id;

    // Create Group
    if ($group->create()) {
        http_response_code(200);
        $group_arr['data'] = array(
            'id' => $group->id,
            'name' => $group->name,
            'project_id' => $group->project_id
        );
        print_r(json_encode($group_arr));
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Group Not Created.')
        );
    }
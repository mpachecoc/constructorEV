<?php

    /**
     *  Read all Project-User
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/ProjectUser.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project-User' object
    $proj_user = new ProjectUser($db);

    // Get 'read' method
    $result = $proj_user->read();
    $num    = $result->rowCount();

    // Check if any Project-User
    if($num > 0){
        // Project-User array
        $arr = array();
        $arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $item = array(
                'user_id' => $userID,
                'project_id' => $projID,
                'assigned_date' => $assignedDate
            );

            // Push to 'data'
            array_push($arr['data'], $item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($arr));

    }else{
        // No Project-User
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Project-User Data Found')
        );
    }
<?php

    /**
     *  Read Project-User by User (s)
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

    // Get Project ID
    $proj_user->project_id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get Project-User
    $result = $proj_user->read_by_project();
    $num    = $result->rowCount();

    if($num > 0){
        // Project-User array
        $arr = array();
        $arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $item = array(
              'project_id' => $projID,
              'user_id' => $userID,
              'assigned_date' => $assignedDate
            );

            // Push to 'data'
            array_push($arr['data'], $item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($arr));

    }else{
        // No Project
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Project ID Found')
        );
    }

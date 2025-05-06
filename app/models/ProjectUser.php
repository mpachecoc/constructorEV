<?php

    class ProjectUser {
        // DB Conn
        private $conn;
        private $table = 'project_user';

        // Project-User Properties
        public $project_id;
        public $user_id;
        public $assigned_date;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Project-User */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY userID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET by single User */
        public function read_by_user() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE userID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind userID
            $stmt->bindParam(1, $this->user_id);

            // Execute query
            $stmt->execute();

            return $stmt;
        }
        
        /** GET by single Project */
        public function read_by_project() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE projID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind projectID
            $stmt->bindParam(1, $this->project_id);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** CREATE a Project-User */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      projID = :proj_id, 
                      userID = :user_id, 
                      assignedDate = :date';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->project_id = htmlspecialchars(strip_tags($this->project_id));
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));

            // Bind data
            $stmt->bindParam(':proj_id', $this->project_id);
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':date', $this->assigned_date);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE a Project-User (Not Available) */

        /** DELETE Project-User */
        public function delete() {
            // Create Query
            $query = 'DELETE FROM '.$this->table.' WHERE userID = :user_id AND projID = :proj_id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->project_id = htmlspecialchars(strip_tags($this->project_id));
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));

            // Bind userID
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':proj_id', $this->project_id);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

    }

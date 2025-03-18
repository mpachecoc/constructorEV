<?php

    class Task {
        // DB Conn
        private $conn;
        private $table = 'tasks';

        // Task Properties
        public $id;
        public $project_id;
        public $name;
        public $percentage;
        public $start_date;
        public $end_date;
        public $exists; // Num of returned rows
        public $row_to_patch; // DB Row
        public $val_to_patch; // Value 

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Tasks */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY apuID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single Task */
        public function read_single() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE apuID = ? AND projID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind IDs
            $stmt->bindParam(1, $this->id);
            $stmt->bindParam(2, $this->project_id);

            // Execute query
            $stmt->execute();

            $this->exists = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->id = $row['apuID'];
            $this->project_id = $row['projID'];
            $this->name = $row['taskName'];
            $this->percentage = $row['taskPercentageComp'];
            $this->start_date = $row['taskStart'];
            $this->end_date = $row['taskEnd'];
        }

        /** GET Tasks by Project ID */
        public function read_by_project() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE projID = ? ORDER BY apuID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind projID
            $stmt->bindParam(1, $this->project_id);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** CREATE a Task */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      apuID = :id, 
                      projID = :project_id, 
                      taskName = :name,
                      taskPercentageComp = :percentage,
                      taskStart = :start_date, 
                      taskEnd = :end_date';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->project_id = htmlspecialchars(strip_tags($this->project_id));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->percentage = htmlspecialchars(strip_tags($this->percentage));
            $this->start_date = htmlspecialchars(strip_tags($this->start_date));
            $this->end_date = htmlspecialchars(strip_tags($this->end_date));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':project_id', $this->project_id);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':percentage', $this->percentage);
            $stmt->bindParam(':start_date', $this->start_date);
            $stmt->bindParam(':end_date', $this->end_date);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE a Task */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET                     
                    taskName = :name,
                    taskPercentageComp = :percentage,
                    taskStart = :start_date, 
                    taskEnd = :end_date
                WHERE
                    projID = :project_id
                AND
                    apuID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->project_id = htmlspecialchars(strip_tags($this->project_id));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->percentage = htmlspecialchars(strip_tags($this->percentage));
            $this->start_date = htmlspecialchars(strip_tags($this->start_date));
            $this->end_date = htmlspecialchars(strip_tags($this->end_date));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':project_id', $this->project_id);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':percentage', $this->percentage);
            $stmt->bindParam(':start_date', $this->start_date);
            $stmt->bindParam(':end_date', $this->end_date);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE APU */
        public function delete() {
            // Create Query
            $query = 'DELETE FROM '.$this->table.' WHERE apuID = :id AND projID = :project_id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->project_id = htmlspecialchars(strip_tags($this->project_id));

            // Bind IDs
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':project_id', $this->project_id);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        /** PATCH (update) a Task */
        public function update_single() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                     '.$this->row_to_patch.' = :val
                    WHERE
                     projID = :project_id
                    AND
                     apuID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->project_id = htmlspecialchars(strip_tags($this->project_id));
            $this->val_to_patch = htmlspecialchars(strip_tags($this->val_to_patch));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':project_id', $this->project_id);
            $stmt->bindParam(':val', $this->val_to_patch);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

    }
























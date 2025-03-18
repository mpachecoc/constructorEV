<?php

    class Worker {
        // DB Conn
        private $conn;
        private $table = 'worker';

        // Worker Properties
        public $id;
        public $name;
        public $desc;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Workers */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY workerID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single Worker */
        public function read_single() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE workerID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind userID
            $stmt->bindParam(1, $this->id);

            // Execute query
            $stmt->execute();

            $this->exists = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->name = $row['workerName'];
            $this->desc = $row['workerDesc'];
        }

        /** CREATE a Worker */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      workerName = :name, 
                      workerDesc = :desc';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->desc = htmlspecialchars(strip_tags($this->desc));

            // Bind data
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':desc', $this->desc);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE a Worker */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                      workerName = :name, 
                      workerDesc = :desc
                    WHERE 
                      workerID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->desc = htmlspecialchars(strip_tags($this->desc));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':desc', $this->desc);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE Worker */
        public function delete() {
            // Create Query
            $query = 'DELETE FROM '.$this->table.' WHERE workerID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind userID
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

    }

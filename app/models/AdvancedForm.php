<?php

    class AdvancedForm {
        // DB Conn
        private $conn;
        private $table = 'advanced_form';

        // Advanced Form Properties
        public $id;
        public $proj_id;
        public $date_ini;
        public $date_end;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Advanced Forms */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY projID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single Advanced Form */
        public function read_single() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' 
                        WHERE projID = ? 
                        AND advFormID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->proj_id);
            $stmt->bindParam(2, $this->id);

            // Execute query
            $stmt->execute();

            $this->exists = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->date_ini = $row['advFormDateIni'];
            $this->date_end = $row['advFormDateEnd'];
        }

        /** GET Advanced Forms by Project */
        public function read_by_project() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE projID = ? ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind Proj ID
            $stmt->bindParam(1, $this->proj_id);

            // Execute query
            $stmt->execute();

            return $stmt;

        }

        /** CREATE an Advanced Form */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      projID = :proj_id, 
                      advFormID = :id, 
                      advFormDateIni = :date_ini, 
                      advFormDateEnd = :date_end';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->proj_id = htmlspecialchars(strip_tags($this->proj_id));
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->date_ini = htmlspecialchars(strip_tags($this->date_ini));
            $this->date_end = htmlspecialchars(strip_tags($this->date_end));

            // Bind data
            $stmt->bindParam(':proj_id', $this->proj_id);
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':date_ini', $this->date_ini);
            $stmt->bindParam(':date_end', $this->date_end);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE an Advanced Form */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                        advFormDateIni = :date_ini, 
                        advFormDateEnd = :date_end
                    WHERE 
                        projID = :proj_id
                    AND
                        advFormID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

             // Clean data
             $this->proj_id = htmlspecialchars(strip_tags($this->proj_id));
             $this->id = htmlspecialchars(strip_tags($this->id));
             $this->date_ini = htmlspecialchars(strip_tags($this->date_ini));
             $this->date_end = htmlspecialchars(strip_tags($this->date_end));
 
             // Bind data
             $stmt->bindParam(':proj_id', $this->proj_id);
             $stmt->bindParam(':id', $this->id);
             $stmt->bindParam(':date_ini', $this->date_ini);
             $stmt->bindParam(':date_end', $this->date_end);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE Advanced Form */
        public function delete() {
            // Create Query
            $query = 'DELETE FROM '.$this->table.' 
                        WHERE projID = :proj_id
                        AND advFormID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->proj_id = htmlspecialchars(strip_tags($this->proj_id));
            $this->id  = htmlspecialchars(strip_tags($this->id));

            // Bind userID
            $stmt->bindParam(':proj_id', $this->proj_id);
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

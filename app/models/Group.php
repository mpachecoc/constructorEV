<?php

    class Group {
        // DB Conn
        private $conn;
        private $table = 'groups';

        // Worker Properties
        public $id;
        public $name;
        public $project_id;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Groups */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY groupID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single Group */
        public function read_single() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE groupID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind groupID
            $stmt->bindParam(1, $this->id);

            // Execute query
            $stmt->execute();

            $this->exists = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->name = $row['groupName'];
            $this->project_id = $row['projID'];
        }

        /** GET Groups by Project ID */
        public function read_by_project() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE projID = ? ORDER BY groupID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind projID
            $stmt->bindParam(1, $this->project_id);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** CREATE a Group */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      groupName = :name, 
                      projID = :project_id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->project_id = htmlspecialchars(strip_tags($this->project_id));

            // Bind data
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':project_id', $this->project_id);

            // Execute query
            if ($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE a Group */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                      groupName = :name, 
                      projID = :project_id
                    WHERE 
                      groupID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->project_id = htmlspecialchars(strip_tags($this->project_id));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':project_id', $this->project_id);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE Group */
        public function delete() {
            // Create Query
            $query = 'DELETE FROM '.$this->table.' WHERE groupID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind groupID
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

<?php

    class Equipment {
        // DB Conn
        private $conn;
        private $table = 'equipment_machinery';

        // Equipment Properties
        public $id;
        public $name;
        public $desc;
        public $precio;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Equipments */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY equipID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single Equipment */
        public function read_single() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE equipID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind userID
            $stmt->bindParam(1, $this->id);

            // Execute query
            $stmt->execute();

            $this->exists = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->name = $row['equipName'];
            $this->desc = $row['equipDesc'];
            $this->precio = $row['equipPrecio'];
        }

        /** CREATE an Equipment */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      equipName = :name, 
                      equipDesc = :desc,
                      equipPrecio = :precio';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->desc = htmlspecialchars(strip_tags($this->desc));
            $this->precio = htmlspecialchars(strip_tags($this->precio));

            // Bind data
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':desc', $this->desc);
            $stmt->bindParam(':precio', $this->precio);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE an Equipment */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                      equipName = :name, 
                      equipDesc = :desc,
                      equipPrecio = :precio
                    WHERE 
                      equipID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->desc = htmlspecialchars(strip_tags($this->desc));
            $this->precio = htmlspecialchars(strip_tags($this->precio));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':desc', $this->desc);
            $stmt->bindParam(':precio', $this->precio);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE Equipment */
        public function delete() {
            // Create Query
            $query = 'DELETE FROM '.$this->table.' WHERE equipID = :id';

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

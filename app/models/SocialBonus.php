<?php

    class SocialBonus {
        // DB Conn
        private $conn;
        private $table = 'social_bonuses';

        // Bonus Properties
        public $id;
        public $aguinaldo;
        public $subsidios;
        public $indemnizacion;
        public $otros;
        public $subtotal;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Bonuses */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY projID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single Bonus */
        public function read_single() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE projID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind userID
            $stmt->bindParam(1, $this->id);

            // Execute query
            $stmt->execute();

            $this->exists = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->aguinaldo = $row['bonusAguinaldo'];
            $this->subsidios = $row['bonusSubsidios'];
            $this->indemnizacion = $row['bonusIndemnizacion'];
            $this->otros = $row['bonusOtros'];
            $this->subtotal = $row['bonusSubtotal'];
        }

        /** CREATE a Bonus */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      projID = :id, 
                      bonusAguinaldo = :aguinaldo, 
                      bonusSubsidios = :subsidios,
                      bonusIndemnizacion = :indemnizacion,
                      bonusOtros = :otros,
                      bonusSubtotal = :subtotal';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->aguinaldo = htmlspecialchars(strip_tags($this->aguinaldo));
            $this->subsidios = htmlspecialchars(strip_tags($this->subsidios));
            $this->indemnizacion = htmlspecialchars(strip_tags($this->indemnizacion));
            $this->otros = htmlspecialchars(strip_tags($this->otros));
            $this->subtotal  = htmlspecialchars(strip_tags($this->subtotal));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':aguinaldo', $this->aguinaldo);
            $stmt->bindParam(':subsidios', $this->subsidios);
            $stmt->bindParam(':indemnizacion', $this->indemnizacion);
            $stmt->bindParam(':otros', $this->otros);
            $stmt->bindParam(':subtotal', $this->subtotal);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE a Bonus */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                      bonusAguinaldo = :aguinaldo, 
                      bonusSubsidios = :subsidios,
                      bonusIndemnizacion = :indemnizacion,
                      bonusOtros = :otros,
                      bonusSubtotal = :subtotal
                    WHERE 
                      projID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->aguinaldo = htmlspecialchars(strip_tags($this->aguinaldo));
            $this->subsidios = htmlspecialchars(strip_tags($this->subsidios));
            $this->indemnizacion = htmlspecialchars(strip_tags($this->indemnizacion));
            $this->otros = htmlspecialchars(strip_tags($this->otros));
            $this->subtotal  = htmlspecialchars(strip_tags($this->subtotal));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':aguinaldo', $this->aguinaldo);
            $stmt->bindParam(':subsidios', $this->subsidios);
            $stmt->bindParam(':indemnizacion', $this->indemnizacion);
            $stmt->bindParam(':otros', $this->otros);
            $stmt->bindParam(':subtotal', $this->subtotal);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE Bonus */
        public function delete() {
            // Create Query
            $query = 'DELETE FROM '.$this->table.' WHERE projID = :id';

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
























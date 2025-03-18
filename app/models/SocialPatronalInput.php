<?php

    class SocialPatronalInput {
        // DB Conn
        private $conn;
        private $table = 'social_patronal_input';

        // Patronal Input Properties
        public $id;
        public $cnss;
        public $infocal;
        public $aporte_vivencia;
        public $afps;
        public $subtotal;
        public $equivalente_dc;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Patronal Input */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY projID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single Patronal Input */
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
            $this->cnss = $row['patronalCNSS'];
            $this->infocal  = $row['patronalInfocal'];
            $this->aporte_vivencia = $row['patronalAporteVivencia'];
            $this->afps = $row['patronalAfps'];
            $this->subtotal = $row['patronalSubtotal'];
            $this->equivalente_dc  = $row['patronalEquivalenteDC'];
        }

        /** CREATE a Patronal Input */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      projID = :id, 
                      patronalCNSS = :cnss, 
                      patronalInfocal  = :infocal,
                      patronalAporteVivencia = :aporte_vivencia,
                      patronalAfps = :afps,
                      patronalSubtotal = :subtotal,
                      patronalEquivalenteDC  = :equivalente_dc';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->cnss = htmlspecialchars(strip_tags($this->cnss));
            $this->infocal  = htmlspecialchars(strip_tags($this->infocal));
            $this->aporte_vivencia = htmlspecialchars(strip_tags($this->aporte_vivencia));
            $this->afps = htmlspecialchars(strip_tags($this->afps));
            $this->subtotal = htmlspecialchars(strip_tags($this->subtotal));
            $this->equivalente_dc  = htmlspecialchars(strip_tags($this->equivalente_dc));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':cnss', $this->cnss);
            $stmt->bindParam(':infocal', $this->infocal);
            $stmt->bindParam(':aporte_vivencia', $this->aporte_vivencia);
            $stmt->bindParam(':afps', $this->afps);
            $stmt->bindParam(':subtotal', $this->subtotal);
            $stmt->bindParam(':equivalente_dc', $this->equivalente_dc);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE a Patronal Input */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                      patronalCNSS = :cnss, 
                      patronalInfocal  = :infocal,
                      patronalAporteVivencia = :aporte_vivencia,
                      patronalAfps = :afps,
                      patronalSubtotal = :subtotal,
                      patronalEquivalenteDC  = :equivalente_dc
                    WHERE 
                      projID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->cnss = htmlspecialchars(strip_tags($this->cnss));
            $this->infocal  = htmlspecialchars(strip_tags($this->infocal));
            $this->aporte_vivencia = htmlspecialchars(strip_tags($this->aporte_vivencia));
            $this->afps = htmlspecialchars(strip_tags($this->afps));
            $this->subtotal = htmlspecialchars(strip_tags($this->subtotal));
            $this->equivalente_dc  = htmlspecialchars(strip_tags($this->equivalente_dc));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':cnss', $this->cnss);
            $stmt->bindParam(':infocal', $this->infocal);
            $stmt->bindParam(':aporte_vivencia', $this->aporte_vivencia);
            $stmt->bindParam(':afps', $this->afps);
            $stmt->bindParam(':subtotal', $this->subtotal);
            $stmt->bindParam(':equivalente_dc', $this->equivalente_dc);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE Patronal Input */
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
























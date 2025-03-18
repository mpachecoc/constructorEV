<?php

    class ApuAdvancedForm {
        // DB Conn
        private $conn;
        private $table = 'apu_advanced_form';

        // APU-AdvancedForm Properties
        public $apu_id;
        public $adv_form_id;
        public $proj_id;
        public $cant;
        public $total;
        public $percent;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET APU-AdvancedForm */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY projID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single APU-AdvancedForm */
        public function read_single() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' 
                        WHERE projID = ? 
                        AND apuID = ? 
                        AND advFormID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->proj_id);
            $stmt->bindParam(2, $this->apu_id);
            $stmt->bindParam(3, $this->adv_form_id);

            // Execute query
            $stmt->execute();

            $this->exists = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->cant = $row['apuAdvFormCant'];
            $this->total = $row['apuAdvFormTotal'];
            $this->percent = $row['apuAdvFormPercent'];
        }

        /** GET by APU */
        public function read_by_apu() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE projID = ? AND apuID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->proj_id);
            $stmt->bindParam(2, $this->apu_id);

            // Execute query
            $stmt->execute();

            return $stmt;

        }
        
        /** GET by Adv. Form ID */
        public function read_by_id() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE projID = ? AND advFormID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->proj_id);
            $stmt->bindParam(2, $this->adv_form_id);

            // Execute query
            $stmt->execute();

            return $stmt;

        }

        /** CREATE an APU-AdvancedForm */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      projID = :proj_id, 
                      apuID = :apu_id, 
                      advFormID = :adv_form_id, 
                      apuAdvFormCant = :cant,
                      apuAdvFormTotal = :total,
                      apuAdvFormPercent = :percent';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->proj_id = htmlspecialchars(strip_tags($this->proj_id));
            $this->apu_id = htmlspecialchars(strip_tags($this->apu_id));
            $this->adv_form_id = htmlspecialchars(strip_tags($this->adv_form_id));
            $this->cant = htmlspecialchars(strip_tags($this->cant));
            $this->total   = htmlspecialchars(strip_tags($this->total));
            $this->percent = htmlspecialchars(strip_tags($this->percent));

            // Bind data
            $stmt->bindParam(':proj_id', $this->proj_id);
            $stmt->bindParam(':apu_id', $this->apu_id);
            $stmt->bindParam(':adv_form_id', $this->adv_form_id);
            $stmt->bindParam(':cant', $this->cant);
            $stmt->bindParam(':total', $this->total);
            $stmt->bindParam(':percent', $this->percent);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE an APU-AdvancedForm */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                        apuAdvFormCant = :cant,
                        apuAdvFormTotal = :total,
                        apuAdvFormPercent = :percent
                    WHERE 
                        projID = :proj_id
                    AND
                      apuID = :apu_id
                    AND
                      advFormID = :adv_form_id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->proj_id = htmlspecialchars(strip_tags($this->proj_id));
            $this->apu_id = htmlspecialchars(strip_tags($this->apu_id));
            $this->adv_form_id = htmlspecialchars(strip_tags($this->adv_form_id));
            $this->cant = htmlspecialchars(strip_tags($this->cant));
            $this->total   = htmlspecialchars(strip_tags($this->total));
            $this->percent = htmlspecialchars(strip_tags($this->percent));

            // Bind data
            $stmt->bindParam(':proj_id', $this->proj_id);
            $stmt->bindParam(':apu_id', $this->apu_id);
            $stmt->bindParam(':adv_form_id', $this->adv_form_id);
            $stmt->bindParam(':cant', $this->cant);
            $stmt->bindParam(':total', $this->total);
            $stmt->bindParam(':percent', $this->percent);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE APU-AdvancedForm (Omit APU) */
        public function delete() {
            // Create Query
            // $query = 'DELETE FROM '.$this->table.' 
            //             WHERE projID = :proj_id
            //             AND apuID = :apu_id 
            //             AND advFormID = :adv_form_id';

            $query = 'DELETE FROM '.$this->table.' 
                        WHERE projID = :proj_id
                        AND advFormID = :adv_form_id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->proj_id = htmlspecialchars(strip_tags($this->proj_id));
            $this->adv_form_id = htmlspecialchars(strip_tags($this->adv_form_id));
            // $this->apu_id  = htmlspecialchars(strip_tags($this->apu_id));

            // Bind IDs
            $stmt->bindParam(':proj_id', $this->proj_id);
            $stmt->bindParam(':adv_form_id', $this->adv_form_id);
            // $stmt->bindParam(':apu_id', $this->apu_id);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        /** Check if exists to delete */
        public function check_to_delete() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' 
                        WHERE projID = ? 
                        AND advFormID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->proj_id);
            $stmt->bindParam(2, $this->adv_form_id);

            // Execute query
            $stmt->execute();
            $this->exists = $stmt->rowCount();
            
        }

    }

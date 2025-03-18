<?php

    class ApuCostHrsWorker {
        // DB Conn
        private $conn;
        private $table = 'apu_cost_hrs_worker';

        // APU-Workers Properties
        public $proj_id;
        public $apu_id;
        public $cw_id;
        public $cant;
        public $precio_productivo;
        public $costo_total;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET APU-Workers */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY projID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single APU-Workers */
        public function read_single() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' 
                        WHERE projID = ?
                        AND apuID = ? 
                        AND cwID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->proj_id);
            $stmt->bindParam(2, $this->apu_id);
            $stmt->bindParam(3, $this->cw_id);

            // Execute query
            $stmt->execute();

            $this->exists = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->cant = $row['apuCwCant'];
            $this->precio_productivo = $row['apuCwPrecioProductivo'];
            $this->costo_total = $row['apuCwCostoTotal'];
        }

        /** GET by Project and APU (APU-Workers) */
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

        /** CREATE an APU-Workers */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      projID = :proj_id, 
                      apuID = :apu_id, 
                      cwID = :cw_id, 
                      apuCwCant = :cant,
                      apuCwPrecioProductivo = :precio_productivo,
                      apuCwCostoTotal = :costo_total';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->proj_id = htmlspecialchars(strip_tags($this->proj_id));
            $this->apu_id = htmlspecialchars(strip_tags($this->apu_id));
            $this->cw_id = htmlspecialchars(strip_tags($this->cw_id));
            $this->cant = htmlspecialchars(strip_tags($this->cant));
            $this->precio_productivo   = htmlspecialchars(strip_tags($this->precio_productivo));
            $this->costo_total = htmlspecialchars(strip_tags($this->costo_total));

            // Bind data
            $stmt->bindParam(':proj_id', $this->proj_id);
            $stmt->bindParam(':apu_id', $this->apu_id);
            $stmt->bindParam(':cw_id', $this->cw_id);
            $stmt->bindParam(':cant', $this->cant);
            $stmt->bindParam(':precio_productivo', $this->precio_productivo);
            $stmt->bindParam(':costo_total', $this->costo_total);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE an APU-Workers */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                      apuCwCant = :cant,
                      apuCwPrecioProductivo = :precio_productivo,
                      apuCwCostoTotal = :costo_total
                    WHERE 
                      projID = :proj_id
                    AND
                      apuID = :apu_id
                    AND
                      cwID = :cw_id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->proj_id = htmlspecialchars(strip_tags($this->proj_id));
            $this->apu_id = htmlspecialchars(strip_tags($this->apu_id));
            $this->cw_id = htmlspecialchars(strip_tags($this->cw_id));
            $this->cant = htmlspecialchars(strip_tags($this->cant));
            $this->precio_productivo   = htmlspecialchars(strip_tags($this->precio_productivo));
            $this->costo_total = htmlspecialchars(strip_tags($this->costo_total));

            // Bind data
            $stmt->bindParam(':proj_id', $this->proj_id);
            $stmt->bindParam(':apu_id', $this->apu_id);
            $stmt->bindParam(':cw_id', $this->cw_id);
            $stmt->bindParam(':cant', $this->cant);
            $stmt->bindParam(':precio_productivo', $this->precio_productivo);
            $stmt->bindParam(':costo_total', $this->costo_total);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE APU-Workers */
        public function delete() {
            // Create Query
            $query = 'DELETE FROM '.$this->table.' 
                        WHERE projID = :proj_id
                        AND apuID = :apu_id 
                        AND cwID = :cw_id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->proj_id = htmlspecialchars(strip_tags($this->proj_id));
            $this->apu_id  = htmlspecialchars(strip_tags($this->apu_id));
            $this->cw_id = htmlspecialchars(strip_tags($this->cw_id));

            // Bind userID
            $stmt->bindParam(':proj_id', $this->proj_id);
            $stmt->bindParam(':apu_id', $this->apu_id);
            $stmt->bindParam(':cw_id', $this->cw_id);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

    }

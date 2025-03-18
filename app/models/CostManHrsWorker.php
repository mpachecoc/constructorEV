<?php

    class CostManHrsWorker {
        // DB Conn
        private $conn;
        private $table = 'cost_man_hrs_worker';

        // Cost Man Hrs. Worker Properties
        public $id;
        public $proj_id;
        public $worker_id;
        public $basico;
        public $bono;
        public $epp;
        public $cant;
        public $transporte;
        public $gasto_mensual_tot;
        public $bs_x_hr;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Cost Man Hrs. Worker */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY cwID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single Cost Man Hrs. Worker */
        public function read_single() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE cwID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind 'Cost Man Hrs.' ID
            $stmt->bindParam(1, $this->id);

            // Execute query
            $stmt->execute();

            $this->exists = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->proj_id = $row['projID'];
            $this->worker_id = $row['workerID'];
            $this->basico = $row['cwBasico'];
            $this->bono = $row['cwBono'];
            $this->epp  = $row['cwEPP'];
            $this->cant = $row['cwCantidad'];
            $this->transporte = $row['cwTransporte'];
            $this->gasto_mensual_tot = $row['cwGastoMensualTotal'];
            $this->bs_x_hr = $row['cwBsXHr'];
        }

        /** GET Cost Man Hrs. Worker By Project ID */
        public function read_by_project() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE projID = ? ORDER BY cwID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind projID
            $stmt->bindParam(1, $this->proj_id);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** CREATE a Cost Man Hrs. Worker */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      projID = :proj_id, 
                      workerID = :worker_id, 
                      cwBasico = :basico,
                      cwBono = :bono,
                      cwEPP  = :epp,
                      cwCantidad = :cant,
                      cwTransporte = :transporte,
                      cwGastoMensualTotal = :gasto_mensual_tot,
                      cwBsXHr = :bs_x_hr';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->proj_id = htmlspecialchars(strip_tags($this->proj_id));
            $this->worker_id = htmlspecialchars(strip_tags($this->worker_id));
            $this->basico = htmlspecialchars(strip_tags($this->basico));
            $this->bono = htmlspecialchars(strip_tags($this->bono));
            $this->epp = htmlspecialchars(strip_tags($this->epp));
            $this->cant = htmlspecialchars(strip_tags($this->cant));
            $this->transporte = htmlspecialchars(strip_tags($this->transporte));
            $this->gasto_mensual_tot = htmlspecialchars(strip_tags($this->gasto_mensual_tot));
            $this->bs_x_hr = htmlspecialchars(strip_tags($this->bs_x_hr));

            // Bind data
            $stmt->bindParam(':proj_id', $this->proj_id);
            $stmt->bindParam(':worker_id', $this->worker_id);
            $stmt->bindParam(':basico', $this->basico);
            $stmt->bindParam(':bono', $this->bono);
            $stmt->bindParam(':epp', $this->epp);
            $stmt->bindParam(':cant', $this->cant);
            $stmt->bindParam(':transporte', $this->transporte);
            $stmt->bindParam(':gasto_mensual_tot', $this->gasto_mensual_tot);
            $stmt->bindParam(':bs_x_hr', $this->bs_x_hr);

            // Execute query
            if ($stmt->execute()) {
                // As ID inserted is auto-increment, get it after execute query
                $this->id = $this->conn->lastInsertId();
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE a Cost Man Hrs. Worker */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                      projID = :proj_id, 
                      workerID = :worker_id, 
                      cwBasico = :basico,
                      cwBono = :bono,
                      cwEPP  = :epp,
                      cwCantidad = :cant,
                      cwTransporte = :transporte,
                      cwGastoMensualTotal = :gasto_mensual_tot,
                      cwBsXHr = :bs_x_hr
                    WHERE 
                      cwID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->proj_id = htmlspecialchars(strip_tags($this->proj_id));
            $this->worker_id = htmlspecialchars(strip_tags($this->worker_id));
            $this->basico = htmlspecialchars(strip_tags($this->basico));
            $this->bono = htmlspecialchars(strip_tags($this->bono));
            $this->epp  = htmlspecialchars(strip_tags($this->epp));
            $this->cant = htmlspecialchars(strip_tags($this->cant));
            $this->transporte = htmlspecialchars(strip_tags($this->transporte));
            $this->gasto_mensual_tot = htmlspecialchars(strip_tags($this->gasto_mensual_tot));
            $this->bs_x_hr = htmlspecialchars(strip_tags($this->bs_x_hr));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':proj_id', $this->proj_id);
            $stmt->bindParam(':worker_id', $this->worker_id);
            $stmt->bindParam(':basico', $this->basico);
            $stmt->bindParam(':bono', $this->bono);
            $stmt->bindParam(':epp', $this->epp);
            $stmt->bindParam(':cant', $this->cant);
            $stmt->bindParam(':transporte', $this->transporte);
            $stmt->bindParam(':gasto_mensual_tot', $this->gasto_mensual_tot);
            $stmt->bindParam(':bs_x_hr', $this->bs_x_hr);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE Cost Man Hrs. Worker */
        public function delete() {
            // Create Query
            $query = 'DELETE FROM '.$this->table.' WHERE cwID = :id';

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

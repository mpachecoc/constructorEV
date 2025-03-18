<?php

    class SocialBenefits {
        // DB Conn
        private $conn;
        private $table = 'social_benefits_percentage';

        // Benefits Properties
        public $id;
        public $dias_paga_x_ano;
        public $dias_paga_x_apatronal;
        public $dias_paga_x_bonos;
        public $total;
        public $tot_dias_paga;
        public $tot_dias_habiles;
        public $tot_dias_paga_sin_trab;
        public $porcentaje_carga_social;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Benefits */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY projID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single Benefit */
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
            $this->dias_paga_x_ano = $row['socialDiasPagadosXAno'];
            $this->dias_paga_x_apatronal = $row['socialDiasPagadosXApPatronal'];
            $this->dias_paga_x_bonos = $row['socialDiasPagadosXBonos'];
            $this->total = $row['socialTotal'];
            $this->tot_dias_paga = $row['socialTotalDiasPagados'];
            $this->tot_dias_habiles = $row['socialTotalDiasHabiles'];
            $this->tot_dias_paga_sin_trab  = $row['socialTotalDiasPagadosSinTrabajar'];
            $this->porcentaje_carga_social = $row['socialPorcentajeDeCargaSocial'];
        }

        /** CREATE a Benefit */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      projID = :id, 
                      socialDiasPagadosXAno = :dias_paga_x_ano, 
                      socialDiasPagadosXApPatronal = :dias_paga_x_apatronal,
                      socialDiasPagadosXBonos = :dias_paga_x_bonos,
                      socialTotal = :total,
                      socialTotalDiasPagados = :tot_dias_paga,
                      socialTotalDiasHabiles = :tot_dias_habiles,
                      socialTotalDiasPagadosSinTrabajar = :tot_dias_paga_sin_trab,
                      socialPorcentajeDeCargaSocial = :porcentaje_carga_social';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->dias_paga_x_ano = htmlspecialchars(strip_tags($this->dias_paga_x_ano));
            $this->dias_paga_x_apatronal = htmlspecialchars(strip_tags($this->dias_paga_x_apatronal));
            $this->dias_paga_x_bonos = htmlspecialchars(strip_tags($this->dias_paga_x_bonos));
            $this->total = htmlspecialchars(strip_tags($this->total));
            $this->tot_dias_paga  = htmlspecialchars(strip_tags($this->tot_dias_paga));
            $this->tot_dias_habiles = htmlspecialchars(strip_tags($this->tot_dias_habiles));
            $this->tot_dias_paga_sin_trab  = htmlspecialchars(strip_tags($this->tot_dias_paga_sin_trab));
            $this->porcentaje_carga_social = htmlspecialchars(strip_tags($this->porcentaje_carga_social));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':dias_paga_x_ano', $this->dias_paga_x_ano);
            $stmt->bindParam(':dias_paga_x_apatronal', $this->dias_paga_x_apatronal);
            $stmt->bindParam(':dias_paga_x_bonos', $this->dias_paga_x_bonos);
            $stmt->bindParam(':total', $this->total);
            $stmt->bindParam(':tot_dias_paga', $this->tot_dias_paga);
            $stmt->bindParam(':tot_dias_habiles', $this->tot_dias_habiles);
            $stmt->bindParam(':tot_dias_paga_sin_trab', $this->tot_dias_paga_sin_trab);
            $stmt->bindParam(':porcentaje_carga_social', $this->porcentaje_carga_social);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE a Benefit */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                      socialDiasPagadosXAno = :dias_paga_x_ano, 
                      socialDiasPagadosXApPatronal = :dias_paga_x_apatronal,
                      socialDiasPagadosXBonos = :dias_paga_x_bonos,
                      socialTotal = :total,
                      socialTotalDiasPagados = :tot_dias_paga,
                      socialTotalDiasHabiles = :tot_dias_habiles,
                      socialTotalDiasPagadosSinTrabajar = :tot_dias_paga_sin_trab,
                      socialPorcentajeDeCargaSocial = :porcentaje_carga_social
                    WHERE 
                      projID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->dias_paga_x_ano = htmlspecialchars(strip_tags($this->dias_paga_x_ano));
            $this->dias_paga_x_apatronal = htmlspecialchars(strip_tags($this->dias_paga_x_apatronal));
            $this->dias_paga_x_bonos = htmlspecialchars(strip_tags($this->dias_paga_x_bonos));
            $this->total = htmlspecialchars(strip_tags($this->total));
            $this->tot_dias_paga  = htmlspecialchars(strip_tags($this->tot_dias_paga));
            $this->tot_dias_habiles = htmlspecialchars(strip_tags($this->tot_dias_habiles));
            $this->tot_dias_paga_sin_trab  = htmlspecialchars(strip_tags($this->tot_dias_paga_sin_trab));
            $this->porcentaje_carga_social = htmlspecialchars(strip_tags($this->porcentaje_carga_social));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':dias_paga_x_ano', $this->dias_paga_x_ano);
            $stmt->bindParam(':dias_paga_x_apatronal', $this->dias_paga_x_apatronal);
            $stmt->bindParam(':dias_paga_x_bonos', $this->dias_paga_x_bonos);
            $stmt->bindParam(':total', $this->total);
            $stmt->bindParam(':tot_dias_paga', $this->tot_dias_paga);
            $stmt->bindParam(':tot_dias_habiles', $this->tot_dias_habiles);
            $stmt->bindParam(':tot_dias_paga_sin_trab', $this->tot_dias_paga_sin_trab);
            $stmt->bindParam(':porcentaje_carga_social', $this->porcentaje_carga_social);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE Benefit */
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
























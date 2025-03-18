<?php

    class ProjCostManHrsData {
        // DB Conn
        private $conn;
        private $table = 'project_cost_man_hrs_data';

        // Project Cost Man Hrs Properties
        public $id;
        public $hrs_trab_x_dia;
        public $hrs_mes_x_persona;
        public $hrs_trabajadas_mes;
        public $relac_gastos_hrs_trab;
        public $comida_completa_dia;
        public $total_mensual;
        public $coef_ap_patronales;
        public $coef_aguinaldo_liq;
        public $coef_desc_afp;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Project Cost Man Hrs */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY projID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single Project Cost Man Hrs */
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
            $this->hrs_trab_x_dia     = $row['projHrsTrabajoXDia'];
            $this->hrs_mes_x_persona  = $row['projHrsMesXPersona'];
            $this->hrs_trabajadas_mes = $row['projHrsTrabajadasMes'];
            $this->relac_gastos_hrs_trab = $row['projRelacGastosHrsTrabajadas'];
            $this->comida_completa_dia = $row['projComidaCompletaDiaBs'];
            $this->total_mensual = $row['projTotalMensual'];
            $this->coef_ap_patronales = $row['projCoeficienteAportesPatronales'];
            $this->coef_aguinaldo_liq = $row['projCoeficienteAguinaldoLiquidacion'];
            $this->coef_desc_afp = $row['projCoeficienteDescAFP'];
        }

        /** CREATE a Project Cost Man Hrs */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      projID = :id, 
                      projHrsTrabajoXDia   = :hrs_trab_x_dia, 
                      projHrsMesXPersona   = :hrs_mes_x_persona,
                      projHrsTrabajadasMes = :hrs_trabajadas_mes,
                      projRelacGastosHrsTrabajadas = :relac_gastos_hrs_trab,
                      projComidaCompletaDiaBs = :comida_completa_dia,
                      projTotalMensual = :total_mensual,
                      projCoeficienteAportesPatronales = :coef_ap_patronales,
                      projCoeficienteAguinaldoLiquidacion = :coef_aguinaldo_liq,
                      projCoeficienteDescAFP = :coef_desc_afp';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->hrs_trab_x_dia = htmlspecialchars(strip_tags($this->hrs_trab_x_dia));
            $this->hrs_mes_x_persona = htmlspecialchars(strip_tags($this->hrs_mes_x_persona));
            $this->hrs_trabajadas_mes = htmlspecialchars(strip_tags($this->hrs_trabajadas_mes));
            $this->relac_gastos_hrs_trab = htmlspecialchars(strip_tags($this->relac_gastos_hrs_trab));
            $this->comida_completa_dia = htmlspecialchars(strip_tags($this->comida_completa_dia));
            $this->total_mensual = htmlspecialchars(strip_tags($this->total_mensual));
            $this->coef_ap_patronales = htmlspecialchars(strip_tags($this->coef_ap_patronales));
            $this->coef_aguinaldo_liq = htmlspecialchars(strip_tags($this->coef_aguinaldo_liq));
            $this->coef_desc_afp = htmlspecialchars(strip_tags($this->coef_desc_afp));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':hrs_trab_x_dia', $this->hrs_trab_x_dia);
            $stmt->bindParam(':hrs_mes_x_persona', $this->hrs_mes_x_persona);
            $stmt->bindParam(':hrs_trabajadas_mes', $this->hrs_trabajadas_mes);
            $stmt->bindParam(':relac_gastos_hrs_trab', $this->relac_gastos_hrs_trab);
            $stmt->bindParam(':comida_completa_dia', $this->comida_completa_dia);
            $stmt->bindParam(':total_mensual', $this->total_mensual);
            $stmt->bindParam(':coef_ap_patronales', $this->coef_ap_patronales);
            $stmt->bindParam(':coef_aguinaldo_liq', $this->coef_aguinaldo_liq);
            $stmt->bindParam(':coef_desc_afp', $this->coef_desc_afp);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE a Project Cost Man Hrs */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                      projHrsTrabajoXDia  = :hrs_trab_x_dia, 
                      projHrsMesXPersona = :hrs_mes_x_persona,
                      projHrsTrabajadasMes = :hrs_trabajadas_mes,
                      projRelacGastosHrsTrabajadas = :relac_gastos_hrs_trab,
                      projComidaCompletaDiaBs = :comida_completa_dia,
                      projTotalMensual = :total_mensual,
                      projCoeficienteAportesPatronales = :coef_ap_patronales,
                      projCoeficienteAguinaldoLiquidacion = :coef_aguinaldo_liq,
                      projCoeficienteDescAFP = :coef_desc_afp
                    WHERE 
                      projID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->hrs_trab_x_dia = htmlspecialchars(strip_tags($this->hrs_trab_x_dia));
            $this->hrs_mes_x_persona = htmlspecialchars(strip_tags($this->hrs_mes_x_persona));
            $this->hrs_trabajadas_mes = htmlspecialchars(strip_tags($this->hrs_trabajadas_mes));
            $this->relac_gastos_hrs_trab = htmlspecialchars(strip_tags($this->relac_gastos_hrs_trab));
            $this->comida_completa_dia = htmlspecialchars(strip_tags($this->comida_completa_dia));
            $this->total_mensual = htmlspecialchars(strip_tags($this->total_mensual));
            $this->coef_ap_patronales = htmlspecialchars(strip_tags($this->coef_ap_patronales));
            $this->coef_aguinaldo_liq = htmlspecialchars(strip_tags($this->coef_aguinaldo_liq));
            $this->coef_desc_afp = htmlspecialchars(strip_tags($this->coef_desc_afp));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':hrs_trab_x_dia', $this->hrs_trab_x_dia);
            $stmt->bindParam(':hrs_mes_x_persona', $this->hrs_mes_x_persona);
            $stmt->bindParam(':hrs_trabajadas_mes', $this->hrs_trabajadas_mes);
            $stmt->bindParam(':relac_gastos_hrs_trab', $this->relac_gastos_hrs_trab);
            $stmt->bindParam(':comida_completa_dia', $this->comida_completa_dia);
            $stmt->bindParam(':total_mensual', $this->total_mensual);
            $stmt->bindParam(':coef_ap_patronales', $this->coef_ap_patronales);
            $stmt->bindParam(':coef_aguinaldo_liq', $this->coef_aguinaldo_liq);
            $stmt->bindParam(':coef_desc_afp', $this->coef_desc_afp);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE Project Cost Man Hrs */
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

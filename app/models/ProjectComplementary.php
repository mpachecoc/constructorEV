<?php

    class ProjectComplementary {
        // DB Conn
        private $conn;
        private $table = 'project_complementary';

        // Project Complementary Properties
        public $id;
        public $costo_herramientas;
        public $beneficios_sociales;
        public $gastos_generales;
        public $utilidad_costo_directo;
        public $iva;
        public $it;
        public $factor_de_paso;
        public $compra_sin_factura;
        public $exists; // Num of returned rows
        public $row_to_patch; // DB Row
        public $val_to_patch; // Value 

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Project Complementary */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY projID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single Project Complementary */
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
            $this->costo_herramientas  = $row['projCostoHerramientasManoDeObra'];
            $this->beneficios_sociales = $row['projBeneficiosSocialesManoDeObra'];
            $this->gastos_generales    = $row['projGastosGenerales'];
            $this->utilidad_costo_directo = $row['projUtilidadCostoDirecto'];
            $this->iva = $row['projIVA'];
            $this->it  = $row['projIT'];
            $this->factor_de_paso = $row['projFactorDePaso'];
            $this->compra_sin_factura = $row['projFactorReducCompraSinFactura'];
        }

        /** CREATE a Project Complementary */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      projID = :id, 
                      projCostoHerramientasManoDeObra  = :costo_herramientas, 
                      projBeneficiosSocialesManoDeObra = :beneficios_sociales,
                      projGastosGenerales = :gastos_generales,
                      projUtilidadCostoDirecto = :utilidad_costo_directo,
                      projIVA = :iva,
                      projIT  = :it,
                      projFactorDePaso = :factor_de_paso,
                      projFactorReducCompraSinFactura = :compra_sin_factura';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->costo_herramientas = htmlspecialchars(strip_tags($this->costo_herramientas));
            $this->beneficios_sociales = htmlspecialchars(strip_tags($this->beneficios_sociales));
            $this->gastos_generales   = htmlspecialchars(strip_tags($this->gastos_generales));
            $this->utilidad_costo_directo = htmlspecialchars(strip_tags($this->utilidad_costo_directo));
            $this->iva = htmlspecialchars(strip_tags($this->iva));
            $this->it = htmlspecialchars(strip_tags($this->it));
            $this->factor_de_paso = htmlspecialchars(strip_tags($this->factor_de_paso));
            $this->compra_sin_factura = htmlspecialchars(strip_tags($this->compra_sin_factura));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':costo_herramientas', $this->costo_herramientas);
            $stmt->bindParam(':beneficios_sociales', $this->beneficios_sociales);
            $stmt->bindParam(':gastos_generales', $this->gastos_generales);
            $stmt->bindParam(':utilidad_costo_directo', $this->utilidad_costo_directo);
            $stmt->bindParam(':iva', $this->iva);
            $stmt->bindParam(':it', $this->it);
            $stmt->bindParam(':factor_de_paso', $this->factor_de_paso);
            $stmt->bindParam(':compra_sin_factura', $this->compra_sin_factura);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE a Project Complementary */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                      projCostoHerramientasManoDeObra  = :costo_herramientas, 
                      projBeneficiosSocialesManoDeObra = :beneficios_sociales,
                      projGastosGenerales = :gastos_generales,
                      projUtilidadCostoDirecto = :utilidad_costo_directo,
                      projIVA = :iva,
                      projIT  = :it,
                      projFactorDePaso = :factor_de_paso,
                      projFactorReducCompraSinFactura = :compra_sin_factura
                    WHERE 
                      projID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->costo_herramientas = htmlspecialchars(strip_tags($this->costo_herramientas));
            $this->beneficios_sociales = htmlspecialchars(strip_tags($this->beneficios_sociales));
            $this->gastos_generales   = htmlspecialchars(strip_tags($this->gastos_generales));
            $this->utilidad_costo_directo = htmlspecialchars(strip_tags($this->utilidad_costo_directo));
            $this->iva = htmlspecialchars(strip_tags($this->iva));
            $this->it = htmlspecialchars(strip_tags($this->it));
            $this->factor_de_paso = htmlspecialchars(strip_tags($this->factor_de_paso));
            $this->compra_sin_factura = htmlspecialchars(strip_tags($this->compra_sin_factura));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':costo_herramientas', $this->costo_herramientas);
            $stmt->bindParam(':beneficios_sociales', $this->beneficios_sociales);
            $stmt->bindParam(':gastos_generales', $this->gastos_generales);
            $stmt->bindParam(':utilidad_costo_directo', $this->utilidad_costo_directo);
            $stmt->bindParam(':iva', $this->iva);
            $stmt->bindParam(':it', $this->it);
            $stmt->bindParam(':factor_de_paso', $this->factor_de_paso);
            $stmt->bindParam(':compra_sin_factura', $this->compra_sin_factura);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE Project Complementary */
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

        /** PATCH (update) a Project Complementary */
        public function update_single() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                     '.$this->row_to_patch.' = :val
                    WHERE 
                      projID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->val_to_patch = htmlspecialchars(strip_tags($this->val_to_patch));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':val', $this->val_to_patch);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

    }
























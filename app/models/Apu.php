<?php

    class Apu {
        // DB Conn
        private $conn;
        private $table = 'apu';

        // APU Properties
        public $id;
        public $project_id;
        public $actividad;
        public $unidad;
        public $cant;
        public $moneda;
        public $tot_materiales;
        public $tot_mano_de_obra;
        public $tot_equipo;
        public $tot_gastos_gral_admin;
        public $tot_utilidad;
        public $tot_impuestos;
        public $tot_precio_unitario;
        public $group_id;
        public $exists; // Num of returned rows
        public $row_to_patch; // DB Row
        public $val_to_patch; // Value 

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET APUs */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY apuID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single APU */
        public function read_single() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE apuID = ? AND projID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind APU ID
            $stmt->bindParam(1, $this->id);
            $stmt->bindParam(2, $this->project_id);

            // Execute query
            $stmt->execute();

            $this->exists = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->actividad = $row['apuActividad'];
            $this->unidad = $row['apuUnidad'];
            $this->cant = $row['apuCant'];
            $this->moneda = $row['apuMoneda'];
            $this->tot_materiales = $row['apuTotalMateriales'];
            $this->tot_mano_de_obra = $row['apuTotalManoDeObra'];
            $this->tot_equipo = $row['apuTotalEquipoMaquinaria'];
            $this->tot_gastos_gral_admin = $row['apuTotalGastosGeneralesAdmin'];
            $this->tot_utilidad = $row['apuTotalUtilidad'];
            $this->tot_impuestos = $row['apuTotalImpuestos'];
            $this->tot_precio_unitario = $row['apuTotalPrecioUnitario'];
            $this->group_id = $row['groupID'];
        }

        /** GET APUs by Project ID */
        public function read_by_project() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE projID = ? ORDER BY apuID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind projID
            $stmt->bindParam(1, $this->project_id);

            // Execute query
            $stmt->execute();

            return $stmt;
        }
        
        /** GET APUs by Group ID */
        public function read_by_group() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE groupID = ? ORDER BY apuID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind groupID
            $stmt->bindParam(1, $this->group_id);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** CREATE an APU */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      apuID = :id, 
                      projID = :project_id, 
                      apuActividad = :actividad,
                      apuUnidad = :unidad,
                      apuCant = :cant, 
                      apuMoneda = :moneda,
                      apuTotalMateriales = :tot_materiales,
                      apuTotalManoDeObra = :tot_mano_de_obra,
                      apuTotalEquipoMaquinaria = :tot_equipo,
                      apuTotalGastosGeneralesAdmin = :tot_gastos_gral_admin,
                      apuTotalUtilidad = :tot_utilidad,
                      apuTotalImpuestos = :tot_impuestos,
                      apuTotalPrecioUnitario = :tot_precio_unitario';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->project_id = htmlspecialchars(strip_tags($this->project_id));
            $this->actividad = htmlspecialchars(strip_tags($this->actividad));
            $this->unidad   = htmlspecialchars(strip_tags($this->unidad));
            $this->cant = htmlspecialchars(strip_tags($this->cant));
            $this->moneda = htmlspecialchars(strip_tags($this->moneda));
            $this->tot_materiales = htmlspecialchars(strip_tags($this->tot_materiales));
            $this->tot_mano_de_obra = htmlspecialchars(strip_tags($this->tot_mano_de_obra));
            $this->tot_equipo = htmlspecialchars(strip_tags($this->tot_equipo));
            $this->tot_gastos_gral_admin = htmlspecialchars(strip_tags($this->tot_gastos_gral_admin));
            $this->tot_utilidad = htmlspecialchars(strip_tags($this->tot_utilidad));
            $this->tot_impuestos = htmlspecialchars(strip_tags($this->tot_impuestos));
            $this->tot_precio_unitario = htmlspecialchars(strip_tags($this->tot_precio_unitario));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':project_id', $this->project_id);
            $stmt->bindParam(':actividad', $this->actividad);
            $stmt->bindParam(':unidad', $this->unidad);
            $stmt->bindParam(':cant', $this->cant);
            $stmt->bindParam(':moneda', $this->moneda);
            $stmt->bindParam(':tot_materiales', $this->tot_materiales);
            $stmt->bindParam(':tot_mano_de_obra', $this->tot_mano_de_obra);
            $stmt->bindParam(':tot_equipo', $this->tot_equipo);
            $stmt->bindParam(':tot_gastos_gral_admin', $this->tot_gastos_gral_admin);
            $stmt->bindParam(':tot_utilidad', $this->tot_utilidad);
            $stmt->bindParam(':tot_impuestos', $this->tot_impuestos);
            $stmt->bindParam(':tot_precio_unitario', $this->tot_precio_unitario);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE an APU */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET                     
                      apuActividad = :actividad,
                      apuUnidad = :unidad,
                      apuCant = :cant, 
                      apuMoneda = :moneda,
                      apuTotalMateriales = :tot_materiales,
                      apuTotalManoDeObra = :tot_mano_de_obra,
                      apuTotalEquipoMaquinaria = :tot_equipo,
                      apuTotalGastosGeneralesAdmin = :tot_gastos_gral_admin,
                      apuTotalUtilidad = :tot_utilidad,
                      apuTotalImpuestos = :tot_impuestos,
                      apuTotalPrecioUnitario = :tot_precio_unitario
                    WHERE
                      projID = :project_id
                    AND 
                      apuID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->project_id = htmlspecialchars(strip_tags($this->project_id));
            $this->actividad = htmlspecialchars(strip_tags($this->actividad));
            $this->unidad   = htmlspecialchars(strip_tags($this->unidad));
            $this->cant = htmlspecialchars(strip_tags($this->cant));
            $this->moneda = htmlspecialchars(strip_tags($this->moneda));
            $this->tot_materiales = htmlspecialchars(strip_tags($this->tot_materiales));
            $this->tot_mano_de_obra = htmlspecialchars(strip_tags($this->tot_mano_de_obra));
            $this->tot_equipo = htmlspecialchars(strip_tags($this->tot_equipo));
            $this->tot_gastos_gral_admin = htmlspecialchars(strip_tags($this->tot_gastos_gral_admin));
            $this->tot_utilidad = htmlspecialchars(strip_tags($this->tot_utilidad));
            $this->tot_impuestos = htmlspecialchars(strip_tags($this->tot_impuestos));
            $this->tot_precio_unitario = htmlspecialchars(strip_tags($this->tot_precio_unitario));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':project_id', $this->project_id);
            $stmt->bindParam(':actividad', $this->actividad);
            $stmt->bindParam(':unidad', $this->unidad);
            $stmt->bindParam(':cant', $this->cant);
            $stmt->bindParam(':moneda', $this->moneda);
            $stmt->bindParam(':tot_materiales', $this->tot_materiales);
            $stmt->bindParam(':tot_mano_de_obra', $this->tot_mano_de_obra);
            $stmt->bindParam(':tot_equipo', $this->tot_equipo);
            $stmt->bindParam(':tot_gastos_gral_admin', $this->tot_gastos_gral_admin);
            $stmt->bindParam(':tot_utilidad', $this->tot_utilidad);
            $stmt->bindParam(':tot_impuestos', $this->tot_impuestos);
            $stmt->bindParam(':tot_precio_unitario', $this->tot_precio_unitario);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE APU */
        public function delete() {
            // Create Query
            $query = 'DELETE FROM '.$this->table.' WHERE apuID = :id AND projID = :proj_id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->project_id = htmlspecialchars(strip_tags($this->project_id));

            // Bind userID
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':proj_id', $this->project_id);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        /** PATCH (update) an APU */
        public function update_single() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                     '.$this->row_to_patch.' = :val
                    WHERE
                     projID = :project_id
                    AND
                     apuID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->project_id = htmlspecialchars(strip_tags($this->project_id));
            $this->val_to_patch = htmlspecialchars(strip_tags($this->val_to_patch));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':project_id', $this->project_id);
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
























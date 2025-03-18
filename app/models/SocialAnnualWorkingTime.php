<?php

    class SocialAnnualWorkingTime {
        // DB Conn
        private $conn;
        private $table = 'social_anual_working_time';

        // Annual Working Properties
        public $id;
        public $year_days;
        public $inactividad;
        public $vacaciones;
        public $feriados;
        public $lluvias;
        public $enfermedades;
        public $dias_no_trab;
        public $subtotal;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Annual Working */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY projID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single Annual Working */
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
            $this->year_days = $row['anualYearDays'];
            $this->inactividad = $row['anualInactividad'];
            $this->vacaciones  = $row['anualVacaciones'];
            $this->feriados = $row['anualFeriados'];
            $this->lluvias  = $row['anualLluvias'];
            $this->enfermedades = $row['anualEnfermedades'];
            $this->dias_no_trab = $row['anualDiasNoTrabajados'];
            $this->subtotal = $row['anualSubtotal'];
        }

        /** CREATE an Annual Working */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      projID = :id, 
                      anualYearDays = :year_days, 
                      anualInactividad = :inactividad,
                      anualVacaciones  = :vacaciones,
                      anualFeriados = :feriados,
                      anualLluvias  = :lluvias,
                      anualEnfermedades = :enfermedades,
                      anualDiasNoTrabajados = :dias_no_trab,
                      anualSubtotal = :subtotal';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->year_days = htmlspecialchars(strip_tags($this->year_days));
            $this->inactividad = htmlspecialchars(strip_tags($this->inactividad));
            $this->vacaciones  = htmlspecialchars(strip_tags($this->vacaciones));
            $this->feriados = htmlspecialchars(strip_tags($this->feriados));
            $this->lluvias  = htmlspecialchars(strip_tags($this->lluvias));
            $this->enfermedades = htmlspecialchars(strip_tags($this->enfermedades));
            $this->dias_no_trab = htmlspecialchars(strip_tags($this->dias_no_trab));
            $this->subtotal  = htmlspecialchars(strip_tags($this->subtotal));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':year_days', $this->year_days);
            $stmt->bindParam(':inactividad', $this->inactividad);
            $stmt->bindParam(':vacaciones', $this->vacaciones);
            $stmt->bindParam(':feriados', $this->feriados);
            $stmt->bindParam(':lluvias', $this->lluvias);
            $stmt->bindParam(':enfermedades', $this->enfermedades);
            $stmt->bindParam(':dias_no_trab', $this->dias_no_trab);
            $stmt->bindParam(':subtotal', $this->subtotal);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE an Annual Working */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                      anualYearDays = :year_days, 
                      anualInactividad = :inactividad,
                      anualVacaciones  = :vacaciones,
                      anualFeriados = :feriados,
                      anualLluvias  = :lluvias,
                      anualEnfermedades = :enfermedades,
                      anualDiasNoTrabajados = :dias_no_trab,
                      anualSubtotal = :subtotal
                    WHERE 
                      projID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->year_days = htmlspecialchars(strip_tags($this->year_days));
            $this->inactividad = htmlspecialchars(strip_tags($this->inactividad));
            $this->vacaciones  = htmlspecialchars(strip_tags($this->vacaciones));
            $this->feriados = htmlspecialchars(strip_tags($this->feriados));
            $this->lluvias  = htmlspecialchars(strip_tags($this->lluvias));
            $this->enfermedades = htmlspecialchars(strip_tags($this->enfermedades));
            $this->dias_no_trab = htmlspecialchars(strip_tags($this->dias_no_trab));
            $this->subtotal  = htmlspecialchars(strip_tags($this->subtotal));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':year_days', $this->year_days);
            $stmt->bindParam(':inactividad', $this->inactividad);
            $stmt->bindParam(':vacaciones', $this->vacaciones);
            $stmt->bindParam(':feriados', $this->feriados);
            $stmt->bindParam(':lluvias', $this->lluvias);
            $stmt->bindParam(':enfermedades', $this->enfermedades);
            $stmt->bindParam(':dias_no_trab', $this->dias_no_trab);
            $stmt->bindParam(':subtotal', $this->subtotal);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE Annual Working */
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
























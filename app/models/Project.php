<?php

    class Project {
        // DB Conn
        private $conn;
        private $table = 'project';

        // Project Properties
        public $id;
        public $name;
        public $date_created;
        public $created_by;
        public $location;
        public $elaboration_date;
        public $presup_date;
        public $end_date;
        public $calendar_days;
        public $contract_num;
        public $duration;
        public $currency;
        public $exchange_rate;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Projects */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY projID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single Project */
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
            $this->name = $row['projName'];
            $this->date_created = $row['projDateCreated'];
            $this->created_by   = $row['projCreatedBy'];
            $this->location = $row['projLocation'];
            $this->elaboration_date = $row['projElaborationDate'];
            $this->presup_date = $row['projPresupDate'];
            $this->end_date = $row['projEndDate'];
            $this->calendar_days = $row['projCalendarDays'];
            $this->contract_num = $row['projContractNum'];
            $this->duration = $row['projDuration'];
            $this->currency = $row['projCurrency'];
            $this->exchange_rate = $row['projExchangeRate'];
        }

        /** CREATE a Project */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      projID = :id, 
                      projName = :name, 
                      projDateCreated = :date_created,
                      projCreatedBy = :created_by,
                      projLocation = :location,
                      projElaborationDate = :elaboration_date,
                      projPresupDate = :presup_date,
                      projEndDate = :end_date,
                      projCalendarDays = :calendar_days,
                      projContractNum = :contract_num,
                      projDuration  = :duration,
                      projCurrency  = :currency,
                      projExchangeRate = :exchange_rate';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->date_created = htmlspecialchars(strip_tags($this->date_created));
            $this->created_by   = htmlspecialchars(strip_tags($this->created_by));
            $this->location = htmlspecialchars(strip_tags($this->location));
            $this->elaboration_date = htmlspecialchars(strip_tags($this->elaboration_date));
            $this->presup_date = htmlspecialchars(strip_tags($this->presup_date));
            $this->end_date = htmlspecialchars(strip_tags($this->end_date));
            $this->calendar_days = htmlspecialchars(strip_tags($this->calendar_days));
            $this->contract_num = htmlspecialchars(strip_tags($this->contract_num));
            $this->duration = htmlspecialchars(strip_tags($this->duration));
            $this->currency = htmlspecialchars(strip_tags($this->currency));
            $this->exchange_rate = htmlspecialchars(strip_tags($this->exchange_rate));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':date_created', $this->date_created);
            $stmt->bindParam(':created_by', $this->created_by);
            $stmt->bindParam(':location', $this->location);
            $stmt->bindParam(':elaboration_date', $this->elaboration_date);
            $stmt->bindParam(':presup_date', $this->presup_date);
            $stmt->bindParam(':end_date', $this->end_date);
            $stmt->bindParam(':calendar_days', $this->calendar_days);
            $stmt->bindParam(':contract_num', $this->contract_num);
            $stmt->bindParam(':duration', $this->duration);
            $stmt->bindParam(':currency', $this->currency);
            $stmt->bindParam(':exchange_rate', $this->exchange_rate);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE a Project */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                      projName = :name, 
                      projDateCreated = :date_created,
                      projCreatedBy = :created_by,
                      projLocation = :location,
                      projElaborationDate = :elaboration_date,
                      projPresupDate = :presup_date,
                      projEndDate = :end_date,
                      projCalendarDays = :calendar_days,
                      projContractNum = :contract_num,
                      projDuration  = :duration,
                      projCurrency  = :currency,
                      projExchangeRate = :exchange_rate
                    WHERE 
                      projID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->date_created = htmlspecialchars(strip_tags($this->date_created));
            $this->created_by   = htmlspecialchars(strip_tags($this->created_by));
            $this->location = htmlspecialchars(strip_tags($this->location));
            $this->elaboration_date = htmlspecialchars(strip_tags($this->elaboration_date));
            $this->presup_date = htmlspecialchars(strip_tags($this->presup_date));
            $this->end_date = htmlspecialchars(strip_tags($this->end_date));
            $this->calendar_days = htmlspecialchars(strip_tags($this->calendar_days));
            $this->contract_num = htmlspecialchars(strip_tags($this->contract_num));
            $this->duration = htmlspecialchars(strip_tags($this->duration));
            $this->currency = htmlspecialchars(strip_tags($this->currency));
            $this->exchange_rate = htmlspecialchars(strip_tags($this->exchange_rate));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':date_created', $this->date_created);
            $stmt->bindParam(':created_by', $this->created_by);
            $stmt->bindParam(':location', $this->location);
            $stmt->bindParam(':elaboration_date', $this->elaboration_date);
            $stmt->bindParam(':presup_date', $this->presup_date);
            $stmt->bindParam(':end_date', $this->end_date);
            $stmt->bindParam(':calendar_days', $this->calendar_days);
            $stmt->bindParam(':contract_num', $this->contract_num);
            $stmt->bindParam(':duration', $this->duration);
            $stmt->bindParam(':currency', $this->currency);
            $stmt->bindParam(':exchange_rate', $this->exchange_rate);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE Project */
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
























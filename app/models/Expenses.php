<?php

    class Expenses {
        // DB Conn
        private $conn;
        private $table = 'expenses';

        // Expenses Properties
        public $id;
        public $proj_id;
        public $type;
        public $discharge;
        public $date;
        public $supplier;
        public $desc;
        public $item;
        public $sub_item;
        public $object;
        public $amount;
        public $number;
        public $invoice;
        public $origin;
        public $authorization;
        public $cond_1;
        public $cond_2;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Expenses */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY expenseID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single Expense */
        public function read_single() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE expenseID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind 'Expense' ID
            $stmt->bindParam(1, $this->id);

            // Execute query
            $stmt->execute();

            $this->exists = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->proj_id = $row['projID'];
            $this->type = $row['expenseType'];
            $this->discharge = $row['expDischarge'];
            $this->date = $row['expDate'];
            $this->supplier  = $row['expSupplier'];
            $this->desc = $row['expDesc'];
            $this->item = $row['expItem'];
            $this->sub_item = $row['expSubItem'];
            $this->object = $row['expObject'];
            $this->amount = $row['expAmount'];
            $this->number = $row['expNumber'];
            $this->invoice = $row['expInvoice'];
            $this->origin = $row['expOrigin'];
            $this->authorization = $row['expAuthorization'];
            $this->cond_1 = $row['expCond1'];
            $this->cond_2 = $row['expCond2'];
        }

        /** GET Expenses By Project ID */
        public function read_by_project() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE projID = ? ORDER BY expenseID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind projID
            $stmt->bindParam(1, $this->proj_id);

            // Execute query
            $stmt->execute();

            return $stmt;
        }
        
        /** GET Expenses By Group ID */
        public function read_by_group() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE expItem = ? ORDER BY expenseID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind group ID
            $stmt->bindParam(1, $this->item);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** CREATE a Expense */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      projID = :proj_id, 
                      expenseType = :type, 
                      expDischarge = :discharge,
                      expDate = :date,
                      expSupplier  = :supplier,
                      expDesc = :desc,
                      expItem = :item,
                      expSubItem = :sub_item,
                      expObject = :object,
                      expAmount = :amount,
                      expNumber = :number,
                      expInvoice = :invoice,
                      expOrigin = :origin,
                      expAuthorization = :authorization,
                      expCond1 = :cond_1,
                      expCond2 = :cond_2';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->proj_id = htmlspecialchars(strip_tags($this->proj_id));
            $this->type = htmlspecialchars(strip_tags($this->type));
            $this->discharge = htmlspecialchars(strip_tags($this->discharge));
            $this->date = htmlspecialchars(strip_tags($this->date));
            $this->supplier = htmlspecialchars(strip_tags($this->supplier));
            $this->desc = htmlspecialchars(strip_tags($this->desc));
            $this->item = htmlspecialchars(strip_tags($this->item));
            $this->sub_item = htmlspecialchars(strip_tags($this->sub_item));
            $this->object = htmlspecialchars(strip_tags($this->object));
            $this->amount = htmlspecialchars(strip_tags($this->amount));
            $this->number = htmlspecialchars(strip_tags($this->number));
            $this->invoice = htmlspecialchars(strip_tags($this->invoice));
            $this->origin = htmlspecialchars(strip_tags($this->origin));
            $this->authorization = htmlspecialchars(strip_tags($this->authorization));
            $this->cond_1 = htmlspecialchars(strip_tags($this->cond_1));
            $this->cond_2 = htmlspecialchars(strip_tags($this->cond_2));

            // Bind data
            $stmt->bindParam(':proj_id', $this->proj_id);
            $stmt->bindParam(':type', $this->type);
            $stmt->bindParam(':discharge', $this->discharge);
            $stmt->bindParam(':date', $this->date);
            $stmt->bindParam(':supplier', $this->supplier);
            $stmt->bindParam(':desc', $this->desc);
            $stmt->bindParam(':item', $this->item);
            $stmt->bindParam(':sub_item', $this->sub_item);
            $stmt->bindParam(':object', $this->object);
            $stmt->bindParam(':amount', $this->amount);
            $stmt->bindParam(':number', $this->number);
            $stmt->bindParam(':invoice', $this->invoice);
            $stmt->bindParam(':origin', $this->origin);
            $stmt->bindParam(':authorization', $this->authorization);
            $stmt->bindParam(':cond_1', $this->cond_1);
            $stmt->bindParam(':cond_2', $this->cond_2);

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

        /** UPDATE a Expense */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                        projID = :proj_id, 
                        expenseType = :type, 
                        expDischarge = :discharge,
                        expDate = :date,
                        expSupplier  = :supplier,
                        expDesc = :desc,
                        expItem = :item,
                        expSubItem = :sub_item,
                        expObject = :object,
                        expAmount = :amount,
                        expNumber = :number,
                        expInvoice = :invoice,
                        expOrigin = :origin,
                        expAuthorization = :authorization,
                        expCond1 = :cond_1,
                        expCond2 = :cond_2
                    WHERE 
                        expenseID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->proj_id = htmlspecialchars(strip_tags($this->proj_id));
            $this->type = htmlspecialchars(strip_tags($this->type));
            $this->discharge = htmlspecialchars(strip_tags($this->discharge));
            $this->date = htmlspecialchars(strip_tags($this->date));
            $this->supplier = htmlspecialchars(strip_tags($this->supplier));
            $this->desc = htmlspecialchars(strip_tags($this->desc));
            $this->item = htmlspecialchars(strip_tags($this->item));
            $this->sub_item = htmlspecialchars(strip_tags($this->sub_item));
            $this->object = htmlspecialchars(strip_tags($this->object));
            $this->amount = htmlspecialchars(strip_tags($this->amount));
            $this->number = htmlspecialchars(strip_tags($this->number));
            $this->invoice = htmlspecialchars(strip_tags($this->invoice));
            $this->origin = htmlspecialchars(strip_tags($this->origin));
            $this->authorization = htmlspecialchars(strip_tags($this->authorization));
            $this->cond_1 = htmlspecialchars(strip_tags($this->cond_1));
            $this->cond_2 = htmlspecialchars(strip_tags($this->cond_2));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':proj_id', $this->proj_id);
            $stmt->bindParam(':type', $this->type);
            $stmt->bindParam(':discharge', $this->discharge);
            $stmt->bindParam(':date', $this->date);
            $stmt->bindParam(':supplier', $this->supplier);
            $stmt->bindParam(':desc', $this->desc);
            $stmt->bindParam(':item', $this->item);
            $stmt->bindParam(':sub_item', $this->sub_item);
            $stmt->bindParam(':object', $this->object);
            $stmt->bindParam(':amount', $this->amount);
            $stmt->bindParam(':number', $this->number);
            $stmt->bindParam(':invoice', $this->invoice);
            $stmt->bindParam(':origin', $this->origin);
            $stmt->bindParam(':authorization', $this->authorization);
            $stmt->bindParam(':cond_1', $this->cond_1);
            $stmt->bindParam(':cond_2', $this->cond_2);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE Expense */
        public function delete() {
            // Create Query
            $query = 'DELETE FROM '.$this->table.' WHERE expenseID = :id';

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

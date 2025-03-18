<?php

    class User {
        // DB Conn
        private $conn;
        private $table = 'users';

        // User Properties
        public $id;
        public $username;
        public $password;
        public $rol;
        public $roles;
        public $date_created;
        public $exists; // Num of returned rows

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        /** GET Users */
        public function read() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' ORDER BY userID ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        /** GET single User */
        public function read_single() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE userID = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind userID
            $stmt->bindParam(1, $this->id);

            // Execute query
            $stmt->execute();

            $this->exists = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->username = $row['userName'];
            $this->password = $row['userPass'];
            $this->rol      = $row['userRole'];
            $this->roles    = $row['userRoles'];
            $this->date_created = $row['userDateIn'];
        }

        /** CREATE a User */
        public function create() {
            // Create Query
            $query = 'INSERT INTO '.$this->table.' SET 
                      userName = :username, 
                      userPass = :password,
                      userRole = :rol,
                      userRoles  = :roles,
                      userDateIn = :date_created';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->password = htmlspecialchars(strip_tags($this->password));
            $this->rol   = htmlspecialchars(strip_tags($this->rol));
            $this->roles = htmlspecialchars(strip_tags($this->roles));
            $this->date_created = htmlspecialchars(strip_tags($this->date_created));

            // Bind data
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':rol', $this->rol);
            $stmt->bindParam(':roles', $this->roles);
            $stmt->bindParam(':date_created', $this->date_created);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** UPDATE a User */
        public function update() {
            // Create Query
            $query = 'UPDATE '.$this->table.' SET 
                      userName = :username, 
                      userPass = :password,
                      userRole = :rol,
                      userRoles  = :roles,
                      userDateIn = :date_created
                    WHERE 
                      userID = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->password = htmlspecialchars(strip_tags($this->password));
            $this->rol   = htmlspecialchars(strip_tags($this->rol));
            $this->roles = htmlspecialchars(strip_tags($this->roles));
            $this->date_created = htmlspecialchars(strip_tags($this->date_created));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':rol', $this->rol);
            $stmt->bindParam(':roles', $this->roles);
            $stmt->bindParam(':date_created', $this->date_created);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if went wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        /** DELETE User */
        public function delete() {
            // Create Query
            $query = 'DELETE FROM '.$this->table.' WHERE userID = :id';

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

        /** Login User */
        public function login() {
            // Create Query
            $query = 'SELECT * FROM '.$this->table.' WHERE userName = :username AND userPass = :pass';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->password = htmlspecialchars(strip_tags($this->password));

            // Bind userID
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':pass', $this->password);

            // Execute query
            $stmt->execute();

            $this->exists = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->id  = $row['userID'];
            $this->rol = $row['userRole'];
            $this->roles = $row['userRoles'];
            $this->date_created = $row['userDateIn'];
        }

    }
























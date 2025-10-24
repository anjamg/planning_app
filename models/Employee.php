<?php
class Employee {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }
    public function getAll() {
        $query = "SELECT user_id AS id, 
                         COALESCE(userfullname, CONCAT(firstname, ' ', lastname)) AS name
                  FROM main_employees_summary WHERE isactive = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

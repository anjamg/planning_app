<?php
class Planning {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }
    public function getAllBetween($start, $end) {
        $query = "SELECT p.id, p.employee_id, p.start, p.end, p.comment,
                         e.userfullname AS employee_name
                  FROM planning p
                  JOIN main_employees e ON e.user_id = p.employee_id
                  WHERE p.start BETWEEN :start AND :end";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($data) {
        $query = "INSERT INTO planning (employee_id, start, end, comment)
                  VALUES (:employee_id, :start, :end, :comment)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        return $this->conn->lastInsertId();
    }
    public function update($data) {
        $query = "UPDATE planning SET employee_id=:employee_id, start=:start, end=:end, comment=:comment WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM planning WHERE id=:id");
        return $stmt->execute(['id'=>$id]);
    }
}
?>

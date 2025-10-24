<?php
header('Content-Type: application/json');
require_once '../config.php';
require_once '../models/Employee.php';

$db = new PDO($dsn, $user, $pass);
$model = new Employee($db);
echo json_encode($model->getAll());
?>

<?php
header('Content-Type: application/json');
require_once '../config.php';
require_once '../models/Planning.php';

$db = new PDO($dsn, $user, $pass);
$model = new Planning($db);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $events = $model->getAllBetween($_GET['start'], $_GET['end']);
        $formatted = array_map(function($e) {
            return [
                'id' => $e['id'],
                'title' => $e['employee_name'],
                'start' => $e['start'],
                'end' => $e['end'],
                'employee_id' => $e['employee_id'],
                'comment' => $e['comment']
            ];
        }, $events);
        echo json_encode($formatted);
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $model->create($data);
        echo json_encode(['status'=>'created']);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $model->update($data);
        echo json_encode(['status'=>'updated']);
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        $model->delete($data['id']);
        echo json_encode(['status'=>'deleted']);
        break;
}
?>
